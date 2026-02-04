<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\CartItem;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Diskon;

class PaymentController extends Controller
{
     public function index()
    {
        $user = Auth::user();
        $cartItems = CartItem::with('barang')->where('user_id', $user->id)->get();

        // Jika keranjang kosong, tendang ke shop
        if ($cartItems->isEmpty()) {
            return redirect()->route('shop')->with('error', 'Keranjang Anda kosong.');
        }

        // Hitung Total Belanja
        $grandTotal = 0;
        foreach ($cartItems as $item) {
            $grandTotal += $item->barang->harga * $item->quantity;
        }

        // Cek Diskon Aktif
        $diskon = Diskon::where('status', true)->first();
        $discountAmount = 0;
        $namaDiskon = '';
        
        if ($diskon) {
            $discountAmount = ($grandTotal * $diskon->persentase) / 100;
            $namaDiskon = $diskon->nama_diskon;
        }

        $finalPrice = $grandTotal - $discountAmount;

        // Tampilkan View Checkout
        return view('checkout', compact('cartItems', 'grandTotal', 'discountAmount', 'finalPrice', 'namaDiskon'));
    }

    public function process(Request $request) 
{
    // dd($request->all());
    // dd($request);
    // 1. Validasi Input agar tidak muncul error "invalid" lagi
   $request->validate([
        'payment_method' => 'required|in:cod,midtrans',
        'name' => 'required',
        'address' => 'required',
        'phone' => 'required',
        'city' => 'required',
        'postal_code' => 'required',
    ]);

    // 2. Ambil isi keranjang
    $cartItems = \App\Models\CartItem::where('user_id', auth()->id())->with('barang')->get();

    // Hitung subtotal secara manual dari keranjang
    $calculatedSubtotal = $cartItems->sum(function($item) {
        return $item->barang->harga * $item->quantity;
    });

    // 3. Simpan ke tabel 'transactions'
    $transaction = \App\Models\Transaction::create([
        'user_id'        => auth()->id(),
        'invoice_code'   => 'CHOC-' . strtoupper(uniqid()),
        'subtotal'       => $calculatedSubtotal, // TAMBAHKAN INI agar tidak error 1364 lagi
        'grand_total'    => (int)$request->final_price,
        'status'         => 'pending',
        'payment_method' => $request->payment_method,
        'name'           => $request->name,
        'email'          => auth()->user()->email,
        'phone'          => $request->phone,
        'address'        => $request->address,
        'city'           => $request->city,
        'postal_code'    => $request->postal_code,
        'country'        => 'Indonesia',
    ]);

    // 4. Simpan detail ke transaction_items
    foreach ($cartItems as $item) {
        \App\Models\TransactionItem::create([
            'transaction_id' => $transaction->id,
            'barang_id'      => $item->barang_id,
            'product_name'   => $item->barang->title,
            'quantity'       => $item->quantity,
            'price'          => $item->barang->harga,
            'subtotal'       => $item->barang->harga * $item->quantity,
        ]);
    }

    // 5. PROSES KE MIDTRANS
    if ($request->payment_method == 'midtrans') {
        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $transaction->invoice_code,
                'gross_amount' => (int)$request->final_price,
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'email' => auth()->user()->email,
                'phone' => $request->phone,
            ],
        ];

        // Filter metode jika user memilih spesifik di dropdown
        if ($request->midtrans_type !== 'all') {
            $params['enabled_payments'] = [$request->midtrans_type];
        }

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            // Kosongkan keranjang setelah token didapat
            \App\Models\CartItem::where('user_id', auth()->id())->delete();

            // Lempar ke halaman konfirmasi pembayaran
            return view('payment_confirm', compact('snapToken', 'transaction'));
        } catch (\Exception $e) {
                // Pesan inilah yang muncul di gambar kamu
                return redirect()->back()->with('error', 'Koneksi Midtrans Gagal: ' . $e->getMessage());
         }
         
    }

    // Jika COD
    \App\Models\CartItem::where('user_id', auth()->id())->delete();
    return redirect()->route('home')->with('success', 'Pesanan COD Berhasil Dibuat!');
}
}