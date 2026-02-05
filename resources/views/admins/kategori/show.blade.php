@extends('admins.index')

@section('admin')

<div class="max-w-3xl mx-auto mt-8">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-choco">Daftar Kategori</h1>
            <p class="text-sm text-gray-500">List semua kategori produk yang tersedia.</p>
        </div>

       <a href="{{ route('kategori.create') }}" class="...">
    Tambah Kategori
</a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-cream text-choco uppercase tracking-wider font-bold">
                <tr>
                    <th scope="col" class="px-6 py-4 w-16 text-center">#</th>
                    <th scope="col" class="px-6 py-4">Nama Kategori</th>
                    <th scope="col" class="px-6 py-4 w-32 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse($kategoris as $index => $kategori)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 text-center text-gray-500 font-medium">
                        {{ $index + 1 }}
                    </td>

                    <td class="px-6 py-4 text-gray-800 font-semibold text-lg">
                        {{ $kategori->nama_kategori }}
                    </td>

                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('kategori.edit', $kategori->id) }}" 
                            class="p-2 bg-yellow-50 text-yellow-600 rounded hover:bg-yellow-100 transition"
                            title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                           <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-2 bg-red-50 text-red-600 rounded hover:bg-red-100 transition"
                                        onclick="return confirm('Yakin ingin menghapus data ini?')"
                                        title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-gray-500 bg-gray-50 italic">
                        Belum ada kategori yang dibuat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection