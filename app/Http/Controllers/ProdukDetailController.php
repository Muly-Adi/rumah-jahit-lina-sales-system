<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProdukDetailController extends Controller
{
    public function show($id)
    {
        // Ambil produk berdasarkan ID dengan relasi
        $produk = Produk::with(['kategori', 'gambarProduk', 'jenisProduk', 'itemTransaksi.invoice'])
            ->findOrFail($id);

        // Olah jenis produk untuk view - grouping berdasarkan atribut yang ada
        $jenisProdukGrouped = [
            'nama' => [],
            'warna' => [],
            'ukuran' => [],
        ];

        if ($produk->jenisProduk->count() > 0) {
            // Ambil semua nilai unik untuk setiap atribut yang terisi
            $jenisProdukGrouped['nama'] = $produk->jenisProduk
                ->pluck('nama')
                ->filter(function($value) {
                    return !empty($value);
                })
                ->unique()
                ->values();

            $jenisProdukGrouped['warna'] = $produk->jenisProduk
                ->pluck('warna')
                ->filter(function($value) {
                    return !empty($value);
                })
                ->unique()
                ->values();

            $jenisProdukGrouped['ukuran'] = $produk->jenisProduk
                ->pluck('ukuran')
                ->filter(function($value) {
                    return !empty($value);
                })
                ->unique()
                ->values();
        }

        // Konversi semua variasi ke JSON untuk digunakan di JavaScript
        $semuaVariasiJson = $produk->jenisProduk->map(function ($jenis) {
            return [
                'id' => $jenis->id_jenis_produk,
                'nama' => $jenis->nama,
                'warna' => $jenis->warna,
                'ukuran' => $jenis->ukuran,
                'harga' => $jenis->harga,
                'stok' => $jenis->jumlah_produk,
                'gambar' => $jenis->path_gambar ? asset($jenis->path_gambar) : null
            ];
        })->toJson();

        // Ambil produk terkait
        $produkTerkait = Produk::with(['kategori', 'gambarProduk', 'jenisProduk'])
            ->where('kategori_id', $produk->kategori_id)
            ->where('id_produk', '!=', $id)
            ->where('jumlah_produk', '>', 0)
            ->limit(6)
            ->get();

        // Ambil produk serupa
        $produkSerupa = Produk::with(['kategori', 'gambarProduk', 'jenisProduk'])
            ->where('id_produk', '!=', $id)
            ->where('jumlah_produk', '>', 0)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        // Ambil semua kategori untuk sidebar
        $kategori = Kategori::all();

        // Ambil ulasan rating dengan user
        $ulasanRatings = $produk->ulasanRating()->with('user')->latest()->paginate(10);

        // Hitung rata-rata rating
        $averageRating = $produk->ulasanRating()->avg('rating') ?? 0;
        $totalReviews = $produk->ulasanRating()->count();

        // Hitung distribusi rating
        $ratingDistribution = [
            5 => $produk->ulasanRating()->where('rating', 5)->count(),
            4 => $produk->ulasanRating()->where('rating', 4)->count(),
            3 => $produk->ulasanRating()->where('rating', 3)->count(),
            2 => $produk->ulasanRating()->where('rating', 2)->count(),
            1 => $produk->ulasanRating()->where('rating', 1)->count(),
        ];

        // Hitung persentase untuk setiap rating
        $ratingPercentages = [];
        foreach ($ratingDistribution as $rating => $count) {
            $ratingPercentages[$rating] = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
        }

        // Cek apakah user sudah memberikan ulasan
        $userReview = null;
        if (auth()->check()) {
            $userReview = $produk->ulasanRating()
                ->where('user_id', auth()->id())
                ->first();
        }

        return view('produk-detail', compact(
            'produk',
            'produkTerkait',
            'produkSerupa',
            'kategori',
            'ulasanRatings',
            'averageRating',
            'totalReviews',
            'userReview',
            'ratingDistribution',
            'ratingPercentages',
            'jenisProdukGrouped',
            'semuaVariasiJson'
        ));
    }
}
