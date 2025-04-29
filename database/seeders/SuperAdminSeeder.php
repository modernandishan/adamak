<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::where('name', 'super_admin')->first();

        if (!$role) {
            $role = Role::create([
                'name' => 'super_admin',
                'guard_name' => 'web',
            ]);
        }

        $user = User::create([
            'first_name' => 'Ahmad',
            'last_name' => 'Rohani',
            'mobile' => '09904861378',
            'mobile_verified_at' => date(now()),
            'password' => Hash::make('1qazxsw2'),
        ]);

        $user->roles()->attach($role);
    }
}
