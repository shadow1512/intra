<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terms extends Model
{
    //
    protected $fillable = [
        'term', 'baseterm', 'section', 'record'
    ];
    protected $table = 'terms';
}
