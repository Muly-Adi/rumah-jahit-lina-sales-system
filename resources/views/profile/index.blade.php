@extends('template-dashboard')
@section('title', 'Profil')
@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="page-description page-description-tabbed">
                    <h1>Pengaturan</h1>

                    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab" aria-controls="hoaccountme" aria-selected="true">Akun</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab" aria-controls="security" aria-selected="false">Password</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        @if (session("success"))
            <div class="alert alert-custom alert-indicator-top indicator-success" role="alert">
                <div class="alert-content">
                    <span class="alert-title">Success!</span>
                    <span class="alert-text">{{ session("success") }}</span>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                        <div class="card">
                            <div class="card-body">
                                <!-- Upload Image Start -->
                                <form action="{{ route("profile.update") }}" method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    @method("PUT")
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-2">
                                                <label for="nama"
                                                       class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                                                    Lengkap <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       class="form-control radius-8 @error("nama") is-invalid @enderror"
                                                       id="nama" name="nama"
                                                       value="{{ old("nama", $user->nama) }}"
                                                       placeholder="Enter Full Name">
                                                @error("nama")
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-2">
                                                <label for="username"
                                                       class="form-label fw-semibold text-primary-light text-sm mb-8">Username
                                                    <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       class="form-control radius-8 @error("username") is-invalid @enderror"
                                                       id="username" name="username"
                                                       value="{{ old("username", $user->username) }}"
                                                       placeholder="Enter username">
                                                @error("username")
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-2">
                                                <label for="email"
                                                       class="form-label fw-semibold text-primary-light text-sm mb-8">Email
                                                    <span class="text-danger">*</span></label>
                                                <input type="email"
                                                       class="form-control radius-8 @error("email") is-invalid @enderror"
                                                       id="email" name="email"
                                                       value="{{ old("email", $user->email) }}"
                                                       placeholder="Enter email address">
                                                @error("email")
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-2">
                                                <label for="no_hp"
                                                       class="form-label fw-semibold text-primary-light text-sm mb-8">Telp</label>
                                                <input type="text"
                                                       class="form-control radius-8 @error("no_hp") is-invalid @enderror"
                                                       id="no_hp" name="no_hp"
                                                       value="{{ old("no_hp", $user->no_hp) }}"
                                                       placeholder="Enter phone number">
                                                @error("no_hp")
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-2">
                                                <label for="alamat"
                                                       class="form-label fw-semibold text-primary-light text-sm mb-8">Alamat</label>
                                                <textarea name="alamat" class="form-control radius-8 @error("alamat") is-invalid @enderror" id="alamat"
                                                          rows="3" placeholder="Write address...">{{ old("alamat", $user->alamat) }}</textarea>
                                                @error("alamat")
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center gap-3 mt-3">
                                        <a href="{{ route("dashboard.admin") }}"
                                           class="btn btn-lg btn-outline-danger">
                                            Batal
                                        </a>
                                        <button type="submit" class="btn btn-lg btn-primary">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route("profile.password.update") }}" method="POST">
                                    @csrf
                                    @method("PUT")
                                    <div class="mb-2">
                                        <label for="current-password"
                                               class="form-label fw-semibold text-primary-light text-sm mb-8">Password
                                            Saat Ini <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="password"
                                                   class="form-control radius-8 @error("current_password") is-invalid @enderror"
                                                   id="current-password" name="current_password"
                                                   placeholder="Masukkan Password Saat Ini">
                                            <span
                                                class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                                data-toggle="#current-password"></span>
                                        </div>
                                        @error("current_password")
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <label for="password"
                                               class="form-label fw-semibold text-primary-light text-sm mb-8">Password
                                            Baru <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="password"
                                                   class="form-control radius-8 @error("password") is-invalid @enderror"
                                                   id="password" name="password"
                                                   placeholder="Masukkan Password Baru">
                                            <span
                                                class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                                data-toggle="#password"></span>
                                        </div>
                                        @error("password")
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <label for="password-confirmation"
                                               class="form-label fw-semibold text-primary-light text-sm mb-8">Konfirmasi
                                            Password <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control radius-8"
                                                   id="password-confirmation" name="password_confirmation"
                                                   placeholder="Masukkan Konfirmasi Password">
                                            <span
                                                class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                                data-toggle="#password-confirmation"></span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center gap-3 mt-3">
                                        <a href="{{ route("dashboard.admin") }}"
                                           class="btn btn-lg btn-outline-danger">
                                            Batal
                                        </a>
                                        <button type="submit"
                                            class="btn btn-lg btn-primary">
                                            Perbarui Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
