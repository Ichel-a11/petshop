@extends('layouts.customer')

@section('content')
<div class="container py-4">

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Form Booking Grooming: {{ $service->name }}</h2>
            <p class="text-muted mb-3">
                Silakan pilih tanggal, jam, dan lengkapi informasi hewan Anda.
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('customer.grooming.book', $service->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <select name="booking_date" class="form-select" required>
                                @foreach($timeSlots as $date => $slots)
                                    <option value="{{ $date }}">{{ $date }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jam</label>
                            <select name="booking_time" class="form-select" required>
                                @foreach($timeSlots as $date => $slots)
                                    @foreach($slots as $slot)
                                        <option value="{{ $slot }}">{{ $slot }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Hewan</label>
                            <input type="text" name="pet_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ukuran Hewan</label>
                            <select name="pet_size" class="form-select" required>
                                <option value="small">Small</option>
                                <option value="medium">Medium</option>
                                <option value="large">Large</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customer.grooming.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i> Booking Sekarang
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i> Info Penting</h5>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-check text-success me-2"></i> Pastikan memilih tanggal H-1 minimal.</li>
                        <li><i class="fas fa-check text-success me-2"></i> Gunakan ukuran hewan sesuai kenyataan.</li>
                        <li><i class="fas fa-check text-success me-2"></i> Catatan opsional untuk kebutuhan khusus hewan.</li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <a href="{{ route('customer.grooming.my-bookings') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-list me-2"></i> Lihat Booking Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.card-body form .form-label {
    font-weight: 500;
}
</style>
@endpush
@endsection
