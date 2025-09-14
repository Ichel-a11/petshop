<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fc;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            background: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .sidebar-brand h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            padding: 0.25rem 1.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--secondary-color);
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color);
            background: rgba(78, 115, 223, 0.1);
        }

        .nav-link.active {
            color: var(--primary-color);
            background: rgba(78, 115, 223, 0.1);
            font-weight: 600;
        }

        .nav-link i {
            width: 1.5rem;
            text-align: center;
            margin-right: 0.75rem;
            font-size: 1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            transition: all 0.3s ease;
            padding-bottom: 2rem;
        }
        
        .content-wrapper {
            padding: 1.5rem;
        }

        /* Topbar */
        .topbar {
            background: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .topbar-search {
            max-width: 300px;
        }

        .topbar-search .input-group {
            border-radius: 2rem;
            overflow: hidden;
        }

        .topbar-search input {
            border-right: none;
            padding-left: 1.25rem;
        }

        .topbar-search .input-group-text {
            background: white;
            border-left: none;
        }

        /* User Dropdown */
        .user-dropdown .dropdown-toggle::after {
            display: none;
        }

        .user-dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .user-dropdown .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            margin-right: 0.75rem;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        /* Buttons */
        .btn {
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: #2e59d9;
            border-color: #2e59d9;
        }

        /* Tables */
        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            border-top: none;
        }

        /* Badges */
        .badge {
            padding: 0.5em 1em;
            font-weight: 500;
            font-size: 0.75rem;
        }

        /* Catalog */
        .product-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .product-card .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-card .card-text {
            flex: 1;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggler {
                display: block;
            }

            .topbar-search {
                display: none;
            }
        }

        /* Custom Animations */
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>Pablo Petshop</h2>
        </div>
        <nav class="sidebar-nav">
            @yield('navigation')
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <!-- Sidebar Toggle -->
            <button class="btn btn-link sidebar-toggler d-md-none">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Search -->
            @yield('search')

            <!-- User Menu -->
            <div class="dropdown user-dropdown">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="user-avatar">
                        {{ substr(auth()->user()->name ?? 'G', 0, 1) }}
                    </div>
                    <div class="d-none d-md-block">
                        <div class="fw-semibold">{{ auth()->user()->name ?? 'Guest' }}</div>
                        <div class="small text-muted">{{ ucfirst(auth()->user()->role ?? 'guest') }}</div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-circle me-2"></i>Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Page Content -->
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="fade-in">
                @yield('content')
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle
        document.querySelector('.sidebar-toggler')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });

        // Close sidebar on click outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                const sidebar = document.querySelector('.sidebar');
                const sidebarToggler = document.querySelector('.sidebar-toggler');
                
                if (!sidebar.contains(e.target) && !sidebarToggler.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Auto-hide alerts
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150);
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>
</html>
