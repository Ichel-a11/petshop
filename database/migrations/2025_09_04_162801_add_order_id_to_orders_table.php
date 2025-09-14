<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom order_id ke tabel orders
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Cek apakah kolom belum ada
            if (!Schema::hasColumn('orders', 'order_id')) {
                $table->string('order_id', 50)->after('id')->unique()->nullable();
                // Kita buat nullable dulu agar bisa isi data lama
            }
        });
    }

    /**
     * Hapus kolom order_id
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['order_id']); // Hapus unique key
            $table->dropColumn('order_id');
        });
    }
};