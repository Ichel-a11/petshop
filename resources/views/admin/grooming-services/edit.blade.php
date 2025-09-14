@extends('admin.layouts.app')

@section('title', 'Edit Layanan Grooming')

@section('content')
<div class="container mt-4">
    <h2>Edit Layanan Grooming</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.grooming-services.update', $groomingService->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama Layanan</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $groomingService->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3" required>{{ old('description', $groomingService->description) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', $groomingService->price) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Durasi (menit)</label>
                    <input type="number" name="duration_minutes" class="form-control" value="{{ old('duration_minutes', $groomingService->duration_minutes) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Hewan</label>
                    <select name="pet_type" class="form-select" required>
                        <option value="anjing" {{ old('pet_type', $groomingService->pet_type) == 'anjing' ? 'selected' : '' }}>Anjing</option>
                        <option value="kucing" {{ old('pet_type', $groomingService->pet_type) == 'kucing' ? 'selected' : '' }}>Kucing</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ukuran</label>
                    <select name="pet_size" class="form-select">
                        <option value="">-</option>
                        <option value="kecil" {{ old('pet_size', $groomingService->pet_size) == 'kecil' ? 'selected' : '' }}>Kecil</option>
                        <option value="sedang" {{ old('pet_size', $groomingService->pet_size) == 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="besar" {{ old('pet_size', $groomingService->pet_size) == 'besar' ? 'selected' : '' }}>Besar</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gambar (opsional)</label>
                    <input type="file" name="image" class="form-control">
                    @if($groomingService->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $groomingService->image) }}" alt="Gambar" width="100">
                        </div>
                    @endif
                </div>
                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('admin.grooming-services.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection