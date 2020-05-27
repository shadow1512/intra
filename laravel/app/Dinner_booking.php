<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dinner_booking extends Model
{
    //
    use SoftDeletes;
    //
    protected $table = 'dinner_booking';

    public static function getRecordByUserAndDate($user_id,    $date_record) {
        return Dinner_booking::whereDate('date_created',    '=',    $date_record)->where('user_id', '=',    $user_id)->first();
    }
}
