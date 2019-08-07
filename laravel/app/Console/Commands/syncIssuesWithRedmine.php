<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Config;
use App\Technical_Request;

class syncIssuesWithRedmine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncissues:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncing technical issues between portal and Redmine.dmz';

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
        require_once 'vendor/autoload.php';

        $client =   new Redmine\Client(Config::get('redmine.host'), Config::get('redmine.login'), Config::get('redmine.password'));

        $rec    =   $client->issue->all([
                            'project_id'    =>  Config::get('redmine.project_id_oto'),
                            'tracker_id'    =>  Config::get('redmine.tracker_id_oto'),
                            'status_id'     =>  'closed',
                            'cf_'   .   Config::get('redmine.cs_room')  =>  '205'
                    ]);

        var_dump($rec);
    }
}
