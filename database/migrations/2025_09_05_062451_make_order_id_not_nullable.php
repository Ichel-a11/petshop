<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Pastikan tidak ada yang null
        \DB::statement("UPDATE orders SET order_id = CONCAT('INV-', LPAD(id, 6, '0')) WHERE order_id IS NULL");

        // Ubah jadi not null
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_id', 50)->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_id', 50)->nullable()->change();
        });
    }
};