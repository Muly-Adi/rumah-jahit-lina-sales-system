@extends('template-dashboard')
@section('title', 'Data Transaksi')

@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="section-description section-description-inline">
                <h1>Data Transaksi</h1>
            </div>
        </div>
        @if (session("success"))
            <div class="alert alert-rounded alert-success alert-style-light" role="alert">
                {{ session("success") }}
            </div>
        @endif

        @if (session("error"))
            <div class="alert alert-rounded alert-success alert-style-light" role="alert">
                {{ session("error") }}
            </div>
        @endif
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header bg-base py-16 px-24">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="text-lg fw-semibold mb-0">Daftar Transaksi</h6>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route("transaksi.index") }}" method="GET" class="d-flex gap-2">
                                    <input type="text" name="search" class="form-control radius-8"
                                           placeholder="Cari kode invoice atau nama..." value="{{ request("search") }}">
                                    <button type="submit" class="btn btn-primary text-center">
                                        <i class="material-icons-outlined">search</i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <form action="{{ route("transaksi.index") }}" method="GET" id="filterForm">
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <select name="status_pembayaran" class="form-select radius-8"
                                                    onchange="this.form.submit()">
                                                <option value="">Semua Status Pembayaran</option>
                                                <option value="pending"
                                                    {{ request("status_pembayaran") == "pending" ? "selected" : "" }}>
                                                    Pending</option>
                                                <option value="terima"
                                                    {{ request("status_pembayaran") == "terima" ? "selected" : "" }}>Terima
                                                </option>
                                                <option value="tolak"
                                                    {{ request("status_pembayaran") == "tolak" ? "selected" : "" }}>Tolak
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="status_pengiriman" class="form-select radius-8"
                                                    onchange="this.form.submit()">
                                                <option value="">Semua Status Pengiriman</option>
                                                <option value="0"
                                                    {{ request("status_pengiriman") === "0" ? "selected" : "" }}>Belum
                                                    Dikirim</option>
                                                <option value="1"
                                                    {{ request("status_pengiriman") === "1" ? "selected" : "" }}>Sudah
                                                    Dikirim</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            @if (request("status_pembayaran") || request("status_pengiriman") || request("search"))
                                                <a href="{{ route("transaksi.index") }}"
                                                   class="btn btn-lg btn-secondary radius-8">
                                                    Reset Filter
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table id="datatable1" class="display" style="width:100%">
                            <thead>
                            <tr class="text-center">
                                <th scope="col" class="text-center">No</th>
                                <th scope="col">Kode Invoice</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Total</th>
                                <th scope="col">Status Pembayaran</th>
                                <th scope="col">Status Pengiriman</th>
                                <th scope="col" class="text-center">Opsi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($transaksis as $index => $transaksi)
                                <tr>
                                    <td class="text-center">{{ $transaksis->firstItem() + $index }}</td>
                                    <td>
                                            <span
                                                class="text-md fw-semibold text-primary-light">{{ $transaksi->kode_invoice }}</span>
                                    </td>
                                    <td>{{ $transaksi->tanggal->format("d M Y") }}</td>
                                    <td>{{ $transaksi->nama }}</td>
                                    <td>
                                            <span class="text-success-600 fw-semibold">Rp
                                                {{ number_format($transaksi->total_bayar, 0, ",", ".") }}</span>
                                    </td>
                                    <td style="text-align: center">
                                        @if ($transaksi->status_pembayaran === "pending")
                                            <span class="badge badge-warning text-sm fw-bold px-20 py-9 radius-4">
                                                Pending
                                            </span>
                                        @elseif($transaksi->status_pembayaran === "terima")
                                            <span class="badge badge-success text-sm fw-bold px-20 py-9 radius-4">
                                                Terima
                                            </span>
                                        @else
                                            <span class="badge badge-danger text-sm fw-bold px-20 py-9 radius-4">
                                                Tolak
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusPengiriman = $transaksi->status_pengiriman;
                                        @endphp
                                        @if ($statusPengiriman === "dikirim")
                                            <span class="badge badge-info text-sm fw-bold px-20 py-9 radius-4">
                                                Sudah Dikirim
                                            </span>
                                        @elseif ($statusPengiriman === "diterima")
                                            <span class="badge badge-success text-sm fw-bold px-20 py-9 radius-4">
                                                Diterima
                                            </span>
                                        @elseif ($statusPengiriman === "dibatalkan")
                                            <span class="badge badge-danger text-sm fw-bold px-20 py-9 radius-4">
                                                Dibatalkan
                                            </span>
                                        @else
                                            <span
                                                class="badge text-sm fw-bold badge-warning px-20 py-9 radius-4">
                                                    Belum Dikirim
                                                </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center gap-10 justify-content-center">
                                            <a href="{{ route("transaksi.show", $transaksi->id_invoice) }}"
                                               class="btn btn-sm d-flex justify-content-center align-items-center rounded-circle"
                                               title="Detail ">
                                                <i class="material-icons-outlined text-primary">
                                                    remove_red_eye
                                                </i>
                                            </a>
                                            <a href="{{ route("transaksi.invoice", $transaksi->id_invoice) }}"
                                               class="btn btn-sm fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle"
                                               title="Download Invoice">
                                                <span class="material-icons-outlined text-success">
                                                    receipt
                                                </span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <span class="text-secondary-light">Tidak ada data transaksi</span>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                            <tfoot>
                            <tr class="text-center">
                                <th scope="col" class="text-center">No</th>
                                <th scope="col">Kode Invoice</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Total</th>
                                <th scope="col">Status Pembayaran</th>
                                <th scope="col">Status Pengiriman</th>
                                <th scope="col" class="text-center">Opsi</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
