<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_produk',
        'satuan',
        'harga_beli',
        'tanggal_update_harga_beli',
        'harga_jual',
        'tanggal_update_harga_jual',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_update_harga_beli' => 'datetime',
            'tanggal_update_harga_jual' => 'datetime',
        ];
    }

    /**
     * Get the price histories for the product.
     */
    public function priceHistories(): HasMany
    {
        return $this->hasMany(ProductPriceHistory::class, 'produk_id');
    }

    /**
     * Get the stocks for the product.
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class, 'produk_id');
    }

    /**
     * Get the total stock quantity.
     */
    public function getTotalStockAttribute(): float
    {
        $inStock = $this->stocks()->where('transaksi', 'in')->sum('jumlah');
        $outStock = $this->stocks()->where('transaksi', 'out')->sum('jumlah');
        return $inStock - $outStock;
    }
}
