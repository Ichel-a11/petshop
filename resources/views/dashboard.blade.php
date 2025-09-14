@extends('layouts.app')

@section('title', 'Dashboard Customer')

@section('content')
<div class="container py-5">

    <!-- Hero Section -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h1 class="fw-bold display-5">Selamat Datang, {{ Auth::user()->name }} 🐾</h1>
            <p class="text-muted">Kelola aktivitas belanja, layanan grooming, dan GroomingBooking dengan mudah.</p>
            <div class="mt-4">
                <a href="{{ route('customer.catalog') }}" class="btn btn-success btn-lg me-2">🛍️ Belanja Produk</a>
                <a href="{{ route('customer.grooming') }}" class="btn btn-outline-primary btn-lg">✂️ Grooming</a>
            </div>
        </div>
        <div class="col-md-6 text-center">
            <img src="{{ asset('images/pet-dashboard.png') }}" class="img-fluid rounded-3 shadow" alt="Dashboard Pet">
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row text-center g-4">
        <div class="col-md-4">
            <div class="p-4 shadow-sm rounded bg-white h-100">
                <i class="bi bi-bag-check fs-1 text-success"></i>
                <h4 class="mt-3">Katalog Produk</h4>
                <p>Temukan berbagai kebutuhan hewan kesayangan Anda.</p>
                <a href="{{ route('customer.catalog') }}" class="btn btn-sm btn-success">Lihat Produk</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 shadow-sm rounded bg-white h-100">
                <i class="bi bi-scissors fs-1 text-primary"></i>
                <h4 class="mt-3">Layanan Grooming</h4>
                <p>GroomingBooking grooming profesional untuk hewan kesayangan.</p>
                <a href="{{ route('customer.grooming') }}" class="btn btn-sm btn-primary">GroomingBooking Grooming</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 shadow-sm rounded bg-white h-100">
                <i class="bi bi-calendar-check fs-1 text-danger"></i>
                <h4 class="mt-3">GroomingBooking Saya</h4>
                <p>Lihat dan kelola semua jadwal grooming Anda.</p>
                <a href="{{ route('customer.bookings') }}" class="btn btn-sm btn-danger">Lihat GroomingBooking</a>
            </div>
        </div>
    </div>
</div>
@endsection
