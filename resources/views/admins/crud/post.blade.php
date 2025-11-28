@extends('admins.index')

@section('admin')

<div class="w-full max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-choco">Daftar Barang</h1>
            <p class="text-gray-500 text-sm">Kelola semua produk di toko Anda.</p>
        </div>

        <a href="/creat"
            class="group relative inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold text-white transition-all duration-200 bg-choco rounded-lg hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-choco shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
            <i class="bi bi-plus-lg mr-2 text-lg"></i>
            Create New Post
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
        <p class="font-bold">Berhasil!</p>
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-left text-sm">
                <thead class="bg-cream text-choco">
                    <tr>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider">Product</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider">jumlah barang</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @foreach($barangs as $barang)
                    <tr class="hover:bg-gray-50 transition-colors">

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 rounded-lg overflow-hidden border border-gray-200 flex-shrink-0">
                                    <img src="{{ asset('images/' . $barang->img) }}" alt="{{ $barang->title }}"
                                        class="h-full w-full object-cover">
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold text-gray-800">{{ $barang->title }}</span>
                                    <span class="text-xs text-gray-500"> Berat / Satuan
                                        {{ $barang->berat_barang }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 max-w-xs">
                            <p class="truncate text-gray-500" title="{{ $barang->deskripsi }}">
                                {{ $barang->deskripsi }}
                            </p>
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-800">
                            Rp {{ number_format($barang->harga, 0, ',', '.') }}
                        </td>



                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cream/50 text-choco">
                                {{ $barang->kategori->nama_kategori }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cream/50 text-choco">
                                {{ $barang->jumlah_barang }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{route('barangs.show',$barang->id)}}"
                                    class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition tooltip-btn"
                                    title="View Details">
                                    <i class="bi bi-eye text-lg"></i>
                                </a>

                                <a href="{{ route('barangs.edit', $barang->id) }}"
                                    class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition tooltip-btn"
                                    title="Edit Data">
                                    <i class="bi bi-pencil-square text-lg"></i>
                                </a>

                                <form id="delete-form-{{ $barang->id }}"
                                    action="{{ route('barangs.destroy', $barang->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $barang->id }})"
                                        class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition tooltip-btn"
                                        title="Delete">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>

                            <div id="extra-info-{{ $barang->id }}" style="display:none;">
                                <p>Harga: {{ $barang->harga }}</p>
                                <p>Kategori: {{ $barang->kategori->nama_kategori }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($barangs->isEmpty())
        <div class="p-8 text-center text-gray-500 bg-gray-50">
            <i class="bi bi-box-seam text-4xl mb-3 block"></i>
            <p>Belum ada barang. Silakan buat post baru.</p>
        </div>
        @endif
    </div>
</div>

{{-- Note: SweetAlert2 sudah diload di layout utama (admins.index), jadi kita gunakan script yg kompatibel --}}
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // Merah untuk bahaya
            cancelButtonColor: '#3085d6', // Biru untuk batal
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>

{{-- Script custom Anda --}}
<script src="../js/admins/shop.js"></script>

@endsection