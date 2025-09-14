@extends('admin.layouts.app')

@section('title', 'Kelola Layanan Grooming')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Daftar Layanan Grooming</h2>
        <a href="{{ route('admin.grooming-services.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Layanan
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Layanan</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        <th>Durasi</th>
                        <th>Jenis Hewan</th>
                        <th>Ukuran</th>
                        <th>Status</th>
                        <th width="160px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $service->name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($service->description, 40) }}</td>
                            <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                            <td>{{ $service->duration_minutes }} menit</td>
                            <td>{{ ucfirst($service->pet_type) }}</td>
                            <td>{{ $service->pet_size ? ucfirst($service->pet_size) : '-' }}</td>
                            <td class="text-center">
                                @if($service->is_available)
                                    <span class="badge bg-success">Tersedia</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Tersedia</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.grooming-services.show', $service->id) }}" class="btn btn-info btn-sm" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.grooming-services.edit', $service->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.grooming-services.destroy', $service->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Belum ada layanan grooming</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $services->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
