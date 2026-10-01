<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['customer', 'itemTransaksi.produk']);

        // Filter by status pembayaran
        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        // Filter by status pengiriman
        if ($request->filled('status_pengiriman')) {
            $query->where('status_pengiriman', $request->status_pengiriman);
        }

        // Search by kode invoice or nama customer
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_invoice', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        $transaksis = $query->latest()->paginate(10);

        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaksi = Invoice::with(['customer', 'itemTransaksi.produk.gambarProduk', 'itemTransaksi.jenisProduk'])
            ->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Display invoice
     */
    public function invoice(string $id)
    {
        $transaksi = Invoice::with(['customer', 'itemTransaksi.produk', 'itemTransaksi.jenisProduk'])
            ->findOrFail($id);
        return view('transaksi.invoice', compact('transaksi'));
    }

    /**
     * Validate payment
     */
    public function validatePayment(Request $request, string $id)
    {
        $transaksi = Invoice::with('itemTransaksi.produk', 'itemTransaksi.jenisProduk')->findOrFail($id);

        $statusLama = $transaksi->status_pembayaran;

        $validated = $request->validate([
            'status_pembayaran' => 'required|in:pending,terima,tolak',
        ]);

        $statusBaru = $validated['status_pembayaran'];

        // Jika status diubah menjadi "tolak" dan sebelumnya bukan "tolak"
        if ($statusBaru === 'tolak' && $statusLama !== 'tolak') {
            DB::transaction(function () use ($transaksi) {
                // Kembalikan stok untuk setiap item transaksi
                foreach ($transaksi->itemTransaksi as $item) {
                    // Jika item memiliki jenis produk, kembalikan stok ke jenis produk
                    if ($item->jenis_produk_id && $item->jenisProduk) {
                        $item->jenisProduk->increment('jumlah_produk', $item->jumlah);
                    }
                    // Jika tidak ada jenis produk, kembalikan stok ke produk utama
                    else if ($item->produk) {
                        $item->produk->increment('jumlah_produk', $item->jumlah);
                    }
                }
            });
        }

        // Jika status diubah dari "tolak" ke status lain (terima/pending), kurangi stok lagi
        if ($statusLama === 'tolak' && $statusBaru !== 'tolak') {
            DB::transaction(function () use ($transaksi) {
                // Kurangi stok kembali untuk setiap item transaksi
                foreach ($transaksi->itemTransaksi as $item) {
                    // Jika item memiliki jenis produk, kurangi stok dari jenis produk
                    if ($item->jenis_produk_id && $item->jenisProduk) {
                        $item->jenisProduk->decrement('jumlah_produk', $item->jumlah);
                    }
                    // Jika tidak ada jenis produk, kurangi stok dari produk utama
                    else if ($item->produk) {
                        $item->produk->decrement('jumlah_produk', $item->jumlah);
                    }
                }
            });
        }

        $transaksi->update([
            'status_pembayaran' => $statusBaru
        ]);

        return redirect()->back()->with('success', 'Status pembayaran berhasil diupdate dan stok produk telah disesuaikan!');
    }

    /**
     * Update shipping info
     */
    public function updateShipping(Request $request, string $id)
    {
        $transaksi = Invoice::findOrFail($id);

        $validated = $request->validate([
            'resi' => 'nullable|string|max:255',
            'status_pengiriman' => 'required|in:pending,dikirim,diterima,dibatalkan',
        ]);

        if($validated['status_pengiriman'] === 'dikirim' && empty($validated['resi'])) {
            return redirect()->back()->withErrors(['resi' => 'Nomor resi harus diisi saat status pengiriman diubah menjadi "dikirim".'])->withInput();
        }

        $transaksi->update($validated);

        return redirect()->back()->with('success', 'Informasi pengiriman berhasil diupdate!');
    }
}
