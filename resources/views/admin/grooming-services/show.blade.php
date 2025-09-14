@extends('admin.layouts.app')

@section('title', 'Detail Layanan Grooming')

@section('content')
<div class="container mt-4">
    <h2>Detail Layanan Grooming</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>Nama:</strong> {{ $service->name }}</p>
            <p><strong>Deskripsi:</strong> {{ $service->description }}</p>
            <p><strong>Harga:</strong> Rp {{ number_format($service->price, 0, ',', '.') }}</p>
            <p><strong>Durasi:</strong> {{ $service->duration_minutes }} menit</p>
            <p><strong>Jenis Hewan:</strong> {{ ucfirst($service->pet_type) }}</p>
            <p><strong>Ukuran:</strong> {{ $service->pet_size ? ucfirst($service->pet_size) : '-' }}</p>
            <p><strong>Status:</strong>
                @if($service->is_available)
                    <span class="badge bg-success">Tersedia</span>
                @else
                    <span class="badge bg-secondary">Tidak Tersedia</span>
                @endif
            </p>
            @if($service->image)
                <p><strong>Gambar:</strong></p>
                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="img-fluid" style="max-width: 300px;">
            @endif
            <a href="{{ route('admin.grooming-services.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
</div>
@endsection
