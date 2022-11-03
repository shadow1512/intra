<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Dep;
use App\Deps_Peoples;
use App\User;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use Config;

class makeCsvDirectoryInfographics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maindepcsv:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates CSV for Directory main page Infographics';

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
        $deps   =   Dep::whereNotNull('short_name')->orderBy('sort_in_diagram')->get();
        if(count($deps)) {
            $bar = $this->output->createProgressBar(count($deps));

            $writer = Writer::createFromPath(Config::get('image.directory_path')    .   '/public_data.csv', 'w+');
            $writer->insertOne(["label","title","score","color","url"]);
            foreach($deps as $dep) {

                //Собираем вместе всю численность сотрудников по текущему и дочерним департаментам

                $children_deps  =   Dep::whereRaw("parent_id LIKE '" .   $dep->parent_id .   "%'")->get();
                $ids_to_find    =   array($dep->id);
                foreach($children_deps as $chdep) {
                    $ids_to_find[]  =   $chdep->id;
                }

                //$num    =   Deps_Peoples::whereIn('dep_id', $ids_to_find)->count('people_id');
                $num    =   User::leftJoin('deps_peoples', function($join) {
                    $join->on('users.id', '=', 'deps_peoples.people_id')->whereRaw('deps_peoples.deleted_at IS NULL');
                })->whereIn('deps_peoples.dep_id',  $ids_to_find)->distinct('users.id')->count('users.id');


                $writer->insertOne([$dep->short_name,$dep->name,$num,$dep->color,route('people.dept',    ["id"   =>  $dep->id])]);

                $bar->advance();
            }
            $bar->finish();
        }
        else {
            echo "Nothing to export";
        }
    }
}
