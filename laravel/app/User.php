<?php

namespace App;

use App\Users_Moderators_Rules;
use App\Deps_Peoples;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends \TCG\Voyager\Models\User
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
        $records    =   Users_Moderators_Rules::where('user_id',    '=',    $moderator)->where('section',   '=',    'deps')->get();
        if(count($records)) {
            $record_ids =   array();
            foreach($records as $record) {
                $record_ids[]   =   $record->record;
            }

            $users  =   Deps_Peoples::whereIn('dep_id', $record_ids)->get();
            if(count($users)) {
                $user_ids   =   array();
                foreach($users as $user) {
                    $user_ids[] =   $user->people_id;
                }


            }

            return $query->whereIn('id', $user_ids);
        }
    }
}