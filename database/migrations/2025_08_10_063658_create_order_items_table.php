<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('item_id');   // id produk atau grooming
            $table->string('item_type');             // 'product' atau 'grooming'
            $table->string('name');                  // simpan nama produk/grooming saat transaksi
            $table->integer('quantity')->default(1);
            $table->decimal('price', 15, 2);         // harga satuan
            $table->decimal('subtotal', 15, 2);      // total per item (price * quantity)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
