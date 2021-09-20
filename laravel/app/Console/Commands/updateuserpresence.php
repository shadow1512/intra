<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Parsec_log;
use App\User;

class updateuserpresence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presence:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating presence status of the user';

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
        //берем только те записи, которые не обработаны
        $records_to_process    =   Parsec_log::where("processed", "=",  0)->get();
        foreach($records_to_process as $record) {
            $user_names =   explode(" ",    $record->user);

            foreach($user_names as $index   =>  $part_name) {
                if(!trim($part_name)) {
                    unset($user_names[$index]);
                }
            }
            $processed  =   0;
            if(count($user_names)   ==  3) {
                $user   =   User::where("fname",    "=",    trim($user_names[1]))->where("lname", "=", trim($user_names[0]))->where("mname", "=", trim($user_names[2]))->first();
                if($user) {
                    $user->in_office    =   $record->action;
                    $processed  =   1;
                }
                else {
                    $processed  =   2;
                }
            }
            if(count($user_names)   ==  2) {
                $num_users   =   User::where("fname",    "=",    trim($user_names[1]))->where("lname", "=", trim($user_names[0]))->count();
                if($num_users   ==  1) {
                    $user   =   User::where("fname",    "=",    trim($user_names[1]))->where("lname", "=", trim($user_names[0]))->first();
                    if($user) {
                        $user->in_office    =   $record->action;
                        $processed  =   1;
                    }
                    else {
                        $processed  =   2;
                    }
                }
            }

            $record->processed  =   $processed;
            $record->processed_at   =   date("Y-m-d H:i:s");

            $record->save();
        }
    }
}
