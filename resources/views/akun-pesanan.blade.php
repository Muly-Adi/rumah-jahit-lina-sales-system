@extends("template")

@section("title", "Pesanan Saya - Aksesoris Ria")

@section("body")
    <!-- My Account Orders -->
    <section class="tf-sp-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="wrap-sidebar-account">
                        <ul class="my-account-nav content-append">
                            <li><a href="{{ route("akun.saya") }}" class="my-account-nav-item">Dashboard</a></li>
                            <li><span class="my-account-nav-item active">Pesanan</span></li>
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
                        <h3 class="fw-semibold mb-30">Pesanan Saya</h3>

                        @if ($orders->count() > 0)
                            <div class="tf-order_history-table">
                                <table class="table_def">
                                    <thead>
                                        <tr>
                                            <th class="title-sidebar fw-medium">Order ID</th>
                                            <th class="title-sidebar fw-medium">Date</th>
                                            <th class="title-sidebar fw-medium">Status</th>
                                            <th class="title-sidebar fw-medium">Total</th>
                                            <th class="title-sidebar fw-medium">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                        @php
                                            $statusPengiriman = $order->status_pengiriman;
                                        @endphp
                                        <tr class="td-order-item">
                                            <td class="body-text-3">#{{ $order->kode_invoice }}</td>
                                            <td class="body-text-3">{{ $order->created_at->format("d M Y") }} </td>
                                            <td class="body-text-3">
                                                @if ($statusPengiriman === "pending")
                                                    <span class="badge bg-secondary">
                                                        Belum Dikirim
                                                    </span>
                                                @elseif ($statusPengiriman === "dikirim")
                                                    <span class="badge bg-info">
                                                        Dikirim
                                                    </span>
                                                @elseif ($statusPengiriman === "diterima")
                                                    <span class="badge bg-success">
                                                        Diterima
                                                    </span>
                                                @elseif ($statusPengiriman === "dibatalkan")
                                                    <span class="badge bg-danger">
                                                        Dibatalkan
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="body-text-3">Rp {{ number_format($order->total_bayar, 0, ",", ".") }}</td>
                                            <td>
                                                @if ($statusPengiriman === "dikirim")
                                                    <form action="{{ route('transaksi.update-shipping', $order->id_invoice) }}" method="post" class="d-inline-flex">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status_pengiriman" value="diterima">
                                                        <button type="submit" class="btn btn-small btn-warning d-inline-flex">
                                                            <span class="text-white">Terima</span>
                                                        </button>
                                                    </form>
                                                @elseif ($statusPengiriman === "diterima")
                                                     <a href="{{ route('order.confirmation', $order->id_invoice) }}#detail" class="btn btn-small btn-success d-inline-flex">
                                                        <span class="text-white">Nilai</span>
                                                    </a>
                                                @endif
                                                <a href="{{ route("order.confirmation", $order->id_invoice) }}" class="btn btn-primary btn-small d-inline-flex">
                                                    <span class="text-white">Detail</span>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $orders->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <svg width="100" height="100" viewBox="0 0 100 100" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="mb-3">
                                    <path
                                        d="M80.6344 72.6641H33.3641C32.8541 72.6646 32.3525 72.5345 31.907 72.2864C31.4615 72.0383 31.0869 71.6803 30.8188 71.2465C30.5507 70.8127 30.398 70.3176 30.3753 69.8081C30.3526 69.2987 30.4606 68.7919 30.6891 68.336L33.4656 62.7844C33.6401 62.4347 33.678 62.0325 33.5719 61.6563L22.0563 21.361C21.7786 20.4019 21.1977 19.5587 20.4005 18.9575C19.6033 18.3564 18.6328 18.0298 17.6344 18.0266H7.78282C7.36822 18.0266 6.97059 18.1913 6.67742 18.4845C6.38425 18.7777 6.21954 19.1753 6.21954 19.5899C6.21954 20.0045 6.38425 20.4021 6.67742 20.6953C6.97059 20.9885 7.36822 21.1532 7.78282 21.1532H17.6359C17.9554 21.1542 18.2658 21.2587 18.5208 21.4511C18.7758 21.6436 18.9615 21.9135 19.05 22.2204L30.3984 61.9313L27.8922 66.9391C27.4257 67.8717 27.2054 68.9081 27.2523 69.9497C27.2991 70.9914 27.6115 72.0038 28.1598 72.8908C28.7081 73.7777 29.4741 74.5098 30.3849 75.0173C31.2958 75.5249 32.3213 75.7911 33.3641 75.7907H80.6344C81.0488 75.7907 81.4462 75.6261 81.7392 75.333C82.0323 75.04 82.1969 74.6426 82.1969 74.2282C82.1969 73.8138 82.0323 73.4163 81.7392 73.1233C81.4462 72.8303 81.0488 72.6641 80.6344 72.6641Z"
                                        fill="#73787D" />
                                </svg>
                                <h5 class="fw-semibold mb-3">Belum Ada Pesanan</h5>
                                <p class="body-text-3 mb-4">Anda belum memiliki pesanan. Mari mulai berbelanja!</p>
                                <a href="{{ route("home") }}" class="tf-btn btn-gray">
                                    <span class="text-white">Mulai Belanja</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /My Account Orders -->


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
