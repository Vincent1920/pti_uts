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

                            <span
                                class="relative text-4xl font-qwigley text-black group-hover:text-choco transition-colors duration-300">
                                Shop

                                <span
                                    class="absolute -bottom-2 left-0 w-full h-[2px] bg-choco transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-out origin-right group-hover:origin-left"></span>
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
                                        <img src="../img/navbar/dark_22.png"
                                            class="w-48 h-32 object-cover rounded-md shadow-sm" alt="Milk">
                                        <h2 class="font-rancho text-3xl text-gray-800 pt-2">Milk Magazine</h2>
                                    </a>

                                    <a href="{{ route('shop') }}"
                                        class="flex flex-col items-center text-center space-y-2 p-4 hover:bg-white rounded-lg transition group/item transform hover:-translate-y-1">
                                        <img src="../img/navbar/Chocolate.png"
                                            class="w-48 h-32 object-cover rounded-md shadow-sm" alt="Delivered">
                                        <h2 class="font-rancho text-3xl text-gray-800 pt-2">Delivered Nationwide</h2>
                                    </a>

                                    <a href="{{ route('shop') }}"
                                        class="flex flex-col items-center text-center space-y-2 p-4 hover:bg-white rounded-lg transition group/item transform hover:-translate-y-1">
                                        <img src="../img/navbar/Super_cookies.png"
                                            class="w-48 h-32 object-contain rounded-md" alt="Chocolate">
                                        <h2 class="font-rancho text-3xl text-gray-800 pt-2">Chocolate</h2>
                                    </a>

                                    <a href="{{ route('shop') }}"
                                        class="flex flex-col items-center text-center space-y-2 p-4 hover:bg-white rounded-lg transition group/item transform hover:-translate-y-1">
                                        <img src="../img/navbar/Tongan_vanilla.png"
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
                    <div id="dropdown-container" class="relative z-50">

                        <button id="dropdown-btn" onclick="toggleDropdown()"
                            class="text-3xl font-qwigley text-black hover:text-gold transition flex items-center gap-2 focus:outline-none">
                            {{ auth()->user()->username }}
                            <i id="dropdown-icon"
                                class="bi bi-chevron-down text-sm pt-1 transition-transform duration-300"></i>
                        </button>

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

                            <a href="{{ route('orders.index') }}"
                                class="block px-4 py-3 text-lg font-manuale text-gray-700 hover:bg-alice hover:text-choco transition flex items-center">
                                <i class="bi bi-receipt mr-3 text-gold"></i> Order List
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
    </nav>

    
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
