<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus nullable, isi ulang jika perlu
            $table->string('order_id')->nullable(false)->change(); // pastikan tidak null
            $table->unique('order_id'); // ✅ sekarang aman
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['order_id']);
            $table->string('order_id')->nullable()->change();
        });
    }
};