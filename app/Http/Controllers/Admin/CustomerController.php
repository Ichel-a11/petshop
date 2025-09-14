<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = User::query()
            ->where('role', 'customer')
            ->withCount('orders')
            ->withSum('orders', 'total_price')

            // 🔍 Pencarian
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })

            // 📌 Filter status aktif/nonaktif
            ->when($request->status, function ($query, $status) {
                if ($status === 'active') {
                    $query->where('is_active', true);
                } elseif ($status === 'inactive') {
                    $query->where('is_active', false);
                }
            })

            // ↕️ Sorting
            ->when($request->sort, function ($query, $sort) {
                switch ($sort) {
                    case 'oldest':
                        $query->oldest();
                        break;
                    case 'most_orders':
                        $query->orderByDesc('orders_count');
                        break;
                    case 'highest_spent':
                        $query->orderByDesc('orders_sum_total_price');
                        break;
                    default:
                        $query->latest();
                        break;
                }
            }, function ($query) {
                $query->latest();
            })

            ->paginate(9)
            ->withQueryString(); // biar pagination tidak reset filter/search

        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = User::with([
            'orders' => function ($query) {
                $query->latest();
            },
            'orders.orderItems.product'
        ])->findOrFail($id);

        $totalSpent = $customer->orders->sum('total_price');
        $totalOrders = $customer->orders->count();

        return view('admin.customers.show', compact('customer', 'totalSpent', 'totalOrders'));
    }

    public function ban($id)
    {
        $customer = User::findOrFail($id);
        $customer->update(['is_active' => false]);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer berhasil dinonaktifkan');
    }

    public function unban($id)
    {
        $customer = User::findOrFail($id);
        $customer->update(['is_active' => true]);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer berhasil diaktifkan kembali');
    }
}
