@extends('customer.layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded shadow-sm" alt="{{ $product->name }}">
            @else
                <img src="https://via.placeholder.com/500x400" class="img-fluid rounded shadow-sm" alt="No Image">
            @endif
        </div>
        <div class="col-md-6">
            <h2 class="fw-bold">{{ $product->name }}</h2>
            <h4 class="text-danger mb-2">Rp{{ number_format($product->price, 0, ',', '.') }}</h4>

            {{-- Badge stok --}}
            @if($product->stock > 3)
                <span class="badge bg-success mb-3">Stok: {{ $product->stock }}</span>
            @elseif($product->stock > 0 && $product->stock <= 3)
                <span class="badge bg-warning text-dark mb-3">Stok hampir habis ({{ $product->stock }})</span>
            @else
                <span class="badge bg-danger mb-3">Stok Habis</span>
            @endif

            <p>{{ $product->description }}</p>

            {{-- ✅ Form Tambah ke Keranjang — DIPERBAIKI --}}
            <form action="{{ route('customer.cart.add.product', $product->id) }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-3">
                    <label for="quantity" class="form-label">Jumlah</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control" style="width: 120px;" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                </div>
                <button type="submit" class="btn btn-success btn-lg" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                    🛒 Tambah ke Keranjang
                </button>
            </form>
        </div>
    </div>

    {{-- Ulasan & Rating --}}
    <div class="mt-5">
        <h4>Ulasan & Rating</h4>

        {{-- Daftar Ulasan --}}
        @if($product->reviews && $product->reviews->count())
            @foreach($product->reviews as $review)
                <div class="border rounded p-3 mb-3">
                    <strong>{{ $review->user->name }}</strong>  
                    <span class="text-warning">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating) ★ @else ☆ @endif
                        @endfor
                    </span>
                    <p class="mb-0">{{ $review->comment }}</p>
                    <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                </div>
            @endforeach
        @else
            <p class="text-muted">Belum ada ulasan untuk produk ini.</p>
        @endif

        {{-- Form Tambah Ulasan --}}
        @auth
            <div class="mt-4">
                <h5>Tulis Ulasan</h5>
                <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating</label>
                        <select name="rating" id="rating" class="form-control" required>
                            <option value="">Pilih rating</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}">{{ $i }} ★</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Komentar</label>
                        <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                </form>
            </div>
        @else
            <p class="mt-3">Silakan <a href="{{ route('login') }}">login</a> untuk memberi ulasan.</p>
        @endauth
    </div>

    {{-- Produk Terkait --}}
    @if(isset($relatedProducts) && $relatedProducts->count())
    <div class="mt-5">
        <h4>Produk Terkait</h4>
        <div class="row">
            @foreach($relatedProducts as $related)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        @if($related->image)
                            <img src="{{ asset('storage/' . $related->image) }}" class="card-img-top" alt="{{ $related->name }}">
                        @else
                            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="No Image">
                        @endif
                        <div class="card-body">
                            <h6 class="card-title">{{ $related->name }}</h6>
                            <p class="text-danger mb-2">Rp{{ number_format($related->price, 0, ',', '.') }}</p>
                            <a href="{{ route('products.show', $related->id) }}" class="btn btn-outline-primary btn-sm">Lihat</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection