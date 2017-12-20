<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibBook extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'authors', 'year', 'anno', 'file', 'image'
    ];

    protected $table = 'lib_books';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}