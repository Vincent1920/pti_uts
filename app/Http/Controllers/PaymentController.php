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
        ],
    ];

    try {
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
    }
}
}