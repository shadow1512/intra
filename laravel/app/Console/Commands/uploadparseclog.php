<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use DOMDocument;
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
        /*exec('mntParsec.sh run');
        $doc    = new DOMDocument('1.0');
        $dl =   $doc->load(Config::get('parsec.path') . '/'   .   Config::get('parsec.filename'));
        if($dl) {
            $doc->encoding  =   'utf-8';
            $elements = $doc->getElementsByTagName("DocumentProperties");
            $props = $elements->item(0);
            $options = $props->childNodes;
            for ($j = 0; $j < $options->length; $j++) {
                $option = $options->item($j);
                if ($option->nodeName == "Created") {
                    $option->nodeValue = "";
                }
            }
            $doc->save(Config::get('parsec.parsec_converted_path') . '/'   .   Config::get('parsec.filename'));
        }*/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xml");
        $spreadsheet = $reader->load(Config::get('parsec.parsec_converted_path') . '/'   .   Config::get('parsec.filename'));
        if(!$spreadsheet->getSheetCount()) {
            echo 'problem with file';
        }
        $worksheets =   $spreadsheet->getAllSheets();
        $sheet= $worksheets[0];

        $sourceArray    =   $sheet->rangeToArray($sheet->calculateWorksheetDataDimension());
        foreach($sourceArray as $row) {
            if(preg_match('/[0-9]{2}:[0-9]{2}:[0-9]{2}/', $row[0], $matches)) {
                var_dump($matches[0]);
            }
        }
        //exec('mntParsec.sh stop');
    }
}
