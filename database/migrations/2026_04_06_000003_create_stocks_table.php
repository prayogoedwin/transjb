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
        Schema::create('stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->nullable();
            $table->foreignId('pembelian_id')->nullable();
            $table->decimal('jumlah', 15, 4);
            $table->string('satuan', 50)->default('kg');
            $table->enum('transaksi', ['in', 'out'])->nullable();
            $table->timestamps();
            $table->index('produk_id');
            $table->index('pembelian_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok');
    }
};