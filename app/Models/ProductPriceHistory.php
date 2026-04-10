<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPriceHistory extends Model
{
    /** @use HasFactory<\Database\Factories\ProductPriceHistoryFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'produk_id',
        'harga_beli_before',
        'tanggal_update_harga_beli',
        'harga_jual_before',
        'tanggal_update_harga_jual',
        'created_by',
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
     * Get the product associated with the price history.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }

    /**
     * Get the user who created the price history.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
