<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRecord extends Model
{
    protected $fillable = [
        'user_id', 'car_id', 'type', 'mileage',
        'service_date', 'notes', 'cost', 'workshop',
    ];

    protected $casts = [
        'service_date' => 'date',
        'cost'         => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}