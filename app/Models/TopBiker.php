<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopBiker extends Model
{
    //

    protected $fillable = [
        'user_id', 'month', 'year', 'rank', 'total_mins', 'total_rentals'
    ];

    function users() {
        return $this->belongsTo(User::class);
    }
}
