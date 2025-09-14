<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\GroomingService;
use App\Models\GroomingBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Cart;

class GroomingController extends Controller
{
    /**
     * Tampilkan daftar layanan grooming
     */
    public function index()
    {
        $allServices = GroomingService::where('is_available', true)
            ->orderBy('pet_type')
            ->orderBy('price')
            ->get();

        $servicesByType = $allServices->groupBy('pet_type');

        return view('customer.grooming.index', [
            'allServices' => $allServices,
            'services'    => $servicesByType,
        ]);
    }

    /**
     * Formulir GroomingBooking
     */
    public function bookingForm($serviceId)
    {
        $service = GroomingService::findOrFail($serviceId);

        // Generate slot waktu untuk 7 hari ke depan
        $timeSlots = [];
        $startDate = Carbon::tomorrow();
        $endDate   = Carbon::tomorrow()->addDays(7);

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $slots     = [];
            $startTime = Carbon::parse($date->format('Y-m-d') . ' 09:00:00');
            $endTime   = Carbon::parse($date->format('Y-m-d') . ' 17:00:00');

            while ($startTime < $endTime) {
                $isBooked = GroomingBooking::where('grooming_service_id', $service->id)
                    ->where('booking_time', $startTime)
                    ->whereIn('status', ['pending', 'approved'])
                    ->exists();

                if (!$isBooked) {
                    $slots[] = $startTime->format('H:i');
                }

                $startTime->addHour();
            }

            if (!empty($slots)) {
                $timeSlots[$date->format('Y-m-d')] = $slots;
            }
        }

        return view('customer.grooming.groomingbooking', compact('service', 'timeSlots'));
    }

    /**
     * Simpan GroomingBooking baru
     */
    public function book(Request $request, $serviceId)
    {
        \Log::info('=== START BOOKING PROCESS ===');
        \Log::info('Service ID: ' . $serviceId);
        \Log::info('User ID: ' . Auth::id());

        $service = GroomingService::findOrFail($serviceId);

        $validated = $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'pet_name'     => 'required|string|max:255',
            'pet_size'     => 'required|in:small,medium,large',
            'notes'        => 'nullable|string|max:1000',
        ]);

        $bookingTime = Carbon::parse($validated['booking_date'] . ' ' . $validated['booking_time']);

        // Cek slot tersedia
        $isBooked = GroomingBooking::where('grooming_service_id', $service->id)
            ->where('booking_time', $bookingTime)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($isBooked) {
            return back()->with('error', 'Maaf, jadwal ini sudah dipesan. Silakan pilih jadwal lain.');
        }

        // Buat booking
        $booking = GroomingBooking::create([
            'user_id'             => Auth::id(),
            'grooming_service_id' => $service->id,
            'booking_time'        => $bookingTime,
            'pet_name'            => $validated['pet_name'],
            'pet_type'            => $service->pet_type,
            'pet_size'            => $validated['pet_size'],
            'notes'               => $validated['notes'] ?? null,
            'total_price'         => $service->price,
            'status'              => 'pending'
        ]);

        \Log::info('Booking created: ' . $booking->id);

        // Secara otomatis tambahkan ke keranjang
        try {
            $cartItem = Cart::create([
                'user_id'              => Auth::id(),
                'product_id'           => null,
                'quantity'             => 1,
                'price'                => $service->price,
                'type'                 => 'grooming',
                'grooming_booking_id'  => $booking->id
            ]);
            
            \Log::info('Cart item created: ' . $cartItem->id);
            \Log::info('Cart item data: ', $cartItem->toArray());
            
        } catch (\Exception $e) {
            \Log::error('Error creating cart item: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());
        }

        \Log::info('=== END BOOKING PROCESS ===');

        return redirect()->route('customer.grooming.my-bookings')
            ->with('success', 'Booking grooming berhasil dibuat dan ditambahkan ke keranjang!'); 
    }

    /**
     * Daftar GroomingBooking user
     */
    public function myBookings()
    {
        $bookings = GroomingBooking::where('user_id', Auth::id())
            ->with('service')
            ->latest()
            ->paginate(10);

        return view('customer.grooming.my-bookings', compact('bookings'));
    }

    /**
     * Batalkan GroomingBooking
     */
    public function cancelBooking(Request $request, $bookingId)
    {
        $booking = GroomingBooking::where('user_id', Auth::id())
            ->findOrFail($bookingId);

        if (!in_array($booking->status, ['pending', 'approved'])) {
            return back()->with('error', 'Booking ini tidak dapat dibatalkan.');
        }

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $booking->update([
            'status'              => 'cancelled',
            'cancellation_reason' => $request->reason ?? null,
        ]);

        return back()->with('success', 'Booking grooming berhasil dibatalkan.');
    }

    /**
     * Hapus bookingan (hanya untuk yang sudah dibatalkan)
     */
    public function deleteBooking($id)
    {
        $booking = GroomingBooking::where('user_id', Auth::id())
            ->findOrFail($id);

        // Cek status: hanya bisa dihapus jika sudah dibatalkan
        if ($booking->status !== 'cancelled') {
            return back()->with('error', 'Booking tidak dapat dihapus.');
        }

        // 🔥 HAPUS ITEM CART YANG BERHUBUNGAN DENGAN BOOKING INI
        Cart::where('user_id', Auth::id())
            ->where('type', 'grooming')
            ->where('grooming_booking_id', $booking->id)
            ->delete();

        // Hapus booking
        $booking->delete();

        return back()->with('success', 'Booking berhasil dihapus.');
    }

    /**
     * Tambahkan booking ke cart
     */
    public function addToCart($bookingId)
    {
        $booking = GroomingBooking::where('id', $bookingId)
                    ->where('user_id', Auth::id())
                    ->whereNull('order_id') // belum masuk order
                    ->firstOrFail();

        // Cek apakah sudah ada di cart
        $existingCart = Cart::where('user_id', Auth::id())
                           ->where('grooming_booking_id', $bookingId)
                           ->where('type', 'grooming')
                           ->first();

        if ($existingCart) {
            return redirect()->back()->with('error', 'Booking sudah ada di keranjang.');
        }

        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => null,
            'quantity' => 1,
            'price' => $booking->total_price,
            'type' => 'grooming',
            'grooming_booking_id' => $booking->id
        ]);

        return redirect()->route('customer.cart.index')->with('success', 'Booking grooming ditambahkan ke keranjang.');
    }

    /**
     * Hapus item dari cart
     */
    public function remove($id)
    {
        $item = Cart::where('id', $id)
               ->where('user_id', Auth::id())
               ->firstOrFail();
               
        $item->delete();

        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}