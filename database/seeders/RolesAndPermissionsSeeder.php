<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        Role::create([
            'name' => 'user',
            'guard_name' => 'web',
        ]);
        Role::create([
            'name' => 'marketer',
            'guard_name' => 'web',
        ]);
        Role::create([
            'name' => 'consultant',
            'guard_name' => 'web',
        ]);
        Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);
        Role::create([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);
    }

}
