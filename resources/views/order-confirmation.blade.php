@extends('template')

@section('title', 'Konfirmasi Pesanan - RJ Lina')

@section('body')

    @php
        $midtransReady =
            filled(config('services.midtrans.clientKey')) &&
            filled(config('services.midtrans.serverKey'));

        $isProduction = (bool) config('services.midtrans.isProduction', false);
    @endphp

    {{-- Breadcrumbs --}}
    <div class="tf-sp-3 pb-0">
        <div class="container">
            <ul class="breakcrumbs">
                <li>
                    <a href="{{ route('home') }}" class="body-small link">
                        Home
                    </a>
                </li>

                <li class="d-flex align-items-center">
                    <i class="icon icon-arrow-right"></i>
                </li>

                <li>
                    <span class="body-small">
                        Konfirmasi Pesanan
                    </span>
                </li>
            </ul>
        </div>
    </div>
    {{-- /Breadcrumbs --}}

    <section class="tf-sp-2">
        <div class="container">

            {{-- Flash Message --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="icon-check"></i>
                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="icon-close"></i>
                    {{ session('error') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Terjadi kesalahan:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                    </button>
                </div>
            @endif

            {{-- Checkout Progress --}}
            <div class="checkout-status tf-sp-2 pt-0">
                <div class="checkout-wrap">

                    <span class="checkout-bar complete"></span>

                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-1"></i>
                        </span>

                        <span class="link body-text-3">
                            Keranjang Belanja
                        </span>
                    </div>

                    <div class="step-payment">
                        <span class="icon">
                            <i class="icon-shop-cart-2"></i>
                        </span>

                        <span class="link body-text-3">
                            Checkout
                        </span>
                    </div>

                    <div class="step-payment active">
                        <span class="icon">
                            <i class="icon-shop-cart-3"></i>
                        </span>

                        <span class="text-secondary body-text-3">
                            Konfirmasi
                        </span>
                    </div>

                </div>
            </div>

            <div class="tf-order-detail">

                {{-- Success Header --}}
                <div class="text-center mb-5">

                    <div class="success-icon mb-3">
                        <svg
                            width="80"
                            height="80"
                            viewBox="0 0 80 80"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg">

                            <circle
                                cx="40"
                                cy="40"
                                r="40"
                                fill="#28a745"
                                opacity="0.1"
                            />

                            <path
                                d="M25 40L35 50L55 30"
                                stroke="#28a745"
                                stroke-width="4"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />

                        </svg>
                    </div>

                    <h3 class="fw-semibold mb-2">
                        Pesanan Berhasil Dibuat!
                    </h3>

                    <p class="text-primary">
                        Terima kasih atas pesanan Anda.
                    </p>

                    <div class="alert alert-success d-inline-block mt-3">
                        <strong>Kode Invoice:</strong>
                        {{ $invoice->kode_invoice }}
                    </div>

                </div>

                {{-- Information Cards --}}
                <div class="row g-4 mb-4">

                    {{-- Shipping Information --}}
                    <div class="col-md-6">

                        <div class="card h-100">
                            <div class="card-body">

                                <h5 class="card-title mb-3">
                                    Informasi Pengiriman
                                </h5>

                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">

                                        <tr>
                                            <td width="140">
                                                <strong>Nama:</strong>
                                            </td>

                                            <td>
                                                {{ $invoice->nama }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>No. HP:</strong>
                                            </td>

                                            <td>
                                                {{ $invoice->no_hp }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>Alamat:</strong>
                                            </td>

                                            <td>
                                                {{ $invoice->alamat }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>Kurir:</strong>
                                            </td>

                                            <td>
                                                {{ $invoice->kurir ?: '-' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>Layanan:</strong>
                                            </td>

                                            <td>
                                                {{ $invoice->layanan_pengiriman ?: '-' }}
                                            </td>
                                        </tr>

                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>

                    {{-- Order Status --}}
                    <div class="col-md-6">

                        <div class="card h-100">
                            <div class="card-body">

                                <h5 class="card-title mb-3">
                                    Status Pesanan
                                </h5>

                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">

                                        <tr>
                                            <td width="170">
                                                <strong>Tanggal:</strong>
                                            </td>

                                            <td>
                                                {{ $invoice->tanggal->format('d M Y') }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>Status Pembayaran:</strong>
                                            </td>

                                            <td>

                                                @if ($invoice->status_pembayaran === 'pending')

                                                    <span class="badge bg-warning text-dark">
                                                        Belum Bayar
                                                    </span>

                                                @elseif ($invoice->status_pembayaran === 'terima')

                                                    <span class="badge bg-success">
                                                        Lunas
                                                    </span>

                                                @else

                                                    <span class="badge bg-danger">
                                                        Ditolak
                                                    </span>

                                                @endif

                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <strong>Status Pengiriman:</strong>
                                            </td>

                                            <td>

                                                @if ($invoice->status_pengiriman === 'dikirim')

                                                    <span class="badge bg-info">
                                                        Dikirim
                                                    </span>

                                                    @if ($invoice->resi)
                                                        <br>

                                                        <small class="text-muted">
                                                            Resi:
                                                            {{ $invoice->resi }}
                                                        </small>
                                                    @endif

                                                @elseif ($invoice->status_pengiriman === 'diterima')

                                                    <span class="badge bg-success">
                                                        Diterima
                                                    </span>

                                                    @if ($invoice->resi)
                                                        <br>

                                                        <small class="text-muted">
                                                            Resi:
                                                            {{ $invoice->resi }}
                                                        </small>
                                                    @endif

                                                @elseif ($invoice->status_pengiriman === 'dibatalkan')

                                                    <span class="badge bg-danger">
                                                        Dibatalkan
                                                    </span>

                                                @else

                                                    <span class="badge bg-secondary">
                                                        Belum Dikirim
                                                    </span>

                                                @endif

                                            </td>
                                        </tr>

                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>

                {{-- Order Items --}}
                <div class="card mb-4">

                    <div class="card-body">

                        <h5 class="card-title mb-3" id="detail">
                            Detail Pesanan
                        </h5>

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead>
                                    <tr>

                                        <th>
                                            Produk
                                        </th>

                                        <th class="text-center">
                                            Jumlah
                                        </th>

                                        <th class="text-end">
                                            Harga
                                        </th>

                                        <th class="text-end">
                                            Subtotal
                                        </th>

                                        @if ($invoice->status_pengiriman === 'diterima')
                                            <th class="text-center">
                                                Aksi
                                            </th>
                                        @endif

                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($items as $item)

                                        @php
                                            $gambarProduk =
                                                $item->produk
                                                    ? $item->produk->gambarProduk->first()
                                                    : null;

                                            $quantity = max((int) $item->jumlah, 1);

                                            /*
                                             * Harga satuan dihitung dari subtotal transaksi.
                                             * Dengan begitu histori pesanan tetap menampilkan
                                             * harga pada saat transaksi dilakukan.
                                             */
                                            $hargaSatuan =
                                                (int) round(
                                                    ((int) $item->subtotal) / $quantity
                                                );

                                            $parts = [];

                                            if ($item->jenisProduk) {

                                                $namaVar =
                                                    trim(
                                                        $item->jenisProduk->nama ?? ''
                                                    );

                                                $warnaVar =
                                                    trim(
                                                        $item->jenisProduk->warna ?? ''
                                                    );

                                                $ukuranVar =
                                                    trim(
                                                        $item->jenisProduk->ukuran ?? ''
                                                    );

                                                if ($namaVar !== '') {

                                                    $containsWarna =
                                                        $warnaVar !== '' &&
                                                        stripos(
                                                            $namaVar,
                                                            $warnaVar
                                                        ) !== false;

                                                    $containsUkuran =
                                                        $ukuranVar !== '' &&
                                                        stripos(
                                                            $namaVar,
                                                            $ukuranVar
                                                        ) !== false;

                                                    if (
                                                        !$containsWarna &&
                                                        !$containsUkuran
                                                    ) {
                                                        $parts[] = $namaVar;
                                                    }
                                                }

                                                if ($warnaVar !== '') {
                                                    $parts[] = $warnaVar;
                                                }

                                                if ($ukuranVar !== '') {
                                                    $parts[] = $ukuranVar;
                                                }
                                            }

                                            $userReview =
                                                $userReviews[$item->produk_id] ?? null;
                                        @endphp

                                        <tr>

                                            <td>

                                                <div class="d-flex align-items-center">

                                                    @if ($gambarProduk)

                                                        <img
                                                            src="{{ asset($gambarProduk->path_gambar) }}"
                                                            alt="{{ $item->produk->nama }}"
                                                            style="
                                                                width: 50px;
                                                                height: 50px;
                                                                object-fit: cover;
                                                                margin-right: 10px;
                                                            "
                                                        >

                                                    @endif

                                                    <div>

                                                        <strong>
                                                            {{ $item->produk->nama }}
                                                        </strong>

                                                        @if (count($parts) > 0)

                                                            <br>

                                                            <small class="text-muted fst-italic">
                                                                {{ implode(' - ', $parts) }}
                                                            </small>

                                                        @endif

                                                    </div>

                                                </div>

                                            </td>

                                            <td class="text-center">
                                                {{ $item->jumlah }}
                                            </td>

                                            <td class="text-end">
                                                Rp.
                                                {{ number_format(
                                                    $hargaSatuan,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </td>

                                            <td class="text-end">
                                                Rp.
                                                {{ number_format(
                                                    $item->subtotal,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </td>

                                            @if ($invoice->status_pengiriman === 'diterima')

                                                <td class="text-center">

                                                    @if ($userReview)

                                                        <div class="d-flex flex-column gap-1">

                                                            <span class="badge bg-success mb-1">
                                                                Sudah Dinilai
                                                            </span>

                                                            <div class="mb-1">

                                                                @for ($i = 1; $i <= 5; $i++)

                                                                    <i
                                                                        class="icon-star {{ $i <= $userReview->rating ? 'text-warning' : 'text-muted' }}"
                                                                        style="font-size: 0.8rem;">
                                                                    </i>

                                                                @endfor

                                                            </div>

                                                            <a
                                                                href="{{ route('ulasan.form', $item->produk->id_produk) }}"
                                                                class="btn btn-sm btn-outline-primary">

                                                                Edit Ulasan

                                                            </a>

                                                        </div>

                                                    @else

                                                        <a
                                                            href="{{ route('ulasan.form', $item->produk->id_produk) }}"
                                                            class="btn btn-info text-white btn-sm">

                                                            Nilai Produk

                                                        </a>

                                                    @endif

                                                </td>

                                            @endif

                                        </tr>

                                    @endforeach

                                </tbody>

                                <tfoot>

                                    <tr>

                                        <td
                                            colspan="3"
                                            class="text-end">

                                            <strong>
                                                Ongkir:
                                            </strong>

                                        </td>

                                        <td class="text-end">

                                            Rp.
                                            {{ number_format(
                                                $invoice->ongkir,
                                                0,
                                                ',',
                                                '.'
                                            ) }}

                                        </td>

                                        @if ($invoice->status_pengiriman === 'diterima')
                                            <td></td>
                                        @endif

                                    </tr>

                                    <tr>

                                        <td
                                            colspan="3"
                                            class="text-end">

                                            <strong>
                                                Total:
                                            </strong>

                                        </td>

                                        <td class="text-end">

                                            <strong class="text-primary fs-5">

                                                Rp.
                                                {{ number_format(
                                                    $invoice->total_bayar,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}

                                            </strong>

                                        </td>

                                        @if ($invoice->status_pengiriman === 'diterima')
                                            <td></td>
                                        @endif

                                    </tr>

                                </tfoot>

                            </table>

                        </div>

                    </div>

                </div>

                {{-- Payment Information --}}
                @if ($invoice->status_pembayaran === 'pending')

                    <div class="card mb-4">
                        <div class="card-body">

                            <h5 class="card-title mb-3">
                                Pembayaran
                            </h5>

                            @if ($midtransReady)

                                <div class="alert alert-info mb-0">

                                    Pesanan Anda belum dibayar.

                                    Klik tombol
                                    <strong>Lakukan Pembayaran</strong>
                                    untuk melanjutkan pembayaran secara aman melalui Midtrans.

                                </div>

                            @else

                                <div class="alert alert-warning mb-0">

                                    <strong>
                                        Pembayaran online belum tersedia.
                                    </strong>

                                    <br>

                                    Konfigurasi Midtrans belum diaktifkan pada environment ini.

                                </div>

                            @endif

                        </div>
                    </div>

                @elseif ($invoice->status_pembayaran === 'terima')

                    <div class="alert alert-success mb-4">

                        <strong>
                            Pembayaran telah diterima.
                        </strong>

                        Pesanan Anda sedang diproses.

                    </div>

                @elseif ($invoice->status_pembayaran === 'tolak')

                    <div class="alert alert-danger mb-4">

                        <strong>
                            Pembayaran tidak dapat diproses.
                        </strong>

                        Silakan hubungi administrator apabila membutuhkan bantuan.

                    </div>

                @endif

                {{-- Action Buttons --}}
                <div
                    class="d-flex flex-wrap justify-content-center gap-2">

                    <a
                        href="{{ route('home') }}"
                        class="tf-btn btn-gray">

                        <span class="text-white">
                            Kembali ke Beranda
                        </span>

                    </a>

                    @if (
                        $invoice->status_pembayaran === 'pending' &&
                        $midtransReady
                    )

                        <button
                            type="button"
                            class="btn btn-success"
                            id="bayar"
                            data-id-invoice="{{ $invoice->id_invoice }}">

                            Lakukan Pembayaran

                        </button>

                    @endif

                    @auth

                        <a
                            href="{{ route('akun.pesanan') }}"
                            class="tf-btn">

                            <span class="text-white">
                                Lihat Pesanan Saya
                            </span>

                        </a>

                    @endauth

                </div>

            </div>

        </div>
    </section>

@endsection


@push('scripts')

    @if (
        $midtransReady &&
        $invoice->status_pembayaran === 'pending'
    )

        <script
            type="text/javascript"
            src="{{ $isProduction
                ? 'https://app.midtrans.com/snap/snap.js'
                : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('services.midtrans.clientKey') }}">
        </script>

        <script type="text/javascript">

            $(document).ready(function () {

                const payButton = $('#bayar');

                const originalButtonText =
                    payButton.html();


                function resetPayButton() {

                    payButton
                        .prop('disabled', false)
                        .html(originalButtonText);
                }


                payButton.on('click', function () {

                    const idInvoice =
                        payButton.data('id-invoice');


                    /*
                    |--------------------------------------------------------------------------
                    | Make sure Midtrans Snap is loaded
                    |--------------------------------------------------------------------------
                    */

                    if (typeof window.snap === 'undefined') {

                        Swal.fire({
                            icon: 'error',
                            title: 'Pembayaran Tidak Tersedia',
                            text: 'Midtrans Snap belum berhasil dimuat. Silakan refresh halaman.'
                        });

                        return;
                    }


                    payButton
                        .prop('disabled', true)
                        .text('Memproses...');


                    /*
                    |--------------------------------------------------------------------------
                    | Request Snap Token
                    |--------------------------------------------------------------------------
                    |
                    | Hanya ID invoice yang dikirim.
                    | Nominal pembayaran diambil oleh server dari database.
                    |
                    */

                    $.ajax({

                        url:
                            '{{ route('pembayaran.bayar') }}',

                        method:
                            'POST',

                        data: {

                            _token:
                                '{{ csrf_token() }}',

                            id_invoice:
                                idInvoice

                        },

                        success: function (response) {

                            if (
                                response.status !== 'success' ||
                                !response.snap_token
                            ) {

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Pembayaran Gagal',
                                    text:
                                        response.message ??
                                        'Gagal mendapatkan token pembayaran.'
                                });

                                resetPayButton();

                                return;
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | Open Midtrans Snap
                            |--------------------------------------------------------------------------
                            */

                            window.snap.pay(
                                response.snap_token,
                                {

                                    onSuccess: function (result) {

                                        /*
                                         * Jangan mempercayai status dari browser.
                                         *
                                         * Server akan mengecek ulang status
                                         * langsung ke API Midtrans.
                                         */

                                        updatePaymentStatus(
                                            '{{ $invoice->kode_invoice }}'
                                        );

                                    },


                                    onPending: function (result) {

                                        Swal.fire({

                                            icon: 'info',

                                            title:
                                                'Menunggu Pembayaran',

                                            text:
                                                'Transaksi dibuat. Selesaikan pembayaran sesuai instruksi Midtrans.',

                                            confirmButtonText:
                                                'OK'

                                        }).then(function () {

                                            window.location.reload();

                                        });

                                    },


                                    onError: function (result) {

                                        Swal.fire({

                                            icon: 'error',

                                            title:
                                                'Pembayaran Gagal',

                                            text:
                                                'Terjadi kesalahan saat memproses pembayaran.'

                                        });

                                        resetPayButton();

                                    },


                                    onClose: function () {

                                        Swal.fire({

                                            icon: 'warning',

                                            title:
                                                'Pembayaran Belum Selesai',

                                            text:
                                                'Jendela pembayaran ditutup sebelum transaksi selesai.'

                                        });

                                        resetPayButton();

                                    }

                                }
                            );

                        },


                        error: function (xhr) {

                            const response =
                                xhr.responseJSON ?? {};

                            Swal.fire({

                                icon: 'error',

                                title:
                                    'Pembayaran Tidak Dapat Diproses',

                                text:
                                    response.message ??
                                    'Terjadi kesalahan saat menghubungi server.'

                            });

                            resetPayButton();

                        }

                    });

                });


                /*
                |--------------------------------------------------------------------------
                | Verify payment status through backend
                |--------------------------------------------------------------------------
                */

                function updatePaymentStatus(kodeInvoice) {

                    Swal.fire({

                        title:
                            'Memverifikasi Pembayaran',

                        text:
                            'Mohon tunggu sebentar...',

                        allowOutsideClick:
                            false,

                        didOpen: function () {

                            Swal.showLoading();

                        }

                    });


                    $.ajax({

                        url:
                            '{{ route('pembayaran.cek-status') }}',

                        method:
                            'PUT',

                        data: {

                            _token:
                                '{{ csrf_token() }}',

                            kode_invoice:
                                kodeInvoice

                        },


                        success: function (response) {

                            if (
                                response.success === true &&
                                response.status === 'lunas'
                            ) {

                                Swal.fire({

                                    icon: 'success',

                                    title:
                                        'Pembayaran Berhasil',

                                    text:
                                        'Pembayaran telah berhasil diverifikasi.',

                                    confirmButtonText:
                                        'Lihat Pesanan'

                                }).then(function () {

                                    window.location.href =
                                        '{{ route('akun.pesanan') }}';

                                });

                                return;
                            }


                            Swal.fire({

                                icon: 'info',

                                title:
                                    'Status Pembayaran',

                                text:
                                    response.message ??
                                    'Pembayaran masih menunggu konfirmasi.',

                                confirmButtonText:
                                    'OK'

                            }).then(function () {

                                window.location.reload();

                            });

                        },


                        error: function (xhr) {

                            const response =
                                xhr.responseJSON ?? {};

                            Swal.fire({

                                icon: 'error',

                                title:
                                    'Verifikasi Gagal',

                                text:
                                    response.message ??
                                    'Status pembayaran belum dapat diverifikasi. Silakan cek kembali beberapa saat lagi.',

                                confirmButtonText:
                                    'OK'

                            }).then(function () {

                                window.location.reload();

                            });

                        }

                    });

                }

            });

        </script>

    @endif

@endpush