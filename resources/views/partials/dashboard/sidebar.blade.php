<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route("dashboard.admin") }}" class="sidebar-logo">
            <h6>Ria Aksesoris</h6>
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li class="{{ Route::currentRouteName() === "dashboard" ? "active-page" : "" }}">
                <a href="{{ route("dashboard.admin") }}">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->routeIs("kategori.*") ? "active-page" : "" }}">
                <a href="{{ route("kategori.index") }}">
                    <iconify-icon icon="mage:email" class="menu-icon"></iconify-icon>
                    <span>Data Kategori</span>
                </a>
            </li>
            <li class="{{ request()->routeIs("produk.*") ? "active-page" : "" }}">
                <a href="{{ route("produk.index") }}">
                    <iconify-icon icon="flowbite:store-outline" class="menu-icon"></iconify-icon>
                    <span>Data Produk</span>
                </a>
            </li>
            <li class="{{ request()->routeIs("transaksi.*") ? "active-page" : "" }}">
                <a href="{{ route("transaksi.index") }}">
                    <iconify-icon icon="flowbite:cart-outline" class="menu-icon"></iconify-icon>
                    <span>Transaksi/Pesanan</span>
                </a>
            </li>
            <li class="{{ request()->routeIs("laporan.*") ? "active-page" : "" }}">
                <a href="{{ route("laporan.index") }}">
                    <iconify-icon icon="flowbite:file-chart-bar-outline" class="menu-icon"></iconify-icon>
                    <span>Laporan Penjualan</span>
                </a>
            </li>

            @if (Auth::user()->role === "admin")
                <li class="sidebar-menu-group-title">Data Pengguna</li>
                <li class="{{ request()->routeIs("admin.*") ? "active-page" : "" }}">
                    <a href="{{ route("admin.index") }}">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Data Admin</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs("karyawan.*") ? "active-page" : "" }}">
                    <a href="{{ route("karyawan.index") }}">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Data Karyawan</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs("customer.*") ? "active-page" : "" }}">
                    <a href="{{ route("customer.index") }}">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Data Customer</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</aside>
