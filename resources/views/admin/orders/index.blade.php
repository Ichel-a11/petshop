@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Order</h2>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label>Status Pesanan</label>
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        @foreach(['pending', 'processing', 'shipped', 'completed', 'cancelled', 'waiting_verification'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Status Pembayaran</label>
                    <select name="payment_status" class="form-control">
                        <option value="">Semua Status</option>
                        @foreach(['unpaid', 'paid', 'failed', 'refunded'] as $status)
                            <option value="{{ $status }}" {{ request('payment_status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>
                        <strong>{{ $order->order_id ?? '#' . $order->id }}</strong>
                        @if(in_array($order->payment_method, ['gopay', 'bca_va', 'bni_va', 'mandiri_va', 'credit_card', 'shopeepay', 'alfamart']))
                            <br><small class="text-success">Otomatis</small>
                        @endif
                    </td>
                    <td>{{ $order->customer_name }}</td>
                    <td>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge bg-secondary text-white">
                            {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ 
                            $order->status == 'completed' ? 'success' : 
                            ($order->status == 'cancelled' ? 'danger' : 
                            ($order->status == 'processing' ? 'primary' : 'warning')) 
                        }}">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ 
                            $order->payment_status == 'paid' ? 'success' : 'danger' 
                        }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">
                            Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada order</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $orders->links() }}
</div>
@endsection