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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('products')->cascadeOnDelete();
            $table->decimal('harga_satuan_beli', 15, 2);
            $table->string('satuan', 50)->default('kg');
            $table->decimal('total_berat', 15, 4);
            $table->decimal('total_harga', 15, 2);
            $table->decimal('biaya_admin_persen', 5, 2)->default(0);
            $table->decimal('biaya_admin', 15, 2)->default(0);
            $table->decimal('harga_akhir', 15, 2);
            $table->timestamps();
            $table->softDeletes();
            $table->index('produk_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
