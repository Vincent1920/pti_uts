@extends('index')

@section('container-home')

<style>
    /* Style untuk Animasi Border pada Gambar */
    .draw-border::before,
    .draw-border::after {
        content: '';
        width: 0;
        height: 0;
        position: absolute;
        box-sizing: inherit;
        border: 3px solid transparent;
        transition: width 0.25s ease-out, height 0.25s ease-out;
    }

    .draw-border::before {
        top: -20px;
        left: -20px;
    }

    .draw-border::after {
        bottom: -20px;
        right: -20px;
    }

    .group:hover .draw-border::before,
    .group:hover .draw-border::after {
        width: calc(100% + 40px);
        height: calc(100% + 40px);
    }

    .group:hover .draw-border::before {
        border-top-color: #B87C4C;
        border-right-color: #e4a877;
        transition: width 0.25s ease-out, height 0.25s ease-out 0.25s;
    }

    .group:hover .draw-border::after {
        border-bottom-color: #eae0af;
        border-left-color: #F7ACA9;
        transition: border-color 0s ease-out 0.5s, width 0.25s ease-out 0.5s, height 0.25s ease-out 0.75s;
    }

    /* Style untuk Tombol 3D */
    .perspective-230 {
        perspective: 230px;
    }

    .rotate-x-90 {
        transform: rotateX(90deg);
        transform-origin: 50% 50% -20px;
    }

    .rotate-x-0 {
        transform: rotateX(0deg);
        transform-origin: 50% 50% -20px;
    }

    .rotate-x-neg-90 {
        transform: rotateX(-90deg);
        transform-origin: 50% 50% -20px;
    }
</style>

<!-- MAIN WRAPPER: Flex container untuk memisahkan Gambar (Kiri) dan Form (Kanan) -->
<div
    class="w-full h-screen bg-[#decdb5] flex flex-row justify-center items-center gap-[100px] overflow-hidden font-['Itim']">

    <!-- BAGIAN 1: KARTU VISUAL (GAMBAR & DESKRIPSI) -->
    <!-- Form tidak ada di sini lagi -->
    <div id="main" class="group relative w-[400px] h-[300px] flex items-center">

        <!-- Judul di atas kartu -->
        <p class="absolute -top-[50px] left-0 text-[24px] font-['Itim'] text-[#131313]">
            {{$barang->title}}
        </p>

        <!-- Border Animation Element -->
        <div class="draw-border absolute inset-0 pointer-events-none z-50"></div>

        <!-- Gambar Produk -->
        <div
            class="absolute z-20 w-full h-full bg-[#e1b882] rounded-[2rem] shadow-xl transition-all duration-500 ease-in-out flex items-center justify-center overflow-hidden group-hover:scale-[1.02]">
            <img id="image" src="{{ asset('images/' . $barang->img) }}" alt="{{ $barang->title }}"
                class="w-full h-full object-cover">
        </div>

        <!-- Deskripsi (Muncul saat hover, tapi form sudah tidak ada disini) -->
        <div
            class="absolute w-[90%] -bottom-[80px] left-[5%] opacity-0 transition-all duration-500 group-hover:opacity-100 group-hover:translate-y-[-20px] z-30 text-center bg-white/80 p-3 rounded-lg backdrop-blur-sm shadow-md">
            <p class="text-[1.1rem] text-[#1d1c1b]">
                {{$barang->deskripsi}}
            </p>
        </div>
    </div>


    <!-- BAGIAN 2: FORMULIR PEMBELIAN (DI LUAR / STATIS) -->
    <!-- Ini adalah kode form yang Anda minta dipisahkan -->
    <div class="w-[300px] bg-[#e1b882]/30 p-8 rounded-[2rem] border border-[#B87C4C] shadow-lg backdrop-blur-sm">

        <div class="mb-6 text-center">
            <h3 class="font-['Permanent_Marker'] text-[2.5rem] text-[#131313]">
                IDR {{$barang->harga}}
            </h3>
            <p class="text-sm text-[#575739]">Siap dikirim ke rumah Anda</p>
        </div>

        <form action="{{ route('cart.add') }}" method="POST" class="flex flex-col items-center gap-6">
            @csrf
            <input type="hidden" name="barang_id" value="{{ $barang->id }}">

            <!-- Input Jumlah -->
            <div class="w-full">
                <label class="block text-[18px] text-[#0f0f0e] mb-2 text-center">Jumlah Barang</label>
                
                <p class="text-center text-xs text-red-500 mb-2 font-sans">
                    Stok Tersisa: {{ $barang->jumlah_barang }}
                </p>

                <div class="flex justify-center">
                    <input type="number" name="quantity" min="1" max="{{ $barang->jumlah_barang }}" value="1" required
                        class="w-[80%] h-[45px] text-center text-xl bg-[#e2d8cf] border-2 border-transparent focus:border-[#B87C4C] rounded-lg outline-none transition-colors"
                        oninput="checkMax(this)"> 
                </div>
            </div>

            <!-- Tombol 3D Flip -->
            <div
                class="p-1 mt-4 rounded-xl bg-gradient-to-br from-[#575739]/20 to-[#e4a877]/20 backdrop-blur-sm border border-[#575739]/30 shadow-lg hover:shadow-[#e4a877]/40 transition-shadow duration-300">

                <button
                    class="relative w-[160px] h-[50px] bg-transparent border-none cursor-pointer perspective-230 group/btn">

                    <span class="absolute block w-full h-full inset-0 bg-gradient-to-r from-[#e4a877] to-[#d6955b] rounded-lg flex items-center justify-center 
                     text-white font-bold tracking-widest text-sm shadow-[inset_0_2px_4px_rgba(255,255,255,0.3)]
                     transition-all duration-500 ease-out 
                     rotate-x-90 group-hover/btn:rotate-x-0 group-hover/btn:shadow-[0_0_20px_rgba(228,168,119,0.6)]">
                        CHECK OUT! <i class="ml-2 fas fa-arrow-right"></i>
                    </span>

                    <span class="absolute block w-full h-full inset-0 bg-[#575739] rounded-lg flex items-center justify-center 
                     text-[#e2d8cf] font-bold tracking-widest text-xs uppercase border border-[#6e6e4a]
                     transition-all duration-500 ease-out 
                     rotate-x-0 group-hover/btn:rotate-x-neg-90 group-hover/btn:opacity-0 shadow-md">
                        Tambah Cart
                    </span>

                </button>
            </div>
        </form>

    </div>

</div>
<script>
    function checkMax(input) {
        const max = parseInt(input.max);
        const min = parseInt(input.min);
        
        if (parseInt(input.value) > max) {
            // Jika input melebihi max, kembalikan ke nilai max
            input.value = max;
            // Opsional: Tampilkan alert kecil
            // alert('Maksimal pembelian adalah ' + max + ' item'); 
        }
        if (parseInt(input.value) < min) {
            input.value = min;
        }
    }
</script>
@endsection