<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Controller Imports
use App\Http\Controllers\index;
use App\Http\Controllers\card; // Sepertinya ini Controller Keranjang
use App\Http\Controllers\admin;
use App\Http\Controllers\controller_shop;
use App\Http\Controllers\controller_login;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DiskonController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminTransactionController;
use App\Http\Controllers\PaymentController;




/*
|--------------------------------------------------------------------------
| 1. RUTE PUBLIK (Bisa diakses siapa saja)
|--------------------------------------------------------------------------
*/
Route::get('/', [index::class, 'index'])->name('home');
Route::get('/about', [index::class, 'about'])->name('about');
Route::get('/brand', [index::class, 'brand'])->name('brand');
Route::get('/learn', [index::class, 'learn'])->name('learn');
Route::get('/shop', [controller_shop::class, 'index'])->name('shop');
// Route::get('/kategori/{kategori_id}', [controller_shop::class, 'show'])->name('kategori.show');

// Autentikasi Tamu (Guest)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [controller_login::class, 'view_login'])->name('login');
    Route::post('/login', [controller_login::class, 'login'])->name('postlogin');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
    
    // Lupa Password
    Route::get('forgot_password', [controller_login::class, 'forgot_password'])->name('forgot_password');
    Route::get('forgot-password/{token}', [controller_login::class, 'forgot_password']); // Cek method di controller, biasanya butuh token
});

Route::prefix('kategori')->name('kategori.')->group(function () {
    // 1. Letakkan 'create' di paling ATAS agar tidak tertabrak wildcard {id}
    Route::get('/create', [KategoriController::class, 'create'])->name('create');
    
    // 2. Route standar lainnya
    Route::get('/', [KategoriController::class, 'index'])->name('index');
    Route::post('/', [KategoriController::class, 'store'])->name('store');
    
    // 3. Route dengan parameter/wildcard diletakkan di BAWAH
    Route::get('/{id}/edit', [KategoriController::class, 'edit'])->name('edit');
    Route::put('/{id}', [KategoriController::class, 'update'])->name('update');
    Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('destroy');
    
    // Jika Anda punya route show dari controller lain (seperti di log Anda), 
    // pastikan letaknya di paling bawah agar tidak mengganggu /create
    // Route::get('/{kategori_id}', [controller_shop::class, 'show']);
});
/*
|--------------------------------------------------------------------------
| 2. LOGIKA VERIFIKASI EMAIL
|--------------------------------------------------------------------------
*/
// Tampilan pemberitahuan "Harap Verifikasi Email"
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

// Handler ketika link di email diklik
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); 
    return redirect('/login')->with('success', 'Email berhasil diverifikasi! Silakan login.');
})->middleware(['signed'])->name('verification.verify');


Route::post('/checkout/cancel/{invoice_code}', [PaymentController::class, 'cancelTransaction'])->name('checkout.cancel');
Route::get('/checkout', [PaymentController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [PaymentController::class, 'process'])->name('checkout.process');
    // Route::post('/checkout/process', [PaymentController::class, 'testMidtrans'])->name('checkout.process');
/*
|--------------------------------------------------------------------------
| 3. RUTE USER (Login + Verified)
|--------------------------------------------------------------------------
| Semua rute di sini HANYA bisa diakses jika user sudah login DAN 
| sudah memverifikasi email.
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Logout
    Route::get('/logout', [controller_login::class, 'logout'])->name('logout');

    // Halaman Shop & Kategori
    // (Jika Anda ingin user verified baru bisa lihat barang)
   
    // Keranjang (Cart)
    // PENTING: Semua manipulasi keranjang harus verified
    Route::get('/card', [card::class, 'card'])->name('card'); // Pastikan nama method sesuai
    Route::get('/card/{id}', [card::class, 'card'])->name('card.show'); // Cek duplikasi route card
    Route::get('/cart', [card::class, 'cart'])->name('cart');
    Route::post('cart/add', [card::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}', [card::class, 'update'])->name('cart.update');
    Route::delete('cart/remove/{id}', [card::class, 'remove'])->name('cart.remove');

    // Checkout & Transaksi
    

    // Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    // Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // Riwayat Pesanan
    Route::get('/orders', [CheckoutController::class, 'history'])->name('orders.index');
});


/*
|--------------------------------------------------------------------------
| 4. RUTE ADMIN (Login + Verified + Role:Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    
    // Dashboard Admin
    Route::get('/admin', [admin::class, 'home'])->name('admin');
    Route::get('/admin/dashboard', [admin::class, 'dashboard'])->name('admin.dashboard');
    Route::get('cart/admin', [admin::class, 'cart'])->name('cart_admin');

    // Manajemen Barang (Resource Route sudah mencakup create, store, edit, update, destroy)
    Route::resource('barangs', BarangController::class);
    
    // Custom Routes Barang (Jika resource tidak cukup)
    Route::get('/post', [BarangController::class, 'index'])->name('post');
    Route::get('/creat', [BarangController::class, 'create']);
    Route::post('/posts/store', [BarangController::class, 'store']);
    Route::get('/barangs/show/{barang}', [BarangController::class, 'show'])->name('show');
    Route::post('/update/{barang}', [BarangController::class, 'update']);

    // Manajemen Kategori
//   Route::resource('kategori', KategoriController::class);

    // Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    // Route::get('/kategori/tambah', [KategoriController::class, 'create'])->name('kategori.create');
    // Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    // Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    // Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    // Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    // Route::delete('/kategor/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // Manajemen Diskon
    Route::get('/diskon', [DiskonController::class, 'index'])->name('diskon.index');
    Route::get('/diskon/create', [DiskonController::class, 'create'])->name('diskon.create');
    Route::post('/diskon', [DiskonController::class, 'store'])->name('diskon.store');
    Route::delete('/diskon/{id}', [DiskonController::class, 'destroy'])->name('diskon.destroy');
    Route::post('/diskon/{id}/status', [DiskonController::class, 'toggleStatus'])->name('diskon.status');

    // Transaksi Admin
    
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('admin.transactions.index');
    Route::patch('/transactions/{id}/status', [AdminTransactionController::class, 'updateStatus'])->name('admin.transactions.updateStatus');
});

Route::get('/midtrans-test', [PaymentController::class, 'testMidtrans']);
