<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\ItemTransaksi;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        // Get transaksi in date range with status terima
        $transaksis = Invoice::with(['customer', 'itemTransaksi.produk'])
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->where('status_pembayaran', 'terima')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Calculate summary
        $totalTransaksi = $transaksis->count();
        $totalPendapatan = $transaksis->sum('total_bayar');

         // Calculate total modal and keuntungan
        $totalModal = 0;
        foreach ($transaksis as $transaksi) {
            foreach ($transaksi->itemTransaksi as $item) {
                if ($item->produk) {
                    $totalModal += $item->jumlah * $item->produk->harga_modal;
                }
            }
        }
        $totalKeuntungan = $totalPendapatan - $totalModal;

        // Get top products
        $topProducts = ItemTransaksi::select('produk_id', DB::raw('SUM(jumlah) as total_qty'))
            ->whereHas('invoice', function($query) use ($tanggalAwal, $tanggalAkhir) {
                $query->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                      ->where('status_pembayaran', 'terima');
            })
            ->groupBy('produk_id')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->with('produk')
            ->get();

        return view('laporan.preview', compact(
                'transaksis',
                'tanggalAwal',
                'tanggalAkhir',
                'totalTransaksi',
                'totalPendapatan',
                'topProducts',
                'totalModal',
                'totalKeuntungan'
            )
        );
    }

    public function print(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $transaksis = Invoice::with(['customer', 'itemTransaksi.produk'])
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->where('status_pembayaran', 'terima')
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalTransaksi = $transaksis->count();
        $totalPendapatan = $transaksis->sum('total_bayar');

        $totalModal = 0;
        foreach ($transaksis as $transaksi) {
            foreach ($transaksi->itemTransaksi as $item) {
                if ($item->produk) {
                    $totalModal += $item->jumlah * $item->produk->harga_modal;
                }
            }
        }
        $totalKeuntungan = $totalPendapatan - $totalModal;

        $topProducts = ItemTransaksi::select('produk_id', DB::raw('SUM(jumlah) as total_qty'))
            ->whereHas('invoice', function($query) use ($tanggalAwal, $tanggalAkhir) {
                $query->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                      ->where('status_pembayaran', 'terima');
            })
            ->groupBy('produk_id')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->with('produk')
            ->get();

        return view('laporan.print', compact(
                'transaksis',
                'tanggalAwal',
                'tanggalAkhir',
                'totalTransaksi',
                'totalPendapatan',
                'topProducts',
                'totalModal',
                'totalKeuntungan'
            )
        );
    }

    public function downloadPdf(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $transaksis = Invoice::with(['customer', 'itemTransaksi.produk'])
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->where('status_pembayaran', 'terima')
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalTransaksi = $transaksis->count();
        $totalPendapatan = $transaksis->sum('total_bayar');

        // Calculate total modal and keuntungan
        $totalModal = 0;
        foreach ($transaksis as $transaksi) {
            foreach ($transaksi->itemTransaksi as $item) {
                if ($item->produk) {
                    $totalModal += $item->jumlah * $item->produk->harga_modal;
                }
            }
        }
        $totalKeuntungan = $totalPendapatan - $totalModal;


        $topProducts = ItemTransaksi::select('produk_id', DB::raw('SUM(jumlah) as total_qty'))
            ->whereHas('invoice', function($query) use ($tanggalAwal, $tanggalAkhir) {
                $query->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                      ->where('status_pembayaran', 'terima');
            })
            ->groupBy('produk_id')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->with('produk')
            ->get();

        return view('laporan.pdf', compact(
                'transaksis',
                'tanggalAwal',
                'tanggalAkhir',
                'totalTransaksi',
                'totalPendapatan',
                'topProducts',
                'totalModal', 
                'totalKeuntungan'
            )
        );
    }
}
