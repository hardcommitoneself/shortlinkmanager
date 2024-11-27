<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteShortenerSetting extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => 'boolean',
    ];

    public function shortener_setting(): BelongsTo
    {
        return $this->belongsTo(ShortenerSetting::class, 'shortener_settings_id');
    }

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }
}
