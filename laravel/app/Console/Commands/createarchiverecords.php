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

    public function loader_2016() {
        $file = fopen(Config::get('archiveexcel.2016'), "r");
        $importData = array(); // Read through the file and store the contents as an array
        $i = 0;
        $currentRootDep =   $currentDep =   null;
        while (($filedata = fgetcsv($file, 1000, ";")) !== FALSE) {
            $key    =   trim($filedata[0]);
            //запись об уволенном сотруднике
            if(preg_match('/^[0-9]{2}.[0-9]{2}.[0-9]{4}/', $key, $matches)) {
                $date_string= trim($key);
                $date_array =   explode(".",    $date_string);
                $date       =    $date_array[2]  .   "-" .   $date_array[1]  .   "-" .   $date_array[0];

                $names  =   explode(" ", $filedata[1]);
                $lname  =   null;
                if(isset($names[0])) {
                    $lname  =   $names[0];
                }
                $fname  =   null;
                if(isset($names[1])) {
                    $fname  =   $names[1];
                }
                $mname  =   null;
                if(isset($names[2])) {
                    $mname  =   $names[2];
                }

                $date_string= trim($filedata[3]);
                $date_array =   explode(".",    $date_string);
                $date_string1   =   implode(",",    $date_array);
                $date_birth       =    $date_array[2]  .   "-" .   $date_array[1]  .   "-" .   $date_array[0];

                $importData[$currentRootDep]["deps"][$currentDep]["users"][]    =   array("datedel" =>  $date, "lname"  =>  $lname, "fname" =>  $fname, "mname" =>  $mname, "position"  =>  trim($filedata[2]), "date_birth"    =>  $date_birth);
            }
            else {
                //бестолковая запись
                if(mb_strtolower($key, "UTF-8") ==  "увольнение") {
                    continue;
                }
                //отдел
                else {
                    $marker =   mb_substr($key, 0,   3);
                    //верхний уровень
                    if($marker  ==  "АО "   ||  $marker  ==  "ООО"   ||  $marker  ==  "ЗАО"  ||  $marker  ==  "ОАО") {
                        $importData[$key]["deps"]   =   array();
                        $currentRootDep =   $key;
                    }
                    else {
                        $currentDep =   $key;
                    }
                }
            }
        }

        return $importData;
    }
    public function handle()
    {
        //
        $importData =   $this->loader_2016();
        var_dump($importData);

        /*$present    =   Dep::where('parent_id',  '=',    'AN')->first();

        if($present) {

        }
        else {
            $dep = new Dep();
            $dep->parent_id =   'AN';
            $dep->name      =   'Архив';
            $dep->save();
            $dep->delete();
        }

        $this->serveDepLevel("Архив", 'AN');*/
    }
}
