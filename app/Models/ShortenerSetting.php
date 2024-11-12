<?php

namespace App\Models;

use Filament\Forms\Components\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortenerSetting extends Model
{
    use HasFactory;

    public function shortener(): BelongsTo
    {
        return $this->belongsTo(Shortener::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
