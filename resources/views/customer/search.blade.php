@extends('cusomer.layouts.customer')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8 text-center">
            <h1 class="display-4 mb-4">Cari Produk</h1>
            
            <!-- Search Form -->
            <form action="{{ route('customer.search') }}" method="GET" class="mb-4">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="input-group input-group-lg shadow-sm">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" 
                                name="query" 
                                class="form-control border-start-0" 
                                placeholder="Cari nama produk atau deskripsi..." 
                                value="{{ $query }}"
                                required
                                autofocus>
                            <button type="submit" class="btn btn-primary px-4">
                                Cari
                            </button>
                        </div>
                        <div class="mt-2 text-muted small">
                            <i class="fas fa-info-circle me-1"></i>
                            Contoh: makanan kucing, vitamin anjing, dll
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($hasSearch)
        <!-- Search Results -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Hasil Pencarian</h2>
                    <p class="text-muted mb-0">
                        Ditemukan {{ $products->total() }} produk untuk "{{ $query }}"
                    </p>
                </div>
                <hr>
            </div>
        </div>

        @if($products->isEmpty())
            <!-- No Results -->
            <div class="row justify-content-center">
                <div class="col-md-6 text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" 
                        alt="No Results" 
                        class="img-fluid mb-4" 
                        style="max-width: 200px; opacity: 0.5;">
                    <h3 class="text-muted">Tidak Ada Hasil</h3>
                    <p class="text-muted mb-4">
                        Maaf, kami tidak dapat menemukan produk yang sesuai dengan pencarian Anda.
                        Coba gunakan kata kunci yang berbeda atau periksa ejaan Anda.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('customer.search') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-2"></i>Reset Pencarian
                        </a>
                        <a href="{{ route('customer.catalog') }}" class="btn btn-primary">
                            <i class="fas fa-th-large me-2"></i>Lihat Semua Produk
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Results Grid -->
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="card h-100 border-0 shadow-sm product-card">
                            <!-- Product Image -->
                            <div class="position-relative">
                                @if($product->stock == 0)
                                    <div class="position-absolute top-0 start-0 m-3">
                                        <span class="badge bg-danger">Stok Habis</span>
                                    </div>
                                @endif
                                @if ($product->image)
                                    <img src="{{ asset('storage/produk/' . $product->image) }}" 
                                        class="card-img-top" 
                                        alt="{{ $product->name }}"
                                        style="height: 200px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" 
                                        class="card-img-top" 
                                        alt="No Image"
                                        style="height: 200px; object-fit: cover;">
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="card-body">
                                <h5 class="card-title mb-1">{{ $product->name }}</h5>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h4 class="text-primary mb-0">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </h4>
                                    <small class="text-muted">
                                        Stok: {{ $product->stock }}
                                    </small>
                                </div>
                                @if($product->description)
                                    <p class="card-text small text-muted mb-0">
                                        {{ Str::limit($product->description, 100) }}
                                    </p>
                                @endif
                            </div>

                            <!-- Action Button -->
                            <div class="card-footer bg-white border-0 pt-0">
                                <form action="{{ route('customer.cart.add.product', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100" 
                                        {{ $product->stock == 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-cart-plus me-2"></i>
                                        Tambah ke Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        @endif

        <!-- Popular Search Terms (can be dynamic later) -->
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="mb-3">Pencarian Populer</h4>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('customer.search', ['query' => 'makanan kucing']) }}" 
                        class="btn btn-outline-secondary btn-sm">
                        Makanan Kucing
                    </a>
                    <a href="{{ route('customer.search', ['query' => 'vitamin anjing']) }}" 
                        class="btn btn-outline-secondary btn-sm">
                        Vitamin Anjing
                    </a>
                    <a href="{{ route('customer.search', ['query' => 'mainan']) }}" 
                        class="btn btn-outline-secondary btn-sm">
                        Mainan
                    </a>
                    <a href="{{ route('customer.search', ['query' => 'grooming']) }}" 
                        class="btn btn-outline-secondary btn-sm">
                        Grooming
                    </a>
                    <a href="{{ route('customer.search', ['query' => 'aksesoris']) }}" 
                        class="btn btn-outline-secondary btn-sm">
                        Aksesoris
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .product-card {
        transition: transform 0.2s ease-in-out;
    }
    .product-card:hover {
        transform: translateY(-5px);
    }
    .product-card .card-img-top {
        transition: opacity 0.2s ease-in-out;
    }
    .product-card:hover .card-img-top {
        opacity: 0.8;
    }
</style>
@endpush
@endsection
