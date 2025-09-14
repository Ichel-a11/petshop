<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 public function up()
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->string('kode_transaksi')->unique();
        $table->string('nama_pemesan');
        $table->string('status')->default('pending');
        $table->timestamps();
    });
}
};
