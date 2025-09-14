@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Transaksi Online</h2>

    <!-- Info Panel -->
    <div class="alert alert-info d-flex align-items-center mb-4">
        <i class="fas fa-info-circle me-2"></i>
        <div>
            Semua transaksi di sini diproses otomatis melalui <strong>Midtrans</strong>. 
            Tidak perlu verifikasi manual.
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Status Transaksi</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach(['pending', 'success', 'failed'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="payment_method" class="form-select">
                        <option value="">Semua Metode</option>
                        @foreach(['gopay', 'bca_va', 'bni_va', 'mandiri_va', 'credit_card', 'shopeepay', 'alfamart'] as $method)
                            <option value="{{ $method }}" {{ request('payment_method') == $method ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_va', ' Virtual Account', $method)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Order ID</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                    <tr>
                        <td>
                            <strong>{{ $tx->order_id ?? '#' . $tx->id }}</strong><br>
                            <small class="text-success"><i class="fas fa-credit-card"></i> Online</small>
                        </td>
                        <td>
                            {{ optional($tx->order->user)->name ?? '-' }}<br>
                            <small>{{ optional($tx->order->user)->email ?? '' }}</small>
                        </td>
                        <td>
                            <strong>{{ $tx->formatted_amount }}</strong> {{-- ✅ Diperbaiki: pakai formatted_amount --}}
                        </td>
                        <td>
                            <span class="badge bg-primary text-white" style="font-size: 0.85em;">
                                {{ ucfirst(str_replace(['_va', 'shopeepay'], [' Virtual Account', 'ShopeePay'], $tx->payment_method)) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $tx->status === 'success' ? 'success' : ($tx->status === 'failed' ? 'danger' : 'warning') }}">
                                {{ ucfirst($tx->status) }}
                            </span>
                            @if(optional($tx->order)->payment_status === 'paid')
                                <br>
                                <small class="text-success">Lunas</small>
                            @endif
                        </td>
                        <td>{{ $tx->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.transactions.show', $tx->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-receipt me-2"></i>
                            Tidak ada transaksi online
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $transactions->links() }}
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }
    .table th:first-child,
    .table td:first-child {
        text-align: left;
    }
</style>
@endpush