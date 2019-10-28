<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Dep;
use App\Deps_Peoples;
use Illuminate\Support\Facades\Log;
use Config;
use DB;

class fixchefs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chefs:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates chef value for departments chefs';

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
        $chefs  =   Deps_Peoples::whereNotNull('chef')->get();//получили всех боссов
        foreach ($chefs as $chef) {
            $dep    =   Dep::findOrFail($chef->dep_id);
            $value  =   mb_strlen($dep->parent_id,  "UTF-8");
            $chef->chef =   $value;
            $chef->update();
        }
    }
}
