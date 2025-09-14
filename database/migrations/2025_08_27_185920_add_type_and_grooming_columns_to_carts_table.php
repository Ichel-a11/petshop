<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
             // Tambahkan kolom type jika belum ada
            $table->string('type')->default('product')->after('user_id');
            
            // Tambahkan kolom grooming_booking_id jika belum ada
            $table->foreignId('grooming_booking_id')
                  ->nullable()
                  ->constrained('grooming_bookings')
                  ->onDelete('cascade')
                  ->after('product_id');
                  
            // Buat index untuk performa
            $table->index(['user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            //
        });
    }
};
