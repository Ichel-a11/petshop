{{-- resources/views/admin/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        /* Reset & Layout */
        * {
            box-sizing: border-box;
        }
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f6fa;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar-header {
            padding: 1rem 1.5rem;
            font-size: 1.25rem;
            font-weight: bold;
            border-bottom: 1px solid #495057;
        }

        .sidebar-nav {
            flex-grow: 1;
        }

        .sidebar a.nav-link {
            color: #dfe4ea;
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar a.nav-link:hover,
        .sidebar a.nav-link.active {
            color: white;
            background-color: #495057;
            border-left: 3px solid #0d6efd;
        }

        .sidebar a.nav-link i {
            margin-right: 0.8rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            margin-left: 250px;
            padding: 2rem;
            min-height: 100vh;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 220px;
            }
            .main-content {
                margin-left: 220px;
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 200px;
            }
            .main-content {
                margin-left: 200px;
                padding: 1rem;
            }
            .sidebar-header h4 {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar Admin -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4 class="mb-0"><i class="bi bi-shield-lock"></i>Admin Panel</h4>
        </div>

        <div class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.products.index') }}" 
               class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>Produk</span>
            </a>

            <a href="{{ route('admin.transactions.index') }}" 
               class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i>
                <span>Transaksi</span>
            </a>

            <a href="{{ route('admin.customers.index') }}" 
               class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Pelanggan</span>
            </a>

            <a href="{{ route('admin.grooming-services.index') }}" 
               class="nav-link {{ request()->routeIs('admin.grooming-services.*') ? 'active' : '' }}">
                <i class="bi bi-scissors"></i>
                <span>Grooming</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" 
               class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i>
                <span>Kategori</span>
            </a>

            <a href="{{ route('admin.reviews.index') }}" 
               class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <i class="bi bi-star"></i>
                <span>Ulasan</span>
            </a>
        </div>

        <!-- Logout -->
        <div class="p-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100 d-flex align-items-center justify-content-center">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1 class="h3 mb-4">@yield('page_title', 'Dashboard')</h1>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auto Close Alert -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    if (alert) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                });
            }, 3000);
        });
    </script>
</body>
</html>