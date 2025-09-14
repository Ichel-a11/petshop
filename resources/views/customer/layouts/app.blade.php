<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pablo Petshop</title>

    {{-- Bootstrap 5 CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Custom CSS --}}
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-search {
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fw-bold text-danger d-flex align-items-center" href="{{ route('customer.dashboard') }}">
                <img src="{{ asset('images/pablo.jpg') }}" alt="Logo" width="32" height="32" class="me-2">
                Pablo <span class="text-dark">Petshop</span>
            </a>

            <!-- Toggle (mobile) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                

                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.catalog') }}">Katalog</a>
                    </li>

                    {{-- Grooming --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.grooming.*') ? 'active fw-bold text-danger' : '' }}" 
                           href="{{ route('customer.grooming.index') }}">
                            <i class="bi bi-scissors me-1"></i> Grooming
                        </a>
                    </li>

                    {{-- Cart --}}
                    <li class="nav-item position-relative mx-2">
                        <a class="nav-link" href="{{ route('customer.cart.index') }}">
                            <i class="bi bi-cart3 fs-5"></i>
                            @if(session('cart_count', 0) > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ session('cart_count') }}
                                </span>
                            @endif
                        </a>
                    </li>

                    {{-- Auth --}}
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">👤 Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">🚪 Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="btn btn-outline-danger rounded-pill px-3 ms-2" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-danger rounded-pill px-3 ms-2" href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> Daftar
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- Konten --}}
    <main class="flex-fill py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        &copy; {{ date('Y') }} Selamat Berbelanja 
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
