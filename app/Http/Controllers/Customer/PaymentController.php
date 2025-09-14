<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', 'false') === 'true';
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /**
     * Tampilkan halaman pembayaran Midtrans
     */
    public function checkout($orderId)
    {
        $user = Auth::user();

        // 🔍 Ambil order + relasi items
        $order = Order::with('items')->where('id', $orderId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // 🔴 Validasi: pastikan order punya item
        if ($order->items->isEmpty()) {
            Log::warning('Order tanpa item: ' . $orderId);
            return redirect()->route('customer.cart.index')
                ->with('error', 'Order tidak memiliki item untuk dibayar.');
        }

        // ✅ Siapkan item_details
        $item_details = [];
        foreach ($order->items as $item) {
            // Pastikan price dan quantity integer
            $price = (int) $item->price;
            $quantity = (int) $item->quantity;

            // Jika price 0, kasih default
            if ($price <= 0) {
                Log::warning("Item price 0: {$item->name} (ID: {$item->id})");
                $price = 1000; // harga simbolik, atau skip
            }

            $item_details[] = [
                'id'       => $item->item_type . '_' . $item->item_id,
                'price'    => $price,
                'quantity' => $quantity,
                'name'     => $item->name ?? 'Item Tanpa Nama',
            ];
        }

        // 🔢 Validasi: total item harus sama dengan total order
        $calculatedTotal = collect($item_details)->sum(fn($i) => $i['price'] * $i['quantity']);
        $orderTotal = (int) $order->total_amount;

        if (abs($calculatedTotal - $orderTotal) > 1) {
            Log::error("❌ Mismatch total: Order {$orderId}, DB: {$orderTotal}, Hitung: {$calculatedTotal}");
            return redirect()->back()->with('error', 'Total pembayaran tidak valid. Hubungi admin.');
        }

        // 👤 Data pembeli
        $customerName = $order->customer_name ?? $user->name ?? 'Pelanggan';
        $customerEmail = $order->customer_email ?? $user->email;
        $customerPhone = $order->customer_phone ?? $user->phone ?? '081234567890';

        $customerDetails = [
            'first_name' => $customerName,
            'email'      => $customerEmail,
            'phone'      => $customerPhone,
            'billing_address' => [
                'first_name'    => $customerName,
                'address'       => $order->alamat ?? 'Alamat tidak tersedia',
                'phone'         => $customerPhone,
                'city'          => 'Ambon',
                'postal_code'   => '97228',
                'country_code'  => 'IDN'
            ]
        ];

        // 📦 Payload untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => 'ORDER-' . $order->id . '-' . now()->timestamp,
                'gross_amount' => $orderTotal, // pastikan integer
            ],
            'customer_details' => $customerDetails,
            'item_details'     => $item_details, // ✅ INI YANG BIKIN DETAIL BARANG MUNCUL!
        ];

        // 🔍 Debug: Log payload sebelum dikirim
        Log::info('📤 [Midtrans] Payload dikirim ke Midtrans:', [
            'order_id' => $order->id,
            'total_amount' => $orderTotal,
            'item_details' => $item_details,
            'gross_amount' => $params['transaction_details']['gross_amount'],
        ]);

        try {
            $snapToken = Snap::getSnapToken($params);
            Log::info('✅ [Midtrans] Snap Token berhasil dibuat', ['order_id' => $order->id]);
        } catch (\Exception $e) {
            Log::error('❌ [Midtrans] Gagal generate Snap Token', [
                'error' => $e->getMessage(),
                'payload' => $params,
            ]);
            return redirect()->back()->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }

        return view('customer.checkout.payment', compact('snapToken', 'order'));
    }
}