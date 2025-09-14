<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@petshop.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Customer
        User::updateOrCreate(
            ['email' => 'customer@petshop.com'],
            [
                'name' => 'Customer',
                'password' => Hash::make('customer123'),
                'role' => 'customer',
            ]
        );

        // Seeder kategori (hanya pakai 'name')
        $this->call(CategorySeeder::class);

           // Seeder services
        $this->call(ServiceSeeder::class);
    }
}
