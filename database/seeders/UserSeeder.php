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
        User::updateOrCreate(
            ['email' => 'administrator@pos.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'administrator',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@pos.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }
}
