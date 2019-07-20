<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use App\Deps_Peoples;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
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
                //создавать ли новую запись
                $nocreate   =   false;

                /*if($item->id    ==  1302526896 || $item->id ==  536000973) {
                    $item->fullname->value
                }*/

                if(isset($item->phones->phone) && count($item->phones->phone)) {
                    foreach ($item->phones->phone as $contact) {

                        if (isset($contact->value) && !empty($contact->value)) {
                            $validator = Validator::make(array('contact' => $contact->value), [
                                'contact' => 'email',
                            ]);
                            if (!$validator->fails()) {
                                $num = User::where('email', 'LIKE', $contact->value)->count();
                                //у нас бывает так, что на один email несколько сотрудников
                                if ($num > 1) {
                                    /*есть шанс сверить их по имени+фамилии+отчеству, проблема в том, что в телефонном справочнике у подавляющего большинства сотрудников
                                        эти поля не заполнены, а заполнено только общее поле ФИО. Обнадеживает, что вроде бы заполнено единообразно*/
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
                                        if ($lname && $fname && $mname) {
                                            $record = User::where('email', 'LIKE', $contact->value)
                                                ->where('fname', 'LIKE', $fname)
                                                ->where('lname', 'LIKE', $lname)
                                                ->where('mname', 'LIKE', $mname)->first();
                                            if (!$record) {
                                                print_r($contact->value . ": в базе дважды, но по ФИО ($lname $fname $mname) не находится\r\n");
                                                //В этом случае надо просто вывести сообщение на экран, создавать новую запись не надо
                                                $nocreate   =   true;
                                            }
                                        }
                                        else {
                                            //попытка найти по имени и фамилии
                                            if ($lname && $fname) {
                                                $record = User::where('email', 'LIKE', $contact->value)
                                                    ->where('fname', 'LIKE', $fname)
                                                    ->where('lname', 'LIKE', $lname)->first();
                                                if (!$record) {
                                                    print_r($contact->value . ": в базе дважды, но по ФИ ($lname $fname) не находится\r\n");
                                                    //В этом случае надо просто вывести сообщение на экран, создавать новую запись не надо
                                                    $nocreate   =   true;
                                                }
                                            }
                                        }
                                    }
                                }
                                if ($num == 1) {
                                    $record = User::where('email', 'LIKE', $contact->value)->first();
                                }

                                //кусочек проверки по прочим признакам
                                if (!$record) {
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
                                            if($fname   ==  "Янина") {
                                                $fname  =   "Яна";
                                            }
                                        }
                                        if (isset($names[2])) {
                                            $mname = preg_replace("/[^А-яЁё]/ius",    "", $names[2]);
                                        }
                                        if ($lname && $fname && $mname) {
                                            $record = User::where('fname', 'LIKE', $fname)
                                                ->where('lname', 'LIKE', $lname)
                                                ->where('mname', 'LIKE', $mname)->first();
                                            if (!$record) {
                                                $record = new User();
                                                $record->email = $contact->value;
                                                $counter_added++;
                                            }
                                        }
                                        else {
                                            if ($lname && $fname) {
                                                $record = User::where('fname', 'LIKE', $fname)
                                                    ->where('lname', 'LIKE', $lname)->first();
                                                if (!$record) {
                                                    $record = new User();
                                                    $record->email = $contact->value;
                                                    $counter_added++;
                                                }

                                            }
                                            else {
                                                print_r($item->fullname->value . ": в базе нет, но ФИО ($lname $fname $mname) не введено полностью\r\n");
                                                //В этом случае надо просто вывести сообщение на экран, создавать новую запись не надо
                                                continue;
                                            }
                                        }
                                    }
                                    else {
                                        print_r($item->id . ": пустой fullname\r\n");
                                        //В этом случае надо просто вывести сообщение на экран, создавать новую запись не надо
                                        continue;
                                    }
                                }
                            }
                        }
                    }
                }

                if(is_null($record) &&  !$nocreate) {
                    //запись не была найдена и это не исключительный случай, надо создавать запись, для которой не было email
                    //но может быть проблема, что такой пользователь есть по ФИО

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

                            if($fname   ==  "Янина") {
                                $fname  =   "Яна";
                            }
                        }
                        if (isset($names[2])) {
                            $mname = preg_replace("/[^А-яЁё]/ius",    "", $names[2]);
                        }
                        if ($lname && $fname && $mname) {
                            $record = User::where('fname', 'LIKE', $fname)
                                ->where('lname', 'LIKE', $lname)
                                ->where('mname', 'LIKE', $mname)->first();
                            if (!$record) {
                                $record = new User();
                                $counter_added++;
                            }
                        }
                        else {
                            if ($lname && $fname) {
                                $record = User::where('fname', 'LIKE', $fname)
                                    ->where('lname', 'LIKE', $lname)->first();
                                if (!$record) {
                                    $record = new User();
                                    $record->email = $contact->value;
                                    $counter_added++;
                                }

                            }
                            else {
                                print_r($item->fullname->value . ": в базе нет, но ФИО ($lname $fname $mname) не введено полностью\r\n");
                                //В этом случае надо просто вывести сообщение на экран, создавать новую запись не надо
                                continue;
                            }
                        }
                    }
                    else {
                        print_r($item->id . ": пустой fullname\r\n");
                        //В этом случае надо просто вывести сообщение на экран, создавать новую запись не надо
                        continue;
                    }


                }
                if(is_null($record) &&  $nocreate) {
                    continue;
                }

                if(isset($item->fullname->value) && !empty($item->fullname->value)) {
                    $item->fullname->value  =   preg_replace("/\s/ius",    " ", $item->fullname->value);
                    $names = explode( " ", $item->fullname->value);
                    //не надо заменять данные из СЭД
                    /*if(empty($record->name)) {
                        $record->name   =   $item->fullname->value;
                    }*/
                    for($i= 0;  $i<count($names);   $i++) {
                        if(empty($names[$i])) {
                            unset($names[$i]);
                        }
                    }
                    $names  =   array_values($names);

                    if (isset($names[0])    &&  trim($names[0])  &&  empty($record->lname)) {
                        $record->lname = preg_replace("/[^А-яЁё]/ius",    "", $names[0]);
                    }
                    if (isset($names[1])    &&  trim($names[1])  &&  empty($record->fname)) {
                        $record->fname = preg_replace("/[^А-яЁё]/ius",    "", $names[1]);

                        if($record->fname   ==  "Янина") {
                            $record->fname  =   "Яна";
                        }
                    }
                    if (isset($names[2])    &&  trim($names[2])  &&  empty($record->mname)) {
                        $record->mname = preg_replace("/[^А-яЁё]/ius",    "", $names[2]);
                    }
                }

                if(isset($item->bdate->value) && !empty($item->bdate->value)) {
                    $record->birthday   =   date("Y-m-d", strtotime($item->bdate->value));
                }
                if(isset($item->startdate->value) && !empty($item->startdate->value)) {
                    $record->workstart   =   date("Y-m-d", strtotime($item->startdate->value));
                }
                if(isset($item->room->value) && !empty($item->room->value)) {
                    $record->room   =   $item->room->value;
                }
                if(isset($item->phones->phone) && count($item->phones->phone)) {
                    foreach ($item->phones->phone as $contact_phone) {

                        $validator = Validator::make(array('contact' => $contact_phone->value), [
                            'contact' => 'email',
                        ]);
                        if ($validator->fails()) {
                            if((mb_strlen($contact_phone->value, "UTF-8") >= 10) && (mb_strlen($contact_phone->value, "UTF-8") <= 12)) {
                                $record->mobile_phone = $contact_phone->value;
                            }
                            if((mb_strlen($contact_phone->value, "UTF-8") > 3) && (mb_strlen($contact_phone->value, "UTF-8") < 10)) {
                                $record->city_phone = $contact_phone->value;
                            }
                            if(mb_strlen($contact_phone->value, "UTF-8") == 3) {
                                $record->phone = $contact_phone->value;
                            }

                        }
                        else {
                            if(empty($record->email)) {
                                $record->email  =   $contact_phone->value;
                            }
                            else {
                                if($record->email   !=   $contact_phone->value) {
                                    $record->email_secondary    =   $contact_phone->value;
                                }
                            }
                        }
                    }
                }

                if(Storage::disk('public')->exists('/xml/iphotos/'    .   $item->id   .   ".jpg")) {
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

                $user_id    =   $record->id;
                $works  =   Deps_Peoples::Where("people_id",    $user_id)->count();
                if($works   === 0) {
                    if ($user_id && isset($item->depnum->value) && !empty($item->depnum->value) && isset($item->worktitle->value) && !empty($item->worktitle->value)) {
                        $dep = Deps_Temporal::where("source_id", $item->depnum->value)->first();
                        if ($dep) {
                            if(!is_null($dep->sedd_dep_id)) {
                                $dp = new Deps_Peoples();
                                $dp->people_id  = $user_id;
                                $dp->dep_id     =   $dep->sedd_dep_id;
                                $dp->work_title =   $item->worktitle->value;

                                $dp->save();
                            }

                        }
                    }
                }

                $names= $record->lname  .   " " .   mb_substr($record->fname,   0,  1,  "UTF-8")    .   ".";
                if($record->mname) {
                    $names  .=  mb_substr($record->mname,   0,  1,  "UTF-8")    .   ".";
                }

                $works= Deps_Peoples::where("people_id",    $record->id)->first();
                if($works   &&  ($works->count() >   0)   &&  $works->work_title) {
                    $names  .=  " - "   .   $works->work_title;
                }

                $record->name=   $names;
                //$record->name=  null;
                $record->save();

                $processed_counter ++;
                $bar->advance();
            }
        }
        User::where('avatar',   NULL)->update(['avatar' => '/images/faces/default.svg']);
        print_r($record_counter . " прочитано, "    .   $processed_counter  .   " обработано, "    .   $counter_added  .   " добавлено");

        $bar->finish();
    }
}
