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
            'payment_method' => 'required|in:bank_transfer,cod',
            'payment_proof' => 'required_if:payment_method,bank_transfer|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        // dd($request);
        try {
            $user = Auth::user();
            // Load barang supaya kita bisa akses stoknya
            $cartItems = CartItem::with('barang')->where('user_id', $user->id)->get();
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('shop');
            }

            // Hitung Subtotal
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item->barang->harga * $item->quantity;
            }
            
            $diskon = Diskon::where('status', true)->first();
            $discountAmount = $diskon ? ($subtotal * $diskon->persentase) / 100 : 0;
            $grandTotal = $subtotal - $discountAmount;

            // 2. PROSES UPLOAD GAMBAR
            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('payment_proofs'), $fileName);
                $proofPath = 'payment_proofs/' . $fileName;
            }

            // 3. Simpan Transaksi Utama
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
                'city' => $request->city ?? '', 
                'postal_code' => $request->postal_code ?? '',
                'payment_method' => $request->payment_method,
                'payment_proof' => $proofPath, 
                
                // --- BAGIAN INI DIHAPUS ATAU DI-KOMENTAR ---
                'status' => ($request->payment_method == 'cod') ? 'unpaid' : 'pending',
                // -------------------------------------------
            ]);

            // 4. Simpan Detail Item DAN Kurangi Stok
            foreach ($cartItems as $item) {
                
                // --- LOGIKA PENGURANGAN STOK DI SINI ---
                
                // Ambil data barang terkait
                $barang = $item->barang;

                // Pastikan barangnya ada (safety check)
                if (!$barang) {
                    throw new \Exception("Barang tidak ditemukan.");
                }

                // Cek apakah stok mencukupi
                // Kita convert ke int karena di database kamu tipenya string
                $stokSekarang = (int) $barang->jumlah_barang;
                $jumlahBeli = $item->quantity;

                if ($stokSekarang < $jumlahBeli) {
                    // Jika stok kurang, batalkan semua (rollback) dan beri pesan error
                    throw new \Exception("Stok barang '{$barang->title}' tidak mencukupi. Sisa: {$stokSekarang}");
                }

                // Kurangi stok
                $barang->jumlah_barang = $stokSekarang - $jumlahBeli;
                $barang->save(); // Simpan perubahan stok ke database
                
                // ---------------------------------------

                // Simpan ke TransactionItem
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'barang_id' => $item->barang_id,
                    'product_name' => $barang->title,
                    'quantity' => $item->quantity,
                    'price' => $barang->harga,
                    'subtotal' => $barang->harga * $item->quantity,
                ]);
            }

            // Hapus Keranjang setelah berhasil
            CartItem::where('user_id', $user->id)->delete();

            DB::commit();
            
            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Kembali ke halaman sebelumnya dengan pesan error
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
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