<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Log;
use Config;
use Illuminate\Support\Facades\Validator;
use DB;

class updatedirectoryfromoldts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oldtsstaff:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import staff from old phone dictionary (XML)';

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

        $record_counter =   0;
        $processed_counter  =   0;
        $counter_added      =   0;

        $xmlstring  =   Storage::disk('public')->get('/xml/sprav.xml');
        $olddict    =   simplexml_load_string($xmlstring);
        if(($olddict->count() > 0) &&  ($olddict->children()->count()   >   0)){
            $items  =   $olddict->children()->children();
            $bar = $this->output->createProgressBar(count($items));
            foreach($items as $item) {

                $record_counter ++;
                $record     =    null;

                if (isset($item->fullname->value) && !empty($item->fullname->value)) {
                    $item->fullname->value  =   preg_replace("/\s/ius",    " ", $item->fullname->value);
                    $lname = $fname = $mname = "";
                    $names = explode( " ", $item->fullname->value);
                    for($i= 0;  $i<count($names);   $i++) {
                        if(empty($names[$i])) {
                            unset($names[$i]);
                        }
                    }
                    $names  =   array_values($names);
                    if (isset($names[0])) {
                        $lname = preg_replace("/[^А-яЁё]/ius",    "", $names[0]);
                    }
                    if (isset($names[1])) {
                        $fname = preg_replace("/[^А-яЁё]/ius",    "", $names[1]);
                        //маленький хак на левое имя
                        if($fname   ==  "Янина") {
                            $fname  =   "Яна";
                        }
                    }
                    if (isset($names[2])) {
                        $mname = preg_replace("/[^А-яЁё]/ius",    "", $names[2]);
                    }
                    if ($lname && $fname) {
                        if($mname) {
                            $record = User::where('fname', 'LIKE', $fname)
                                ->where('lname', 'LIKE', $lname)
                                ->where('mname', 'LIKE', $mname)->first();
                        }
                        if (!$record) {
                            $record = User::where('fname', 'LIKE', $fname)
                                    ->where('lname', 'LIKE', $lname)->first();
                        }
                        if (!$record) {
                            Log::info('No database record for ' .   $lname  .   " " .   $fname);
                            continue;
                        }

                        if(isset($item->bdate->value) && !empty($item->bdate->value)    &&  empty($record->birthday)) {
                            $record->birthday   =   date("Y-m-d", strtotime($item->bdate->value));
                        }
                        if($mname    &&  empty($record->mname)) {
                            $record->mname   =   $mname;
                        }
                        if(isset($item->startdate->value) && !empty($item->startdate->value)    &&  empty($record->workstart)) {
                            $record->workstart   =   date("Y-m-d", strtotime($item->startdate->value));
                        }
                        if(isset($item->room->value) && !empty($item->room->value)  &&  empty($record->room)) {
                            $record->room   =   $item->room->value;
                        }

                        if(isset($item->phones->phone) && count($item->phones->phone)) {
                            foreach ($item->phones->phone as $contact_phone) {

                                $validator = Validator::make(array('contact' => $contact_phone->value), [
                                    'contact' => 'email',
                                ]);
                                if ($validator->fails()) {
                                    if((mb_strlen($contact_phone->value, "UTF-8") >= 10) && (mb_strlen($contact_phone->value, "UTF-8") <= 12)   &&  empty($record->mobile_phone)) {
                                        $record->mobile_phone = $contact_phone->value;
                                    }
                                    if((mb_strlen($contact_phone->value, "UTF-8") > 3) && (mb_strlen($contact_phone->value, "UTF-8") < 10)  &&  empty($record->city_phone)) {
                                        $record->city_phone = $contact_phone->value;
                                    }
                                    if(mb_strlen($contact_phone->value, "UTF-8") == 3   &&  empty($record->phone)) {
                                        $record->phone = $contact_phone->value;
                                    }

                                }
                                else {
                                    if(empty($record->email)) {
                                        $record->email  =   $contact_phone->value;
                                    }
                                    else {
                                        if($record->email   !=   $contact_phone->value  &&  empty($record->email_secondary)) {
                                            $record->email_secondary    =   $contact_phone->value;
                                        }
                                    }
                                }
                            }
                        }

                        if(Storage::disk('public')->exists('/xml/iphotos/'    .   $item->id   .   ".jpg")   &&  $record->avatar ==  '/images/faces/default.svg') {
                            $path   =   Config::get('image.avatar_path')   .   '/' .   $item->id   .   ".jpg";
                            Storage::disk('public')->put($path, Storage::disk('public')->get('/xml/iphotos/'    .   $item->id   .   ".jpg"), 'public');
                            $size   =   Storage::disk('public')->getSize($path);
                            $type   =   Storage::disk('public')->getMimetype($path);

                            if($size <= 3000000) {
                                if ($type == "image/jpeg" || $type == "image/pjpeg" || $type == "image/png") {
                                    $manager = new ImageManager(array('driver' => 'imagick'));
                                    $image = $manager->make(storage_path('app/public') . '/' . $path)->fit(Config::get('image.avatar_width'))->save(storage_path('app/public') . '/' . $path);
                                    $record->avatar = Storage::disk('public')->url($path);
                                }
                            }
                        }

                        $record->save();

                    }
                    else {
                        Log::info('No first name and last name for ' .   $item->fullname->value);
                    }
                }

                $processed_counter ++;
                $bar->advance();
            }
        }
        $bar->finish();
    }
}
