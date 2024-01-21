<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dinner_menu_complex extends Model
{
    //
    protected $fillable = [
        'date_menu_complex', 'meals_complex', 'price_meals_complex'
    ];
    protected $table = 'dinner_menu_complex';
}
