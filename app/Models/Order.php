<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'car_id', 'seller_id',
        'car_price', 'booking_fee', 'total_paid', 'remaining_payment',
        'status', 'delivery_method', 'delivery_address', 'notes',
        'paid_at', 'completed_at',
    ];

    protected $casts = [
        'paid_at'      => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    // ============ RELASI ============

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // ============ ACCESSOR ============

    public function getFormattedCarPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->car_price, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'created'              => 'Dibuat',
            'payment_pending'      => 'Menunggu Pembayaran',
            'payment_verified'     => 'Pembayaran Diverifikasi',
            'reserved'             => 'Direservasi',
            'document_processing'  => 'Proses Dokumen',
            'ready_delivery'       => 'Siap Kirim',
            'delivered'            => 'Terkirim',
            'completed'            => 'Selesai',
            'cancelled'            => 'Dibatalkan',
            default                => ucfirst($this->status),
        };
    }
}