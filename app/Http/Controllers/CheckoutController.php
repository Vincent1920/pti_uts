<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use App\Models\Diskon;
use App\Models\CartItem;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
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
    // 1. Validasi Input
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'address' => 'required',
        'phone' => 'required',
        'city' => 'required',
        'postal_code' => 'required',
        'payment_method' => 'required|in:midtrans,cod',
    ]);

    DB::beginTransaction();
    try {
        $user = Auth::user();
        $cartItems = CartItem::with('barang')->where('user_id', $user->id)->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('shop')->with('error', 'Keranjang belanja kosong.');
        }

        // Hitung Total
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->barang->harga * $item->quantity;
        }
        
        $diskon = Diskon::where('status', true)->first();
        $discountAmount = $diskon ? ($subtotal * $diskon->persentase) / 100 : 0;
        $grandTotal = $subtotal - $discountAmount;

        // --- LANGKAH 2: SIMPAN TRANSAKSI DULU (snap_token biarkan null) ---
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'invoice_code' => 'INV-' . strtoupper(Str::random(10)),
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'grand_total' => $grandTotal,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'country' => 'Indonesia',
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'snap_token' => null, // Diisi nanti setelah dapat dari Midtrans
        ]);

        // --- LANGKAH 3: SIMPAN DETAIL & UPDATE STOK ---
        foreach ($cartItems as $item) {
            $barang = $item->barang;
            if (!$barang) throw new \Exception("Barang tidak ditemukan.");

            $stokSekarang = (int) $barang->jumlah_barang;
            if ($stokSekarang < $item->quantity) {
                throw new \Exception("Stok barang '{$barang->title}' tidak mencukupi.");
            }

            $barang->update(['jumlah_barang' => $stokSekarang - $item->quantity]);
            
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'barang_id' => $item->barang_id,
                'product_name' => $barang->title,
                'quantity' => $item->quantity,
                'price' => $barang->harga,
                'subtotal' => $barang->harga * $item->quantity,
            ]);
        }

        // --- LANGKAH 4: JIKA PAKAI MIDTRANS, BUAT PARAMS & GET TOKEN ---
        if ($request->payment_method == 'midtrans') {
            // Set konfigurasi Midtrans
            \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
            \Midtrans\Config::$isProduction = false; // set true jika sudah live
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $transaction->invoice_code,
                    'gross_amount' => (int) $grandTotal,
                ],
                'customer_details' => [
                    'first_name' => $request->name,
                    'email'      => $request->email,
                    'phone'      => $request->phone,
                ],
                'item_details' => $cartItems->map(function ($item) {
                    return [
                        'id'       => $item->barang_id,
                        'price'    => (int) $item->barang->harga,
                        'quantity' => $item->quantity,
                        'name'     => substr($item->barang->title, 0, 50),
                    ];
                })->toArray()
            ];

            if ($discountAmount > 0) {
                $params['item_details'][] = [
                    'id'       => 'DISCOUNT',
                    'price'    => (int) -$discountAmount,
                    'quantity' => 1,
                    'name'     => 'Potongan Diskon',
                ];
            }

            // Ambil Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // UPDATE Transaksi dengan Snap Token yang baru didapat
            $transaction->update([
                'snap_token' => $snapToken
            ]);
        }

        // Hapus Keranjang
        CartItem::where('user_id', $user->id)->delete();

        DB::commit();
        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
    }
}

    // Function History (Order List)
    public function history()
    {
        $user = Auth::user();
       $orders = Transaction::where('user_id', Auth::id())
            ->with(['items.barang']) // <--- Tambahkan .barang di sini
            ->orderBy('created_at', 'desc')
            ->get();

        return view('OrderList', compact('orders'));
    }

}