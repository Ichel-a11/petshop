@extends('layouts.customer')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-1">Pesanan Saya</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pesanan Saya</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($orders->isEmpty())
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076423.png" alt="No Orders" class="img-fluid mb-4" style="max-width: 150px; opacity: 0.5;">
                    <h3 class="text-muted">Anda Belum Memiliki Pesanan</h3>
                    <p class="text-muted mb-4">Mulai belanja sekarang untuk melihat pesanan Anda di sini.</p>
                    <a href="{{ route('customer.catalog') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">ID Pesanan</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Total</th>
                                <th scope="col">Status Pembayaran</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
