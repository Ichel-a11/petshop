<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\GroomingBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Tampilkan isi keranjang
     */
    public function index()
    {
        $cartItems = Cart::with(['product', 'groomingBooking.service'])
            ->where('user_id', Auth::id())
            ->get();

        return view('customer.cart', compact('cartItems'));
    }

    /**
     * Tambah ke keranjang (produk atau grooming)
     */
    public function add(Request $request, $id = null)
    {
        $type = $request->route()->parameter('type', 'product');
        
        if ($type === 'product') {
            $productId = $id;
            $quantity  = $request->input('quantity', 1);

            $product = Product::find($productId);
            if (!$product) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan.');
            }

            if ($product->stock <= 0) {
                return redirect()->back()->with('error', 'Produk habis.');
            }

            $cart = Cart::where('user_id', Auth::id())
                        ->where('product_id', $productId)
                        ->where('type', 'product')
                        ->first();

            if ($cart) {
                $newQuantity = $cart->quantity + $quantity;
                if ($newQuantity > $product->stock) {
                    return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia.');
                }
                $cart->quantity = $newQuantity;
                $cart->save();
            } else {
                if ($quantity > $product->stock) {
                    return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia.');
                }
                
                Cart::create([
                    'user_id'    => Auth::id(),
                    'product_id' => $productId,
                    'quantity'   => $quantity,
                    'price'      => $product->price,
                    'type'       => 'product',
                ]);
            }

            return redirect()->route('customer.cart.index')
                             ->with('success', 'Produk berhasil ditambahkan ke keranjang.');

        } elseif ($type === 'grooming') {
            $bookingId = $id;

            $booking = GroomingBooking::where('id', $bookingId)
                                      ->where('user_id', Auth::id())
                                      ->whereNull('order_id')
                                      ->first();

            if (!$booking) {
                return redirect()->back()->with('error', 'Booking grooming tidak valid.');
            }

            $existingCart = Cart::where('user_id', Auth::id())
                               ->where('grooming_booking_id', $bookingId)
                               ->where('type', 'grooming')
                               ->first();
                               
            if ($existingCart) {
                return redirect()->back()->with('error', 'Booking grooming sudah ada di keranjang.');
            }

            Cart::create([
                'user_id'              => Auth::id(),
                'product_id'           => null,
                'quantity'             => 1,
                'price'                => $booking->total_price,
                'type'                 => 'grooming',
                'grooming_booking_id'  => $booking->id
            ]);

            return redirect()->route('customer.cart.index')
                             ->with('success', 'Booking grooming ditambahkan ke keranjang.');
        }

        return redirect()->back()->with('error', 'Jenis item tidak valid.');
    }

    /**
     * Update jumlah produk di keranjang
     */
    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('type', 'product')
            ->firstOrFail();

        $product = $cart->product;
        if (!$product) {
            return redirect()->route('customer.cart.index')->with('error', 'Produk tidak ditemukan.');
        }

        if ($request->quantity > $product->stock) {
            return redirect()->route('customer.cart.index')->with('error', 'Jumlah melebihi stok yang tersedia.');
        }

        $cart->quantity = $request->quantity;
        $cart->save();

        return redirect()->route('customer.cart.index')->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    /**
     * Hapus item dari keranjang
     */
    public function remove($id)
    {
        $cart = Cart::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        $cart->delete();

        return back()->with('success', 'Item keranjang berhasil dihapus.');
    }
}