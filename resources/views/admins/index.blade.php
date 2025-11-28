<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>ChocoScript</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        choco: '#48311B',
                        cream: '#E8D5B5',
                        cream_hover: '#f6d59e',
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="../css/admin/list.css">
    <link rel="stylesheet" href="css/admin/home.css">
    <link rel="stylesheet" href="css/admin/dashboard.css">
    <link rel="stylesheet" href="../../css/admin/nav.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-gray-50">

    <header class="fixed top-0 z-50 w-full bg-gray-900 text-white shadow-md flex items-center justify-between px-4 py-3">
        <div class="flex items-center">
            <a class="text-xl font-bold tracking-wider mr-4" href="{{route('home')}}"> ChocoScript </a>
            
            <button id="mobile-menu-btn" class="md:hidden text-gray-300 hover:text-white focus:outline-none">
                <i class="bi bi-list text-2xl"></i>
            </button>
        </div>

        <div class="flex items-center space-x-4">
            <a class="px-4 py-2 text-sm bg-gray-800 hover:bg-gray-700 rounded transition duration-200" href="{{route('logout')}}">Logout</a>
        </div>
    </header>

    <div class="flex h-screen pt-16"> 
       @include('admins.navbar.navbar')
        <main class="flex-1 w-full overflow-y-auto bg-gray-50 p-6">
            
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200">
                <h1 class="text-2xl font-semibold text-choco">Admin Area</h1>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm min-h-[500px]">
                @yield('admin')
            </div>

        </main>
    </div>

    <script src="../admins/pos.js"></script>
    <script src="js/index.js"></script>
    <script src="js/admins/dashboard.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.2/dist/chart.umd.js" integrity="sha384-eI7PSr3L1XLISH8JdDII5YN/njoSsxfbrkCTnJrzXt+ENP5MOVBxD+l6sEG4zoLp" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebarMenu');

        btn.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
            sidebar.classList.toggle('absolute'); // Agar melayang di mobile
            sidebar.classList.toggle('h-screen');
        });
    </script>

    <script>
        // Jika Berhasil (Success)
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#E8D5B5', // Warna Cream
                confirmButtonText: 'OK',
                color: '#48311B' // Warna Choco
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
</body>
</html>