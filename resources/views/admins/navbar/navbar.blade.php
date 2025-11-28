<nav id="sidebarMenu" class=" col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link active text-decoration-none" aria-current="page" href="{{route('admin.dashboard')}}">
            <button class="admin-custom-btn admin-btn-5"><span>Dashboard</span></button>
          </a>
        </li>   
        <li class="nav-item">
          <a class="nav-link" href="/post">
            <button class="admin-custom-btn admin-btn-5"><span>toko</span></button>
            {{-- <span data-feather="file"></span> --}}
           
          </a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="{{route('kategori')}} ">
          <button class="admin-custom-btn admin-btn-5"><span>kategori</span></button>
          {{-- <span data-feather="file"></span> --}}
         
        </a>
      </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('cart_admin')}} ">
            <button class="admin-custom-btn admin-btn-5"><span>list cart</span></button>
            {{-- <span data-feather="file"></span> --}}
           
          </a>
        </li>
        {{-- <li class="nav-item">
          <a class="nav-link" href="#">
            <span data-feather="shopping-cart"></span>
            menu
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/news">
            <span data-feather="file-text"></span>
            news 
          </a>
        </li> --}}
      </ul>
    </div>
  </nav>
  