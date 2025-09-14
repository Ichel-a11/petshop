<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Transaction;      // ✅ WAJIB — untuk relasi transaksi
use App\Models\OrderItem;        // ✅ untuk relasi item
use App\Models\User;             // ✅ untuk relasi user

class Order extends Model
{
    use HasFactory;

    /**
     * Nama tabel (opsional, jika tidak standar)
     * @var string
     */
    protected $table = 'orders';

    /**
     * Kolom yang bisa diisi (mass assignment)
     */
    protected $fillable = [
        'user_id',
        'order_id',           // ID unik, misal: INV-202504050001
        'nama',
        'alamat',
        'no_hp',
        'total_amount',       // total semua item (produk + grooming)
        'status',             // pending, processing, shipped, completed, cancelled, expired
        'payment_status',     // unpaid, paid, failed
        'payment_method',     // gopay, bca_va, credit_card, dll
        'payment_proof',      // bisa link Midtrans atau path file
        'customer_name',
        'customer_email',
        'customer_phone',
    ];

    /**
     * Cast field tertentu agar konsisten
     */
    protected $casts = [
        'total_amount' => 'integer',
        'created_at' => 'datetime:d M Y, H:i',
        'updated_at' => 'datetime:d M Y, H:i',
    ];

    // ──────────────────────────────────────────────────
    // 🔗 RELASI
    // ──────────────────────────────────────────────────

    /**
     * Relasi: Order bisa memiliki banyak transaksi (pembayaran)
     * Contoh: gagal → coba lagi → sukses
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'order_id');
    }

    /**
     * Relasi: Ambil transaksi utama (terbaru) untuk order ini
     * Berguna untuk menampilkan status pembayaran terkini
     */
    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class, 'order_id')->latestOfMany();
        // Jika versi Laravel < 8, ganti jadi:
        // return $this->hasOne(Transaction::class, 'order_id')->latest();
    }

    /**
     * Relasi: Order memiliki banyak OrderItem (produk)
     */
    public function productItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id')->where('item_type', 'product');
    }

    /**
     * Relasi: Order memiliki banyak OrderItem (grooming)
     */
    public function groomingItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id')->where('item_type', 'grooming');
    }

    /**
     * Relasi: Order memiliki banyak OrderItem (semua tipe)
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Alias untuk kompatibilitas kode lama: $order->items
     */
    public function items()
    {
        return $this->orderItems();
    }

    /**
     * Relasi: Order dimiliki oleh User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ──────────────────────────────────────────────────
    // 🔧 METHOD TAMBAHAN
    // ──────────────────────────────────────────────────

    /**
     * Cek apakah pesanan sudah dibayar
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid' || $this->payment_status === 'lunas';
    }

    /**
     * Cek apakah pembayaran gagal
     */
    public function isFailed(): bool
    {
        return $this->payment_status === 'failed' || $this->status === 'dibatalkan';
    }

    /**
     * Cek apakah pesanan kadaluarsa
     */
    public function isExpired(): bool
    {
        return $this->status === 'kadaluarsa' || $this->status === 'expired';
    }

    /**
     * Cek apakah pesanan dibatalkan
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled' || $this->status === 'dibatalkan';
    }

    /**
     * Dapatkan metode pembayaran dalam format rapi
     */
    public function getDisplayPaymentMethodAttribute(): string
    {
        $labels = [
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'bca_va' => 'BCA Virtual Account',
            'bni_va' => 'BNI Virtual Account',
            'mandiri_va' => 'Mandiri Virtual Account',
            'alfamart' => 'Pembayaran di Alfamart',
            'indomaret' => 'Pembayaran di Indomaret',
            'credit_card' => 'Kartu Kredit',
            'bank_transfer' => 'Transfer Bank',
        ];

        return $labels[$this->payment_method] ?? ucfirst(str_replace('_', ' ', $this->payment_method));
    }

    /**
     * Format total_amount ke Rupiah
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp' . number_format($this->total_amount, 0, ',', '.');
    }
}