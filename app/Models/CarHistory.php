<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarHistory extends Model
{
    protected $fillable = ['car_id', 'year', 'event', 'description', 'order'];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}