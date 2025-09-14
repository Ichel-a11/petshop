@extends('layouts.admin-new')

@section('title', 'Kelola Ulasan')
@section('page_title', 'Ulasan Produk')

@section('content')
<div class="container-fluid">

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5><i class="fas fa-star me-2"></i>Daftar Ulasan</h5>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Pelanggan</th>
                        <th>Produk</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>{{ $review->user->name }}</td>
                            <td>{{ $review->product->name ?? 'Produk Dihapus' }}</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }} text-warning"></i>
                                @endfor
                                ({{ $review->rating }})
                            </td>
                            <td>{{ Str::limit($review->comment, 100) }}</td>
                            <td>{{ $review->created_at->format('d M Y') }}</td>
                            <td>
                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Yakin hapus ulasan ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-inbox text-muted" style="font-size: 48px;"></i>
                                <p class="text-muted mb-0">Belum ada ulasan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection