<?php
// vincent luhulima 10123309
use App\Models\Kategori;
use App\Http\Controllers\card;
use App\Http\Controllers\admin;
use App\Http\Controllers\index;
use App\Http\Controllers\barang;
use App\Http\Middleware\Ceklavel;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controller_shop;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\controller_login;
use App\Http\Controllers\DiskonController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ControllerKategori;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminTransactionController;

Route::get('/', [index::class, 'index'])->name('home');
Route::get('/brand', [index::class, 'brand'])->name('brand');
Route::get('/shop', [controller_shop::class, 'index'])->name('shop');
Route::get('/card',[card::class ,'card'])->name('card');
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [controller_login::class, 'view_login'])->name('login');
    Route::post('/login', [controller_login::class, 'login'])->name('postlogin');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [Controller_Login::class, 'logout'])->name('logout');
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('barangs', BarangController::class);
    Route::get('/admin', [admin::class, 'home'])->name('admin');
    Route::get('/admin/dashboard', [admin::class, 'dashboard'])->name('admin.dashboard');
    
    // barang 
    Route::get('/post',[BarangController::class,'index'])->name('post');
    Route::get('/creat',[BarangController::class,'create']);
    Route::post('/posts/store',[BarangController::class,'store']);
    Route::get('/barangs/show/{{barang}}',[BarangController::class,'show'])->name('show');
    Route::post('/update/{barang}',[BarangController::class , 'update']);
    Route::get('cart/admin',[admin::class,'cart'])->name('cart_admin');

    // Kategori
    Route::get('/kategori',[KategoriController::class,'index'])->name('kategori');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');

    // diskon
    Route::get('/diskon', [DiskonController::class, 'index'])->name('diskon.index');
    Route::get('/diskon/create', [DiskonController::class, 'create'])->name('diskon.create');
    Route::post('/diskon', [DiskonController::class, 'store'])->name('diskon.store');
    Route::delete('/diskon/{id}', [DiskonController::class, 'destroy'])->name('diskon.destroy');
    Route::post('/diskon/{id}/status', [DiskonController::class, 'toggleStatus'])->name('diskon.status');

    // Update Status Transaksi
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('admin.transactions.index');
    Route::patch('/transactions/{id}/status', [AdminTransactionController::class, 'updateStatus'])->name('admin.transactions.updateStatus');
    
});


Route::get('/learn',[index::class,'learn'])->name('learn');
Route::get('/kategori/{kategori_id}', [controller_shop::class, 'show'])->name('kategori.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/card/{id}', [card::class, 'card'])->name('card.show');
    Route::get('/cart', [card::class, 'cart'])->name('cart');
    Route::patch('/cart/{id}', [card::class, 'update'])->name('cart.update');
    Route::post('cart/add', [card::class, 'add'])->name('cart.add');
    Route::delete('cart/remove/{id}', [card::class, 'remove'])->name('cart.remove');
});

Route::get('forgot_password',[controller_login::class,'forgot_password'])->name('forgot_password');
    Route::get('forgot-password/{token}', [controller_login::class, 'forgot_password']);



Route::middleware(['auth'])->group(function () {
    // Route untuk Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // Route untuk Order List (History)
    // Pastikan ->name('orders.index') ada disini
    Route::get('/orders', [CheckoutController::class, 'history'])->name('orders.index');
});