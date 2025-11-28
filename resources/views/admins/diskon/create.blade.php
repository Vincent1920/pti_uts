@extends('admins.index')

@section('admin')
<div class="max-w-md mx-auto mt-10">
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        
        <div class="bg-choco px-6 py-4 flex justify-between items-center">
            <h2 class="text-lg font-bold text-white">Buat Diskon Baru</h2>
            <a href="{{ route('diskon.index') }}" class="text-cream hover:text-white transition"><i class="bi bi-x-lg"></i></a>
        </div>

        <div class="p-6">
            <form action="{{ route('diskon.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Promo / Diskon</label>
                    <input type="text" name="nama_diskon" required placeholder="Contoh: Promo Merdeka"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-choco focus:ring-choco p-2.5 border">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Besar Diskon (%)</label>
                    <div class="relative">
                        <input type="number" name="persentase" required min="1" max="100" placeholder="15"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-choco focus:ring-choco p-2.5 border pr-10">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500 font-bold">%</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Masukkan angka 1 sampai 100.</p>
                </div>

                <button type="submit" class="w-full bg-choco text-white font-bold py-2.5 rounded-lg hover:bg-opacity-90 transition shadow-md">
                    Simpan Diskon
                </button>
            </form>
        </div>
    </div>
</div>
@endsection