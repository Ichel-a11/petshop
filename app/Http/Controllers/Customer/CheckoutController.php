<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Order;
use App\Models\GroomingBooking;
use App\Models\Transaction;
use Midtrans\Snap;
use Midtrans\Config;

class CheckoutController extends Controller
{
    /**
     * Halaman checkout (tampilkan produk & grooming)
     */
    public function index()
    {
        $user = Auth::user();

        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->where('type', 'product')
            ->whereNotNull('product_id')
            ->get();

        $bookings = GroomingBooking::where('user_id', $user->id)
            ->whereNull('order_id')
            ->whereNotNull('total_price')
            ->where('total_price', '>', 0)
            ->get();

        if ($cartItems->isEmpty() && $bookings->isEmpty()) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Keranjang & Grooming Anda kosong.');
        }

        $totalAmount = $cartItems->sum(fn($item) => ($item->product->price ?? 0) * $item->quantity);
        $totalAmount += $bookings->sum('total_price') ?? 0;

        return view('customer.checkout', compact('cartItems', 'bookings', 'totalAmount'));
    }

    /**
     * Proses checkout: buat order & arahkan ke Midtrans
     */
    public function process(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'no_hp'  => 'required|string|max:20',
        ]);

        $user = Auth::user();

        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->where('type', 'product')
            ->whereNotNull('product_id')
            ->get();

        $bookings = GroomingBooking::where('user_id', $user->id)
            ->whereNull('order_id')
            ->whereNotNull('total_price')
            ->where('total_price', '>', 0)
            ->get();

        if ($cartItems->isEmpty() && $bookings->isEmpty()) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Keranjang & Grooming Anda kosong.');
        }

        $totalAmount = $cartItems->sum(fn($item) => ($item->product->price ?? 0) * $item->quantity);
        $totalAmount += $bookings->sum('total_price') ?? 0;

        $nama = $request->nama ?? 'Customer';
        $alamat = $request->alamat ?? 'Alamat tidak tersedia';
        $noHp = $request->no_hp ?? '081234567890';

        $orderId = null;

        DB::transaction(function () use (
            $user, $cartItems, $bookings, $totalAmount,
            $nama, $alamat, $noHp, &$orderId
        ) {
            foreach ($cartItems as $item) {
                $product = $item->product;
                if (!$product) {
                    throw new \Exception("Produk tidak ditemukan.");
                }
                if ($product->stock < $item->quantity) {
                    throw new \Exception("Stok tidak cukup untuk: {$product->name}");
                }
            }

            // ✅ GENERATE ORDER ID
            $order_id = $this->generateOrderId();

            $order = Order::create([
                'user_id'        => $user->id,
                'order_id'       => $order_id, // ✅ Wajib diisi!
                'nama'           => $nama,
                'alamat'         => $alamat,
                'no_hp'          => $noHp,
                'total_amount'   => $totalAmount,
                'status'         => 'pending',
                'payment_method' => 'midtrans',
                'payment_status' => 'unpaid',
                'customer_name'  => $nama,
                'customer_email' => $user->email,
                'customer_phone' => $noHp,
            ]);

            $orderId = $order->id;

            // ✅ BUAT TRANSAKSI
            Transaction::create([
                'order_id' => $order->id,
                'amount'   => $order->total_amount,
                'status'   => 'pending',
            ]);

            foreach ($cartItems as $item) {
                $product = $item->product;
                if (!$product) continue;

                $product->stock -= $item->quantity;
                $product->save();

                $order->items()->create([
                    'order_id'  => $order_id, // ✅ Gunakan order_id
                    'item_id'   => $item->product_id,
                    'item_type' => 'product',
                    'quantity'  => $item->quantity,
                    'price'     => $product->price,
                    'name'      => $product->name ?? 'Produk Tanpa Nama',
                    'subtotal'  => $product->price * $item->quantity,
                ]);
            }

            foreach ($bookings as $booking) {
                $order->items()->create([
                    'order_id'  => $order_id, // ✅ Gunakan order_id
                    'item_id'   => $booking->id,
                    'item_type' => 'grooming',
                    'quantity'  => 1,
                    'price'     => $booking->total_price ?? 0,
                    'name'      => $booking->service->name ?? 'Layanan Grooming',
                    'subtotal'  => $booking->total_price ?? 0,
                ]);

                $booking->update([
                    'order_id' => $order->id,
                    'status'   => 'paid'
                ]);
            }

            Cart::where('user_id', $user->id)->delete();
        });

        return redirect()->route('payment.index', $orderId);
    }

    /**
     * Generate order_id unik: INV-YYYYMMDD-0001
     */
    private function generateOrderId()
    {
        $prefix = 'INV-' . date('Ymd');
        $lastOrder = Order::where('order_id', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastOrder ? (int) substr($lastOrder->order_id, -4) + 1 : 1;

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Halaman pembayaran Midtrans
     */
    public function payment($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);

        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        $customerName  = $order->customer_name ?: 'Customer';
        $customerEmail = $order->customer_email ?: 'noreply@petshop.com';
        $customerPhone = $order->customer_phone ?: '081234567890';
        $address       = $order->alamat ?: 'Alamat tidak tersedia';

        $item_details = [];
        foreach ($order->items as $item) {
            $item_details[] = [
                'id'       => $item->item_type . '_' . $item->item_id,
                'price'    => (int) $item->price,
                'quantity' => (int) $item->quantity,
                'name'     => $item->name ?? 'Item Tanpa Nama',
            ];
        }

        if (empty($item_details)) {
            Log::error('Item details kosong untuk order ID: ' . $order->id);
            return redirect()->route('customer.cart.index')
                ->with('error', 'Tidak ada item untuk dibayar.');
        }

        $calculatedTotal = collect($item_details)->sum(fn($i) => $i['price'] * $i->quantity);
        if (abs($calculatedTotal - $order->total_amount) > 1) {
            Log::error("Mismatch total: Order ID {$order->id}, DB: {$order->total_amount}, Hitung: $calculatedTotal");
            return redirect()->route('customer.cart.index')
                ->with('error', 'Total pembayaran tidak valid.');
        }

        $customerDetails = [
            'first_name' => $customerName,
            'email'      => $customerEmail,
            'phone'      => $customerPhone,
            'billing_address' => [
                'first_name'    => $customerName,
                'address'       => $address,
                'phone'         => $customerPhone,
                'city'          => 'Ambon',
                'postal_code'   => '97228',
                'country_code'  => 'IDN'
            ]
        ];

        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_id, // ✅ Gunakan order_id, bukan id
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => $customerDetails,
            'item_details'     => $item_details,
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error('Midtrans API Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }

        return view('customer.payment', compact('order', 'snapToken'));
    }
}