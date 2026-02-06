<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Diskon;
use App\Models\CartItem;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Auth;
use Midtrans\Notification;
use Midtrans\Transaction as MidtransTransaction;

class PaymentController extends Controller
{
 public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
        // \Midtrans\Config::$isVerifyPeer = false;
    }

    public function index()
    {
        $user = Auth::user();
        $cartItems = CartItem::with('barang')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('shop')->with('error', 'Keranjang Anda kosong.');
        }

        $grandTotal = $cartItems->sum(function ($item) {
            return $item->barang->harga * $item->quantity;
        });

        $diskon = Diskon::where('status', true)->first();
        $discountAmount = 0;
        $namaDiskon = '';

        if ($diskon) {
            $discountAmount = ($grandTotal * $diskon->persentase) / 100;
            $namaDiskon = $diskon->nama_diskon;
        }

        $finalPrice = $grandTotal - $discountAmount;

        return view('checkout', compact('cartItems', 'grandTotal', 'discountAmount', 'finalPrice', 'namaDiskon'));
    }

        
 public function process(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'address' => 'required',
        'phone' => 'required',
        'city' => 'required',
        'postal_code' => 'required',
        'payment_method' => 'required|in:midtrans,cod',
    ]);

    $user = Auth::user();
    $cartItems = CartItem::with('barang')->where('user_id', $user->id)->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('shop')->with('error', 'Keranjang belanja kosong.');
    }

    $invoiceCode = 'INV-' . strtoupper(Str::random(10));

    DB::beginTransaction();
    try {
        $subtotal = $cartItems->sum(fn ($item) => $item->barang->harga * $item->quantity);
        $diskon = Diskon::where('status', true)->first();
        $discountAmount = $diskon ? ($subtotal * $diskon->persentase) / 100 : 0;
        $grandTotal = $subtotal - $discountAmount;

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'invoice_code' => $invoiceCode,
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'grand_total' => $grandTotal,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'payment_method' => $request->payment_method,
            'status' => 'pending', 
        ]);

        foreach ($cartItems as $item) {
            $barang = $item->barang;
            if ($barang->jumlah_barang < $item->quantity) {
                throw new \Exception("Stok {$barang->title} tidak mencukupi");
            }

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'barang_id' => $barang->id,
                'product_name' => $barang->title,
                'quantity' => $item->quantity,
                'price' => $barang->harga,
                'subtotal' => $barang->harga * $item->quantity,
                'diskon' => $diskon ? ($barang->harga * $item->quantity * $diskon->persentase) / 100 : 0,
            ]);
            
            $barang->decrement('jumlah_barang', $item->quantity);
        }

        DB::commit();

        // LOGIKA SETELAH TRANSAKSI BERHASIL DISIMPAN
        if ($request->payment_method === 'midtrans') {
            return $this->handleMidtrans($transaction, $cartItems, $grandTotal);
        }

        // TAMBAHKAN LOGIKA COD DI SINI
        // 1. Hapus item di keranjang karena pesanan sudah dibuat
        CartItem::where('user_id', $user->id)->delete();

        // 2. Redirect ke halaman pesanan dengan pesan sukses
        return redirect()->route('orders.index')->with('success', 'Pesanan COD berhasil dibuat!');

    } catch (\Exception $e) {
        DB::rollBack();
        
        // Cek jika request datang dari AJAX (untuk Midtrans) atau form biasa (untuk COD)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
        
        return back()->with('error', $e->getMessage());
    }
}


 private function handleMidtrans($transaction, $cartItems, $grandTotal)
{
    $itemDetails = [];
    foreach ($cartItems as $item) {
        $itemDetails[] = [
            'id'       => 'PRD-' . $item->barang_id,
            'price'    => (int)$item->barang->harga,
            'quantity' => (int)$item->quantity,
            'name'     => substr($item->barang->title, 0, 50),
        ];
    }

    if ($transaction->discount_amount > 0) {
        $itemDetails[] = [
            'id'       => 'DISC-PROMO',
            'price'    => -(int)$transaction->discount_amount,
            'quantity' => 1,
            'name'     => 'Potongan Harga',
        ];
    }

    $params = [
        'transaction_details' => [
            'order_id'     => $transaction->invoice_code . '-' . time(), // Tambah time agar selalu unik saat testing
            'gross_amount' => (int)$grandTotal, 
        ],
        'item_details' => $itemDetails,
        'customer_details' => [
            'first_name' => $transaction->name,
            'email'      => $transaction->email,
            'phone'      => $transaction->phone,
        ],
    ];

    try {
        $snapToken = Snap::getSnapToken($params);
        $transaction->update(['snap_token' => $snapToken]);

        // Kirim respon JSON ke AJAX
        return response()->json([
            'success' => true,
            'snap_token' => $snapToken
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}


public function midtransNotification(Request $request)
{
    $payload = $request->all();
    $rawOrderId = $payload['order_id']; // Contoh: "INV-RDVZDE44GQ-1770398223"

    // Potong ID untuk mendapatkan invoice_code asli
    $lastDash = strrpos($rawOrderId, '-');
    $invoiceCode = ($lastDash !== false) ? substr($rawOrderId, 0, $lastDash) : $rawOrderId;

    $transaction = Transaction::where('invoice_code', $invoiceCode)->first();

    if ($transaction) {
        $transaction->update([
            'status_midtrans' => $payload['transaction_status'], // Mengisi kolom status_midtrans
            'status' => ($payload['transaction_status'] == 'settlement') ? 'success' : 'pending'
        ]);
        
        // Hapus keranjang jika lunas
        if ($payload['transaction_status'] == 'settlement') {
            CartItem::where('user_id', $transaction->user_id)->delete();
        }
    }

    return response()->json(['message' => 'OK']);
}

// Fungsi pembantu untuk sukses (agar kode lebih rapi)
private function finalizeSuccessTransaction($transaction, $statusMidtrans)
{
    $transaction->update([
        'status' => 'success',
        'status_midtrans' => $statusMidtrans
    ]);
    
    // Hapus keranjang user
    \App\Models\CartItem::where('user_id', $transaction->user_id)->delete();
}

}