<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'nama_produk' => 'Sawit',
                'satuan' => 'kg',
                'harga_beli' => 0,
                'tanggal_update_harga_beli' => now(),
                'harga_jual' => 0,
                'tanggal_update_harga_jual' => now(),
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['nama_produk' => $product['nama_produk']],
                $product
            );
        }
    }
}
