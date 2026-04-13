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
        Schema::table('pembelian', function (Blueprint $table) {
            // Add nasabah_id if not exists
            if (!Schema::hasColumn('pembelian', 'nasabah_id')) {
                $table->foreignId('nasabah_id')
                    ->nullable()
                    ->constrained('nasabah')
                    ->cascadeOnDelete()
                    ->after('id');
            }

            // Add keterangan field
            if (!Schema::hasColumn('pembelian', 'keterangan')) {
                $table->text('keterangan')
                    ->nullable()
                    ->after('harga_akhir');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            if (Schema::hasColumn('pembelian', 'keterangan')) {
                $table->dropColumn('keterangan');
            }

            if (Schema::hasColumn('pembelian', 'nasabah_id')) {
                $table->dropForeign(['nasabah_id']);
                $table->dropColumn('nasabah_id');
            }
        });
    }
};
