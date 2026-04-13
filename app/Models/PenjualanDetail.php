<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenjualanDetail extends Model
{
    protected $table = 'penjualan_detail';

    protected $fillable = [
        'penjualan_id',
        'produk_id',
        'nama_produk',
        'harga_satuan',
        'satuan',
        'jumlah',
        'harga_total',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'jumlah' => 'decimal:4',
        'harga_total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the sale that owns this detail item.
     */
    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class);
    }

    /**
     * Get the product for this detail item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }

    public function decreaseStock(): Stok
    {
        return Stok::create([
            'produk_id' => $this->produk_id,
            'penjualan_detail_id' => $this->id,
            'jumlah' => $this->jumlah,
            'satuan' => $this->satuan,
            'transaksi' => 'out',
        ]);
    }
}
