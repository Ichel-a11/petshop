<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\GroomingBooking;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Tambahkan relasi count & sum untuk order
        $user->loadCount('orders');
        $user->loadSum('orders', 'total_price');
        
        // Ambil 5 order terbaru
        $latestOrders = $user->orders()
            ->with('items.product')
            ->latest()
            ->take(5)
            ->get();

        // Produk terbaru (featured)
        $featuredProducts = Product::where('stock', '>', 0)
            ->latest()
            ->take(4)
            ->get();
            
        // Produk rekomendasi (berdasarkan pembelian terbanyak)
        $recommendedProducts = Product::where('stock', '>', 0)
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(4)
            ->get();

        // Statistik grooming
        $upcomingGroomingCount = GroomingBooking::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $completedGroomingCount = GroomingBooking::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // Jadwal grooming terdekat
        $upcomingGrooming = GroomingBooking::where('user_id', $user->id)
            ->with('service')
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('booking_time')
            ->take(3)
            ->get();
            
        return view('customer.dashboard-new', compact(
            'user', 
            'latestOrders',
            'recommendedProducts',
            'featuredProducts',
            'upcomingGroomingCount',
            'completedGroomingCount',
            'upcomingGrooming'
        ));
    }
}
