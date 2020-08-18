<?php

namespace App;

use App\Users_Moderators_Rules;
use App\Deps_Peoples;
use App\Dep;
use Auth;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends \TCG\Voyager\Models\User
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'sid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeByModerator($query, $moderator)
    {
        if(Auth::user()->role_id    <>  1) {
            $records    =   Users_Moderators_Rules::where('user_id',    '=',    $moderator)->where('section',   '=',    'deps')->get();
            if(count($records)) {
                $record_ids =   array();
                foreach($records as $record) {
                    $record_ids[]   =   $record->record;
                }

                $deps   =   Dep::where(function($query) use($record_ids) {
                    foreach($recrd_ids as $key   =>  $code) {
                        if($key ==  0) {
                            $query->where('parent_id',  'LIKE', $code.  '%');
                        }
                        else {
                            $query->orWhere('parent_id',  'LIKE', $code.  '%');
                        }
                    }
                })->get();

                if(count($deps)) {
                    $dep_ids    =   array();
                    foreach($deps as $dep) {
                        $dep_ids[]  =   $dep->id;
                    }

                    $users  =   Deps_Peoples::whereIn('dep_id', $dep_ids)->get();
                    if(count($users)) {
                        $user_ids   =   array();
                        foreach($users as $user) {
                            $user_ids[] =   $user->people_id;
                        }
                        return $query->whereIn('id', $user_ids);
                    }
                }
            }

            return $query->whereNull('id');
        }
    }
}