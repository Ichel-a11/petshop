@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Edit Transaksi #{{ $transaction->id ?? 'N/A' }}</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Kode Transaksi --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Kode Transaksi</label>
                    <input type="text" class="form-control" 
                           value="{{ $transaction->order->order_id ?? $transaction->order_id ?? $transaction->id }}" 
                           disabled>
                </div>

                {{-- Nama Pemesan --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Pemesan</label>
                    <input type="text" class="form-control" 
                           value="{{ $transaction->order->customer_name ?? optional($transaction->order->user)->name ?? '-' }}" 
                           disabled>
                </div>

                {{-- Total --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Total</label>
                    <input type="text" class="form-control" 
                           value="Rp{{ number_format($transaction->order->total_price ?? $transaction->total_price ?? 0, 0, ',', '.') }}" 
                           disabled>
                </div>

                {{-- Status Transaksi --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Status Transaksi</label>
                    <select name="status" class="form-select">
                        @foreach(['pending' => 'Pending', 'success' => 'Berhasil', 'failed' => 'Gagal'] as $value => $label)
                            <option value="{{ $value }}" {{ $transaction->status === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
