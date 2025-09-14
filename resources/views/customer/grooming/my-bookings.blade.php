@extends('layouts.customer')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Booking Grooming Saya</h2>
            <p class="text-muted">Daftar semua booking grooming Anda</p>
        </div>
    </div>

    @if($bookings->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Anda belum memiliki booking grooming. 
            <a href="{{ route('customer.grooming.index') }}">Booking sekarang</a>
        </div>
    @else
        <div class="row">
            @foreach($bookings as $booking)
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title mb-1">{{ $booking->service->name ?? 'Layanan tidak tersedia' }}</h5>
                                    <span class="badge bg-{{ 
                                        $booking->status == 'approved' ? 'success' : 
                                        ($booking->status == 'pending' ? 'warning' : 
                                        ($booking->status == 'cancelled' ? 'danger' : 'secondary')) 
                                    }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                                <small class="text-muted">#{{ $booking->id }}</small>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Tanggal & Jam</small>
                                    <small class="fw-bold">{{ \Carbon\Carbon::parse($booking->booking_time)->format('d M Y, H:i') }}</small>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Hewan</small>
                                    <small>{{ $booking->pet_name }} ({{ ucfirst($booking->pet_type ?? $booking->pet_size) }})</small>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Harga</small>
                                    <small class="fw-bold text-success">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</small>
                                </div>
                            </div>

                            @if($booking->notes)
                                <div class="mb-3">
                                    <small class="text-muted">Catatan:</small>
                                    <p class="mb-0">{{ $booking->notes }}</p>
                                </div>
                            @endif

                            <!-- Tombol Aksi -->
                            <div class="d-flex gap-2 flex-wrap">
                                @if($booking->status == 'approved')
                                    @php
                                        $inCart = App\Models\Cart::where('user_id', Auth::id())
                                                    ->where('grooming_booking_id', $booking->id)
                                                    ->where('type', 'grooming')
                                                    ->exists();
                                    @endphp
                                    
                                    @if(!$inCart)
                                        <form action="{{ route('customer.grooming.addToCart', $booking->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-cart-plus me-1"></i> Tambah ke Keranjang
                                            </button>
                                        </form>
                                    @else
                                        <span class="btn btn-sm btn-success disabled">
                                            <i class="fas fa-check me-1"></i> Sudah di Keranjang
                                        </span>
                                    @endif

                                @elseif($booking->status == 'pending')
                                    <form action="{{ route('customer.grooming.cancel', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Batalkan booking ini?')">
                                            <i class="fas fa-times me-1"></i> Batalkan
                                        </button>
                                    </form>

                                @elseif($booking->status == 'cancelled')
                                    <!-- 🔥 Tombol Hapus untuk Booking yang Sudah Dibatalkan -->
                                    <form action="{{ route('customer.grooming.deleteBooking', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Yakin ingin menghapus booking ini? Ini akan menghapusnya secara permanen.')">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                                
                                <a href="{{ route('customer.grooming.my-bookings') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection