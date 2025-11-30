<?php

namespace App\Http\Controllers;

use App\Models\Diskon;
use Illuminate\Http\Request;

class DiskonController extends Controller
{
   public function index()
    {
        $diskons = Diskon::all();
        return view('admins.diskon.index', compact('diskons'));
    }

    // Tampilkan Form Create
    public function create()
    {
        return view('admins.diskon.create');
    }

    // Simpan Data
    public function store(Request $request)
    {
        $request->validate([
            'nama_diskon' => 'required|string|max:255',
            'persentase'  => 'required|integer|min:1|max:100', // Validasi 1-100%
        ]);

        Diskon::create([
            'nama_diskon' => $request->nama_diskon,
            'persentase'  => $request->persentase,
            'status'      => false 
        ]);

        return redirect()->route('diskon.index')->with('success', 'Diskon berhasil dibuat.');
    }

    // Hapus Data
    public function destroy($id)
    {
        $diskon = Diskon::findOrFail($id);
        $diskon->delete();

        return redirect()->route('diskon.index')->with('success', 'Diskon berhasil dihapus.');
    }

    // Toggle Status (Aktif/Non-Aktif)
   public function toggleStatus($id)
    {
        $diskon = Diskon::findOrFail($id);

        if ($diskon->status == false) {
            // KASUS: MAU MENGAKTIFKAN
            // 1. Matikan (update) semua diskon lain menjadi false
            Diskon::where('id', '!=', $id)->update(['status' => false]);
            
            // 2. Aktifkan diskon yang dipilih
            $diskon->status = true;
            $pesan = 'Diskon diaktifkan. Diskon lain otomatis dinonaktifkan.';
        } else {
            // KASUS: MAU MENONAKTIFKAN
            // Langsung matikan saja (boleh tidak ada yang aktif sama sekali)
            $diskon->status = false;
            $pesan = 'Diskon dinonaktifkan.';
        }

        $diskon->save();

        return back()->with('success', $pesan);
    }
}
