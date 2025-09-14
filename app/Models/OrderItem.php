<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_id',     // ID produk atau ID grooming_booking
        'item_type',   // 'product' atau 'grooming'
        'name',        // Nama item saat dibeli
        'quantity',
        'price',
        'subtotal'
    ];

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke Product (hanya jika item_type = 'product')
    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id');
    }

    // Relasi ke GroomingBooking (hanya jika item_type = 'grooming')
    public function grooming()
    {
        return $this->belongsTo(GroomingBooking::class, 'item_id');
    }

    /**
     * Atribut dinamis: Nama item
     */
    public function getDisplayNameAttribute()
    {
        if ($this->item_type === 'product' && $this->product) {
            return $this->product->name ?? $this->product->nama ?? 'Produk Tidak Ditemukan';
        }

        if ($this->item_type === 'grooming' && $this->grooming) {
            return "Grooming {$this->grooming->pet_name}";
        }

        return 'Item Tidak Dikenal';
    }

    /**
     * Scope: Filter item berdasarkan tipe
     */
    public function scopeProduct($query)
    {
        return $query->where('item_type', 'product');
    }

    public function scopeGrooming($query)
    {
        return $query->where('item_type', 'grooming');
    }
}