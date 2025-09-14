<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
      Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('nama');
    $table->text('alamat');
    $table->string('no_hp', 20);
    $table->decimal('total_price', 15, 2)->default(0);
    $table->string('status')->default('pending'); // pending, completed, canceled
    $table->string('payment_status')->default('unpaid'); // unpaid, paid
    $table->string('payment_method')->nullable(); // misal: transfer, midtrans
    $table->string('payment_proof')->nullable();  // bukti transfer
    $table->string('customer_email')->nullable();
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
