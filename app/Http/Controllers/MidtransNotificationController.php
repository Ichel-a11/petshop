<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;

class MidtransNotificationController extends Controller
{
    public function handle(Request $request)
    {
        // Ambil notifikasi dari Midtrans
        $notification = new Notification();

        $order_id = $notification->order_id;
        $transaction_status = $notification->transaction_status;
        $payment_type = $notification->payment_type;
        $fraud_status = $notification->fraud_status ?? 'accept';

        // Log notifikasi untuk debugging
        Log::info('Midtrans Notification', [
            'order_id' => $order_id,
            'transaction_status' => $transaction_status,
            'payment_type' => $payment_type,
            'fraud_status' => $fraud_status,
        ]);

        // Cari order
        $order = Order::where('order_id', $order_id)->first();
        if (!$order) {
            Log::warning("Order {$order_id} not found");
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update status sesuai notifikasi
        switch ($transaction_status) {
            case 'capture':
                if ($payment_type == 'credit_card') {
                    if ($fraud_status == 'challenge') {
                        $order->payment_status = 'pending';
                        $order->status = 'menunggu_verifikasi';
                    } else {
                        $order->payment_status = 'lunas';
                        $order->status = 'dibayar';
                    }
                }
                break;

            case 'settlement':
                $order->payment_status = 'lunas';
                $order->status = 'dibayar';
                break;

            case 'pending':
                $order->payment_status = 'pending';
                $order->status = 'menunggu_pembayaran';
                break;

            case 'deny':
            case 'cancel':
                $order->payment_status = 'gagal';
                $order->status = 'dibatalkan';
                break;

            case 'expire':
                $order->payment_status = 'kadaluarsa';
                $order->status = 'kadaluarsa';
                break;

            default:
                Log::warning("Unhandled transaction status: {$transaction_status}");
                break;
        }

        $order->save();

        return response()->json(['message' => 'Notification processed successfully']);
    }
}
