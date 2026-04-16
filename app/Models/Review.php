<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $fillable = [
        'user_id', 'rental_id', 'bike_id', 'station_id',
         'bike_rating', 'bike_comment', 'station_rating', 'station_comment'
    ];

    function user() {
        return $this->belongsTo(User::class);
    }

    function station() {
        return $this->belongsTo(Station::class);
    }

    function bike() {
        return $this->belongsTo(Bike::class);
    }

    function Rental() {
        return $this->belongsTo(Rental::class);
    }


}
