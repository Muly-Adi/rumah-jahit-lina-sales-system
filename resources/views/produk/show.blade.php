@extends("template-dashboard")
@section("title", "Detail Produk")
@section("main")
    <div class="row">
        <div class="section-description section-description-inline">
            <h1>Data Produk</h1>
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

    <!-- Info Produk -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Produk</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="fw-bold text-secondary-light mb-2">Nama Produk:</label>
                            <p class="mb-0">{{ $produk->nama }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="fw-bold text-secondary-light mb-2">Kategori:</label>
                            <p class="mb-0">{{ $produk->kategori->nama ?? "-" }}</p>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="fw-bold text-secondary-light mb-2">Harga:</label>
                            <p class="mb-0 text-success fw-bold">Rp
                                {{ number_format($produk->harga, 0, ",", ".") }}</p>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="fw-bold text-secondary-light mb-2">Harga Modal:</label>
                            <p class="mb-0 text-success fw-bold">Rp
                                {{ number_format($produk->harga_modal, 0, ",", ".") }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="fw-bold text-secondary-light mb-2">Stok Total:</label>
                            @php
                                $totalStok = $produk->jumlah_produk ?? 0;
                                $totalStok += $produk->jenisProduk->sum("jumlah_produk");
                            @endphp
                            <p class="mb-0 fw-bold">{{ $totalStok }}</p>
                            <small class="text-secondary-light">
                                (Produk utama: {{ $produk->jumlah_produk ?? 0 }} + Variasi:
                                {{ $produk->jenisProduk->sum("jumlah_produk") }})
                            </small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="fw-bold text-secondary-light mb-2">Keterangan:</label>
                            <p class="mb-0">{{ $produk->keterangan ?? "-" }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="fw-bold text-secondary-light mb-2">Minimal Pembelian:</label>
                            <p class="mb-0">{{ $produk->min_beli ?? "-" }} Item</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gambar Produk -->
    <div class="card mb-24">
        <div class="card-header">
            <h6 class="fw-bold mb-0 card-title">Gambar Produk</h6>
        </div>
        <div class="card-body p-24">
            <div class="d-flex flex-wrap gap-3">
                @forelse($produk->gambarProduk as $gambar)
                    <img src="{{ asset($gambar->path_gambar) }}" alt="{{ $produk->nama }}"
                        class="w-25 h-150-px rounded object-fit-cover border">
                @empty
                    <p class="text-secondary-light">Tidak ada gambar</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Jenis Produk -->
    <div class="card mb-24">
        <div class="card-header">
            <h6 class="fw-bold mb-0 card-title">Jenis/Variasi Produk</h6>
        </div>
        <div class="card-body p-24">
            <div class="row">
                @forelse($produk->jenisProduk as $jenis)
                    <div class="col-md-4 mb-3">
                        <div class="card border">
                            <div class="card-body">
                                @if ($jenis->path_gambar)
                                    <img src="{{ asset($jenis->path_gambar) }}" alt="{{ $jenis->nama }}"
                                        class="w-100 h-150-px rounded object-fit-cover mb-3">
                                @endif
                                <h6 class="fw-semibold mb-2">{{ $jenis->nama }}</h6>
                                @if ($jenis->warna)
                                    <p class="mb-1"><small><strong>Warna:</strong> {{ $jenis->warna }}</small></p>
                                @endif
                                @if ($jenis->ukuran)
                                    <p class="mb-1"><small><strong>Ukuran:</strong> {{ $jenis->ukuran }}</small></p>
                                @endif
                                <p class="mb-2"><strong class="text-success">Harga: Rp
                                        {{ number_format($jenis->harga, 0, ",", ".") }}</strong></p>
                                <span class="badge badge-warning text-white px-3 py-2">
                                    Stok: {{ $jenis->jumlah_produk }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-secondary-light mb-0">Tidak ada jenis produk</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Riwayat Stok -->
    <div class="card mb-24">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 card-title">Riwayat Stok Produk</h6>
            <div class="d-flex gap-2">
                <a href="{{ route("produk.riwayat-stok.print", $produk->id_produk) }}" target="_blank"
                    class="btn btn-sm btn-success radius-8">
                    Cetak PDF
                </a>
                <button type="button" class="btn btn-sm btn-primary radius-8" data-bs-toggle="modal"
                    data-bs-target="#tambahStokModal">
                    Tambah Stok
                </button>
            </div>
        </div>
        <div class="card-body p-24">
            <div class="table-responsive">
                <table class="table bordered-table sm-table mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Jenis</th>
                            <th>Stok Awal</th>
                            <th>Produk Masuk</th>
                            <th>Produk Keluar</th>
                            <th>Stok Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Group riwayat by tanggal dan jenis_produk_id
                            $riwayatGrouped = $produk
                                ->riwayatStokProduk()
                                ->orderBy("tanggal", "desc")
                                ->orderBy("created_at", "desc")
                                ->get()
                                ->groupBy(function ($item) {
                                    return $item->tanggal . "_" . ($item->jenis_produk_id ?? "utama");
                                });
                        @endphp

                        @forelse($riwayatGrouped as $key => $riwayatItems)
                            @php
                                // Urutkan items berdasarkan created_at (transaksi tertua dulu)
                                $sortedItems = $riwayatItems->sortBy("created_at");

                                $firstItem = $sortedItems->first(); // Transaksi pertama (tertua)
                                $lastItem = $sortedItems->last(); // Transaksi terakhir (terbaru)

                                $totalMasuk = $sortedItems->sum("stok_masuk");
                                $totalKeluar = $sortedItems->sum("stok_keluar");

                                // Stok awal dari transaksi pertama
                                $stokAwal = $firstItem->stok_awal;
                                // Stok akhir dari transaksi terakhir
                                $stokAkhir = $lastItem->stok_akhir;
                            @endphp
                            <tr>
                                <td>{{ $firstItem->tanggal }}</td>
                                <td>{{ $produk->nama }}</td>
                                <td>{{ $firstItem->jenis_produk_id ? $firstItem->jenisProduk->nama : "Produk Utama" }}
                                </td>
                                <td>{{ $stokAwal }}</td>
                                <td class="text-success">+{{ $totalMasuk }}</td>
                                <td class="text-danger">-{{ $totalKeluar }}</td>
                                <td class="fw-bold">{{ $stokAkhir }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary-light">
                                    Tidak ada riwayat stok
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <a href="{{ route("produk.index") }}" class="btn btn-lg btn-secondary radius-8">
            <i class="material-icons-outlined">
                arrow_circle_left
            </i>
            Kembali
        </a>
        <a href="{{ route("produk.edit", $produk->id_produk) }}" class="btn btn-lg btn-primary radius-8">
            <i class="material-icons-outlined">
                drive_file_rename_outline
            </i>
            Edit Produk
        </a>
    </div>

    <!-- Modal Tambah Stok -->
    <div class="modal fade" id="tambahStokModal" tabindex="-1" aria-labelledby="tambahStokModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahStokModalLabel">Tambah Stok Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route("produk.stok.tambah", $produk->id_produk) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-20">
                            <label for="tipe_stok" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Tipe Transaksi <span class="text-danger-600">*</span>
                            </label>
                            <select class="form-select radius-8" id="tipe_stok" name="tipe_stok" required>
                                <option value="">Pilih Tipe</option>
                                <option value="masuk">Stok Masuk</option>
                                <option value="keluar">Stok Keluar</option>
                            </select>
                        </div>

                        <div class="mb-20">
                            <label for="jenis_produk_id" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Pilih Produk
                            </label>
                            <select class="form-select radius-8" id="jenis_produk_id" name="jenis_produk_id">
                                <option value="">Produk Utama</option>
                                @foreach ($produk->jenisProduk as $jenis)
                                    <option value="{{ $jenis->id_jenis_produk }}">{{ $jenis->nama }} (Stok:
                                        {{ $jenis->jumlah_produk }})</option>
                                @endforeach
                            </select>
                            <small class="text-secondary-light">Kosongkan jika ingin menambah stok produk utama</small>
                        </div>

                        <div class="mb-20">
                            <label for="jumlah" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                Jumlah <span class="text-danger-600">*</span>
                            </label>
                            <input type="number" class="form-control radius-8" id="jumlah" name="jumlah"
                                placeholder="Masukkan jumlah stok" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push("script")
    @if (session("success_stok"))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success_stok") }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if (session("error_stok"))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session("error_stok") }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endpush
