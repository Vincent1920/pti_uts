<?php
//  vincenet 10123309 

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\Diskon;
use App\Models\CartItem;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionItem;
// use Illuminate\Database\Eloquent\
class admin extends Controller
{
    public function home()
    {
        $users = User::all();
        $authUser = auth()->user();
        $barangs = Barang::all();

        return view('admins.home', [
            'barangs' => $barangs,
            'users' => $users,
            'authUser' => $authUser
        ]);
    }

    
  public function dashboard()
{
    $kategori = Kategori::all();
    $barangs = Barang::with('kategori')->get();
    
    // Data barang untuk grafik stok
    $dataBarang = Barang::select('title', 'jumlah_barang')->get();

    // 1. Ambil data untuk Grafik Penjualan (7 hari terakhir)
    $salesData = DB::table('transaction_items')
        ->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(subtotal) as total')
        )
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();

    // 2. Ambil data untuk Summary Card (Bulan ini)
    $totalVolume = DB::table('transaction_items')->whereMonth('created_at', now()->month)->sum('subtotal');
    $totalTransaction = DB::table('transaction_items')->whereMonth('created_at', now()->month)->count();

    // 3. Logika Diskon (Hanya berjalan jika kolom 'diskon' sudah ada)
    // Jika kolom belum ada, kita beri nilai default 0 agar tidak error
    try {
        $transaksiDiskon = DB::table('transaction_items')->where('diskon', '>', 0)->count();
        $totalHemat = DB::table('transaction_items')->where('diskon', '>', 0)->sum('diskon');
        $tanpaDiskon = DB::table('transaction_items')->where('diskon', '<=', 0)->count();
    } catch (\Exception $e) {
        $transaksiDiskon = 0;
        $totalHemat = 0;
        $tanpaDiskon = $totalTransaction;
    }

    $perbandinganDiskon = [
        'Dengan Diskon' => $transaksiDiskon,
        'Tanpa Diskon' => $tanpaDiskon
    ];

    return view('admins.pages.dashboard', compact(
        'kategori', 'barangs', 'salesData', 'totalVolume', 
        'totalTransaction', 'transaksiDiskon', 'totalHemat', 'perbandinganDiskon', 'dataBarang'
    ));
}


    public function cart(){
        $diskon = Diskon::where('status', true)->first();
        $item_cart = User::whereHas('cartItems')->with('cartItems.barang')->get();  
            return view('admins.pages.list_cart', [
             'item_cart' => $item_cart,
             'diskon' =>$diskon
        ]);
    }
}


