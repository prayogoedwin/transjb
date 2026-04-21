<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PenyesuaianStok extends Model
{
    use HasFactory;

    protected $table = 'penyesuaian_stok';

    protected $fillable = [
        'produk_id',
        'user_id',
        'status',
        'jumlah',
        'satuan',
        'keterangan',
    ];

    protected $appends = ['created_at_id'];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:4',
        ];
    }

    public function getCreatedAtIdAttribute(): string
    {
        return Carbon::parse($this->created_at)->locale('id')->translatedFormat('d F Y H:i');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function stok(): HasOne
    {
        return $this->hasOne(Stok::class, 'penyesuaian_stok_id');
    }

    public function updateStokProduk(): Stok
    {
        return Stok::create([
            'produk_id'           => $this->produk_id,
            'penyesuaian_stok_id' => $this->id,
            'transaksi'           => $this->status,
            'jumlah'              => $this->jumlah,
            'satuan'              => $this->satuan,
        ]);
    }
}
