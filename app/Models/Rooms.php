<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    //
    protected $fillable = [
        'room_number',
        'room_type',
        'price',
        'description',
        'image',
        'status',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_id');
    }
}
