<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function index()
    {
        // Ambil semua transaksi, urutkan dari yang terbaru
        // Load relasi 'user' dan 'items.barang' (agar gambar produk muncul)
        // Kita juga load 'items' saja karena snapshot data ada di sana
        $transactions = Transaction::with(['user', 'items'])->latest()->get();

        return view('admins.transactions.index', compact('transactions'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:unpaid,pending,paid,shipping,completed,cancelled'
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->status = $request->status;
        $transaction->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
