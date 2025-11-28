<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::all();
        return view('admins.kategori.show',[
            'kategoris' => $kategori,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    // 1. Validasi
    $request->validate([
        'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori',
    ], [
        'nama_kategori.required' => 'Nama kategori tidak boleh kosong.',
        'nama_kategori.unique' => 'Kategori ini sudah ada.',
    ]);

    // 2. Simpan
    Kategori::create([
        'nama_kategori' => $request->nama_kategori
    ]);

    return redirect()->route('kategori')->with('success', 'Kategori berhasil ditambahkan.');
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit($id)
{
    $kategori = Kategori::findOrFail($id);
    return view('admins.kategori.edit', compact('kategori'));
}

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    // 1. Validasi
    $request->validate([
        // Validasi unique mengecualikan ID kategori ini sendiri
        'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori,' . $id,
    ]);

    // 2. Update
    $kategori = Kategori::findOrFail($id);
    $kategori->update([
        'nama_kategori' => $request->nama_kategori
    ]);

    return redirect()->route('kategori')->with('success', 'Kategori berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
