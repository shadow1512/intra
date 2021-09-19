<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Config;

class uploadparseclog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parsec:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parsec log upload to database';

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
        exec('mntParsec.sh run');
        $doc    = new DOMDocument();
        $dl =   $doc->load(Config::get('parsec.path') . '/'   .   Config::get('parsec.filename'));
        if($dl) {
            $elements = $doc->getElementsByTagName("DocumentProperties");
            $props = $elements->item(0);
            $options = $props->childNodes;
            for ($j = 0; $j < $options->length; $j++) {
                $option = $options->item($j);
                if ($option->nodeName == "Created") {
                    $option->nodeValue = "";
                }
            }
            Storage::disk('public')->put('/parsec/'    .   Config::get('parsec.filename'), $doc->saveXML(), 'public');
        }
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xml");
        $spreadsheet = $reader->load(Config::get('parsec_converted_path') . '/'   .   Config::get('parsec.filename'));
        if(!$spreadsheet->getSheetCount()) {
            echo 'problem with file';
        }
        var_dump($spreadsheet->getSheetCount());
        exec('mntParsec.sh stop');
    }
}
