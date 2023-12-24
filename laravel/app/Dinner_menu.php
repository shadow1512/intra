<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dinner_menu extends Model
{
    //
    
    protected $fillable = [
        'date_menu', 'meals', 'type_meals', 'price_meals'
    ];
    protected $table = 'dinner_menu';
}
