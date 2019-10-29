<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;

use App\User;
use App\Dep;
use DB;
use PDO;
use App\News;
use App\LibBook;
use App\LibRazdel;
use App\Terms;
use cijic\phpMorphy\Facade\Morphy;

class buildsearchindex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'searchindex:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating database table terms - index of all data';

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
        Terms::truncate();
        //создаем файлик, в который потом добавим в словарь
        // Setup the personal dictionary

        /*$pspell_config = pspell_config_create('ru_RU', null, null, 'utf-8');
        pspell_config_personal($pspell_config, "/var/www/intra/laravel/storage/app/public/dict/ru.custom.rws");
        $pspell_link = pspell_new_config($pspell_config);*/
        $fp =   fopen("/var/www/intra/laravel/storage/app/public/dict/ru.custom.rws",   "w+");

        //Секция "пользователи"
        $users = User::orderBy('name', 'asc')
            ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
            ->select('users.*', 'deps_peoples.work_title as work_title')->get();

        $bar = $this->output->createProgressBar(count($users));

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
            $term->partial  =   'fname';
            $term->save();

            //pspell_add_to_personal($pspell_link, mb_strtoupper( preg_replace("/[^0-9A-zА-яЁё]/iu", "", $user->fname), "UTF-8"));
            fwrite($fp, mb_strtoupper( preg_replace("/[^0-9A-zА-яЁё]/iu", "", $user->fname), "UTF-8"));
            //Фамилия

            $term = new Terms();
            $baseform = Morphy::getBaseForm(trim(mb_strtoupper($user->lname, "UTF-8")));
            if($baseform && count($baseform)) {
                $term->baseterm = $baseform[0];
            }
            $term->term = trim($user->lname);
            $term->section = 'users';
            $term->record = $user->id;
            $term->partial  =   'lname';
            $term->save();

            //pspell_add_to_personal($pspell_link, mb_strtoupper( preg_replace("/[^0-9A-zА-яЁё]/iu", "", $user->lname), "UTF-8"));
            fwrite($fp, mb_strtoupper( preg_replace("/[^0-9A-zА-яЁё]/iu", "", $user->lname), "UTF-8"));
            //Отчество

            $term = new Terms();
            $baseform = Morphy::getBaseForm(trim(mb_strtoupper($user->mname, "UTF-8")));
            if($baseform && count($baseform)) {
                $term->baseterm = $baseform[0];
            }
            $term->term = trim($user->mname);
            $term->section = 'users';
            $term->record = $user->id;
            $term->partial  =   'mname';
            $term->save();

            //pspell_add_to_personal($pspell_link, mb_strtoupper( preg_replace("/[^0-9A-zА-яЁё]/iu", "", $user->mname), "UTF-8"));
            fwrite($fp, mb_strtoupper( preg_replace("/[^0-9A-zА-яЁё]/iu", "", $user->mname), "UTF-8"));
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
                        if(mb_strlen(trim($word), "UTF-8") >= 3) {
                            $term = new Terms();
                            $baseform = Morphy::getBaseForm(trim(mb_strtoupper($word, "UTF-8")));
                            if($baseform && count($baseform)) {
                                $term->baseterm = $baseform[0];
                            }
                            $term->term = trim($word);
                            $term->section = 'users';
                            $term->record = $user->id;
                            $term->partial  =   'work';
                            $term->save();
                        }
                    }
                }
            }

            $bar->advance();
        }

        $bar->finish();

        //Секция "отделы"
        $deps = Dep::orderBy('name', 'asc')->get();
        $bar = $this->output->createProgressBar(count($deps));

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
                            $term->partial  =   'dep';
                            $term->save();
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
        $bar->finish();

    }
}
