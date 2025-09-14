@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Detail Transaksi #{{ $transaction->id ?? 'N/A' }}</h2>

    <!-- Info Otomatis -->
    <div class="alert alert-success d-flex align-items-center mb-4">
        <i class="fas fa-check-circle me-2"></i>
        <div>
            <strong>Pembayaran Otomatis</strong><br>
            Transaksi ini diproses secara otomatis oleh <strong>Midtrans</strong>. Tidak perlu verifikasi manual.
        </div>
    </div>

    @if($transaction->order)
        {{-- ================= INFO PELANGGAN ================= --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-user me-1"></i> Informasi Pelanggan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama:</strong> {{ $transaction->order->customer_name ?? optional($transaction->order->user)->name ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $transaction->order->customer_email ?? optional($transaction->order->user)->email ?? 'N/A' }}</p>
                        <p><strong>Telepon:</strong> {{ $transaction->order->customer_phone ?? $transaction->order->no_hp ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Alamat:</strong> {{ $transaction->order->alamat ?? 'N/A' }}</p>
                        <p><strong>No HP:</strong> {{ $transaction->order->no_hp ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= INFO TRANSAKSI ================= --}}
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-receipt me-1"></i> Detail Transaksi
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Order ID:</strong> {{ $transaction->order->order_id ?? '#' . $transaction->order->id }}</p>
                        <p><strong>Total Pesanan:</strong> 
                            @if($transaction->order->total_amount && $transaction->order->total_amount > 0)
                                Rp{{ number_format($transaction->order->total_amount, 0, ',', '.') }}
                            @else
                                <span class="text-muted">Tidak tersedia</span>
                            @endif
                        </p>
                        <p><strong>Total Dibayar:</strong> <strong class="text-success">{{ $transaction->formatted_amount }}</strong></p>
                        <p><strong>Status Pesanan:</strong> 
                            <span class="badge bg-{{ 
                                $transaction->order->status === 'completed' ? 'success' : 
                                ($transaction->order->status === 'cancelled' ? 'danger' : 
                                ($transaction->order->status === 'processing' ? 'primary' : 'warning')) 
                            }}">
                                {{ ucfirst($transaction->order->status ?? 'unknown') }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Metode Pembayaran:</strong> 
                            <span class="badge bg-info text-white">
                                {{ ucfirst(str_replace(['_va', 'shopeepay'], [' Virtual Account', 'ShopeePay'], $transaction->payment_method ?? 'Unknown')) }}
                            </span>
                        </p>
                        <p><strong>Status Pembayaran:</strong> 
                            <span class="badge bg-{{ $transaction->status === 'success' ? 'success' : ($transaction->status === 'failed' ? 'danger' : 'warning') }}">
                                {{ ucfirst($transaction->status ?? 'unknown') }}
                            </span>
                        </p>
                        <p class="mt-2 text-muted">
                            <small>💳 Diproses oleh Midtrans</small>
                        </p>
                    </div>
                </div>

                {{-- Link ke Midtrans --}}
                @if($transaction->order->payment_proof && str_contains($transaction->order->payment_proof, 'midtrans.com'))
                    <div class="mt-4">
                        <a href="{{ $transaction->order->payment_proof }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i> Lihat di Midtrans
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- ================= ITEM YANG DIBELI ================= --}}
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <i class="fas fa-box me-1"></i> Item yang Dibeli
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Tipe</th>
                                <th>Nama</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaction->order->orderItems as $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $item->item_type === 'product' ? 'primary' : 'info' }}">
                                            {{ ucfirst($item->item_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada item</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="4" class="text-end">Total Pesanan:</td>
                                <td>
                                    @if($transaction->order->total_amount && $transaction->order->total_amount > 0)
                                        Rp{{ number_format($transaction->order->total_amount, 0, ',', '.') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr class="fw-bold text-success">
                                <td colspan="4" class="text-end">Total Dibayar:</td>
                                <td>{{ $transaction->formatted_amount }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- ================= UPDATE STATUS TRANSAKSI ================= --}}
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <i class="fas fa-edit me-1"></i> Update Status Transaksi
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.transactions.update', $transaction->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <select name="status" class="form-select">
                            @foreach(['pending', 'success', 'failed'] as $status)
                                <option value="{{ $status }}" {{ $transaction->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i> Update Status
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            <strong>⚠️ Data Order Tidak Ditemukan!</strong> Transaksi ini tidak terhubung ke pesanan.
        </div>
    @endif

    <div class="text-center mb-4">
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-header i {
        font-size: 1.1em;
    }
    .badge {
        font-weight: 600;
    }
    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }
    .table th:first-child,
    .table td:first-child {
        text-align: left;
    }
    .alert {
        border-radius: 8px;
    }
    .text-success {
        font-weight: 600;
    }
</style>
@endpush