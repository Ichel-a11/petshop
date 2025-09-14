@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Detail Produk</h4>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm float-end">Kembali</a>
        </div>
        <div class="card-body">
            <div class="row">
                @if($product->image)
                    <div class="col-md-4">
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
                    </div>
                @endif
                <div class="col-md-8">
                    <h5>Nama Produk:</h5>
                    <p>{{ $product->name }}</p>

                    <h5>Harga:</h5>
                    <p>Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                    <h5>Deskripsi:</h5>
                    <p>{{ $product->description }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
