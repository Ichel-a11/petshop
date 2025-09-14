@extends('layouts.customer')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container-fluid py-4">

   

    <!-- Daftar Layanan Grooming -->
    <div class="row" id="services">
        <div class="col-12 mb-4">
            <h3 class="mb-4">
                <i class="fas fa-dog me-2"></i> Daftar Layanan Grooming
            </h3>

            @forelse($services as $petType => $serviceGroup)
                <h4 class="fw-bold mt-4 mb-3 text-primary">{{ ucfirst($petType) }}</h4>
                <div class="row g-4">
                    @foreach($serviceGroup as $service)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm service-card">
                                @if($service->image)
                                    <img src="{{ asset('storage/' . $service->image) }}" 
                                         class="card-img-top" 
                                         alt="{{ $service->name }}" 
                                         style="height:200px; object-fit:cover;">
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h4 class="card-title mb-3">{{ $service->name }}</h4>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="text-primary mb-0">{{ number_format($service->price, 0, ',', '.') }}</h5>
                                        <span class="badge bg-info">
                                            <i class="far fa-clock me-1"></i>{{ $service->duration_minutes }} menit
                                        </span>
                                    </div>
                                    <p class="card-text text-muted mb-4">{{ Str::limit($service->description, 80) }}</p>

                                    {{-- Tombol GroomingBooking tiap layanan --}}
                                    <a href="{{ route('customer.grooming.bookingForm', $service->id) }}" 
                                       class="btn btn-primary w-100 mt-auto">
                                        <i class="fas fa-calendar-plus me-2"></i> GroomingBooking Layanan Ini
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="alert alert-warning text-center">
                    Belum ada layanan grooming tersedia saat ini.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Info & FAQ -->
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fas fa-star text-warning me-2"></i> Mengapa Memilih Kami?
                    </h4>
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-primary bg-opacity-10 p-2 rounded-circle">
                                <i class="fas fa-user-md text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <h5>Groomer Profesional</h5>
                            <p class="text-muted mb-0">Ditangani oleh groomer berpengalaman dan tersertifikasi.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-success bg-opacity-10 p-2 rounded-circle">
                                <i class="fas fa-pump-medical text-success"></i>
                            </div>
                        </div>
                        <div>
                            <h5>Peralatan Steril</h5>
                            <p class="text-muted mb-0">Menggunakan peralatan yang selalu dijaga kebersihannya.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-info bg-opacity-10 p-2 rounded-circle">
                                <i class="fas fa-heart text-info"></i>
                            </div>
                        </div>
                        <div>
                            <h5>Pelayanan Terbaik</h5>
                            <p class="text-muted mb-0">Memberikan pelayanan terbaik untuk hewan kesayangan Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h4 class="card-title mb-4">
                        <i class="fas fa-question-circle text-info me-2"></i> FAQ
                    </h4>
                    <div class="accordion" id="faqAccordion">
                        @foreach([
                            'Berapa lama proses grooming?' => 'Waktu grooming bervariasi tergantung jenis layanan dan kondisi hewan, rata-rata 1-2 jam.',
                            'Apakah bisa GroomingBooking di hari yang sama?' => 'Untuk memberikan layanan terbaik, GroomingBooking harus dilakukan minimal H-1.',
                            'Bagaimana jika ingin membatalkan GroomingBooking?' => 'Pembatalan dapat dilakukan maksimal 24 jam sebelum jadwal grooming.'
                        ] as $question => $answer)
                            <div class="accordion-item border-0 mb-3">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-light" type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#{{ Str::slug($question) }}">
                                        {{ $question }}
                                    </button>
                                </h2>
                                <div id="{{ Str::slug($question) }}" 
                                     class="accordion-collapse collapse" 
                                     data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted">{{ $answer }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.service-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}
.accordion-button:not(.collapsed) {
    background-color: var(--bs-primary);
    color: white;
}
.accordion-button:focus {
    box-shadow: none;
}
</style>
@endpush
@endsection
