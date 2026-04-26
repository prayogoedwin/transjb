<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE simpan_pinjam MODIFY COLUMN tipe ENUM('bayar','bayar_cash','bayar_simpanan','hutang','transaksi','ambil','simpan','ambil_simpanan') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE simpan_pinjam SET tipe = 'bayar' WHERE tipe = 'bayar_cash'");
        DB::statement("ALTER TABLE simpan_pinjam MODIFY COLUMN tipe ENUM('bayar','bayar_simpanan','hutang','transaksi','ambil','simpan','ambil_simpanan') NULL");
    }
};
