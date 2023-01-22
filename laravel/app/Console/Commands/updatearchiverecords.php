<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Dep;
use App\Deps_Peoples;
use Illuminate\Support\Facades\Log;
use Config;

class updatearchiverecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fired:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update information about employees fired since Intra had been run until soft deletes were turn on';

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
        $file=  fopen(Config::get('archiveexcel.kudina'), "r");
        while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
            if($filedata[0]    ==  "Done") {
                continue;
            }
            $lname  =   trim($filedata[2]);
            $fname  =   trim($filedata[3]);
            $mname  =   trim($filedata[4]);
            $dep    =   trim($filedata[5]);
            $work   =   trim($filedata[6]);
            $work_start =   trim($filedata[7]);
            $work_end   =   trim($filedata[8]);
            $updated    =   0;
            $skipped_not_deleted        =   0;
            $skipped_already_has        =   0;   
            $flag_create    =   0;
            //ищем среди всех сотрудников тех, что есть в списке как удаленные
            $obj    =   User::onlyTrashed()->where("fname",    "=",    $fname)->where("lname", "=",    $lname)->where("mname", "=",    $mname)->get();
            if($obj->count()    ==  1) {
                if(is_numeric($dep)) {
                    $current_user   =   $obj->first();
                    $work_place= Deps_Peoples::where("people_id", "=",    $current_user->id)->orderBy("created_at", "desc")->get();
                    if($work_place->count()   >   0) {
                        $current_work   =   $work_place->last();
                        if(($current_work->work_title    !== $work) &&  ($current_work->dep_id   !=  $dep)) {
                            $current_work->delete();
                            $flag_create    =   1;
                        }
                        else {
                            $skipped_already_has++;
                        }
                        
                    }
                    else {
                        $flag_create    =   1;
                    }
                    
                    if($flag_create) {
                        $archive_work   =   new Deps_Peoples();
                        $archive_work->people_id    =   $current_user->id;
                        $archive_work->dep_id       =   $dep;
                        $archive_work->work_title   =   $work;
                        $archive_work->save();
                        
                        if(!is_null($work_start)) {
                            $current_user->workstart   =   $work_start;
                        }
                        if(!is_null($work_end)) {
                            $current_user->workend     =   $work_end;
                            $current_user->deleted_at   =   $work_end   .   " 00:00:00";
                        }
                        
                        $current_user->save();

                        $updated ++;
                    }
                }
                else {
                    //echo $lname .   " " .   $fname  .   " " .   $mname  .   " || $dep \r\n";
                    echo "$dep \r\n";
                }
            }
            else {
                $skipped_not_deleted++;
            }
        }
        
        echo "Updated: $updated\r\n Skipped because already has: $skipped_already_has\r\n Skipped because they are alive: $skipped_not_deleted\r\n";
    }
}
