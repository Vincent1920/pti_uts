@extends('admins.index')

@section('admin')

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-choco">Dashboard</h1>
        <p class="text-gray-600 mt-1">Ringkasan data toko Anda.</p>
    </div>

    <div class="mb-10">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 border-l-4 border-choco pl-3">
            Daftar Kategori
        </h2>
        
        <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-cream">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left font-bold text-choco uppercase tracking-wider">
                            Nama Kategori
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($kategori as $cat) 
                    {{-- Note: Saya ubah variabel alias jadi $cat agar tidak bentrok nama variabel --}}
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $cat->nama_kategori }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <h2 class="text-xl font-semibold text-gray-800 mb-4 border-l-4 border-choco pl-3">
            Daftar Barang
        </h2>

        <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-cream">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left font-bold text-choco uppercase tracking-wider">Title</th>
                        <th scope="col" class="px-6 py-3 text-left font-bold text-choco uppercase tracking-wider">Image</th>
                        <th scope="col" class="px-6 py-3 text-left font-bold text-choco uppercase tracking-wider">Deskripsi</th>
                        <th scope="col" class="px-6 py-3 text-left font-bold text-choco uppercase tracking-wider">Harga</th>
                        <th scope="col" class="px-6 py-3 text-left font-bold text-choco uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-left font-bold text-choco uppercase tracking-wider">Berat (G)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($barangs as $barang)
                    <tr class="hover:bg-gray-50 transition-colors even:bg-gray-50/50">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $barang->title }}
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="h-16 w-16 overflow-hidden rounded-md border border-gray-200">
                                <img src="{{ asset('images/' . $barang->img) }}" 
                                     alt="{{ $barang->title }}" 
                                     class="h-full w-full object-cover">
                            </div>
                        </td>

                        <td class="px-6 py-4 text-gray-500 max-w-xs truncate">
                            {{ $barang->deskripsi }}
                        </td>

                        <td class="px-6 py-4 text-gray-900 font-semibold whitespace-nowrap">
                            Rp {{ number_format($barang->harga, 0, ',', '.') }}
                        </td>

                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            <span class="px-2 py-1 bg-cream/50 text-choco rounded-full text-xs font-semibold">
                                {{ $barang->kategori->nama_kategori }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $barang->berat_barang }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @endsection