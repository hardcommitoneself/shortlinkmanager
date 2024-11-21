<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShortenerSetting extends Model
{
    use HasFactory;

    public function shortener(): BelongsTo
    {
        return $this->belongsTo(Shortener::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function website_shortener_settings(): HasMany
    {
        return $this->hasMany(WebsiteShortenerSetting::class);
    }

    public function scopeMyShorteners(Builder $query): Builder
    {
        return $query->where('user_id', Auth::user()->id);
    }

    public function scopeProperShortenerSettings(Builder $query, int $website_id): Builder
    {
        return $query
            ->leftJoin('website_shortener_settings', fn (JoinClause $join) => $join
                ->on('website_shortener_settings.shortener_settings_id', '=', 'shortener_settings.id')
                ->where('website_shortener_settings.website_id', $website_id)
            )
            ->where('user_id', Auth::user()->id)
            ->select([
                'shortener_settings.id as id',
                'shortener_settings.shortener_id as shortener_id',
                'shortener_settings.views as views',
                DB::raw('IFNULL(website_shortener_settings.status, false) as status'),
                DB::raw('IFNULL(website_shortener_settings.priority, 1000000) as priority'),
            ]);
    }
}
