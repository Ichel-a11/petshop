<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    /**
     * Nama tabel (opsional, jika tidak standar)
     * @var string
     */
    protected $table = 'transactions';

    /**
     * Kolom yang bisa diisi (mass assignment)
     * @var array
     */
    protected $fillable = [
        'order_id',
        'amount',           // jumlah pembayaran
        'status',           // success, pending, failed
        'payment_id',       // transaction_id dari Midtrans
        'payment_type',     // gopay, bca_va, dll
        'payment_url',      // URL redirect (untuk QRIS, redirect, dll)
        'payment_response', // JSON response dari Midtrans (untuk log)
        'expired_at',       // waktu kadaluarsa (jika ada)
    ];

    /**
     * Kolom yang di-cast otomatis
     * @var array
     */
    protected $casts = [
        'amount' => 'float',
        'payment_response' => 'array', // otomatis jadi array saat diakses
        'expired_at' => 'datetime',
        'created_at' => 'datetime:d M Y, H:i',
        'updated_at' => 'datetime:d M Y, H:i',
    ];

    /**
     * Relasi: Transaction dimiliki oleh Order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Accessor: Format amount menjadi Rupiah
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Accessor: Dapatkan nama metode pembayaran yang lebih rapi
     */
    public function getDisplayPaymentTypeAttribute(): string
    {
        $labels = [
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'bca_va' => 'BCA Virtual Account',
            'bni_va' => 'BNI Virtual Account',
            'mandiri_va' => 'Mandiri Virtual Account',
            'alfamart' => 'Alfamart',
            'indomaret' => 'Indomaret',
            'credit_card' => 'Kartu Kredit',
        ];

        return $labels[$this->payment_type] ?? ucfirst($this->payment_type);
    }

    /**
     * Cek apakah pembayaran berhasil
     */
    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Cek apakah pembayaran gagal
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Cek apakah pembayaran pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}