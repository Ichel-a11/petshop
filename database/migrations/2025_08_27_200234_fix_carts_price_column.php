<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Tambahkan kolom price yang hilang
            if (!Schema::hasColumn('carts', 'price')) {
                $table->decimal('price', 10, 2)->after('quantity');
            }
            
            // Perbaiki kolom quantity (hapus default)
            DB::statement('ALTER TABLE carts MODIFY quantity INT NOT NULL');
            
            // Perbaiki kolom product_id (buat nullable)
            DB::statement('ALTER TABLE carts MODIFY product_id BIGINT UNSIGNED NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            if (Schema::hasColumn('carts', 'price')) {
                $table->dropColumn('price');
            }
            
            // Kembalikan ke struktur sebelumnya jika perlu
            DB::statement('ALTER TABLE carts MODIFY quantity INT NOT NULL DEFAULT 1');
            DB::statement('ALTER TABLE carts MODIFY product_id BIGINT UNSIGNED NOT NULL');
        });
    }
};
