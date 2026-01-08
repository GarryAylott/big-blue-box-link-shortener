<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Click extends Model
{
    const CREATED_AT = 'clicked_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'slug',
        'referrer',
        'user_agent',
        'ip_address',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class, 'slug', 'short_slug');
    }
}
