<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">

<head>
    <meta charset="utf-8">
    <title>@yield("title")</title>
    <meta name="author" content="themesflat.com">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Themesflat Onsus, Multipurpose eCommerce Template">

    <!-- font -->
    <link rel="stylesheet" href="{{ asset("home/fonts/font.css") }}">
    <link rel="stylesheet" href="{{ asset("home/icons/icomoon/style.css") }}">
    <!-- css -->
    <link rel="stylesheet" href="https://sibforms.com/forms/end-form/build/sib-styles.css">
    <link rel="stylesheet" href="{{ asset("home/css/bootstrap.min.css") }}">
    <link rel="stylesheet" href="{{ asset("home/css/drift-basic.min.css") }}">
    <link rel="stylesheet" href="{{ asset("home/css/nice-select.css") }}">
    <link rel="stylesheet" href="{{ asset("home/css/photoswipe.css") }}">
    <link rel="stylesheet" href="{{ asset("home/css/swiper-bundle.min.css") }}">
    <link rel="stylesheet" href="{{ asset("home/css/animate.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("home/css/app.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("home/css/filter-custom.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("home/css/cart-custom.css") }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="{{ asset("home/images/logo/short-logo.svg") }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset("home/images/logo/short-logo.svg") }}">

</head>

<body class="preload-wrapper popup-loader">

    <!-- Scroll Top -->
    <button id="goTop">
        <span class="border-progress"></span>
        <span class="icon icon-arrow-right"></span>
    </button>

    <!-- preload -->
    <div class="preload preload-container" id="preload">
        <div class="preload-logo">
            <div class="spinner"></div>
        </div>
    </div>
    <!-- /preload -->

    <div id="wrapper">
        <!-- Top Bar-->

        <!-- /Top Bar -->
        <!-- Header -->
        <header class="tf-header">
            <div class="inner-header line-bt">
                <div class="container">
                    <div class="row">
                        <div class=" col-lg-2 col-6 d-flex align-items-center">
                            <div class="logo-site">
                                <a href="{{ route("home") }}">
                                    {{-- <img src="{{ asset("home/images/logo/logo.svg") }}" alt="Logo"> --}}
                                    <h4 class="text-bold">RJ Lina</h4>
                                </a>
                            </div>
                        </div>
                        <div class=" col-lg-8 d-none d-lg-block">
                            <div class="header-center">
                                <form class="form-search-product m-auto" action="{{ route("home") }}" method="GET">
                                    <fieldset>
                                        <input type="text" name="search" placeholder="Cari produk di sini"
                                            value="{{ request("search") }}" autocomplete="off">
                                    </fieldset>
                                    <button type="submit" class="btn-submit-form">
                                        <i class="icon-search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class=" col-lg-2 col-6 d-flex align-items-center justify-content-end">
                            <div class="header-right">
                                <ul class="nav-icon justify-content-xl-center">
                                    @auth
                                        <li class="nav-account">
                                            <a href="{{ route("akun.saya") }}" class="link nav-icon-item link-fill">
                                                <span>
                                                    @if (Auth::user()->foto)
                                                        <img src="{{ asset(Auth::user()->foto) }}" alt="Foto Profil"
                                                            class="rounded-circle"
                                                            style="width: 26px; height: 26px; object-fit: cover; border: 1px solid #ddd;">
                                                    @else
                                                        <svg width="26" height="26" viewBox="0 0 22 23"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M10.9998 11.5283C5.20222 11.5283 0.485352 16.2452 0.485352 22.0428C0.485352 22.2952 0.69017 22.5 0.942518 22.5C1.19487 22.5 1.39968 22.2952 1.39968 22.0428C1.39968 16.749 5.70606 12.4426 10.9999 12.4426C16.2937 12.4426 20.6001 16.749 20.6001 22.0428C20.6001 22.2952 20.8049 22.5 21.0572 22.5C21.3096 22.5 21.5144 22.2952 21.5144 22.0428C21.5144 16.2443 16.7975 11.5283 10.9998 11.5283Z"
                                                                fill="#333E48" stroke="#333E48" stroke-width="0.3" />
                                                            <path
                                                                d="M10.9999 0.5C8.22767 0.5 5.97119 2.75557 5.97119 5.52866C5.97119 8.30174 8.22771 10.5573 10.9999 10.5573C13.772 10.5573 16.0285 8.30174 16.0285 5.52866C16.0285 2.75557 13.772 0.5 10.9999 0.5ZM10.9999 9.64303C8.73146 9.64303 6.88548 7.79705 6.88548 5.52866C6.88548 3.26027 8.73146 1.41429 10.9999 1.41429C13.2682 1.41429 15.1142 3.26027 15.1142 5.52866C15.1142 7.79705 13.2682 9.64303 10.9999 9.64303Z"
                                                                fill="#333E48" stroke="#333E48" stroke-width="0.3" />
                                                        </svg>
                                                    @endif
                                                </span>
                                                <p class="body-small">
                                                    Akun Saya
                                                </p>
                                            </a>
                                        </li>
                                        <li class="nav-cart">
                                            <a href="{{ route('cart') }}"
                                                class="link link-fill nav-icon-item relative">
                                                <span>
                                                    <svg width="26" height="26" viewBox="0 0 26 26"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M8.55865 19.1096C6.8483 19.1096 5.46191 20.496 5.46191 22.2064C5.46191 23.9165 6.8483 25.3029 8.55865 25.3029C10.2688 25.3029 11.6552 23.9165 11.6552 22.2064C11.6534 20.4969 10.2681 19.1114 8.55865 19.1096ZM8.55865 24.1644C7.47712 24.1644 6.60037 23.2877 6.60037 22.2064C6.60037 21.1248 7.47712 20.2481 8.55865 20.2481C9.63996 20.2481 10.5167 21.1248 10.5167 22.2064C10.5167 23.2877 9.63996 24.1644 8.55865 24.1644Z"
                                                            fill="#333E48" />
                                                        <path
                                                            d="M25.436 6.1144H5.33643L4.92663 3.82036C4.67403 2.40819 3.56715 1.30353 2.15453 1.05382L0.668757 0.792113C0.359017 0.736969 0.0635073 0.943536 0.00836329 1.25305C-0.0465584 1.56279 0.159787 1.8583 0.469527 1.91345L1.96086 2.17516C2.90187 2.34193 3.63853 3.07859 3.80529 4.01959L5.82027 15.387C6.05819 16.7472 7.24001 17.7393 8.62083 17.738H20.5746C21.8305 17.7418 22.9396 16.9197 23.3014 15.7172L25.9767 6.84861C26.0263 6.67562 25.995 6.48929 25.8913 6.34209C25.7831 6.19956 25.6147 6.11551 25.436 6.1144ZM22.214 15.3813C21.9992 16.1035 21.3337 16.5975 20.5804 16.5938H8.62661C7.79745 16.596 7.08769 15.9994 6.94739 15.182L5.54144 7.24707H24.6731L22.214 15.3813Z"
                                                            fill="#333E48" />
                                                        <path
                                                            d="M20.5123 19.1096C18.8019 19.1096 17.4155 20.496 17.4155 22.2064C17.4155 23.9165 18.8019 25.3029 20.5123 25.3029C22.2224 25.3029 23.6088 23.9165 23.6088 22.2064C23.607 20.4969 22.2217 19.1114 20.5123 19.1096ZM20.5123 24.1644C19.4307 24.1644 18.554 23.2877 18.554 22.2064C18.554 21.1248 19.4307 20.2481 20.5123 20.2481C21.5936 20.2481 22.4703 21.1248 22.4703 22.2064C22.4703 23.2877 21.5936 24.1644 20.5123 24.1644Z"
                                                            fill="#333E48" />
                                                    </svg>
                                                </span>
                                                <p class="body-small">
                                                    Keranjang
                                                </p>
                                            </a>
                                        </li>
                                    @endauth
                                    @guest
                                        <li class="nav-account">
                                            <a href="#log" data-bs-toggle="modal"
                                                class="link nav-icon-item link-fill">
                                                <span>
                                                    <svg width="26" height="26" viewBox="0 0 22 23"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M10.9998 11.5283C5.20222 11.5283 0.485352 16.2452 0.485352 22.0428C0.485352 22.2952 0.69017 22.5 0.942518 22.5C1.19487 22.5 1.39968 22.2952 1.39968 22.0428C1.39968 16.749 5.70606 12.4426 10.9999 12.4426C16.2937 12.4426 20.6001 16.749 20.6001 22.0428C20.6001 22.2952 20.8049 22.5 21.0572 22.5C21.3096 22.5 21.5144 22.2952 21.5144 22.0428C21.5144 16.2443 16.7975 11.5283 10.9998 11.5283Z"
                                                            fill="#333E48" stroke="#333E48" stroke-width="0.3" />
                                                        <path
                                                            d="M10.9999 0.5C8.22767 0.5 5.97119 2.75557 5.97119 5.52866C5.97119 8.30174 8.22771 10.5573 10.9999 10.5573C13.772 10.5573 16.0285 8.30174 16.0285 5.52866C16.0285 2.75557 13.772 0.5 10.9999 0.5ZM10.9999 9.64303C8.73146 9.64303 6.88548 7.79705 6.88548 5.52866C6.88548 3.26027 8.73146 1.41429 10.9999 1.41429C13.2682 1.41429 15.1142 3.26027 15.1142 5.52866C15.1142 7.79705 13.2682 9.64303 10.9999 9.64303Z"
                                                            fill="#333E48" stroke="#333E48" stroke-width="0.3" />
                                                    </svg>

                                                </span>
                                                <p class="body-small">
                                                    Login
                                                </p>
                                            </a>
                                        </li>
                                    @endguest
                                    <li class="d-flex align-items-center d-xl-none">
                                        <a href="#mobileMenu" class="mobile-button" data-bs-toggle="offcanvas"
                                            aria-controls="mobileMenu">
                                            <span></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </header>
        <!-- /Header -->

        <!-- Main Content -->
        <div class="flat-content">
            <div class="container">
                @yield("body")
            </div>
        </div>
        <!-- /Main Content -->

        <!-- /Iconbox -->
        <!-- Footer -->
        @yield("footer")
        <!-- /Footer -->
        <div class="overlay-filter" id="overlay-filter"></div>
    </div>
    <!-- Login -->
    @include("partials.login")
    <!-- /Login -->
    <!-- Register -->
    @include("partials.register")
    <!-- /Register -->
    <!-- Forgot Password -->
    @include("partials.lupa-password")
    <!-- /Forgot Password -->
    <!-- Shopping Cart -->
    {{-- @include("partials.shopping-cart") --}}
    <!-- /Shopping Cart -->

    <!-- Mobile Menu -->
    <div class="offcanvas offcanvas-start canvas-mb" id="mobileMenu">
        <span class="icon-close btn-close-mb link" data-bs-dismiss="offcanvas"></span>
        <div class="logo-site">
            <a href="{{ route("home") }}">
                <img src="{{ asset("images/logo/logo.svg") }}" alt="">
            </a>
        </div>
        <div class="mb-canvas-content">
            <div class="mb-body">
                <div class="flat-animate-tab">
                    <div class="flat-title-tab-nav-mobile">
                        <ul class="menu-tab-line" role="tablist">
                            <li class="nav-tab-item" role="presentation">
                                <a href="#main-menu" class="tab-link link fw-semibold active"
                                    data-bs-toggle="tab">Menu</a>
                            </li>
                            <li class="br-line type-vertical bg-line h23"></li>
                            <li class="nav-tab-item" role="presentation">
                                <a href="#category" class="tab-link link fw-semibold" data-bs-toggle="tab">Kategori</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active show" id="main-menu" role="tabpanel">
                            <div class="mb-content-top">
                                <form class="form-search">
                                    <fieldset>
                                        <input class="" type="text" placeholder="Search for anything"
                                            name="text" tabindex="2" value="" aria-required="true"
                                            required="">
                                    </fieldset>
                                    <button type="submit" class="button-submit">
                                        <i class="icon-search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane" id="category" role="tabpanel">
                            <div class="mb-content-top">
                                <ul class="nav-ul-mb">
                                    @forelse ($kategori as $item)
                                        <li class="nav-mb-item">
                                            <a href="{{ route("home", ["kategori" => $item->id_kategori] + request()->except("kategori")) }}" class="mb-menu-link"><span>{{ $item->nama }}</span></a>
                                        </li>
                                    @empty
                                        <li class="nav-mb-item">
                                            <a href="#" class="mb-menu-link"><span>Kategori Kosong</span></a>
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Mobile Menu -->

    <!-- Javascript -->
    <script src="{{ asset("home/js/bootstrap.min.js") }}"></script>
    <script src="{{ asset("home/js/jquery.min.js") }}"></script>
    <script src="{{ asset("home/js/jquery.nice-select.js") }}"></script>
    <script src="{{ asset("home/js/swiper-bundle.min.js") }}"></script>
    <script src="{{ asset("home/js/carousel.js") }}"></script>
    <script src="{{ asset("home/js/bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("home/js/lazysize.min.js") }}"></script>
    <script src="{{ asset("home/js/count-down.js") }}"></script>
    <script src="{{ asset("home/js/drift.min.js") }}"></script>
    <script src="{{ asset("home/js/wow.min.js") }}"></script>
    <script src="{{ asset("home/js/multiple-modal.js") }}"></script>
    <script src="{{ asset("home/js/infinityslide.js") }}"></script>
    <script src="{{ asset("home/js/shop.js") }}"></script>
    <script src="{{ asset("home/js/main.js") }}"></script>
    <script src="{{ asset("home/js/sibforms.js") }}" defer></script>
    <script src="{{ asset("home/js/filter-custom.js") }}"></script>
    <script type="module" src="{{ asset("home/js/zoom.js") }}"></script>
    <script src="{{ asset("home/js/cart.js") }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack("scripts")
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
        window.REQUIRED_CODE_ERROR_MESSAGE = 'Please choose a country code';
        window.LOCALE = 'en';
        window.EMAIL_INVALID_MESSAGE = window.SMS_INVALID_MESSAGE =
            "The information provided is invalid. Please review the field format and try again.";

        window.REQUIRED_ERROR_MESSAGE = "This field cannot be left blank. ";

        window.GENERIC_INVALID_MESSAGE =
            "The information provided is invalid. Please review the field format and try again.";

        window.translation = {
            common: {
                selectedList: '{quantity} list selected',
                selectedLists: '{quantity} lists selected'
            }
        };

        var AUTOHIDE = Boolean(0);
    </script>
</body>

</html>
