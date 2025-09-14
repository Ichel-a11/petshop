@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">Checkout</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @php $total = 0; @endphp

    @if($cartItems->isEmpty() && $bookings->isEmpty())
        <div class="alert alert-info">
            Keranjang & Booking Grooming kosong. 
            <a href="{{ route('customer.catalog') }}">Belanja sekarang</a>
        </div>
    @else
    <div class="row">
        <!-- Detail Produk & Grooming -->
        <div class="col-md-8">

            {{-- Produk --}}
            @if(!$cartItems->isEmpty())
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Detail Produk</div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                        @if($item->product)
                        <div class="d-flex mb-3 align-items-center border-bottom pb-2">
                            <img src="{{ asset('storage/' . ($item->product->image ?? $item->product->gambar ?? '')) }}" 
                                 alt="" class="img-thumbnail me-3" style="width:100px;height:100px;object-fit:cover;">
                            <div class="flex-grow-1">
                                <h6>{{ $item->product->name ?? $item->product->nama }}</h6>
                                <p class="mb-1">Harga: Rp {{ number_format($item->product->price ?? $item->product->harga, 0, ',', '.') }}</p>
                                <p class="mb-0">Jumlah: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-end">
                                <strong class="text-success">
                                    Rp {{ number_format(($item->product->price ?? $item->product->harga) * $item->quantity, 0, ',', '.') }}
                                </strong>
                            </div>
                        </div>
                        @php $total += ($item->product->price ?? $item->product->harga) * $item->quantity; @endphp
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Grooming --}}
            @if(!$bookings->isEmpty())
            <div class="card mb-4">
                <div class="card-header bg-info text-white">Detail Grooming</div>
                <div class="card-body">
                    @foreach($bookings as $booking)
                        <div class="d-flex mb-3 align-items-center border-bottom pb-2">
                            <div class="flex-grow-1">
                                <h6>Grooming {{ $booking->pet_name ?? 'Hewan' }}</h6>
                                <p class="mb-1">
                                    <strong>Layanan:</strong> 
                                    {{ $booking->service->name ?? $booking->service->nama_layanan ?? 'Layanan Grooming' }}
                                </p>
                                <p class="mb-1">
                                    <strong>Tanggal:</strong> 
                                    {{ $booking->booking_time ? $booking->booking_time->format('d M Y H:i') : '-' }}
                                </p>
                                <p class="mb-1">
                                    <strong>Ukuran Pet:</strong> 
                                    {{ ucfirst($booking->pet_size ?? '-') }}
                                </p>
                                <p class="mb-0">
                                    <strong>Jenis Pet:</strong> 
                                    {{ ucfirst($booking->pet_type ?? '-') }}
                                </p>
                            </div>
                            <div class="text-end">
                                <strong class="text-success">
                                    Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}
                                </strong>
                            </div>
                        </div>
                        @php $total += $booking->total_price ?? 0; @endphp
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        <!-- Ringkasan & Form Checkout -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-warning">Ringkasan Pembayaran</div>
                <div class="card-body">
                    <p class="d-flex justify-content-between">
                        <span>Total Belanja</span>
                        <strong>Rp {{ number_format($total,0,',','.') }}</strong>
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Data Pengiriman</div>
                <div class="card-body">
                    <form action="{{ route('customer.checkout.process') }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Alamat Pengiriman</label>
                            <textarea name="alamat" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-2">
                            <label>Nomor HP</label>
                            <input type="text" name="no_hp" class="form-control" required>
                        </div>

                        <!-- Hanya Midtrans Online Payment -->
                        <input type="hidden" name="payment_method" value="midtrans">

                        <button type="submit" class="btn btn-success w-100">
                            Proses Checkout & Bayar Online
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection