<?php
//  vincenet 10123309 

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\Diskon;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class card extends Controller
{
    public function card($id){
        $barang = barang::find($id);

    // Periksa jika data barang ditemukan
     if (!$barang) {
            abort(404, 'Barang tidak ditemukan');
        }

    // Kirim data barang ke view
         return view('pages.shop.card', [
            'barang' => $barang,
     ]);
    }

    // App/Http/Controllers/CartController.php

public function cart()
{
    // 1. Ambil Data
    $cartItems = CartItem::where('user_id', Auth::id())->with('barang')->get();

    // 2. Hitung Total Kotor (Tanpa Diskon)
    $grandTotal = $cartItems->sum(function($item) {
        return $item->barang->harga * $item->quantity;
    });

    // 3. Cek Database Diskon
    $diskon = \App\Models\Diskon::where('status', true)->first(); // Ambil diskon aktif

    // 4. Hitung Potongan
    if ($diskon) {
        $namaDiskon = $diskon->nama_diskon;
        $persenDiskon = $diskon->persentase;
        $discountAmount = $grandTotal * ($persenDiskon / 100);
    } else {
        $namaDiskon = "";
        $persenDiskon = 0;
        $discountAmount = 0;
    }

    // 5. Hitung Total Bersih
    $finalPrice = $grandTotal - $discountAmount;
// dd($finalPrice);
    // 6. Kirim ke View
   return view('layouts.cart', [
        'cartItems'      => $cartItems,
        'grandTotal'     => $grandTotal,
        'discountAmount' => $discountAmount,
        'namaDiskon'     => $namaDiskon,
        'persenDiskon'   => $persenDiskon,
        'finalPrice'     => $finalPrice
    ]);
}
    

    public function add(Request $request){
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::updateOrCreate(
            ['user_id' => Auth::id(), 'barang_id' => $request->barang_id],
            ['quantity' => DB::raw('quantity + ' . $request->quantity)]
        );

        return redirect()->route('cart')->with('success', 'berhasil di tambah kan ');
    }


    public function update(Request $request, $id){
        $cartItem = CartItem::findOrFail($id);

    if ($request->action === 'increase') {
        $cartItem->quantity++;
    } elseif ($request->action === 'decrease' && $cartItem->quantity > 1) {
        $cartItem->quantity--;
    }

    $cartItem->save();

    return redirect()->route('cart')->with('success', ' updated keranjang');
    }

    public function remove($id)
    {
        $item = CartItem::find($id);

    if ($item) {
        $item->delete();
        return redirect()->route('cart')->with('success', 'Item berhasil dihapus dari keranjang.');
    } else {
        return redirect()->route('cart')->with('error', 'Item tidak ditemukan.');
    }}
}
