<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home');

Route::get('/login', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('login');

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])
    ->name('auth.login');

Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])
    ->name('register.store');

Route::post(
    '/lupa-password',
    [App\Http\Controllers\LupaPasswordController::class, 'kirimLinkReset']
)->name('lupa-password');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', [
        'token' => $token,
    ]);
})
    ->middleware('guest')
    ->name('password.reset');

Route::post(
    '/reset-password',
    [App\Http\Controllers\LupaPasswordController::class, 'resetPassword']
)
    ->middleware('guest')
    ->name('password.update');


/*
|--------------------------------------------------------------------------
| PUBLIC PRODUCT ROUTES
|--------------------------------------------------------------------------
*/

Route::get(
    '/produk/detail/{id}',
    [App\Http\Controllers\ProdukDetailController::class, 'show']
)
    ->whereNumber('id')
    ->name('produk.detail');


/*
|--------------------------------------------------------------------------
| PUBLIC CART ROUTE
|--------------------------------------------------------------------------
|
| Keranjang tetap dapat dibuka sebelum login.
| Checkout hanya dapat dilakukan customer yang sudah login.
|
*/

Route::get(
    '/cart',
    [App\Http\Controllers\CartController::class, 'index']
)->name('cart');


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post(
    '/logout',
    [App\Http\Controllers\AuthController::class, 'logout']
)
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:customer',
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | CHECKOUT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/checkout',
        [App\Http\Controllers\CartController::class, 'checkout']
    )->name('checkout');

    Route::post(
        '/checkout/process',
        [App\Http\Controllers\CartController::class, 'processCheckout']
    )->name('checkout.process');


    /*
    |--------------------------------------------------------------------------
    | ORDER CONFIRMATION
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/order/confirmation/{id}',
        [App\Http\Controllers\CartController::class, 'orderConfirmation']
    )
        ->whereNumber('id')
        ->name('order.confirmation');


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER ACCOUNT
    |--------------------------------------------------------------------------
    */

    Route::prefix('akun')
        ->name('akun.')
        ->group(function () {

            Route::get(
                '/',
                [App\Http\Controllers\AkunController::class, 'index']
            )->name('saya');

            Route::get(
                '/pesanan',
                [App\Http\Controllers\AkunController::class, 'pesanan']
            )->name('pesanan');

            Route::get(
                '/pesanan/{id}',
                [App\Http\Controllers\AkunController::class, 'pesananDetail']
            )
                ->whereNumber('id')
                ->name('pesanan.detail');

            /*
            |--------------------------------------------------------------------------
            | CUSTOMER CONFIRM ORDER RECEIVED
            |--------------------------------------------------------------------------
            */

            Route::put(
                '/pesanan/{id}/diterima',
                [App\Http\Controllers\AkunController::class, 'konfirmasiDiterima']
            )
                ->whereNumber('id')
                ->name('pesanan.diterima');

            Route::get(
                '/alamat',
                [App\Http\Controllers\AkunController::class, 'alamat']
            )->name('alamat');

            Route::post(
                '/alamat',
                [App\Http\Controllers\AkunController::class, 'updateAlamat']
            )->name('alamat.update');

            Route::get(
                '/edit',
                [App\Http\Controllers\AkunController::class, 'edit']
            )->name('edit');

            Route::post(
                '/edit',
                [App\Http\Controllers\AkunController::class, 'update']
            )->name('update');
        });


    /*
    |--------------------------------------------------------------------------
    | RAJAONGKIR
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/rajaongkir/districts',
        [App\Http\Controllers\CartController::class, 'getDistricts']
    )->name('rajaongkir.districts');

    Route::post(
        '/rajaongkir/cost',
        [App\Http\Controllers\CartController::class, 'calculateShippingCost']
    )->name('rajaongkir.cost');


    /*
    |--------------------------------------------------------------------------
    | MIDTRANS PAYMENT
    |--------------------------------------------------------------------------
    */

    Route::prefix('pembayaran')->group(function () {

        Route::post(
            '/',
            [App\Http\Controllers\PembayaranController::class, 'bayar']
        )->name('pembayaran.bayar');

        Route::put(
            '/cek-status',
            [App\Http\Controllers\PembayaranController::class, 'updateStatus']
        )->name('pembayaran.cek-status');
    });


    /*
    |--------------------------------------------------------------------------
    | REVIEW & RATING
    |--------------------------------------------------------------------------
    */

    Route::prefix('ulasan')->group(function () {

        Route::get(
            '/form/{produk_id}',
            [App\Http\Controllers\UlasanRatingController::class, 'formTambahUlasan']
        )
            ->whereNumber('produk_id')
            ->name('ulasan.form');

        Route::post(
            '/{produk_id}',
            [App\Http\Controllers\UlasanRatingController::class, 'store']
        )
            ->whereNumber('produk_id')
            ->name('ulasan.store');

        Route::delete(
            '/{id}',
            [App\Http\Controllers\UlasanRatingController::class, 'destroy']
        )
            ->whereNumber('id')
            ->name('ulasan.destroy');
    });
});


/*
|--------------------------------------------------------------------------
| ADMIN + KARYAWAN ROUTES
|--------------------------------------------------------------------------
|
| Admin dan karyawan dapat mengakses operasional:
| dashboard, kategori, produk, transaksi, laporan.
|
*/

Route::middleware([
    'auth',
    'role:admin,karyawan',
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard-admin',
        [App\Http\Controllers\HomeController::class, 'dashboard']
    )->name('dashboard.admin');


    /*
    |--------------------------------------------------------------------------
    | STAFF PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [App\Http\Controllers\ProfileController::class, 'index']
    )->name('profile.index');

    Route::put(
        '/profile',
        [App\Http\Controllers\ProfileController::class, 'update']
    )->name('profile.update');

    Route::put(
        '/profile/password',
        [App\Http\Controllers\ProfileController::class, 'updatePassword']
    )->name('profile.password.update');


    /*
    |--------------------------------------------------------------------------
    | CATEGORY MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'kategori',
        App\Http\Controllers\KategoriController::class
    );


    /*
    |--------------------------------------------------------------------------
    | PRODUCT MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::delete(
        '/produk/gambar/{id}',
        [App\Http\Controllers\ProdukController::class, 'deleteGambar']
    )
        ->whereNumber('id')
        ->name('produk.gambar.delete');

    Route::delete(
        '/produk/jenis/{id}',
        [App\Http\Controllers\ProdukController::class, 'deleteJenis']
    )
        ->whereNumber('id')
        ->name('produk.jenis.delete');

    Route::post(
        '/produk/{id}/stok/tambah',
        [App\Http\Controllers\ProdukController::class, 'tambahStok']
    )
        ->whereNumber('id')
        ->name('produk.stok.tambah');

    Route::get(
        '/produk/{id}/riwayat-stok/print',
        [App\Http\Controllers\ProdukController::class, 'printRiwayatStok']
    )
        ->whereNumber('id')
        ->name('produk.riwayat-stok.print');

    Route::prefix('produk')->group(function () {

        Route::get(
            '/',
            [App\Http\Controllers\ProdukController::class, 'index']
        )->name('produk.index');

        Route::get(
            '/tambah',
            [App\Http\Controllers\ProdukController::class, 'create']
        )->name('produk.create');

        Route::post(
            '/',
            [App\Http\Controllers\ProdukController::class, 'store']
        )->name('produk.store');

        Route::get(
            '/{id}/edit',
            [App\Http\Controllers\ProdukController::class, 'edit']
        )
            ->whereNumber('id')
            ->name('produk.edit');

        Route::put(
            '/{id}',
            [App\Http\Controllers\ProdukController::class, 'update']
        )
            ->whereNumber('id')
            ->name('produk.update');

        Route::delete(
            '/{id}',
            [App\Http\Controllers\ProdukController::class, 'destroy']
        )
            ->whereNumber('id')
            ->name('produk.destroy');
    });

    Route::get(
        '/produk-detail/{id}',
        [App\Http\Controllers\ProdukController::class, 'show']
    )
        ->whereNumber('id')
        ->name('detail-produk');


    /*
    |--------------------------------------------------------------------------
    | TRANSACTION
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/transaksi/{id}/invoice',
        [App\Http\Controllers\TransaksiController::class, 'invoice']
    )
        ->whereNumber('id')
        ->name('transaksi.invoice');

    Route::put(
        '/transaksi/{id}/update-shipping',
        [App\Http\Controllers\TransaksiController::class, 'updateShipping']
    )
        ->whereNumber('id')
        ->name('transaksi.update-shipping');

    Route::resource(
        'transaksi',
        App\Http\Controllers\TransaksiController::class
    )->only([
        'index',
        'show',
    ]);


    /*
    |--------------------------------------------------------------------------
    | REPORT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/laporan',
        [App\Http\Controllers\LaporanController::class, 'index']
    )->name('laporan.index');

    Route::post(
        '/laporan/generate',
        [App\Http\Controllers\LaporanController::class, 'generate']
    )->name('laporan.generate');

    Route::post(
        '/laporan/print',
        [App\Http\Controllers\LaporanController::class, 'print']
    )->name('laporan.print');

    Route::post(
        '/laporan/pdf',
        [App\Http\Controllers\LaporanController::class, 'downloadPdf']
    )->name('laporan.pdf');
});


/*
|--------------------------------------------------------------------------
| ADMIN ONLY ROUTES
|--------------------------------------------------------------------------
|
| Pengelolaan user dan fungsi administratif sensitif hanya untuk admin.
|
*/

Route::middleware([
    'auth',
    'role:admin',
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ADMIN MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::prefix('data-admin')->group(function () {

        Route::get(
            '/',
            [App\Http\Controllers\AdminController::class, 'index']
        )->name('admin.index');

        Route::get(
            '/create',
            [App\Http\Controllers\AdminController::class, 'create']
        )->name('admin.create');

        Route::post(
            '/',
            [App\Http\Controllers\AdminController::class, 'store']
        )->name('admin.store');

        Route::get(
            '/{id}/edit',
            [App\Http\Controllers\AdminController::class, 'edit']
        )
            ->whereNumber('id')
            ->name('admin.edit');

        Route::put(
            '/{id}',
            [App\Http\Controllers\AdminController::class, 'update']
        )
            ->whereNumber('id')
            ->name('admin.update');

        Route::delete(
            '/{id}',
            [App\Http\Controllers\AdminController::class, 'destroy']
        )
            ->whereNumber('id')
            ->name('admin.destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'karyawan',
        App\Http\Controllers\KaryawanController::class
    );


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER MANAGEMENT
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'customer',
        App\Http\Controllers\CustomerController::class
    );


    /*
    |--------------------------------------------------------------------------
    | MANUAL PAYMENT VALIDATION
    |--------------------------------------------------------------------------
    |
    | Jika fitur ini masih digunakan, hanya admin yang diperbolehkan.
    | Pembayaran customer utama tetap menggunakan Midtrans.
    |
    */

    Route::put(
        '/transaksi/{id}/validate-payment',
        [App\Http\Controllers\TransaksiController::class, 'validatePayment']
    )
        ->whereNumber('id')
        ->name('transaksi.validate-payment');
});