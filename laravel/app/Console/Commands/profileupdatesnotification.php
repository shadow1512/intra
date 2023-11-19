pro<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Dep;
use App\Profiles_Saved;
use App\Profiles_Saved_Data;
use Mail;
use Illuminate\Support\Facades\Log;
use Config;

class profileupdatesnotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profileupdate:inform';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to department moderator about profile updates initiated by the employee';

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
        $ps =   Profiles_Saved::where("notified",    "=",    0)->get();

        foreach($ps as $item) {
            $moderate   =   null;
            
            echo $item->user_id .   "\r\n";
            $user   =   User::select("users.*", "deps_peoples.dep_id")
                ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                ->where('users.id', '=', $item->user_id)->first();
            echo $user->dep_id  .   "\r\n";
            if(!is_null($user->dep_id)) {
                $moderate   =   Dep::getModerate($user->dep_id);
            }
            echo count($moderate)   .   "\r\n";
            if(is_null($moderate)) {
                //так тут делаем для унификации
                $moderate   =   User::where("id",   "=", Config::get("dict.main_moderate"))->get();
            }
            echo "----------------\r\n";
            if(count($moderate)) {
                foreach($moderate as $moderator) {
                    if($moderator->email) {
                        Mail::send('emails.profileupdate', ['user' => $user], function ($m) use ($moderator, $user) {
                            $m->from('newintra@kodeks.ru', 'Новый корпоративный портал');
                            $m->to($moderator->email, $moderator->fname)->subject('Сотрудник ' .   $user->fname    .   " " .   $user->lname    .   " обновил свой профиль");
                            Log::info('email sent to ' .   $moderator->email . ' for '  .   $user->fname    .   " " .   $user->lname);
                        });

                        $item->notified =   1;
                        $item->save();
                    }
                    else {
                        Log::error('NO EMAIL MODERATE ERROR: no email  for moderator ' .   $moderator->id);
                    }
                }
            }
            else {
                Log::error('NO MODERATE ERROR: no moderator  for record ' .   $item->id);
            }
        }

    }
}
