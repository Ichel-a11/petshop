@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">✏️ Edit Profil</h4>
                </div>
                <div class="card-body">

                    {{-- ✅ NOTIFIKASI SUKSES DIPERBARUI --}}
                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>{{ session('status') }}</strong>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="name" class="form-label">👤 Nama</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">📧 Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">🔒 Password Baru (Opsional)</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah password.</small>
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">🔁 Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>

                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                <i class="fas fa-trash-alt me-1"></i> Hapus Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Konfirmasi Hapus Akun -->
            <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">⚠️ Konfirmasi Hapus Akun</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-danger"><strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan. Semua data akun Anda akan dihapus permanen.</p>
                            <form method="POST" action="{{ route('profile.destroy') }}" class="mt-3">
                                @csrf
                                @method('DELETE')
                                <div class="mb-3">
                                    <label for="password" class="form-label">Masukkan Password Anda untuk konfirmasi</label>
                                    <input type="password" class="form-control" name="password" required>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash-alt me-1"></i> Ya, Hapus Akun
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i> Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .alert {
        border-radius: 8px;
    }
    .btn i {
        font-size: 0.9em;
    }
</style>
@endpush