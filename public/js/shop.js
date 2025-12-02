
    // Pastikan script jalan setelah HTML selesai dimuat
    document.addEventListener('DOMContentLoaded', function () {
        
        // Ambil elemen berdasarkan ID
        const trigger = document.getElementById('dropdownTrigger');
        const menu = document.getElementById('dropdownMenu');
        const arrow = document.getElementById('arrowIcon');

        // Fungsi Toggle saat tombol diklik
        trigger.addEventListener('click', function (event) {
            // Mencegah event klik tembus ke window (agar tidak langsung menutup)
            event.stopPropagation(); 

            if (menu.classList.contains('hidden')) {
                // Buka Menu
                menu.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                // Tutup Menu
                menu.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        });

        // Tutup jika klik di mana saja di luar tombol dropdown
        window.addEventListener('click', function (e) {
            if (!trigger.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        });
    });
