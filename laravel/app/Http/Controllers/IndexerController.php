<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 11.12.2017
 * Time: 10:18
 */

namespace App\Http\Controllers;

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
use Config;
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

            //email
            if(trim($user->email)) {
                $term = new Terms();
                $term->baseterm = trim(mb_strtoupper($user->email, "UTF-8"));
                $term->term = $user->email;
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

    public function dirloader() {

        $record_counter =   0;
        $processed_counter  =   0;

        $xmlstring  =   Storage::disk('public')->get('/xml/sprav.xml');
        $olddict    =   simplexml_load_string($xmlstring);
        if(($olddict->count() > 0) &&  ($olddict->children()->count()   >   0)){
            $items  =   $olddict->children()->children();
            foreach($items as $item) {
                if(isset($item->phones->phone) && count($item->phones->phone)) {
                    foreach($item->phones->phone as $contact) {

                        if(isset($contact->value) && !empty($contact->value)) {
                            $validator = Validator::make(array('contact'   =>  $contact->value), [
                                'contact'           =>  'email',
                            ]);
                            if (!$validator->fails()) {
                                $record =   User::where('email',    'LIKE', $contact->value)->first();

                                if($record) {
                                    if(isset($item->bdate->value) && !empty($item->bdate->value)) {
                                        $record->birthday   =   date("Y-m-d", strtotime($item->bdate->value));
                                    }
                                    if(isset($item->room->value) && !empty($item->room->value)) {
                                        $record->room   =   $item->room->value;
                                    }

                                    foreach($item->phones->phone as $contact_phone) {

                                        $validator = Validator::make(array('contact' => $contact_phone->value), [
                                            'contact' => 'email',
                                        ]);
                                        if ($validator->fails()) {
                                            $record->phone   =   $contact_phone->value;
                                        }
                                    }

                                    if(Storage::disk('public')->exists('/xml/iphotos/'    .   $item->id   .   ".jpg")) {
                                        $path   =   Storage::disk('public')->put(Config::get('image.avatar_path'), Storage::disk('public')->get('/xml/iphotos/'    .   $item->id   .   ".jpg"), 'public');
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
                                    $processed_counter ++;
                                }
                                else {
                                    print_r("Пропущена запись: "    .   $item->fname->value .   "   "   .   $item->lname->value .   ", причина - нет записи в базе СЭД, email: "    .   $contact->value);
                                }
                            }
                        }
                        else {
                            print_r("Пропущена запись: "    .   $item->fname->value .   "   "   .   $item->lname->value .   ", причина - контакты для связи пустые");
                        }
                    }
                }
                else {
                    print_r("Пропущена запись: "    .   $item->fname->value .   "   "   .   $item->lname->value .   ", причина - нет контактов для связи");
                }

                $record_counter ++;
            }
        }
        print_r($record_counter . " прочитано, "    .   $processed_counter  .   " обработано");
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}