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
        'name', 'parent_id', 'description', 'image', 'guid'
    ];

    protected $table = 'deps';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public static function getModerate($id) {
        DB::enableQueryLog(); // Enable query log
        $dep    =   parent::findOrFail($id);
        if(mb_strlen($dep->parent_id,   "UTF-8")    >=   2) {
            $code   =   $dep->parent_id;
            while(mb_strlen($code,   "UTF-8")   >=  2) {
                $rule   =   Users_Moderators_Rules::where('section',    '=',    'deps')->where('record',    'LIKE',    "$code")->first();
                if($rule) {
                    return User::findOrFail($rule->user_id);
                }
                $code   =   mb_substr($code,  0,  mb_strlen($code,   "UTF-8")   -   2);
            }
        }
        dd(DB::getQueryLog());
        return null;
    }

    public static function getCrumbs($id) {
        $crumbs = array();
        $currentDep     = parent::find($id);
        if($currentDep) {
            $parent = $currentDep->parent_id;
            $length = mb_strlen($parent, "UTF-8");
            while ($length > 2) {
                $parent = mb_substr($parent, 0, $length - 2);
                $dep = parent::where('parent_id', '=', $parent)->firstOrFail();
                $crumbs[] = $dep;
                $length = $length - 2;
            }
        }
        return array_reverse($crumbs);
    }
}