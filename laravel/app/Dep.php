<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dep extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'parent_id', 'description', 'image'
    ];

    protected $table = 'deps';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}