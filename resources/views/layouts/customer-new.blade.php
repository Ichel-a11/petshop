@extends('customer.layouts.base')

@section('navigation')
    <!-- Customer Navigation -->
    <div class="nav-item">
        <a href="{{ route('customer.dashboard') }}" 
           class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
    </div>
    <div class="nav-item">
        <a href="{{ route('customer.catalog') }}" 
           class="nav-link {{ request()->routeIs('customer.catalog') ? 'active' : '' }}">
            <i class="fas fa-shopping-bag"></i>
            <span>Katalog</span>
        </a>
    </div>
    <div class="nav-item">
        <a href="{{ route('customer.grooming.index') }}" 
           class="nav-link {{ request()->routeIs('customer.grooming.*') ? 'active' : '' }}">
            <i class="fas fa-cut"></i>
            <span>Grooming</span>
        </a>
    </div>
    <div class="nav-item">
        <a href="{{ route('customer.cart.index') }}" 
           class="nav-link {{ request()->routeIs('customer.cart.*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart"></i>
            <span>Keranjang</span>
        </a>
    </div>
    <hr class="mx-3">
    <div class="nav-item">
        <a href="{{ route('profile.edit') }}" 
           class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i>
            <span>Profile</span>
        </a>
    </div>
@endsection

@section('search')
    <form action="{{ route('customer.search') }}" class="topbar-search d-none d-md-block">
        <div class="input-group">
            <input type="text" class="form-control border-end-0" 
                name="query" placeholder="Cari produk...">
            <span class="input-group-text bg-white border-start-0">
                <i class="fas fa-search text-muted"></i>
            </span>
        </div>
    </form>
@endsection
