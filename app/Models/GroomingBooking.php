<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroomingBooking extends Model
{
    protected $fillable = [
        'user_id',
        'grooming_service_id',
        'booking_time',
        'pet_name',
        'pet_type',
        'pet_size',
        'notes',
        'status',
        'total_price',
        'cancellation_reason'
    ];

    protected $casts = [
        'booking_time' => 'datetime',
        'total_price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(GroomingService::class, 'grooming_service_id');
    }

    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'confirmed' => 'bg-info',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Terkonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Unknown'
        };
    }
    
    public function groomingBooking()
    {
        return $this->belongsTo(GroomingBooking::class, 'grooming_booking_id');
    }
}
