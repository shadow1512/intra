<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profiles_Saved_Data extends Model
{
    //
    protected $table = 'profiles_saved_data';
    use SoftDeletes;
}
