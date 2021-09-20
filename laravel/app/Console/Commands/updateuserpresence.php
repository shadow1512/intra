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

            $user_names_without_empty    =   array();
            foreach($user_names as $element) {
                if(!empty(trim($element))){
                    $user_names_without_empty[] = $element;
                }
            }

            $processed  =   0;
            if(count($user_names_without_empty)   ==  3) {
                $user   =   User::where("fname",    "=",    trim($user_names_without_empty[1]))->where("lname", "=", trim($user_names_without_empty[0]))->where("mname", "=", trim($user_names_without_empty[2]))->first();
                if($user) {
                    $user->in_office    =   $record->action;
                    $user->save();
                    $processed  =   1;
                }
                else {
                    $num_users   =   User::where("fname",    "=",    trim($user_names_without_empty[1]))->where("lname", "=", trim($user_names_without_empty[0]))->count();
                    if($num_users   ==  1) {
                        $user   =   User::where("fname",    "=",    trim($user_names_without_empty[1]))->where("lname", "=", trim($user_names_without_empty[0]))->first();
                        if($user) {
                            $user->in_office    =   $record->action;
                            $user->save();
                            $processed  =   1;
                        }
                        else {
                            $processed  =   2;
                        }
                    }
                    else {
                        $processed  =   2;
                    }

                }
            }
            else if(count($user_names_without_empty)   ==  2) {
                $num_users   =   User::where("fname",    "=",    trim($user_names_without_empty[1]))->where("lname", "=", trim($user_names_without_empty[0]))->count();
                if($num_users   ==  1) {
                    $user   =   User::where("fname",    "=",    trim($user_names_without_empty[1]))->where("lname", "=", trim($user_names_without_empty[0]))->first();
                    if($user) {
                        $user->in_office    =   $record->action;
                        $user->save();
                        $processed  =   1;
                    }
                    else {
                        $processed  =   2;
                    }
                }
                else {
                    $processed  =   2;
                }
            }
            else {
                $processed  =   2;
            }
            $record->processed  =   $processed;
            $record->processed_at   =   date("Y-m-d H:i:s");

            $record->save();
        }
    }
}
