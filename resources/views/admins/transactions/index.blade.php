@extends('admins.index')

@section('admin')

<div class="max-w-5xl mx-auto mt-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-choco">Daftar Transaksi Masuk</h1>
        <p class="text-sm text-gray-500">Kelola pesanan Midtrans & COD, serta update status pengiriman.</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-4 border border-green-200">
        {{ session('success') }}
    </div>
    @endif

    @if($transactions->isEmpty())
        <div class="bg-yellow-50 text-yellow-800 p-4 rounded-lg border border-yellow-200">
            Belum ada transaksi masuk.
        </div>
    @else
    
    <div id="accordion-collapse" data-accordion="collapse">
        @foreach ($transactions as $trans)
            <div class="mb-4 border border-gray-200 rounded-xl shadow-sm bg-white overflow-hidden">
                
                <h2 id="accordion-heading-{{ $trans->id }}">
                    <button type="button" 
                            class="flex items-center justify-between w-full p-5 font-medium text-left text-gray-800 bg-white hover:bg-gray-50 transition group" 
                            onclick="toggleAccordion('{{ $trans->id }}')">
                        
                        <div class="flex items-center gap-4 flex-1">
                            @php
                                // Logika Warna berdasarkan Status Admin
                                $colors = [
                                    'pending' => 'bg-orange-100 text-orange-700',
                                    'processing' => 'bg-blue-100 text-blue-700',
                                    'shipping' => 'bg-purple-100 text-purple-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                ];
                                $statusAdmin = $trans->status_dari_admin ?? 'pending';
                                $colorClass = $colors[$statusAdmin] ?? 'bg-gray-100';
                            @endphp

                            <div class="flex flex-col items-center justify-center w-24">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $colorClass }}">
                                    {{ $statusAdmin }}
                                </span>
                                <span class="text-[10px] text-gray-400 mt-1">{{ $trans->created_at->format('d M H:i') }}</span>
                            </div>

                            <div class="border-l pl-4 border-gray-200">
                                <div class="flex items-center gap-2">
                                    <span class="block text-lg font-bold text-choco">{{ $trans->invoice_code }}</span>
                                    @if($trans->status == 'success' || $trans->status == 'settlement')
                                        <span class="text-[10px] bg-green-500 text-white px-2 py-0.5 rounded-full">LUNAS</span>
                                    @endif
                                </div>
                                <span class="text-sm text-gray-500">
                                    <i class="bi bi-person-fill"></i> {{ $trans->name }} 
                                    <span class="ml-1 italic">({{ strtoupper($trans->payment_method) }})</span>
                                </span>
                            </div>

                            <div class="ml-auto text-right pr-4 hidden sm:block">
                                <span class="block text-sm text-gray-500">Total</span>
                                <span class="font-bold text-choco text-lg">Rp {{ number_format($trans->grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <i id="icon-{{ $trans->id }}" class="bi bi-chevron-down transform transition-transform duration-300 text-gray-400"></i>
                    </button>
                </h2>

                <div id="accordion-body-{{ $trans->id }}" class="hidden transition-all duration-300 border-t border-gray-100">
                    <div class="p-5 bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <div class="md:col-span-2 space-y-3">
                                <h3 class="font-bold text-gray-700 mb-2 border-b pb-1">Detail Pesanan</h3>
                                @foreach ($trans->items as $item)
                                <div class="flex items-center bg-white p-3 rounded border border-gray-200 gap-3">
                                    <div class="w-12 h-12 flex-shrink-0 bg-gray-100 rounded overflow-hidden">
                                        @if($item->barang && $item->barang->img)
                                            <img src="{{ asset('images/' . $item->barang->img) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">No Img</div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm font-bold text-gray-800">{{ $item->product_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                    </div>
                                    <div class="font-bold text-sm text-gray-700">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                                @endforeach

                                <div class="mt-4 bg-white p-4 rounded border border-gray-200 text-sm">
                                    <p class="font-bold text-gray-700 mb-1">Informasi Pengiriman:</p>
                                    <p class="text-gray-600">{{ $trans->address }}</p>
                                    <p class="text-gray-600">{{ $trans->city }}, {{ $trans->postal_code }}</p>
                                    <p class="text-gray-600">No. HP: <span class="font-semibold">{{ $trans->phone }}</span></p>
                                </div>
                            </div>

                            <div class="md:col-span-1 space-y-4">
                                <div class="bg-white p-4 rounded border border-gray-200">
                                    <h3 class="font-bold text-gray-700 mb-2 text-sm text-center border-b pb-2">Status Pembayaran</h3>
                                    <div class="text-center py-2">
                                        @if($trans->payment_method == 'cod')
                                            <span class="text-orange-600 font-bold uppercase tracking-wider">CASH ON DELIVERY</span>
                                        @else
                                            <p class="text-[10px] text-gray-400 uppercase">Midtrans Status:</p>
                                            <span class="text-sm font-mono font-bold {{ $trans->status == 'success' || $trans->status == 'settlement' ? 'text-green-600' : 'text-orange-500' }}">
                                                {{ strtoupper($trans->status) }}
                                            </span>
                                            <p class="text-[10px] text-gray-400 mt-2">Order ID: {{ $trans->midtrans_order_id ?? '-' }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="bg-white p-4 rounded border-2 border-choco/20 shadow-sm">
                                    <h3 class="font-bold text-choco mb-3 text-sm italic">Update Progres Barang</h3>
                                    <form action="{{ route('admin.transactions.updateStatus', $trans->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <select name="status_dari_admin" class="w-full border border-gray-300 rounded p-2 text-sm mb-3 focus:ring-1 focus:ring-choco focus:outline-none">
                                            <option value="pending" {{ $trans->status_dari_admin == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                                            <option value="processing" {{ $trans->status_dari_admin == 'processing' ? 'selected' : '' }}>Diproses (Packing)</option>
                                            <option value="shipping" {{ $trans->status_dari_admin == 'shipping' ? 'selected' : '' }}>Dikirim (Kurir)</option>
                                            <option value="completed" {{ $trans->status_dari_admin == 'completed' ? 'selected' : '' }}>Selesai</option>
                                            <option value="cancelled" {{ $trans->status_dari_admin == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>

                                        <button type="submit" class="w-full bg-choco hover:bg-choco_light text-white text-sm font-bold py-2 rounded transition shadow-md">
                                            Update Progres
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>

<script>
    function toggleAccordion(id) {
        const body = document.getElementById('accordion-body-' + id);
        const icon = document.getElementById('icon-' + id);
        if (body.classList.contains('hidden')) {
            body.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            body.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }
</script>

@endsection