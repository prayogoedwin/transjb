<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    use SoftDeletes;

    protected $table = 'penjualan';

    protected $fillable = [
        'customer_id',
        'nama_customer',
        'total_pembelian',
        'nopol',
        'keterangan',
    ];

    protected $casts = [
        'total_pembelian' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the customer (nasabah) that owns the sale.
     */
    // public function nasabah(): BelongsTo
    // {
    //     return $this->belongsTo(Nasabah::class, 'customer_id');
    // }

    /**
     * Get the detail items for this sale.
     */
    public function details(): HasMany
    {
        return $this->hasMany(PenjualanDetail::class);
    }
}
