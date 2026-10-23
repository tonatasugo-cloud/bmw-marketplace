<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Car extends Model
{
    protected $fillable = [
        'seller_id', 'car_series_id', 'title', 'slug', 'year', 'price', 'mileage',
        'transmission', 'fuel_type', 'engine_capacity', 'drivetrain',
        'exterior_color', 'interior_color', 'body_type', 'condition',
        'is_certified', 'location_city', 'location_province', 'plate_number',
        'vin', 'seat_capacity', 'door_count', 'description', 'feature_list',
        'status', 'is_featured', 'view_count', 'wishlist_count',
        'published_at', 'sold_at',
    ];

    protected $casts = [
        'feature_list'  => 'array',
        'is_certified'  => 'boolean',
        'is_featured'   => 'boolean',
        'published_at'  => 'datetime',
        'sold_at'       => 'datetime',
        'engine_capacity' => 'decimal:1',
    ];

    // ========== Auto slug ==========
    protected static function booted()
    {
        static::creating(function ($car) {
            if (empty($car->slug)) {
                $car->slug = Str::slug($car->title . '-' . uniqid());
            }
        });
    }

    // ========== Relasi ==========
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function series()
    {
        return $this->belongsTo(CarSeries::class, 'car_series_id');
    }

    public function images()
    {
        return $this->hasMany(CarImage::class)->orderBy('order');
    }

    public function primaryImage()
    {
        return $this->hasOne(CarImage::class)->where('is_primary', true);
    }

    public function inspections()
    {
        return $this->hasMany(CarInspection::class);
    }

    public function histories()
    {
        return $this->hasMany(CarHistory::class)->orderBy('order');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function testDrives()
    {
        return $this->hasMany(TestDrive::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ========== Scopes ==========
    public function scopeAvailable($q)
    {
        return $q->where('status', 'available');
    }

    public function scopeFeatured($q)
    {
        return $q->where('is_featured', true);
    }

    public function scopeCertified($q)
    {
        return $q->where('is_certified', true);
    }

    // ========== Accessor ==========
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedMileageAttribute(): string
    {
        return number_format($this->mileage, 0, ',', '.') . ' KM';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}