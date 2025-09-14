@extends('customer.layouts.app')

@section('content')
<div class="container my-5">

  

    <h3 class="text-center mb-4">Katalog Produk</h3>

    {{-- Form Filter --}}
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <input type="number" name="min_price" class="form-control" placeholder="Harga min" value="{{ request('min_price') }}">
        </div>
        <div class="col-md-2">
            <input type="number" name="max_price" class="form-control" placeholder="Harga max" value="{{ request('max_price') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    {{-- Katalog --}}
    <div class="row g-2">
        @foreach($products as $product)
            <div class="col-6 col-md-2 mb-3">
                <div class="card h-100 shadow-sm" style="font-size: 0.8rem;">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             class="card-img-top" 
                             style="height: 110px; object-fit: cover;">
                    @else
                        <img src="https://via.placeholder.com/150x110" class="card-img-top">
                    @endif
                    <div class="card-body p-2 d-flex flex-column">
                        <h6 class="card-title text-truncate" style="font-size: 0.8rem;">
                            {{ $product->name }}
                        </h6>

                        <p class="text-danger fw-bold mb-1" style="font-size: 0.8rem;">
                            Rp{{ number_format($product->price, 0, ',', '.') }}
                        </p>

                        {{-- Badge stok --}}
                        <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }} mb-2" style="font-size: 0.7rem;">
                            {{ $product->stock > 0 ? 'Stok: ' . $product->stock : 'Habis' }}
                        </span>

                        <a href="{{ route('products.show', $product->id) }}" 
                           class="btn btn-sm btn-outline-primary mb-1">Detail</a>

                        <form action="{{ route('customer.cart.add.product', $product->id) }}" method="POST">

                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-sm btn-success w-100"
                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                🛒 Beli
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
