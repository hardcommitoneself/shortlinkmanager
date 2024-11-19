<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\JoinClause;

class WebsiteShortenerSetting extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => 'boolean'
    ];

    public function shortener_setting(): BelongsTo
    {
        return $this->belongsTo(ShortenerSetting::class);
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }
}
