<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>ChocoScript</title>

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
                            '0%': {
                                transform: 'translateY(-10px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            },
                        },
                        // INI BAGIAN YANG DIPERBAIKI UNTUK 6 ITEM
                        scrollText: {
                            '0%, 15%': {
                                transform: 'translateY(0)'
                            }, // Item 1
                            '16%, 32%': {
                                transform: 'translateY(-50px)'
                            }, // Item 2
                            '33%, 49%': {
                                transform: 'translateY(-100px)'
                            }, // Item 3
                            '50%, 66%': {
                                transform: 'translateY(-150px)'
                            }, // Item 4
                            '67%, 83%': {
                                transform: 'translateY(-200px)'
                            }, // Item 5
                            '84%, 100%': {
                                transform: 'translateY(-250px)'
                            }, // Item 6
                        }
                    }
                }
            }
        }
    </script>
</head>


<body class="flex flex-col min-h-screen bg-cream text-choco">
    {{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> --}}

    <script src="js/nav.js"></script>

    {{-- <script src="js/index.js"></script> --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Redressed&display=swap');
    </style>
   
@include('navbar.nav')
<main class="flex-grow">
@yield('container-home')
   </main>

@include('footer.footer')

<script src="js/shop.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Jika Berhasil (Success)
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#E8D5B5',
                confirmButtonText: 'OK',
                color: '#48311B'
            });
        @endif

        // Jika Gagal (Error Session)
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#d33',
                confirmButtonText: 'Coba Lagi'
            });
        @endif

        // Jika Error Validasi (Input salah)
        @if($errors->any())
            Swal.fire({
                icon: 'warning',
                title: 'Periksa Inputan!',
                html: "{!! implode('<br>', $errors->all()) !!}", 
                confirmButtonColor: '#f6d59e',
                confirmButtonText: 'OK',
                color: '#48311B'
            });
        @endif
    </script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ticker = document.getElementById('ticker-content');
        const items = ticker.children;
        const itemCount = items.length;

        // KONFIGURASI
        const itemHeight = 48; // Sesuai h-12 (12 * 4px = 48px)
        const durationPerItem = 3; // Detik per item (bisa diubah)

        if (itemCount > 0) {
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

</body>

</html>