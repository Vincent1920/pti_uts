    <!-- 
        IF-8 
    Abdul Malik Febrian Zulkifli (10123308)
    Nadzla Khoerunnisa Misbah(10123285)
    vincent luhulima  (10123309)
     -->
    <div class="iklan">
        <div class="container-iklan">
            <div id="container-iklan">
                <div id="container-iklan-text1"> Vegsn Milk Chocolate 20%</div>
                <div id="container-iklan-text3">Milk Magazine 15%</div>
                <div>Diskon 40%</div>
                <div id="container-iklan-text2"> cherry cake 30%</div>
            </div>
        </div>
    </div>


    <hr />

    <!-- navbar -->

    <nav class="navbar">
        <ul>
            <li class="navbar-li ">

                <a class="navbar-link" href="/"> ChocoScript</a>
            </li>
        </ul>


        <ul class="navbar1">
            <li class="navbar-li dropdown">
                <a class=" navbar-link dropdown-toggle" href="{{ route('shop') }}">
                    Shop
                </a>
                <ul class="dropdown-menu ">
                    <li class="dropdown-item">
                        <a class="navbar-link1" href="{{ route('shop') }}">
                            <div class="navbar-img1">
                            <img src="../img/brand/Tongan_vanilla.png" alt="">
                            </div>
                            <div class="navbar-text1">
                                <h2>
                                    Milk Magazine
                                </h2>
                            </div>
                        </a>
                    </li>
                    <li class="dropdown-item">
                        <a class="navbar-link1" href="{{ route('shop') }}">
                            <div class="navbar-img1">
                                 <img src="../img/brand/Tongan_vanilla.png" alt="">
                            </div>
                            <div class="navbar-text1">
                                <h2>
                                    Delivered Nationwide
                                </h2>
                            </div>
                        </a>
                    </li>

                    <li class="dropdown-item">
                        <a class="navbar-link1" href="{{ route('shop') }}">
                            <div class="navbar-img1">
                                <img src="../img/brand/dark_22_piece-removebg-preview.png" alt="">
                            </div>
                            <div class="navbar-text1">
                                <h2>
                                    Chocolate
                                </h2>
                            </div>
                        </a>
                    </li>

                    <li class="dropdown-item">
                        <a class="navbar-link1" href="{{ route('shop') }}">
                            <div class="navbar-img1">
                                <img src="../img/brand/Chocolate.png" alt="">
                            </div>
                            <div class="navbar-text1">
                                <h2>
                                    Dark Choco
                                </h2>
                            </div>
                        </a>
                    </li>


                </ul>
            </li>
            {{-- 
        <li class="navbar-li dropdown">
            <a class="navbar-link dropdown-toggle " href="{{route('brand')}}">
            Brand
            </a>
            <ul class="dropdown-menu ">
                <li class="dropdown-item">
                    <a class="navbar-link1" href="{{route('shop')}}">
                        <div class="navbar-img1">
                            <img src="../img/brand/Chocolate.png" alt="">
                        </div>
                        <div class="navbar-text1">
                            <h2>
                                Chocolate
                            </h2>
                        </div>
                    </a>
                </li>
                <li class="dropdown-item">
                    <a class="navbar-link1" href="{{route('shop')}}">
                        <div class="navbar-img1">
                            <img src="../img/brand/dark_22_piece-removebg-preview.png" alt="">
                        </div>
                        <div class="navbar-text1">
                            <h2>
                                Dark Choco
                            </h2>
                        </div>
                    </a>
                </li>
                <li class="dropdown-item">
                    <a class="navbar-link1" href="{{route('shop')}}">
                        <div class="navbar-img1">
                            <img src="../img/brand/Super_cookies.png" alt="">
                        </div>
                        <div class="navbar-text1">
                            <h2>
                                Super Cookies
                            </h2>
                        </div>
                    </a>
                </li>

                <li class="dropdown-item">
                    <a class="navbar-link1" href="{{route('shop')}}">
                        <div class="navbar-img1">
                            <img src="../img/brand/Tongan_vanilla.png" alt="">
                        </div>
                        <div class="navbar-text1">
                            <h2>
                                Tongan vanilla
                            </h2>
                        </div>
                    </a>
                </li>
            </ul>
            </li> --}}
        </ul>

        @auth
        <ul>
            <li id="dd2" class="navbar_cart wrapper-dropdown-3 navbar-li navbar_cart navbar-link">
                <p class="dropdown2">{{ auth()->user()->username }}</p>
                <ul class="dropdown">
                    <li class="bob_nav">
                        <a href="{{ route('logout') }}">Logout

                            <svg class="bob_nav-svg" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                fill="#c88a5b" class="logout bi bi-box-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                                <path fill-rule="evenodd"
                                    d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                            </svg>
                        </a>
                    </li>
                    @if (auth()->user()->role === 'admin')
                    <li class="bob_nav"><a class="dropdown_a" href="/admin">Admin
                            <svg class="bob_nav-svg" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                fill="#c88a5b" class="bi bi-person-fill-lock" viewBox="0 0 16 16">
                                <path
                                    d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5v-1a2 2 0 0 1 .01-.2 4.49 4.49 0 0 1 1.534-3.693Q8.844 9.002 8 9c-5 0-6 3-6 4m7 0a1 1 0 0 1 1-1v-1a2 2 0 1 1 4 0v1a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zm3-3a1 1 0 0 0-1 1v1h2v-1a1 1 0 0 0-1-1" />
                            </svg>
                        </a></li>
                    <li class="bob_nav"><a class="dropdown_a" href="{{route('cart')}}">Card

                            <svg class="bob_nav-svg" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                fill="#c88a5b" class="bi bi-basket2-fill" viewBox="0 0 16 16">
                                <path
                                    d="M5.929 1.757a.5.5 0 1 0-.858-.514L2.217 6H.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h.623l1.844 6.456A.75.75 0 0 0 3.69 15h8.622a.75.75 0 0 0 .722-.544L14.877 8h.623a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1.717L10.93 1.243a.5.5 0 1 0-.858.514L12.617 6H3.383zM4 10a1 1 0 0 1 2 0v2a1 1 0 1 1-2 0zm3 0a1 1 0 0 1 2 0v2a1 1 0 1 1-2 0zm4-1a1 1 0 0 1 1 1v2a1 1 0 1 1-2 0v-2a1 1 0 0 1 1-1" />
                            </svg>
                        </a>
                    </li>
                    @else
                    <li class="bob_nav"><a class="dropdown_a" href="{{route('cart')}}">Card

                            <svg class="bob_nav-svg" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                fill="#c88a5b" class="bi bi-basket2-fill" viewBox="0 0 16 16">
                                <path
                                    d="M5.929 1.757a.5.5 0 1 0-.858-.514L2.217 6H.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h.623l1.844 6.456A.75.75 0 0 0 3.69 15h8.622a.75.75 0 0 0 .722-.544L14.877 8h.623a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1.717L10.93 1.243a.5.5 0 1 0-.858.514L12.617 6H3.383zM4 10a1 1 0 0 1 2 0v2a1 1 0 1 1-2 0zm3 0a1 1 0 0 1 2 0v2a1 1 0 1 1-2 0zm4-1a1 1 0 0 1 1 1v2a1 1 0 1 1-2 0v-2a1 1 0 0 1 1-1" />
                            </svg>
                        </a>
                    </li>
                    @endif
                    {{-- <li><a href="../page/kategori/drink.html">Drink</a></li> --}}
                </ul>
            </li>
            @else
            <a class="navbar-bob" href="/login">Login</a>
        </ul>
        @endauth
    </nav>