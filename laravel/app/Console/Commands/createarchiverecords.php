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

    public function serveDepLevel($name,    $parent_code) {
        //print $ou.  "\r\n\r\n";
        $hiercode   =   new \HierCode(CODE_LENGTH);
        $archive_deps   =   array();
        if(isset($archive_deps[$name]["deps"])  &&  count($archive_deps[$name]["deps"])) {
            $index  =   0;
            foreach($archive_deps[$name]["deps"] as $dep_inner) {
                $present    =   Dep::where('name',  '=',    $dep_inner)->where('parent_id', 'LIKE', "$parent_code%")->whereRaw("LENGTH(parent_id)=" .   (mb_strlen($parent_code, "UTF-8") +   CODE_LENGTH))->first();
                if($present) {
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

                    $present->parent_id =   $parent_id;
                    $present->save();
                }
                else {
                    $newdep=   new Dep();
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
                    $newdep->name      =   $dep_inner;
                    $newdep->save();
                    $newdep->delete();
                    $dep_user    =   $newdep;
                }

                $new_ou = addslashes($dep_inner);
                $this->serveDepUsers($dep_inner,   $dep_user);
                $this->serveDepLevel($dep_inner,    $parent_id);

                $index  ++;
            }
        }
    }

    /**
     * @return string
     */
    public function serveDepUsers()
    {

    }


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
                $date_fired     =   $date_array[2]  .   "-" .   $date_array[1]  .   "-" .   $date_array[0];

                $date_string    =   trim($filedata[5]);
                $date_array     =   explode(".",    $date_string);
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
    $unique_users   =   array("lname"   =>array(),  "mname" =>  array(),    "fname" =>  array());
        $importData =   $this->loader(array(Config::get('archiveexcel.2016'), Config::get('archiveexcel.2017')));
        foreach($importData as $org =>  $deps_data) {
            foreach ($deps_data as $dep =>  $user_data) {
                foreach($user_data as $key  =>  $data) {
                    if(in_array($data["lname"], $unique_users["lname"]) &&  in_array($data["fname"], $unique_users["fname"])    &&  in_array($data["mname"], $unique_users["mname"])) {
                        echo $data["lname"] . " " . $data["fname"] . " " . $data["mname"] . "\r\n";
                    }
                    $unique_users["lname"][]    =   $data["lname"];
                    $unique_users["fname"][]    =   $data["fname"];
                    $unique_users["mname"][]    =   $data["mname"];
                }
            }
        }
    }
}
