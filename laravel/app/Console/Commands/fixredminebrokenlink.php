<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Technical_Request;

class fixredminebrokenlink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fixredmine:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fixing the problem when issue was deleted at Redmine but with null status at intra - adding new status';

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
        //
        $tr->setCorrectStatusByRedmine();
    }
}
