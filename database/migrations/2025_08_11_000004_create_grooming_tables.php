<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grooming_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('duration_minutes')->default(60); // Durasi layanan dalam menit
            $table->string('pet_type'); // jenis hewan: cat/dog
            $table->string('pet_size')->nullable(); // small/medium/large
            $table->string('image')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        Schema::create('grooming_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('grooming_service_id')->constrained('grooming_services')->onDelete('cascade');
            $table->dateTime('booking_time');
            $table->string('pet_name');
            $table->string('pet_type');
            $table->string('pet_size');
            $table->text('notes')->nullable();
            $table->string('status')->default('pending'); // pending/confirmed/completed/cancelled
            $table->decimal('total_price', 10, 2);
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('grooming_bookings');
        Schema::dropIfExists('grooming_services');
    }
};
