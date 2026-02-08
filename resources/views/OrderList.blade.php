@extends('index')

@section('container-home')
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    choco: '#463126',
                    choco_light: '#654e42',
                    cream: '#f3eae5',
                    gold: '#c88a5b',
                    alice: '#f0f8ff',
                },
                fontFamily: {
                    qwigley: ['Qwigley', 'cursive'],
                    kotta: ['Kotta One', 'serif'],
                    manuale: ['Manuale', 'serif'],
                }
            }
        }
    }
</script>




<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-kotta text-gray-900 mb-6">Daftar Transaksi</h1>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if($orders->isEmpty())
    <div class="text-center py-20 bg-white rounded-lg shadow-sm border border-gray-200">
        <i class="bi bi-bag-x text-6xl text-gray-300 mb-4 block"></i>
        <p class="text-gray-500 text-lg">Belum ada transaksi.</p>
        <a href="{{ route('shop') }}"
            class="mt-4 inline-block bg-choco text-white px-6 py-2 rounded-md hover:bg-choco_light transition">Mulai
            Belanja</a>
    </div>
    @else
    <div class="space-y-4">
        @foreach($orders as $order) {{-- Pastikan variabel ini adalah $order --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 transition hover:shadow-md">

            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mb-4 text-sm">
                <div class="flex items-center gap-2">
                    <i class="bi bi-bag-fill text-choco"></i>
                    <span class="font-bold text-gray-900">Belanja</span>
                    <span class="text-gray-500">{{ $order->created_at->format('d M Y') }}</span>
                </div>

                @php
                // 1. Inisialisasi Default
                $statusColor = 'bg-gray-100 text-gray-500';
                $statusLabel = 'Status Menunggu';
                $mColor = 'text-gray-500 border-gray-300';
                $midtransBadge = null;

                // 2. Tentukan Label Dasar berdasarkan Metode & Status Pembayaran
                if ($order->payment_method != 'cod') {
                $midtransBadge = $order->status; // Ambil kolom 'status' (success, pending, etc)

                if (in_array($order->status, ['settlement', 'success', 'capture'])) {
                $statusColor = 'bg-green-100 text-green-700';
                $statusLabel = 'Pembayaran Berhasil';
                $mColor = 'text-green-600 border-green-600';
                } elseif ($order->status == 'pending') {
                $statusColor = 'bg-orange-100 text-orange-700';
                $statusLabel = 'Menunggu Pembayaran';
                $mColor = 'text-orange-500 border-orange-500';
                } else {
                $statusColor = 'bg-red-100 text-red-700';
                $statusLabel = 'Pembayaran Gagal/Batal';
                $mColor = 'text-red-500 border-red-500';
                }
                } else {
                $statusColor = 'bg-blue-100 text-blue-700';
                $statusLabel = 'COD: Menunggu Konfirmasi';
                }

                // 3. LOGIKA SINKRONISASI ADMIN (Override Label Dasar)
                // Jika admin mengubah status_dari_admin, maka label ini yang menang
                if ($order->status_dari_admin == 'processing') {
                $statusColor = 'bg-blue-100 text-blue-700';
                $statusLabel = 'Pesanan Sedang Diproses Admin';
                } elseif ($order->status_dari_admin == 'shipping') {
                $statusColor = 'bg-purple-100 text-purple-700';
                $statusLabel = 'Barang Sedang Dikirim Kurir';
                } elseif ($order->status_dari_admin == 'completed') {
                $statusColor = 'bg-green-500 text-white';
                $statusLabel = 'Pesanan Telah Selesai';
                } elseif ($order->status_dari_admin == 'cancelled') {
                $statusColor = 'bg-red-500 text-white';
                $statusLabel = 'Dibatalkan oleh Admin';
                }

                // Ambil data produk untuk display
                $firstItem = $order->items->first();
                $productImage = $firstItem->barang->img ?? null;
                @endphp

                <div class="flex flex-wrap gap-2 items-center">
                    <span class="px-3 py-1 rounded-full text-[11px] font-bold {{ $statusColor }}">
                        {{ $statusLabel }}
                    </span>

                    @if($midtransBadge && $order->payment_method != 'cod')
                    <span
                        class="px-2 py-0.5 rounded border text-[10px] uppercase font-mono font-semibold {{ $mColor }}">
                        Online Pay: {{ $midtransBadge }}
                    </span>
                    @endif
                </div>

                <span class="text-gray-400 text-xs sm:ml-auto font-mono">{{ $order->invoice_code }}</span>
            </div>

            <div class="flex items-center gap-1 mb-4 text-sm font-bold text-gray-800">
                <i class="bi bi-shop text-gold"></i> ChocoScript Official
            </div>

            <div class="flex gap-4">
                <div
                    class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-md overflow-hidden flex-shrink-0 border border-gray-200">
                    @if($productImage)
                    <img src="{{ asset('images/' . $productImage) }}" class="w-full h-full object-cover" alt="Product">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400">
                        <i class="bi bi-image text-xl"></i>
                    </div>
                    @endif
                </div>

                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 text-sm sm:text-base truncate line-clamp-1">
                        {{ $firstItem->product_name ?? 'Produk Tidak Diketahui' }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $firstItem->quantity ?? 0 }} barang x IDR
                        {{ number_format($firstItem->price ?? 0, 0, ',', '.') }}
                    </p>

                    @if($order->items->count() > 1)
                    <p class="text-[11px] text-choco font-medium mt-1">+{{ $order->items->count() - 1 }} produk lainnya
                    </p>
                    @endif
                </div>

                <div class="hidden sm:block text-right border-l pl-4 border-gray-200 min-w-[120px]">
                    <p class="text-xs text-gray-500 mb-1">Total Belanja</p>
                    <p class="font-bold text-choco">IDR {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-4 flex flex-col sm:flex-row justify-between items-end sm:items-center gap-4">
                <div class="sm:hidden w-full flex justify-between items-center border-t pt-3">
                    <p class="text-sm text-gray-500">Total Belanja</p>
                    <p class="font-bold text-choco">IDR {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                </div>

                <div class="flex gap-2 w-full sm:w-auto justify-end mt-2 sm:mt-0">
                    <a href="{{ route('shop') }}"
                        class="w-full sm:w-auto bg-choco text-white font-bold text-xs px-6 py-2.5 rounded hover:bg-choco_light transition text-center uppercase tracking-wider">
                        Beli Lagi
                    </a>
                </div>
            </div>

        </div>
        @endforeach {{-- Akhir dari foreach --}}
    </div>
    @endif
</div>

@endsection