 <nav id="sidebarMenu" class="hidden md:block w-64 bg-white border-r border-gray-200 h-full fixed md:relative z-40 overflow-y-auto">
            <div class="p-4 space-y-2">
                
                <a href="{{route('admin.dashboard')}}" class="block w-full">
                    <button class="admin-custom-btn admin-btn-5 w-full text-left px-4 py-2 rounded hover:bg-cream hover:text-choco transition">
                        <span>Dashboard</span>
                    </button>
                </a>

                <a href="/post" class="block w-full">
                    <button class="admin-custom-btn admin-btn-5 w-full text-left px-4 py-2 rounded hover:bg-cream hover:text-choco transition">
                        <span>Toko</span>
                    </button>
                </a>

                <a href="{{route('kategori')}} " class="block w-full">
                    <button class="admin-custom-btn admin-btn-5 w-full text-left px-4 py-2 rounded hover:bg-cream hover:text-choco transition">
                        <span>Kategori</span>
                    </button>
                </a>

                <a href="{{route('diskon.index')}} " class="block w-full">
                    <button class="admin-custom-btn admin-btn-5 w-full text-left px-4 py-2 rounded hover:bg-cream hover:text-choco transition">
                        <span>diskon</span>
                    </button>
                </a>

                <a href="{{route('cart_admin')}} " class="block w-full">
                    <button class="admin-custom-btn admin-btn-5 w-full text-left px-4 py-2 rounded hover:bg-cream hover:text-choco transition">
                        <span>List Cart</span>
                    </button>
                </a>
                <a href="{{route('admin.transactions.index')}} " class="block w-full">
                    <button class="admin-custom-btn admin-btn-5 w-full text-left px-4 py-2 rounded hover:bg-cream hover:text-choco transition">
                        <span>transactions</span>
                    </button>
                </a>
            </div>
        </nav>