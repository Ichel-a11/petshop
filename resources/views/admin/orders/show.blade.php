@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-3">Detail Order #{{ $order->id }}</h2>

    {{-- ================= INFO CUSTOMER ================= --}}
    <div class="card mb-4">
        <div class="card-header">Data Customer</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                    <p><strong>Telepon:</strong> {{ $order->customer_phone }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Alamat:</strong> {{ $order->alamat }}</p>
                    <p><strong>No HP:</strong> {{ $order->no_hp }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= INFO ORDER ================= --}}
    <div class="card mb-4">
        <div class="card-header">Informasi Order</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Order ID:</strong> {{ $order->order_id ?? '#' . $order->id }}</p>
                    <p><strong>Total:</strong> Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ 
                            $order->status == 'completed' ? 'success' : 
                            ($order->status == 'cancelled' ? 'danger' : 
                            ($order->status == 'processing' ? 'primary' : 'warning')) 
                        }}">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Metode Pembayaran:</strong> 
                        <span class="badge bg-info text-white">
                            {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                        </span>
                    </p>
                    <p><strong>Status Pembayaran:</strong> 
                        <span class="badge bg-success">Lunas</span>
                    </p>
                    <p class="text-success"><strong>💳 Dibayar Otomatis via Midtrans</strong></p>
                </div>
            </div>

            {{-- Link ke Midtrans Dashboard --}}
            @if($order->payment_proof && str_contains($order->payment_proof, 'midtrans.com'))
                <div class="mt-3">
                    <a href="{{ $order->payment_proof }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        📊 Lihat di Dashboard Midtrans
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- ================= ITEM ORDER ================= --}}
    <div class="card mb-4">
        <div class="card-header">Detail Item</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tipe</th>
                            <th>Nama Item</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->items as $item)
                            <tr>
                                <td>
                                    <span class="badge bg-{{ $item->item_type == 'product' ? 'primary' : 'info' }}">
                                        {{ ucfirst($item->item_type) }}
                                    </span>
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada item</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Total:</th>
                            <th>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- ================= UPDATE STATUS PESANAN (Hanya ini yang tersisa) ================= --}}
    <div class="card mb-4">
        <div class="card-header">Update Status Pesanan</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}">
                @csrf
                <div class="mb-3">
                    <select name="status" class="form-control">
                        @foreach(['pending', 'processing', 'shipped', 'completed', 'cancelled'] as $status)
                            <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Status</button>
            </form>
        </div>
    </div>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection