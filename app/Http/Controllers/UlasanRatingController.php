<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\UlasanRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanRatingController extends Controller
{
    public function formTambahUlasan($produk_id)
    {
        $produk = Produk::findOrFail($produk_id);
        
        // Cek apakah user sudah memberikan ulasan untuk produk ini
        $existingReview = null;
        if (Auth::check()) {
            $existingReview = UlasanRating::where('user_id', Auth::id())
                ->where('produk_id', $produk_id)
                ->first();
        }
        
        return view('form-ulasan', compact('produk', 'existingReview'));
    }

    public function store(Request $request, $produk_id)
    {
        // Validasi input
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Rating wajib diisi.',
            'rating.integer' => 'Rating harus berupa angka.',
            'rating.min' => 'Rating minimal adalah 1.',
            'rating.max' => 'Rating maksimal adalah 5.',
            'ulasan.max' => 'Ulasan maksimal 1000 karakter.',
        ]);

        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Cek apakah produk ada
        $produk = Produk::findOrFail($produk_id);

        // Cek apakah user sudah memberikan ulasan
        $existingReview = UlasanRating::where('user_id', Auth::id())
            ->where('produk_id', $produk_id)
            ->first();

        if ($existingReview) {
            // Update ulasan yang sudah ada
            $existingReview->update([
                'rating' => $validated['rating'],
                'ulasan' => $validated['ulasan'] ?? null,
            ]);

            return redirect()->route('produk.detail', $produk_id)
                ->with('success', 'Ulasan berhasil diperbarui.');
        } else {
            // Buat ulasan baru
            UlasanRating::create([
                'user_id' => Auth::id(),
                'produk_id' => $produk_id,
                'rating' => $validated['rating'],
                'ulasan' => $validated['ulasan'] ?? null,
            ]);

            return redirect()->route('produk.detail', $produk_id)
                ->with('success', 'Ulasan berhasil ditambahkan. Terima kasih atas feedback Anda!');
        }
    }

    public function destroy($id)
    {
        $ulasan = UlasanRating::findOrFail($id);
        
        // Pastikan hanya user yang membuat ulasan atau admin yang bisa menghapus
        if (Auth::id() != $ulasan->user_id && Auth::user()->role != 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus ulasan ini.');
        }

        $produk_id = $ulasan->produk_id;
        $ulasan->delete();

        return redirect()->route('produk.detail', $produk_id)
            ->with('success', 'Ulasan berhasil dihapus.');
    }
}
