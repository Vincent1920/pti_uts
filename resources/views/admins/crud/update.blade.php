@extends('admins.index')

@section('admin')
<div class="max-w-3xl mx-auto">
    
    <div class="border-b border-gray-200 pb-4 mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-choco">Edit Barang</h1>
            <p class="text-sm text-gray-500 mt-1">Perbarui informasi produk: <strong>{{ $barang->title }}</strong></p>
        </div>
        <a href="{{ route('post') }}" class="text-sm text-gray-500 hover:text-choco transition">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <form method="post" action="{{ route('barangs.update', $barang->id) }}" class="space-y-6 mb-10" enctype="multipart/form-data">
        @csrf
        @method('PUT') <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title / Nama Produk</label>
            <div class="mt-1">
                <input type="text" 
                       id="title" 
                       name="title" 
                       required 
                       value="{{ old('title', $barang->title) }}"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-choco focus:ring-choco sm:text-sm p-2.5 border @error('title') border-red-500 @enderror">
            </div>
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="harga_display" class="block text-sm font-medium text-gray-700">Harga</label>
            <div class="relative mt-1 rounded-md shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <span class="text-gray-500 sm:text-sm font-bold">IDR</span>
                </div>
                
                <input type="text" 
                       id="harga_display" 
                       class="block w-full rounded-md border-gray-300 pl-12 pr-12 focus:border-choco focus:ring-choco sm:text-sm p-2.5 border @error('harga') border-red-500 @enderror" 
                       placeholder="0"
                       required
                       value="{{ old('harga') ? number_format(old('harga'), 0, ',', '.') : number_format($barang->harga, 0, ',', '.') }}">
                
                <input type="hidden" name="harga" id="harga_actual" value="{{ old('harga', $barang->harga) }}">
            </div>
            @error('harga')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori</label>
            <select id="kategori_id" 
                    name="kategori_id" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-choco focus:ring-choco sm:text-sm p-2.5 border">
                <option value="" disabled>Pilih kategori</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" 
                        {{ old('kategori_id', $barang->kategori_id) == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="berat_barang" class="block text-sm font-medium text-gray-700">Berat (Gram/Slice)</label>
            <div class="mt-1">
                <input type="text" 
                       id="berat_barang" 
                       name="berat_barang" 
                       required 
                       value="{{ old('berat_barang', $barang->berat_barang) }}"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-choco focus:ring-choco sm:text-sm p-2.5 border @error('berat_barang') border-red-500 @enderror">
            </div>
            @error('berat_barang')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
<div>
            <label for="jumlah_barang" class="block text-sm font-medium text-gray-700">Jumlah Barang</label>
            <div class="mt-1">
                <input type="number" 
                       id="jumlah_barang" 
                       name="jumlah_barang" 
                       required 
                       min="1"
                       value="{{ old('jumlah_barang', $barang->jumlah_barang) }}"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-choco focus:ring-choco sm:text-sm p-2.5 border @error('jumlah_barang') border-red-500 @enderror">
            </div>
            @error('jumlah_barang')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Post Image (Opsional)</label>
            
            <div class="mb-3 flex gap-4 items-end">
                <div class="relative">
                    <p class="text-xs text-gray-500 mb-1">Preview:</p>
                    @if($barang->img)
                        <img id="imagePreview" src="{{ asset('images/' . $barang->img) }}" class="w-32 h-32 object-cover rounded-lg border border-gray-300 shadow-sm">
                    @else
                        <img id="imagePreview" class="hidden w-32 h-32 object-cover rounded-lg border border-gray-300 shadow-sm">
                        <div id="noImageText" class="w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-xs border border-gray-300">No Image</div>
                    @endif
                </div>
            </div>

            <input class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-cream file:text-choco
                    hover:file:bg-cream_hover
                    border border-gray-300 rounded-md cursor-pointer bg-white focus:outline-none
                    @error('img') border-red-500 @enderror" 
                    id="image" 
                    type="file" 
                    name="img" 
                    onchange="previewImage()">
            <p class="text-xs text-gray-500 mt-1">*Biarkan kosong jika tidak ingin mengubah gambar.</p>
            
            @error('img')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <div class="mt-1">
                <textarea id="deskripsi" 
                          name="deskripsi" 
                          rows="4" 
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-choco focus:ring-choco sm:text-sm p-2.5 border">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
            </div>
        </div>

        <div class="pt-4 flex gap-3">
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-choco hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-choco transition duration-150">
                Update Data
            </button>
            <a href="{{ route('post') }}" class="w-1/3 flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                Batal
            </a>
        </div>

    </form>
</div>

<script>
    // 1. Script Preview Image
    function previewImage() {
        const image = document.querySelector('#image');
        const imgPreview = document.querySelector('#imagePreview');
        const noImageText = document.querySelector('#noImageText');

        if (image.files && image.files[0]) {
            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function (oFREvent) {
                imgPreview.src = oFREvent.target.result;
            };

            imgPreview.classList.remove('hidden');
            if(noImageText) noImageText.classList.add('hidden');
        }
    }

    // 2. Script Format Rupiah
    const hargaDisplay = document.getElementById('harga_display');
    const hargaActual = document.getElementById('harga_actual');

    hargaDisplay.addEventListener('keyup', function(e) {
        let value = this.value.replace(/[^0-9]/g, '');
        hargaActual.value = value;
        this.value = formatRupiah(value);
    });

    function formatRupiah(angka) {
        let number_string = angka.toString(),
            sisa    = number_string.length % 3,
            rupiah  = number_string.substr(0, sisa),
            ribuan  = number_string.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }
</script>

@endsection