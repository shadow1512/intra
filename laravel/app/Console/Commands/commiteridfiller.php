<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Dep;
use App\Profiles_Saved;
use App\Profiles_Saved_Data;
use Illuminate\Support\Facades\Log;
use Config;

class commiteridfiller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commiters:fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill commiter_id for profiles_saved and profiles_saved_data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $ps =   Profiles_Saved::onlyTrashed()->whereNull("commiter_id")->get();

        foreach($ps as $item) {
            $user   =   User::select("users.*", "deps_peoples.dep_id")
                ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                ->where('users.id', '=', $item->user_id)->first();

            if((!is_null($user))  &&  (!is_null($user->dep_id))) {
                $moderate   =   Dep::getModerate($user->dep_id);
            }
            else {
                Log::error($item->user_id   .   "- no user record");
            }

            if(is_null($moderate)) {
                //так тут делаем для унификации
                $moderate   =   User::where("id",   "=", Config::get("dict.main_moderate"))->get();
            }

            if(count($moderate)) {
                foreach ($moderate as $moderator) {
                    $item->commiter_id  =   $moderator->id;
                    $item->save();
                    break;
                }
            }
        }

        $psd    =   Profiles_Saved_Data::withTrashed()->select("profiles_saved_data.id", "profiles_saved.user_id")
                    ->leftJoin('profiles_saved', 'profiles_saved.id', '=', 'profiles_saved_data.ps_id')
                    ->where("profiles_saved_data.status", "!=", "1")->get();

        echo count($psd);
        foreach($psd as $item) {
            $user   =   User::select("users.*", "deps_peoples.dep_id")
                ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                ->where('users.id', '=', $item->user_id)->first();

            if((!is_null($user))  &&  (!is_null($user->dep_id))) {
                $moderate   =   Dep::getModerate($user->dep_id);
            }
            else {
                Log::error($item->user_id   .   "- no user record");
            }

            if(is_null($moderate)) {
                //так тут делаем для унификации
                $moderate   =   User::where("id",   "=", Config::get("dict.main_moderate"))->get();
            }

            if(count($moderate)) {
                foreach ($moderate as $moderator) {
                    Profiles_Saved_Data::update(["commiter_id"  =>  $moderator->id])->where("id",   "=",    $item->id);
                    break;
                }
            }
        }
    }
}
