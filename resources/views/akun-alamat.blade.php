@extends("template")

@section("title", "Alamat Saya - Aksesoris Ria")

@section("body")
    <!-- My Account Address -->
    <section class="tf-sp-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="wrap-sidebar-account">
                        <ul class="my-account-nav content-append">
                            <li><a href="{{ route("akun.saya") }}" class="my-account-nav-item">Dashboard</a></li>
                            <li><a href="{{ route("akun.pesanan") }}" class="my-account-nav-item">Pesanan</a></li>
                            <li><span class="my-account-nav-item active">Alamat</span></li>
                            <li><a href="{{ route("akun.edit") }}" class="my-account-nav-item">Detail Akun</a></li>
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
                    <div class="my-account-content account-address">
                        <h3 class="fw-semibold mb-30">Alamat Pengiriman</h3>

                        @if (session("success"))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session("success") }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route("akun.alamat.update") }}" method="POST" class="form-address">
                            @csrf

                            <div class="row">
                                <div class="col-12">
                                    <fieldset class="mb-4">
                                        <label class="fw-semibold body-md-2 mb-2">Alamat Lengkap *</label>
                                        <textarea name="alamat" rows="5" required>{{ old("alamat", Auth::user()->alamat) }}</textarea>
                                        @error("alamat")
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                        <small class="text-muted">Masukkan alamat lengkap termasuk nama jalan, nomor rumah,
                                            RT/RW, kelurahan, kecamatan, kota, dan kode pos</small>
                                    </fieldset>
                                </div>
                            </div>

                            <div class="d-flex gap-3 mt-4">
                                <button type="submit" class="tf-btn btn-gray">
                                    <span class="text-white">Simpan Alamat</span>
                                </button>
                                <a href="{{ route("akun.saya") }}" class="tf-btn btn-line">
                                    <span>Batal</span>
                                </a>
                            </div>
                        </form>

                        @if (Auth::user()->alamat)
                            <div class="mt-5">
                                <h5 class="fw-semibold mb-3">Alamat Tersimpan</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <i class="icon-location fs-24 text-primary me-3"></i>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-semibold mb-2">{{ Auth::user()->nama }}</h6>
                                                <p class="body-text-3 mb-2">{{ Auth::user()->no_hp ?? "-" }}</p>
                                                <p class="body-text-3 mb-0">{{ Auth::user()->alamat }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /My Account Address -->

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
@endsection
