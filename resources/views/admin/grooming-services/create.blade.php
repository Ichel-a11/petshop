@extends('admin.layouts.app')

@section('title', 'Tambah Layanan Grooming')

@section('content')
<div class="container mt-4">
    <h2>Tambah Layanan Grooming</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.grooming-services.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Layanan</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="price" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Durasi (menit)</label>
                    <input type="number" name="duration_minutes" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Hewan</label>
                    <select name="pet_type" class="form-select" required>
                        <option value="anjing">Anjing</option>
                        <option value="kucing">Kucing</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ukuran</label>
                    <select name="pet_size" class="form-select">
                        <option value="">-</option>
                        <option value="kecil">Kecil</option>
                        <option value="sedang">Sedang</option>
                        <option value="besar">Besar</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gambar (opsional)</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('admin.grooming-services.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
