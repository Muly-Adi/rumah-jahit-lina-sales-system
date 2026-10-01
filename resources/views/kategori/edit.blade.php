@extends('template-dashboard')
@section('title', 'Edit Data Kategori')
@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="section-description section-description-inline">
                <h1>Tambah Data Kategori</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Form Tambah Kategori</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route("kategori.update", $kategori->id_kategori) }}" method="POST">
                            @csrf
                            @method("PUT")
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="mb-20">
                                        <label for="nama"
                                               class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Nama Kategori <span class="text-danger-600">*</span>
                                        </label>
                                        <input type="text"
                                               class="form-control radius-8 @error("nama") is-invalid @enderror" id="nama"
                                               name="nama" value="{{ old("nama", $kategori->nama) }}"
                                               placeholder="Masukkan nama kategori">
                                        @error("nama")
                                        <div class="text-danger-600 mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-center gap-3 mt-2">
                                <a href="{{ route("kategori.index") }}" class="btn  btn-lg btn-danger">
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
