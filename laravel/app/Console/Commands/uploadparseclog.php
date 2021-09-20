<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Parsec_log;
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
        exec('mntParsec.sh run');
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
        }
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xml");
        $spreadsheet = $reader->load(Config::get('parsec.parsec_converted_path') . '/'   .   Config::get('parsec.filename'));
        if(!$spreadsheet->getSheetCount()) {
            echo 'problem with file';
        }
        $worksheets =   $spreadsheet->getAllSheets();
        $sheet= $worksheets[0];

        $sourceArray    =   $sheet->rangeToArray($sheet->calculateWorksheetDataDimension());

        $last_record    =   Parsec_log::orderBy('datetime_record', 'desc')->first();

        foreach($sourceArray as $row) {
            if(preg_match('/[0-9]{2}:[0-9]{2}:[0-9]{2}/', $row[0], $matches)) {
                $time   =   $matches[0];
                $action =   null;
                if (str_contains($row[1], 'выход')) {
                    $action =   false;
                }
                if (str_contains($row[1], 'вход')) {
                    $action =   true;
                }
                $area   =   $row[3];
                $user   =   $row[5];
                $date_data  =   explode(";", $row[6]);
                $date_parts =   explode(":", $date_data[0]);
                $date_string= trim($date_parts[1]);
                $date_array =   explode(".",    $date_string);
                $date       =    $date_array[2]  .   "-" .   $date_array[1]  .   "-" .   $date_array[0];

                //не надо добавлять файл весь, а только те записи, которые старше последней

                if($last_record &&  ($last_record->datetime_record    <   ($date.    " " .   $time))) {
                    $pl =   new Parsec_log();

                    $pl->datetime_record    = $date.    " " .   $time;
                    $pl->user               =   $user;
                    $pl->action             =   $action;
                    $pl->area               =   $area;

                    $pl->save();
                }

            }
        }
        exec('mntParsec.sh stop');
    }
}
