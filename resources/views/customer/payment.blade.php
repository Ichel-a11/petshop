@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">Pembayaran Online - Order #{{ $order->id }}</h2>

    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body text-center">
            <p><strong>Total Bayar:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
            <button id="pay-button" class="btn btn-primary btn-lg w-100">Bayar Sekarang</button>
        </div>
    </div>
</div>

<!-- Midtrans Snap.js -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
document.getElementById('pay-button').onclick = function() {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result){
            alert("Pembayaran berhasil!");
            window.location.href = "{{ route('customer.dashboard') }}"; // Bisa diubah ke halaman sukses
        },
        onPending: function(result){
            alert("Pembayaran menunggu konfirmasi.");
            window.location.href = "{{ route('customer.dashboard') }}";
        },
        onError: function(result){
            alert("Pembayaran gagal. Silakan coba lagi.");
        }
    });
};
</script>
@endsection
