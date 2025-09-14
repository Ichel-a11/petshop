<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the customer's orders.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orders = Auth::user()->orders()->with('orderItems.product', 'transaction')->latest()->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        // Ensure the authenticated user owns the order
        if ($order->user_id !== Auth::id()) {
            abort(404);
        }
        
        $order->load('orderItems.product', 'transaction');

        return view('customer.orders.show', compact('order'));
    }
}
