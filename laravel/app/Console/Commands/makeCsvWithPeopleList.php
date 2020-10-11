<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Dep;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use Config;

class makeCsvWithPeopleList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'directorycsv:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates CSV with people list in departments';

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
    public function handle()
    {
        //


        $rootdeps   =   Dep::whereRaw("LENGTH(parent_id)="  .   CODE_LENGTH)->get();
        $bar = $this->output->createProgressBar(count($rootdeps));

        foreach($rootdeps   as  $rootdep) {
            $writer = Writer::createFromPath(Config::get('image.csv_directory_path')    .   $rootdep->id    .   ".csv", 'w+');

            $dep_ids   =   Dep::where('parent_id', 'LIKE', $rootdep->code)->pluck('id')->toArray();

            $users  =   User::select("users.*")
                ->leftJoin("deps_peoples",  "users.id", "=",    "deps_peoples.people_id")
                ->whereIn("deps_peoples.dep_id", $dep_ids)->orderBy("users.lname")->orderBy("users.fname")->orderBy("users.mname")->get();

            if(count($users)) {
                foreach($users  as $user) {
                    $writer->insertOne([$user->lname   .   " " .   $user->fname   .   " " .   $user->mname]);
                }
            }

            $bar->advance();
        }

        $bar->finish();
    }
}
