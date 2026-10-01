@extends("template-dashboard")
@section("title", "Dashboard Admin")
@section("halaman", "Dashboard")
@section("main")
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="page-description">
                    <h2>Dashboard</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3">
                <div class="card widget widget-stats">
                    <div class="card-body">
                        <div class="widget-stats-container d-flex">
                            <div class="widget-stats-icon widget-stats-icon-primary">
                                <i class="material-icons-outlined">storefront</i>
                            </div>
                            <div class="widget-stats-content flex-fill">
                                <span class="widget-stats-title">Total Produk</span>
                                <span class="widget-stats-amount">{{ $totalProduk }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card widget widget-stats">
                    <div class="card-body">
                        <div class="widget-stats-container d-flex">
                            <div class="widget-stats-icon widget-stats-icon-warning">
                                <i class="material-icons-outlined">person</i>
                            </div>
                            <div class="widget-stats-content flex-fill">
                                <span class="widget-stats-title">Total Customer</span>
                                <span class="widget-stats-amount">{{ $totalCustomer }}</span>
                                <span class="widget-stats-info">Pelanggan Terdaftar</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card widget widget-stats">
                    <div class="card-body">
                        <div class="widget-stats-container d-flex">
                            <div class="widget-stats-icon widget-stats-icon-danger">
                                <i class="material-icons-outlined">attach_money</i>
                            </div>
                            <div class="widget-stats-content flex-fill">
                                <span class="widget-stats-title">Total Invoice</span>
                                <span class="widget-stats-amount">{{ $totalInvoice }}</span>
                                @if ($invoicePending > 0)
                                    <span class="widget-stats-info">{{ $invoicePending }} pending</span>
                                @else
                                    <span class="widget-stats-info">Tidak ada pending</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card widget widget-stats">
                    <div class="card-body">
                        <div class="widget-stats-container d-flex">
                            <div class="widget-stats-icon widget-stats-icon-success">
                                <i class="material-icons-outlined">group</i>
                            </div>
                            <div class="widget-stats-content flex-fill">
                                <span class="widget-stats-title">Total Pengguna</span>
                                <span class="widget-stats-amount">{{ $totalPengguna }}</span>
                                <span class="widget-stats-info">Admin & Karyawan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card widget widget-stats-large">
                    <div class="row">
                        <div class="col-12">
                            <div class="widget-stats-large-chart-container">
                                <div class="card-header">
                                    <h5 class="card-title">Transaksi & Pendapatan<span
                                            class="badge badge-success badge-style-light">30 Hari Terakhir</span></h5>
                                </div>
                                <div class="card-body">
                                    <div id="apex-transaksi"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Stok Produk Rendah -->
        <div class="row gy-4 mb-24">
            <div class="col-12">
                <div class="card h-100 radius-8 border-0">
                    <div
                        class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                        <h6 class="text-lg fw-semibold mb-0 card-title">
                            <iconify-icon
                                icon="solar:danger-triangle-bold-duotone"class="text-danger-600 me-2"></iconify-icon>
                            Peringatan Stok Produk (≤ 5 Unit)
                        </h6>
                        <span class="badge bg-danger-100 text-danger-600">{{ $stokProduk->count() }} Item</span>
                    </div>
                    <div class="card-body p-24">
                        @if ($stokProduk->count() > 0)
                            <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                                <iconify-icon icon="solar:info-circle-bold" class="text-warning-600 me-2"
                                    style="font-size: 20px;"></iconify-icon>
                                <div>
                                    <strong>Perhatian!</strong> Produk-produk berikut memiliki stok ≤ 5 unit. Segera
                                    lakukan restok!
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle mb-0">
                                    <thead class="bg-base">
                                        <tr>
                                            <th class="text-center" style="width: 50px;">No</th>
                                            <th>Nama Produk</th>
                                            <th>Jenis Produk</th>
                                            <th class="text-center" style="width: 150px;">Stok Tersisa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stokProduk as $index => $item)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>
                                                    <span class="fw-semibold text-primary">{{ $item["nama_produk"] }}</span>
                                                </td>
                                                <td>{{ $item["nama_jenis"] ?? "Produk Utama" }}</td>
                                                <td class="text-center">
                                                    <span class="fw-bold text-danger-600" style="font-size: 18px;">
                                                        {{ $item["stok"] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <p class="text-success fw-semibold mt-3 mb-1">Semua Stok Aman!</p>
                                <p class="text-secondary-light mb-0">Tidak ada produk dengan stok ≤ 5 unit</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- User Info & Quick Actions -->
        <div class="row mt-4">
            <!-- Detail Login -->
            <div class="col">
                <div class="card h-100 radius-8 border-0">
                    <div class="card-header border-bottom bg-base py-16 px-24">
                        <h6 class="text-lg fw-semibold mb-0 card-title">Detail Login</h6>
                    </div>
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center gap-3 mb-20">
                            <img src="{{ Auth::user()->foto ? asset(Auth::user()->foto) : asset("admin/images/avatars/avatar.png") }}"
                                alt="User" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                            <div>
                                <h6 class="mb-1 fw-semibold">{{ Auth::user()->nama }}</h6>
                                <p class="mb-0 text-sm text-secondary-light">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="px-0 py-2 text-secondary-light" style="width: 140px;">Username
                                        </td>
                                        <td class="px-0 py-2">: {{ Auth::user()->username }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 py-2 text-secondary-light">No. HP</td>
                                        <td class="px-0 py-2">: {{ Auth::user()->no_hp ?? "-" }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 py-2 text-secondary-light">Alamat</td>
                                        <td class="px-0 py-2">: {{ Auth::user()->alamat ?? "-" }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-0 py-2 text-secondary-light">Role</td>
                                        <td class="px-0 py-2">: <span
                                                class="badge badge-primary">{{ ucfirst(Auth::user()->role) }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("script")
    <script>
        // Data dari backend
        var chartDates = @json($chartDates);
        var chartTotals = @json($chartTotals);
        var chartRevenue = @json($chartRevenue);

        // Chart Transaksi & Pendapatan
        var options = {
            chart: {
                height: 350,
                type: 'bar',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            series: [{
                name: 'Jumlah Transaksi',
                data: chartTotals
            }, {
                name: 'Pendapatan (Rp)',
                data: chartRevenue
            }],
            xaxis: {
                categories: chartDates,
                labels: {
                    style: {
                        colors: '#9ca3af'
                    }
                }
            },
            yaxis: [{
                title: {
                    text: 'Jumlah Transaksi'
                },
                labels: {
                    style: {
                        colors: '#9ca3af'
                    }
                }
            }, {
                opposite: true,
                title: {
                    text: 'Pendapatan (Rp)'
                },
                labels: {
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    },
                    style: {
                        colors: '#9ca3af'
                    }
                }
            }],
            colors: ['#3b82f6', '#10b981'],
            fill: {
                opacity: 1
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right'
            },
            grid: {
                borderColor: '#e5e7eb'
            },
            tooltip: {
                y: [{
                    formatter: function(val) {
                        return val + " transaksi";
                    }
                }, {
                    formatter: function(val) {
                        return "Rp " + val.toLocaleString('id-ID');
                    }
                }]
            }
        };

        var chart = new ApexCharts(document.querySelector("#apex-transaksi"), options);
        chart.render();
    </script>
@endpush
