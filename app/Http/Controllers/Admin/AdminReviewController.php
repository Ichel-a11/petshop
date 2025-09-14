<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    /**
     * Daftar semua ulasan
     */
    public function index()
    {
        $reviews = Review::with('user', 'product')
            ->latest()
            ->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Hapus ulasan
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Ulasan berhasil dihapus.');
    }
}