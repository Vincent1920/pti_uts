<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Diskon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
  
    public function process(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'address' => 'required',
            'phone' => 'required',
            'payment_method' => 'required|in:bank_transfer,cod',
            'payment_proof' => 'required_if:payment_method,bank_transfer|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::user();
            $cartItems = CartItem::with('barang')->where('user_id', $user->id)->get();
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('shop');
            }

            // Hitung Subtotal dll...
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item->barang->harga * $item->quantity;
            }
            $diskon = \App\Models\Diskon::where('status', true)->first();
            $discountAmount = $diskon ? ($subtotal * $diskon->persentase) / 100 : 0;
            $grandTotal = $subtotal - $discountAmount;

            // 2. PERBAIKAN LOGIKA UPLOAD GAMBAR
            // Syntax $request->move(...) itu salah. Gunakan $request->file(...)->store(...)
            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                // Ini akan menyimpan di storage/app/public/payment_proofs
                $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            // 3. Simpan Transaksi
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
                'payment_method' => $request->payment_method,
                'payment_proof' => $proofPath, 
                'status' => ($request->payment_method == 'cod') ? 'unpaid' : 'pending',
            ]);

            // Simpan Detail Item
            foreach ($cartItems as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'barang_id' => $item->barang_id,
                    'product_name' => $item->barang->title,
                    'quantity' => $item->quantity,
                    'price' => $item->barang->harga,
                    'subtotal' => $item->barang->harga * $item->quantity,
                ]);
            }

            // Hapus Keranjang (Ini yang bikin cart jadi kosong)
            CartItem::where('user_id', $user->id)->delete();

            DB::commit();
            
            // 4. PERBAIKAN REDIRECT
            // Arahkan ke route 'orders.index' (halaman history), JANGAN ke checkout lagi.
            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Function History (Order List)
    public function history()
    {
        $user = Auth::user();
        $orders = Transaction::where('user_id', $user->id)
                    ->with('items') 
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('OrderList', compact('orders'));
    }

}