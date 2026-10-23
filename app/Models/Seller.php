<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    protected $fillable = [
        'user_id', 'store_name', 'slug', 'logo', 'description',
        'address', 'city', 'province', 'postal_code',
        'latitude', 'longitude', 'phone', 'whatsapp',
        'operational_hours', 'status',
        'identity_verified', 'phone_verified', 'email_verified', 'documents_verified',
        'rating_avg', 'rating_count', 'joined_at',
    ];

    protected $casts = [
        'identity_verified'  => 'boolean',
        'phone_verified'     => 'boolean',
        'email_verified'     => 'boolean',
        'documents_verified' => 'boolean',
        'rating_avg'         => 'decimal:2',
        'joined_at'          => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function documents()
    {
        return $this->hasMany(SellerDocument::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    // ========== Helper ==========
    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function getActiveCarsCountAttribute(): int
    {
        return $this->cars()->where('status', 'available')->count();
    }

    public function getSoldCarsCountAttribute(): int
    {
        return $this->cars()->where('status', 'sold')->count();
    }
}