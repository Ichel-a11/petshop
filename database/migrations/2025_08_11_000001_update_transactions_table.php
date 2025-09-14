<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Remove old columns
            $table->dropColumn(['kode_transaksi', 'nama_pemesan']);
            
            // Add new columns
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('payment_url')->nullable();
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Revert changes
            $table->string('kode_transaksi')->unique();
            $table->string('nama_pemesan');
            
            $table->dropForeign(['order_id']);
            $table->dropColumn([
                'order_id',
                'amount',
                'payment_id',
                'payment_type',
                'payment_url'
            ]);
        });
    }
};
