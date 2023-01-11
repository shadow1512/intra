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
            
            //ищем среди всех сотрудников тех, что есть в списке как удаленные
            $obj    =   User::withTrashed()->where("fname",    "=",    $fname)->where("lname", "=",    $lname)->where("mname", "=",    $mname)->get();
            echo $lname . " " . $fname . " " . $mname . " || " . $obj->count() . "\r\n";
        }
    }
}
