<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SellerSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = [
            [
                'store_name' => 'Bavaria Auto Premium',
                'email'      => 'seller1@bmw.test',
                'city'       => 'Jakarta Selatan',
                'province'   => 'DKI Jakarta',
                'address'    => 'Jl. Sudirman No. 123',
                'rating'     => 4.9,
                'reviews'    => 126,
            ],
            [
                'store_name' => 'BMW Specialist Surabaya',
                'email'      => 'seller2@bmw.test',
                'city'       => 'Surabaya',
                'province'   => 'Jawa Timur',
                'address'    => 'Jl. Darmo No. 45',
                'rating'     => 4.7,
                'reviews'    => 89,
            ],
            [
                'store_name' => 'Premier Motor Bandung',
                'email'      => 'seller3@bmw.test',
                'city'       => 'Bandung',
                'province'   => 'Jawa Barat',
                'address'    => 'Jl. Asia Afrika No. 78',
                'rating'     => 4.8,
                'reviews'    => 64,
            ],
            [
                'store_name' => 'Elite Cars Medan',
                'email'      => 'seller4@bmw.test',
                'city'       => 'Medan',
                'province'   => 'Sumatera Utara',
                'address'    => 'Jl. Gatot Subroto No. 22',
                'rating'     => 4.6,
                'reviews'    => 42,
            ],
        ];

        foreach ($sellers as $i => $s) {
            $user = User::create([
                'name'     => $s['store_name'],
                'email'    => $s['email'],
                'password' => Hash::make('password'),
                'phone'    => '0812345678' . $i,
                'status'   => 'active',
            ]);
            $user->assignRole('seller');

            Seller::create([
                'user_id'            => $user->id,
                'store_name'         => $s['store_name'],
                'slug'               => Str::slug($s['store_name']),
                'description'        => 'Showroom BMW pre-owned terpercaya dengan ratusan unit berkualitas.',
                'address'            => $s['address'],
                'city'               => $s['city'],
                'province'           => $s['province'],
                'phone'              => '021-5550' . $i,
                'whatsapp'           => '62812345678' . $i,
                'operational_hours'  => 'Senin-Sabtu 09:00-18:00',
                'status'             => 'verified',
                'identity_verified'  => true,
                'phone_verified'     => true,
                'email_verified'     => true,
                'documents_verified' => true,
                'rating_avg'         => $s['rating'],
                'rating_count'       => $s['reviews'],
                'joined_at'          => now()->subMonths(rand(6, 24)),
            ]);
        }

        $this->command->info('✅ SellerSeeder: ' . count($sellers) . ' seller dibuat.');
    }
}