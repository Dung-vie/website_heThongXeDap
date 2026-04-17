<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    //

    protected $fillable = [
        'user_id', 'bike_id', 'rent_station_id', 'return_station_id',
        'rent_at', 'return_at', 'price', 'total_mins', 'status'
    ];

    protected $casts = ['rent_at' => 'datetime', 'return_at' => 'datetime'];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function bike()
    {
        return $this->belongsTo(Bike::class);
    }

    function rentStation()
    {
        return $this->belongsTo(Station::class, 'rent_station_id');
    }

    function returnStation()
    {
        return $this->belongsTo(Station::class, 'return_station_id');
    }

    function review()
    {
        return $this->hasOne(Review::class);
    }

    function minutesElapsed() {
        return now()->diffInMinutes($this->rent_id);
    }

    function currentAmount() {
        return $this->minutesElapsed() * $this->price;
    }
}
