<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Adldap\Laravel\Facades\Adldap;
use App\User;
use App\Dep;
use App\Deps_Peoples;
use Illuminate\Support\Facades\Log;
use Config;
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

    //Массивы для поддержания удаления подразделений или пользователей, которые могли быть удалены или перенесены
    // в служебные группы, что означает де-факто удаление, нужно хранить текущее состояние Intra-базы и сравнивать с тем,
    //что получается во время импорта (а также поддержка переноса пользователей из подразделения в подразделение

    protected $i_links;
    protected $i_uids;
    protected $i_dids;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        require_once public_path() . '/hiercode.php';

        $i_uids       =   User::pluck("id");
        foreach($i_uids as $uid) {
            $this->i_uids[] =   $uid;
        }

        $i_dids       =   Dep::pluck("id");
        foreach($i_dids as $did) {
            $this->i_dids[] =   $did;
        }
        $this->i_links      =   array();

        $deps_peoples   =   Deps_Peoples::get();
        foreach($deps_peoples as $dep_people) {
            $this->i_links[$dep_people->people_id][]  =   $dep_people->dep_id;
        }
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
                //print "continue\r\n";
            }
            $present    =   Dep::where('guid',  '=',    $dep_inner->getConvertedGuid())->first();
            if($present) {
                $present->name      =   $dep_inner->getName();
                //Могли переместить по структуре
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

                $present->parent_id =   $parent_id;
                $present->save();

                //Раз департамент есть, значит, его можно удалить из перечня проверки
                $key    =   array_search($present->id,  $this->i_dids);
                if($key !== false) {
                    unset($this->i_dids[$key]);
                }
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

            $new_ou =    "OU="    .   addslashes($dep_inner->getName())   .   "," .   $ou;
            $this->serveDepUsers($new_ou,   $dep_user);
            $this->serveDepLevel($new_ou,    $parent_id);

            $index  ++;
        }
    }

    public function serveDepUsers($ou,  $dep) {
        $users = Adldap::getProvider('default')->search()->users()->in($ou .   ",dc=work,dc=kodeks,dc=ru")->sortBy('samaccountname', 'asc')->listing()->get();
        if(count($users)) {
            foreach($users as $user) {

                //print $user->getLastName()  .   "\r\n";
                $currentRecord  =   null;
                $present    =   User::withTrashed()->where('sid',  '=',    $user->getConvertedSid())->first();
                if($present) {
                    //print "present\r\n";
                    if($user->isActive()    &&  $user->isEnabled()) {
                        if(!is_null($present->deleted_at)) {
                            $present->restore();
                        }

                        //Раз сотрудник есть, значит, его можно удалить из перечня проверки
                        $key    =   array_search($present->id,  $this->i_uids);
                        if($key !== false) {
                            unset($this->i_uids[$key]);
                        }

                        //Можно его удалить и из проверки связей
                        if(isset($this->i_links[$present->id])) {
                            $key    =   array_search($dep->id,  $this->i_links[$present->id]);
                            if($key !== false) {
                                unset($this->i_links[$present->id][$key]);
                            }
                            //Для этого сотрудника нет текущего отдела, надо создать, вероятно, он перенесен
                            else {
                                $work_title     =   $user->getTitle();
                                $chef   =   null;
                                if($user->getBusinessCategory() ==  "boss") {
                                    $chef   =   mb_strlen($dep->parent_id,  "UTF-8");
                                }
                                //Заплатка для Тимофеевой, Крупцова
                                if($user->getBusinessCategory() ==  "superboss") {
                                    $chef   =   1;
                                }
                                if($user->getBusinessCategory() ==  "president") {
                                    $chef   =   0;
                                }

                                $dp =   new Deps_Peoples();
                                $dp->dep_id     =   $dep->id;
                                $dp->people_id  =   $present->id;
                                $dp->work_title =   $work_title;
                                $dp->chef       =   $chef;
                                $dp->save();
                            }
                        }
                        //Для этого сотрудника не было отдела, надо создать
                        else {
                            $work_title     =   $user->getTitle();
                            $chef   =   null;
                            if($user->getBusinessCategory() ==  "boss") {
                                $chef   =   mb_strlen($dep->parent_id,  "UTF-8");
                            }
                            //Заплатка для Тимофеевой, Крупцова
                            if($user->getBusinessCategory() ==  "superboss") {
                                $chef   =   1;
                            }
                            if($user->getBusinessCategory() ==  "president") {
                                $chef   =   0;
                            }
                            $dp =   new Deps_Peoples();
                            $dp->dep_id     =   $dep->id;
                            $dp->people_id  =   $present->id;
                            $dp->work_title =   $work_title;
                            $dp->chef       =   $chef;
                            $dp->save();
                        }
                    }
                    else {
                        //print "trashed\r\n";
                        $present->delete();
                        Deps_Peoples::where("people_id",    "=",    $present->id)->delete();
                    }
                }
                else {
                    $currentRecord  =   new User();
                }

                if(!is_null($currentRecord)) {
                    //var_dump($user);exit();
                    //print "new atributes\r\n";
                    $currentRecord->sid       =   $user->getConvertedSid();
                    if($user->getFirstName()) {
                        $currentRecord->fname = $user->getFirstName();
                    }
                    if($user->getMiddleName()) {
                        $currentRecord->mname = $user->getMiddleName();
                    }
                    if($user->getLastName()) {
                        $currentRecord->lname = $user->getLastName();
                    }
                    if($user->getUrl()) {
                        $currentRecord->avatar = $user->getUrl();
                    }
                    if($user->getEmail()) {
                        $currentRecord->email = $user->getEmail();
                    }
                    if($user->getTelephoneNumber()) {
                        $currentRecord->phone = $user->getTelephoneNumber();
                    }
                    if($user->getPhysicalDeliveryOfficeName()) {
                        $currentRecord->room = $user->getPhysicalDeliveryOfficeName();
                    }
                    if($user->getComment()) {
                        $birthday   =   explode("-",    $user->getComment());
                        if((count($birthday) ==  3)   &&    (mb_strlen($birthday[0])  ==  4)    &&  (mb_strlen($birthday[1])  ==  2)    &&  (mb_strlen($birthday[2])  ==  2)) {
                            $currentRecord->birthday = $user->getComment();
                        }
                    }

                    $currentRecord->updated_at  =   $user->changedDate();
                    $currentRecord->save();

                    $work_title     =   $user->getTitle();
                    $chef   =   null;
                    if($user->getBusinessCategory() ==  "boss") {
                        $chef   =   mb_strlen($dep->parent_id,  "UTF-8");
                    }
                    //Заплатка для Тимофеевой, Крупцова
                    if($user->getBusinessCategory() ==  "superboss") {
                        $chef   =   1;
                    }
                    if($user->getBusinessCategory() ==  "president") {
                        $chef   =   0;
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
        $root =   Adldap::getProvider('default')->search()->ous()->find("Консорциум КОДЕКС");

        $present    =   Dep::where('guid',  '=',    $root->getConvertedGuid())->first();

        if($present) {
            $present->name      =   $root->getName();
            $present->save();

            //Раз департамент есть, значит, его можно удалить из перечня проверки
            $key    =   array_search($present->id,  $this->i_dids);
            if($key !== false) {
                unset($this->i_dids[$key]);
            }
        }
        else {
            $dep = new Dep();
            $dep->parent_id =   null;
            $dep->name      =   $root->getName();
            $dep->guid      =   $root->getConvertedGuid();
            $dep->save();
        }

        $this->serveDepLevel("OU=Консорциум КОДЕКС", null);

        //Те люди, которые остались в списках по предыдущему состоянию Intra, но их нет в текущем состоянии после синхронизации.
        //Вердикт - убить
        foreach($this->i_uids as $uid) {
            User::where("id",    "=",    $uid)->delete();
            Deps_Peoples::where("people_id",    '=',    $uid)->delete();
        }

        //Те департаменты, которые остались в списках по предыдущему состоянию Intra, но их нет в текущем состоянии после синхронизации.
        //Вердикт - убить
        foreach($this->i_dids as $did) {
            Dep::where("id",    "=",    $did)->delete();
            Deps_Peoples::where("dep_id",    '=',    $did)->delete();
        }

        //Самое сложное - повисшие связи. Люди, которые, видимо, были перемещены между департаментами. Старую связь надо удалить
        foreach($this->i_links as $uid  =>  $deps) {
            foreach($deps as $dep) {
                Deps_Peoples::where("dep_id",    '=',    $dep)->where("people_id",  "=",    $uid)->delete();
            }
        }

        User::whereNull('avatar')->update(['avatar'    =>  '/images/faces/default.svg']);
    }
}
