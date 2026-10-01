<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Menampilkan seluruh data kategori
     */
    public function index()
    {
        $kategoris = Kategori::withCount('produk')->latest()->get();
        return view('kategori.index', compact('kategoris'));
    }

    /**
     * Menampilkan form untuk membuat kategori baru
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategori,nama',
        ]);

        Kategori::create($validated);

        return redirect()->route('kategori.index')->with('success', 'Data kategori berhasil ditambahkan!');
    }

    /**
     * Menampilkan data kategori berdasarkan ID.
     */
    public function show(string $id)
    {
        $kategori = Kategori::withCount('produk')->findOrFail($id);
        return view('kategori.show', compact('kategori'));
    }

    /**
     * Menampilkan form untuk mengedit kategori.
     */
    public function edit(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Memperbarui data kategori di database.
     */
    public function update(Request $request, string $id)
    {
        $kategori = Kategori::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategori,nama,' . $id . ',id_kategori',
        ]);

        $kategori->update($validated);

        return redirect()->route('kategori.index')->with('success', 'Data kategori berhasil diupdate!');
    }

    /**
     * Menghapus data kategori dari database.
     */
    public function destroy(string $id)
    {
        $kategori = Kategori::findOrFail($id);

        // cek apakah kategori memiliki produk
        if ($kategori->produk()->count() > 0) {
            return redirect()->route('kategori.index')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk!');
        }

        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Data kategori berhasil dihapus!');
    }
}
