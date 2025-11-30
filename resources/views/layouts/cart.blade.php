<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>ChocoScript - Cart</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Delius+Unicase:wght@400;700&family=Kotta+One&family=Lobster+Two:ital,wght@0,400;0,700;1,400;1,700&family=Macondo&family=Manuale:ital,wght@0,300..800;1,300..800&family=Margarine&family=Qwigley&family=Rancho&family=Redressed&family=Moulpali&family=Murecho&family=Montaga&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
                    margarine: ['Margarine', 'sans-serif'],
                    rancho: ['Rancho', 'cursive'],
                    montaga: ['Montaga', 'serif'],
                },
                animation: {
                    'slide-down': 'slideDown 0.3s ease-out forwards',
                    // Ubah durasi jadi 15s atau 20s agar tidak terlalu cepat karena itemnya banyak
                    'scroll-text': 'scrollText 20s cubic-bezier(0.4, 0, 0.2, 1) infinite', 
                },
                keyframes: {
                    slideDown: {
                        '0%': { transform: 'translateY(-10px)', opacity: '0' },
                        '100%': { transform: 'translateY(0)', opacity: '1' },
                    },
                    // INI BAGIAN YANG DIPERBAIKI UNTUK 6 ITEM
                    scrollText: {
                        '0%, 15%':   { transform: 'translateY(0)' },        // Item 1
                        '16%, 32%':  { transform: 'translateY(-50px)' },    // Item 2
                        '33%, 49%':  { transform: 'translateY(-100px)' },   // Item 3
                        '50%, 66%':  { transform: 'translateY(-150px)' },   // Item 4
                        '67%, 83%':  { transform: 'translateY(-200px)' },   // Item 5
                        '84%, 100%': { transform: 'translateY(-250px)' },   // Item 6
                    }
                }
            }
        }
    }
</script>
</head>

<body class="bg-white">

   <div class="bg-choco h-12 overflow-hidden relative w-full z-50">
    
    <div id="ticker-content" class="absolute w-full top-0 left-0">
        
        <div class="h-12 flex items-center justify-center text-cream font-montaga text-lg">
            Vegan Milk Chocolate 20%
        </div>
        
        <div class="h-12 flex items-center justify-center text-cream font-montaga text-lg">
            Milk Magazine 15%
        </div>
        
        <div class="h-12 flex items-center justify-center text-cream font-montaga text-lg">
            Milk Magazine 1%
        </div>
        
        <div class="h-12 flex items-center justify-center text-cream font-montaga text-lg">
            Milk Magazine 5%
        </div>

        <div class="h-12 flex items-center justify-center text-cream font-montaga text-lg">
            Diskon Spesial Hari Ini!
        </div>

        <div class="h-12 flex items-center justify-center text-cream font-montaga text-lg">
            Cherry Cake 30%
        </div>

        </div>
</div>

    <nav
        class="sticky top-0 z-40 bg-white/95 backdrop-blur-sm shadow-sm w-full transition-all duration-500 hover:bg-alice">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <div class="flex-shrink-0">
                    <a href="/" class="text-5xl font-qwigley text-black hover:text-choco transition">ChocoScript</a>

                </div>

                <div class="hidden md:flex space-x-8 items-center h-full">

                    <div class="group h-full flex items-center">

   <a href="{{ route('shop') }}" 
   class="h-full flex items-center px-4 transition cursor-pointer relative z-50">
   
   <span class="relative text-4xl font-qwigley text-black group-hover:text-choco transition-colors duration-300">
       Shop
       
       <span class="absolute -bottom-2 left-0 w-full h-[2px] bg-choco transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out origin-right group-hover:origin-left"></span>
   </span>
</a>
                        <div class="fixed left-0 top-20 w-full bg-alice shadow-xl overflow-hidden 
                                h-0 group-hover:h-auto 
                                transition-all duration-300 ease-in-out 
                                border-t-2 border-choco 
                                opacity-0 group-hover:opacity-100 
                                invisible group-hover:visible 
                                z-40">

                            <div class="max-w-7xl mx-auto p-8">
                                <div class="grid grid-cols-4 gap-8">

                                    <a href="{{ route('shop') }}"
                                        class="flex flex-col items-center text-center space-y-2 p-7 hover:bg-white rounded-lg transition group/item transform hover:-translate-y-1">
                                        <img src="../img/brand/Tongan_vanilla.png"
                                            class="w-48 h-32 object-cover rounded-md shadow-sm" alt="Milk">
                                        <h2 class="font-rancho text-3xl text-gray-800 pt-2">Milk Magazine</h2>
                                    </a>

                                    <a href="{{ route('shop') }}"
                                        class="flex flex-col items-center text-center space-y-2 p-4 hover:bg-white rounded-lg transition group/item transform hover:-translate-y-1">
                                        <img src="../img/brand/Tongan_vanilla.png"
                                            class="w-48 h-32 object-cover rounded-md shadow-sm" alt="Delivered">
                                        <h2 class="font-rancho text-3xl text-gray-800 pt-2">Delivered Nationwide</h2>
                                    </a>

                                    <a href="{{ route('shop') }}"
                                        class="flex flex-col items-center text-center space-y-2 p-4 hover:bg-white rounded-lg transition group/item transform hover:-translate-y-1">
                                        <img src="../img/brand/dark_22_piece-removebg-preview.png"
                                            class="w-48 h-32 object-contain rounded-md" alt="Chocolate">
                                        <h2 class="font-rancho text-3xl text-gray-800 pt-2">Chocolate</h2>
                                    </a>

                                    <a href="{{ route('shop') }}"
                                        class="flex flex-col items-center text-center space-y-2 p-4 hover:bg-white rounded-lg transition group/item transform hover:-translate-y-1">
                                        <img src="../img/brand/Chocolate.png"
                                            class="w-48 h-32 object-cover rounded-md shadow-sm" alt="Dark Choco">
                                        <h2 class="font-rancho text-3xl text-gray-800 pt-2">Dark Choco</h2>
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex items-center space-x-6">
                    @auth
                    <!-- Tambahkan ID 'dropdown-container' untuk referensi JS -->
                    <div id="dropdown-container" class="relative z-50">

                        <!-- Tambahkan ID 'dropdown-btn' dan event onclick -->
                        <button id="dropdown-btn" onclick="toggleDropdown()"
                            class="text-3xl font-qwigley text-black hover:text-gold transition flex items-center gap-2 focus:outline-none">
                            {{ auth()->user()->username }}

                            <!-- ID 'dropdown-icon' untuk animasi panah -->
                            <i id="dropdown-icon"
                                class="bi bi-chevron-down text-sm pt-1 transition-transform duration-300"></i>
                        </button>

                        <!-- Hapus 'group-hover:block', biarkan default 'hidden' -->
                        <!-- Tambahkan ID 'dropdown-menu' -->
                        <div id="dropdown-menu"
                            class="absolute right-0 mt-2 w-48 bg-white shadow-xl rounded-md overflow-hidden hidden border border-gray-100 origin-top-right">

                            @if (auth()->user()->role === 'admin')
                            <a href="/admin"
                                class="block px-4 py-3 text-lg font-manuale text-gray-700 hover:bg-alice hover:text-choco transition flex items-center">
                                <i class="bi bi-person-lock mr-3 text-gold"></i> Admin Panel
                            </a>
                            @endif

                            <a href="{{ route('cart') }}"
                                class="block px-4 py-3 text-lg font-manuale text-gray-700 hover:bg-alice hover:text-choco transition flex items-center">
                                <i class="bi bi-basket2-fill mr-3 text-gold"></i> Keranjang
                            </a>

                            <a href="{{ route('logout') }}"
                                class="block px-4 py-3 text-lg font-manuale text-gray-700 hover:bg-red-50 hover:text-red-600 transition border-t border-gray-100 flex items-center">
                                <i class="bi bi-box-arrow-right mr-3 text-red-400"></i> Logout
                            </a>
                        </div>
                    </div>
                    @else
                    <a href="/login" class="text-4xl font-qwigley text-black hover:text-gold transition">Login</a>
                    @endauth
                </div>
            </div>
    </nav>

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
            <div
                class="flex flex-col md:flex-row items-center p-6 border-b border-gray-100 hover:bg-gray-50 transition">

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
                            <button type="submit" name="action" value="increase"
                                class="px-3 py-1 text-gray-600 hover:bg-gray-100 hover:text-choco transition">+</button>
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

                <button
                    class="w-full mt-6 bg-choco hover:bg-choco_light text-white font-bold py-3 px-4 rounded shadow-md transition transform hover:-translate-y-0.5">
                    Checkout Sekarang
                </button>
            </div>
        </div>
        @endif

    </div>
    <footer class="bg-choco text-cream py-8 mt-12">
        <div class="text-center font-qwigley text-6xl">ChocoScript</div>
        <div class="text-center mt-4 text-sm font-manuale opacity-70">&copy; {{ date('Y') }} All Rights Reserved.</div>
    </footer>

</body>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ticker = document.getElementById('ticker-content');
        const items = ticker.children;
        const itemCount = items.length;
        
        // KONFIGURASI
        const itemHeight = 48; // Sesuai h-12 (12 * 4px = 48px)
        const durationPerItem = 3; // Detik per item (bisa diubah)
        
        if(itemCount > 0) {
            // 1. Clone item pertama dan taruh di paling bawah agar looping mulus (Infinity Loop)
            const firstItemClone = items[0].cloneNode(true);
            ticker.appendChild(firstItemClone);

            // 2. Hitung total durasi animasi
            const totalCount = itemCount + 1; // +1 karena ada clone
            const totalDuration = itemCount * durationPerItem;

            // 3. Buat Keyframes CSS secara Dinamis
            let keyframes = `@keyframes dynamicScroll {`;
            
            const percentageStep = 100 / itemCount; 
            
            for (let i = 0; i < itemCount; i++) {
                const startPercent = i * percentageStep;
                const endPercent = startPercent + (percentageStep * 0.85); // 85% waktu diam
                const nextPercent = (i + 1) * percentageStep; // 15% waktu jalan

                const position = -(i * itemHeight);

                // Tahap Diam (Pause)
                keyframes += `
                    ${startPercent}% { transform: translateY(${position}px); }
                    ${endPercent}% { transform: translateY(${position}px); }
                `;
            }

            // Tahap Terakhir (Geser ke Clone untuk efek mulus)
            keyframes += `100% { transform: translateY(-${itemCount * itemHeight}px); }`;
            keyframes += `}`;

            // 4. Inject CSS ke dalam halaman
            const styleSheet = document.createElement("style");
            styleSheet.innerText = keyframes;
            document.head.appendChild(styleSheet);

            // 5. Terapkan animasi ke elemen
            ticker.style.animation = `dynamicScroll ${totalDuration}s cubic-bezier(0.4, 0, 0.2, 1) infinite`;
        }
    });
</script>

<!-- Script Khusus untuk Dropdown ini -->
<script>
    function toggleDropdown() {
        const menu = document.getElementById('dropdown-menu');
        const icon = document.getElementById('dropdown-icon');

        // Toggle visibility menu
        if (menu.classList.contains('hidden')) {
            // Buka Menu
            menu.classList.remove('hidden');
            menu.classList.add('animate-slide-down'); // Efek slide
            icon.classList.add('rotate-180'); // Putar panah ke atas
        } else {
            // Tutup Menu
            menu.classList.add('hidden');
            menu.classList.remove('animate-slide-down');
            icon.classList.remove('rotate-180'); // Balikkan panah
        }
    }

    // Menutup dropdown jika user klik di luar area dropdown
    window.addEventListener('click', function (e) {
        const container = document.getElementById('dropdown-container');
        const menu = document.getElementById('dropdown-menu');
        const icon = document.getElementById('dropdown-icon');

        if (!container.contains(e.target)) {
            menu.classList.add('hidden');
            menu.classList.remove('animate-slide-down');
            icon.classList.remove('rotate-180');
        }
    });
</script>

</html>