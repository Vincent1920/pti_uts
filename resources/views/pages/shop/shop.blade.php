@extends('index')

@section('container-home')

<style>
    /* ANIMASI */
    @keyframes openclose {

        0%,
        100% {
            width: 0;
        }

        10%,
        90% {
            width: 100%;
        }
    }

    .animate-openclose {
        animation: openclose 8s ease-in-out infinite;
    }

    @keyframes slideInLeft {
        0% {
            transform: translateX(-50px);
            opacity: 0;
        }

        100% {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .animate-slideInLeft {
        animation: slideInLeft 0.8s ease-out forwards;
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .animate-slideUp {
        animation: slideUp 0.8s forwards;
    }
</style>

<div class="relative w-full h-[350px] bg-gray-800 flex justify-center items-center overflow-hidden">
    <div class="absolute inset-0 bg-[url('../../img/shop/home.webp')] bg-cover bg-center opacity-60"></div>
    <div class="relative z-10 p-6 rounded-xl backdrop-blur-sm border border-white/10">
        <h2
            class="text-white font-['Ribeye_Marrow',serif] text-5xl animate-pulse text-center drop-shadow-lg tracking-wide">
            Seluruh ChocoScript
        </h2>
    </div>
</div>

<div
    class="sticky top-0 w-full h-[80px] bg-white shadow-md z-50 flex justify-center items-center border-b-4 border-[#F8DEC3]">
    <div class="relative w-full max-w-3xl h-full flex items-center justify-center overflow-hidden">
        <h1
            class="text-[#a44b03] font-['Rochester',cursive] text-[3.5rem] font-thin uppercase whitespace-nowrap relative">
            <div
                class="animate-openclose text-[#DD751F] font-black block overflow-hidden whitespace-nowrap mx-auto px-4 text-center">
                ChocoScript
            </div>
        </h1>
    </div>
</div>

<div class="container mx-auto px-4 py-12 max-w-7xl">

    <div class="flex flex-col lg:flex-row gap-10">
        {{-- Kategori --}}
        <div class="w-full lg:w-1/4 animate-slideInLeft z-40">
            <div class="sticky top-[100px]">

                <div
                    class="relative w-full bg-white rounded-2xl shadow-lg border border-[#e4a877]/20 transition-all duration-300 hover:shadow-xl">

                    <div id="dropdownTrigger"
                        class="p-5 flex items-center justify-between bg-white rounded-2xl relative z-10 cursor-pointer">
                        <p class="text-[#5c4033] text-2xl font-['Quintessential',cursive]">
                            <b>Kategori</b>
                        </p>
                        <span id="arrowIcon" class="text-[#DD751F] text-lg transform transition-transform duration-300">
                            ▼
                        </span>
                    </div>

                    <div id="dropdownMenu"
                        class="hidden absolute top-full left-0 w-full bg-white/95 backdrop-blur-md rounded-b-2xl border-x border-b border-[#e4a877]/20 shadow-2xl overflow-hidden mt-1 animate-slideUp">
                        <ul class="flex flex-col font-['Spirax',cursive] text-lg py-2">
                            @forelse($kategoris as $kategori)
                            <li>
                                <a class="block w-full px-6 py-3 text-gray-600 hover:bg-[#F8DEC3] hover:text-[#a44b03] hover:pl-8 transition-all duration-300 border-l-4 border-transparent hover:border-[#a44b03]"
                                    href="{{ route('kategori.show', $kategori->id) }}">
                                    {{ $kategori->nama_kategori }}
                                </a>
                            </li>
                            @empty
                            <li class="px-6 py-4 text-gray-400 text-sm italic">Belum ada kategori</li>
                            @endforelse
                        </ul>
                    </div>

                </div>
            </div>
        </div>


        {{-- barang --}}
        <div class="w-full lg:w-3/4 z-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">

                @forelse ( $barangs as $barang )
                @php
                // Pastikan dikonversi ke integer agar aman karena di database tipenya string
                $stokSaatIni = (int) $barang->jumlah_barang;
                $isOutOfStock = $stokSaatIni <= 0; @endphp <div
                    class="animate-slideUp group relative flex flex-col items-center h-[420px]">

                    <div
                        class="z-30 relative -mb-4 w-[60%] bg-[#fffcfc] border border-gray-100 shadow-md rounded-b-xl py-2 px-4 text-center transform transition-transform duration-300 group-hover:-translate-y-2">
                        <p class="font-['Shalimar',cursive] text-[#a17307] text-2xl font-bold">
                             IDR {{ number_format($barang->harga, 0, ',', '.') }}
                        </p>
                    </div>

                    <div
                        class="w-full flex-1 bg-[#F8DEC3] rounded-[2rem] border-4 border-white shadow-lg overflow-hidden relative z-20 group-hover:shadow-2xl transition-all duration-500 group-hover:bg-[#D19F9C]">

                        <a href="{{ $isOutOfStock ? '#' : route('card.show', $barang->id) }}"
                            class="block w-full h-full {{ $isOutOfStock ? 'cursor-not-allowed' : '' }}">

                            <img class="w-full h-full object-cover transition-all duration-700 
                                {{ $isOutOfStock ? 'grayscale opacity-50' : 'opacity-90 group-hover:opacity-100 group-hover:scale-110' }}"
                                src="{{ asset('images/' . $barang->img) }}"
                                onerror="this.src='https://via.placeholder.com/300x400?text=No+Image'"
                                alt="{{ $barang->title }}">
                        </a>

                        @if($isOutOfStock)
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-30">
                            <div class="relative w-32 h-32 opacity-80">
                                <div
                                    class="absolute inset-0 bg-red-600 h-2 w-full top-1/2 -translate-y-1/2 rotate-45 rounded-full shadow-lg">
                                </div>
                                <div
                                    class="absolute inset-0 bg-red-600 w-2 h-full left-1/2 -translate-x-1/2 rotate-45 rounded-full shadow-lg">
                                </div>
                            </div>
                            <span
                                class="absolute mt-10 bg-red-600 text-white px-4 py-1 rounded-full font-bold text-lg shadow-xl border-2 border-white transform -rotate-12">
                                HABIS
                            </span>
                        </div>
                        @else
                        <div
                            class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center pointer-events-none">
                            <span
                                class="bg-white/90 text-[#a44b03] px-4 py-2 rounded-full font-bold text-sm shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                Lihat Detail
                            </span>
                        </div>
                        @endif

                    </div>

                    <div class="mt-4 text-center z-10">
                        <h3
                            class="font-['Ruge_Boogie',cursive] text-4xl transition-colors duration-300 drop-shadow-sm 
                           {{ $isOutOfStock ? 'text-gray-400 line-through decoration-red-500' : 'text-[#5c4033] group-hover:text-[#a44b03]' }}">
                            {{$barang->title}}
                        </h3>

                        @if(!$isOutOfStock)
                        <p class="text-xs text-gray-500 mt-1 font-sans">Stok: {{ $barang->jumlah_barang }}</p>
                        @endif
                    </div>

            </div>
            @empty
            <div class="col-span-full py-20 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <p class="text-gray-400 font-sans text-lg">Produk belum tersedia saat ini.</p>
            </div>
            @endforelse

        </div>
    </div>

</div>

@endsection