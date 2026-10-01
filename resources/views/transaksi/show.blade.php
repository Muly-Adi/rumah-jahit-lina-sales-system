@extends("template-dashboard")
@section("title", "Detail Transaksi")
@section("main")
    <div class="row">
        <div class="section-description section-description-inline">
            <h1>Detail Transaksi</h1>
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

    <div class="row gy-4">
        <!-- Info Transaksi -->
        <div class="col-lg-8">
            <div class="card mb-24">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Informasi Transaksi</h6>
                </div>
                <div class="card-body p-24">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-semibold text-secondary-light mb-2">Kode Invoice:</label>
                            <p class="mb-0 fw-bold text-primary-600">{{ $transaksi->kode_invoice }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-semibold text-secondary-light mb-2">Tanggal:</label>
                            <p class="mb-0">{{ $transaksi->tanggal->format("d F Y") }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-semibold text-secondary-light mb-2">Nama Customer:</label>
                            <p class="mb-0">{{ $transaksi->nama }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-semibold text-secondary-light mb-2">No. HP:</label>
                            <p class="mb-0">{{ $transaksi->no_hp }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="fw-semibold text-secondary-light mb-2">Alamat Pengiriman:</label>
                            <p class="mb-0">{{ $transaksi->alamat }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-semibold text-secondary-light mb-2">Total Bayar:</label>
                            <p class="mb-0 fw-bold text-success-600">Rp
                                {{ number_format($transaksi->total_bayar, 0, ",", ".") }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-semibold text-secondary-light mb-2">No. Resi:</label>
                            <p class="mb-0">{{ $transaksi->resi ?? "-" }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item Transaksi -->
            <div class="card mb-24">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Detail Pesanan</h6>
                </div>
                <div class="card-body p-24">
                    <div class="table-responsive">
                        <table class="table bordered-table mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi->itemTransaksi as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <span class="fw-semibold">{{ $item->produk->nama }}</span>
                                                @if ($item->jenis_produk_id && $item->jenisProduk)
                                                    @php
                                                        $namaVar = trim($item->jenisProduk->nama ?? "");
                                                        $warnaVar = trim($item->jenisProduk->warna ?? "");
                                                        $ukuranVar = trim($item->jenisProduk->ukuran ?? "");

                                                        $parts = [];

                                                        // Hanya tambahkan `nama` jika tidak kosong dan
                                                        // tidak sudah mengandung warna/ukuran untuk menghindari duplikasi
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
                                                        <span class="text-secondary-light text-sm">
                                                            <i class="text-primary">Jenis:</i>
                                                            {{ implode(" - ", $parts) }}
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($item->produk->harga, 0, ",", ".") }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td class="fw-semibold">Rp
                                            {{ number_format($item->produk->harga * $item->jumlah, 0, ",", ".") }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-base">
                                    <td colspan="3" class="text-end">Ongkos Kirim:</td>
                                    <td class="">Rp
                                        {{ number_format($transaksi->ongkir, 0, ",", ".") }}</td>
                                </tr>
                                <tr class="bg-base">
                                    <td colspan="3" class="text-end fw-bold">TOTAL:</td>
                                    <td class="fw-bold text-success">Rp
                                        {{ number_format($transaksi->total_bayar, 0, ",", ".") }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Panel -->
        <div class="col-lg-4">
            <!-- Status Pembayaran -->
            <div class="card mb-24">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Status Pembayaran</h6>
                </div>
                <div class="card-body p-24">
                    <div class="mb-3">
                        <label class="fw-bold text-secondary-light mb-2">Status Saat Ini:</label>
                        @if ($transaksi->status_pembayaran === "pending")
                            <span class="badge badge-warning px-20 py-9 radius-4">
                                Pending
                            </span>
                        @elseif($transaksi->status_pembayaran === "terima")
                            <span class="badge badge-success px-20 py-9 radius-4">
                                Terima
                            </span>
                        @else
                            <span class="badge badge-danger px-20 py-9 radius-4">
                                Tolak
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Status Pengiriman -->
            <div class="card mb-24">
                <div class="card-header bg-success-50">
                    <h6 class="fw-bold mb-0">Informasi Pengiriman</h6>
                </div>
                <div class="card-body p-24">
                    <div class="mb-3">
                        <label class="fw-bold text-secondary-light mb-2">Kurir:</label>
                        <span class="radius-4">
                            {{ $transaksi->kurir }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <label class="fw-semibold text-secondary-light mb-2">Status Saat Ini:</label>
                        @php
                            $statusPengiriman = $transaksi->status_pengiriman;
                        @endphp
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
                    </div>

                    <form action="{{ route("transaksi.update-shipping", $transaksi->id_invoice) }}" method="POST">
                        @csrf
                        @method("PUT")
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Nomor Resi <span class="text-danger-600">*</span>
                            </label>
                            <input type="text" class="form-control radius-8" name="resi"
                                value="{{ $transaksi->resi }}" placeholder="Masukkan nomor resi">
                            @error('resi')
                                <div class="text-danger text-sm mt-1 fst-italic">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Status Pengiriman
                            </label>
                            <select class="form-select radius-8" name="status_pengiriman" required>
                                <option value="">Pilih Status</option>
                                <option value="dikirim"
                                    {{ $transaksi->status_pengiriman === "dikirim" ? "selected" : "" }}>
                                    Sudah Dikirim</option>
                                <option value="dibatalkan"
                                    {{ $transaksi->status_pengiriman === "dibatalkan" ? "selected" : "" }}>
                                    Dibatalkan</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100 radius-8">
                            Update Pengiriman
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <a href="{{ route("transaksi.index") }}" class="btn btn-secondary px-24 py-12 radius-8">
            <iconify-icon icon="solar:arrow-left-outline" class="icon"></iconify-icon>
            Kembali
        </a>
        <a href="{{ route("transaksi.invoice", $transaksi->id_invoice) }}" class="btn btn-success px-24 py-12 radius-8">
            <iconify-icon icon="solar:download-linear" class="icon"></iconify-icon>
            Download Invoice
        </a>
    </div>
@endsection
