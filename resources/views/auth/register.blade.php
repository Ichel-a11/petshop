@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="d-flex min-vh-100">
    {{-- Kolom kiri (form register) --}}
    <div class="d-flex flex-column justify-content-center align-items-center w-50 bg-white px-5">

        {{-- Logo --}}
        <div class="text-center mb-4">
            <img src="{{ asset('images/pablo.jpg') }}" alt="Logo" style="width: 60px;">
            <h3 class="fw-bold mt-3 text-danger">Pet Care</h3>
            <p class="text-muted">Buat akun baru untuk melanjutkan</p>
        </div>

        {{-- Notifikasi Error --}}
        @if ($errors->any())
            <div class="alert alert-danger w-100">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Register --}}
        <form method="POST" action="{{ route('register') }}" class="w-100" style="max-width: 320px;">
            @csrf

            {{-- Nama --}}
            <div class="mb-3">
                <input type="text" name="name" id="name"
                    class="form-control rounded-pill p-2"
                    value="{{ old('name') }}" required autofocus
                    placeholder="Nama Lengkap">
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <input type="email" name="email" id="email"
                    class="form-control rounded-pill p-2"
                    value="{{ old('email') }}" required
                    placeholder="Email">
            </div>

            {{-- Password --}}
            <div class="mb-3 position-relative">
                <input type="password" name="password" id="password"
                    class="form-control rounded-pill p-2 pe-5"
                    required placeholder="Password">

                {{-- Toggle Password --}}
                <span class="position-absolute top-50 end-0 translate-middle-y me-3"
                      style="cursor: pointer;" onclick="togglePassword('password','toggleIcon1')">
                    <i id="toggleIcon1" class="bi bi-eye"></i>
                </span>
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-3 position-relative">
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="form-control rounded-pill p-2 pe-5"
                    required placeholder="Konfirmasi Password">

                {{-- Toggle Confirm --}}
                <span class="position-absolute top-50 end-0 translate-middle-y me-3"
                      style="cursor: pointer;" onclick="togglePassword('password_confirmation','toggleIcon2')">
                    <i id="toggleIcon2" class="bi bi-eye"></i>
                </span>
            </div>

            {{-- Tombol Register --}}
            <button type="submit" class="btn btn-danger rounded-pill w-100 fw-bold mb-3">
                Daftar
            </button>

            {{-- Sudah punya akun --}}
            <div class="text-center">
                <span class="small text-muted">Sudah punya akun?</span>
                <a href="{{ route('login') }}" 
                   class="btn btn-outline-danger rounded-pill w-100 fw-bold mt-2">
                    Login
                </a>
            </div>
        </form>

        {{-- Footer kecil --}}
        <div class="mt-4 small text-muted">
            <a href="#" class="text-decoration-none me-2">Terms & Conditions</a>
            <a href="#" class="text-decoration-none">Privacy Policy</a>
        </div>
    </div>

    {{-- Kolom kanan (gambar background) --}}
    <div class="w-50 d-none d-md-block"
         style="background: url('{{ asset('images/loginn-bg.jpg') }}') center/cover no-repeat; min-height:100vh;">
    </div>
</div>

{{-- Script Show/Hide Password --}}
<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        }
    }
</script>
@endsection
