<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Tambah kolom fleksibel
            $table->unsignedBigInteger('item_id')->nullable()->after('order_id');
            $table->string('item_type')->nullable()->after('item_id'); // 'product'|'grooming'
        });

        // Backfill data lama (anggap data lama adalah produk)
        DB::table('order_items')->update([
            'item_id' => DB::raw('product_id'),
            'item_type' => 'product',
        ]);

        // Opsional: setelah data aman, boleh drop kolom lama
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'product_id')) {
                $table->dropConstrainedForeignId('product_id'); // drop FK + kolom (Laravel 9+)
                // Jika versi lama: $table->dropForeign(['product_id']); $table->dropColumn('product_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Rollback sederhana: tambahkan kembali product_id
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->dropColumn(['item_id', 'item_type']);
        });
    }
};
