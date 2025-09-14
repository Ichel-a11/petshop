{{-- resources/views/customer/home.blade.php --}}
@extends('customer.layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Selamat Datang di Pablo Petshop 🐾</h1>
        <p class="text-muted">Temukan hewan peliharaan impianmu di sini!</p>
        <a href="{{ route('customer.cart.index') }}" class="btn btn-outline-primary mt-3">
            <i class="bi bi-cart-fill"></i> Lihat Keranjang
        </a>
    </div>

    <div class="row">
        @foreach($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                @else
                    <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="No Image">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text mb-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <a href="{{ route('customer.products.show', $product->id) }}" class="btn btn-primary mt-auto">Lihat Detail</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
