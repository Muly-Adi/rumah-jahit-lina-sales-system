<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    /**
     * Menampilkan seluruh data karyawan
     */
    public function index()
    {
        $karyawans = User::where('role', 'karyawan')->latest()->get();
        return view('karyawan.index', compact('karyawans'));
    }

    /**
     * Menampilkan form untuk membuat data karyawan baru
     */
    public function create()
    {
        return view('karyawan.create');
    }

    /**
     * Menyimpan data karyawan baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'no_hp' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['role'] = 'karyawan';
        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/karyawan'), $filename);
            $validated['foto'] = 'uploads/karyawan/' . $filename;
        }

        User::create($validated);

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil ditambahkan!');
    }

    /**
     * Menampilkan data karyawan berdasarkan ID.
     */
    public function show(string $id)
    {
        $karyawan = User::where('role', 'karyawan')->findOrFail($id);
        return view('karyawan.show', compact('karyawan'));
    }

    /**
     * Menampilkan form untuk mengedit data karyawan.
     */
    public function edit(string $id)
    {
        $karyawan = User::where('role', 'karyawan')->findOrFail($id);
        return view('karyawan.edit', compact('karyawan'));
    }

    /**
     * Memperbarui data karyawan di database.
     */
    public function update(Request $request, string $id)
    {
        $karyawan = User::where('role', 'karyawan')->findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id . ',id_user',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id . ',id_user',
            'no_hp' => 'required|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('foto')) {
            // menghapus foto lama jika ada
            if ($karyawan->foto && file_exists(public_path($karyawan->foto))) {
                unlink(public_path($karyawan->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/karyawan'), $filename);
            $validated['foto'] = 'uploads/karyawan/' . $filename;
        }

        $karyawan->update($validated);

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diupdate!');
    }

    /**
     * Menghapus data karyawan dari database.
     */
    public function destroy(string $id)
    {
        $karyawan = User::where('role', 'karyawan')->findOrFail($id);

        // menghapus foto lama jika ada
        if ($karyawan->foto && file_exists(public_path($karyawan->foto))) {
            unlink(public_path($karyawan->foto));
        }

        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil dihapus!');
    }
}
