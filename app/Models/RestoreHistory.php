<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestoreHistory extends Model
{
    protected $table = 'restore_history';

    protected $fillable = [
        'user_id',
        'status',
        'error_message',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the restore
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get date in readable format
     */
    public function getDateReadableAttribute(): string
    {
        return $this->created_at->format('d M Y H:i:s');
    }

    /**
     * Get user name
     */
    public function getUserNameAttribute(): ?string
    {
        return $this->user?->name;
    }
}
