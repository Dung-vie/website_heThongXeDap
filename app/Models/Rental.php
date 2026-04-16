<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    //

    protected $fillable = [
        'user_id', 'bike_id', 'pickup_station_id', 'return_station_id',
        'rented_at', 'return_at', 'price', 'total', 'status'
    ];

    protected $casts = ['rented_at' => 'datetime', 'return_at' => 'datetime'];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function bike()
    {
        return $this->belongsTo(Bike::class);
    }

    function pickupStation()
    {
        return $this->belongsTo(Station::class, 'pickup_station_id');
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
        return now()->diffInMinutes($this->rented_id);
    }

    function currentAmount() {
        return $this->minutesElapsed() * $this->price;
    }
}
