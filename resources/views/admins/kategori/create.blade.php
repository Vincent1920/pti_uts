@extends('admins.index')

@section('admin')

<div class="max-w-md mx-auto mt-10">
    
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        
        <div class="bg-choco px-6 py-4 flex justify-between items-center">
            <h2 class="text-lg font-bold text-white">Tambah Kategori</h2>
            <a href="{{ route('kategori') }}" class="text-cream hover:text-white text-sm transition">
                <i class="bi bi-x-lg"></i>
            </a>
        </div>

        <div class="p-6">
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <label for="nama_kategori" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Kategori
                    </label>
                    <input type="text" 
                           name="nama_kategori" 
                           id="nama_kategori"
                           placeholder="Contoh: Kue Kering, Minuman, dll"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-choco focus:ring-choco p-2.5 border @error('nama_kategori') border-red-500 @enderror"
                           value="{{ old('nama_kategori') }}"
                           required 
                           autofocus>
                    
                    @error('nama_kategori')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="w-full bg-choco text-white font-bold py-2.5 rounded-lg hover:bg-opacity-90 transition shadow-md">
                        Simpan Kategori
                    </button>
                    <a href="{{ route('kategori') }}" class="w-full bg-gray-100 text-gray-600 font-bold py-2.5 rounded-lg hover:bg-gray-200 transition text-center border border-gray-300">
                        Batal
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection