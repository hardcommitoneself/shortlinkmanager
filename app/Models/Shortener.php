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
            ->where('shorteners.status', true)
            ->selectRaw('COUNT(shortener_settings.id) as settings_count')
            ->groupBy('shorteners.id');
    }

    public function fixWithdrawFormat($withdraw): string
    {
        preg_match_all('/(?P<price>((?:\d{1,3}[,\.]?)+\d{2}))\sfor\s(?P<method>(.*?))\swithdrawals/', $withdraw, $output_array, PREG_SET_ORDER);

        if($output_array){
            foreach($output_array as $val){
                $output[] = '<b>'.$val['method'].':</b> $'.number_format($val['price'], 2, '.', '');
            }

            sort($output);
            $withdraw = implode(', ',$output);
        }

        else {

            preg_match_all('/(?P<method>(.*?))[\t]\$?(?P<price>((?:\d{1,4}[,\.]?)+\d{2}))/', $withdraw, $output_array, PREG_SET_ORDER);

            if($output_array){
                foreach($output_array as $val){
                    $output[] = '<b>'.trim($val['method']).':</b> $'.number_format($val['price'], 2, '.', '');
                }

                sort($output);
                $withdraw = implode(', ',$output);
            }
        }

        $withdraw = 'Test';

        return $withdraw;
    }
}
