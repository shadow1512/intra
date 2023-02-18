<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;

use App\User;
use App\Dep;
use App\News;
use App\LibBook;
use App\LibRazdel;
use App\Terms;
use cijic\phpMorphy\Facade\Morphy;

class buildsearchindexdelta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'searchindexdelta:createhourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating updates of terms table - index of changed data';

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
        /*$pspell_config = pspell_config_create('ru_RU', null, null, 'utf-8');
        pspell_config_personal($pspell_config, "/var/www/intra/laravel/storage/app/public/dict/ru.custom.rws");
        $pspell_link = pspell_new_config($pspell_config);*/
        $fp =   fopen("/var/www/intra/laravel/storage/app/public/dict/ru.custom.txt",   "a+");

        //Секция "пользователи" - выбираем только измененные за последний час, включая тех, кто был удален
        
        $users = User::withTrashed()->whereRaw('users.updated_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)')
                ->orderBy('lname', 'asc')->orderBy('fname',    'asc')->orderBy('mname',    'asc')
            ->leftJoin('deps_peoples', function($join) {
                $join->on('users.id', '=', 'deps_peoples.people_id')->whereRaw('deps_peoples.deleted_at IS NULL');
            })
            ->select('users.*', 'deps_peoples.work_title as work_title')->get();

        $bar = $this->output->createProgressBar(count($users));

        foreach($users as $user) {
            //удаляем всю информацию об этой записи, если она ранее существовала
            Terms::where('section', 'users')->where('record',    $user->id)->delete();
            //если запись пользователя не была удалена, то добавляем в Индекс
            
            //Имя
            $fname= preg_replace("/[^0-9A-zА-яЁё\-]/iu", "", $user->fname);
            $fname_res  =   array();
            if(mb_strripos($fname,  "-",    0,  "UTF-8")    !== false) {
                $fnames =   explode("-",    $fname);
                foreach($fnames as  $part) {
                    if(trim($part)) {
                        $fname_res[]    =   trim($part);
                    }
                }
            }
            else {
                $fname_res[]  =   trim($fname);
            }

            foreach($fname_res as $part) {
                if($part) {
                    $term = new Terms();
                    $part  =   mb_strtoupper($part, "UTF-8");
                    $baseform = Morphy::getBaseForm($part);
                    if($baseform && count($baseform)) {
                        $term->baseterm = $baseform[0];
                    }
                    $term->term = $part;
                    $term->section = 'users';
                    $term->record = $user->id;
                    $term->partial  =   'fname';
                    $term->save();
                }
            }


            //pspell_add_to_personal($pspell_link, mb_strtoupper( preg_replace("/[^0-9A-zА-яЁё]/iu", "", $user->fname), "UTF-8"));
            $str    =   mb_strtoupper( preg_replace("/[^0-9A-zА-яЁё]/iu", "", $user->fname), "UTF-8");
            if($str) {
                fwrite($fp, $str    .   PHP_EOL);
            }

            //Фамилия

            $lname= preg_replace("/[^0-9A-zА-яЁё\-]/iu", "", $user->lname);
            $lname_res  =   array();
            if(mb_strripos($lname,  "-",    0,  "UTF-8")    !== false) {
                $lnames =   explode("-",    $lname);
                foreach($lnames as  $part) {
                    if(trim($part)) {
                        $lname_res[]    =   trim($part);
                    }
                }
            }
            else {
                $lname_res[]  =   trim($lname);
            }

            foreach($lname_res as $part) {
                if($part) {
                    $term = new Terms();
                    $part  =   mb_strtoupper($part, "UTF-8");
                    $baseform = Morphy::getBaseForm($part);
                    if($baseform && count($baseform)) {
                        $term->baseterm = $baseform[0];
                    }
                    $term->term = $part;
                    $term->section = 'users';
                    $term->record = $user->id;
                    $term->partial  =   'lname';
                    $term->save();
                }
            }

            //pspell_add_to_personal($pspell_link, mb_strtoupper( preg_replace("/[^0-9A-zА-яЁё]/iu", "", $user->mname), "UTF-8"));
            $str    =   mb_strtoupper( preg_replace("/[^0-9A-zА-яЁё]/iu", "", $user->lname), "UTF-8");
            if($str) {
                fwrite($fp, $str    .   PHP_EOL);
            }

            //Отчество

            $mname= preg_replace("/[^0-9A-zА-яЁё\-]/iu", "", $user->mname);
            $mname_res  =   array();
            if(mb_strripos($mname,  "-",    0,  "UTF-8")    !== false) {
                $mnames =   explode("-",    $mname);
                foreach($mnames as  $part) {
                    if(trim($part)) {
                        $mname_res[]    =   trim($part);
                    }
                }
            }
            else {
                $mname_res[]  =   trim($mname);
            }

            foreach($mname_res as $part) {
                if($part) {
                    $term = new Terms();
                    $part  =   mb_strtoupper($part, "UTF-8");
                    $baseform = Morphy::getBaseForm($part);
                    if($baseform && count($baseform)) {
                        $term->baseterm = $baseform[0];
                    }
                    $term->term = $part;
                    $term->section = 'users';
                    $term->record = $user->id;
                    $term->partial  =   'mname';
                    $term->save();
                }
            }

            //pspell_add_to_personal($pspell_link, mb_strtoupper( preg_replace("/[^0-9A-zА-яЁё]/iu", "", $user->mname), "UTF-8"));
            $str    =   mb_strtoupper( preg_replace("/[^0-9A-zА-яЁё]/iu", "", $user->mname), "UTF-8");
            if($str) {
                fwrite($fp, $str    .   PHP_EOL);
            }

            //Номер комнаты

            if(trim($user->room)) {
                $term = new Terms();
                $term->baseterm = trim(mb_strtoupper($user->room, "UTF-8"));
                $term->term = $user->room;
                $term->section = 'users';
                $term->record = $user->id;
                $term->partial  =   'room';
                $term->save();
            }

            //телефон
            if(trim($user->phone)) {
                $term = new Terms();
                $term->baseterm = trim(mb_strtoupper($user->phone, "UTF-8"));
                $term->term = $user->phone;
                $term->section = 'users';
                $term->record = $user->id;
                $term->partial  =   'phone';
                $term->save();
            }

            //городской телефон
            if(trim($user->city_phone)) {
                $term = new Terms();
                $term->baseterm = trim(mb_strtoupper($user->city_phone, "UTF-8"));
                $term->term = $user->city_phone;
                $term->section = 'users';
                $term->record = $user->id;
                $term->partial  =   'phone';
                $term->save();
            }

            //мобильный телефон
            if(trim($user->mobile_phone)) {
                $term = new Terms();
                $term->baseterm = trim(mb_strtoupper($user->mobile_phone, "UTF-8"));
                $term->term = $user->mobile_phone;
                $term->section = 'users';
                $term->record = $user->id;
                $term->partial  =   'phone';
                $term->save();
            }

            //ip телефон
            if(trim($user->ip_phone)) {
                $term = new Terms();
                $term->baseterm = trim(mb_strtoupper($user->ip_phone, "UTF-8"));
                $term->term = $user->ip_phone;
                $term->section = 'users';
                $term->record = $user->id;
                $term->partial  =   'phone';
                $term->save();
            }

            //email
            if(trim($user->email)) {
                $term = new Terms();
                $term->baseterm = trim(mb_strtoupper($user->email, "UTF-8"));
                $term->term = $user->email;
                $term->section = 'users';
                $term->record = $user->id;
                $term->partial  =   'email';
                $term->save();
            }

            //email secondary
            if(trim($user->email_secondary)) {
                $term = new Terms();
                $term->baseterm = trim(mb_strtoupper($user->email_secondary, "UTF-8"));
                $term->term = $user->email_secondary;
                $term->section = 'users';
                $term->record = $user->id;
                $term->partial  =   'email';
                $term->save();
            }

            //должность. Тут веселее, т.к. состоит из нескольких слов
            if(trim($user->work_title)) {
                $words = explode(" ", $user->work_title);
                if(count($words)) {
                    foreach($words as $word) {
                        $word   =   preg_replace("/[^0-9A-zА-яЁё\-]/iu", "", $word);
                        if(mb_strlen(trim($word), "UTF-8") >= 3) {
                            $word_res  =   array();
                            if(mb_strripos($word,  "-",    0,  "UTF-8")    !== false) {
                                $words =   explode("-",    $word);
                                foreach($words as  $part) {
                                    if(trim($part)) {
                                        $word_res[]    =   trim($part);
                                    }
                                }
                            }
                            else {
                                $word_res[]  =   trim($word);
                            }

                            foreach($word_res as $part) {
                                $term = new Terms();
                                $part  =   mb_strtoupper($part, "UTF-8");
                                $baseform = Morphy::getBaseForm($part);
                                if($baseform && count($baseform)) {
                                    $term->baseterm = $baseform[0];
                                }
                                $term->term = $part;
                                $term->section = 'users';
                                $term->record = $user->id;
                                $term->partial  =   'work';
                                $term->save();
                            }
                        }
                    }
                }
            }
            
            

            $bar->advance();
        }

        $bar->finish();

        //Секция "отделы" - оставляем полное обновление базы данных, но проверяем на предмет удаленных
        $deps = Dep::withTrashed()->orderBy('name', 'asc')->get();
        $bar = $this->output->createProgressBar(count($deps));

        foreach($deps as $dep) {
            //удаляем всю информацию об этой записи, если она ранее существовала
            Terms::where('section', 'deps')->where('record',    $dep->id)->delete();
            
            if(is_null($dep->deleted_at)) {
                //Наименование Тут веселее, т.к. состоит из нескольких слов

                if(trim($dep->name)) {
                    $words = explode(" ", $dep->name);
                    if(count($words)) {
                        foreach($words as $word) {
                            $word   =   preg_replace("/[^0-9A-zА-яЁё\-]/iu", "", $word);
                            if(mb_strlen(trim($word), "UTF-8") >= 3) {

                                $word_res  =   array();
                                if(mb_strripos($word,  "-",    0,  "UTF-8")    !== false) {
                                    $words =   explode("-",    $word);
                                    foreach($words as  $part) {
                                        if(trim($part)) {
                                            $word_res[]    =   trim($part);
                                        }
                                    }
                                }
                                else {
                                    $word_res[]  =   trim($word);
                                }

                                foreach($word_res as $part) {
                                    $term = new Terms();
                                    $part  =   mb_strtoupper($part, "UTF-8");
                                    $baseform = Morphy::getBaseForm($part);
                                    if($baseform && count($baseform)) {
                                        $term->baseterm = $baseform[0];
                                    }
                                    $term->term = $part;
                                    $term->section = 'deps';
                                    $term->record = $dep->id;
                                    $term->partial  =   'dep';
                                    $term->save();
                                }
                            }
                        }
                    }
                }
            }
            
            $bar->advance();
        }

        $bar->finish();

        //Секция "библиотека/разделы"
        $lrazdels = LibRazdel::orderBy('name', 'asc')->get();
        $bar = $this->output->createProgressBar(count($lrazdels));

        foreach($lrazdels as $razdel) {
            //удаляем всю информацию об этой записи, если она ранее существовала
            Terms::where('section', 'librazdels')->where('record',    $razdel->id)->delete();
            //Наименование Тут веселее, т.к. состоит из нескольких слов

            if(trim($razdel->name)) {
                $words = explode(" ", $razdel->name);
                if(count($words)) {
                    foreach($words as $word) {
                        if(mb_strlen(trim($word), "UTF-8") >= 3) {
                            $word   =   preg_replace("/[^А-яЁёA-z0-9]/ius",    "", $word);
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
            $bar->advance();
        }
        $bar->finish();

        //Секция "библиотека/книги"
        $lbooks = LibBook::orderBy('name', 'asc')->get();
        $bar = $this->output->createProgressBar(count($lbooks));
        foreach($lbooks as $book) {
            //удаляем всю информацию об этой записи, если она ранее существовала
            Terms::where('section', 'libbooks')->where('record',    $book->id)->delete();
            //Наименование Тут веселее, т.к. состоит из нескольких слов

            if(trim($book->name)) {
                $words = explode(" ", $book->name);
                if(count($words)) {
                    foreach($words as $word) {
                        if(mb_strlen(trim($word), "UTF-8") >= 3) {
                            $word   =   preg_replace("/[^А-яЁёA-z0-9]/ius",    "", $word);
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
                            $word   =   preg_replace("/[^А-яЁёA-z0-9]/ius",    "", $word);
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
                $words   =   preg_replace("/[^А-яЁёA-z0-9]/ius",    " ", $book->anno);
                $words = explode(" ", $words);
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
            $bar->advance();
        }

        $bar->finish();

        //Секция "новости"
        $news = News::orderBy('importancy', 'desc')->get();
        $bar = $this->output->createProgressBar(count($news));
        foreach($news as $record) {
            //удаляем всю информацию об этой записи, если она ранее существовала
            Terms::where('section', 'news')->where('record',    $record->id)->delete();
            //Наименование Тут веселее, т.к. состоит из нескольких слов

            if(trim($record->title)) {
                $words   =   preg_replace("/[^А-яЁёA-z0-9]/ius",    " ", $record->title);
                $words = explode(" ", $words);
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
                $words   =   preg_replace("/[^А-яЁёA-z0-9]/ius",    " ", $record->fulltext);
                $words = explode(" ", $words);
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
            $bar->advance();
        }

        //pspell_save_wordlist($pspell_link);
        fclose($fp);
        exec('aspell --lang=ru create master /var/www/intra/laravel/storage/app/public/dict/ru.custom.rws <  /var/www/intra/laravel/storage/app/public/dict/ru.custom.txt');
        $bar->finish();

    }
}

