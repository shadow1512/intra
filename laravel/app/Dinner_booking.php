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
}
