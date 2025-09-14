<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query()
            ->where('role', 'customer')
            ->withCount('orders')
            ->withSum('transactions', 'amount');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $customer)
    {
        $customer->loadCount('orders')
                ->loadSum('transactions', 'amount');
        
        $customer->load(['orders' => function($query) {
            $query->with('transaction')->latest();
        }]);

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Ban a customer.
     */
    public function ban(User $customer)
    {
        if ($customer->role !== 'customer') {
            return back()->with('error', 'Hanya dapat memblokir akun pelanggan.');
        }

        $customer->update(['is_active' => false]);

        return back()->with('success', 'Pelanggan berhasil diblokir.');
    }

    /**
     * Unban a customer.
     */
    public function unban(User $customer)
    {
        if ($customer->role !== 'customer') {
            return back()->with('error', 'Hanya dapat mengaktifkan akun pelanggan.');
        }

        $customer->update(['is_active' => true]);

        return back()->with('success', 'Pelanggan berhasil diaktifkan.');
    }
}
