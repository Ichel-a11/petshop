<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        if (!Schema::hasColumn('orders', 'payment_proof')) {
            $table->string('payment_proof')->nullable()->after('total_price');
        }

        // Jangan buat status lagi karena sudah ada
        // $table->enum('status', ['pending', 'waiting_verification', 'paid', 'failed'])
        //       ->default('pending')
        //       ->after('payment_proof');
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        if (Schema::hasColumn('orders', 'payment_proof')) {
            $table->dropColumn('payment_proof');
        }
        // Jangan drop status karena memang sudah ada sebelumnya
    });
}

};
