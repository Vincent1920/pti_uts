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
                <a href="{{ route('shop') }}" class="mt-4 inline-block bg-choco text-white px-6 py-2 rounded-md hover:bg-choco_light transition">Mulai Belanja</a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($orders as $order)
                {{-- @dd($order->toArray()) --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 transition hover:shadow-md">
                    
                   <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mb-4 text-sm">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-bag-fill text-choco"></i>
                            <span class="font-bold text-gray-900">Belanja</span>
                            <span class="text-gray-500">{{ $order->created_at->format('d M Y') }}</span>
                        </div>
    
                @php
                    // Logika Status Internal (yang sudah kamu buat)
                    $statusColor = 'bg-gray-100 text-gray-500';
                    $statusLabel = 'Unknown Status';

                    if ($order->status == 'unpaid') {
                        $statusColor = 'bg-gray-200 text-gray-700';
                        $statusLabel = ($order->payment_method == 'cod') ? 'COD (Bayar Ditempat)' : 'Belum Dibayar';
                    } elseif ($order->status == 'pending') {
                        $statusColor = 'bg-orange-100 text-orange-700';
                        $statusLabel = 'Menunggu Konfirmasi';
                    } elseif ($order->status == 'paid') {
                        $statusColor = 'bg-blue-100 text-blue-700';
                        $statusLabel = 'Lunas / Proses';
                    } elseif ($order->status == 'shipping') {
                        $statusColor = 'bg-purple-100 text-purple-700';
                        $statusLabel = 'Dikirim';
                    } elseif ($order->status == 'completed') {
                        $statusColor = 'bg-green-100 text-green-700';
                        $statusLabel = 'Selesai';
                    } elseif ($order->status == 'cancelled') {
                        $statusColor = 'bg-red-100 text-red-700';
                        $statusLabel = 'Dibatalkan';
                    }

                    // Logika Warna Status Midtrans (Khusus Pembayaran Online)
                    $midtransBadge = '';
                    if($order->payment_method != 'cod' && $order->status_midtrans) {
                        $mColor = 'text-gray-500 border-gray-300';
                        if($order->status_midtrans == 'settlement' || $order->status_midtrans == 'capture') $mColor = 'text-green-600 border-green-600';
                        if($order->status_midtrans == 'pending') $mColor = 'text-orange-500 border-orange-500';
                        if(in_array($order->status_midtrans, ['expire', 'cancel', 'deny'])) $mColor = 'text-red-500 border-red-500';
                        
                        $midtransBadge = $order->status_midtrans;
                    }
                @endphp

                        <div class="flex flex-wrap gap-2 items-center">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>

                            @if($midtransBadge)
                            <span class="px-2 py-0.5 rounded border text-[10px] uppercase font-mono font-semibold {{ $mColor }}">
                                Midtrans: {{ $midtransBadge }}
                            </span>
                            @endif
                        </div>
                            
                        <span class="text-gray-400 text-xs sm:ml-auto">{{ $order->invoice_code }}</span>
                        </div>

                    <div class="flex items-center gap-1 mb-4 text-sm font-bold text-gray-800">
                        <i class="bi bi-shop text-gold"></i> ChocoScript Official
                    </div>

                    @php 
                        $firstItem = $order->items->first(); 
                        // Karena kita belum set relasi ke 'barang' di model TransactionItem (sebelumnya belum ada), 
                        // kita pakai product_name dan price snapshot yang ada di tabel transaction_items
                        // Jika Anda sudah menambahkan relasi belongsTo 'barang', bisa pakai $firstItem->barang->img
                    @endphp

                    <div class="flex gap-4">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-md overflow-hidden flex-shrink-0 border border-gray-200">
                            @if($firstItem->barang_id)
                                <img src="{{ asset('images/' . \App\Models\Barang::find($firstItem->barang_id)->img) }}" 
                                     class="w-full h-full object-cover">
                             @else
                                <div class="w-full h-full flex items-center justify-center text-choco text-xs text-center p-1">No Image</div>
                             @endif
                        </div>

                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900 text-sm sm:text-base truncate line-clamp-1">
                                {{ $firstItem->product_name }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $firstItem->quantity }} barang x IDR {{ number_format($firstItem->price, 0, ',', '.') }} 
                            </p>
                            
                            @if($order->items->count() > 1)
                                <p class="text-xs text-gray-400 mt-1">+{{ $order->items->count() - 1 }} produk lainnya</p>
                            @endif
                        </div>

                        <div class="hidden sm:block text-right border-l pl-4 border-gray-200 min-w-[120px]">
                            <p class="text-xs text-gray-500 mb-1">Total Belanja</p>
                            <p class="font-bold text-choco">IDR {{ number_format($order->grand_total, 0, ',', '.') }} </p>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-col sm:flex-row justify-between items-end sm:items-center gap-4">
                        <div class="sm:hidden w-full flex justify-between items-center">
                            <p class="text-sm text-gray-500">Total Belanja</p>
                            <p class="font-bold text-choco">IDR {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                        </div>

                        <div class="flex gap-2 w-full sm:w-auto justify-end">    
                            <a href="{{ route('shop') }}" class="bg-choco text-white font-bold text-sm px-6 py-2 rounded hover:bg-choco_light transition text-center">
                                Beli Lagi
                            </a>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection
