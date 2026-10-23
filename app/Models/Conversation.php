<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['buyer_id', 'seller_id', 'car_id', 'last_message_at'];
    protected $casts = ['last_message_at' => 'datetime'];

    public function buyer()    { return $this->belongsTo(User::class, 'buyer_id'); }
    public function seller()   { return $this->belongsTo(Seller::class); }
    public function car()      { return $this->belongsTo(Car::class); }
    public function messages() { return $this->hasMany(Message::class)->latest(); }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}