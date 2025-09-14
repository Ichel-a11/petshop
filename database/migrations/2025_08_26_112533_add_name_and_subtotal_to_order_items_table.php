<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('order_items', function (Blueprint $table) {
        $table->string('name')->after('item_type');
        $table->decimal('subtotal', 15, 2)->after('price');
    });
}

public function down(): void
{
    Schema::table('order_items', function (Blueprint $table) {
        $table->dropColumn(['name', 'subtotal']);
    });
}

};
