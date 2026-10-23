<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Seller;
use App\Models\Car;
use App\Models\Review;
use App\Models\Order;
use App\Models\Conversation;
use App\Models\Message;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ============ BUYER DUMMY ============
        $buyer = User::whereHas('roles', fn($q) => $q->where('name', 'buyer'))->first();
        if (!$buyer) {
            $buyer = User::create([
                'name'     => 'Andi Wijaya',
                'email'    => 'andi@demo.test',
                'password' => bcrypt('password'),
                'status'   => 'active',
            ]);
            $buyer->assignRole('buyer');
        }

        $sellers = Seller::with('user')->get();
        $cars = Car::all();

        if ($cars->isEmpty()) {
            $this->command->error('Tidak ada mobil. Jalankan CarSeeder dulu.');
            return;
        }

        // ============ BIKIN ORDER DUMMY (untuk review) ============
        $dummyOrders = [];
        foreach ($cars->take(4) as $i => $car) {
            $order = Order::firstOrCreate(
                ['order_number' => 'ORD-DEMO-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT)],
                [
                    'user_id'           => $buyer->id,
                    'car_id'            => $car->id,
                    'seller_id'         => $car->seller_id,
                    'car_price'         => $car->price,
                    'booking_fee'       => 10_000_000,
                    'total_paid'        => $car->price,
                    'remaining_payment' => 0,
                    'status'            => 'completed',
                    'delivery_method'   => 'pickup',
                    'paid_at'           => now()->subDays(rand(10, 60)),
                    'completed_at'      => now()->subDays(rand(1, 9)),
                ]
            );
            $dummyOrders[$car->id] = $order;
        }

        // ============ REVIEW DUMMY ============
        $comments = [
            'Pelayanan ramah, mobil sesuai deskripsi. Recommended!',
            'Proses cepat, dokumen lengkap. Puas beli di sini.',
            'Mobil mulus, harga sesuai. Terima kasih!',
            'Seller profesional, komunikasi lancar.',
            'Unit bersih, sudah diinspeksi dengan detail.',
        ];

        foreach ($dummyOrders as $carId => $order) {
            $car = $cars->find($carId);
            if (!$car) continue;

            Review::firstOrCreate(
                [
                    'user_id'   => $buyer->id,
                    'car_id'    => $car->id,
                    'seller_id' => $car->seller_id,
                ],
                [
                    'order_id'          => $order->id,
                    'car_rating'        => rand(4, 5),
                    'seller_rating'     => rand(4, 5),
                    'experience_rating' => rand(4, 5),
                    'comment'           => $comments[array_rand($comments)],
                ]
            );
        }

        // ============ CHAT DUMMY ============
        $firstCar = $cars->first();
        if ($firstCar && $firstCar->seller) {
            $conv = Conversation::firstOrCreate(
                [
                    'buyer_id'  => $buyer->id,
                    'seller_id' => $firstCar->seller_id,
                    'car_id'    => $firstCar->id,
                ],
                ['last_message_at' => now()]
            );

            if ($conv->messages()->count() === 0) {
                $messages = [
                    [$buyer->id,                 'Halo, BMW ini masih tersedia?'],
                    [$firstCar->seller->user_id, 'Halo kak! Masih tersedia.'],
                    [$buyer->id,                 'Bisa lihat unit besok?'],
                    [$firstCar->seller->user_id, 'Bisa kak, jam 10 pagi oke?'],
                    [$buyer->id,                 'Oke, saya datang besok ya.'],
                    [$firstCar->seller->user_id, 'Siap kak, ditunggu!'],
                ];
                foreach ($messages as [$sender, $body]) {
                    Message::create([
                        'conversation_id' => $conv->id,
                        'sender_id'       => $sender,
                        'body'            => $body,
                        'is_read'         => true,
                    ]);
                }
            }
        }

        $this->command->info('✅ DemoSeeder: ' . count($dummyOrders) . ' order + review + chat dibuat.');
    }
}