<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Menampilkan katalog produk
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        if ($request->sort == 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort == 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($request->sort == 'newest') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(8)->withQueryString();
        $categories = Category::all();

        return view('customer.catalog-new', compact('products', 'categories'));
    }

    // Menampilkan detail produk + produk terkait
    public function show($id)
    {
        $product = Product::findOrFail($id);

        // Ambil produk terkait berdasarkan kategori yang sama
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('customer.product-detail', [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }
}
