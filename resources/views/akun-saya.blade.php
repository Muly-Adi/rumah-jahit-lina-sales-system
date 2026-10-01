@extends("template")

@section("title", "Akun Saya - Aksesoris Ria")

@section("body")
    <!-- My Account -->
    <section class="tf-sp-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="wrap-sidebar-account">
                        <ul class="my-account-nav content-append">
                            <li><span class="my-account-nav-item active">Dashboard</span></li>
                            <li><a href="{{ route("akun.pesanan") }}" class="my-account-nav-item">Pesanan</a></li>
                            <li><a href="{{ route("akun.alamat") }}" class="my-account-nav-item">Alamat</a></li>
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
                    <div class="my-account-content account-dashboard">
                        <div class="mb_60">
                            <h3 class="fw-semibold mb-20">Halo {{ Auth::user()->nama ?? "Pengguna" }}</h3>
                            <p>
                                Dari dashboard akun Anda, Anda dapat melihat
                                <a class="text-dark link fw-medium" href="{{ route("akun.pesanan") }}">
                                    pesanan terbaru
                                </a>
                                , mengelola
                                <a class="text-dark link fw-medium" href="{{ route("akun.alamat") }}">
                                    alamat pengiriman dan pembayaran
                                </a>
                                , dan
                                <a class="text-dark link fw-medium" href="{{ route("akun.edit") }}">
                                    mengedit kata sandi dan detail akun Anda
                                </a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /My Account -->


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
