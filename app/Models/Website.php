<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

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

    public function scopeMyWebsites(Builder $query): Builder
    {
        return $query->where('user_id', Auth::user()->id);
    }
}
