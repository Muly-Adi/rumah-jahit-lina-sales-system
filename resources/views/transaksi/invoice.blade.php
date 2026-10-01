@extends("template-dashboard")

@section("title", "Invoice - " . $transaksi->kode_invoice)

@section("main")
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="page-description">
                    <h1>Invoice {{ $transaksi->kode_invoice }}</h1>
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route("transaksi.show", $transaksi->id_invoice) }}" class="btn btn-secondary">
                            <i class="material-icons">arrow_back</i> Kembali
                        </a>
                        <button type="button" class="btn btn-warning" onclick="window.print()">
                            <i class="material-icons">print</i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="invoice">
            <div class="col">
                <div class="card invoice">
                    <div class="card-body">
                        <div class="invoice-header">
                            <div class="row">
                                <div class="col-md-6 text-white">
                                    <h3>Invoice {{ $transaksi->kode_invoice }}</h3>
                                    <p class="mb-1">Tanggal: {{ $transaksi->tanggal->format("d/m/Y") }}</p>
                                    <p class="mb-0">Status:
                                        @if ($transaksi->status_pembayaran === "pending")
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($transaksi->status_pembayaran === "terima")
                                            <span class="badge bg-success">Terima</span>
                                        @else
                                            <span class="badge bg-danger">Tolak</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 text-end text-white">
                                    <p class="mb-1 fw-bold">Rumah Jahit Lina</p>
                                    <p class="mb-1">Jl. Harmonika Baru, Tj. Sari, Kec. Medan Selayang, Kota Medan,
                                        Sumatera Utara 20132</p>
                                    <p class="mb-0">08116091099</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6 class="mb-3">Invoice Untuk:</h6>
                                <table class="table table-borderless table-sm">
                                    <tbody>
                                        <tr>
                                            <td width="100">Nama</td>
                                            <td>: {{ $transaksi->nama }}</td>
                                        </tr>
                                        <tr>
                                            <td>Alamat</td>
                                            <td>: {{ $transaksi->alamat }}</td>
                                        </tr>
                                        <tr>
                                            <td>No. HP</td>
                                            <td>: {{ $transaksi->no_hp }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless table-sm">
                                    <tbody>
                                        <tr>
                                            <td>Tanggal Invoice</td>
                                            <td>: {{ $transaksi->tanggal->format("d M Y") }}</td>
                                        </tr>
                                        <tr>
                                            <td>Kode Invoice</td>
                                            <td>: {{ $transaksi->kode_invoice }}</td>
                                        </tr>
                                        @if ($transaksi->resi)
                                            <tr>
                                                <td>No. Resi</td>
                                                <td>: {{ $transaksi->resi }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td>Kurir</td>
                                            <td>: {{ $transaksi->kurir }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status Pengiriman</td>
                                            @php
                                                $statusPengiriman = $transaksi->status_pengiriman;
                                            @endphp

                                            <td>:
                                                @if ($statusPengiriman === "dikirim")
                                                    <span class="badge badge-success px-20 py-9 radius-4">
                                                        Sudah Dikirim
                                                    </span>
                                                @elseif ($statusPengiriman === "diterima")
                                                    <span class="badge badge-primary px-20 py-9 radius-4">
                                                        Diterima
                                                    </span>
                                                @elseif ($statusPengiriman === "dibatalkan")
                                                    <span class="badge badge-danger px-20 py-9 radius-4">
                                                        Dibatalkan
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning px-20 py-9 radius-4">
                                                        Belum Dikirim
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="table-responsive">
                                <table class="table invoice-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">No.</th>
                                            <th scope="col">Item</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Harga Satuan</th>
                                            <th scope="col" class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transaksi->itemTransaksi as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    {{ $item->jenisProduk->produk->nama }}
                                                    <br>
                                                    @if ($item->jenis_produk_id && $item->jenisProduk)
                                                        @php
                                                            $namaVar = trim($item->jenisProduk->nama ?? "");
                                                            $warnaVar = trim($item->jenisProduk->warna ?? "");
                                                            $ukuranVar = trim($item->jenisProduk->ukuran ?? "");

                                                            $parts = [];

                                                            if ($namaVar !== "") {
                                                                $containsWarna =
                                                                    $warnaVar !== "" &&
                                                                    stripos($namaVar, $warnaVar) !== false;
                                                                $containsUkuran =
                                                                    $ukuranVar !== "" &&
                                                                    stripos($namaVar, $ukuranVar) !== false;
                                                                if (!$containsWarna && !$containsUkuran) {
                                                                    $parts[] = $namaVar;
                                                                }
                                                            }

                                                            if ($warnaVar !== "") {
                                                                $parts[] = $warnaVar;
                                                            }

                                                            if ($ukuranVar !== "") {
                                                                $parts[] = $ukuranVar;
                                                            }
                                                        @endphp

                                                        @if (count($parts) > 0)
                                                            <small class="text-muted">
                                                                {{ implode(" - ", $parts) }}
                                                            </small>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>{{ $item->jumlah }}</td>
                                                <td>Rp {{ number_format($item->jenisProduk->harga, 0, ",", ".") }}</td>
                                                <td class="text-end">Rp
                                                    {{ number_format($item->subtotal, 0, ",", ".") }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if ($transaksi->ongkir ?? 0 > 0)
                                            <tr>
                                                <td colspan="4" class="text-end"><strong>Ongkir</strong></td>
                                                <td class="text-end">Rp
                                                    {{ number_format($transaksi->ongkir, 0, ",", ".") }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Total</strong></td>
                                            <td class="text-end">Rp
                                                {{ number_format($transaksi->total_bayar, 0, ",", ".") }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row invoice-summary">
                            <p class="mt-3 text-center">Terima kasih atas pembelian Anda!</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("script")
    <style>
        @media print {
            @page {
                size: portrait;
                margin: 0.5cm;
            }

            body {
                margin: 0;
                padding: 0;
                transform: scale(0.75);
                transform-origin: top left;
                width: 118%;
            }

            .app-sidebar,
            .app-header,
            .page-description .btn {
                display: none !important;
            }

            .app-container {
                margin-left: 0 !important;
            }

            #invoice {
                margin-top: 0 !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .container {
                max-width: 100% !important;
                padding: 0 !important;
            }

            .table {
                font-size: 0.9em;
            }
        }
    </style>
@endpush
