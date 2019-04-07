<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dinner_slots extends Model
{
    //
    //
    use SoftDeletes;
    //
    protected $fillable = [
        'name', 'time_start', 'time_end',
    ];
    protected $table = 'dinner_slots';
}
