<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 11.12.2017
 * Time: 10:18
 */

namespace App\Http\Controllers;

use App\Deps_Peoples;
use App\Deps_Temporal;
use App\User;
use App\Dep;
use DB;
use PDO;
use App\News;
use App\LibBook;
use App\LibRazdel;
use App\Terms;
use cijic\phpMorphy\Facade\Morphy;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Config;
use DateTime;
use DateInterval;
use DOMDocument;
use Illuminate\Support\Facades\Validator;


class IndexerController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        Terms::truncate();
        //создаем файлик, в который потом добавим в словарь
        // Setup the personal dictionary

        //Секция "пользователи"
        $users = User::orderBy('name', 'asc')
            ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
            ->select('users.*', 'deps_peoples.work_title as work_title')->get();
        foreach($users as $user) {
            //Имя

            $term = new Terms();
            $baseform = Morphy::getBaseForm(trim(mb_strtoupper($user->fname, "UTF-8")));
            if($baseform && count($baseform)) {
                $term->baseterm = $baseform[0];
            }
            $term->term = trim($user->fname);
            $term->section = 'users';
            $term->record = $user->id;
            $term->save();

            //Фамилия

            $term = new Terms();
            $baseform = Morphy::getBaseForm(trim(mb_strtoupper($user->lname, "UTF-8")));
            if($baseform && count($baseform)) {
                $term->baseterm = $baseform[0];
            }
            $term->term = trim($user->lname);
            $term->section = 'users';
            $term->record = $user->id;
            $term->save();

            //Отчество

            $term = new Terms();
            $baseform = Morphy::getBaseForm(trim(mb_strtoupper($user->mname, "UTF-8")));
            if($baseform && count($baseform)) {
                $term->baseterm = $baseform[0];
            }
            $term->term = trim($user->mname);
            $term->section = 'users';
            $term->record = $user->id;
            $term->save();

            //Номер комнаты

            if(trim($user->room)) {
                $term = new Terms();
                $term->baseterm = trim(mb_strtoupper($user->room, "UTF-8"));
                $term->term = $user->room;
                $term->section = 'users';
                $term->record = $user->id;
                $term->save();
            }

            //телефон
            if(trim($user->phone)) {
                $term = new Terms();
                $term->baseterm = trim(mb_strtoupper($user->phone, "UTF-8"));
                $term->term = $user->phone;
                $term->section = 'users';
                $term->record = $user->id;
                $term->save();
            }

            //городской телефон
            if(trim($user->city_phone)) {
                $term = new Terms();
                $term->baseterm = trim(mb_strtoupper($user->city_phone, "UTF-8"));
                $term->term = $user->city_phone;
                $term->section = 'users';
                $term->record = $user->id;
                $term->save();
            }

            //мобильный телефон
            if(trim($user->mobile_phone)) {
                $term = new Terms();
                $term->baseterm = trim(mb_strtoupper($user->mobile_phone, "UTF-8"));
                $term->term = $user->mobile_phone;
                $term->section = 'users';
                $term->record = $user->id;
                $term->save();
            }

            //email
            if(trim($user->email)) {
                $term = new Terms();
                $term->baseterm = trim(mb_strtoupper($user->email, "UTF-8"));
                $term->term = $user->email;
                $term->section = 'users';
                $term->record = $user->id;
                $term->save();
            }

            //email secondary
            if(trim($user->email_secondary)) {
                $term = new Terms();
                $term->baseterm = trim(mb_strtoupper($user->email_secondary, "UTF-8"));
                $term->term = $user->email_secondary;
                $term->section = 'users';
                $term->record = $user->id;
                $term->save();
            }

            //должность. Тут веселее, т.к. состоит из нескольких слов
            if(trim($user->work_title)) {
                $words = explode(" ", $user->work_title);
                if(count($words)) {
                    foreach($words as $word) {
                        if(mb_strlen(trim($word), "UTF-8") >= 3) {
                            $term = new Terms();
                            $baseform = Morphy::getBaseForm(trim(mb_strtoupper($word, "UTF-8")));
                            if($baseform && count($baseform)) {
                                $term->baseterm = $baseform[0];
                            }
                            $term->term = trim($word);
                            $term->section = 'users';
                            $term->record = $user->id;
                            $term->save();
                        }
                    }
                }
            }
        }

        //Секция "отделы"
        $deps = Dep::orderBy('name', 'asc')->get();
        foreach($deps as $dep) {
            //Наименование Тут веселее, т.к. состоит из нескольких слов

            if(trim($dep->name)) {
                $words = explode(" ", $dep->name);
                if(count($words)) {
                    foreach($words as $word) {
                        if(mb_strlen(trim($word), "UTF-8") >= 3) {
                            $term = new Terms();
                            $baseform = Morphy::getBaseForm(trim(mb_strtoupper($word, "UTF-8")));
                            if($baseform && count($baseform)) {
                                $term->baseterm = $baseform[0];
                            }
                            $term->term = trim($word);
                            $term->section = 'deps';
                            $term->record = $dep->id;
                            $term->save();
                        }
                    }
                }
            }
        }

        //Секция "библиотека/разделы"
        $lrazdels = LibRazdel::orderBy('name', 'asc')->get();
        foreach($lrazdels as $razdel) {
            //Наименование Тут веселее, т.к. состоит из нескольких слов

            if(trim($razdel->name)) {
                $words = explode(" ", $razdel->name);
                if(count($words)) {
                    foreach($words as $word) {
                        if(mb_strlen(trim($word), "UTF-8") >= 3) {
                            $term = new Terms();
                            $baseform = Morphy::getBaseForm(trim(mb_strtoupper($word, "UTF-8")));
                            if($baseform && count($baseform)) {
                                $term->baseterm = $baseform[0];
                            }
                            $term->term = trim($word);
                            $term->section = 'librazdels';
                            $term->record = $razdel->id;
                            $term->save();
                        }
                    }
                }
            }
        }

        //Секция "библиотека/книги"
        $lbooks = LibBook::orderBy('name', 'asc')->get();
        foreach($lbooks as $book) {
            //Наименование Тут веселее, т.к. состоит из нескольких слов

            if(trim($book->name)) {
                $words = explode(" ", $book->name);
                if(count($words)) {
                    foreach($words as $word) {
                        if(mb_strlen(trim($word), "UTF-8") >= 3) {
                            $term = new Terms();
                            $baseform = Morphy::getBaseForm(trim(mb_strtoupper($word, "UTF-8")));
                            if($baseform && count($baseform)) {
                                $term->baseterm = $baseform[0];
                            }
                            $term->term = trim($word);
                            $term->section = 'libbooks';
                            $term->record = $book->id;
                            $term->save();
                        }
                    }
                }
            }
            //Автор Тут веселее, т.к. состоит из нескольких слов

            if(trim($book->authors)) {
                $words = explode(" ", $book->authors);
                if(count($words)) {
                    foreach($words as $word) {
                        if(mb_strlen(trim($word), "UTF-8") >= 3) {
                            $term = new Terms();
                            $baseform = Morphy::getBaseForm(trim(mb_strtoupper($word, "UTF-8")));
                            if($baseform && count($baseform)) {
                                $term->baseterm = $baseform[0];
                            }
                            $term->term = trim($word);
                            $term->section = 'libbooks';
                            $term->record = $book->id;
                            $term->save();
                        }
                    }
                }
            }

            //Аннотация Тут веселее, т.к. состоит из нескольких слов

            if(trim($book->anno)) {
                $words = explode(" ", $book->anno);
                if(count($words)) {
                    foreach($words as $word) {
                        if(mb_strlen(trim($word), "UTF-8") >= 3) {
                            $term = new Terms();
                            $baseform = Morphy::getBaseForm(trim(mb_strtoupper($word, "UTF-8")));
                            if($baseform && count($baseform)) {
                                $term->baseterm = $baseform[0];
                            }
                            $term->term = trim($word);
                            $term->section = 'libbooks';
                            $term->record = $book->id;
                            $term->save();
                        }
                    }
                }
            }
        }

        //Секция "новости"
        $news = News::orderBy('importancy', 'desc')->get();
        foreach($news as $record) {
            //Наименование Тут веселее, т.к. состоит из нескольких слов

            if(trim($record->title)) {
                $words = explode(" ", $record->title);
                if(count($words)) {
                    foreach($words as $word) {
                        if(mb_strlen(trim($word), "UTF-8") >= 3) {
                            $term = new Terms();
                            $baseform = Morphy::getBaseForm(trim(mb_strtoupper($word, "UTF-8")));
                            if($baseform && count($baseform)) {
                                $term->baseterm = $baseform[0];
                            }
                            $term->term = trim($word);
                            $term->section = 'news';
                            $term->record = $record->id;
                            $term->save();
                        }
                    }
                }
            }
            //Текст Тут веселее, т.к. состоит из нескольких слов

            if(trim($record->fulltext)) {
                $words = explode(" ", $record->fulltext);
                if(count($words)) {
                    foreach($words as $word) {
                        if(mb_strlen(trim($word), "UTF-8") >= 3) {
                            $term = new Terms();
                            $baseform = Morphy::getBaseForm(trim(mb_strtoupper($word, "UTF-8")));
                            if($baseform && count($baseform)) {
                                $term->baseterm = $baseform[0];
                            }
                            $term->term = trim($word);
                            $term->section = 'news';
                            $term->record = $record->id;
                            $term->save();
                        }
                    }
                }
            }
        }
    }

    public function structloader() {
        $record_counter =   0;
        $processed_counter  =   0;
        $counter_added      =   0;

        $xmlstring  =   Storage::disk('public')->get('/xml/org-all.xml');
        $olddict    =   simplexml_load_string($xmlstring);
        if(($olddict->count() > 0) &&  ($olddict->children()->count()   >   0)) {
            $items = $olddict->children()->children();
            //первый проход - заполняем данные старого справочника
            foreach ($items as $item) {

                $record_counter++;
                $record = null;
                //создавать ли новую запись
                $nocreate = false;
                if (isset($item->id) && !empty($item->id)) {
                    $record = new Deps_Temporal();
                    $record->source_id  =   $item->id;
                    if (isset($item->parent1->value) && !empty($item->parent1->value)) {
                        $record->parent_id  =   $item->parent1->value;
                    }
                    if (isset($item->name->value) && !empty($item->name->value)) {
                        $record->name  =   $item->name->value;
                    }

                    $record->save();
                }
            }
        }

        print("Загружено $record_counter    подразделений\r\n");
    }

    public function dirloader() {

        $record_counter =   0;
        $processed_counter  =   0;
        $counter_added      =   0;

        $xmlstring  =   Storage::disk('public')->get('/xml/sprav.xml');
        $olddict    =   simplexml_load_string($xmlstring);
        if(($olddict->count() > 0) &&  ($olddict->children()->count()   >   0)){
            $items  =   $olddict->children()->children();
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
                                        if (isset($names[0])) {
                                            $lname = preg_replace("/[^А-я]/ius",    "", $names[0]);
                                        }
                                        if (isset($names[1])) {
                                            $fname = preg_replace("/[^А-я]/ius",    "", $names[1]);
                                            //маленький хак на левое имя
                                            if($fname   ==  "Янина") {
                                                $fname  =   "Яна";
                                            }
                                        }
                                        if (isset($names[2])) {
                                            $mname = preg_replace("/[^А-я]/ius",    "", $names[2]);
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
                                        if (isset($names[0])) {
                                            $lname = preg_replace("/[^А-я]/ius",    "", $names[0]);
                                        }
                                        if (isset($names[1])) {
                                            $fname = preg_replace("/[^А-я]/ius",    "", $names[1]);
                                            if($fname   ==  "Янина") {
                                                $fname  =   "Яна";
                                            }
                                        }
                                        if (isset($names[2])) {
                                            $mname = preg_replace("/[^А-я]/ius",    "", $names[2]);
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
                        if (isset($names[0])) {
                            $lname = preg_replace("/[^А-я]/ius",    "", $names[0]);
                        }
                        if (isset($names[1])) {
                            $fname = preg_replace("/[^А-я]/ius",    "", $names[1]);

                            if($fname   ==  "Янина") {
                                $fname  =   "Яна";
                            }
                        }
                        if (isset($names[2])) {
                            $mname = preg_replace("/[^А-я]/ius",    "", $names[2]);
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
                    if (isset($names[0])    &&  trim($names[0])  &&  empty($record->lname)) {
                        $record->lname = preg_replace("/[^А-я]/ius",    "", $names[0]);
                    }
                    if (isset($names[1])    &&  trim($names[1])  &&  empty($record->fname)) {
                        $record->fname = preg_replace("/[^А-я]/ius",    "", $names[1]);

                        if($record->fname   ==  "Янина") {
                            $record->fname  =   "Яна";
                        }
                    }
                    if (isset($names[2])    &&  trim($names[2])  &&  empty($record->mname)) {
                        $record->mname = preg_replace("/[^А-я]/ius",    "", $names[2]);
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
                    $names  .=  mb_substr($record->fname,   0,  1,  "UTF-8")    .   ".";
                }

                $works= Deps_Peoples::where("people_id",    $record->id)->first();
                if($works->work_title) {
                    $names  .=  " - "   .   $works->work_title;
                }

                $record->name=   $names;
                $record->save();

                $processed_counter ++;
            }
        }
        User::where('avatar',   NULL)->update(['avatar' => '/images/faces/default.svg']);
        print_r($record_counter . " прочитано, "    .   $processed_counter  .   " обработано, "    .   $counter_added  .   " добавлено");
    }

    public function createXMLFromUpdatedUsers() {

        $caldate = new DateTime();
        $finish  =   $caldate->format("Y-m-d H:i:s");
        $caldate->sub(new DateInterval("P1D"));
        $start =   $caldate->format("Y-m-d H:i:s");

        $up_users   =   User::select("users.*", "deps_peoples.work_title",  "deps.name as depname",    "deps.parent_id",   "user_keys.sid",    "user_keys.user_login")
                        ->leftJoin("deps_peoples",  "users.id", "=",    "deps_peoples.people_id")
                        ->leftJoin("deps",  "deps.id",  "=",    "deps_peoples.dep_id")
                        ->leftJoin("user_keys",  "users.id",  "=",    "user_keys.user_id")
                        ->whereBetween("users.updated_at",    [$start, $finish])->get();
        if($up_users->count()) {
            if(!Storage::disk('public')->exists('/xml/export/'    .   $caldate->format("Ymd")   .   '/')) {
                Storage::disk('public')->makeDirectory('/xml/export/' . $caldate->format("Ymd") . '/');
            }
            foreach($up_users as $user) {
                    $parent_id  =   mb_substr($user->parent_id, 0,  4,  "UTF-8");
                    $department_name   =   Dep::where("parent_id", "=",    $parent_id)->value("name");

                    $dom = new DOMDocument('1.0', 'utf-8');
                    $data       =   $dom->createElement("data");
                    $datanode   =   $dom->appendChild($data);
                    $userel     =   $dom->createElement("user");
                    $usernode   =   $datanode->appendChild($userel);
                    $login      =   $dom->createElement("login",  $user->user_login);
                    $loginnode  =   $usernode->appendChild($login);
                    $sid        =   $dom->createElement("sid",  $user->sid);
                    $sidnode    =   $usernode->appendChild($sid);

                    $fname      =   $dom->createElement("fname",  preg_replace("/[^А-я  \-]/ius",   "", $user->fname));
                    $fnamenode  =   $usernode->appendChild($fname);
                    $mname      =   $dom->createElement("mname",  preg_replace("/[^А-я  \-]/ius",   "", $user->mname));
                    $mnamenode  =   $usernode->appendChild($mname);
                    $lname      =   $dom->createElement("lname",  preg_replace("/[^А-я  \-]/ius",   "", $user->lname));
                    $lnamenode  =   $usernode->appendChild($lname);

                    $phones         =   $dom->createElement("phones");
                    $phonesnode     =   $usernode->appendChild($phones);
                    $localphone     =   $dom->createElement("localphone", preg_replace("/[^0-9]/ius",   "", $user->phone));
                    $localphonenode =   $phonesnode->appendChild($localphone);
                    $mobilephone     =   $dom->createElement("mobilephone", preg_replace("/[^0-9]/ius",   "", $user->mobile_phone));
                    $mobilephonenode =   $phonesnode->appendChild($mobilephone);

                    $address        =   $dom->createElement("address");
                    $addressnode    =   $usernode->appendChild($address);
                    $room           =   $dom->createElement("room", preg_replace("/[^0-9]/ius",   "", $user->room));
                    $roomnode       =   $addressnode->appendChild($room);

                    $email          =   $dom->createElement("emails",   $user->email);
                    $emailnode      =   $usernode->appendChild($email);

                    $work           =   $dom->createElement("work");
                    $worknode       =   $usernode->appendChild($work);
                    $worktitle      =   $dom->createElement("worktitle", $user->work_title);
                    $worktitlenode  =   $worknode->appendChild($worktitle);
                    $division       =   $dom->createElement("division", $user->depname);
                    $divisionnode   =   $worknode->appendChild($division);
                    $department     =   $dom->createElement("department", $department_name);
                    $departmentnode =   $worknode->appendChild($department);

                    $desc           =   $dom->createElement("description",    $user->position_desc);
                    $descnode       =   $usernode->appendChild($desc);

                    $photo          =   $dom->createElement("photopath",  Config::get('app.url')  .   $user->avatar);
                    $photonode      =   $usernode->appendChild($photo);

                    Storage::disk('public')->put('/xml/export/'    .   $caldate->format("Ymd")   .   '/'    .   $user->id   .   '.xml', $dom->saveXML(), 'public');
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}