@extends('layouts.base')

@section('content')
    <!-- Sidebar -->
    <div class="sidebar p-3">
        <h4 class="text-center mb-4">🐾 PETSHOPKU</h4>
        <div class="nav flex-column">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-box me-2"></i> Produk
            </a>
            <a href="{{ route('admin.transactions.index') }}" class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart me-2"></i> Transaksi
            </a>
            <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="fas fa-users me-2"></i> Pelanggan
            </a>

            <!-- 🔥 Menu Ulasan (Reviews) -->
            <a href="{{ route('admin.reviews.index') }}" 
               class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="fas fa-star me-2"></i> Ulasan
            </a>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf
            <a href="#" 
               onclick="event.preventDefault(); this.closest('form').submit();" 
               class="nav-link">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">@yield('header', 'Dashboard')</h4>
            <div>
                <span class="me-3">
                    <i class="fas fa-user-circle me-1"></i>
                    {{ Auth::user()->name }}
                </span>
                <span class="text-muted">Admin</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>
@endsection

@push('styles')
<style>
    /* Admin CSS */
    .sidebar {
        height: 100vh;
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #1e1e2d;
        color: white;
        padding: 1rem 0;
        z-index: 100;
        overflow-y: auto;
    }
    .sidebar h4 {
        color: white;
        font-weight: bold;
    }
    .nav-link {
        color: #b8b8c7;
        border-radius: 8px;
        padding: 10px 15px;
        margin: 0 0.5rem;
        display: block;
        text-decoration: none;
    }
    .nav-link:hover, .nav-link.active {
        background-color: #0d6efd;
        color: white;
    }
    .main-content {
        margin-left: 250px;
        padding: 2rem;
    }
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .table-section {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.875rem;
    }
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    .status-success {
        background-color: #d4edda;
        color: #155724;
    }
    .status-failed {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>
@endpush

@push('scripts')
<script>
    // Admin search functionality
    const adminSearch = document.getElementById('adminSearch');
    adminSearch?.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            const currentUrl = window.location.pathname;
            let searchUrl = '{{ route("admin.dashboard") }}';
            
            if (currentUrl.includes('products')) {
                searchUrl = '{{ route("admin.products.index") }}';
            } else if (currentUrl.includes('transactions')) {
                searchUrl = '{{ route("admin.transactions.index") }}';
            } else if (currentUrl.includes('customers')) {
                searchUrl = '{{ route("admin.customers.index") }}';
            } else if (currentUrl.includes('reviews')) {
                searchUrl = '{{ route("admin.reviews.index") }}';
            }
            
            window.location.href = `${searchUrl}?search=${this.value}`;
        }
    });
</script>
@endpush