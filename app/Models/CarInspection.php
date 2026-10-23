<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarInspection extends Model
{
    protected $fillable = ['car_id', 'component', 'condition', 'notes'];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}