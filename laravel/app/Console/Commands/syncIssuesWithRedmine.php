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
    public function handle(Technical_Request $tr)
    {
        $tr->syncToRedmine();
    }
}
