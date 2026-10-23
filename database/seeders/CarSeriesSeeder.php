<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarSeries;
use Illuminate\Support\Str;

class CarSeriesSeeder extends Seeder
{
    public function run(): void
    {
        $series = [
            ['name' => '1 Series', 'category' => 'Hatchback'],
            ['name' => '2 Series', 'category' => 'Coupe'],
            ['name' => '3 Series', 'category' => 'Sedan'],
            ['name' => '4 Series', 'category' => 'Coupe'],
            ['name' => '5 Series', 'category' => 'Sedan'],
            ['name' => '6 Series', 'category' => 'Coupe'],
            ['name' => '7 Series', 'category' => 'Luxury Sedan'],
            ['name' => '8 Series', 'category' => 'Grand Coupe'],
            ['name' => 'X1', 'category' => 'SUV'],
            ['name' => 'X3', 'category' => 'SUV'],
            ['name' => 'X5', 'category' => 'SUV'],
            ['name' => 'X7', 'category' => 'Luxury SUV'],
            ['name' => 'Z4', 'category' => 'Roadster'],
            ['name' => 'M Series', 'category' => 'Performance'],
        ];

        foreach ($series as $i => $s) {
            CarSeries::create([
                'name'     => $s['name'],
                'slug'     => Str::slug($s['name']),
                'category' => $s['category'],
                'order'    => $i,
            ]);
        }

        $this->command->info('✅ CarSeriesSeeder: ' . count($series) . ' series dibuat.');
    }
}