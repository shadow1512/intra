<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Technical_Request;

class syncRedminePrinters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syncprinters:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize printers list (custom field = 14) from redmine Printers project to the Intra Portal';

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
        $tr->syncPrintersFromRedmine();
    }
}
