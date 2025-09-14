<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\GroomingBooking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Simpan GroomingBooking baru
     */
    public function store(Request $request, $serviceId)
    {
        $validated = $request->validate([
            'pet_name' => 'required|string|max:255',
            'pet_type' => 'required|in:cat,dog',
            'date'     => 'required|date|after_or_equal:today',
            'time'     => 'required',
            'notes'    => 'nullable|string|max:500',
        ]);

        GroomingBooking::create([
            'user_id'     => auth()->id(),
            'service_id'  => $serviceId,
            'pet_name'    => $validated['pet_name'],
            'pet_type'    => $validated['pet_type'],
            'date'        => $validated['date'],
            'time'        => $validated['time'],
            'notes'       => $validated['notes'] ?? null,
            'status'      => 'pending',
            'order_id'    => null,
        ]);

        return redirect()
            ->route('customer.bookings.index')
            ->with('success', 'Booking berhasil dibuat!');
    }

    /**
     * Halaman daftar GroomingBooking aktif (belum masuk order & belum dibatalkan)
     */
    public function myBooking()
    {
        $bookings = GroomingBooking::with('service')
            ->where('user_id', auth()->id())
            ->whereNull('order_id')         // 🔥 Hanya yang belum masuk order
            ->where('status', 'pending')    // Hanya yang belum dibatalkan
            ->latest()
            ->paginate(10);

        return view('customer.grooming.my-bookings', compact('bookings'));
    }

    /**
     * Proses pembatalan GroomingBooking
     */
    public function cancel(Request $request, GroomingBooking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak membatalkan booking ini.');
        }

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $booking->update([
            'status' => 'cancelled',
            'notes'  => trim(($booking->notes ?? '') . "\n[Batal]: " . $request->reason),
        ]);

        return redirect()
            ->route('customer.bookings.index')
            ->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * Hapus booking yang sudah dibatalkan (opsional)
     */
    public function deleteBooking($id)
    {
        $booking = GroomingBooking::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'cancelled')
            ->firstOrFail();

        $booking->delete();

        return redirect()->route('customer.bookings.index')
            ->with('success', 'Booking berhasil dihapus.');
    }
}