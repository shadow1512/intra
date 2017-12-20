<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'room_id', 'user_id', 'date_book', 'time_start', 'time_end'
    ];

    protected $table = 'room_bookings';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}