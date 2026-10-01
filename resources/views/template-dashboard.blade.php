<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Responsive Admin Dashboard Template">
    <meta name="keywords" content="admin,dashboard">
    <meta name="author" content="stacks">
    <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title -->
    <title>@yield("title")</title>

    <!-- Styles -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp"
        rel="stylesheet">
    <link href="{{ asset("admin/plugins/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet">
    <link href="{{ asset("admin/plugins/perfectscroll/perfect-scrollbar.css") }}" rel="stylesheet">
    <link href="{{ asset("admin/plugins/pace/pace.css") }}" rel="stylesheet">
    <link href="{{ asset("admin/plugins/highlight/styles/github-gist.css") }}" rel="stylesheet">
    <link href="{{ asset("admin/plugins/datatables/datatables.min.css") }}" rel="stylesheet">

    <!-- Theme Styles -->
    <link href="{{ asset("admin/css/main.min.css") }}" rel="stylesheet">
    <link href="{{ asset("admin/css/custom.css") }}" rel="stylesheet">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset("admin/images/neptune.png") }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset("admin/images/neptune.png") }}" />

    <!-- Custom Sidebar Styles -->
    <style>
        /* Penyesuaian minimal untuk logo di atas profil */
        .app-sidebar .logo {
            text-align: center;
        }

        .app-sidebar .logo>a {
            display: block;
            margin-bottom: 15px;
        }

        .app-sidebar .logo .logo-text {
            font-size: 22px;
            font-weight: 700;
        }
    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="app align-content-stretch d-flex flex-wrap">
        <div class="app-sidebar">
            <div class="logo">
                <a href="{{ route("dashboard.admin") }}"><span class="logo-text">RJ Lina</span></a>
                <div class="sidebar-user-switcher user-activity-online">
                    <a href="{{ route("profile.index") }}">
                        <img src="{{ asset(Auth::user()->foto ?? "admin/images/avatars/avatar.png") }}">
                        <span class="activity-indicator"></span>
                        <span class="user-info-text">{{ Auth::user()->nama }}<br><span
                                class="user-state-info">Online</span></span>
                    </a>
                </div>
            </div>
            <div class="app-menu">
                <ul class="accordion-menu">
                    <li class="sidebar-title">
                        Master Data
                    </li>
                    <li class="{{ Route::currentRouteName() === "dashboard.admin" ? "active-page" : "" }}">
                        <a href="{{ route("dashboard.admin") }}"
                            class="{{ Route::currentRouteName() === "dashboard.admin" ? "active" : "" }}"><i
                                class="material-icons-two-tone">dashboard</i>Dashboard</a>
                    </li>
                    <li class="{{ request()->routeIs("kategori.*") ? "active-page" : "" }}">
                        <a href="{{ route("kategori.index") }}"
                            class="{{ request()->routeIs("kategori.*") ? "active" : "" }}"><i
                                class="material-icons-two-tone">drafts</i>Data Kategori</a>
                    </li>
                    <li class="{{ request()->routeIs("produk.*") ? "active-page" : "" }}">
                        <a href="{{ route("produk.index") }}"
                            class="{{ request()->routeIs("produk.*") ? "active" : "" }}"><i
                                class="material-icons-two-tone">store</i>Data Produk</a>
                    </li>
                    <li class="{{ request()->routeIs("transaksi.*") ? "active-page" : "" }}">
                        <a href="{{ route("transaksi.index") }}"
                            class="{{ request()->routeIs("transaksi.*") ? "active" : "" }}"><i
                                class="material-icons-two-tone">local_mall</i>Transaksi/Penjualan</a>
                    </li>
                    <li class="{{ request()->routeIs("laporan.*") ? "active-page" : "" }}">
                        <a href="{{ route("laporan.index") }}"
                            class="{{ request()->routeIs("laporan.*") ? "active" : "" }}"><i
                                class="material-icons-two-tone">analytics</i>Laporan Penjualan</a>
                    </li>
                    @if (Auth::user()->role === "admin")
                        <li class="sidebar-title">
                            Data Pengguna
                        </li>
                        <li class="{{ request()->routeIs("admin.*") ? "active-page" : "" }}">
                            <a href="{{ route("admin.index") }}"
                                class="{{ request()->routeIs("admin.*") ? "active" : "" }}"><i
                                    class="material-icons-two-tone">account_box</i>Data Admin</a>
                        </li>
                        <li class="{{ request()->routeIs("karyawan.*") ? "active-page" : "" }}">
                            <a href="{{ route("karyawan.index") }}"
                                class="{{ request()->routeIs("karyawan.*") ? "active" : "" }}"><i
                                    class="material-icons-two-tone">account_box</i>Data Karyawan</a>
                        </li>
                        <li class="{{ request()->routeIs("customer.*") ? "active-page" : "" }}">
                            <a href="{{ route("customer.index") }}"
                                class="{{ request()->routeIs("customer.*") ? "active" : "" }}"><i
                                    class="material-icons-two-tone">account_box</i>Data Customer</a>
                        </li>
                    @endif
                    <li>
                        <form action="{{ route("logout") }}" method="post" class="w-100 p-3">
                            @csrf
                            @method("POST")
                            <button class="btn btn-danger w-100">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <div class="app-container">
            <div class="search">
                <form>
                    <input class="form-control" type="text" placeholder="Type here..." aria-label="Search">
                </form>
                <a href="#" class="toggle-search"><i class="material-icons">close</i></a>
            </div>
            <div class="app-header">
                <nav class="navbar navbar-light navbar-expand-lg">
                    <div class="container-fluid">
                        <div class="navbar-nav" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link hide-sidebar-toggle-button" href="#"><i
                                            class="material-icons">first_page</i></a>
                                </li>
                            </ul>

                        </div>
                    </div>
                </nav>
            </div>
            <div class="app-content">
                <div class="content-wrapper">
                    @yield("main")
                </div>
            </div>
        </div>
    </div>

    <!-- Javascripts -->
    <script src="{{ asset("admin/plugins/jquery/jquery-3.5.1.min.js") }}"></script>
    <script src="{{ asset("admin/plugins/bootstrap/js/bootstrap.min.js") }}"></script>
    <script src="{{ asset("admin/plugins/perfectscroll/perfect-scrollbar.min.js") }}"></script>
    <script src="{{ asset("admin/plugins/pace/pace.min.js") }}"></script>
    <script src="{{ asset("admin/plugins/apexcharts/apexcharts.min.js") }}"></script>
    <script src="{{ asset("admin/js/main.min.js") }}"></script>
    <script src="{{ asset("admin/js/custom.js") }}"></script>
    <script src="{{ asset("admin/js/pages/dashboard.js") }}"></script>
    <script src="{{ asset("admin/plugins/highlight/highlight.pack.js") }}"></script>
    <script src="{{ asset("admin/plugins/datatables/datatables.min.js") }}"></script>
    <script src="{{ asset("admin/js/pages/datatables.js") }}"></script>
    <script src="{{ asset("admin/js/pages/widgets.js") }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack("script")
</body>

</html>
