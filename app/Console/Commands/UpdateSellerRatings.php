<?php

namespace App\Console\Commands;

use App\Models\Seller;
use App\Models\Review;
use Illuminate\Console\Command;

class UpdateSellerRatings extends Command
{
    protected $signature = 'sellers:update-ratings';
    protected $description = 'Update rating_avg & rating_count semua seller berdasarkan review';

    public function handle()
    {
        $sellers = Seller::all();
        $this->info("🔄 Update rating untuk {$sellers->count()} seller...");
        $this->newLine();

        $bar = $this->output->createProgressBar($sellers->count());
        $bar->start();

        foreach ($sellers as $seller) {
            $avg = Review::where('seller_id', $seller->id)
                ->selectRaw('AVG((car_rating + seller_rating + experience_rating) / 3) as avg')
                ->value('avg');

            $count = Review::where('seller_id', $seller->id)->count();

            $seller->update([
                'rating_avg'   => round($avg ?? 0, 2),
                'rating_count' => $count,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Tampilkan hasil
        $this->table(
            ['Seller', 'Rating', 'Total Review'],
            Seller::all(['store_name', 'rating_avg', 'rating_count'])
                ->map(fn($s) => [
                    $s->store_name,
                    number_format($s->rating_avg, 2) . ' ⭐',
                    $s->rating_count,
                ])
                ->toArray()
        );

        $this->info('✅ Selesai! Rating seller berhasil diupdate.');
        return 0;
    }
}