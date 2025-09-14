@extends('layouts.customer')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-1">Detail Pesanan #{{ $order->id }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customer.orders.index') }}" class="text-decoration-none">Pesanan Saya</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Pesanan</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Item Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td style="width: 100px;">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/produk/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/no-image.png') }}" alt="No Image" class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->product)
                                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                                            <small class="text-muted">Rp {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}</small>
                                        @else
                                            <h6 class="mb-0 text-muted">Produk tidak tersedia</h6>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            ID Pesanan
                            <span>#{{ $order->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Tanggal Pesanan
                            <span>{{ $order->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Status Pembayaran
                            <span>
                                @if($order->transaction)
                                    @if($order->transaction->status == 'paid')
                                        <span class="badge bg-success">Lunas</span>
                                    @elseif($order->transaction->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($order->transaction->status) }}</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Belum ada transaksi</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 fw-bold">
                            Total
                            <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </li>
                    </ul>

                    @if($order->transaction && $order->transaction->status == 'pending' && optional($order->transaction)->payment_url)
                        <div class="d-grid mt-4">
                            <a href="{{ $order->transaction->payment_url }}" class="btn btn-primary" target="_blank">
                                <i class="fas fa-credit-card me-2"></i>Lanjutkan Pembayaran
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white border-0">
                    <h6 class="mb-2">Alamat Pengiriman</h6>
                    <p class="text-muted mb-0">
                        {{ $order->address_line_1 }}<br>
                        @if($order->address_line_2)
                            {{ $order->address_line_2 }}<br>
                        @endif
                        {{ $order->city }}, {{ $order->province }} {{ $order->postal_code }}<br>
                        {{ $order->country }}
                    </p>
                </div>
            </div>
             <div class="mt-3">
                <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Pesanan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
