<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\ItemTransaksi;
use App\Models\JenisProduk;
use App\Models\Produk;
use App\Models\RiwayatStokProduk;
use App\Models\UlasanRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class CartController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | RajaOngkir Configuration
    |--------------------------------------------------------------------------
    */

    private function rajaOngkirApiKey(): ?string
    {
        return config('services.rajaongkir.key');
    }

    private function rajaOngkirBaseUrl(): string
    {
        return rtrim(
            (string) config(
                'services.rajaongkir.base_url',
                'https://rajaongkir.komerce.id/api/v1'
            ),
            '/'
        );
    }

    private function originDistrict(): ?string
    {
        return config('services.rajaongkir.origin_district');
    }

    private function ensureRajaOngkirConfigured(): void
    {
        if (
            empty($this->rajaOngkirApiKey()) ||
            empty($this->originDistrict())
        ) {
            throw new RuntimeException(
                'Konfigurasi RajaOngkir belum tersedia.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Request shipping services directly from RajaOngkir
    |--------------------------------------------------------------------------
    */

    private function fetchShippingServices(
        string $destination,
        int $weight
    ): array {
        $this->ensureRajaOngkirConfigured();

        $response = Http::timeout(15)
            ->retry(2, 300)
            ->withHeaders([
                'key' => $this->rajaOngkirApiKey(),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])
            ->asForm()
            ->post(
                $this->rajaOngkirBaseUrl() . '/calculate/domestic-cost',
                [
                    'origin' => $this->originDistrict(),
                    'destination' => $destination,
                    'weight' => $weight,
                    'courier' => 'jnt',
                ]
            );

        if (!$response->successful()) {
            Log::warning('RajaOngkir shipping request failed', [
                'status' => $response->status(),
                'destination' => $destination,
                'weight' => $weight,
            ]);

            throw new RuntimeException(
                'Gagal mendapatkan informasi ongkos kirim.'
            );
        }

        $services = $response->json('data');

        if (!is_array($services) || count($services) === 0) {
            throw new RuntimeException(
                'Tidak ada layanan pengiriman yang tersedia.'
            );
        }

        return $services;
    }

    /*
    |--------------------------------------------------------------------------
    | Cart
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('shop-cart');
    }

    /*
    |--------------------------------------------------------------------------
    | Checkout
    |--------------------------------------------------------------------------
    */

    public function checkout()
    {
        $customer = Auth::user();

        return view('checkout', compact('customer'));
    }

    /*
    |--------------------------------------------------------------------------
    | Process Checkout
    |--------------------------------------------------------------------------
    |
    | Harga produk TIDAK dipercaya dari localStorage/browser.
    | Harga dibaca ulang dari database.
    |
    */

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string|max:1000',
            'destination' => 'required|string|max:100',
            'layanan_ongkir' => 'required|string|max:100',
            'cart_data' => 'required|json',
        ]);

        $cartData = json_decode(
            $validated['cart_data'],
            true
        );

        if (!is_array($cartData) || empty($cartData)) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang belanja kosong.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate cart structure
        |--------------------------------------------------------------------------
        */

        $totalWeight = 0;

        foreach ($cartData as $item) {
            if (
                !isset($item['id']) ||
                !isset($item['quantity']) ||
                !is_numeric($item['id']) ||
                !is_numeric($item['quantity'])
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data keranjang tidak valid.',
                ], 422);
            }

            $quantity = (int) $item['quantity'];

            if ($quantity < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah produk tidak valid.',
                ], 422);
            }

            /*
             * Saat ini sistem checkout mengasumsikan
             * satu item = 1000 gram.
             *
             * Nantinya jika database memiliki kolom berat,
             * gunakan berat asli produk dari database.
             */
            $totalWeight += $quantity * 1000;
        }

        /*
        |--------------------------------------------------------------------------
        | Verify shipping cost on server
        |--------------------------------------------------------------------------
        */

        try {
            $shippingServices = $this->fetchShippingServices(
                $validated['destination'],
                $totalWeight
            );
        } catch (Throwable $e) {
            Log::warning('Shipping verification failed', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        $selectedShipping = collect($shippingServices)
            ->first(function ($service) use ($validated) {
                return isset($service['service']) &&
                    strcasecmp(
                        (string) $service['service'],
                        $validated['layanan_ongkir']
                    ) === 0;
            });

        if (!$selectedShipping) {
            return response()->json([
                'success' => false,
                'message' => 'Layanan pengiriman tidak valid.',
            ], 422);
        }

        $shippingCost = (int) ($selectedShipping['cost'] ?? 0);

        if ($shippingCost < 0) {
            return response()->json([
                'success' => false,
                'message' => 'Ongkos kirim tidak valid.',
            ], 422);
        }

        $courierCode = strtoupper(
            (string) ($selectedShipping['code'] ?? 'JNT')
        );

        $shippingService = (string) $selectedShipping['service'];

        try {
            $invoice = DB::transaction(function () use (
                $cartData,
                $validated,
                $shippingCost,
                $courierCode,
                $shippingService
            ) {
                $processedItems = [];
                $subtotal = 0;

                /*
                |--------------------------------------------------------------------------
                | Validate products and prices from database
                |--------------------------------------------------------------------------
                */

                foreach ($cartData as $item) {
                    $productId = (int) $item['id'];
                    $quantity = (int) $item['quantity'];

                    $produk = Produk::query()
                        ->lockForUpdate()
                        ->find($productId);

                    if (!$produk) {
                        throw new RuntimeException(
                            "Produk dengan ID {$productId} tidak ditemukan."
                        );
                    }

                    $minimumPurchase = max(
                        1,
                        (int) ($produk->min_beli ?? 1)
                    );

                    if ($quantity < $minimumPurchase) {
                        throw new RuntimeException(
                            "Minimal pembelian produk '{$produk->nama}' adalah {$minimumPurchase}."
                        );
                    }

                    $jenisId = $item['jenis_id'] ?? null;

                    if (
                        $jenisId === '' ||
                        $jenisId === 'null' ||
                        $jenisId === null
                    ) {
                        $jenisId = null;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Product with variant
                    |--------------------------------------------------------------------------
                    */

                    if ($jenisId !== null) {
                        $jenisProduk = JenisProduk::query()
                            ->where(
                                'id_jenis_produk',
                                (int) $jenisId
                            )
                            ->where(
                                'produk_id',
                                $produk->id_produk
                            )
                            ->lockForUpdate()
                            ->first();

                        if (!$jenisProduk) {
                            throw new RuntimeException(
                                "Varian untuk produk '{$produk->nama}' tidak ditemukan."
                            );
                        }

                        if (
                            (int) $jenisProduk->jumlah_produk <
                            $quantity
                        ) {
                            throw new RuntimeException(
                                "Stok varian '{$jenisProduk->nama}' dari produk '{$produk->nama}' tidak mencukupi."
                            );
                        }

                        $unitPrice = (int) (
                            $jenisProduk->harga ??
                            $produk->harga ??
                            0
                        );

                        if ($unitPrice <= 0) {
                            throw new RuntimeException(
                                "Harga produk '{$produk->nama}' tidak valid."
                            );
                        }

                        $itemSubtotal = $unitPrice * $quantity;

                        $processedItems[] = [
                            'produk' => $produk,
                            'jenis_produk' => $jenisProduk,
                            'quantity' => $quantity,
                            'unit_price' => $unitPrice,
                            'subtotal' => $itemSubtotal,
                        ];

                        $subtotal += $itemSubtotal;

                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Product without variant
                    |--------------------------------------------------------------------------
                    */

                    $stock = (int) ($produk->jumlah_produk ?? 0);

                    if ($stock < $quantity) {
                        throw new RuntimeException(
                            "Stok produk '{$produk->nama}' tidak mencukupi."
                        );
                    }

                    $unitPrice = (int) ($produk->harga ?? 0);

                    if ($unitPrice <= 0) {
                        throw new RuntimeException(
                            "Harga produk '{$produk->nama}' tidak valid."
                        );
                    }

                    $itemSubtotal = $unitPrice * $quantity;

                    $processedItems[] = [
                        'produk' => $produk,
                        'jenis_produk' => null,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'subtotal' => $itemSubtotal,
                    ];

                    $subtotal += $itemSubtotal;
                }

                /*
                |--------------------------------------------------------------------------
                | Create invoice
                |--------------------------------------------------------------------------
                */

                $kodeInvoice =
                    'INV-' .
                    now()->format('Ymd') .
                    '-' .
                    strtoupper(Str::random(6));

                $totalBayar =
                    (int) $subtotal +
                    (int) $shippingCost;

                $invoice = Invoice::create([
                    'kode_invoice' => $kodeInvoice,
                    'tanggal' => now(),
                    'customer_id' => Auth::id(),
                    'nama' => $validated['nama'],
                    'no_hp' => $validated['no_hp'],
                    'alamat' => $validated['alamat'],
                    'total_bayar' => $totalBayar,
                    'ongkir' => $shippingCost,
                    'kurir' => $courierCode,
                    'layanan_pengiriman' => $shippingService,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Create transaction items and update stock
                |--------------------------------------------------------------------------
                */

                foreach ($processedItems as $processedItem) {
                    /** @var Produk $produk */
                    $produk = $processedItem['produk'];

                    /** @var JenisProduk|null $jenisProduk */
                    $jenisProduk =
                        $processedItem['jenis_produk'];

                    $quantity =
                        $processedItem['quantity'];

                    /*
                    |--------------------------------------------------------------------------
                    | Variant stock
                    |--------------------------------------------------------------------------
                    */

                    if ($jenisProduk) {
                        $stokAwal =
                            (int) $jenisProduk->jumlah_produk;

                        ItemTransaksi::create([
                            'invoice_id' =>
                                $invoice->id_invoice,

                            'produk_id' =>
                                $produk->id_produk,

                            'jenis_produk_id' =>
                                $jenisProduk->id_jenis_produk,

                            'jumlah' =>
                                $quantity,

                            'subtotal' =>
                                $processedItem['subtotal'],
                        ]);

                        $jenisProduk->decrement(
                            'jumlah_produk',
                            $quantity
                        );

                        RiwayatStokProduk::create([
                            'produk_id' =>
                                $produk->id_produk,

                            'jenis_produk_id' =>
                                $jenisProduk->id_jenis_produk,

                            'tanggal' =>
                                now()->toDateString(),

                            'stok_awal' =>
                                $stokAwal,

                            'stok_masuk' =>
                                0,

                            'stok_keluar' =>
                                $quantity,

                            'stok_akhir' =>
                                $stokAwal - $quantity,
                        ]);

                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Main product stock
                    |--------------------------------------------------------------------------
                    */

                    $stokAwal =
                        (int) ($produk->jumlah_produk ?? 0);

                    ItemTransaksi::create([
                        'invoice_id' =>
                            $invoice->id_invoice,

                        'produk_id' =>
                            $produk->id_produk,

                        'jenis_produk_id' =>
                            null,

                        'jumlah' =>
                            $quantity,

                        'subtotal' =>
                            $processedItem['subtotal'],
                    ]);

                    $produk->decrement(
                        'jumlah_produk',
                        $quantity
                    );

                    RiwayatStokProduk::create([
                        'produk_id' =>
                            $produk->id_produk,

                        'jenis_produk_id' =>
                            null,

                        'tanggal' =>
                            now()->toDateString(),

                        'stok_awal' =>
                            $stokAwal,

                        'stok_masuk' =>
                            0,

                        'stok_keluar' =>
                            $quantity,

                        'stok_akhir' =>
                            $stokAwal - $quantity,
                    ]);
                }

                return $invoice;
            });

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat.',
                'invoice_id' => $invoice->id_invoice,
                'kode_invoice' => $invoice->kode_invoice,
                'redirect_url' => route(
                    'order.confirmation',
                    $invoice->id_invoice
                ),
            ]);
        } catch (RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            Log::error('Checkout error', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' =>
                    'Terjadi kesalahan saat memproses pesanan.',
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Order Confirmation
    |--------------------------------------------------------------------------
    |
    | Customer hanya dapat melihat invoice miliknya sendiri.
    |
    */

    public function orderConfirmation($id)
    {
        $invoice = Invoice::with('customer')
            ->where('id_invoice', $id)
            ->where('customer_id', Auth::id())
            ->firstOrFail();

        $items = ItemTransaksi::with([
            'produk.gambarProduk',
            'jenisProduk',
        ])
            ->where(
                'invoice_id',
                $invoice->id_invoice
            )
            ->get();

        $userReviews = [];

        foreach ($items as $item) {
            $userReviews[$item->produk_id] =
                UlasanRating::where(
                    'user_id',
                    Auth::id()
                )
                    ->where(
                        'produk_id',
                        $item->produk_id
                    )
                    ->first();
        }

        return view(
            'order-confirmation',
            compact(
                'invoice',
                'items',
                'userReviews'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RajaOngkir District Search
    |--------------------------------------------------------------------------
    */

    public function getDistricts(Request $request)
    {
        $validated = $request->validate([
            'q' => 'nullable|string|max:100',
        ]);

        try {
            $this->ensureRajaOngkirConfigured();

            $response = Http::timeout(15)
                ->retry(2, 300)
                ->withHeaders([
                    'key' => $this->rajaOngkirApiKey(),
                ])
                ->get(
                    $this->rajaOngkirBaseUrl() .
                        '/destination/domestic-destination',
                    [
                        'search' => $validated['q'] ?? '',
                    ]
                );

            if (!$response->successful()) {
                Log::warning(
                    'RajaOngkir district request failed',
                    [
                        'status' => $response->status(),
                    ]
                );

                return response()->json([
                    'results' => [],
                    'pagination' => [
                        'more' => false,
                    ],
                ], 502);
            }

            $data = $response->json('data');

            if (!is_array($data)) {
                return response()->json([
                    'results' => [],
                    'pagination' => [
                        'more' => false,
                    ],
                ]);
            }

            $results = array_map(
                function ($district) {
                    return [
                        'id' =>
                            $district['id'] ?? null,

                        'text' =>
                            $district['label'] ?? '-',
                    ];
                },
                $data
            );

            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => false,
                ],
            ]);
        } catch (Throwable $e) {
            Log::warning(
                'RajaOngkir district error',
                [
                    'message' => $e->getMessage(),
                ]
            );

            return response()->json([
                'results' => [],
                'pagination' => [
                    'more' => false,
                ],
                'message' =>
                    'Layanan pencarian alamat belum tersedia.',
            ], 503);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RajaOngkir Shipping Cost
    |--------------------------------------------------------------------------
    */

    public function calculateShippingCost(
        Request $request
    ) {
        $validated = $request->validate([
            'destination' =>
                'required|string|max:100',

            'weight' =>
                'required|integer|min:1|max:1000000',
        ]);

        try {
            $services = $this->fetchShippingServices(
                $validated['destination'],
                (int) $validated['weight']
            );

            $formattedServices = array_map(
                function ($service) {
                    return [
                        'service' =>
                            $service['service'] ?? '',

                        'description' =>
                            $service['description'] ?? '',

                        'cost' =>
                            (int) ($service['cost'] ?? 0),

                        'etd' =>
                            $service['etd'] ??
                            'Estimasi 2-3 hari',
                    ];
                },
                $services
            );

            $firstService = $services[0];

            return response()->json([
                'success' => true,

                'courier' => strtoupper(
                    (string) (
                        $firstService['code'] ??
                        'JNT'
                    )
                ),

                'courier_name' =>
                    $firstService['name'] ??
                    'J&T Express',

                'services' => $formattedServices,
            ]);
        } catch (RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            Log::error(
                'RajaOngkir shipping error',
                [
                    'message' => $e->getMessage(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    'Terjadi kesalahan saat menghitung ongkos kirim.',
            ], 500);
        }
    }
}