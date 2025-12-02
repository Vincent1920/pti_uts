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

        // 1. Ambil data barang dari database untuk cek stok
        $barang = Barang::findOrFail($request->barang_id);

        // 2. Cek apakah jumlah yang diminta melebihi stok yang tersedia
        // Asumsi nama kolom di database adalah 'jumlah_barang' (sesuai diskusi sebelumnya)
        // Jika nama kolomnya 'stok', ganti $barang->jumlah_barang menjadi $barang->stok
        if ($request->quantity > $barang->jumlah_barang) {
            return redirect()->back()->with('error', 'Stok tidak cukup! Barang hanya tersisa ' . $barang->jumlah_barang . ' item.');
        }

        // 3. (Opsional tapi disarankan) Cek juga jika digabung dengan keranjang yang sudah ada
        $existingCart = CartItem::where('user_id', Auth::id())
                                ->where('barang_id', $request->barang_id)
                                ->first();
        
        $currentQtyInCart = $existingCart ? $existingCart->quantity : 0;

        if (($currentQtyInCart + $request->quantity) > $barang->jumlah_barang) {
             return redirect()->back()->with('error', 'Stok terbatas. Anda sudah punya ' . $currentQtyInCart . ' di keranjang. Sisa stok: ' . $barang->jumlah_barang);
        }

        // 4. Jika aman, simpan ke database
        $cartItem = CartItem::updateOrCreate(
            ['user_id' => Auth::id(), 'barang_id' => $request->barang_id],
            ['quantity' => DB::raw('quantity + ' . $request->quantity)]
        );

        return redirect()->route('cart')->with('success', 'Berhasil ditambahkan ke keranjang');
    }
public function update(Request $request, $id){
        // Tambahkan with('barang') agar kita bisa cek stoknya
        $cartItem = CartItem::with('barang')->findOrFail($id);

        if ($request->action === 'increase') {
            // === LOGIKA PENGECEKAN STOK BARU ===
            $stokTersedia = $cartItem->barang->jumlah_barang; // Pastikan nama kolom sesuai DB

            // Cek apakah kuantitas saat ini sudah sama atau lebih dari stok
            if ($cartItem->quantity >= $stokTersedia) {
                return redirect()->route('cart')->with('error', 'Stok mentok! Barang ini hanya tersisa ' . $stokTersedia . ' item.');
            }
            // =====================================

            $cartItem->quantity++;
        } elseif ($request->action === 'decrease' && $cartItem->quantity > 1) {
            $cartItem->quantity--;
        }

        $cartItem->save();

        return redirect()->route('cart')->with('success', 'Keranjang berhasil diperbarui');
    }
    
}
