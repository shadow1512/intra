<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deps_Peoples extends Model
{
    //
    use SoftDeletes;

    protected $table = 'deps_peoples';
}
