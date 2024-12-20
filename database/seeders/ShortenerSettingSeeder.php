<?php

namespace Database\Seeders;

use App\Models\Shortener;
use App\Models\ShortenerSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShortenerSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $shorteners = Shortener::all();

        foreach ($users as $user) {
            $priority = 1;

            foreach ($shorteners as $shortener) {
                $setting = new ShortenerSetting;

                $setting->api_key = '';
                $setting->user_id = $user->id;
                $setting->views = $shortener->views;
                $setting->priority = ++$priority;
                $setting->shortener_id = $shortener->id;

                $setting->save();
            }
        }
    }
}
