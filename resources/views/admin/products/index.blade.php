@extends('admin.layouts.app')

@section('title', 'Manajemen Produk')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Manajemen Produk</h1>

    {{-- Tombol Tambah & Filter --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            + Tambah Produk
        </a>

        {{-- Form Search & Filter --}}
        <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex gap-2">
            {{-- Filter kategori --}}
            <select name="category" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" 
                        {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            {{-- Search --}}
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="form-control" placeholder="Cari produk...">

            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>
    </div>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabel Produk --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" 
                                         alt="{{ $product->name }}" width="70" class="rounded">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category ? $product->category->name : '-' }}</td>
                            <td>Rp {{ number_format($product->price,0,',','.') }}</td>
                            <td>
                                @if($product->stock > 3)
                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                @elseif($product->stock > 0 && $product->stock <= 3)
                                    <span class="badge bg-warning text-dark">{{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </td>
                            <td>{{ $product->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                   class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada produk</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
