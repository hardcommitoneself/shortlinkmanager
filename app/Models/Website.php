<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Website extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'url',
        'original_api_key',
    ];

    // Model Event for generating the API key before saving
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($website) {
            $website->api_key = (string) Str::uuid();
            $website->user_id = Auth::user() ? (int) Auth::user()->id : 1;
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shortLinks(): HasMany
    {
        return $this->hasMany(ShortLink::class);
    }

    public function websiteShortenerSettings(): HasMany
    {
        return $this->hasMany(WebsiteShortenerSetting::class);
    }

    public function scopeMyWebsites(Builder $query): Builder
    {
        return $query->where('user_id', Auth::user()->id);
    }
}
