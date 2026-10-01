<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Invoice;

class AkunController extends Controller
{
    // Dashboard Akun Saya
    public function index()
    {
        $user = Auth::user();

        $recentOrders = Invoice::where('customer_id', $user->id_user)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('akun-saya', compact('recentOrders'));
    }

    // Halaman Pesanan
    public function pesanan()
    {
        $user = Auth::user();

        $orders = Invoice::where('customer_id', $user->id_user)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('akun-pesanan', compact('orders'));
    }

    // Detail Pesanan
    public function pesananDetail($id)
    {
        $user = Auth::user();

        // Uncomment jika sudah menggunakan Invoice
        // $order = Invoice::where('customer_id', $user->id_user)
        //     ->where('id_invoice', $id)
        //     ->with('items.produk')
        //     ->firstOrFail();

        // Sementara redirect ke halaman pesanan
        return redirect()->route('akun.pesanan');
    }

    // Halaman Alamat
    public function alamat()
    {
        $user = Auth::user();

        return view('akun-alamat', compact('user'));
    }

    // Update Alamat
    public function updateAlamat(Request $request)
    {
        $request->validate([
            'alamat' => 'required|string',
        ]);

        $user = Auth::user();
        $user->alamat = $request->alamat;
        $user->save();

        return redirect()->route('akun.alamat')->with('success', 'Alamat berhasil diperbarui');
    }

    // Halaman Edit Akun
    public function edit()
    {
        $user = Auth::user();

        return view('akun-edit', compact('user'));
    }

    // Update Akun
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:25',
            'email' => 'required|email|max:50|unique:users,email,' . $user->id_user . ',id_user',
            'no_hp' => 'nullable|string|max:15',
            'username' => 'nullable|string|max:20|unique:users,username,' . $user->id_user . ',id_user',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->username = $request->username;

        // Upload foto profil jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && file_exists(public_path($user->foto))) {
                unlink(public_path($user->foto));
            }

            // Upload foto baru
            $foto = $request->file('foto');
            $namaFoto = 'profile_' . $user->id_user . '_' . time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('uploads/profile'), $namaFoto);
            $user->foto = 'uploads/profile/' . $namaFoto;
        }

        // Update password jika diisi
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('akun.edit')->with('success', 'Akun berhasil diperbarui');
    }
    
    public function konfirmasiDiterima(string $id)
{
    $user = Auth::user();

    $invoice = Invoice::where('id_invoice', $id)
        ->where('customer_id', $user->id_user)
        ->firstOrFail();

    if ($invoice->status_pengiriman !== 'dikirim') {
        return redirect()
            ->route('akun.pesanan')
            ->with('error', 'Pesanan ini belum dapat dikonfirmasi sebagai diterima.');
    }

    $invoice->update([
        'status_pengiriman' => 'diterima',
    ]);

    return redirect()
        ->route('akun.pesanan')
        ->with('success', 'Pesanan berhasil dikonfirmasi telah diterima.');
}
}