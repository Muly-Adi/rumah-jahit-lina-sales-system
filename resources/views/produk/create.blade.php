@extends("template-dashboard")
@section("title", "Tambah Data Produk")
@section("main")
    <div class="container-fluid">
        <div class="row">
            <div class="section-description section-description-inline">
                <h1>Tambah Data Produk</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Form Tambah Produk</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route("produk.store") }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- Informasi Produk -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h4 class="fw-semibold mb-3 text-primary">Informasi Produk</h4>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <div class="mb-2">
                                        <label for="nama"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Nama Produk <span class="text-danger-600">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control radius-8 @error("nama") is-invalid @enderror" id="nama"
                                            name="nama" value="{{ old("nama") }}" placeholder="Masukkan nama produk">
                                        @error("nama")
                                            <div class="text-danger-600 mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-2">
                                        <label for="kategori_id"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Kategori <span class="text-danger-600">*</span>
                                        </label>
                                        <select class="form-select radius-8 @error("kategori_id") is-invalid @enderror"
                                            id="kategori_id" name="kategori_id">
                                            <option value="">Pilih Kategori</option>
                                            @foreach ($kategoris as $kategori)
                                                <option value="{{ $kategori->id_kategori }}"
                                                    {{ old("kategori_id") == $kategori->id_kategori ? "selected" : "" }}>
                                                    {{ $kategori->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error("kategori_id")
                                            <div class="text-danger-600 mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="mb-2">
                                        <label for="harga_modal"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Harga Modal <span class="text-danger-600">*</span>
                                        </label>
                                        <input type="number"
                                            class="form-control radius-8 @error("harga_modal") is-invalid @enderror"
                                            id="harga_modal" name="harga_modal" value="{{ old("harga_modal") }}"
                                            placeholder="Masukkan harga modal" min="0" step="0.01">
                                        @error("harga_modal")
                                            <div class="text-danger-600 mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="mb-2">
                                        <label for="harga"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Harga <span class="text-danger-600">*</span>
                                        </label>
                                        <input type="number"
                                            class="form-control radius-8 @error("harga") is-invalid @enderror"
                                            id="harga" name="harga" value="{{ old("harga") }}"
                                            placeholder="Masukkan harga" min="0" step="0.01">
                                        @error("harga")
                                            <div class="text-danger-600 mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="mb-2">
                                        <label for="jumlah_produk"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Jumlah Stok
                                        </label>
                                        <input type="number"
                                            class="form-control radius-8 @error("jumlah_produk") is-invalid @enderror"
                                            id="jumlah_produk" name="jumlah_produk" value="{{ old("jumlah_produk") }}"
                                            placeholder="Masukkan jumlah stok">
                                        @error("jumlah_produk")
                                            <div class="text-danger-600 mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="mb-2">
                                        <label for="min_beli"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Minimal Pembelian
                                        </label>
                                        <input type="number"
                                            class="form-control radius-8 @error("min_beli") is-invalid @enderror"
                                            id="min_beli" name="min_beli" value="{{ old("min_beli") }}"
                                            placeholder="Masukkan minimal pembelian">
                                        @error("min_beli")
                                            <div class="text-danger-600 mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-2">
                                        <label for="keterangan"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Keterangan/Deskripsi
                                        </label>
                                        <textarea class="form-control radius-8 @error("keterangan") is-invalid @enderror" id="keterangan" name="keterangan"
                                            rows="3" placeholder="Masukkan keterangan produk">{{ old("keterangan") }}</textarea>
                                        @error("keterangan")
                                            <div class="text-danger-600 mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Gambar Produk -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="fw-semibold mb-3 text-primary">Gambar Produk</h5>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-2">
                                        <label for="gambar_produk"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Upload Gambar (Multiple)
                                        </label>
                                        <input type="file"
                                            class="form-control radius-8 @error("gambar_produk.*") is-invalid @enderror"
                                            id="gambar_produk" name="gambar_produk[]" accept="image/*" multiple>
                                        @error("gambar_produk.*")
                                            <div class="text-danger-600 mt-2">{{ $message }}</div>
                                        @enderror
                                        <small class="text-secondary-light">Format: JPG, JPEG, PNG. Max: 2MB per
                                            file</small>
                                    </div>
                                    <div id="preview-gambar" class="d-flex flex-wrap gap-2"></div>
                                </div>
                            </div>

                            <!-- Jenis Produk -->
                            <div class="row mb-4">
                                <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-semibold mb-0 text-primary">Jenis/Variasi Produk</h5>
                                    <button type="button" class="btn btn-sm btn-primary radius-8" id="tambah-jenis">
                                        <iconify-icon icon="ic:baseline-plus" class="icon"></iconify-icon> Tambah Jenis
                                    </button>
                                </div>
                                <div class="col-12">
                                    <div id="jenis-produk-container">
                                        <!-- Jenis produk akan ditambahkan di sini -->
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-center gap-3 mt-2">
                                <a href="{{ route("produk.index") }}" class="btn  btn-lg btn-danger">
                                    <i class="material-icons">cancel</i>
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-lg btn-primary">
                                    <i class="material-icons">save</i>
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("script")
    <script>
        $(document).ready(function() {
            let jenisIndex = 0;

            // Preview Gambar Produk
            $('#gambar_produk').on('change', function(e) {
                $('#preview-gambar').html('');
                const files = e.target.files;

                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview-gambar').append(`
                            <div class="position-relative">
                                <img src="${e.target.result}" class="w-25 h-100-px rounded border">
                            </div>
                        `);
                    }
                    reader.readAsDataURL(files[i]);
                }
            });

            // Tambah Jenis Produk
            $('#tambah-jenis').on('click', function() {
                const html = `
                    <div class="card mb-3 jenis-item" data-index="${jenisIndex}">
                        <div class="card-body p-20">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-semibold mb-0">Jenis Produk #${jenisIndex + 1}</h6>
                                <button type="button" class="btn btn-sm btn-danger radius-8 hapus-jenis">
                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon> Hapus
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Jenis</label>
                                        <input type="text" class="form-control radius-8" name="jenis_nama[]" placeholder="Contoh: Ukuran L Merah">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Warna</label>
                                        <input type="text" class="form-control radius-8" name="jenis_warna[]" placeholder="Contoh: Merah">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Ukuran</label>
                                        <input type="text" class="form-control radius-8" name="jenis_ukuran[]" placeholder="Contoh: L, XL">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Harga <span class="text-danger-600">*</span></label>
                                        <input type="number" class="form-control radius-8" name="jenis_harga[]" placeholder="0" min="0">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jumlah Stok</label>
                                        <input type="number" class="form-control radius-8" name="jenis_jumlah[]" placeholder="0" min="0">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Gambar</label>
                                        <input type="file" class="form-control radius-8 jenis-gambar" name="jenis_gambar[]" accept="image/*">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="preview-jenis-${jenisIndex}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#jenis-produk-container').append(html);
                jenisIndex++;
            });

            // Hapus Jenis Produk
            $(document).on('click', '.hapus-jenis', function() {
                $(this).closest('.jenis-item').remove();
            });

            // Preview Gambar Jenis
            $(document).on('change', '.jenis-gambar', function(e) {
                const index = $(this).closest('.jenis-item').data('index');
                const previewContainer = $(`.preview-jenis-${index}`);
                previewContainer.html('');

                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.html(`
                            <img src="${e.target.result}" class="w-25 h-100-px rounded border mt-2">
                        `);
                    }
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        });
    </script>
@endpush
