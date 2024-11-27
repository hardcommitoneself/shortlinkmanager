<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;

class ShortLink extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'short_url',
        'original_url',
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function scopeMyShortLinks(Builder $query): Builder
    {
        return $query
            ->leftJoin('websites', function (JoinClause $join) {
                $join
                    ->on('short_links.website_id', '=', 'websites.id')
                    ->where('websites.user_id', Auth::user()->id);
            })
            ->select('short_links.*');
    }
}
