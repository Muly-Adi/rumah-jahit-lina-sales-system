@extends("template")

@section("title", "Edit Akun - Aksesoris Ria")

@section("body")
    <!-- My Account Edit -->
    <section class="tf-sp-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="wrap-sidebar-account">
                        <ul class="my-account-nav content-append">
                            <li><a href="{{ route("akun.saya") }}" class="my-account-nav-item">Dashboard</a></li>
                            <li><a href="{{ route("akun.pesanan") }}" class="my-account-nav-item">Pesanan</a></li>
                            <li><a href="{{ route("akun.alamat") }}" class="my-account-nav-item">Alamat</a></li>
                            <li><span class="my-account-nav-item active">Detail Akun</span></li>
                            <li>
                                <form action="{{ route("logout") }}" method="POST">
                                    @csrf
                                    <button type="submit" class="my-account-nav-item"
                                        style="border: none; background: none; cursor: pointer; text-align: left; width: 100%;">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="my-account-content account-edit">
                        <h3 class="fw-semibold mb-30">Detail Akun</h3>

                        @if (session("success"))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session("success") }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route("akun.update") }}" method="POST" class="form-edit-account"
                            enctype="multipart/form-data">
                            @csrf

                            {{-- Foto Profil --}}
                            <div class="mb-4">
                                <label class="fw-semibold body-md-2 mb-2">Foto Profil</label>
                                <div class="d-flex align-items-start gap-3">
                                    <div class="profile-photo-preview">
                                        @if (Auth::user()->foto)
                                            <img src="{{ asset(Auth::user()->foto) }}" alt="Foto Profil" id="preview-foto"
                                                class="rounded-circle"
                                                style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #ddd;">
                                        @else
                                            <div id="preview-foto"
                                                class="rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 100px; height: 100px; background-color: #f0f0f0; border: 2px solid #ddd;">
                                                <svg width="50" height="50" viewBox="0 0 22 23" fill="none">
                                                    <path
                                                        d="M10.9998 11.5283C5.20222 11.5283 0.485352 16.2452 0.485352 22.0428C0.485352 22.2952 0.69017 22.5 0.942518 22.5C1.19487 22.5 1.39968 22.2952 1.39968 22.0428C1.39968 16.749 5.70606 12.4426 10.9999 12.4426C16.2937 12.4426 20.6001 16.749 20.6001 22.0428C20.6001 22.2952 20.8049 22.5 21.0572 22.5C21.3096 22.5 21.5144 22.2952 21.5144 22.0428C21.5144 16.2443 16.7975 11.5283 10.9998 11.5283Z"
                                                        fill="#999" stroke="#999" stroke-width="0.3" />
                                                    <path
                                                        d="M10.9999 0.5C8.22767 0.5 5.97119 2.75557 5.97119 5.52866C5.97119 8.30174 8.22771 10.5573 10.9999 10.5573C13.772 10.5573 16.0285 8.30174 16.0285 5.52866C16.0285 2.75557 13.772 0.5 10.9999 0.5ZM10.9999 9.64303C8.73146 9.64303 6.88548 7.79705 6.88548 5.52866C6.88548 3.26027 8.73146 1.41429 10.9999 1.41429C13.2682 1.41429 15.1142 3.26027 15.1142 5.52866C15.1142 7.79705 13.2682 9.64303 10.9999 9.64303Z"
                                                        fill="#999" stroke="#999" stroke-width="0.3" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="file" name="foto" id="foto" accept="image/*"
                                            class="form-control mb-2" onchange="previewImage(event)">
                                        <small class="text-muted d-block">Format: JPG, JPEG, PNG, GIF. Maksimal 2MB</small>
                                        @error("foto")
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <hr class="mb-4">

                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset class="mb-4">
                                        <label class="fw-semibold body-md-2 mb-2">Nama Lengkap *</label>
                                        <input type="text" name="nama" value="{{ old("nama", Auth::user()->nama) }}"
                                            required>
                                        @error("nama")
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="mb-4">
                                        <label class="fw-semibold body-md-2 mb-2">Email *</label>
                                        <input type="email" name="email"
                                            value="{{ old("email", Auth::user()->email) }}" required>
                                        @error("email")
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </fieldset>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset class="mb-4">
                                        <label class="fw-semibold body-md-2 mb-2">No. Telepon</label>
                                        <input type="text" name="no_hp"
                                            value="{{ old("no_hp", Auth::user()->no_hp) }}">
                                        @error("no_hp")
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="mb-4">
                                        <label class="fw-semibold body-md-2 mb-2">Username</label>
                                        <input type="text" name="username" value="{{ Auth::user()->username ?? "-" }}">
                                    </fieldset>
                                </div>
                            </div>

                            <h5 class="fw-semibold mb-20 mt-4">Ubah Password</h5>
                            <p class="body-text-3 mb-3">Kosongkan jika tidak ingin mengubah password</p>

                            <div class="row">
                                <div class="col-12">
                                    <fieldset class="mb-4">
                                        <label class="fw-semibold body-md-2 mb-2">Password Saat Ini</label>
                                        <input type="password" name="current_password">
                                        @error("current_password")
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </fieldset>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset class="mb-4">
                                        <label class="fw-semibold body-md-2 mb-2">Password Baru</label>
                                        <input type="password" name="new_password">
                                        @error("new_password")
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="mb-4">
                                        <label class="fw-semibold body-md-2 mb-2">Konfirmasi Password Baru</label>
                                        <input type="password" name="new_password_confirmation">
                                    </fieldset>
                                </div>
                            </div>

                            <div class="d-flex gap-3 mt-4">
                                <button type="submit" class="tf-btn btn-gray">
                                    <span class="text-white">Simpan Perubahan</span>
                                </button>
                                <a href="{{ route("akun.saya") }}" class="tf-btn btn-line">
                                    <span>Batal</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /My Account Edit -->


     <!-- Button Sidebar Accound -->
    <div class="btn-sidebar-mb d-lg-none left">
        <button data-bs-toggle="offcanvas" data-bs-target="#mbSidebar">
            <i class="icon icon-sidebar"></i>
        </button>
    </div>
    <!-- /Button Sidebar Accound -->
    <!-- Sidebar Account-->
    <div class="offcanvas offcanvas-start canvas-sidebar canvas-sidebar-account" id="mbSidebar">
        <div class="canvas-wrapper">
            <div class="canvas-header">
                <h5 class="fw-semibold">SIDEBAR ACCOUNT</h5>
                <span class="icon-close link icon-close-popup" data-bs-dismiss="offcanvas"></span>
            </div>
            <div class="canvas-body sidebar-mobile-append my-account-nav"></div>
        </div>
    </div>
    <!-- /Sidebar Account -->

    @push("scripts")
        <script>
            function previewImage(event) {
                const file = event.target.files[0];
                const preview = document.getElementById('preview-foto');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Jika preview adalah img
                        if (preview.tagName === 'IMG') {
                            preview.src = e.target.result;
                        } else {
                            // Jika preview adalah div, ubah jadi img
                            const newImg = document.createElement('img');
                            newImg.id = 'preview-foto';
                            newImg.src = e.target.result;
                            newImg.className = 'rounded-circle';
                            newImg.style.cssText =
                            'width: 100px; height: 100px; object-fit: cover; border: 2px solid #ddd;';
                            preview.parentNode.replaceChild(newImg, preview);
                        }
                    }
                    reader.readAsDataURL(file);
                }
            }
        </script>
    @endpush
@endsection
