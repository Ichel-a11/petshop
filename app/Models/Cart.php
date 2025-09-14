<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'product_id', 
        'quantity', 
        'price', 
        'type',               // 'product' atau 'grooming'
        'grooming_booking_id'
    ];

    // Relasi ke produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke grooming booking
    public function groomingBooking()
    {
        return $this->belongsTo(GroomingBooking::class, 'grooming_booking_id');
    }
}
