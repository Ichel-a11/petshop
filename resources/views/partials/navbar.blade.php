<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    {{-- Logo --}}
    <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('customer.dashboard') }}">
      <i class="bi bi-shop-window me-2 text-primary fs-4"></i>
      Pablo <span class="text-primary">Petshop</span>
    </a>

    {{-- Hamburger --}}
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      {{-- Menu --}}
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('customer.catalog') }}">
            <i class="bi bi-grid me-1"></i> Katalog
          </a>
        </li>

        {{-- Tombol Grooming --}}
        <li class="nav-item ms-2">
          <a href="{{ route('customer.grooming.index') }}" class="btn btn-danger rounded-pill px-3 fw-semibold shadow-sm">
            <i class="bi bi-scissors me-1"></i> Grooming
          </a>
        </li>

        {{-- Keranjang --}}
        <li class="nav-item ms-2">
          <a class="nav-link position-relative" href="{{ route('customer.cart.index') }}">
            <i class="bi bi-cart4 me-1"></i> Keranjang
            {{-- contoh badge jumlah item --}}
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              2
            </span>
          </a>
        </li>

        @auth
          {{-- Dashboard --}}
          <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
              <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
          </li>

          {{-- User Dropdown --}}
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-gear me-2"></i> Profil</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="dropdown-item text-danger" type="submit">
                    <i class="bi bi-box-arrow-right me-2"></i> Keluar
                  </button>
                </form>
              </li>
            </ul>
          </li>
        @else
          {{-- Login Button --}}
          <li class="nav-item ms-2">
            <a class="btn btn-primary rounded-pill px-3 shadow-sm" href="{{ route('login') }}">
              <i class="bi bi-box-arrow-in-right me-1"></i> Login
            </a>
          </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>
