<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Daftar semua transaksi
     */
    public function index(Request $request)
    {
        // Query dasar
        $query = Transaction::with('order.user', 'order.items.product', 'order.items.grooming.service');

        // Filter berdasarkan status transaksi
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan metode pembayaran
        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        // Urutkan terbaru dulu
        $transactions = $query->latest()->paginate(10);

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Tampilkan detail transaksi
     */
    public function show(Transaction $transaction)
    {
        // Load relasi penting
        $transaction->load([
            'order.user', 
            'order.items.product', 
            'order.items.grooming.service'
        ]);

        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Update status transaksi
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,success,failed'
        ]);

        $transaction->update(['status' => $request->status]);

        // Update status order jika transaksi berhasil
        if ($transaction->order) {
            $orderStatus = $request->status === 'success' ? 'processing' : 'cancelled';
            $paymentStatus = $request->status === 'success' ? 'paid' : 'failed';

            $transaction->order->update([
                'status' => $orderStatus,
          
            ]);
        }

        return redirect()
            ->route('admin.transactions.show', $transaction->id)
            ->with('success', 'Status transaksi berhasil diperbarui.');
    }

    /**
     * Hapus transaksi (opsional)
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()
            ->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}