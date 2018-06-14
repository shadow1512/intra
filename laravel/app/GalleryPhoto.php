<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GalleryPhoto extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image', 'gallery_id'
    ];

    protected $table = 'gallery_photos';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}