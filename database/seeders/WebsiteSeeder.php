<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Website;

class WebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Website::create([
            'name' => 'GR8 Faucet',
            'url' => 'https://gr8.cc/demo/faucet',
            'user_id' => 1
        ]);

        Website::create([
            'name' => 'Google',
            'url' => 'https://google.com',
            'user_id' => 1
        ]);
    }
}
