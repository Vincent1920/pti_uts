<div id="loading-screen">
    <img src="img/b734317dc97d8d22aec2f5b29e0e8672-removebg-preview.png" alt="Loading...">
</div>

<nav class="navbar">
    <ul>
        <li class="navbar-li ">
            <a class="navbar-link" href="/"> ChocoScript</a>
        </li>
    </ul>


    <ul class="navbar1">
        <li class="navbar-li dropdown">
            <a class=" navbar-link dropdown-toggle" href="{{route('shop')}}">
                Shop
            </a>
        </li>

        <li class="navbar-li dropdown">
            <a class="navbar-link dropdown-toggle " href="page/brand.html">
                Brand
            </a>
           </li>
        <ul>
            <li class=" navbar_cart  navbar-li navbar_cart  navbar-link ">
                @auth
                <h1>{{auth()->user()->username}} </h1>
          
                {{-- <a class="logout"href="{{route('logout')}}">logout</a> --}}
                @else
                {{--
                <a class=" navbar-link dropdown-toggle" href="/login">
                    login
                </a> --}}
          
                @endauth 
            </li>
        </ul>
</nav>