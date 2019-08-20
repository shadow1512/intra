<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profiles_Saved extends Model
{
    //
    protected $table = 'profiles_saved';
    use SoftDeletes;
}
