<nav x-data="{ open: false }" class="bg-white shadow-md border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('customer.dashboard') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/pet-logo.png') }}" alt="Petshop Logo" class="h-9 w-9">
                    <span class="font-bold text-lg text-red-600">Pablo Petshop</span>
                </a>
            </div>

          

            <!-- Menu -->
            <div class="hidden sm:flex items-center space-x-6">
                <a href="{{ route('customer.catalog') }}" class="text-gray-700 hover:text-red-600 font-medium">Katalog</a>

                <!-- Grooming -->
                <a href="{{ route('customer.grooming.index') }}" class="text-gray-700 hover:text-red-600 font-medium">
                    ✂️ Grooming
                </a>
                
                <!-- Cart -->
                <a href="{{ route('customer.cart.index') }}" class="relative text-gray-700 hover:text-red-600 font-medium">
                    🛒
                    @if(session('cart_count', 0) > 0)
                        <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full px-1.5">
                            {{ session('cart_count') }}
                        </span>
                    @endif
                </a>

                <!-- Dropdown User -->
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center space-x-2 text-gray-600 hover:text-red-600">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">👤 Profil</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    🚪 Keluar
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition">
                        Login
                    </a>
                @endauth
            </div>

            <!-- Mobile Button -->
            <div class="sm:hidden">
                <button @click="open = ! open" class="p-2 text-gray-600 hover:text-red-600">
                    ☰
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': ! open }" class="hidden sm:hidden bg-white border-t border-gray-200">
        <div class="p-4 space-y-3">
            <a href="{{ route('customer.catalog') }}" class="block text-gray-700 hover:text-red-600">Katalog</a>
            <a href="{{ route('customer.grooming.index') }}" class="block text-gray-700 hover:text-red-600">✂️ Grooming</a>
            <a href="{{ route('customer.cart.index') }}" class="block text-gray-700 hover:text-red-600">Keranjang</a>
            @auth
                <a href="{{ route('profile.edit') }}" class="block text-gray-700 hover:text-red-600">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left text-gray-700 hover:text-red-600">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block text-gray-700 hover:text-red-600">Login</a>
            @endauth
        </div>
    </div>
</nav>
