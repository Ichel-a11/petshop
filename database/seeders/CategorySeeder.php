<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Makanan Anjing',
            'Makanan Kucing',
            'Mainan Anjing',
            'Pasir Kucing',
            'Peralatan Anjing'
        ];

        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}
