<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CarSeriesSeeder::class,
            SellerSeeder::class,
            CarSeeder::class,
            DemoSeeder::class,   // ← tambah ini
        ]);
    }
}