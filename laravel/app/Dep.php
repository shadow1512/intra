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

        $dep    =   parent::findOrFail($id);
        $guids= array();
        if(mb_strlen($dep->parent_id,   "UTF-8")    >=   2) {
            $code   =   $dep->parent_id;
            while(mb_strlen($code,   "UTF-8")   >=  2) {
                //включаем поддержку нескольких "управляющих сотрудников"
                $guids[]    =   Dep::where('parent_id', 'LIKE', "$code")->pluck('guid')->all();
                $code   =   mb_substr($code,  0,  mb_strlen($code,   "UTF-8")   -   2);
            }
        }
        
        var_dump($guids);
        /*
        $moderators   =   Users_Moderators_Rules::select("users.*")
                                ->leftJoin("users", 'users_moderators_rules.user_id', '=', 'users.id')
                                ->where('section',    '=',    'deps')
                                ->where('record',    'LIKE',    "$code")
                                ->get();
                if(count($moderators)) {
                    return $moderators;
                }
        */      
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

    public static function getCrumbsArchieve($id) {
        $crumbs = array();
        $currentDep     = parent::withTrashed()->find($id);
        if($currentDep) {
            $parent = $currentDep->parent_id;
            $length = mb_strlen($parent, "UTF-8");
            while ($length > 2) {
                $parent = mb_substr($parent, 0, $length - 2);
                $dep = parent::withTrashed()->where('parent_id', '=', $parent)->firstOrFail();
                $crumbs[] = $dep;
                $length = $length - 2;
            }
        }
        return array_reverse($crumbs);
    }
}