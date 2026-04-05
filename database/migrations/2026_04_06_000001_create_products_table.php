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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->string('satuan')->default('kg');
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->timestamp('tanggal_update_harga_beli')->nullable();
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->timestamp('tanggal_update_harga_jual')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
