<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Adldap\Laravel\Facades\Adldap;
use App\User;
use App\Dep;
use App\Deps_Peoples;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Config;
use Illuminate\Support\Facades\Validator;
use DB;

class updatedirectoryfromad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adstaff:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import staff from AD';

    protected $fakeous  =   array(  'testandserviceusers',
                                    'groups',
                                    'workpskov',
                                    'spd',
                                    'groupsdtsp',
                                    'groupsdrnp',
                                    'groupsdpnus',
                                    'groupsdmc',
                                    'groupsdccp',
                                    'testfordpt',
                                    'testforprogrammers');

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        require_once public_path() . '/hiercode.php';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function serveDepLevel($ou,    $parent_code) {
        //print $ou.  "\r\n\r\n";
        $hiercode   =   new \HierCode(CODE_LENGTH);

        $deps =   Adldap::getProvider('default')->search()->ous()->in($ou .   ",dc=work,dc=kodeks,dc=ru")->listing()->get();

        $index  =   0;
        foreach($deps as $dep_inner) {

            $dep_user   =   null;
            if(in_array(mb_strtolower($dep_inner->getName(),  "UTF-8"),   $this->fakeous)) {
                continue;
            }
            $present    =   Dep::where('guid',  '=',    $dep_inner->getConvertedGuid())->first();
            if($present) {
                $present->name      =   $dep_inner->getName();
                $present->save();
                $parent_id  =   $present->parent_id;
                $dep_user   =   $present;
            }
            else {
                $newdep=   new Dep();
                $parent_id  =   $parent_code;
                if(!is_null($parent_id)) {
                    if($index   ==  0) {
                        for ($i = 0; $i < CODE_LENGTH; $i++) {
                            $parent_id .= $hiercode->digit_to_char[0];
                        }
                    }
                    else {
                        $parent_id  =   $hiercode->getNextCode();
                    }
                    $hiercode->setValue($parent_id);
                }
                else {
                    $parent_id  =   '';
                    if($index   ==  0) {
                        for ($i = 0; $i < CODE_LENGTH; $i++) {
                            $parent_id .= $hiercode->digit_to_char[0];
                        }
                    }
                    else {
                        $parent_id  =   $hiercode->getNextCode();
                    }
                    $hiercode->setValue($parent_id);
                }
                $newdep->parent_id =   $parent_id;
                $newdep->name      =   $dep_inner->getName();
                $newdep->guid      =   $dep_inner->getConvertedGuid();
                $newdep->save();

                $dep_user    =   $newdep;
            }

            $new_ou =    "OU="    .   $dep_inner->getName()   .   "," .   $ou;
            $this->serveDepUsers($new_ou,   $dep_user);
            $this->serveDepLevel($new_ou,    $parent_id);

            $index  ++;
        }
    }

    public function serveDepUsers($ou,  $dep) {
        $users = Adldap::getProvider('default')->search()->users()->in($ou .   ",dc=work,dc=kodeks,dc=ru")->sortBy('samaccountname', 'asc')->listing()->get();
        if(count($users)) {
            foreach($users as $user) {

                $currentRecord  =   null;
                $present    =   User::where('sid',  '=',    $user->getConvertedSid())->first();
                if($present) {
                    if($user->isActive()    &&  $user->isEnabled()) {
                        if($user->changedDate() >   $present->created_at) {
                            $currentRecord  =   $present;
                        }
                    }
                    else {
                        $present->delete();
                    }
                }
                else {
                    $currentRecord  =   new User();
                }

                if(!is_null($currentRecord)) {
                    $currentRecord->sid       =   $user->getConvertedSid();
                    $currentRecord->fname     =   $user->getFirstName();
                    $currentRecord->mname     =   $user->getMiddleName();
                    $currentRecord->lname     =   $user->getLastName();
                    $currentRecord->avatar    =   $user->getUrl();
                    $currentRecord->email     =   $user->getEmail();
                    $currentRecord->phone     =   $user->getTelephoneNumber();
                    $currentRecord->room      =   $user->getPhysicalDeliveryOfficeName();

                    $currentRecord->save();

                    $work_title     =   $user->getTitle();
                    $chef   =   0;
                    if($user->getBusinessCategory() ==  "boss") {
                        $chef   =   mb_strlen($dep->parent_id,  "UTF-8");
                    }
                    Deps_Peoples::where("people_id",    "=",    $currentRecord->id)->delete();
                    $dp =   new Deps_Peoples();
                    $dp->dep_id     =   $dep->id;
                    $dp->people_id  =   $currentRecord->id;
                    $dp->work_title =   $work_title;
                    $dp->chef       =   $chef;
                    $dp->save();
                }
            }
        }
    }
    public function handle()
    {
        //
        /*$us =   Adldap::getProvider('default')->search()->users()->find("Сергей");
        var_dump($us->changedDate());
        die();*/

        $root =   Adldap::getProvider('default')->search()->ous()->find("Консорциум КОДЕКС");

        $present    =   Dep::where('guid',  '=',    $root->getConvertedGuid())->first();

        if($present) {
            $present->name      =   $root->getName();
            $present->save();
        }
        else {
            $dep = new Dep();
            $dep->parent_id =   null;
            $dep->name      =   $root->getName();
            $dep->guid      =   $root->getConvertedGuid();
            $dep->save();
        }

        $this->serveDepLevel("OU=Консорциум КОДЕКС", null);

        User::whereNull('avatar')->update(['avatar'    =>  '/images/faces/default.svg']);
    }
}
