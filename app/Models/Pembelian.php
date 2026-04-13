<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembelian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pembelian';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nasabah_id',
        'produk_id',
        'harga_satuan_beli',
        'satuan',
        'total_berat',
        'total_harga',
        'biaya_admin_persen',
        'biaya_admin',
        'harga_akhir',
        'keterangan',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'harga_satuan_beli' => 'decimal:2',
            'total_berat' => 'decimal:4',
            'total_harga' => 'decimal:2',
            'biaya_admin_persen' => 'decimal:2',
            'biaya_admin' => 'decimal:2',
            'harga_akhir' => 'decimal:2',
        ];
    }

    /**
     * Get the product associated with the pembelian.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }

    /**
     * Get the nasabah associated with the pembelian.
     */
    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }

    /**
     * Get the stok entry associated with this pembelian.
     */
    public function stok(): HasOne
    {
        return $this->hasOne(Stok::class, 'pembelian_id');
    }

    /**
     * Add stok dari pembelian after invoice printed.
     */
    public function addToStok(): Stok
    {
        return Stok::create([
            'produk_id' => $this->produk_id,
            'pembelian_id' => $this->id,
            'jumlah' => $this->total_berat,
            'satuan' => $this->satuan,
            'transaksi' => 'in',
        ]);
    }
}
