<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\User;

class createmoderator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moderator:create {email} {level}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates user with special access to /moderate/ section';

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
        $user   =   User::where('email',    '=',    $this->argument('email'))->first();
        if($user) {
            $level  =   2;
            if($this->argument('level')   ==  "admin") {
                $level  =   1;
            }
            if($this->argument('level')   ==  "moderate_content") {
                $level  =   4;
            }
            if($this->argument('level')   ==  "moderate_persons") {
                $level  =   3;
            }
            if($this->argument('level')   ==  "moderate_rooms") {
                $level  =   5;
            }
            if($this->argument('level')   ==  "moderate_dinner") {
                $level  =   6;
            }

            $user->role_id  =   $level;
            $user->save();

            print_r("Права предоставлены\r\n");
        }
        else {
            Log::info('SET ADMIN: No user with email ' .   $this->argument('email'));
            print_r("Нет такого пользователя\r\n");
        }
    }
}
