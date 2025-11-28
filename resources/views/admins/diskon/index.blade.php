@extends('admins.index')

@section('admin')
<div class="max-w-4xl mx-auto mt-6">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-choco">Manajemen Diskon</h1>
            <p class="text-sm text-gray-500">Atur potongan harga untuk keranjang belanja.</p>
        </div>
        <a href="{{ route('diskon.create') }}" class="bg-choco text-white px-4 py-2 rounded-lg font-bold hover:bg-opacity-90 transition shadow">
            <i class="bi bi-plus-lg me-1"></i> Tambah Diskon
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <table class="w-full text-sm text-left">
            <thead class="bg-cream text-choco uppercase font-bold">
                <tr>
                    <th class="px-6 py-4">Nama Diskon</th>
                    <th class="px-6 py-4">Persentase</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($diskons as $d)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $d->nama_diskon }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-red-100 text-red-800 text-xs font-bold px-2.5 py-0.5 rounded border border-red-200">
                            {{ $d->persentase }}%
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('diskon.status', $d->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                class="text-xs font-bold px-3 py-1 rounded-full transition {{ $d->status ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-500' }}">
                                {{ $d->status ? 'Aktif' : 'Non-Aktif' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('diskon.destroy', $d->id) }}" method="POST" onsubmit="return confirm('Hapus diskon ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 transition" title="Hapus">
                                <i class="bi bi-trash text-lg"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($diskons->isEmpty())
            <div class="p-6 text-center text-gray-500">Belum ada data diskon.</div>
        @endif
    </div>
</div>
@endsection