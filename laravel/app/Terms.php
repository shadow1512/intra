<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terms extends Model
{
    //
    protected $fillable = [
        'term', 'user_id', 'total', 'record'
    ];
    protected $table = 'terms';
}
