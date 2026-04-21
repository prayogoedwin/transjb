<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('stok', 'penyesuaian_stok_id')) {
            Schema::table('stok', function (Blueprint $table) {
                $table->unsignedBigInteger('penyesuaian_stok_id')->nullable()->after('pembelian_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('stok', 'penyesuaian_stok_id')) {
            Schema::table('stok', function (Blueprint $table) {
                $table->dropColumn('penyesuaian_stok_id');
            });
        }
    }
};
