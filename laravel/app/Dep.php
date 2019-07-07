<?php

namespace App;

use App\Users_Moderators_Rules;
use App\User;
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

    public static function getModerate($id) {
        $dep    =   parent::findOrFail($id);
        if(mb_strlen($dep->parent_id,   "UTF-8")    >=   2) {
            $code   =   $dep->parent_id;
            while(mb_strlen($code,   "UTF-8")   >=  2) {
                $dep    =   parent::where('parent_id',  '=',    $code)->first();
                $rule   =   Users_Moderators_Rules::where('section',    '=',    'deps')->where('record',    '=',    $dep->id)->first();
                if($rule) {
                    return User::findOrFail($rule->user_id);
                }
                $code   =   mb_substr($code,  0,  mb_strlen($code,   "UTF-8")   -   2);
            }
        }

        return null;
    }
}