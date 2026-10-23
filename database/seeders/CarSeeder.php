<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\CarSeries;
use App\Models\Seller;
use App\Models\CarImage;
use App\Models\CarInspection;
use App\Models\CarHistory;
use Illuminate\Support\Str;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $carsData = [
            // [series, judul, tahun, harga, km, warna, certified, featured, kondisi]
            ['3 Series', 'BMW 320i Sport Line', 2021, 625_000_000, 28_000, 'Alpine White', true, true, 'excellent'],
            ['3 Series', 'BMW 330i M Sport', 2022, 785_000_000, 15_000, 'Portimao Blue', true, true, 'excellent'],
            ['3 Series', 'BMW 320i Luxury Line', 2020, 545_000_000, 42_000, 'Black Sapphire', false, false, 'good'],
            ['5 Series', 'BMW 530i M Sport', 2021, 875_000_000, 35_000, 'Mineral White', true, true, 'excellent'],
            ['5 Series', 'BMW 520i Luxury', 2020, 695_000_000, 48_000, 'Sophisto Grey', true, false, 'good'],
            ['5 Series', 'BMW 530e Plug-in Hybrid', 2022, 1_050_000_000, 12_000, 'Phytonic Blue', true, true, 'excellent'],
            ['7 Series', 'BMW 730Li Pure Excellence', 2021, 1_650_000_000, 22_000, 'Black', true, true, 'excellent'],
            ['X1', 'BMW X1 sDrive18i', 2021, 545_000_000, 32_000, 'Cashmere Silver', false, false, 'good'],
            ['X3', 'BMW X3 xDrive30i M Sport', 2022, 985_000_000, 18_000, 'Phytonic Blue', true, true, 'excellent'],
            ['X5', 'BMW X5 xDrive40i M Sport', 2021, 1_385_000_000, 25_000, 'Black Sapphire', true, true, 'excellent'],
            ['X5', 'BMW X5 xDrive45e Hybrid', 2022, 1_595_000_000, 10_000, 'Mineral White', true, false, 'excellent'],
            ['X7', 'BMW X7 xDrive40i', 2021, 2_150_000_000, 30_000, 'Carbon Black', true, true, 'excellent'],
            ['M Series', 'BMW M3 Competition', 2021, 1_750_000_000, 12_000, 'Isle of Man Green', true, true, 'excellent'],
            ['M Series', 'BMW M4 Competition', 2022, 1_985_000_000, 8_000, 'Frozen Portimao Blue', true, true, 'excellent'],
            ['M Series', 'BMW M2 Coupe', 2020, 1_385_000_000, 25_000, 'Hockenheim Silver', true, false, 'good'],
            ['Z4', 'BMW Z4 sDrive30i', 2021, 985_000_000, 22_000, 'San Francisco Red', true, false, 'excellent'],
            ['4 Series', 'BMW 430i Convertible', 2021, 925_000_000, 30_000, 'Arctic Race Blue', false, false, 'good'],
            ['2 Series', 'BMW 220i Gran Coupe', 2020, 545_000_000, 38_000, 'Storm Bay Metallic', false, false, 'good'],
            ['1 Series', 'BMW 118i Sport', 2019, 365_000_000, 55_000, 'Melbourne Red', false, false, 'fair'],
            ['8 Series', 'BMW 840i Gran Coupe', 2022, 2_350_000_000, 6_000, 'Barcelona Blue', true, true, 'excellent'],
        ];

        $sellers = Seller::all();

        foreach ($carsData as $i => $data) {
            [$seriesName, $title, $year, $price, $km, $color, $certified, $featured, $condition] = $data;

            $series = CarSeries::where('name', $seriesName)->first();
            $seller = $sellers->random();

            $car = Car::create([
                'seller_id'         => $seller->id,
                'car_series_id'     => $series->id,
                'title'             => $title,
                'slug'              => Str::slug($title . '-' . $year . '-' . $i),
                'year'              => $year,
                'price'             => $price,
                'mileage'           => $km,
                'transmission'      => 'Automatic',
                'fuel_type'         => str_contains($title, 'Hybrid') ? 'Hybrid' : 'Petrol',
                'engine_capacity'   => str_contains($title, 'M3') || str_contains($title, 'M4') ? 3.0 : 2.0,
                'drivetrain'        => str_contains($title, 'xDrive') ? 'AWD' : 'RWD',
                'exterior_color'    => $color,
                'interior_color'    => 'Black Leather',
                'body_type'         => $series->category,
                'condition'         => $condition,
                'is_certified'      => $certified,
                'location_city'     => $seller->city,
                'location_province' => $seller->province,
                'seat_capacity'     => 5,
                'door_count'        => 4,
                'description'       => "BMW {$title} tahun {$year} dengan kondisi {$condition}. "
                                     . "Sudah melalui inspeksi 175 titik oleh teknisi berpengalaman. "
                                     . "Pajak panjang, surat lengkap, service record resmi BMW Indonesia. "
                                     . "Tersedia garansi mesin 1 tahun dan free jasa service 2x. "
                                     . "Cocok untuk penggunaan harian maupun koleksi.",
                'feature_list' => [
                    'Sunroof', 'Leather Seats', 'Apple CarPlay', 'Android Auto',
                    '360 Camera', 'Head-up Display', 'Harman Kardon Audio',
                    'Parking Sensor', 'Cruise Control', 'Keyless Entry',
                ],
                'status'         => 'available',
                'is_featured'    => $featured,
                'view_count'     => rand(50, 900),
                'wishlist_count' => rand(0, 50),
                'published_at'   => now()->subDays(rand(1, 60)),
            ]);

            // Buat gambar dummy (pakai placeholder image dari picsum)
            $imageCategories = ['exterior', 'interior', 'engine', 'dashboard', 'wheel'];
            foreach ($imageCategories as $j => $cat) {
                CarImage::create([
                    'car_id'     => $car->id,
                    'path'       => 'https://picsum.photos/seed/bmw' . $car->id . $j . '/800/600',
                    'category'   => $cat,
                    'is_primary' => $j === 0,
                    'order'      => $j,
                ]);
            }

            // Buat inspection report
            $components = ['Engine', 'Transmission', 'Brake', 'Suspension',
                          'Electrical', 'AC', 'Body', 'Interior'];
            foreach ($components as $comp) {
                CarInspection::create([
                    'car_id'    => $car->id,
                    'component' => $comp,
                    'condition' => $condition === 'excellent' ? 'good' : ($condition === 'good' ? 'good' : 'fair'),
                    'notes'     => 'Sudah dicek oleh teknisi',
                ]);
            }

            // Buat vehicle history
            $histories = [
                ['year' => $year, 'event' => 'Pembelian Pertama'],
                ['year' => $year + 1, 'event' => 'Servis Rutin Berkala'],
                ['year' => $year + 2, 'event' => 'Inspeksi Showroom'],
                ['year' => date('Y'), 'event' => 'Siap Dijual'],
            ];
            foreach ($histories as $k => $h) {
                CarHistory::create([
                    'car_id'      => $car->id,
                    'year'        => $h['year'],
                    'event'       => $h['event'],
                    'description' => 'Dokumen resmi tersedia',
                    'order'       => $k,
                ]);
            }
        }

        $this->command->info('✅ CarSeeder: ' . count($carsData) . ' mobil BMW dibuat.');
    }
}