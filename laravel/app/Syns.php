<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Syns extends Model
{
    //
    protected $fillable = [
        'term', 'syn'
    ];
    protected $table = 'syns';
}
