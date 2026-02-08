<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Diskon;
<<<<<<< HEAD
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{


  public function __construct()
{
    Config::$serverKey = config('midtrans.server_key');
    Config::$isProduction = config('midtrans.is_production');
    Config::$isSanitized = config('midtrans.is_sanitized');
    Config::$is3ds = config('midtrans.is_3ds');
    Config::$appendNotifUrl = config('midtrans.notification_url');
    // Ini kunci untuk memperbaiki error 10023 di Windows
    Config::$curlOptions = [
        CURLOPT_SSL_VERIFYPEER => false,
    ];
}
=======
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
>>>>>>> bb8fc8355ad3eedea55c976641db0750c36280f6

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

<<<<<<< HEAD
    public function process(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'payment_method' => 'required|in:cod,midtrans',
            'name'           => 'required|string|max:255',
            'address'        => 'required',
            'phone'          => 'required',
            'city'           => 'required',
            'postal_code'    => 'required',
            'final_price'    => 'required|numeric',
        ]);

        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)->with('barang')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang belanja kosong!');
        }

        $calculatedSubtotal = $cartItems->sum(function ($item) {
            return $item->barang->harga * $item->quantity;
        });

        $invoiceCode = 'INV-' . strtoupper(uniqid());

        try {
            $transaction = DB::transaction(function () use ($request, $calculatedSubtotal, $cartItems, $user, $invoiceCode) {
                // 1. Simpan Transaksi Utama
                $transaction = Transaction::create([
                    'user_id'        => $user->id,
                    'invoice_code'   => $invoiceCode,
                    'subtotal'       => $calculatedSubtotal,
                    'grand_total'    => (int)$request->final_price,
                    'status'         => 'pending',
                    'payment_method' => $request->payment_method,
                    'name'           => $request->name,
                    'email'          => $user->email,
                    'phone'          => $request->phone,
                    'address'        => $request->address,
                    'city'           => $request->city,
                    'postal_code'    => $request->postal_code,
                    'country'        => 'Indonesia',
                ]);

                // 2. Simpan Item Detail
                foreach ($cartItems as $item) {
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'barang_id'      => $item->barang_id,
                        'product_name'   => $item->barang->title,
                        'quantity'       => $item->quantity,
                        'price'          => $item->barang->harga,
                        'subtotal'       => $item->barang->harga * $item->quantity,
                    ]);
                }

                return $transaction;
            });

            // 3. Alur Pembayaran
            if ($request->payment_method == 'midtrans') {
                return $this->handleMidtrans($transaction, $cartItems, $calculatedSubtotal, $request);
            } else {
                // Jika COD
                CartItem::where('user_id', $user->id)->delete();
                return redirect()->route('home')->with('success', 'Pesanan COD Berhasil Dibuat!');
            }

        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    private function handleMidtrans($transaction, $cartItems, $subtotal, $request)
{
    // 1. Set Konfigurasi (Pastikan ini sudah diset di AppServiceProvider atau config)

    $itemDetails = [];
    $calculatedGrossAmount = 0;

    // 2. Loop Items
    foreach ($cartItems as $item) {
        if (!$item->barang) continue;

        $price = (int) $item->barang->harga;
        $qty = (int) $item->quantity;
        
        $itemDetails[] = [
            'id'       => 'PRD-' . $item->barang_id, // Prefix id agar jelas
            'price'    => $price,
            'quantity' => $qty,
            'name'     => substr($item->barang->title, 0, 50),
        ];

        $calculatedGrossAmount += ($price * $qty);
    }

    // 3. Hitung Diskon
    $discount = $calculatedGrossAmount - (int) $request->final_price;
    if ($discount > 0) {
        $itemDetails[] = [
            'id'       => 'DISC-PROMO',
            'price'    => -$discount,
            'quantity' => 1,
            'name'     => 'Potongan Harga / Diskon',
        ];
    }

    // 4. Params
    $params = [
        'transaction_details' => [
            'order_id'     => $transaction->invoice_code . '-' . time(),
            // Gunakan hasil hitungan akhir agar sinkron dengan item_details
            'gross_amount' => (int) $request->final_price, 
        ],
        'item_details' => array_values($itemDetails),
        'customer_details' => [
            'first_name' => substr($request->name, 0, 20),
            'email'      => Auth::user()->email,
            'phone'      => $request->phone,
        ],
        // Opsional: Atur waktu kadaluarsa (misal 24 jam)
        'expiry' => [
            'unit' => 'hours',
            'duration' => 24
=======
        
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
>>>>>>> bb8fc8355ad3eedea55c976641db0750c36280f6
        ],
    ];

    try {
<<<<<<< HEAD
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        
        // Simpan snap_token ke database jika perlu untuk tracking
        $transaction->update(['snap_token' => $snapToken]);

        return view('payment_confirm', compact('snapToken', 'transaction'));
    } catch (\Exception $e) {
        Log::error('Midtrans API Error: ' . $e->getMessage());
        
        return redirect()->back()->with(
            'error',
            'Gagal menghubungi server Midtrans. Silakan coba lagi.'
        );
=======
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
>>>>>>> bb8fc8355ad3eedea55c976641db0750c36280f6
    }
}


public function midtransNotification(Request $request)
{
    Log::info('--- NOTIFIKASI MIDTRANS MASUK ---', $request->all());

    $payload = $request->all();

    $rawOrderId = $payload['order_id'];

    // Ambil invoice_code
    $invoiceCode = explode('-', $rawOrderId)[0] . '-' . explode('-', $rawOrderId)[1];

    $transaction = Transaction::where('invoice_code', $invoiceCode)->first();

    if (!$transaction) {
        Log::error("Transaksi tidak ditemukan: " . $invoiceCode);
        return response()->json(['message' => 'Transaction not found'], 404);
    }

    $transaction->update([
        'status_midtrans' => $payload['transaction_status'], // 🔥 INI
        'status' => in_array($payload['transaction_status'], ['settlement', 'capture'])
            ? 'success'
            : 'pending',
    ]);

    if (in_array($payload['transaction_status'], ['settlement', 'capture'])) {
        CartItem::where('user_id', $transaction->user_id)->delete();
    }

    Log::info("Status transaksi {$transaction->id} diupdate: {$payload['transaction_status']}");

    return response()->json(['message' => 'OK']);
}



}