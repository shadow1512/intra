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

class createarchiverecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fired:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'One-time uploader from Excel of fired employees with structure';

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

    public function loader($files_to_import) {

        $importData =   array();
        foreach($files_to_import as $file) {
            $file=  fopen($file, "r");
            while (($filedata = fgetcsv($file, 1000, ";")) !== FALSE) {
                if($filedata[0]    ==  "ФИО") {
                    continue;
                }
                $org    =   trim($filedata[1]);
                if($org ==  'АО "Кодекс"(ЗАО до 16.06.2015 г)') {
                    $org    =   'АО "Кодекс"';
                }
                $dep    =   trim($filedata[2]);
                if($dep ==  'Сектор " Пресс- служба"'   ||  $dep    ==  '"Пресс - служба"') {
                    $dep    =   'Сектор "Пресс-служба"';
                }
                if($dep ==  'Служба поддержки пользователей СЭД') {
                    $dep    =   'Служба поддержки пользователей и тестирования СЭД';
                }
                if($dep ==  'Отдел " Учебный центр "') {
                    $dep    =   'Отдел "Учебный центр"';
                }
                if($dep ==  'Отдел информационно - стратегического развития') {
                    $dep    =   'Отдел информационно-стратегического развития';
                }
                $work   =   trim($filedata[3]);

                $date_string    =   trim($filedata[4]);
                $date_array     =   explode(".",    $date_string);
                if(mb_strlen($date_array[2], "UTF-8")    ==  2) {
                    $date_array[2]  =   "20"    .   $date_array[2];
                }
                $date_fired     =   $date_array[2]  .   "-" .   $date_array[1]  .   "-" .   $date_array[0];

                $date_string    =   trim($filedata[5]);
                $date_array     =   explode(".",    $date_string);
                if(mb_strlen($date_array[2], "UTF-8")    ==  2) {
                    $date_array[2]  =   "19"    .   $date_array[2];
                }
                $date_birth     =   $date_array[2]  .   "-" .   $date_array[1]  .   "-" .   $date_array[0];



                $names  =   explode(" ", $filedata[0]);
                $lname  =   null;
                if(isset($names[0])) {
                    $lname  =   trim($names[0]);
                }
                $fname  =   null;
                if(isset($names[1])) {
                    $fname  =   trim($names[1]);
                }
                if($fname   ==  "Алена") {
                    $fname  =   "Алёна";
                }
                $mname  =   null;
                if(isset($names[2])) {
                    $mname  =   trim($names[2]);
                }

                $importData[$org][$dep][]    =   array("date_fired" =>  $date_fired, "lname"  =>  $lname, "fname" =>  $fname, "mname" =>  $mname, "position"  =>  $work, "date_birth"    =>  $date_birth);
            }
        }


        return $importData;
    }

    public function handle()
    {
        //
        $hiercode           =   new \HierCode(CODE_LENGTH);
        $parent_code    =   'AN';
        $index          =   0;

        $unique_users   =   array();
        $importData =   $this->loader(array(Config::get('archiveexcel.2016'), Config::get('archiveexcel.2017')));
        foreach($importData as $org =>  $deps_data) {
            $org_id =   null;
            if($org) {
                $present    =   Dep::where('name',  '=',    $org)->where('parent_id', 'LIKE', "$parent_code%")->whereRaw("LENGTH(parent_id)=" .   (mb_strlen($parent_code, "UTF-8") +   CODE_LENGTH))->first();
                if($present) {
                    $org_id     =   $present->id;
                    $parent_id  =   $present->parent_id;
                }
                else {
                    $newdep=   new Dep();
                    $newdep->guid   =   "";
                    $parent_id  =   $parent_code;

                    if($index   ==  0) {
                        for ($i = 0; $i < CODE_LENGTH; $i++) {
                            $parent_id .= $hiercode->digit_to_char[0];
                        }
                    }
                    else {
                        $parent_id  =   $hiercode->getNextCode();
                    }
                    $hiercode->setValue($parent_id);
                    $newdep->parent_id =   $parent_id;
                    $newdep->name      =   $org;
                    $newdep->save();
                    $newdep->delete();

                    $org_id =   $newdep->id;
                }
                $index  ++;
            }

            $subindex   =  0;
            $subhiercode       =   new \HierCode(CODE_LENGTH);
            foreach ($deps_data as $dep =>  $user_data) {
                $dep_id =   null;
                if($dep) {
                    $present_dep    =   Dep::where('name',  '=',    $dep)->where('parent_id', 'LIKE', "$parent_id%")->whereRaw("LENGTH(parent_id)=" .   (mb_strlen($parent_id, "UTF-8") +   CODE_LENGTH))->first();
                    if($present_dep) {
                        $dep_id         =   $present_dep->id;
                        $dep_parent_id  =   $present_dep->parent_id;
                    }
                    else {
                        $newdep         =   new Dep();
                        $newdep->guid   =   "";
                        $dep_parent_id  =   $parent_id;
                        if($subindex   ==  0) {
                            for ($i = 0; $i < CODE_LENGTH; $i++) {
                                $dep_parent_id .= $subhiercode->digit_to_char[0];
                            }
                        }
                        else {
                            $dep_parent_id  =   $subhiercode->getNextCode();
                        }
                        $subhiercode->setValue($dep_parent_id);
                        $newdep->parent_id =   $dep_parent_id;
                        $newdep->name      =   $dep;
                        $newdep->save();
                        $newdep->delete();

                        $dep_id =   $newdep->id;
                    }
                    $subindex  ++;
                }

                foreach($user_data as $key  =>  $data) {
                    $data["org_id"] =   $org_id;
                    $data["dep_id"] =   $dep_id;
                    $unique_users[$data["lname"] . " " . $data["fname"] . " " . $data["mname"] . " " . $data["date_birth"]]    =   $data;
                }
            }
        }
        /*ksort($unique_users, SORT_STRING);
        foreach($unique_users as $key   =>  $data) {
            echo "$key - "  .   $data['date_fired'] .   "\r\n";
        }*/

        foreach($unique_users as $key   =>  $data) {
            if(User::withTrashed()->where("lname",  '=',  $data["lname"])->where("fname",  '=',  $data["fname"])->where("birthday", '=', $data["date_birth"])->exists()) {
                continue;
            }

            $user               =   new User();
            $user->sid          =   "";
            $user->lname        =   $data["lname"];
            $user->fname        =   $data["fname"];
            $user->mname        =   $data["mname"];
            $user->birthday     =   $data["date_birth"];
            $user->deleted_at   =   $data["date_fired"] .   " 00:00:00";
            $user->workend      =   $data["date_fired"];

            $user->save();

            $du =   new Deps_Peoples();
            $du->people_id  =   $user->id;
            if(!is_null($data["dep_id"])) {
                $du->dep_id =   $data["dep_id"];
            }
            else {
                $du->dep_id =   $data["org_id"];
            }
            $du->work_title =   $data["position"];

            $du->save();
        }
    }
}
