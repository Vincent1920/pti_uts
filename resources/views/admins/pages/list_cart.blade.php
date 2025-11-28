@extends('admins.index')

@section('admin')

<div class="max-w-4xl mx-auto mt-6">
    
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-choco">Daftar Keranjang User</h1>
        <p class="text-sm text-gray-500">Melihat siapa saja yang sedang menyimpan barang di keranjang.</p>
    </div>

    @if($item_cart->isEmpty())
        <div class="bg-yellow-50 text-yellow-800 p-4 rounded-lg border border-yellow-200">
            Belum ada user yang memasukkan barang ke keranjang.
        </div>
    @else
    
    <div id="accordion-collapse" data-accordion="collapse">
        
        @foreach ($item_cart as $user)
            <div class="mb-4 border border-gray-200 rounded-xl shadow-sm bg-white overflow-hidden">
                
                <h2 id="accordion-heading-{{ $user->id }}">
                    <button type="button" 
                            class="flex items-center justify-between w-full p-5 font-medium text-left text-gray-800 bg-white hover:bg-gray-50 focus:ring-4 focus:ring-cream/50 transition" 
                            data-accordion-target="#accordion-body-{{ $user->id }}" 
                            aria-expanded="false" 
                            aria-controls="accordion-body-{{ $user->id }}"
                            onclick="toggleAccordion('{{ $user->id }}')">
                        
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-choco text-cream flex items-center justify-center font-bold text-lg uppercase">
                                {{ substr($user->username, 0, 1) }}
                            </div>
                            <div>
                                <span class="block text-lg font-bold text-choco">{{ $user->username }}</span>
                                <span class="text-xs text-gray-500">{{ $user->cartItems->count() }} Items di keranjang</span>
                            </div>
                        </div>

                        <i id="icon-{{ $user->id }}" class="bi bi-chevron-down transform transition-transform duration-300"></i>
                    </button>
                </h2>

                <div id="accordion-body-{{ $user->id }}" class="hidden transition-all duration-300 border-t border-gray-100">
                    <div class="p-5 bg-gray-50">
                        
                        @php
                            $grandTotal = 0;
                        @endphp

                        <div class="space-y-4">
                            @foreach ($user->cartItems as $item)
                                @php
                                    $subtotal = $item->barang->harga * $item->quantity;
                                    $grandTotal += $subtotal;
                                @endphp

                                <div class="flex flex-col sm:flex-row bg-white p-4 rounded-lg border border-gray-200 shadow-sm gap-4">
                                    <div class="w-full sm:w-24 h-24 flex-shrink-0">
                                        <img class="w-full h-full object-cover rounded-md" 
                                             src="{{ asset('images/' . $item->barang->img) }}" 
                                             alt="{{ $item->barang->title }}">
                                    </div>
                                    
                                    <div class="flex-1">
                                        <h3 class="text-md font-bold text-gray-800">{{ $item->barang->title }}</h3>
                                        <p class="text-sm text-gray-500 mb-2">Harga: Rp {{ number_format($item->barang->harga, 0, ',', '.') }}</p>
                                        
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="bg-cream text-choco text-xs px-2 py-1 rounded font-bold">
                                                Qty: {{ $item->quantity }}
                                            </span>
                                            <span class="font-bold text-choco">
                                                Subtotal: Rp {{ number_format($subtotal, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @php
                            $discountPercent = 0.15; // 15%
                            $discountAmount = $grandTotal * $discountPercent;
                            $finalPrice = $grandTotal - $discountAmount;
                        @endphp

                        <div class="mt-6 border-t border-gray-200 pt-4 flex flex-col items-end">
                            <div class="w-full sm:w-1/2 space-y-2">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Total Awal:</span>
                                    <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-green-600">
                                    <span>Diskon (15%):</span>
                                    <span>- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                                </div>
                                <div class="border-t border-gray-300 my-2"></div>
                                <div class="flex justify-between text-lg font-bold text-choco">
                                    <span>Total Akhir:</span>
                                    <span>Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
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
    function toggleAccordion(userId) {
        // Ambil elemen body dan icon berdasarkan ID user yang unik
        const body = document.getElementById('accordion-body-' + userId);
        const icon = document.getElementById('icon-' + userId);

        // Toggle class hidden
        if (body.classList.contains('hidden')) {
            body.classList.remove('hidden');
            icon.classList.add('rotate-180'); // Putar panah ke atas
        } else {
            body.classList.add('hidden');
            icon.classList.remove('rotate-180'); // Putar panah balik
        }
    }
</script>

@endsection