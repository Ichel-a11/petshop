<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran Midtrans</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body>
    <div style="max-width:600px;margin:50px auto;text-align:center;">
        <h2>Pembayaran Order #{{ $order->id }}</h2>
        <p>Total Bayar: <strong>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</strong></p>

        <button id="pay-button" style="padding:10px 20px;background:green;color:#fff;border:none;border-radius:5px;cursor:pointer;">
            Bayar Sekarang
        </button>
    </div>

    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            snap.pay("{{ $snapToken }}", {
                onSuccess: function (result) {
                    alert("Pembayaran berhasil!");
                    window.location.href = "/payment-success";
                },
                onPending: function (result) {
                    alert("Menunggu pembayaran...");
                    window.location.href = "/payment-pending";
                },
                onError: function (result) {
                    alert("Pembayaran gagal!");
                    console.log(result);
                }
            });
        });
    </script>
</body>
</html>
