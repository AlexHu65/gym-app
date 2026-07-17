<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@gym.local')],
            [
                'name' => env('ADMIN_NAME', 'Gym Admin'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'Password123!')),
            ]
        );

        $superAdminRole = Role::where('name', 'super-admin')->first();

        if ($superAdminRole) {
            $user->roles()->syncWithoutDetaching([$superAdminRole->id]);
        }
    }
}

