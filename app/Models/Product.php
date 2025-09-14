<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['name','description','price','stock','image','category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke order_items khusus product
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'item_id')
                    ->where('item_type', 'product');
    }

    // Relasi ke reviews
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // accessor gambar
    public function getImageUrlAttribute()
    {
        return $this->image 
            ? asset('storage/'.$this->image) 
            : asset('images/no-image.png');
    }
}
