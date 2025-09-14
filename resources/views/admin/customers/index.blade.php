@extends('admin.layouts.app')


@section('title', 'Kelola Pelanggan')


@push('styles')
<style>
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .status-active {
        background-color: #d4edda;
        color: #155724;
    }
    .status-inactive {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- Notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Judul -->
    <h2 class="fw-bold mb-4">Daftar Pelanggan</h2>

    <!-- Pencarian & Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.customers.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small">Cari Pelanggan</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0"
                                   placeholder="Nama atau email..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Diblokir</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Urutkan</label>
                        <select name="sort" class="form-select">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="most_orders" {{ request('sort') === 'most_orders' ? 'selected' : '' }}>Order Terbanyak</option>
                            <option value="highest_spent" {{ request('sort') === 'highest_spent' ? 'selected' : '' }}>Belanja Terbesar</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Pelanggan -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Total Order</th>
                            <th>Total Belanja</th>
                            <th>Bergabung</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td>#{{ $customer->id }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>
                                @if($customer->is_active)
                                    <span class="status-badge status-active">Aktif</span>
                                @else
                                    <span class="status-badge status-inactive">Diblokir</span>
                                @endif
                            </td>
                            <td>{{ $customer->orders_count ?? 0 }}</td>
                            <td>Rp {{ number_format($customer->orders_sum_total_price ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $customer->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.customers.show', $customer->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($customer->is_active)
                                    <form action="{{ route('admin.customers.ban', $customer->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Yakin ingin memblokir pelanggan ini?')">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.customers.unban', $customer->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success" 
                                                onclick="return confirm('Yakin ingin mengaktifkan pelanggan ini?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <img src="https://cdn-icons-png.flaticon.com/512/3474/3474360.png" 
                                     alt="No Customers" style="max-width: 100px; opacity: 0.5;" class="mb-3">
                                <div class="text-muted">Belum ada pelanggan</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($customers->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $customers->withQueryString()->links() }}
    </div>
    @endif

</div>
@endsection
