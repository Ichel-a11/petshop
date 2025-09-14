@extends('customer.layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Keranjang Belanja Anda</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($cartItems->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Keranjang Anda kosong. 
            <a href="{{ route('customer.catalog') }}">Belanja sekarang</a>.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Item</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($cartItems as $item)
                        @php
                            if($item->type == 'product' && $item->product) {
                                $name = $item->product->name ?? $item->product->nama ?? 'Produk';
                                $desc = $item->product->description ?? $item->product->deskripsi ?? '-';
                                $price = $item->price ?? 0;
                                $quantity = $item->quantity;
                                $subtotal = $price * $quantity;
                                $image = $item->product->image ?? $item->product->gambar ?? null;
                                $stock = $item->product->stock ?? 0;

                            } elseif($item->type == 'grooming' && $item->groomingBooking) {
                                $name = 'Grooming: ' . ($item->groomingBooking->service->name ?? 'Layanan Grooming');
                                $desc = 'Hewan: ' . $item->groomingBooking->pet_name . ' (' . ucfirst($item->groomingBooking->pet_size) . ')' . 
                                       ' | Tanggal: ' . \Carbon\Carbon::parse($item->groomingBooking->booking_time)->format('d M Y, H:i');
                                $price = $item->price ?? $item->groomingBooking->total_price ?? 0;
                                $quantity = 1;
                                $subtotal = $price;
                                $image = null;
                                $stock = 99;

                            } else {
                                $name = 'Item tidak tersedia';
                                $desc = '-';
                                $price = 0;
                                $quantity = 0;
                                $subtotal = 0;
                                $image = null;
                                $stock = 0;
                            }

                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->type == 'product' && $image)
                                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $name }}" width="60" class="me-3 rounded" style="object-fit: cover;">
                                    @elseif($item->type == 'grooming')
                                        <div class="me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background-color: #e9ecef; border-radius: 8px;">
                                            <i class="fas fa-bath fa-lg text-info"></i>
                                        </div>
                                    @else
                                        <div class="me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; background-color: #e9ecef; border-radius: 8px;">
                                            <i class="fas fa-question fa-lg text-secondary"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $name }}</strong><br>
                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($desc, 80) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>Rp {{ number_format($price, 0, ',', '.') }}</td>
                            <td>
                                @if($item->type == 'product' && $item->product)
                                    <form action="{{ route('customer.cart.update-quantity', $item->id) }}" method="POST" style="display: flex; align-items: center; gap: 6px; max-width: 120px;">
                                        @csrf
                                        @method('POST')

                                        <button type="submit" name="quantity" value="{{ $quantity - 1 }}"
                                                class="btn btn-sm btn-outline-secondary {{ $quantity <= 1 ? 'disabled' : '' }}"
                                                {{ $quantity <= 1 ? 'disabled' : '' }}>
                                            −
                                        </button>

                                        <input type="number" 
                                               name="quantity" 
                                               value="{{ $quantity }}" 
                                               min="1" 
                                               max="99" 
                                               readonly
                                               style="width: 50px; text-align: center; padding: 0.25rem; font-size: 0.9rem;"
                                               class="form-control form-control-sm">

                                        <button type="submit" name="quantity" value="{{ $quantity + 1 }}"
                                                class="btn btn-sm btn-outline-secondary"
                                                {{ ($quantity + 1) > $stock ? 'disabled' : '' }}>
                                            +
                                        </button>
                                    </form>

                                    @if(($quantity + 1) > $stock && $stock > 0)
                                        <small class="text-danger">Stok hanya: {{ $stock }}</small>
                                    @endif
                                @else
                                    <span>{{ $quantity }}</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('customer.cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="table-light">
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td colspan="2"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('customer.catalog') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Lanjut Belanja
            </a>
            <a href="{{ route('customer.checkout') }}" class="btn btn-success">
                <i class="fas fa-shopping-cart me-1"></i> Checkout Sekarang
            </a>
        </div>
    @endif
</div>
@endsection