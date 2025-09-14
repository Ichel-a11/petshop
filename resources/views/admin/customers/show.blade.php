@extends('layouts.admin-new')

@section('title', 'Detail Pelanggan')
@section('page_title', 'Detail Pelanggan')

@section('content')
<div class="container-fluid">

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tombol kembali --}}
    <div class="mb-4">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-light border">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Profil Pelanggan -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Profil Pelanggan</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="d-inline-block p-3 rounded-circle bg-light mb-3">
                        <i class="fas fa-user-circle fa-4x text-primary"></i>
                    </div>
                    <h5 class="mb-1">{{ $customer->name }}</h5>
                    <p class="text-muted small mb-3">Customer ID: #{{ $customer->id }}</p>

                    {{-- Tombol Aktifkan/Nonaktifkan --}}
                    @if($customer->is_active)
                        <form action="{{ route('admin.customers.ban', $customer->id) }}" method="POST" class="d-inline">
                            @csrf @method('PUT')
                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                onclick="return confirm('Yakin ingin menonaktifkan pelanggan ini? Pelanggan tidak bisa login dan melakukan pembelian.')"
                                title="Nonaktifkan akun pelanggan">
                                <i class="fas fa-ban me-1"></i> Nonaktifkan
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.customers.unban', $customer->id) }}" method="POST" class="d-inline">
                            @csrf @method('PUT')
                            <button type="submit" class="btn btn-outline-success btn-sm"
                                onclick="return confirm('Yakin ingin mengaktifkan pelanggan ini?')"
                                title="Aktifkan kembali akun pelanggan">
                                <i class="fas fa-check me-1"></i> Aktifkan
                            </button>
                        </form>
                    @endif
                </div>

                <div class="col-md-8">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="text-muted w-25">Email</th>
                            <td>{{ $customer->email }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Bergabung</th>
                            <td>{{ $customer->created_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Status Akun</th>
                            <td>
                                @if($customer->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Diblokir</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Total Pesanan</th>
                            <td>{{ $customer->orders_count ?? 0 }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Total Pembelian</th>
                            <td><strong>Rp {{ number_format($customer->orders_sum_total_price ?? 0, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pesanan -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Pesanan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($customer->orders->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" 
                                         alt="No Orders" style="max-width: 100px; opacity: 0.4;" class="mb-2">
                                    <div class="text-muted small">Belum ada pesanan</div>
                                </td>
                            </tr>
                        @else
                            @foreach($customer->orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        @if($order->status === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($order->status === 'paid')
                                            <span class="badge bg-success">Dibayar</span>
                                        @elseif($order->status === 'cancelled')
                                            <span class="badge bg-danger">Dibatalkan</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>
                                    <td><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                                    <td>
                                        <a href="{{ route('admin.transactions.show', $order->id) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Lihat detail pesanan">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection