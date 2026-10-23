<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'order_id', 'driver_name', 'driver_phone', 'vehicle_number',
        'origin_address', 'destination_address',
        'estimated_arrival', 'delivered_at', 'proof_path', 'status',
    ];

    protected $casts = [
        'estimated_arrival' => 'datetime',
        'delivered_at'      => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function trackings()
    {
        return $this->hasMany(ShipmentTracking::class)->latest();
    }
}