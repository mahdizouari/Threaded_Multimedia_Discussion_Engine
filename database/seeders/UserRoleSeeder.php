<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'username' => 'admin_pulse',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        Profile::create([
            'user_id' => $admin->id,
            'first_name' => 'Admin',
            'last_name' => 'User',
            'interests' => ['management','moderation'],
        ]);

        // Moderator
        $mod = User::create([
            'name' => 'Moderator User',
            'email' => 'moderator@example.com',
            'username' => 'mod_pulse',
            'password' => Hash::make('password'),
            'role' => 'moderator',
        ]);

        Profile::create([
            'user_id' => $mod->id,
            'first_name' => 'Moderator',
            'last_name' => 'User',
            'interests' => ['community','support'],
        ]);
    }
}

