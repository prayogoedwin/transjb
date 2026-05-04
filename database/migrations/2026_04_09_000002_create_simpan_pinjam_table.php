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
        Schema::create('simpan_pinjam', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabah')->cascadeOnDelete();
            $table->enum('tipe', ['bayar', 'hutang', 'transaksi', 'ambil'])->nullable();
            $table->decimal('nominal', 15, 2);
            $table->unsignedBigInteger('pembelian_id')->nullable();
            $table->string('keterangan');
            $table->timestamps();
            $table->softDeletes();
            $table->index('nasabah_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpan_pinjam');
    }
};
