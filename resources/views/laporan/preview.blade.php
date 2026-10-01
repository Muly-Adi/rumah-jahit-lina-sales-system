@extends('template-dashboard')
@section('title', 'Preview Laporan Penjualan')
@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="section-description section-description-inline">
                <h1>Preview Laporan Penjualan</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
            <!-- Action Buttons -->
                <div class="card mb-24 radius-12">
                    <div class="card-body p-20">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <h6 class="text-md fw-bold mb-1">Periode: {{ date("d M Y", strtotime($tanggalAwal)) }} -
                                    {{ date("d M Y", strtotime($tanggalAkhir)) }}</h6>
                                <p class="text-sm text-secondary-light mb-0">Total: {{ $totalTransaksi }} Transaksi |
                                    Pendapatan: Rp {{ number_format($totalPendapatan, 0, ",", ".") }} | Modal: Rp {{ number_format($totalModal, 0, ",", ".") }} | Keuntungan: Rp {{ number_format($totalKeuntungan, 0, ",", ".") }}</p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route("laporan.index") }}"
                                   class="btn btn-sm btn-secondary radius-8 d-inline-flex align-items-center gap-1">
                                    <i class="material-icons-outlined">
                                        arrow_circle_left
                                    </i>
                                    Kembali
                                </a>
                                <form action="{{ route("laporan.print") }}" method="POST" target="_blank" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="tanggal_awal" value="{{ $tanggalAwal }}">
                                    <input type="hidden" name="tanggal_akhir" value="{{ $tanggalAkhir }}">
                                    <button type="submit"
                                            class="btn btn-sm btn-warning radius-8 d-inline-flex align-items-center gap-1">
                                        <i class="material-icons-outlined">
                                            print
                                        </i>
                                        Print
                                    </button>
                                </form>
                                <form action="{{ route("laporan.pdf") }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="tanggal_awal" value="{{ $tanggalAwal }}">
                                    <input type="hidden" name="tanggal_akhir" value="{{ $tanggalAkhir }}">
                                    <button type="submit"
                                            class="btn btn-sm btn-success radius-8 d-inline-flex align-items-center gap-1">
                                        <i class="material-icons-outlined">
                                            picture_as_pdf
                                        </i>
                                        Download PDF
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row gy-4 mb-24">
                    <div class="col-lg-3">
                        <div class="card widget widget-stats">
                            <div class="card-body">
                                <div class="widget-stats-container d-flex">
                                    <div class="widget-stats-icon widget-stats-icon-warning">
                                        <i class="material-icons-outlined">
                                            bar_chart
                                        </i>
                                    </div>
                                    <div class="widget-stats-content flex-fill">
                                        <span class="widget-stats-title">Total Transaksi</span>
                                        <span class="fw-bold">{{ $totalTransaksi }}</span>
                                        <span class="widget-stats-info">Transaksi Berhasil</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card widget widget-stats">
                            <div class="card-body">
                                <div class="widget-stats-container d-flex">
                                    <div class="widget-stats-icon widget-stats-icon-primary">
                                        <i class="material-icons-outlined">paid</i>
                                    </div>
                                    <div class="widget-stats-content flex-fill">
                                        <span class="widget-stats-title">Total Pendapatan</span>
                                        <span class="fw-bold">Rp
                                            {{ number_format($totalPendapatan, 0, ",", ".") }}</span>
                                        <span class="widget-stats-info">Dari Transaksi Terima</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card widget widget-stats">
                            <div class="card-body">
                                <div class="widget-stats-container d-flex">
                                    <div class="widget-stats-icon widget-stats-icon-success">
                                        <i class="material-icons-outlined">
                                            account_balance_wallet
                                        </i>
                                    </div>
                                    <div class="widget-stats-content flex-fill">
                                        <span class="widget-stats-title">Total Modal</span>
                                        <span class="fw-bold">Rp {{ number_format($totalModal, 0, ",", ".") }}</span>
                                        <span class="widget-stats-info">Dari Transaksi Diterima</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card widget widget-stats">
                            <div class="card-body">
                                <div class="widget-stats-container d-flex">
                                    <div class="widget-stats-icon widget-stats-icon-success">
                                        <i class="material-icons-outlined">
                                            account_balance_wallet
                                        </i>
                                    </div>
                                    <div class="widget-stats-content flex-fill">
                                        <span class="widget-stats-title">Total Keuntungan</span>
                                        <span class="fw-bold">Rp {{ number_format($totalKeuntungan, 0, ",", ".") }}</span>
                                        <span class="widget-stats-info">Dari Transaksi Diterima</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="card radius-12">
                    <div class="card-header bg-base py-16 px-24">
                        <h6 class="text-lg fw-bold mb-0 card-title">Detail Transaksi</h6>
                    </div>
                    <div class="card-body p-24">
                        @if ($transaksis->count() > 0)
                            <div class="table-responsive">
                                <table class="table bordered-table sm-table mb-0">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Kode Invoice</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Status Pengiriman</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($transaksis as $index => $transaksi)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $transaksi->tanggal->format("d M Y") }}</td>
                                            <td>
                                                <span class="text-primary fw-bold">{{ $transaksi->kode_invoice }}</span>
                                            </td>
                                            <td>{{ $transaksi->nama }}</td>
                                            <td>
                                                <span class="text-success-600 fw-bold">
                                                    Rp {{ number_format($transaksi->total_bayar, 0, ",", ".") }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($transaksi->status_pengiriman)
                                                    <span class="badge badge-success px-20 py-9">
                                                        Sudah Dikirim
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning px-20 py-9">
                                                        Belum Dikirim
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-base">
                                        <td colspan="5" class="text-end fw-bold">TOTAL PENDAPATAN:</td>
                                        <td colspan="2">
                                            <span class="text-success-600 fw-bold text-lg">
                                                Rp {{ number_format($totalPendapatan, 0, ",", ".") }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="bg-base">
                                        <td colspan="5" class="text-end fw-bold">TOTAL MODAL:</td>
                                        <td colspan="2">
                                            <span class="text-success-600 fw-bold text-lg">
                                                Rp {{ number_format($totalModal, 0, ",", ".") }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="bg-base">
                                        <td colspan="5" class="text-end fw-bold">TOTAL KEUNTUNGAN:</td>
                                        <td colspan="2">
                                            <span class="text-success-600 fw-bold text-lg">
                                                Rp {{ number_format($totalKeuntungan, 0, ",", ".") }}
                                            </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="material-icons-outlined">
                                    add_chart
                                </i>
                                <p class="text-secondary-light mt-3">Tidak ada transaksi pada periode ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
