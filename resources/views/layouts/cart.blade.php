@extends('index')
@section('container-home')


<div class="max-w-6xl mx-auto px-4 py-10 min-h-screen">

    <h1 class="text-4xl font-kotta text-center text-gray-800 mb-2">Keranjang Belanja</h1>
    <p class="text-center text-gray-500 font-manuale mb-8">Dapatkan pengiriman gratis untuk pesanan Anda!</p>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
        <p class="font-bold">Berhasil!</p>
        <p>{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
        <p class="font-bold">Error!</p>
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden mb-8">

        @if($cartItems->isEmpty())
        <div class="p-10 text-center text-gray-500 font-manuale text-xl">
            <i class="bi bi-cart-x text-6xl mb-4 block text-gray-300"></i>
            Keranjang Anda masih kosong.
        </div>
        @else
        @foreach($cartItems as $item)
        <div class="flex flex-col md:flex-row items-center p-6 border-b border-gray-100 hover:bg-gray-50 transition">

            <div class="w-full md:w-32 h-32 flex-shrink-0 bg-gray-100 rounded-md overflow-hidden mb-4 md:mb-0">
                <img src="{{ asset('images/' . $item->barang->img) }}" alt="{{ $item->barang->title }}"
                    class="w-full h-full object-cover">
            </div>

            <div class="flex-1 md:ml-6 text-center md:text-left w-full">
                <h3 class="text-2xl font-kotta text-gray-900">{{ $item->barang->title }}</h3>
                <p class="text-lg font-manuale text-gray-600">IDR
                    {{ number_format($item->barang->harga, 0, ',', '.') }}</p>
                <p class="text-sm font-margarine text-gray-400 mt-1 uppercase">1 Buah /
                    {{ $item->barang->berat_barang }}g</p>
            </div>

            <div
                class="flex flex-col items-center justify-center space-y-3 md:space-y-0 md:space-x-6 md:flex-row mt-4 md:mt-0">

                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center">
                    @csrf
                    @method('PATCH')
                    <div class="flex items-center border border-gray-300 rounded-md bg-white">
                        <button type="submit" name="action" value="decrease"
                            class="px-3 py-1 text-gray-600 hover:bg-gray-100 hover:text-choco transition">-</button>
                        <input type="text" name="quantity" value="{{ $item->quantity }}"
                            class="w-12 text-center border-x border-gray-300 py-1 text-gray-800 font-bold focus:outline-none"
                            readonly>
                        <!-- Cek Stok di View -->
                        <button type="submit" name="action" value="increase"
                            class="px-3 py-1 transition 
                                {{ $item->quantity >= $item->barang->jumlah_barang ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-100 hover:text-choco' }}"
                            {{ $item->quantity >= $item->barang->jumlah_barang ? 'disabled' : '' }}>
                            +
                        </button>
                    </div>
                </form>

                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-red-500 hover:text-red-700 font-bold text-sm uppercase tracking-wider flex items-center transition group">
                        <i class="bi bi-trash mr-1 group-hover:scale-110 transition"></i> Remove
                    </button>
                </form>
            </div>

            <div class="w-full md:w-32 text-center md:text-right mt-4 md:mt-0">
                @php
                $subtotalItem = $item->barang->harga * $item->quantity;
                @endphp
                <p class="text-xl font-bold text-choco">
                    {{ number_format($subtotalItem, 0, ',', '.') }}
                </p>
            </div>

        </div>
        @endforeach
        @endif
    </div>

    @if(!$cartItems->isEmpty())
    <div class="flex justify-end mt-8">
        <div class="w-full md:w-1/2 lg:w-1/3 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-xl font-kotta border-b border-gray-200 pb-2 mb-4">Ringkasan Pesanan</h3>

            <div class="flex justify-between items-center mb-2 font-manuale text-gray-600">
                <span>Total Harga</span>
                <span>IDR {{ number_format($grandTotal, 0, ',', '.') }} </span>
            </div>

            <div class="flex justify-between items-center mb-4 font-manuale">
                <span class="{{ $discountAmount > 0 ? 'text-green-600 font-bold' : 'text-gray-400' }}">
                    Diskon
                    @if($discountAmount > 0)
                    <span class="text-xs bg-green-100 px-2 py-0.5 rounded ml-1">
                        {{ $namaDiskon }} {{ $persenDiskon }}%
                    </span>
                    @endif
                </span>
                <span class="{{ $discountAmount > 0 ? 'text-green-600' : 'text-gray-400' }}">
                    - IDR {{ number_format($discountAmount, 0, ',', '.') }}
                </span>
            </div>

            <div class="border-t border-gray-300 pt-4 flex justify-between items-center">
                <span class="text-2xl font-kotta text-gray-800">Total Bayar</span>
                <span class="text-2xl font-bold text-choco">
                    {{ number_format($finalPrice, 0, ',', '.') }}
                </span>
            </div>

            <a href="{{route("checkout.index")}}">

                <button
                    class="w-full mt-6 bg-choco hover:bg-choco_light text-white font-bold py-3 px-4 rounded shadow-md transition transform hover:-translate-y-0.5">
                    Checkout Sekarang
                </button>
            </a>
        </div>
    </div>
    @endif

</div>

@endsection