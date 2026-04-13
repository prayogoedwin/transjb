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
        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualan')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('products')->cascadeOnDelete();
            $table->string('nama_produk', 255);
            $table->decimal('harga_satuan', 15, 2);
            $table->string('satuan', 50)->default('kg');
            $table->decimal('jumlah', 15, 4);
            $table->decimal('harga_total', 15, 2);
            $table->timestamps();
            $table->index(['penjualan_id', 'produk_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_detail');
    }
};
