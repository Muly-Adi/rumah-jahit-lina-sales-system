<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class PembayaranController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Create Midtrans Payment
    |--------------------------------------------------------------------------
    |
    | Nominal pembayaran selalu diambil dari database.
    | Customer hanya boleh membayar invoice miliknya sendiri.
    |
    */

    public function bayar(Request $request)
    {
        $validated = $request->validate([
            'id_invoice' =>
                'required|integer|exists:invoice,id_invoice',
        ]);

        $invoice = Invoice::where(
            'id_invoice',
            $validated['id_invoice']
        )
            ->where(
                'customer_id',
                Auth::id()
            )
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate / invalid payment
        |--------------------------------------------------------------------------
        */

        if ($invoice->status_pembayaran === 'terima') {
            return response()->json([
                'status' => 'error',
                'message' =>
                    'Invoice ini sudah dibayar.',
            ], 422);
        }

        if ($invoice->status_pembayaran === 'tolak') {
            return response()->json([
                'status' => 'error',
                'message' =>
                    'Invoice ini sudah tidak dapat dibayar.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Return existing Snap token
        |--------------------------------------------------------------------------
        */

        if (!empty($invoice->snap_token)) {
            return response()->json([
                'status' => 'success',
                'snap_token' =>
                    $invoice->snap_token,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Midtrans configuration
        |--------------------------------------------------------------------------
        */

        $serverKey =
            config('services.midtrans.serverKey');

        $clientKey =
            config('services.midtrans.clientKey');

        if (empty($serverKey)) {
            return response()->json([
                'status' => 'error',
                'message' =>
                    'Konfigurasi pembayaran belum tersedia.',
            ], 503);
        }

        /*
        |--------------------------------------------------------------------------
        | Midtrans configuration
        |--------------------------------------------------------------------------
        */

        \Midtrans\Config::$serverKey =
            $serverKey;

        \Midtrans\Config::$isProduction =
            (bool) config(
                'services.midtrans.isProduction',
                false
            );

        \Midtrans\Config::$isSanitized =
            (bool) config(
                'services.midtrans.isSanitized',
                true
            );

        \Midtrans\Config::$is3ds =
            (bool) config(
                'services.midtrans.is3ds',
                true
            );

        try {
            $user = Auth::user();

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            |
            | gross_amount berasal dari database.
            | Browser tidak menentukan nominal pembayaran.
            |
            */

            $params = [
                'transaction_details' => [
                    'order_id' =>
                        $invoice->kode_invoice,

                    'gross_amount' =>
                        (int) $invoice->total_bayar,
                ],

                'customer_details' => [
                    'first_name' =>
                        $user->nama,

                    'last_name' =>
                        $user->username ?? '',

                    'email' =>
                        $user->email ?? '',

                    'phone' =>
                        $user->no_hp ?? '',
                ],
            ];

            $snapToken =
                \Midtrans\Snap::getSnapToken(
                    $params
                );

            $invoice->snap_token =
                $snapToken;

            $invoice->save();

            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
            ]);
        } catch (Throwable $e) {
            Log::error(
                'Midtrans Snap Error',
                [
                    'invoice' =>
                        $invoice->kode_invoice,

                    'customer_id' =>
                        Auth::id(),

                    'message' =>
                        $e->getMessage(),
                ]
            );

            return response()->json([
                'status' => 'error',
                'message' =>
                    'Gagal membuat transaksi pembayaran.',
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Check Midtrans Payment Status
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'kode_invoice' =>
                'required|string|max:100',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Customer ownership check
        |--------------------------------------------------------------------------
        */

        $invoice = Invoice::where(
            'kode_invoice',
            $validated['kode_invoice']
        )
            ->where(
                'customer_id',
                Auth::id()
            )
            ->firstOrFail();

        $serverKey =
            config('services.midtrans.serverKey');

        if (empty($serverKey)) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Konfigurasi pembayaran belum tersedia.',
            ], 503);
        }

        /*
        |--------------------------------------------------------------------------
        | Choose Midtrans environment
        |--------------------------------------------------------------------------
        */

        $isProduction = (bool) config(
            'services.midtrans.isProduction',
            false
        );

        $baseUrl = $isProduction
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';

        try {
            $response = Http::timeout(15)
                ->retry(2, 300)
                ->withBasicAuth(
                    $serverKey,
                    ''
                )
                ->acceptJson()
                ->get(
                    $baseUrl .
                        '/v2/' .
                        rawurlencode(
                            $invoice->kode_invoice
                        ) .
                        '/status'
                );

            if (!$response->successful()) {
                Log::warning(
                    'Midtrans status request failed',
                    [
                        'invoice' =>
                            $invoice->kode_invoice,

                        'http_status' =>
                            $response->status(),
                    ]
                );

                return response()->json([
                    'success' => false,
                    'message' =>
                        'Gagal memeriksa status transaksi.',
                ], 502);
            }

            $data = $response->json();

            $transactionStatus =
                $data['transaction_status'] ??
                null;

            $fraudStatus =
                $data['fraud_status'] ??
                null;

            Log::info(
                'Midtrans payment status checked',
                [
                    'invoice' =>
                        $invoice->kode_invoice,

                    'transaction_status' =>
                        $transactionStatus,

                    'fraud_status' =>
                        $fraudStatus,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Payment successful
            |--------------------------------------------------------------------------
            */

            $paymentAccepted =
                $transactionStatus ===
                    'settlement' ||
                (
                    $transactionStatus ===
                        'capture' &&
                    (
                        $fraudStatus === null ||
                        $fraudStatus ===
                            'accept'
                    )
                );

            if ($paymentAccepted) {
                $invoice->status_pembayaran =
                    'terima';

                $invoice->save();

                return response()->json([
                    'success' => true,

                    'message' =>
                        'Pembayaran berhasil diverifikasi.',

                    'status' =>
                        'lunas',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Payment rejected / expired
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    $transactionStatus,
                    [
                        'deny',
                        'cancel',
                        'expire',
                    ],
                    true
                )
            ) {
                $invoice->status_pembayaran =
                    'tolak';

                $invoice->save();

                return response()->json([
                    'success' => true,

                    'message' =>
                        'Transaksi pembayaran tidak berhasil atau telah kedaluwarsa.',

                    'status' =>
                        'ditolak',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Pending
            |--------------------------------------------------------------------------
            */

            $invoice->status_pembayaran =
                'pending';

            $invoice->save();

            return response()->json([
                'success' => true,

                'message' =>
                    'Pembayaran masih menunggu konfirmasi.',

                'status' =>
                    'belum_bayar',
            ]);
        } catch (Throwable $e) {
            Log::error(
                'Midtrans Status Error',
                [
                    'invoice' =>
                        $invoice->kode_invoice,

                    'customer_id' =>
                        Auth::id(),

                    'message' =>
                        $e->getMessage(),
                ]
            );

            return response()->json([
                'success' => false,

                'message' =>
                    'Terjadi kesalahan saat memeriksa pembayaran.',
            ], 500);
        }
    }
}