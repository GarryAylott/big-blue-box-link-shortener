<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Link extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'short_slug',
        'target_url',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function clicks(): HasMany
    {
        return $this->hasMany(Click::class, 'slug', 'short_slug');
    }

    public function totalClicks(): int
    {
        return $this->clicks()->count();
    }

    public function getShortUrlAttribute(): string
    {
        return config('app.url') . '/' . $this->short_slug;
    }
}
