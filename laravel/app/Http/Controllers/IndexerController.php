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
        //создаем файлик, который потом добавим в словарь
        Storage::disk('public')->put('pspell_custom.txt', "", 'public');
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
            $term->term = $user->fname;
            $term->section = 'users';
            $term->record = $user->id;
            $term->save();

            Storage::disk('public')->append('pspell_custom.txt', $user->fname);
            //Фамилия

            $term = new Terms();
            $baseform = Morphy::getBaseForm(trim(mb_strtoupper($user->lname, "UTF-8")));
            if($baseform && count($baseform)) {
                $term->baseterm = $baseform[0];
            }
            $term->term = $user->lname;
            $term->section = 'users';
            $term->record = $user->id;
            $term->save();

            Storage::disk('public')->append('pspell_custom.txt', $user->lname);
            //Отчество

            $term = new Terms();
            $baseform = Morphy::getBaseForm(trim(mb_strtoupper($user->mname, "UTF-8")));
            if($baseform && count($baseform)) {
                $term->baseterm = $baseform[0];
            }
            $term->term = $user->mname;
            $term->section = 'users';
            $term->record = $user->id;
            $term->save();

            Storage::disk('public')->append('pspell_custom.txt', $user->mname);
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


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
}