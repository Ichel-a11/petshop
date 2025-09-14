<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Redirect user setelah login berdasarkan role.
     */
    public function redirect()
    {
        $role = auth()->user()->role;

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'customer') {
            return redirect()->route('customer.dashboard');
        }

        abort(403, 'Unauthorized');
    }

    /**
     * Tampilkan dashboard admin.
     */
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Tampilkan dashboard customer.
     */
    public function customerDashboard()
    {
        return view('customer.dashboard');
    }
}
