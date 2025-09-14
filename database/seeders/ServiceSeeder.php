<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Grooming',
                'price' => 50000,
                'description' => 'Layanan grooming untuk hewan peliharaan agar selalu bersih dan sehat.'
            ]
        );

        Service::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Penitipan',
                'price' => 100000,
                'description' => 'Layanan penitipan hewan peliharaan dengan fasilitas nyaman.'
            ]
        );

        Service::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Vaksinasi',
                'price' => 75000,
                'description' => 'Layanan vaksinasi hewan untuk menjaga kesehatan.'
            ]
        );
    }
}
