<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Controller Imports
use App\Http\Controllers\index; // Saran: Ubah jadi IndexController
use App\Http\Controllers\card;  // Saran: Ubah jadi CartController
use App\Http\Controllers\admin; // Saran: Ubah jadi AdminController
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
| 1. RUTE PUBLIK (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [index::class, 'index'])->name('home');
Route::get('/about', [index::class, 'about'])->name('about');
Route::get('/brand', [index::class, 'brand'])->name('brand');
Route::get('/learn', [index::class, 'learn'])->name('learn');

// Katalog Produk
Route::get('/shop', [controller_shop::class, 'index'])->name('shop');
Route::get('/kategori/{kategori_id}', [controller_shop::class, 'show'])->name('kategori.show');

// Testing Midtrans (Hapus jika sudah produksi)
Route::get('/midtrans-test', [PaymentController::class, 'testMidtrans']);

/*
|--------------------------------------------------------------------------
| 2. AUTH: GUEST (Hanya untuk user yang BELUM login)
|--------------------------------------------------------------------------
*/
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [controller_login::class, 'view_login'])->name('login');
    Route::post('/login', [controller_login::class, 'login'])->name('postlogin');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
    
    // Lupa Password
    Route::get('forgot_password', [controller_login::class, 'forgot_password'])->name('forgot_password');
    Route::get('forgot-password/{token}', [controller_login::class, 'forgot_password']); 
});

/*
|--------------------------------------------------------------------------
| 3. AUTH: VERIFIKASI EMAIL
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); 
    return redirect('/login')->with('success', 'Email diverifikasi! Silakan login.');
})->middleware(['auth', 'signed'])->name('verification.verify');

/*
|--------------------------------------------------------------------------
| 4. RUTE USER TERAUTENTIKASI (Login + Verified)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/logout', [controller_login::class, 'logout'])->name('logout');

    // Keranjang (Cart)
    Route::prefix('cart')->group(function () {
        Route::get('/', [card::class, 'cart'])->name('cart');
        Route::post('/add', [card::class, 'add'])->name('cart.add');
        Route::patch('/{id}', [card::class, 'update'])->name('cart.update');
        Route::delete('/remove/{id}', [card::class, 'remove'])->name('cart.remove');
    });

    // Checkout & Pembayaran (Pindahkan ke sini agar aman)
    Route::get('/checkout', [PaymentController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [PaymentController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/cancel/{invoice_code}', [PaymentController::class, 'cancelTransaction'])->name('checkout.cancel');
    
    // Riwayat Pesanan User
    Route::get('/orders', [CheckoutController::class, 'history'])->name('orders.index');
});

/*
|--------------------------------------------------------------------------
| 5. RUTE ADMIN (Role: Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    
    // Dashboard & Statistik
    Route::get('/', [admin::class, 'home'])->name('admin');
    Route::get('/dashboard', [admin::class, 'dashboard'])->name('admin.dashboard');

    // Manajemen Produk (Barang)
    Route::resource('barangs', BarangController::class);
    // Note: resource sudah mencakup index, create, store, show, edit, update, destroy
    // Rute manual di bawah ini bisa dihapus jika sudah pakai resource:
    Route::post('/barangs/update/{barang}', [BarangController::class, 'update'])->name('barangs.manual_update');

    // Manajemen Kategori
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // Manajemen Diskon
    Route::prefix('diskon')->group(function () {
        Route::get('/', [DiskonController::class, 'index'])->name('diskon.index');
        Route::get('/create', [DiskonController::class, 'create'])->name('diskon.create');
        Route::post('/', [DiskonController::class, 'store'])->name('diskon.store');
        Route::delete('/{id}', [DiskonController::class, 'destroy'])->name('diskon.destroy');
        Route::post('/{id}/status', [DiskonController::class, 'toggleStatus'])->name('diskon.status');
    });

    // Manajemen Transaksi (Sisi Admin)
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('admin.transactions.index');
    Route::patch('/transactions/{id}/status', [AdminTransactionController::class, 'updateStatus'])->name('admin.transactions.updateStatus');
});