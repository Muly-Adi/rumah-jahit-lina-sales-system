@extends('template-dashboard')
@section('title', 'Edit Data Karyawan')
@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="section-description section-description-inline">
                <h1>Edit Data Karyawan</h1>
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
                        <h5 class="card-title">Form Edit Karyawan</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route("karyawan.update", $karyawan->id_user) }}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            @method("PUT")
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="nama"
                                               class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Nama Lengkap <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               class="form-control radius-8 @error("nama") is-invalid @enderror" id="nama"
                                               name="nama" value="{{ old("nama", $karyawan->nama) }}"
                                               placeholder="Masukkan nama lengkap">
                                        @error("nama")
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="username"
                                               class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Username <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               class="form-control radius-8 @error("username") is-invalid @enderror"
                                               id="username" name="username"
                                               value="{{ old("username", $karyawan->username) }}"
                                               placeholder="Masukkan username">
                                        @error("username")
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="email"
                                               class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Email <span class="text-danger">*</span>
                                        </label>
                                        <input type="email"
                                               class="form-control radius-8 @error("email") is-invalid @enderror"
                                               id="email" name="email" value="{{ old("email", $karyawan->email) }}"
                                               placeholder="Masukkan email">
                                        @error("email")
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="no_hp"
                                               class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            No HP <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               class="form-control radius-8 @error("no_hp") is-invalid @enderror"
                                               id="no_hp" name="no_hp" value="{{ old("no_hp", $karyawan->no_hp) }}"
                                               placeholder="Masukkan nomor HP">
                                        @error("no_hp")
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="password"
                                               class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Password <small class="text-secondary-light">(Kosongkan jika tidak ingin
                                                mengubah)</small>
                                        </label>
                                        <input type="password"
                                               class="form-control radius-8 @error("password") is-invalid @enderror"
                                               id="password" name="password" placeholder="Masukkan password baru">
                                        @error("password")
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="password_confirmation"
                                               class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Konfirmasi Password
                                        </label>
                                        <input type="password" class="form-control radius-8" id="password_confirmation"
                                               name="password_confirmation" placeholder="Konfirmasi password baru">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-20">
                                        <label for="alamat"
                                               class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Alamat
                                        </label>
                                        <textarea class="form-control radius-8 @error("alamat") is-invalid @enderror" id="alamat" name="alamat"
                                                  rows="3" placeholder="Masukkan alamat">{{ old("alamat", $karyawan->alamat) }}</textarea>
                                        @error("alamat")
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-20">
                                        <label for="foto"
                                               class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Foto Profil
                                        </label>
                                        @if ($karyawan->foto)
                                            <div class="mb-2">
                                                <img src="{{ asset($karyawan->foto) }}" alt="{{ $karyawan->nama }}"
                                                     class="w-25 rounded-circle">
                                            </div>
                                        @endif
                                        <input type="file"
                                               class="form-control radius-8 @error("foto") is-invalid @enderror"
                                               id="foto" name="foto" accept="image/*">
                                        @error("foto")
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                        <small class="text-secondary-light">Format: JPG, JPEG, PNG. Max: 2MB</small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-center gap-3 mt-2">
                                <a href="{{ route("karyawan.index") }}" class="btn  btn-lg btn-danger">
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
