<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $admin = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'password' => Hash::make('password'),
        ]);

        $moderator = User::factory()->create([
            'name' => 'Jackson Green',
            'email' => 'jack@test.com',
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole('Admin');
        $moderator->assignRole('Moderator');
    }
}
