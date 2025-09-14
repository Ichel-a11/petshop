@extends('customer.layouts.app')

@section('title', 'Dashboard Customer')

@section('content')
<div class="container py-5">

    <!-- Header -->
    <div class="mb-5 text-center">
        <h1 class="fw-bold">Selamat Datang, {{ Auth::user()->name }} 🐾</h1>
        <p class="text-muted">Selamat Berbelanja dan dan menikmati layanan grooming hewan peliharaan Anda di sini.</p>
    </div>

    <!-- Statistik -->
    <div class="row g-4 mb-5 text-center">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="fs-2 mb-2">📦</div>
                    <h5 class="fw-bold">{{ $user->orders_count }}</h5>
                    <p class="text-muted small">Total Pesanan</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="fs-2 mb-2">💰</div>
                    <h5 class="fw-bold">Rp {{ number_format($user->orders_sum_total_price, 0, ',', '.') }}</h5>
                    <p class="text-muted small">Total Belanja</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="fs-2 mb-2">✂️</div>
                    <h5 class="fw-bold">{{ $upcomingGroomingCount }}</h5>
                    <p class="text-muted small">Booking Grooming Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="fs-2 mb-2">🐕</div>
                    <h5 class="fw-bold">{{ $completedGroomingCount }}</h5>
                    <p class="text-muted small">Grooming Selesai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Utama -->
    <div class="row g-4 mb-5 text-center">
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="{{ route('customer.products.index') }}" class="card shadow-sm border-0 text-decoration-none h-100">
                <div class="card-body">
                    <div class="fs-1 mb-3">🛍️</div>
                    <h5 class="fw-bold">Katalog Produk</h5>
                    <p class="text-muted small">Belanja kebutuhan hewan peliharaan</p>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="{{ route('customer.cart.index') }}" class="card shadow-sm border-0 text-decoration-none h-100">
                <div class="card-body">
                    <div class="fs-1 mb-3">🛒</div>
                    <h5 class="fw-bold">Keranjang</h5>
                    <p class="text-muted small">Lihat produk yang ingin dibeli</p>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="{{ route('customer.grooming.index') }}" class="card shadow-sm border-0 text-decoration-none h-100">
                <div class="card-body">
                    <div class="fs-1 mb-3">✂️</div>
                    <h5 class="fw-bold">Grooming</h5>
                    <p class="text-muted small">Booking layanan grooming hewan Anda</p>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="{{ route('customer.orders.index') }}" class="card shadow-sm border-0 text-decoration-none h-100">
                <div class="card-body">
                    <div class="fs-1 mb-3">📦</div>
                    <h5 class="fw-bold">Pesanan Saya</h5>
                    <p class="text-muted small">Lihat riwayat & status pesanan</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Produk Rekomendasi -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h4 class="fw-bold">✨ Produk Rekomendasi</h4>
        <a href="{{ route('customer.products.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>

    <div class="row g-4">
        @forelse ($recommendedProducts as $product)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm border-0 product-card position-relative">

                    <!-- Badge Stok -->
                    <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }} position-absolute m-2">
                        {{ $product->stock > 0 ? 'Stok Tersedia' : 'Stok Habis' }}
                    </span>

                    <!-- Gambar Produk -->
                    <div class="ratio ratio-4x3">
                        <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300x200' }}" 
                             class="card-img-top rounded-top" 
                             alt="{{ $product->name }}"
                             style="object-fit: cover;">
                    </div>

                    <!-- Detail Produk -->
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title text-truncate mb-1">{{ $product->name }}</h6>
                        <p class="card-text text-muted small flex-grow-1 mb-2">
                            {{ Str::limit($product->description, 50) }}
                        </p>

                        <div class="fw-bold text-primary mb-2">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('customer.products.show', $product->id) }}" 
                               class="btn btn-sm btn-outline-primary w-50">
                                Lihat
                            </a>

                            @if ($product->stock > 0)
                                <form action="{{ route('customer.cart.add.product', $product->id) }}" method="POST" class="w-50">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-sm btn-primary w-100">
                                        🛒
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-secondary w-50" disabled>
                                    🛒
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center text-muted mt-5">Belum ada produk rekomendasi 😔</p>
            </div>
        @endforelse
    </div>

    <!-- Jadwal Grooming Terdekat -->
    <div class="mt-5">
        <h4 class="fw-bold mb-3">📅 Jadwal Grooming Terdekat</h4>
        @forelse($upcomingGrooming as $GroomingBooking)
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-1">{{ $GroomingBooking->service->name ?? 'Layanan Grooming' }}</h6>
                        <p class="text-muted small mb-0">
                            {{ \Carbon\Carbon::parse($GroomingBooking->booking_time)->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <span class="badge bg-warning text-dark text-capitalize">
                        {{ $GroomingBooking->status }}
                    </span>
                </div>
            </div>
        @empty
            <p class="text-muted">Tidak ada Booking grooming.</p>
        @endforelse
    </div>
</div>

<!-- Style Hover Card -->
<style>
.product-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(219, 173, 173, 0.1);
}
</style>
@endsection
