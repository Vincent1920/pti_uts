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

class PaymentController extends Controller
{
public function __construct()
{
    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = config('midtrans.is_production');
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    // Pastikan menggunakan konstanta CURLOPT yang benar
    // Ini memperbaiki "SSL certificate problem" sekaligus error "Undefined array key"
    \Midtrans\Config::$curlOptions = [
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
            // 1. Hitung total & diskon
            $subtotal = $cartItems->sum(fn ($item) => $item->barang->harga * $item->quantity);
            $diskon = Diskon::where('status', true)->first();
            $discountAmount = $diskon ? ($subtotal * $diskon->persentase) / 100 : 0;
            $grandTotal = $subtotal - $discountAmount;

            // 2. Simpan transaksi utama
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

            // 3. Simpan item & kurangi stok
            foreach ($cartItems as $item) {
                $barang = $item->barang;
                if ($barang->jumlah_barang < $item->quantity) {
                    throw new \Exception("Stok {$barang->title} tidak mencukupi");
                }
                $barang->decrement('jumlah_barang', $item->quantity);

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'barang_id' => $barang->id,
                    'product_name' => $barang->title,
                    'quantity' => $item->quantity,
                    'price' => $barang->harga,
                    'subtotal' => $barang->harga * $item->quantity,
                ]);
            }

            DB::commit();

            // 4. Arahkan berdasarkan metode pembayaran
            if ($request->payment_method === 'midtrans') {
                return $this->handleMidtrans($transaction, $cartItems, $grandTotal);
            }

            // Jika COD
            CartItem::where('user_id', $user->id)->delete();
            return redirect()->route('orders.index')->with('success', 'Pesanan COD berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }



    private function handleMidtrans($transaction, $cartItems, $grandTotal)
    {
        $itemDetails = [];
        
        // Detail barang untuk Midtrans
        foreach ($cartItems as $item) {
            $itemDetails[] = [
                'id'       => 'PRD-' . $item->barang_id,
                'price'    => (int)$item->barang->harga,
                'quantity' => (int)$item->quantity,
                'name'     => substr($item->barang->title, 0, 50),
            ];
        }

        // Tambahkan item diskon sebagai harga minus jika ada
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
                'order_id'     => $transaction->invoice_code,
                'gross_amount' => (int)$grandTotal, 
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $transaction->name,
                'email'      => $transaction->email,
                'phone'      => $transaction->phone,
            ],
        ];

        $params = [
                'transaction_details' => [
                    'order_id' => 'TEST-' . time(),
                    'gross_amount' => 10000,
                ],
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                dd($snapToken); // Kalau ini muncul "string", berarti koneksi aman.
            } catch (\Exception $e) {
                dd($e->getMessage()); // Lihat pesan errornya di sini
            }
        // try {
        //     $snapToken = Snap::getSnapToken($params);

        //     // DEBUG DISINI: Hapus "//" di bawah untuk melihat token
        //     dd($snapToken); 

        //     $transaction->update(['snap_token' => $snapToken]);
            
        //     // Hapus keranjang setelah token didapat
        //     CartItem::where('user_id', Auth::id())->delete();

        //     // Pastikan view 'payment_confirm' ada
        //     return view('payment_confirm', compact('snapToken', 'transaction'));

        // } catch (\Exception $e) {
        //     Log::error('Midtrans API Error: ' . $e->getMessage());
        //     return redirect()->back()->with('error', 'Gagal memproses ke Midtrans: ' . $e->getMessage());
        // }
    }

public function testMidtrans()
{
    // PASTIIN kuncinya diawali SB-Mid-server- baru bisa jalan di Sandbox
    \Midtrans\Config::$serverKey = 'Mid-server-Etwcgcy0zmkeXeYVkVRXT-qB'; 
    \Midtrans\Config::$isProduction = false; 
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    $params = [
        'transaction_details' => [
            'order_id' => 'TEST-' . time(),
            'gross_amount' => 10000, 
        ],
    ];

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        
        // Kalo berhasil, layar bakal muncul string token (bukan error key 10023 lagi)
        dd($snapToken); 
        
    } catch (\Exception $e) {
        // Kalo masih gagal, ini bakal kasih tau error aslinya apa
        dd("Error Midtrans: " . $e->getMessage());
    }
}
}