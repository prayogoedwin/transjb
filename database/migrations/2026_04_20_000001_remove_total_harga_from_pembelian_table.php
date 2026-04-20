<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('pembelian', 'total_harga')) {
            Schema::table('pembelian', function (Blueprint $table) {
                $table->dropColumn('total_harga');
            });
        }
    }

    public function down(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            $table->decimal('total_harga', 15, 2)->after('berat_setelah_potong')->default(0);
        });
    }
};
