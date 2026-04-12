<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nasabah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nasabah';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'nama',
        'no_telp',
        'alamat',
    ];

    /**
     * Get the user associated with the nasabah.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the simpan pinjam records for the nasabah.
     */
    public function simpanPinjam(): HasMany
    {
        return $this->hasMany(SimpanPinjam::class, 'nasabah_id');
    }

    /**
     * Get the pembelian records for the nasabah.
     */
    public function pembelian(): HasMany
    {
        return $this->hasMany(Pembelian::class, 'nasabah_id');
    }
}
