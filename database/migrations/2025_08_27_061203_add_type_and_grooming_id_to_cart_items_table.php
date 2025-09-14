<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('cart_items', function (Blueprint $table) {
        $table->enum('type', ['product','grooming'])->default('product');
        $table->unsignedBigInteger('grooming_booking_id')->nullable();
    });
}

public function down()
{
    Schema::table('cart_items', function (Blueprint $table) {
        $table->dropColumn('type');
        $table->dropColumn('grooming_booking_id');
    });
}

};
