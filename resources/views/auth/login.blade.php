@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="d-flex min-vh-100">
    {{-- Kolom kiri (form login) --}}
    <div class="d-flex flex-column justify-content-center align-items-center w-50 bg-white px-5">
        
        {{-- Logo --}}
        <div class="text-center mb-4">
            <img src="{{ asset('images/pablo.jpg') }}" alt="Logo" style="width: 60px;">
            <h3 class="fw-bold mt-3 text-danger">Pet Care</h3>
            <p class="text-muted">Sign in to continue</p>
        </div>

        {{-- Notifikasi --}}
        @if (session('status'))
            <div class="alert alert-success w-100">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger w-100">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form login --}}
        <form method="POST" action="{{ route('login') }}" class="w-100" style="max-width: 320px;">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <input type="email" name="email" id="email"
                    class="form-control rounded-pill p-2"
                    value="{{ old('email') }}" required autofocus
                    placeholder="Email">
            </div>

            {{-- Password + Toggle --}}
            <div class="mb-3 position-relative">
                <input type="password" name="password" id="password"
                    class="form-control rounded-pill p-2 pe-5"
                    required placeholder="Password">

                {{-- Tombol Show/Hide --}}
                <span class="position-absolute top-50 end-0 translate-middle-y me-3"
                      style="cursor: pointer;" onclick="togglePassword()">
                    <i id="toggleIcon" class="bi bi-eye"></i>
                </span>
            </div>

            {{-- Remember + Forgot --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Remember</label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small text-decoration-none text-danger">
                        Forgot Password?
                    </a>
                @endif
            </div>

            {{-- Tombol login --}}
            <button type="submit" class="btn btn-danger rounded-pill w-100 fw-bold mb-3">
                Login
            </button>

            {{-- Tombol daftar jika belum punya akun --}}
            @if (Route::has('register'))
                <div class="text-center">
                    <span class="small text-muted">Belum punya akun?</span>
                    <a href="{{ route('register') }}" 
                       class="btn btn-outline-danger rounded-pill w-100 fw-bold mt-2">
                        Daftar
                    </a>
                </div>
            @endif
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
    function togglePassword() {
        const input = document.getElementById("password");
        const icon = document.getElementById("toggleIcon");
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
