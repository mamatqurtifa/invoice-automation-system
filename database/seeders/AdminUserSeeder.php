<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
        ]);

        $admin->assignRole('admin');

        $user = User::create([
            'name' => 'humtik',
            'email' => 'humtik@gmail.com',
            'password' => Hash::make('humtik123'),
        ]);

        $user->assignRole('user');
    }
}