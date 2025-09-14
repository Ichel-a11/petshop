<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Notification;
use Midtrans\Config;

class PaymentCallbackController extends Controller
{
    public function callback(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        $notification = new Notification();

        $order = Order::find($notification->order_id);

        if ($notification->transaction_status == 'capture' || $notification->transaction_status == 'settlement') {
            $order->payment_status = 'paid';
            $order->status = 'processing';
        } elseif ($notification->transaction_status == 'pending') {
            $order->payment_status = 'pending';
        } elseif ($notification->transaction_status == 'deny' || $notification->transaction_status == 'cancel') {
            $order->payment_status = 'failed';
        }

        $order->save();
        return response()->json(['status' => 'success']);
    }
}
