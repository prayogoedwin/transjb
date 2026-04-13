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
        Schema::table('stok', function (Blueprint $table) {
            // Add penjualan_detail_id if not exists
            if (!Schema::hasColumn('stok', 'penjualan_detail_id')) {
                $table->foreignId('penjualan_detail_id')
                    ->nullable()
                    ->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stok', function (Blueprint $table) {
            if (Schema::hasColumn('stok', 'penjualan_detail_id')) {
                $table->dropColumn('penjualan_detail_id');
            }
        });
    }
};
