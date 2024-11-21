<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'Admin']);
        $moderator = Role::create(['name' => 'Moderator']);
        $user = Role::create(['name' => 'User']);

        Permission::create(['name' => 'view dashboard']);
        Permission::create(['name' => 'view websites']);
        Permission::create(['name' => 'view shorteners']);
        Permission::create(['name' => 'view links']);
        Permission::create(['name' => 'view roles']);
        Permission::create(['name' => 'view permissions']);
        Permission::create(['name' => 'view quick-link']);
        Permission::create(['name' => 'view full-page-script']);
        Permission::create(['name' => 'view developers-api']);
        Permission::create(['name' => 'view admin-shorteners']);
        Permission::create(['name' => 'view users']);

        $admin->givePermissionTo(Permission::all());

        $moderator->givePermissionTo([
            'view dashboard',
            'view websites',
            'view shorteners',
            'view links',
            'view quick-link',
            'view full-page-script',
            'view developers-api',
        ]);

        $user->givePermissionTo([
            'view dashboard',
            'view websites',
            'view shorteners',
            'view links',
            'view quick-link',
            'view full-page-script',
            'view developers-api',
        ]);
    }
}
