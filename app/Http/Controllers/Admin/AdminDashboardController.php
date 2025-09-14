<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Transaction; // ✅ Tambahkan ini — model yang sekarang pakai tabel 'payments'
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 💰 Total pendapatan: hanya transaksi yang sukses (status = 'success')
        $totalPendapatan = Transaction::where('status', 'success')->sum('amount') ?? 0;

        // 📦 Total produk
        $totalProduk = Product::count();

        // 👥 Total pelanggan
        $totalPelanggan = User::where('role', 'customer')->count();

        // ⏳ Pesanan pending: transaksi dengan status 'pending'
        $pesananPending = Transaction::where('status', 'pending')->count();

        // 📊 Transaksi terbaru (5 terakhir) — ambil dari Transaction, bukan Order
        $transaksiTerbaru = Transaction::with(['order.user']) // ✅ relasi ke order.user
            ->latest()
            ->take(5)
            ->get();

        // 👤 Pelanggan terbaru (5 terakhir)
        $pelangganTerbaru = User::where('role', 'customer')
            ->latest()
            ->take(5)
            ->get();

        // 🏆 Produk Terlaris (opsional)
        $produkTerlaris = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('order_items.item_type', 'product')
            ->whereHas('transactions', function ($q) { // ✅ pastikan order punya transaksi sukses
                $q->where('status', 'success');
            })
            ->select('order_items.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('order_items.name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // 📅 Pendapatan Bulan Ini
        $pendapatanBulanIni = Transaction::where('status', 'success')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount') ?? 0;

        return view('admin.dashboard-new', compact(
            'totalPendapatan',
            'totalProduk',
            'totalPelanggan',
            'pesananPending',
            'transaksiTerbaru',
            'pelangganTerbaru',
            'produkTerlaris',
            'pendapatanBulanIni'
        ));
    }
}