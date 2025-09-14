<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter stok tersedia
        if ($request->has('in_stock')) {
            $query->where('stock', '>', 0);
        }

        // Urutkan
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            default:
                $query->latest();
                break;
        }

        // Ambil produk + kategori
        $products    = $query->paginate(12);
        $categories  = Category::orderBy('name')->get();

        return view('customer.catalog-new', compact('products', 'categories'));
    }

    public function show($id)
    {
        // Ambil produk beserta ulasan & user yang memberi ulasan
        $product = Product::with(['reviews.user'])->findOrFail($id);
        
        // Produk terkait (harga mirip)
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->whereBetween('price', [
                $product->price * 0.8, // 20% lebih murah
                $product->price * 1.2  // 20% lebih mahal
            ])
            ->take(4)
            ->get();

        return view('customer.product-detail', compact('product', 'relatedProducts'));
    }
}
