<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 11.12.2017
 * Time: 0:23
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rooms extends Model
{
    //
    use SoftDeletes;
    //
    protected $fillable = [
        'name', 'available',
    ];
    protected $table = 'rooms';

}