@extends('admins.index')

@section('admin')

<div class="max-w-4xl mx-auto mt-8">
    
    <!-- Card Container -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
        
        <!-- Header Tabel -->
        <div class="bg-choco px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white">
                Detail Data Barang
            </h2>
            <a href="{{ route('barangs.index') }}" class="text-xs bg-cream hover:bg-cream_hover text-choco font-bold px-3 py-2 rounded transition">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <!-- Tabel Detail -->
        <table class="min-w-full text-sm text-left">
            <tbody class="divide-y divide-gray-200">
                
                <!-- Baris 1: Nama Barang -->
                <tr class="hover:bg-gray-50">
                    <th class="w-1/4 px-6 py-4 font-semibold text-gray-700 bg-gray-50 uppercase tracking-wider text-xs">
                        Nama Produk
                    </th>
                    <td class="px-6 py-4 text-gray-900 font-medium text-lg">
                        {{ $barang->title }}
                    </td>
                </tr>

                <!-- Baris 2: Kategori -->
                <tr class="hover:bg-gray-50">
                    <th class="px-6 py-4 font-semibold text-gray-700 bg-gray-50 uppercase tracking-wider text-xs">
                        Kategori
                    </th>
                    <td class="px-6 py-4 text-gray-900">
                        <span class="inline-block bg-cream text-choco text-xs px-3 py-1 rounded-full font-bold">
                            {{ $barang->kategori->nama_kategori }}
                        </span>
                    </td>
                </tr>

                <!-- Baris 3: Harga -->
                <tr class="hover:bg-gray-50">
                    <th class="px-6 py-4 font-semibold text-gray-700 bg-gray-50 uppercase tracking-wider text-xs">
                        Harga
                    </th>
                    <td class="px-6 py-4 text-gray-900 font-bold text-xl">
                        Rp {{ number_format($barang->harga, 0, ',', '.') }}
                    </td>
                </tr>

                <!-- Baris 4: Berat (Kondisional) -->
                @if($barang->berat_barang)
                <tr class="hover:bg-gray-50">
                    <th class="px-6 py-4 font-semibold text-gray-700 bg-gray-50 uppercase tracking-wider text-xs">
                        Berat / Satuan
                    </th>
                    <td class="px-6 py-4 text-gray-900">
                        {{ $barang->berat_barang }}
                    </td>
                </tr>
                @endif

                <!-- Baris 5: Deskripsi -->
                <tr class="hover:bg-gray-50">
                    <th class="px-6 py-4 font-semibold text-gray-700 bg-gray-50 uppercase tracking-wider text-xs align-top">
                        Deskripsi
                    </th>
                    <td class="px-6 py-4 text-gray-600 leading-relaxed align-top">
                        {{ $barang->deskripsi }}
                    </td>
                </tr>

                <!-- Baris 6: Gambar -->
                <tr class="hover:bg-gray-50">
                    <th class="px-6 py-4 font-semibold text-gray-700 bg-gray-50 uppercase tracking-wider text-xs align-top">
                        Foto Produk
                    </th>
                    <td class="px-6 py-4">
                        @if($barang->img)
                            <div class="border border-gray-200 p-2 rounded-md inline-block bg-white shadow-sm">
                                <img src="{{ asset('images/' . $barang->img) }}" 
                                     alt="{{ $barang->title }}" 
                                     class="max-w-xs h-auto rounded object-cover">
                            </div>
                        @else
                            <span class="text-gray-400 italic">Tidak ada gambar</span>
                        @endif
                    </td>
                </tr>

            </tbody>
        </table>

 
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
        </div>

    </div>
</div>

@endsection