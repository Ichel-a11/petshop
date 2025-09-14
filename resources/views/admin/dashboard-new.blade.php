@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid px-4 py-3">

    <h2 class="mb-4 fw-bold text-dark">
        <i class="fas fa-tachometer-alt me-2 text-primary"></i> Dashboard Admin
    </h2>

    {{-- Statistik Cards --}}
    <div class="row g-4 mb-4">
        <!-- Total Pendapatan -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm hover-lift h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-wallet fa-2x text-success"></i>
                    </div>
                    <h6 class="text-muted mb-2">Total Pendapatan</h6>
                    <h4 class="fw-bold text-success mb-0">
                        Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm hover-lift h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-box-open fa-2x text-primary"></i>
                    </div>
                    <h6 class="text-muted mb-2">Total Produk</h6>
                    <h4 class="fw-bold text-primary mb-0">{{ $totalProduk ?? 0 }}</h4>
                </div>
            </div>
        </div>

        <!-- Total Pelanggan -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm hover-lift h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                    <h6 class="text-muted mb-2">Total Pelanggan</h6>
                    <h4 class="fw-bold text-info mb-0">{{ $totalPelanggan ?? 0 }}</h4>
                </div>
            </div>
        </div>

        <!-- Pesanan Pending -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm hover-lift h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                    <h6 class="text-muted mb-2">Pesanan Pending</h6>
                    <h4 class="fw-bold text-warning mb-0">{{ $pesananPending ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Transaksi Terbaru --}}
    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">
                <i class="fas fa-receipt me-2 text-dark"></i> Transaksi Terbaru
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 10%;">#ID</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiTerbaru as $trx)
                            <tr class="align-middle">
                                <td class="text-center fw-medium">#{{ $trx->id }}</td>
                                <td>{{ $trx->order?->customer_name ?? $trx->order?->user?->name ?? 'N/A' }}</td>
                                <td class="text-nowrap">
                                    <strong>Rp {{ number_format($trx->amount ?? 0, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <span class="badge px-3 py-2 rounded-pill bg-{{ 
                                        $trx->status === 'success' ? 'success' : 
                                        ($trx->status === 'pending' ? 'warning' : 'danger') 
                                    }}">
                                        {{ ucfirst($trx->status) }}
                                    </span>
                                </td>
                                <td class="text-nowrap">{{ $trx->created_at?->format('d M Y') ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.transactions.show', $trx->id) }}" 
                                       class="btn btn-sm btn-outline-primary px-3 py-2">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-receipt fa-2x mb-2 opacity-50"></i><br>
                                    <small>Belum ada transaksi</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pelanggan Terbaru --}}
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0">
                <i class="fas fa-user-friends me-2 text-dark"></i> Pelanggan Terbaru
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Tanggal Gabung</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pelangganTerbaru as $cust)
                            <tr class="align-middle">
                                <td class="fw-medium">{{ $cust->name }}</td>
                                <td>{{ $cust->email }}</td>
                                <td>{{ $cust->phone ?? '-' }}</td>
                                <td class="text-nowrap">{{ $cust->created_at?->format('d M Y') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-users fa-2x mb-2 opacity-50"></i><br>
                                    <small>Belum ada pelanggan baru</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@push('styles')
<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .card-header h5 i {
        font-size: 1.2em;
    }
    .table th {
        font-weight: 600;
        color: #495057;
    }
    .table td, .table th {
        vertical-align: middle;
    }
    .badge {
        font-weight: 600;
        font-size: 0.85em;
    }
    .text-nowrap {
        white-space: nowrap;
    }
    .opacity-50 {
        opacity: 0.5;
    }
</style>
@endpush

@push('scripts')
<script>
    // Animasi saat halaman load
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.hover-lift').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(10px)';
            setTimeout(() => {
                card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    });
</script>
@endpush
@endsection
