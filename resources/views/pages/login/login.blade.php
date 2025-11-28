<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Delius+Unicase&family=Rancho&family=Qwigley&family=Poppins:wght@100;300;400;500;600;700;800&family=Montserrat:wght@400;800&display=swap" rel="stylesheet">
    
    <link rel="shortcut icon" href="img/b734317dc97d8d22aec2f5b29e0e8672-removebg-preview.png">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    
    <title>ChocoScript</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                        'montserrat': ['Montserrat', 'sans-serif'],
                        'rancho': ['Rancho', 'cursive'],
                        'delius': ['Delius Unicase', 'cursive'],
                        'qwigley': ['Qwigley', 'cursive'],
                    },
                    colors: {
                        'choco-bg': '#FFE9DA',      // Background body
                        'choco-dark': '#48311B',    // Warna text gelap
                        'choco-btn': '#E8D5B5',     // Warna tombol
                        'choco-border': '#f6d59e',  // Border tombol
                        'choco-accent': '#d6a609',  // Aksen overlay
                    },
                    keyframes: {
                        slideDown: {
                            '0%': { transform: 'translateY(-150%)' },
                            '100%': { transform: 'translateY(0%)' },
                        },
                        show: {
                            '0%, 49.99%': { opacity: '0', zIndex: '1' },
                            '50%, 100%': { opacity: '1', zIndex: '5' },
                        }
                    },
                    animation: {
                        'slide-down': 'slideDown 3s ease-out forwards',
                        'show': 'show 0.6s',
                        'spin-slow': 'spin 2s linear infinite',
                    }
                }
            }
        }
    </script>

    <style type="text/tailwindcss">
        @layer utilities {
            /* Logika Transformasi Panel saat class .right-panel-active aktif */
            .container.right-panel-active .sign-in-container {
                @apply translate-x-full;
            }
            
            .container.right-panel-active .sign-up-container {
                @apply translate-x-full opacity-100 z-[5] animate-show;
            }

            .container.right-panel-active .overlay-container {
                @apply -translate-x-full;
            }

            .container.right-panel-active .overlay {
                @apply translate-x-1/2;
            }

            .container.right-panel-active .overlay-left {
                @apply translate-x-0;
            }

            .container.right-panel-active .overlay-right {
                @apply translate-x-[20%];
            }
            
            /* Hide Scrollbar if needed */
            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }
        }
    </style>
</head>

<body class="bg-choco-bg font-montserrat flex flex-col items-center justify-center min-h-screen relative overflow-x-hidden" onload="hideLoadingScreen()">

    {{-- <div id="loading-screen" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white transition-opacity duration-500">
        <img src="img/b734317dc97d8d22aec2f5b29e0e8672-removebg-preview.png" alt="Loading..." class="w-24 h-24 animate-spin-slow object-contain">
    </div> --}}

    <nav class="absolute top-0 w-full h-20 flex items-center justify-between px-10 z-50 animate-slide-down hover:bg-white/90 hover:text-[#503c32] transition-colors duration-300">
        <ul class="flex items-center">
            <li>
                <a class="text-5xl no-underline text-choco-dark font-qwigley font-light mx-2" href="/">ChocoScript</a>
            </li>
        </ul>
    </nav>

    <div class="mt-24 w-full"></div>

    {{-- register --}}
    <div class="bg-white rounded-[10px] shadow-2xl relative overflow-hidden w-[768px] max-w-full min-h-[480px] container" id="container">
        <div class="absolute top-0 left-0 w-1/2 h-full transition-all duration-600 ease-in-out opacity-0 z-[1] form-container sign-up-container bg-white">
            <form action="{{ route('register') }}" method="POST" class="bg-white flex flex-col items-center justify-center h-full px-12 text-center">
                @csrf
                <h1 class="font-bold text-3xl mb-0 text-[#e6c58c]">Create Account</h1>
                
                <span class="text-xs text-[#e6c58c] mb-4 mt-4">use your email for registration</span>
                
                <input type="text" name="name" placeholder="Name" class="bg-[#eee] border-none p-3 my-2 w-full outline-none" />
                <input type="text" name="username" placeholder="Username" class="bg-[#eee] border-none p-3 my-2 w-full outline-none" />
                <input type="email" name="email" placeholder="Email" class="bg-[#eee] border-none p-3 my-2 w-full outline-none" />
                <input type="password" name="password" placeholder="Password" class="bg-[#eee] border-none p-3 my-2 w-full outline-none" />
                
                <button type="submit" class="rounded-[20px] border border-choco-border bg-choco-btn text-[#432900] text-xs font-bold uppercase py-3 px-10 mt-4 tracking-wider transition-transform active:scale-95 focus:outline-none hover:bg-[#e0cba0]">
                    Sign Up
                </button>
            </form>
        </div>

        {{-- login --}}

        <div class="absolute top-0 left-0 w-1/2 h-full transition-all duration-600 ease-in-out z-[2] form-container sign-in-container bg-white">
            <form action="{{route('postlogin')}}" method="POST" class="bg-white flex flex-col items-center justify-center h-full px-12 text-center">
                @csrf
                <h1 class="font-bold text-3xl mb-4 text-[#e6c58c]">Sign in</h1>
                
                <span class="text-xs text-[#e6c58c] mb-4">or use your account</span>

                <input type="email" id="email" name="email" placeholder="Email" class="bg-[#eee] border-none p-3 my-2 w-full outline-none" />
                <input type="password" id="password" name="password" placeholder="Password" class="bg-[#eee] border-none p-3 my-2 w-full outline-none" />
                
                {{-- <a href="#" class="text-[#333] text-sm no-underline my-4 border-b border-transparent hover:border-[#333]">Forgot your password?</a> --}}
                
                <button type="submit" class="rounded-[20px] border border-choco-border bg-choco-btn text-[#432900] text-xs font-bold uppercase py-3 px-10 mt-2 tracking-wider transition-transform active:scale-95 focus:outline-none hover:bg-[#e0cba0]">
                    Login
                </button>
            </form>
        </div>

        <div class="absolute top-0 left-1/2 w-1/2 h-full overflow-hidden transition-transform duration-600 ease-in-out z-[100] overlay-container">
            <div class="bg-no-repeat bg-cover bg-[0_0] text-choco-accent relative -left-full h-full w-[200%] transform translate-x-0 transition-transform duration-600 ease-in-out overlay flex">
                
                <div class="overlay-panel overlay-left absolute flex flex-col items-center justify-center px-10 text-center top-0 h-full w-1/2 transform -translate-x-[20%] transition-transform duration-600 ease-in-out bg-cover bg-center" style="background-image: url('../../img/login/login3.jpg');">
                    <h1 class="font-bold text-3xl mb-0 text-white drop-shadow-md">Welcome ChocoScript</h1>
                    <p class="text-sm font-light leading-5 my-5 text-white drop-shadow-sm">To keep connected with us please login with your personal info</p>
                    <button class="ghost rounded-[20px] border border-[#7F4F00] bg-transparent text-[#432900] text-xs font-bold uppercase py-3 px-10 tracking-wider transition-transform active:scale-95 focus:outline-none hover:bg-white/20 bg-white/10 backdrop-blur-sm" id="signIn">
                        Login
                    </button>
                </div>

                <div class="overlay-panel overlay-right absolute right-0 flex flex-col items-center justify-center px-10 text-center top-0 h-full w-1/2 transform translate-x-0 transition-transform duration-600 ease-in-out bg-cover bg-center" style="background-image: url('../../img/login/login.png');">
                    <h1 class="font-bold text-3xl mb-0 text-white drop-shadow-md">Hello, Friend!</h1>
                    <p class="text-sm font-light leading-5 my-5 text-white drop-shadow-sm">Enter your personal details and start journey with us</p>
                    <button class="ghost rounded-[20px] border border-[#7F4F00] bg-transparent text-[#432900] text-xs font-bold uppercase py-3 px-10 tracking-wider transition-transform active:scale-95 focus:outline-none hover:bg-white/20 bg-white/10 backdrop-blur-sm" id="signUp">
                        Sign Up
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script src="../../js/login/login.js"></script>
    <script src="../../js/loading.js"></script>
    
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

</body>
</html>