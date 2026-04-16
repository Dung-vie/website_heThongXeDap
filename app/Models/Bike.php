<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bike extends Model
{
    //
    protected $fillable = [
        'plate_number',
        'current_location',
        'station_id',
        'status'
    ];

    public function station() {
        return $this->belongsTo(Station::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function isRented() {
        return Rental::where('bike_id', $this->id)
                     ->where('status', 'active')->exists();
    }

    public function avgRating() {
        return $this->reviews()->avg('bike_rating') ?? 0;
    }


}
