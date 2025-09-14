<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class PaymentCallbackController extends Controller
{
    /**
     * Midtrans Callback Handler
     * Dipanggil otomatis oleh Midtrans saat status pembayaran berubah
     */
    public function callback(Request $request)
    {
        // 🛡️ 1. Validasi input dasar
        $request->validate([
            'order_id'      => 'required|string',
            'status_code'   => 'required|string',
            'gross_amount'  => 'required|numeric',
            'signature_key' => 'required|string',
            'transaction_status' => 'required|string',
        ]);

        $serverKey = env('MIDTRANS_SERVER_KEY');
        if (!$serverKey) {
            Log::error('MIDTRANS_SERVER_KEY tidak ditemukan di .env');
            return response()->json(['message' => 'Server configuration error'], 500);
        }

        // 🔐 2. Verifikasi signature
        $signatureKey = hash('sha512', 
            $request->order_id . 
            $request->status_code . 
            $request->gross_amount . 
            $serverKey
        );

        if (!hash_equals($signatureKey, $request->signature_key)) {
            Log::warning('Midtrans callback: Signature tidak valid', [
                'order_id' => $request->order_id,
                'received' => $request->signature_key,
                'expected' => $signatureKey,
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // 🔍 3. Ekstrak order ID asli dari format ORDER-{id}-timestamp
        preg_match('/^ORDER-(\d+)-\d+$/', $request->order_id, $matches);
        $orderId = $matches[1] ?? null;

        if (!$orderId) {
            Log::error('Gagal ekstrak order ID dari: ' . $request->order_id);
            return response()->json(['message' => 'Invalid order ID format'], 400);
        }

        // 🔎 4. Cari order
        $order = Order::with('items')->find($orderId);
        if (!$order) {
            Log::error('Order tidak ditemukan: ' . $orderId);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 🔁 5. Jangan proses ulang jika status sudah final
        if (in_array($order->payment_status, ['paid', 'failed']) && 
            in_array($request->transaction_status, ['settlement', 'expire', 'cancel'])) {
            Log::info('Callback diabaikan (status sudah final)', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order status already processed']);
        }

        // 🔄 6. Update status berdasarkan transaction_status
        $transactionStatus = $request->transaction_status;

        try {
            DB::transaction(function () use ($order, $transactionStatus, $request) {
                if (in_array($transactionStatus, ['capture', 'settlement'])) {
                    $order->status = 'confirmed';
                    $order->payment_status = 'paid';
                } elseif ($transactionStatus === 'pending') {
                    $order->status = 'pending';
                    $order->payment_status = 'pending';
                } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                    $order->status = 'cancelled';
                    $order->payment_status = 'failed';

                    // 🔽 KEMBALIKAN STOK hanya jika sebelumnya belum gagal
                    if ($order->wasChanged('payment_status')) {
                        foreach ($order->items as $item) {
                            if ($item->item_type === 'product' && $item->item_id) {
                                $product = \App\Models\Product::find($item->item_id);
                                if ($product) {
                                    $product->stock += $item->quantity; // 🔁 Tambah kembali stok
                                    $product->save();
                                    Log::info("Stok dikembalikan: {$item->quantity}x {$product->name}");
                                }
                            }
                        }
                    }
                } else {
                    $order->status = 'unknown';
                    $order->payment_status = 'unknown';
                }

                // 💾 Simpan data tambahan
                $order->payment_type = $request->payment_type ?? $order->payment_type;
                $order->gross_amount = (float) $request->gross_amount;
                $order->transaction_time = $request->transaction_time ?? now();
                $order->midtrans_response = $request->all();

                $order->save();
            });

            Log::info('Order & stok berhasil diperbarui via Midtrans callback', [
                'order_id' => $order->id,
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'transaction_status' => $transactionStatus
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal simpan order di callback: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'request' => $request->all()
            ]);
            return response()->json(['message' => 'Failed to update order'], 500);
        }

        return response()->json(['message' => 'Callback processed successfully']);
    }
}