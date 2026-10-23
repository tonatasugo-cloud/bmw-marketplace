<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'order_id', 'user_id', 'car_id', 'seller_id',
        'car_rating', 'seller_rating', 'experience_rating',
        'comment', 'seller_reply', 'replied_at',
    ];

    protected $casts = ['replied_at' => 'datetime'];

    // ✅ TAMBAHKAN INI
    protected static function booted()
    {
        // Setelah review dibuat → update rating seller
        static::created(function ($review) {
            $review->updateSellerRating();
        });

        // Setelah review dihapus → update rating seller
        static::deleted(function ($review) {
            $review->updateSellerRating();
        });
    }

    // ✅ TAMBAHKAN METHOD INI
    public function updateSellerRating()
    {
        $seller = $this->seller;
        if (!$seller) return;

        $avg = static::where('seller_id', $seller->id)
            ->selectRaw('AVG((car_rating + seller_rating + experience_rating) / 3) as avg')
            ->value('avg');

        $count = static::where('seller_id', $seller->id)->count();

        $seller->update([
            'rating_avg'   => round($avg ?? 0, 2),
            'rating_count' => $count,
        ]);
    }

    // Relasi (biarkan seperti sebelumnya)
    public function order()  { return $this->belongsTo(Order::class); }
    public function user()   { return $this->belongsTo(User::class); }
    public function car()    { return $this->belongsTo(Car::class); }
    public function seller() { return $this->belongsTo(Seller::class); }

    public function getAverageRatingAttribute(): float
    {
        return round((
            $this->car_rating +
            $this->seller_rating +
            $this->experience_rating
        ) / 3, 1);
    }
}