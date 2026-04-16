<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    //
    protected $fillable = [
        'name',
        'ward_code',
        'address',
        'slots',
        'status'
    ];

    function bikes() {
        return $this->hasMany(Bike::class);
    }

    function reviews() {
        return $this->hasMany(Review::class);
    }

    function emptySlot() {
        $temp = $this->bike()->whereNotNull('station_id')->count();
        return $this->slots - $temp;
    }

    function avgReviews() {
        return $this->review()->avg('station_rating') ?? 0;
    }

    function availableBikes() {
        return $this->bike()->where('status', 'normal')->whereNotNull('station_id')->whereNotIn('id', function($q) {
            $q->select('bike_id')->from('rentals')->where('status', 'active');
        })->count();
    }

}

