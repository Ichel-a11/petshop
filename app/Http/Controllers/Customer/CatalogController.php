<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;

class CatalogController extends Controller
{
    public function index()
    {
        $query = Product::query();

        // Search by name
        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by price range
        if (request('min_price')) {
            $query->where('price', '>=', request('min_price'));
        }
        if (request('max_price')) {
            $query->where('price', '<=', request('max_price'));
        }

        // Filter by stock
        if (request('in_stock') === 'true') {
            $query->where('stock', '>', 0);
        }

        // Sort products
        switch(request('sort')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
        }

        // ✅ Biar lebih dari 10 produk, kita tampilkan 20 per halaman
        $products = $query->paginate(20)->withQueryString();

        // ✅ Ambil min & max harga untuk filter
        $priceRange = Product::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();

        // ✅ Sesuaikan ke view yang kamu punya
        return view('customer.catalog-new', compact('products', 'priceRange'));
    }
}
