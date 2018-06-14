<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'published_at'
    ];

    protected $table = 'gallery';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}