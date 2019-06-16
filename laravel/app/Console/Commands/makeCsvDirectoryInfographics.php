<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Dep;
use App\Deps_Peoples;
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
        $deps   =   Dep::whereNotNull('short_name')->orderByDesc('short_name')->get();
        if(count($deps)) {
            $bar = $this->output->createProgressBar(count($deps));


            $path   =   Storage::disk('public')->put(Config::get('image.directory_path')    .   'public_data.csv', '', 'public');
            $writer = Writer::createFromPath($path, 'w+');
            $writer->insertOne(["label","score","color","url"]);
            foreach($deps as $dep) {

                //Собираем вместе всю численность сотрудников по текущему и дочерним департаментам

                $children_deps  =   Dep::whereRaw('parent_id LIKE ' .   $dep->parent_id .   '%')->get();
                $ids_to_find    =   array($dep->id);
                foreach($children_deps as $chdep) {
                    $ids_to_find[]  =   $chdep->id;
                }

                $num    =   Deps_Peoples::whereIn('dep_id', $ids_to_find)->count('people_id');

                $writer->insertOne([$dep->name,$num,$dep->color,route('people.dept',    ["id"   =>  $dep->id])]);

                $bar->advance();
            }
            $bar->finish();
        }
        else {
            echo "Nothing to export";
        }
    }
}
