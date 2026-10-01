<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\JenisProduk;
use App\Models\GambarProduk;
use App\Models\RiwayatStokProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produks = Produk::with(['kategori', 'gambarProduk', 'jenisProduk'])
            ->latest()
            ->get();
        return view('produk.index', compact('produks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view('produk.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id_kategori',
            'nama' => 'required|string|max:255',
            'harga_modal' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'jumlah_produk' => 'nullable|integer|min:0',
            'min_beli' => 'required|integer|min:1',
            'gambar_produk.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'jenis_nama.*' => 'nullable|string|max:255',
            'jenis_warna.*' => 'nullable|string|max:20',
            'jenis_ukuran.*' => 'nullable|string|max:20',
            'jenis_harga.*' => 'nullable|integer|min:0',
            'jenis_gambar.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'jenis_jumlah.*' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Create Produk
            $produk = Produk::create([
                'kategori_id' => $validated['kategori_id'],
                'nama' => $validated['nama'],
                'harga_modal' => $validated['harga_modal'],
                'harga' => $validated['harga'],
                'jumlah_produk' => $validated['jumlah_produk'] ?? 0,
                'keterangan' => $validated['keterangan'] ?? null,
                'min_beli' => $validated['min_beli'] ?? null,
            ]);

            // Upload Gambar Produk
            if ($request->hasFile('gambar_produk')) {
                foreach ($request->file('gambar_produk') as $gambar) {
                    $filename = time() . '_' . uniqid() . '.' . $gambar->getClientOriginalExtension();
                    $gambar->move(public_path('uploads/produk'), $filename);

                    GambarProduk::create([
                        'produk_id' => $produk->id_produk,
                        'path_gambar' => 'uploads/produk/' . $filename,
                    ]);
                }
            }

            // Create Jenis Produk
            if ($request->has('jenis_nama') || $request->has('jenis_warna') || $request->has('jenis_ukuran')) {
                $totalVariants = max(
                    count($request->jenis_nama ?? []),
                    count($request->jenis_warna ?? []),
                    count($request->jenis_ukuran ?? [])
                );

                for ($i = 0; $i < $totalVariants; $i++) {
                    $jenisNama = $request->jenis_nama[$i] ?? null;
                    $jenisWarna = $request->jenis_warna[$i] ?? null;
                    $jenisUkuran = $request->jenis_ukuran[$i] ?? null;

                    // Skip jika semua field kosong
                    if (!$jenisNama && !$jenisWarna && !$jenisUkuran) {
                        continue;
                    }

                    // Generate nama otomatis jika tidak ada
                    if (!$jenisNama) {
                        $namaParts = [];
                        if ($jenisWarna) $namaParts[] = $jenisWarna;
                        if ($jenisUkuran) $namaParts[] = $jenisUkuran;
                        $jenisNama = implode(' - ', $namaParts);
                    }

                    $jenisData = [
                        'produk_id' => $produk->id_produk,
                        'nama' => $jenisNama,
                        'warna' => $jenisWarna,
                        'ukuran' => $jenisUkuran,
                        'harga' => $request->jenis_harga[$i] ?? 0,
                        'jumlah_produk' => $request->jenis_jumlah[$i] ?? 0,
                    ];

                    // Upload Gambar Jenis Produk
                    if ($request->hasFile('jenis_gambar.' . $i)) {
                        $jenisGambar = $request->file('jenis_gambar')[$i];
                        $filename = time() . '_' . uniqid() . '.' . $jenisGambar->getClientOriginalExtension();
                        $jenisGambar->move(public_path('uploads/jenis-produk'), $filename);
                        $jenisData['path_gambar'] = 'uploads/jenis-produk/' . $filename;
                    }

                    $jenisProduk = JenisProduk::create($jenisData);

                    // Create Riwayat Stok untuk Jenis Produk
                    if ($jenisData['jumlah_produk'] > 0) {
                        RiwayatStokProduk::create([
                            'produk_id' => $produk->id_produk,
                            'jenis_produk_id' => $jenisProduk->id_jenis_produk,
                            'tanggal' => now(),
                            'stok_awal' => 0,
                            'stok_masuk' => $jenisData['jumlah_produk'],
                            'stok_keluar' => 0,
                            'stok_akhir' => $jenisData['jumlah_produk'],
                        ]);
                    }
                }
            }

            // Create Riwayat Stok Produk Utama (hanya jika jumlah_produk > 0)
            if ($validated['jumlah_produk'] > 0) {
                RiwayatStokProduk::create([
                    'produk_id' => $produk->id_produk,
                    'jenis_produk_id' => null,
                    'tanggal' => now(),
                    'stok_awal' => 0,
                    'stok_masuk' => $validated['jumlah_produk'],
                    'stok_keluar' => 0,
                    'stok_akhir' => $validated['jumlah_produk'],
                ]);
            }

            DB::commit();
            return redirect()->route('produk.index')->with('success', 'Data produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Produk::with(['kategori', 'gambarProduk', 'jenisProduk', 'riwayatStokProduk'])
            ->findOrFail($id);
        return view('produk.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produk = Produk::with(['gambarProduk', 'jenisProduk'])->findOrFail($id);
        $kategoris = Kategori::all();
        return view('produk.edit', compact('produk', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id_kategori',
            'nama' => 'required|string|max:255',
            'harga_modal' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'jumlah_produk' => 'nullable|integer|min:0',
            'min_beli' => 'required|integer|min:1',
            'gambar_produk.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // Jenis Existing
            'jenis_existing_id.*' => 'nullable|exists:jenis_produk,id_jenis_produk',
            'jenis_existing_nama.*' => 'nullable|string|max:255',
            'jenis_existing_warna.*' => 'nullable|string|max:20',
            'jenis_existing_ukuran.*' => 'nullable|string|max:20',
            'jenis_existing_harga.*' => 'nullable|integer|min:0',
            'jenis_existing_jumlah.*' => 'nullable|integer|min:0',
            // Jenis Baru
            'jenis_nama.*' => 'nullable|string|max:255',
            'jenis_warna.*' => 'nullable|string|max:20',
            'jenis_ukuran.*' => 'nullable|string|max:20',
            'jenis_harga.*' => 'nullable|integer|min:0',
            'jenis_gambar.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'jenis_jumlah.*' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $stokLama = $produk->jumlah_produk;

            // Update Produk
            $produk->update([
                'kategori_id' => $validated['kategori_id'],
                'nama' => $validated['nama'],
                'harga_modal' => $validated['harga_modal'],
                'harga' => $validated['harga'],
                'min_beli' => $validated['min_beli'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            // Upload Gambar Produk Baru
            if ($request->hasFile('gambar_produk')) {
                foreach ($request->file('gambar_produk') as $gambar) {
                    $filename = time() . '_' . uniqid() . '.' . $gambar->getClientOriginalExtension();
                    $gambar->move(public_path('uploads/produk'), $filename);

                    GambarProduk::create([
                        'produk_id' => $produk->id_produk,
                        'path_gambar' => 'uploads/produk/' . $filename,
                    ]);
                }
            }

            // Update Jenis Produk Existing
            if ($request->has('jenis_existing_id')) {
                foreach ($request->jenis_existing_id as $index => $jenisId) {
                    $jenis = JenisProduk::find($jenisId);
                    if ($jenis) {
                        $stokLamaJenis = $jenis->jumlah_produk;
                        $stokBaruJenis = $request->jenis_existing_jumlah[$index] ?? 0;

                        // Get nama, warna, ukuran
                        $jenisNama = $request->jenis_existing_nama[$index] ?? null;
                        $jenisWarna = $request->jenis_existing_warna[$index] ?? null;
                        $jenisUkuran = $request->jenis_existing_ukuran[$index] ?? null;

                        // Auto-generate nama jika kosong tapi ada warna/ukuran
                        if (!$jenisNama && ($jenisWarna || $jenisUkuran)) {
                            $namaParts = [];
                            if ($jenisWarna) $namaParts[] = $jenisWarna;
                            if ($jenisUkuran) $namaParts[] = $jenisUkuran;
                            $jenisNama = implode(' - ', $namaParts);
                        }

                        $updateData = [
                            'nama' => $jenisNama,
                            'warna' => $jenisWarna,
                            'ukuran' => $jenisUkuran,
                            'harga' => $request->jenis_existing_harga[$index] ?? 0,
                            'jumlah_produk' => $stokBaruJenis,
                        ];

                        // Upload Gambar Baru untuk Jenis Existing (jika ada)
                        if ($request->hasFile("jenis_existing_gambar_{$jenisId}")) {
                            $jenisGambar = $request->file("jenis_existing_gambar_{$jenisId}");

                            // Hapus gambar lama jika ada
                            if ($jenis->path_gambar && file_exists(public_path($jenis->path_gambar))) {
                                unlink(public_path($jenis->path_gambar));
                            }

                            // Upload gambar baru
                            $filename = time() . '_' . uniqid() . '.' . $jenisGambar->getClientOriginalExtension();
                            $jenisGambar->move(public_path('uploads/jenis-produk'), $filename);
                            $updateData['path_gambar'] = 'uploads/jenis-produk/' . $filename;
                        }

                        $jenis->update($updateData);

                        // Create Riwayat Stok jika ada perubahan stok jenis
                        if ($stokLamaJenis != $stokBaruJenis) {
                            $selisih = $stokBaruJenis - $stokLamaJenis;
                            RiwayatStokProduk::create([
                                'produk_id' => $produk->id_produk,
                                'jenis_produk_id' => $jenisId,
                                'tanggal' => now(),
                                'stok_awal' => $stokLamaJenis,
                                'stok_masuk' => $selisih > 0 ? $selisih : 0,
                                'stok_keluar' => $selisih < 0 ? abs($selisih) : 0,
                                'stok_akhir' => $stokBaruJenis,
                            ]);
                        }
                    }
                }
            }

            // Tambah Jenis Produk Baru
            if ($request->has('jenis_nama') || $request->has('jenis_warna') || $request->has('jenis_ukuran')) {
                $totalVariants = max(
                    count($request->jenis_nama ?? []),
                    count($request->jenis_warna ?? []),
                    count($request->jenis_ukuran ?? [])
                );

                for ($i = 0; $i < $totalVariants; $i++) {
                    $jenisNama = $request->jenis_nama[$i] ?? null;
                    $jenisWarna = $request->jenis_warna[$i] ?? null;
                    $jenisUkuran = $request->jenis_ukuran[$i] ?? null;

                    // Skip jika semua field kosong
                    if (!$jenisNama && !$jenisWarna && !$jenisUkuran) {
                        continue;
                    }

                    // Auto-generate nama jika kosong tapi ada warna/ukuran
                    if (!$jenisNama && ($jenisWarna || $jenisUkuran)) {
                        $namaParts = [];
                        if ($jenisWarna) $namaParts[] = $jenisWarna;
                        if ($jenisUkuran) $namaParts[] = $jenisUkuran;
                        $jenisNama = implode(' - ', $namaParts);
                    }

                    $jenisData = [
                        'nama' => $jenisNama,
                        'warna' => $jenisWarna,
                        'ukuran' => $jenisUkuran,
                        'harga' => $request->jenis_harga[$i] ?? 0,
                        'jumlah_produk' => $request->jenis_jumlah[$i] ?? 0,
                    ];

                    // Upload Gambar Jenis Produk
                    if ($request->hasFile('jenis_gambar.' . $i)) {
                        $jenisGambar = $request->file('jenis_gambar')[$i];
                        $filename = time() . '_' . uniqid() . '.' . $jenisGambar->getClientOriginalExtension();
                        $jenisGambar->move(public_path('uploads/jenis-produk'), $filename);
                        $jenisData['path_gambar'] = 'uploads/jenis-produk/' . $filename;
                    }

                    $jenisProduk = JenisProduk::create([
                        'produk_id' => $produk->id_produk,
                        ...$jenisData
                    ]);

                    // Create Riwayat Stok untuk Jenis Produk Baru (jika ada stok)
                    if ($jenisData['jumlah_produk'] > 0) {
                        RiwayatStokProduk::create([
                            'produk_id' => $produk->id_produk,
                            'jenis_produk_id' => $jenisProduk->id_jenis_produk,
                            'tanggal' => now(),
                            'stok_awal' => 0,
                            'stok_masuk' => $jenisData['jumlah_produk'],
                            'stok_keluar' => 0,
                            'stok_akhir' => $jenisData['jumlah_produk'],
                        ]);
                    }
                }
            }

            // Create Riwayat Stok jika ada perubahan dan jumlah_produk dikirim
            if (isset($validated['jumlah_produk']) && $stokLama != $validated['jumlah_produk']) {
                $selisih = $validated['jumlah_produk'] - $stokLama;
                RiwayatStokProduk::create([
                    'produk_id' => $produk->id_produk,
                    'jenis_produk_id' => null,
                    'tanggal' => now(),
                    'stok_awal' => $stokLama,
                    'stok_masuk' => $selisih > 0 ? $selisih : 0,
                    'stok_keluar' => $selisih < 0 ? abs($selisih) : 0,
                    'stok_akhir' => $validated['jumlah_produk'],
                ]);

                // Update jumlah produk utama
                $produk->update(['jumlah_produk' => $validated['jumlah_produk']]);
            }

            DB::commit();
            return redirect()->route('produk.index')->with('success', 'Data produk berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Produk::findOrFail($id);

        DB::beginTransaction();
        try {
            // Delete Gambar Produk
            foreach ($produk->gambarProduk as $gambar) {
                if (file_exists(public_path($gambar->path_gambar))) {
                    unlink(public_path($gambar->path_gambar));
                }
                $gambar->delete();
            }

            // Delete Gambar Jenis Produk
            foreach ($produk->jenisProduk as $jenis) {
                if ($jenis->path_gambar && file_exists(public_path($jenis->path_gambar))) {
                    unlink(public_path($jenis->path_gambar));
                }
                $jenis->delete();
            }

            // Delete Riwayat Stok
            $produk->riwayatStokProduk()->delete();

            // Delete Produk
            $produk->delete();

            DB::commit();
            return redirect()->route('produk.index')->with('success', 'Data produk berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete gambar produk
     */
    public function deleteGambar($id)
    {
        $gambar = GambarProduk::findOrFail($id);

        if (file_exists(public_path($gambar->path_gambar))) {
            unlink(public_path($gambar->path_gambar));
        }

        $gambar->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Delete jenis produk
     */
    public function deleteJenis($id)
    {
        $jenis = JenisProduk::findOrFail($id);

        if ($jenis->path_gambar && file_exists(public_path($jenis->path_gambar))) {
            unlink(public_path($jenis->path_gambar));
        }

        $jenis->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Tambah stok produk
     */
    public function tambahStok(Request $request, string $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'tipe_stok' => 'required|in:masuk,keluar',
            'jenis_produk_id' => 'nullable|exists:jenis_produk,id_jenis_produk',
            'jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $tipe = $validated['tipe_stok'];
            $jumlah = $validated['jumlah'];
            $jenisId = $validated['jenis_produk_id'] ?? null;

            // Jika ada jenis produk dipilih
            if ($jenisId) {
                $jenis = JenisProduk::findOrFail($jenisId);
                $stokAwal = $jenis->jumlah_produk;

                // Validasi stok keluar
                if ($tipe === 'keluar' && $stokAwal < $jumlah) {
                    return redirect()->back()->with('error_stok', 'Stok tidak mencukupi! Stok saat ini: ' . $stokAwal);
                }

                // Update stok jenis produk
                $stokAkhir = $tipe === 'masuk'
                    ? $stokAwal + $jumlah
                    : $stokAwal - $jumlah;

                $jenis->update(['jumlah_produk' => $stokAkhir]);

                // Catat riwayat stok
                RiwayatStokProduk::create([
                    'produk_id' => $produk->id_produk,
                    'jenis_produk_id' => $jenisId,
                    'tanggal' => now(),
                    'stok_awal' => $stokAwal,
                    'stok_masuk' => $tipe === 'masuk' ? $jumlah : 0,
                    'stok_keluar' => $tipe === 'keluar' ? $jumlah : 0,
                    'stok_akhir' => $stokAkhir,
                ]);
            } else {
                // Update stok produk utama
                $stokAwal = $produk->jumlah_produk ?? 0;

                // Validasi stok keluar
                if ($tipe === 'keluar' && $stokAwal < $jumlah) {
                    return redirect()->back()->with('error_stok', 'Stok tidak mencukupi! Stok saat ini: ' . $stokAwal);
                }

                $stokAkhir = $tipe === 'masuk'
                    ? $stokAwal + $jumlah
                    : $stokAwal - $jumlah;

                $produk->update(['jumlah_produk' => $stokAkhir]);

                // Catat riwayat stok
                RiwayatStokProduk::create([
                    'produk_id' => $produk->id_produk,
                    'jenis_produk_id' => null,
                    'tanggal' => now(),
                    'stok_awal' => $stokAwal,
                    'stok_masuk' => $tipe === 'masuk' ? $jumlah : 0,
                    'stok_keluar' => $tipe === 'keluar' ? $jumlah : 0,
                    'stok_akhir' => $stokAkhir,
                ]);
            }

            DB::commit();

            $message = $tipe === 'masuk'
                ? "Berhasil menambahkan {$jumlah} stok!"
                : "Berhasil mengurangi {$jumlah} stok!";

            return redirect()->back()->with('success_stok', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error_stok', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Print riwayat stok produk
     */
    public function printRiwayatStok(string $id)
    {
        $produk = Produk::with(['kategori', 'jenisProduk', 'riwayatStokProduk.jenisProduk'])
            ->findOrFail($id);

        // Group riwayat stok by date and jenis
        $riwayatGrouped = $produk->riwayatStokProduk
            ->sortBy('created_at')
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d') . '_' . ($item->jenis_produk_id ?? 'utama');
            })
            ->map(function ($group) {
                $firstItem = $group->sortBy('created_at')->first();
                return [
                    'tanggal' => $firstItem->created_at,
                    'jenis' => $firstItem->jenisProduk ? $firstItem->jenisProduk->nama : 'Produk Utama',
                    'stok_awal' => $firstItem->stok_awal,
                    'stok_masuk' => $group->sum('stok_masuk'),
                    'stok_keluar' => $group->sum('stok_keluar'),
                    'stok_akhir' => $group->last()->stok_akhir,
                ];
            })
            ->values();

        // Calculate total stok
        $totalStok = $produk->jumlah_produk + $produk->jenisProduk->sum('jumlah_produk');

        return view('produk.print-riwayat', compact('produk', 'riwayatGrouped', 'totalStok'));
    }
}
