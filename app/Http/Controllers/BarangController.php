<?php
//  vincenet 10123309 

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::all();
        $kategori = Kategori::all();
        return view('admins.crud.post',[
            'barangs' => $barangs,
            'kategori' => $kategori,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::all();
        // $barangs = Barang::all();
        return view('admins.crud.create',[
            'kategoris' => $kategori,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // 1. Validasi Input
    $validated = $request->validate([
        'title'        => 'required|string|max:255',
        'img'          => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'deskripsi'    => 'required|string',
        'harga'        => 'required|numeric', // Pastikan input hidden 'harga' yang diterima
        'berat_barang' => 'required',
        'jumlah_barang' => 'required|numeric|min:1',
        'kategori_id'  => 'required|exists:kategoris,id',
    ]);

    try {
        // 2. Proses Upload Gambar
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            // Nama file: time_random.extension (agar lebih unik)
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        } else {
            return back()->withErrors(['img' => 'Gambar wajib diupload.'])->withInput();
        }

        // 3. Simpan ke Database
        Barang::create([
            'title'        => $request->title,
            'berat_barang' => $request->berat_barang,
            'img'          => $imageName,
            'deskripsi'    => $request->deskripsi,
            'harga'        => $request->harga,
            'kategori_id'  => $request->kategori_id,
             'jumlah_barang'=> $request->jumlah_barang,
            'user_id'      => Auth::id(), // Mengambil ID user yang sedang login
        ]);

        // 4. Redirect Sukses
        return redirect()->route('post')->with('success', 'Data barang berhasil ditambahkan.');

    } catch (\Exception $e) {
        // 5. Error Handling (Jika gagal simpan)
        return back()
            ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
            ->withInput();
    }
}

    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $barang = Barang::findOrFail($id);
        return view('admins.crud.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    // Ambil data barang berdasarkan ID
    $barang = Barang::findOrFail($id);
    
    // Ambil semua kategori dari database
    $kategoris = Kategori::all();

    // Kirim data barang dan kategori ke view
    return view('admins.crud.update', [
        'barang' => $barang,
        'kategoris' => $kategoris
    ]);
}

    public function update(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'title' => 'required|string|max:255',
        'harga' => 'required|numeric',
        'berat_barang' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'jumlah_barang' => 'required|numeric|min:1',
        'kategori_id' => 'required|exists:kategoris,id', 
        'img' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', 
    ]);

    // Temukan barang berdasarkan ID
    $barang = Barang::findOrFail($id);

    // Perbarui data barang
    $barang->title = $request->input('title');
    $barang->harga = $request->input('harga');
    $barang->berat_barang = $request->input('berat_barang');
    $barang->jumlah_barang = $request->jumlah_barang;
    $barang->kategori_id = $request->input('kategori_id'); // Perbarui kategori_id
    $barang->deskripsi = $request->input('deskripsi');

    // Periksa jika ada gambar yang di-upload
    if ($request->hasFile('img')) {
        if ($barang->img) {
            // Hapus file lama dari storage jika ada
            Storage::delete('public/images/' . $barang->img);
        }

    
        $imageName = time() . '.' . $request->img->extension();
        $request->img->move(public_path('images'), $imageName);

        // Perbarui nama gambar di database
        $barang->img = $imageName;
    }

    // Simpan perubahan
    $barang->save();

    return redirect()->route('barangs.index')->with('success', 'Barang updated successfully');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    // 1. Cari data barang
    $barang = Barang::findOrFail($id);

    // 2. Definisikan path gambar
    // Pastikan kolom img tidak kosong untuk menghindari error path
    if ($barang->img) {
        $imagePath = public_path('images/' . $barang->img);

        // 3. Cek apakah file fisik benar-benar ada di folder
        if (File::exists($imagePath)) {
            // Hapus file gambar
            File::delete($imagePath);
        }
    }

    // 4. Hapus data dari database
    $barang->delete();

    return redirect()->route('post')->with('success', 'Barang dan gambar berhasil dihapus.');
}
    
}
