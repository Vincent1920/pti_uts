<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function index()
    {
        // Tetap menggunakan eager loading agar performa cepat
        $transactions = Transaction::with(['user', 'items.barang'])
                        ->latest()
                        ->get();

        return view('admins.transactions.index', compact('transactions'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Sesuaikan validasi dengan opsi yang ada di View Admin
        $request->validate([
            'status_dari_admin' => 'required|in:pending,processing,shipping,completed,cancelled'
        ]);

        $transaction = Transaction::findOrFail($id);
        
        // Update kolom 'status_dari_admin', bukan kolom 'status' (milik Midtrans)
        $transaction->status_dari_admin = $request->status_dari_admin;
        $transaction->save();

        return back()->with('success', 'Progres pesanan #' . $transaction->invoice_code . ' berhasil diperbarui!');
    }
}