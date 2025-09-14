<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('query');
        $products = collect([]);
        $hasSearch = false;

        if ($query) {
            $hasSearch = true;
            $products = Product::where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();
        }

        return view('customer.search', compact('products', 'query', 'hasSearch'));
    }
}
