@extends('layouts.customer')

@section('title', 'Checkout')
@section('content')
<div class="container my-5">
    <h2 class="mb-4">Checkout</h2>

    <div class="card p-4 shadow-sm">
        <table class="table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>Rp{{ number_format($item->product->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4 class="text-end">Total: <strong>Rp{{ number_format($totalAmount, 0, ',', '.') }}</strong></h4>

        <div class="text-end mt-4">
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg">Lanjutkan Pembayaran</button>
            </form>
        </div>
    </div>
</div>
@endsection
