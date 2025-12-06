<?php
//  vincenet 10123309 

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class controller_shop extends Controller
{

    public function index(){
        $kategoris = Kategori::all();
        // $barang = Barang::findOrFail($id);
        $barang = Barang::all();
        return view('pages.shop.shop',[
            'barangs' => $barang,
            'kategoris' => $kategoris
        ]);
    }
    
    public function show($kategoriId){  
        $kategori = Kategori::findOrFail($kategoriId);
        $barangs = $kategori->barangs; // Mengambil barang berdas
        return view('pages.shop.kategori', [
            'barangs' => $barangs,
            'kategori' => $kategori // Ini adalah variabel yang harus ada di view
        ]);

    }

public function showKategori(Kategori $kategori)
{
    return view('pages.shop.kategori', [
        'kategori'  => $kategori,              // Kategori yang dipilih (Judul)
        'kategoris' => Kategori::all(),        // List untuk Sidebar
        'barangs'   => $kategori->barangs      // Produk yang sudah difilter
    ]);
}

//     public function showKategori(Kategori $kategori)
// {
//     // Ambil kategori untuk sidebar
//     $kategoris = Kategori::all();

//     // Ambil barang HANYA yang termasuk kategori yang dipilih
//     // Menggunakan relasi yang dibuat di langkah 1
//     $barangs = $kategori->barangs; 
    
//     // Ubah judul agar dinamis
//     $judulHalaman = "Kategori: " . $kategori->nama_kategori;

//     // Return ke View yang SAMA dengan data yang sudah difilter
//     return view('', compact('kategoris', 'barangs', 'judulHalaman'));
// }

}
