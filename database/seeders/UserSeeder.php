<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin'
        ]);

        // Customer
        User::create([
            'name' => 'Customer Test',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'customer'
        ]);

        // Sopir
        User::create([
            'name' => 'Sopir Test',
            'email' => 'sopir@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'sopir'
        ]);
    }
}