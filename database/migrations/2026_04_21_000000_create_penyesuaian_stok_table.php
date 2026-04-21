<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyesuaian_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['in', 'out']);
            $table->decimal('jumlah', 15, 4);
            $table->string('satuan', 50)->default('kg');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->index('produk_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyesuaian_stok');
    }
};
