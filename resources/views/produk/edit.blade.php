@extends("template-dashboard")
@section("title", "Edit Data Produk")
@section("main")
    <div class="container-fluid">
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

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Form Edit Produk</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route("produk.update", $produk->id_produk) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method("PUT")
                            <!-- Informasi Produk -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="fw-semibold mb-3 text-primary">Informasi Produk</h5>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-2">
                                        <label for="nama"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Nama Produk <span class="text-danger-600">*</span>
                                        </label>
                                        <input type="text"
                                            class="form-control radius-8 @error("nama") is-invalid @enderror" id="nama"
                                            name="nama" value="{{ old("nama", $produk->nama) }}"
                                            placeholder="Masukkan nama produk">
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
                                                    {{ old("kategori_id", $produk->kategori_id) == $kategori->id_kategori ? "selected" : "" }}>
                                                    {{ $kategori->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error("kategori_id")
                                            <div class="text-danger-600 mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-2">
                                        <label for="harga_modal"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Harga Modal <span class="text-danger-600">*</span>
                                        </label>
                                        <input type="number"
                                            class="form-control radius-8 @error("harga_modal") is-invalid @enderror"
                                            id="harga_modal" name="harga_modal" value="{{ old("harga_modal", $produk->harga_modal) }}"
                                            placeholder="Masukkan harga modal" min="0" step="0.01">
                                        @error("harga_modal")
                                            <div class="text-danger-600 mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-2">
                                        <label for="harga"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Harga <span class="text-danger-600">*</span>
                                        </label>
                                        <input type="number"
                                            class="form-control radius-8 @error("harga") is-invalid @enderror"
                                            id="harga" name="harga" value="{{ old("harga", $produk->harga) }}"
                                            placeholder="Masukkan harga" min="0" step="0.01">
                                        @error("harga")
                                            <div class="text-danger-600 mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-2">
                                        <label for="jumlah_produk"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Jumlah Stok
                                        </label>
                                        <input type="number"
                                            class="form-control radius-8 @error("jumlah_produk") is-invalid @enderror"
                                            id="jumlah_produk" name="jumlah_produk"
                                            value="{{ old("jumlah_produk", $produk->jumlah_produk) }}"
                                            placeholder="Masukkan jumlah stok" readonly>
                                        @error("jumlah_produk")
                                            <div class="text-danger-600 mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-2">
                                        <label for="min_beli"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Minimal Pembelian
                                        </label>
                                        <input type="number"
                                            class="form-control radius-8 @error("min_beli") is-invalid @enderror"
                                            id="min_beli" name="min_beli"
                                            value="{{ old("min_beli", $produk->min_beli) }}"
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
                                            rows="3" placeholder="Masukkan keterangan produk">{{ old("keterangan", $produk->keterangan) }}</textarea>
                                        @error("keterangan")
                                            <div class="text-danger-600 mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Gambar Produk Existing -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="fw-semibold mb-3 text-primary">Gambar Produk Saat Ini</h5>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @foreach ($produk->gambarProduk as $gambar)
                                            <div class="position-relative gambar-item"
                                                data-id="{{ $gambar->id_gambar_produk }}">
                                                <img src="{{ asset($gambar->path_gambar) }}"
                                                    class="w-25 h-100-px rounded border">
                                                <button type="button"
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 hapus-gambar-existing"
                                                    data-id="{{ $gambar->id_gambar_produk }}">
                                                    <span class="material-icons-outlined">
                                                        delete_forever
                                                    </span>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Gambar Baru -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="fw-semibold mb-3 text-primary">Tambah Gambar Baru</h5>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-2">
                                        <input type="file" class="form-control radius-8" id="gambar_produk"
                                            name="gambar_produk[]" accept="image/*" multiple>
                                        <small class="text-secondary-light">Format: JPG, JPEG, PNG. Max: 2MB per
                                            file</small>
                                    </div>
                                    <div id="preview-gambar" class="d-flex flex-wrap gap-2"></div>
                                </div>
                            </div>

                            <!-- Jenis Produk Existing -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="fw-semibold mb-3 text-primary">Jenis/Variasi Produk Saat Ini</h5>
                                </div>
                                <div class="col-12">
                                    @foreach ($produk->jenisProduk as $jenis)
                                        <div class="card mb-3 jenis-existing-item"
                                            data-id="{{ $jenis->id_jenis_produk }}">
                                            <div class="card-body p-20">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6 class="fw-semibold mb-0">Edit Jenis Produk</h6>
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger radius-8 hapus-jenis-existing"
                                                        data-id="{{ $jenis->id_jenis_produk }}">
                                                        <span class="material-icons-outlined">
                                                            delete_forever
                                                        </span>
                                                    </button>
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" name="jenis_existing_id[]"
                                                        value="{{ $jenis->id_jenis_produk }}">
                                                    <div class="col-sm-4">
                                                        <div class="mb-2">
                                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                                Nama Jenis <small class="text-secondary-light">(Opsional)</small>
                                                            </label>
                                                            <input type="text" class="form-control radius-8"
                                                                name="jenis_existing_nama[]" value="{{ $jenis->nama }}"
                                                                placeholder="Contoh: Hitam 4 Inch">
                                                            <small class="text-secondary-light">Kosongkan untuk auto-generate dari warna/ukuran</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="mb-2">
                                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Warna</label>
                                                            <input type="text" class="form-control radius-8"
                                                                name="jenis_existing_warna[]" value="{{ $jenis->warna }}"
                                                                placeholder="Contoh: Hitam">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="mb-2">
                                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Ukuran</label>
                                                            <input type="text" class="form-control radius-8"
                                                                name="jenis_existing_ukuran[]"
                                                                value="{{ $jenis->ukuran }}" placeholder="Contoh: 4 Inch">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="mb-2">
                                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Harga
                                                                <span class="text-danger-600">*</span></label>
                                                            <input type="number" class="form-control radius-8"
                                                                name="jenis_existing_harga[]" value="{{ $jenis->harga }}"
                                                                placeholder="0" min="0">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="mb-2">
                                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jumlah
                                                                Stok</label>
                                                            <input type="number" class="form-control radius-8"
                                                                name="jenis_existing_jumlah[]"
                                                                value="{{ $jenis->jumlah_produk }}" placeholder="0"
                                                                min="0" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="mb-2">
                                                            <label class="form-label fw-semibold text-primary-light text-sm mb-8">Gambar
                                                                Baru (Opsional)</label>
                                                            <input type="file"
                                                                class="form-control radius-8 jenis-existing-gambar"
                                                                name="jenis_existing_gambar_{{ $jenis->id_jenis_produk }}"
                                                                accept="image/*">
                                                            <small class="text-secondary-light">Kosongkan jika tidak ingin
                                                                mengubah gambar</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        @if ($jenis->path_gambar)
                                                            <div class="mb-2">
                                                                <label class="form-label fw-semibold text-primary-light text-sm mb-2">Gambar
                                                                    Saat Ini:</label>
                                                                <img src="{{ asset($jenis->path_gambar) }}"
                                                                    class="w-25 h-100-px rounded border d-block">
                                                            </div>
                                                        @endif
                                                        <div class="preview-existing-{{ $jenis->id_jenis_produk }}"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Tambah Jenis Baru -->
                            <div class="row mb-4">
                                <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="fw-semibold mb-1 text-primary-600">Tambah Jenis/Variasi Baru</h6>
                                        <p class="text-sm text-secondary-light mb-0">
                                            Tambahkan variasi baru. Nama jenis akan otomatis dibuat dari warna/ukuran jika dikosongkan.
                                        </p>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary radius-8" id="tambah-jenis">
                                        Tambah Jenis
                                    </button>
                                </div>
                                <div class="col-12">
                                    <div id="jenis-produk-container"></div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-center gap-3 mt-2">
                                <a href="{{ route("produk.index") }}" class="btn  btn-lg btn-danger">
                                    <i class="material-icons">cancel</i>
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-lg btn-primary">
                                    <i class="material-icons">save</i>
                                    Update
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

            // Preview Gambar Baru
            $('#gambar_produk').on('change', function(e) {
                $('#preview-gambar').html('');
                const files = e.target.files;
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview-gambar').append(`
                            <img src="${e.target.result}" class="w-25 h-100-px rounded border">
                        `);
                    }
                    reader.readAsDataURL(files[i]);
                }
            });

            // Hapus Gambar Existing
            $('.hapus-gambar-existing').on('click', function() {
                const id = $(this).data('id');
                const item = $(this).closest('.gambar-item');

                Swal.fire({
                    title: 'Hapus gambar?',
                    text: "Gambar akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/produk/gambar/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                item.remove();
                                Swal.fire('Terhapus!', 'Gambar berhasil dihapus.',
                                    'success');
                            }
                        });
                    }
                });
            });

            // Hapus Jenis Existing
            $('.hapus-jenis-existing').on('click', function() {
                const id = $(this).data('id');
                const item = $(this).closest('.jenis-existing-item');

                Swal.fire({
                    title: 'Hapus jenis produk?',
                    text: "Jenis produk akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/produk/jenis/${id}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                item.remove();
                                Swal.fire('Terhapus!', 'Jenis produk berhasil dihapus.',
                                    'success');
                            }
                        });
                    }
                });
            });

            // Tambah Jenis Baru
            $('#tambah-jenis').on('click', function() {
                const html = `
                    <div class="card mb-3 jenis-item" data-index="${jenisIndex}">
                        <div class="card-body p-20">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-semibold mb-0">Jenis Produk Baru #${jenisIndex + 1}</h6>
                                <button type="button" class="btn btn-sm btn-danger radius-8 hapus-jenis">
                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon> Hapus
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="mb-20">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Nama Jenis <small class="text-secondary-light">(Opsional)</small>
                                        </label>
                                        <input type="text" class="form-control radius-8" name="jenis_nama[]"
                                            placeholder="Contoh: Hitam 4 Inch">
                                        <small class="text-secondary-light">Kosongkan untuk auto-generate dari warna/ukuran</small>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-20">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Warna</label>
                                        <input type="text" class="form-control radius-8" name="jenis_warna[]" placeholder="Contoh: Hitam">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-20">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Ukuran</label>
                                        <input type="text" class="form-control radius-8" name="jenis_ukuran[]" placeholder="Contoh: 4 Inch">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-20">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Harga <span class="text-danger-600">*</span></label>
                                        <input type="number" class="form-control radius-8" name="jenis_harga[]" placeholder="0" min="0">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-20">
                                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jumlah Stok</label>
                                        <input type="number" class="form-control radius-8" name="jenis_jumlah[]" placeholder="0" min="0">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-20">
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

            // Hapus Jenis Baru
            $(document).on('click', '.hapus-jenis', function() {
                $(this).closest('.jenis-item').remove();
            });

            // Preview Gambar Jenis Baru
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

            // Preview Gambar Jenis Existing yang Diubah
            $(document).on('change', '.jenis-existing-gambar', function(e) {
                const jenisId = $(this).attr('name').split('_').pop();
                const previewContainer = $(`.preview-existing-${jenisId}`);
                previewContainer.html('');

                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.html(`
                            <label class="form-label fw-semibold text-primary-light text-sm mb-2 mt-2">Preview Gambar Baru:</label>
                            <img src="${e.target.result}" class="w-25 h-100-px rounded border d-block">
                        `);
                    }
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        });
    </script>
@endpush
