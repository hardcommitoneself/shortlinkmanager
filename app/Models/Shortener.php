<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\JoinClause;

class Shortener extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'api_link',
        'views',
        'cpm',
        'referral',
        'demo',
        'withdraw',
        'status'
    ];

    public function settings(): HasMany
    {
        return $this->hasMany(ShortenerSetting::class);
    }

    public function setting()
    {
        return $this->settings()->where('user_id', Auth::user()->id)->first();
    }

    public function scopeActiveShorteners(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function isSettingExisted(): bool
    {
        return !!$this->setting();
    }

    public function scopeProperShorteners(Builder $query): Builder
    {
        return $query
            ->leftJoin('shortener_settings', function (JoinClause $join) {
                $join
                    ->on('shorteners.id', '=', 'shortener_settings.shortener_id')
                    ->where('shortener_settings.user_id', Auth::user()->id);
            })
            ->select('shorteners.*')
            ->selectRaw('COUNT(shortener_settings.id) as settings_count')
            ->groupBy('shorteners.id');
    }
}
