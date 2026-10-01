@extends("template")
@section("title", "Beranda - RJ Lina")
@section("body")
    <!-- Breakcrumbs -->
    <div class="tf-sp-1">
        <div class="container">
            <ul class="breakcrumbs">
                <li>
                    <a href="{{ route("home") }}" class="body-small link">
                        Home
                    </a>
                </li>
                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>
                <li>
                    <span class="body-small">Produk</span>
                </li>
            </ul>
        </div>
    </div>
    <!-- /Breakcrumbs -->

    <div class="tf-product-view-content wrapper-control-shop">

        <div class="canvas-filter-product sidebar-filter handle-canvas left">
            <div class="canvas-wrapper">
                <div class="canvas-header d-flex d-xl-none">
                    <h5 class="title">Filter</h5>
                    <span class="icon-close link icon-close-popup close-filter" data-bs-dismiss="offcanvas"></span>
                </div>
                <div class="canvas-body">
                    <div class="facet-categories">
                        <h6 class="title fw-medium">Seluruh Kategori</h6>
                        <ul>
                            <li>
                                <a href="{{ route("home") }}" class="{{ !request("kategori") ? "active" : "" }}">
                                    Semua Produk <i class="icon-arrow-right"></i>
                                </a>
                            </li>
                            @forelse ($kategori as $item)
                                <li>
                                    <a href="{{ route("home", ["kategori" => $item->id_kategori] + request()->except("kategori")) }}"
                                        class="{{ request("kategori") == $item->id_kategori ? "active" : "" }}">
                                        {{ $item->nama }} <i class="icon-arrow-right"></i>
                                    </a>
                                </li>
                            @empty
                                <li><a href="#">Kategori Kosong <i class="icon-arrow-right"></i></a></li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="canvas-bottom d-flex d-xl-none">
                    <a href="{{ route("home") }}" class="tf-btn btn-reset w-100">
                        <span class="caption text-white">Reset Filters</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="content-area">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="tf-shop-control flex-wrap gap-10">
                <div class="d-flex align-items-center gap-10 w-100">
                    <button id="filterShop" class="tf-btn-filter d-flex d-xl-none">
                        <span class="icon icon-filter">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#121212"
                                viewBox="0 0 256 256">
                                <path
                                    d="M176,80a8,8,0,0,1,8-8h32a8,8,0,0,1,0,16H184A8,8,0,0,1,176,80ZM40,88H144v16a8,8,0,0,0,16,0V56a8,8,0,0,0-16,0V72H40a8,8,0,0,0,0,16Zm176,80H120a8,8,0,0,0,0,16h96a8,8,0,0,0,0-16ZM88,144a8,8,0,0,0-8,8v16H40a8,8,0,0,0,0,16H80v16a8,8,0,0,0,16,0V152A8,8,0,0,0,88,144Z">
                                </path>
                            </svg>
                        </span>
                        <span class="body-md-2 fw-medium">Filter</span>
                    </button>

                    <!-- Mobile Search -->
                    <div class="flex-grow-1 d-lg-none">
                        <form action="{{ route("home") }}" method="GET" class="form-search-mobile">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari produk..."
                                    value="{{ request("search") }}" autocomplete="off">
                                <button type="submit" class="btn btn-primary">
                                    <i class="icon-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tf-control-view flat-title-tab-product flex-wrap">
                    <form action="{{ route("home") }}" method="get" class="d-inline-block">
                        @foreach (request()->except("sort", "page") as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach

                        <select name="show" class="form-select" onchange="this.form.submit()">
                            <option value="5" {{ request("show") == "5" ? "selected" : "" }}>Tampilkan: 5</option>
                            <option value="10" {{ request("show") == "10" ? "selected" : "" }}>Tampilkan: 10</option>
                            <option value="15" {{ request("show") == "15" ? "selected" : "" }}>Tampilkan: 15</option>
                            <option value="20" {{ request("show") == "20" ? "selected" : "" }}>Tampilkan: 20</option>
                            <option value="50" {{ !request("show") || request("show") == "50" ? "selected" : "" }}>
                                Tampilkan: 50</option>
                        </select>
                    </form>
                    <form action="{{ route("home") }}" method="GET" class="d-inline-block">
                        @foreach (request()->except("sort", "page") as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach

                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="" {{ !request("sort") ? "selected" : "" }}>Urutkan berdasarkan: <i
                                    class="icon-arrow-down fs-10"></i></option>
                            <option value="a-z" {{ request("sort") == "a-z" ? "selected" : "" }}>Nama, A-Z
                            </option>
                            <option value="z-a" {{ request("sort") == "z-a" ? "selected" : "" }}>Nama, Z-A
                            </option>
                            <option value="price-low-high" {{ request("sort") == "price-low-high" ? "selected" : "" }}>
                                Harga, rendah ke tinggi</option>
                            <option value="price-high-low" {{ request("sort") == "price-high-low" ? "selected" : "" }}>
                                Harga, tinggi ke rendah</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="meta-filter-shop"
                style="{{ request()->hasAny(["kategori", "sort", "show", "search"]) ? "" : "display: none;" }}">
                {{-- <div id="product-count-grid" class="count-text">
                    Showing {{ $produk->firstItem() ?? 0 }} - {{ $produk->lastItem() ?? 0 }} of {{ $produk->total() }}
                    products
                </div> --}}
                <div id="applied-filters">
                    @if (request("search"))
                        <span class="filter-tag">
                            Pencarian: "{{ request("search") }}"
                            <a href="{{ route("home", request()->except("search", "page")) }}" class="remove-filter">
                                <i class="icon icon-close"></i>
                            </a>
                        </span>
                    @endif

                    @if (request("kategori"))
                        @php
                            $selectedKategori = $kategori->firstWhere("id", request("kategori"));
                        @endphp
                        @if ($selectedKategori)
                            <span class="filter-tag">
                                Kategori: {{ $selectedKategori->nama }}
                                <a href="{{ route("home", request()->except("kategori", "page")) }}" class="remove-filter">
                                    <i class="icon icon-close"></i>
                                </a>
                            </span>
                        @endif
                    @endif
                </div>
                @if (request()->hasAny(["kategori", "search"]))
                    <a href="{{ route("home") }}" class="remove-all-filters">
                        <span class="caption">REMOVE ALL</span>
                        <i class="icon icon-close"></i>
                    </a>
                @endif
            </div>
            <div class="gridLayout-wrapper">
                <div class="tf-grid-layout lg-col-4 md-col-3 sm-col-2 flat-grid-product wrapper-shop layout-tabgrid-1"
                    id="gridLayout">
                    @forelse ($produk as $item)
                        <div class="card-product" data-condition="Old" data-brand="steelseri" data-rate="5 Star">
                            <div class="card-product-wrapper">
                                <a href="{{ route("produk.detail", $item->id_produk) }}" class="product-img">
                                    @if ($item->gambarProduk->first())
                                        <img class="img-product lazyload"
                                            src="{{ asset($item->gambarProduk->first()->path_gambar) }}"
                                            data-src="{{ asset($item->gambarProduk->first()->path_gambar) }}"
                                            alt="image-product">
                                        <img class="img-hover lazyload"
                                            src="{{ asset($item->gambarProduk->first()->path_gambar) }}"
                                            data-src="{{ asset($item->gambarProduk->first()->path_gambar) }}"
                                            alt="image-product">
                                    @else
                                        <img class="img-product lazyload" src="{{ asset("home/images/no-image.jpg") }}"
                                            data-src="{{ asset("home/images/no-image.jpg") }}" alt="no-image">
                                        <img class="img-hover lazyload" src="{{ asset("home/images/no-image.jpg") }}"
                                            data-src="{{ asset("home/images/no-image.jpg") }}" alt="no-image">
                                    @endif
                                </a>
                            </div>
                            <div class="card-product-info">
                                <div class="box-title">
                                    <div>
                                        {{-- <p class="product-tag caption text-main-2 d-none">Headphone</p> --}}
                                        <a href="{{ route("produk.detail", $item->id_produk) }}"
                                            class="name-product body-md-2 fw-semibold link">
                                            {{ $item->nama }}
                                        </a>
                                    </div>
                                    <p class="price-wrap fw-medium">
                                        <span class="new-price price-text fw-medium">Rp.
                                            {{ number_format($item->harga, 0, ",", ".") }}</span>
                                    </p>
                                    <p class="text-muted">{{ $item->deskripsi }}</p>
                                    <p class="caption text-muted">{{ $item->totalTerjual }} Terjual</p>
                                </div>
                                <div class="box-infor-detail">
                                    <ul class="list-computer-memory">
                                        @php
                                            $totalJenis = $item->jenisProduk->count();
                                        @endphp

                                        @if ($totalJenis > 3)
                                            @foreach ($item->jenisProduk->take(2) as $itemJenis)
                                                <li>
                                                    <p class="caption">{{ $itemJenis->nama }}</p>
                                                </li>
                                            @endforeach
                                            <li>
                                                <p class="caption">{{ $totalJenis - 2 }}+</p>
                                            </li>
                                        @else
                                            @foreach ($item->jenisProduk as $itemJenis)
                                                <li>
                                                    <p class="caption">{{ $itemJenis->nama }}</p>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>

                            </div>
                            <div class="card-product-btn">
                                <a href="#shoppingCart" data-bs-toggle="offcanvas" class="tf-btn btn-line w-100">
                                    <span>Add to cart</span>
                                    <i class="icon-cart-2"></i>
                                </a>
                                <div class="box-btn">
                                    <a href="#compare" data-bs-toggle="offcanvas" class="tf-btn-icon style-2 type-black">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9 6.5V9V6.5ZM9 9V11.5V9ZM9 9H11.5H9ZM9 9H6.5H9ZM16.5 9C16.5 9.98491 16.306 10.9602 15.9291 11.8701C15.5522 12.7801 14.9997 13.6069 14.3033 14.3033C13.6069 14.9997 12.7801 15.5522 11.8701 15.9291C10.9602 16.306 9.98491 16.5 9 16.5C8.01509 16.5 7.03982 16.306 6.12987 15.9291C5.21993 15.5522 4.39314 14.9997 3.6967 14.3033C3.00026 13.6069 2.44781 12.7801 2.0709 11.8701C1.69399 10.9602 1.5 9.98491 1.5 9C1.5 7.01088 2.29018 5.10322 3.6967 3.6967C5.10322 2.29018 7.01088 1.5 9 1.5C10.9891 1.5 12.8968 2.29018 14.3033 3.6967C15.7098 5.10322 16.5 7.01088 16.5 9Z"
                                                stroke="#004EC3" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round">
                                            </path>
                                        </svg>
                                        <span class="body-text-3 fw-normal">Compare</span>
                                    </a>
                                    <a href="wishlist.html" class="tf-btn-icon style-2 type-black">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M3.59837 5.26487C3.25014 5.61309 2.97391 6.02649 2.78546 6.48146C2.597 6.93644 2.5 7.42408 2.5 7.91654C2.5 8.409 2.597 8.89664 2.78546 9.35161C2.97391 9.80658 3.25014 10.22 3.59837 10.5682L10 16.9699L16.4017 10.5682C17.105 9.86494 17.5001 8.9111 17.5001 7.91654C17.5001 6.92197 17.105 5.96814 16.4017 5.26487C15.6984 4.5616 14.7446 4.16651 13.75 4.16651C12.7555 4.16651 11.8016 4.5616 11.0984 5.26487L10 6.3632L8.9017 5.26487C8.55348 4.91665 8.14008 4.64042 7.68511 4.45196C7.23013 4.2635 6.74249 4.1665 6.25003 4.1665C5.75757 4.1665 5.26993 4.2635 4.81496 4.45196C4.35998 4.64042 3.94659 4.91665 3.59837 5.26487V5.26487Z"
                                                stroke="#FF3D3D" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round">
                                            </path>
                                        </svg>
                                        <span class="body-text-3 fw-normal">Wishlist</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <div class="no-products-found">
                                <i class="icon-search" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                                <h4>Produk tidak ditemukan</h4>
                                @if (request("search"))
                                    <p class="text-muted">
                                        Tidak ada hasil untuk pencarian "<strong>{{ request("search") }}</strong>"
                                    </p>
                                    <a href="{{ route("home") }}" class="tf-btn btn-primary mt-3">
                                        Lihat Semua Produk
                                    </a>
                                @else
                                    <p class="text-muted">Belum ada produk yang tersedia.</p>
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="d-flex justify-content-center">
                 @if ($produk->hasPages())
                    {{ $produk->links() }}
                @endif
            </div>
        </div>
    </div>

    @section("footer")
        @include("partials.footer")
    @endsection
@endsection
