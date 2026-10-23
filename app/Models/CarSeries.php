<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarSeries extends Model
{
    protected $table = 'car_series';

    protected $fillable = ['name', 'slug', 'category', 'image', 'order'];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}