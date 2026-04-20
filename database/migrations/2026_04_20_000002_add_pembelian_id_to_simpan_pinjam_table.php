<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('simpan_pinjam', 'pembelian_id')) {
            Schema::table('simpan_pinjam', function (Blueprint $table) {
                $table->unsignedBigInteger('pembelian_id')->nullable()->after('nominal');
            });
        }
    }

    public function down(): void
    {
        Schema::table('simpan_pinjam', function (Blueprint $table) {
            $table->dropColumn('pembelian_id');
        });
    }
};
