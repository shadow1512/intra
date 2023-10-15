<?php

namespace App\Http\Controllers;

use App\Deps_Peoples;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\News;
use App\User;
use App\Dep;
use App\Terms;
use App\Syns;
use App\LibBook;
use App\LibRazdel;
use cijic\phpMorphy\Facade\Morphy;
use DB;
use Config;
use Matrix\Exception;
use Text_LangCorrect;
use Auth;
use App\Search_logs;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*Алгоритм:
        - разбиваем строку на слова;
        - определяем слова от цифр и email;
        - определяем слово на латинице/кирилице;
        - проверяем каждое слово на наличие в словаре через морфолог (соответственно, английский/русский)
            - если слово есть в словаре, берем базовую форму
            - если слова нет в словаре, берем инверсию (раскладка)
                - проверяем инверсию на наличие в словаре
                    - если слово есть в словаре, подставляем вместо исходного найденное, берем базовую форму
                    - если слова нет в словаре, ищет по словарю синонимов
                        - если есть в словаре синонимов, подставляем вместо исходного синонимы, разбиваем, приводим к нормальной форме
                        - если нет в словаре синонимов, то возвращаемся до создания инверсии
            - если инверсия не дала результата, то исходное слово проверяем по словарю синонимов
                - если есть в словаре синонимов, подставляем вместо исходного синонимы, разбиваем, приводим к нормальной форме
                - если нет в словаре синонимов, то стоп, убираем слово из запроса
        - по каждому слову берем поиск по полному совпадению по baseterm
        - массивы найденных разделяем на разделы (библиотека, департаменты, люди, новости).
        - в каждом разделе смотрим идентификаторы записей, которые набрали наибольшее количество совпадений - их выносим наверх по взвешиванию
        - если в разделе нет совпадений, то внутри раздела запускаем поиск по частям каждого слова (т.е. полученный термин обрамляется %)
        - смотрим идентификаторы записей, которые набрали наибольшее количество совпадений - их выносим наверх по взвешиванию */
        
        //Орфография, опечатки
        $dict   = pspell_new ( 'ru', '', '', "utf-8", PSPELL_BAD_SPELLERS);
        //Раскладка
        $corrector = new Text_LangCorrect();
        
        //найденные пользователи, департаменты, книги, документы, секции
        $news = $users = $deps = $books = $razdels  =  $docs    =    $found_sections =   array();
        //найденные слова без определения, кто где, но взвешенный
        $words_records = array();
        $phrase = trim($request->input('phrase'));
        $phrase = mb_substr($phrase, 0, 100);
        if(mb_strlen($phrase) >= 3) {
            $phrase = preg_replace("/[^0-9A-zА-яЁё\.\_\-\@]/iu", " ", $phrase);
            $words = explode(" ", $phrase);
            
            foreach($words as $word) {
                $word=  trim($word);
                if(mb_strlen($word, "UTF-8")) {
                    //Если email, то со словом ничего не надо делать
                    $validator = Validator::make(array('word'   =>  $word), [
                        'word'           =>  'email',
                    ]);
                    //Если цифры или слова
                    if ($validator->fails()) {
                        $word = preg_replace("/[^0-9A-zА-яЁё]/iu", "", $word);//раз не email, то прочую чушь можно выбросить
                        //в начале пытаемся поработать с раскладкой, потому что она круто отрабатывает всякую чушь, которую вводят на английской раскладке, вводя русские (там могут быть знаки преминания)
                        $oldword    =   $word;
                        $word=  $corrector->parse($word, $corrector::KEYBOARD_LAYOUT);
                        //с цифрами ничего делать не надо
                        if(!is_numeric($word) && (mb_strlen($word) >= 3)) {
                            /*Если человек вводит какое-то разумное слово, то если:
                                - он ошибся в транслитерации и еще допустил опечатку, то маловероятно, что выйдет
                                - если он ошибся в чем-то одном, то последовательное применение обоих методов сначала в одном порядке, потом в другом, дадут результат*/
                            //слово есть в словаре
                            $total_found_by_word    =   0;

                            if(pspell_check($dict,  $word)) {
                                $res= $this->getSearchResultsByWord($word);
                                $words_records[]    =   $res;
                                $total_found_by_word    =   count($res);
                                unset($res);
                            }
                            //Слово не нашлось в словаре
                            else {
                                //сначала ищем как есть, вдруг, это правильно?
                                $res= $this->getSearchResultsByWord($oldword);
                                $words_records[]    =   $res;
                                $total_found_by_word    =   count($res);
                                unset($res);
                                //Ну, а если уж не нашлось, то можно пробовать другое
                                if(!$total_found_by_word) {
                                    //пробуем в начале советы (опечатки, если было на русском)
                                    $suggest    =   pspell_suggest($dict,   $word);
                                    //берем только первый вариант, остальные уже не то
                                    if(count($suggest)) {
                                        $word=  $suggest[0];
                                        //var_dump($word);
                                        $res= $this->getSearchResultsByWord($word);
                                        $words_records[]    =   $res;
                                        $total_found_by_word    =   count($res);
                                        unset($res);
                                    }
                                }

                            }
                            //здесь может быть часть email
                            if(!$total_found_by_word) {
                                $email_results  =   array();
                                $word_search_records  =  Terms::where('baseterm', 'LIKE', $oldword.  '%')->orWhere('term',  'LIKE', $oldword)->get();
                                if(count($word_search_records)) {
                                    foreach($word_search_records as $record) {
                                        $email_results[$record->section][]  =   $record->record;
                                    }
                                    foreach($email_results as $section  =>  $records) {
                                        $email_results[$section]    =   array_count_values($records);
                                    }
                                    $words_records[]    =   $email_results;
                                }
                            }
                        }
                        //цифры
                        if(is_numeric($word)) {
                            $digit_results  =   array();
                            $word_search_records  =  Terms::where('baseterm', 'LIKE', $word)->orWhere('term',   'LIKE', $word)->get();
                            if(count($word_search_records)) {
                                foreach($word_search_records as $record) {
                                    $digit_results[$record->section][]  =   $record->record;
                                }
                                foreach($digit_results as $section  =>  $records) {
                                    $digit_results[$section]    =   array_count_values($records);
                                }
                                $words_records[]    =   $digit_results;
                            }
                        }
                    }
                    else {
                        //email будем искать только по той части, что до @, просто потому, что все, что после люди путают
                        $email_parts    =   explode("@",    $word);
                        if(count($email_parts) > 1) {
                            $email_part=   trim(mb_strtoupper($email_parts[0],  "UTF-8"));
                            $email_results  =   array();
                            $word_search_records  =  Terms::where('baseterm', 'LIKE', $email_part.  '%')->orWhere('term', 'LIKE', $email_part.  '%')->get();
                            if(count($word_search_records)) {
                                foreach($word_search_records as $record) {
                                    $email_results[$record->section][]  =   $record->record;
                                }
                                foreach($email_results as $section  =>  $records) {
                                    $email_results[$section]    =   array_count_values($records);
                                }
                                $words_records[]    =   $email_results;
                            }
                        }
                    }
                }
            }
            $search_result = array();
            $parsed_words   =   count($words_records);
            //Ищем по каждому разделу запись, которая вошла в выборку по максимальному количеству слов
            if($parsed_words > 0) {
                //по каждому отработанному слову проверяем найденные разделы
                for($i = 0; $i < $parsed_words; $i++) {
                    $found_sections = array_merge($found_sections, array_keys($words_records[$i]));
                }
                //все уникальные найденные разделы
                $found_sections =   array_unique($found_sections);
                foreach($found_sections as $section) {
                    $search_result[$section] = array();
                    for ($i = 0; $i < $parsed_words; $i++) {
                        if (isset($words_records[$i][$section])) {
                            foreach ($words_records[$i][$section] as $record => $total) {
                                if (array_key_exists($record, $search_result[$section])) {
                                    $search_result[$section][$record] = $search_result[$section][$record] + 1000000;
                                } else {
                                    $search_result[$section][$record] = $total;
                                }
                            }

                        }
                    }
                    arsort($search_result[$section]);
                    //начинаем теперь пляски с базой
                    switch ($section) {
                        case 'users':
                            $user_ids = array_keys($search_result['users']);
                            //Убираем лишние результаты поиска по более, чем одному слову
                            $max_weight =   0;
                            foreach($user_ids as $user_id) {
                                if($search_result['users'][$user_id]    >   $max_weight) {
                                    $max_weight =   $search_result['users'][$user_id];
                                }
                            }
                            $max_user_ids   =   array();
                            //там мы разбиваем, когда одно слово встречается в разных секциях целого и когда два слово входят в одну секцию
                            if($max_weight  <  1000000)  {
                                $max_user_ids   =   $user_ids;
                            }
                            else {
                                foreach($user_ids as $user_id) {
                                    if($search_result['users'][$user_id]   ==   $max_weight) {
                                        $max_user_ids[] =   $user_id;
                                    }
                                }
                            }
                            //
                            $found_records = User::select("users.id", "users.name", "users.avatar", "users.avatar_round", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "users.ip_phone", "users.mobile_phone", "users.birthday", "users.in_office", "deps_peoples.work_title")
                                ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                                ->whereNull('deps_peoples.deleted_at')
                                ->whereIn('users.id', $max_user_ids)->get();
                            $assoc_records = array();
                            foreach ($found_records as $record) {
                                $assoc_records[$record->id] = $record;
                            }
                            if(count($assoc_records)) {
                                foreach ($user_ids as $user_id) {
                                    if(isset($assoc_records[$user_id])) {
                                        $users[] = $assoc_records[$user_id];
                                    }
                                }
                            }
                            unset($found_records);
                            unset($assoc_records);
                            break;

                        case 'deps':
                            $dep_ids = array_keys($search_result['deps']);

                            //Убираем лишние результаты поиска по более, чем одному слову
                            $max_weight =   0;
                            foreach($dep_ids as $dep_id) {
                                if($search_result['deps'][$dep_id]    >   $max_weight) {
                                    $max_weight =   $search_result['deps'][$dep_id];
                                }
                            }
                            $max_dep_ids   =   array();
                            if($max_weight  <  1000000)  {
                                $max_dep_ids   =   $dep_ids;
                            }
                            else {
                                foreach($dep_ids as $dep_id) {
                                    if($search_result['deps'][$dep_id]   ==   $max_weight) {
                                        $max_dep_ids[] =   $dep_id;
                                    }
                                }
                            }
                            //

                            $found_records = Dep::find($max_dep_ids);
                            $assoc_records = array();
                            foreach ($found_records as $record) {
                                $assoc_records[$record->id] = $record;
                            }
                            if(count($assoc_records)) {
                                foreach ($dep_ids as $dep_id) {
                                    if(isset($assoc_records[$dep_id])) {
                                        $deps[] = $assoc_records[$dep_id];
                                    }
                                }
                            }
                            unset($found_records);
                            unset($assoc_records);
                            break;

                        case 'librazdels':
                            $lrazdel_ids = array_keys($search_result['librazdels']);
                            $found_records = LibRazdel::find($lrazdel_ids);
                            $assoc_records = array();
                            foreach ($found_records as $record) {
                                $assoc_records[$record->id] = $record;
                            }
                            if(count($assoc_records)) {
                                foreach ($lrazdel_ids as $lrazdel_id) {
                                    $razdels[] = $assoc_records[$lrazdel_id];
                                }
                            }
                            unset($found_records);
                            unset($assoc_records);
                            break;

                        case 'libbooks':
                            $lbook_ids = array_keys($search_result['libbooks']);
                            $found_records = LibBook::find($lbook_ids);
                            $assoc_records = array();
                            foreach ($found_records as $record) {
                                $assoc_records[$record->id] = $record;
                            }
                            if(count($assoc_records)) {
                                foreach ($lbook_ids as $lbook_id) {
                                    $books[] = $assoc_records[$lbook_id];
                                }
                            }
                            unset($found_records);
                            unset($assoc_records);
                            break;

                        case 'news':
                            $news_ids = array_keys($search_result['news']);
                            $found_records = News::find($news_ids);
                            $assoc_records = array();
                            foreach ($found_records as $record) {
                                $assoc_records[$record->id] = $record;
                            }
                            if(count($assoc_records)) {
                                foreach ($news_ids as $news_id) {
                                    $news[] = $assoc_records[$news_id];
                                }
                            }
                            unset($found_records);
                            unset($assoc_records);
                            break;

                    }
                }
            }
        }
        else {
            //
        }


        try {
            $user_id    =   0;
            if (Auth::check()) {
                $user_id    =   Auth::user()->id;
            }

            $sl = new Search_logs();
            $sl->user_id            =   $user_id;
            $sl->term               =   $phrase;
            $sl->total_res          =   count($news)    +   count($users)   +   count($docs)    +   count($books)   +   count($razdels) +   count($deps);
            $sl->section_results    =   json_encode(
                array(
                    'news'      =>  count($news),
                    'users'     =>  count($users),
                    'docs'      =>  count($docs),
                    'books'     =>  count($books),
                    'razdels'   =>  count($razdels),
                    'deps'      =>  count($deps)
                ));
            $sl->save();
        }
        catch(Exception $e) {

        }



        return view('search.all', [ "news"   =>  $news, "users"  =>  $users, "docs"  => $docs,
                                    "books"  => $books,   "razdels"   =>  $razdels,   "deps"  =>  $deps,
                                    "sections"  =>  $found_sections,
                                    "phrase"    =>  $phrase]);
    }

    private function getSearchResultsByWord($word,  $sections_to_find   =   array(),    $partials_to_find   =   array()) {
        $word_records = array();

        //отдельно обработали синонимы к слову и получили все записи, отсортированные по количеству совпадений отдельным словам
        $syns_records   =   $this->getSearchResultsBySyns(mb_strtoupper($word,  "UTF-8"));
        //синонимы закончены
        
        //базовая форма. Не у всех слов есть, поэтому, если форма не нашлась, то стоит искать по исходному термину
        $word=  trim(mb_strtoupper($word, "UTF-8"));
        $baseform   =   null;
        $forms = Morphy::getBaseForm($word);
        if($forms   !== false) {
            if (count($forms)) {
                $baseform = $forms[0];
            } 
        }
        //Продолжаем со словом
        $where  =   'baseterm';
        if(is_null($baseform)) {
            $where= 'term';
        }
        else {
            $word=  $baseform;
        }
        if(count($sections_to_find) &&  count($partials_to_find)) {
            $word_search_records = Terms::where($where, 'LIKE', $word)
            ->whereIn('section',  $sections_to_find)
            ->whereIn('partial',  $partials_to_find)
            ->get();
        }
        if(count($sections_to_find) &&  !count($partials_to_find)) {
            $word_search_records = Terms::where($where, 'LIKE', $word)
                ->whereIn('section',  $sections_to_find)
                ->get();
        }
        if(!count($sections_to_find) &&  count($partials_to_find)) {
            $word_search_records = Terms::where($where, 'LIKE', $word)
                ->whereIn('partial',  $partials_to_find)
                ->get();
        }
        if(!count($sections_to_find) &&  !count($partials_to_find)) {
            $word_search_records = Terms::where($where, 'LIKE', $word)->get();
        }
        //если у слова были синонимы, по ним что-то нашлось, а по самому слову нет - искать по подстроке не будем. Результат по слову = результат по синонимам
        if(count($syns_records) && !count($word_search_records)) {
            $word_records    =   $syns_records;
        }
        //если что-то нашли по слову
        if(count($word_search_records)) {
            $by_razdels = array();
            //Нам интересно искать по разделам
            foreach($word_search_records as $record) {
                $by_razdels[$record->section][] =   $record->record;
            }

            /*Нам не наплевать, что слово может встретиться для одной записи несколько раз. Например, для сотрудника в имени и отчестве
                (Иван Иванович) или в новости в заголовке и тексе (или в тексте несколько раз). Куда важнее по каким разным поисковым терминам
                запись вошла в выборку. Но при прочих равных надо учитывать количество вхождений*/
            foreach($by_razdels as $section =>  $records) {
                $by_razdels[$section]  =   array_count_values($records);
                arsort($by_razdels[$section]);
            }

            //если еще и синонимы были и что-то нашлось
            if(count($syns_records)) {
                $found_sections =   array_unique(array_merge(array_keys($syns_records), array_keys($by_razdels)));
                foreach($found_sections as $section) {
                    $section_records    =   array();
                    if(array_key_exists($section,   $by_razdels)) {
                        if(array_key_exists($section,   $syns_records)) {
                            $records=   array_unique(array_merge(array_keys($syns_records[$section]), array_keys($by_razdels[$section])));
                            foreach($records as $record) {
                                $total_value = 0;
                                if(array_key_exists($record,   $by_razdels[$section])) {
                                    $total_value += $by_razdels[$section][$record];
                                }
                                if(array_key_exists($record,   $syns_records[$section])) {
                                    $total_value += $syns_records[$section][$record];
                                }
                                $section_records[$record]    = $total_value;
                            }
                        }
                        else {
                            $records=   array_keys($by_razdels[$section]);
                            foreach($records as $record) {
                                $section_records[$record]    = $by_razdels[$section][$record];
                            }
                        }
                    }
                    else {
                        if(array_key_exists($section,   $syns_records)) {
                            $records=   array_keys($syns_records[$section]);
                            foreach($records as $record) {
                                $section_records[$record]    = $syns_records[$section][$record];
                            }
                        }
                    }
                    arsort($section_records);
                    $word_records[$section]    =    $section_records;
                }
            }
            else {
                $word_records   =   $by_razdels;
            }
        }
        return $word_records;
    }

    //Функция поиска результатов по синониму к слову
    private function getSearchResultsBySyns($word) {
        $syns   =   Syns::where('term','LIKE',  $word)->get();
        $syns_records = array();
        if(count($syns)) {
            //Сюда будем складывать обработанные результаты по каждой найденной фразе-синониму
            $parsed_syn_phrases =   0;
            //сейчас будет работать только для последнего синонима
            foreach($syns as $syn) {
                //Сюда будем складывать обработанные результаты по каждому слову из фразы-синонима
                $syn_records = array();
                $syn_words = explode(" ", $syn->syn);

                /*переменная, в которой храним количество проверенных слов во фразе-синониме, чтобы потом сравнивать с количеством вхождений найденных записей
                    в итоговую выборку. Цель - вычислить максимальное соответствие заданной фразе*/
                $parsed_syn_words = 0;
                foreach($syn_words as $syn_word) {
                    $syn_word = preg_replace("/[^0-9A-zА-яЁё]/iu", "", $syn_word);
                    //с цифрами ничего делать не надо
                    if(mb_strlen($syn_word, "UTF-8") >= 3) {
                        $forms = Morphy::getBaseForm(mb_strtoupper($syn_word, "UTF-8"));
                        if($forms !==   false) {
                            if (count($forms)) {
                                $syn_word = $forms[0];
                            } else {
                                $syn_word = mb_strtoupper($syn_word, "UTF-8");
                            }
                        }
                        else {
                            $syn_word = mb_strtoupper($syn_word, "UTF-8");
                        }

                        $syn_records[]  =  Terms::where('baseterm', 'LIKE', $syn_word)->get();
                        $parsed_syn_words   ++;
                    }

                }

                $by_razdels = array();
                //Ищем по каждому разделу запись, которая вошла в выборку по максимальному количеству слов
                if($parsed_syn_words > 0) {
                    //по каждому отработанному слову проверяем найденные записи в индексе
                    //для начала нужно понять, какие секции нашлись
                    $found_sections =   array();
                    for($i = 0; $i < $parsed_syn_words; $i++) {
                        foreach ($syn_records[$i] as $record) {
                            $found_sections[] = $record->section;
                        }
                    }
                    $found_sections    =   array_unique($found_sections);

                    for($i = 0; $i < $parsed_syn_words; $i++) {
                        foreach($found_sections as $section) {
                            $by_razdels[$section][$i]   =   array();
                            foreach ($syn_records[$i] as $record) {
                                if($section==   $record->section) {
                                    $by_razdels[$section][$i][] =   $record->record;
                                }
                            }
                            /*Нам не наплевать, что слово может встретиться для одной записи несколько раз. Например, для сотрудника в имени и отчестве
                            (Иван Иванович) или в новости в заголовке и тексе (или в тексте несколько раз). Куда важнее по каким разным поисковым терминам
                            запись вошла в выборку. Но при прочих равных надо учитывать количество вхождений*/
                            $by_razdels[$section][$i]   =   array_count_values($by_razdels[$section][$i]);
                        }
                    }


                    //Теперь считаем сколько раз запись вошла в результат-секцию по каждому слову
                    foreach($by_razdels as $section =>  $by_word) {
                        if(!isset($syns_records[$section])) {
                            $syns_records[$section] = array();
                        }
                        for($i = 0; $i < $parsed_syn_words; $i++) {
                            $syns_records[$section] = array_merge($syns_records[$section],    array_keys($by_razdels[$section][$i]));
                        }
                        $syns_records[$section] =   array_count_values($syns_records[$section]);
                        //теперь учтем более важное количество вхождения записи в результат по слову + количество вхождений записи в рамках одного слова
                        foreach($syns_records[$section] as $record_id   =>  $numcounts) {
                            $syns_records[$section][$record_id] =   $numcounts + 1000000;
                            for($i = 0; $i < $parsed_syn_words; $i++) {
                                if(array_key_exists($record_id, $by_razdels[$section][$i])) {
                                    $syns_records[$section][$record_id] =   $syns_records[$section][$record_id] +$by_razdels[$section][$i][$record_id];
                                }
                            }
                        }
                        arsort($syns_records[$section]);
                    }
                }
            }

        }

        return $syns_records;
    }

    /*Функция поиска по атрибутам
    В принципе просто набор отдельных поисковых функций.
    Взвешивание идет в начале по пересечению списков, потом по объединению. А уже потом по внутренней сортировке*/
    public function directory(Request $request) {

        $users = array();
        $found_sections =   array("users");
        $parsed_words   =   0;
        //кусок поиска по фамилии/имени/отчеству
        $allname = trim($request->input('allname'));
        $allname = mb_substr($allname, 0, 100);

        //Орфография, опечатки
        $dict   = pspell_new ( 'ru', '', '', "utf-8", PSPELL_BAD_SPELLERS);
        //Раскладка
        $corrector = new Text_LangCorrect();

        if(mb_strlen($allname) >= 3) {
            $allname = preg_replace("/[^0-9A-zА-яЁё]/ius", " ", $allname);
            $words = explode(" ", $allname);
            //итоговый массив со взвешенным списком
            $words_records = array();
            foreach ($words as $word) {
                $word = trim($word);
                if (mb_strlen($word, "UTF-8")) {

                    //в начале пытаемся поработать с раскладкой, потому что она круто отрабатывает всякую чушь, которую вводят на английской раскладке, вводя русские (там могут быть знаки преминания)
                    $oldword = $word;
                    $word = $corrector->parse($word, $corrector::KEYBOARD_LAYOUT);
                    //вот теперь можно убрать лишнее
                    $word = preg_replace("/[^0-9A-zА-яЁё]/ius", "", $word);
                    //с цифрами ничего делать не надо
                    if (mb_strlen($word) >= 3) {
                        /*Если человек вводит какое-то разумное слово, то если:
                            - он ошибся в транслитерации и еще допустил опечатку, то маловероятно, что выйдет
                            - если он ошибся в чем-то одном, то последовательное применение обоих методов сначала в одном порядке, потом в другом, дадут результат*/
                        //слово есть в словаре
                        $total_found_by_word = 0;
                        if (pspell_check($dict, $word)) {
                            $res = $this->getSearchResultsByWord($word, array("users"),  array("fname",  "lname",    "mname"));
                            $words_records[] = $res;
                            $total_found_by_word = count($res);
                            unset($res);
                        } //Слово не нашлось в словаре
                        else {
                            $oldword = preg_replace("/[^0-9A-zА-яЁё]/ius", "", $oldword);
                            $res = $this->getSearchResultsByWord($oldword, array("users"),  array("fname",  "lname",    "mname"));
                            $words_records[] = $res;
                            $total_found_by_word = count($res);
                            unset($res);
                            if(!$total_found_by_word) {
                                //пробуем в начале советы (опечатки, если было на русском)
                                $suggest = pspell_suggest($dict, $word);
                                //берем только первый вариант, остальные уже не то
                                if (count($suggest)) {
                                    $word = $suggest[0];
                                    $res = $this->getSearchResultsByWord($word, array("users"),  array("fname",  "lname",    "mname"));
                                    $words_records[] = $res;
                                    $total_found_by_word = count($res);
                                    unset($res);
                                }
                            }
                        }
                    }
                }
            }
            
            $search_result = array();
            $parsed_words   =   count($words_records);
            //Ищем по каждому разделу запись, которая вошла в выборку по максимальному количеству слов
            if($parsed_words > 0) {
                //по каждому отработанному слову проверяем найденные разделы
                for ($i = 0; $i < $parsed_words; $i++) {
                    $found_sections = array_merge($found_sections, array_keys($words_records[$i]));
                }

                //все уникальные найденные разделы
                $found_sections = array_unique($found_sections);
                foreach ($found_sections as $section) {
                    $search_result[$section] = array();
                    for ($i = 0; $i < $parsed_words; $i++) {
                        if (isset($words_records[$i][$section])) {
                            foreach ($words_records[$i][$section] as $record => $total) {
                                if (array_key_exists($record, $search_result[$section])) {
                                    $search_result[$section][$record] = $search_result[$section][$record] + 1000000;
                                } else {
                                    $search_result[$section][$record] = $total;
                                }
                            }

                        }
                    }
                    arsort($search_result[$section]);
                }

                $user_ids = array_keys($search_result['users']);
                //Убираем лишние результаты поиска по более, чем одному слову
                $max_weight =   0;
                foreach($user_ids as $user_id) {
                    if($search_result['users'][$user_id]    >   $max_weight) {
                        $max_weight =   $search_result['users'][$user_id];
                    }
                }
                $max_user_ids   =   array();
                //там мы разбиваем, когда одно слово встречается в разных секциях целого и когда два слово входят в одну секцию
                if($max_weight  <  1000000)  {
                    $max_user_ids   =   $user_ids;
                }
                else {
                    foreach($user_ids as $user_id) {
                        if($search_result['users'][$user_id]   ==   $max_weight) {
                            $max_user_ids[] =   $user_id;
                        }
                    }
                }

                $found_records = User::find($max_user_ids);
                $assoc_records = array();
                foreach ($found_records as $record) {
                    $assoc_records[$record->id] = $record;
                }
                foreach ($user_ids as $user_id) {
                    if(isset($assoc_records[$user_id])) {
                        $users[] = array("record"   =>  $assoc_records[$user_id],   "weight"    =>  $search_result['users'][$user_id]);
                    }
                }
                unset($found_records);
                unset($assoc_records);
            }
        }


        //кусок поиска по комнате
        $room = trim($request->input('room'));
        $room = mb_substr($room, 0, 100);
        //$room   =   (int)$room;
        // есть комнаты с буквами в наименовании
        $users_by_room  =   array();
        $user_ids   =   array();
        if($room) {
            $room = preg_replace("/[^0-9A-zА-яЁё]/ius", " ", $room);
            $words = explode(" ", $room);
            //итоговый массив со взвешенным списком
            $words_records = array();
            foreach ($words as $word) {
                $word = trim($word);
                if (mb_strlen($word, "UTF-8")) {
                    $word = preg_replace("/[^0-9A-zА-яЁё]/ius", "", $word);
                    if (mb_strlen($word) >= 3) {
                        /*Если человек вводит какое-то разумное слово, то если:
                            - он ошибся в транслитерации и еще допустил опечатку, то маловероятно, что выйдет
                            - если он ошибся в чем-то одном, то последовательное применение обоих методов сначала в одном порядке, потом в другом, дадут результат*/
                        //слово есть в словаре
                        $total_found_by_word = 0;
                        
                        $res = $this->getSearchResultsByWord($word, array("users"),  array("room"));
                        $words_records[] = $res;
                        $total_found_by_word = count($res);
                        unset($res);
                    }
                }
            }
            
            $search_result = array();
            $parsed_words   =   count($words_records);
            //Ищем по каждому разделу запись, которая вошла в выборку по максимальному количеству слов
            if($parsed_words > 0) {
                //по каждому отработанному слову проверяем найденные разделы
                for ($i = 0; $i < $parsed_words; $i++) {
                    $found_sections = array_merge($found_sections, array_keys($words_records[$i]));
                }

                //все уникальные найденные разделы
                $found_sections = array_unique($found_sections);
                foreach ($found_sections as $section) {
                    $search_result[$section] = array();
                    for ($i = 0; $i < $parsed_words; $i++) {
                        if (isset($words_records[$i][$section])) {
                            foreach ($words_records[$i][$section] as $record => $total) {
                                if (array_key_exists($record, $search_result[$section])) {
                                    $search_result[$section][$record] = $search_result[$section][$record] + 1000000;
                                } else {
                                    $search_result[$section][$record] = $total;
                                }
                            }

                        }
                    }
                    arsort($search_result[$section]);
                }

                $user_ids = array_keys($search_result['users']);
                //Убираем лишние результаты поиска по более, чем одному слову
                $max_weight =   0;
                foreach($user_ids as $user_id) {
                    if($search_result['users'][$user_id]    >   $max_weight) {
                        $max_weight =   $search_result['users'][$user_id];
                    }
                }
                $max_user_ids   =   array();
                //там мы разбиваем, когда одно слово встречается в разных секциях целого и когда два слово входят в одну секцию
                if($max_weight  <  1000000)  {
                    $max_user_ids   =   $user_ids;
                }
                else {
                    foreach($user_ids as $user_id) {
                        if($search_result['users'][$user_id]   ==   $max_weight) {
                            $max_user_ids[] =   $user_id;
                        }
                    }
                }

                $found_records = User::find($max_user_ids);
                $assoc_records = array();
                foreach ($found_records as $record) {
                    $assoc_records[$record->id] = $record;
                }
                foreach ($user_ids as $user_id) {
                    if(isset($assoc_records[$user_id])) {
                        $users_by_room[] = array("record"   =>  $assoc_records[$user_id],   "weight"    =>  $search_result['users'][$user_id]);
                    }
                }
                unset($found_records);
                unset($assoc_records);
            }
            unset($room_records);
            unset($user_ids);
        }
        //кусок поиска по email
        $email = trim($request->input('email'));
        $email = mb_substr($email, 0, 100);
        $users_by_email  =   array();
        if($email) {
            $email_records   =   User::where('email', 'LIKE',    '%'  .   $email   .   '%')->orWhere('email_secondary', 'LIKE',    '%'  .   $email   .   '%')->orderBy("lname")->orderBy("fname")->orderBy("mname")->get();
            $users_by_email  =   $email_records;
            unset($email_records);
        }

        //кусок поиска по телефону
        $phone = trim($request->input('phone'));
        $phone = mb_substr($phone, 0, 100);
        $phone   =   (int)$phone;
        $users_by_phone  =   array();
        $user_ids   =   array();
        if($phone) {
            //ищем по всем телефонам
            $phone_records   =   User::where('phone', '=',    $phone)->orWhere('ip_phone',  '=',    $phone)
                    ->orWhere("city_phone",    'LIKE', '%' .   $phone. '%')
                    ->orWhere("mobile_phone",    'LIKE', '%' .   $phone. '%')->orderBy("lname")->orderBy("fname")->orderBy("mname")->get();
            /*foreach($phone_records   as $record) {
                $user_ids[] =   $record->id;
            }
            $phone_sim_records   =   User::where('phone', 'LIKE',    '%'  .   $phone   .   '%')
                ->orWhere("city_phone",    'LIKE', '%' .   $phone. '%')
                ->orWhere("mobile_phone",    'LIKE', '%' .   $phone. '%')
                ->get();
            foreach($phone_sim_records   as $record) {
                if(!in_array($record->id,   $user_ids)) {
                    $user_ids[] =   $record->id;
                    $phone_records->push($record);
                }
            }*/

            $users_by_phone  =   $phone_records;
            unset($phone_records);
            unset($user_ids);
        }

        //кусок поиска по должности
        $worktitle              = trim($request->input('worktitle'));
        $worktitle              = mb_substr($worktitle, 0, 100);
        $users_by_worktitle     =   array();
        //Орфография, опечатки
        $dict   = pspell_new ( 'ru', '', '', "utf-8", PSPELL_FAST);
        //Раскладка
        $corrector = new Text_LangCorrect();

        if(mb_strlen($worktitle) >= 3) {
            $worktitle = preg_replace("/[^0-9A-zА-яЁё]/ius", " ", $worktitle);
            $words = explode(" ", $worktitle);
            //итоговый массив со взвешенным списком
            $words_records = array();
            foreach ($words as $word) {
                $word = trim($word);
                if (mb_strlen($word, "UTF-8")) {

                    //в начале пытаемся поработать с раскладкой, потому что она круто отрабатывает всякую чушь, которую вводят на английской раскладке, вводя русские (там могут быть знаки преминания)
                    $oldword = $word;
                    $word = $corrector->parse($word, $corrector::KEYBOARD_LAYOUT);
                    //вот теперь можно убрать лишнее
                    $word = preg_replace("/[^0-9A-zА-яЁё]/ius", "", $word);
                    //с цифрами ничего делать не надо
                    if (mb_strlen($word) >= 3) {
                        /*Если человек вводит какое-то разумное слово, то если:
                            - он ошибся в транслитерации и еще допустил опечатку, то маловероятно, что выйдет
                            - если он ошибся в чем-то одном, то последовательное применение обоих методов сначала в одном порядке, потом в другом, дадут результат*/
                        //слово есть в словаре
                        $total_found_by_word = 0;

                        if (pspell_check($dict, $word)) {
                            $res = $this->getSearchResultsByWord($word, array("users"), array("work"));
                            $words_records[] = $res;
                            $total_found_by_word = count($res);
                            unset($res);
                        } //Слово не нашлось в словаре
                        else {
                            $oldword = preg_replace("/[^0-9A-zА-яЁё]/ius", "", $oldword);
                            $res = $this->getSearchResultsByWord($oldword, array("users"), array("work"));
                            $words_records[] = $res;
                            $total_found_by_word = count($res);
                            unset($res);

                            if(!$total_found_by_word) {
                                //пробуем в начале советы (опечатки, если было на русском)
                                $suggest = pspell_suggest($dict, $word);
                                //берем только первый вариант, остальные уже не то
                                if (count($suggest)) {
                                    $word = $suggest[0];
                                    //var_dump($word);
                                    $res = $this->getSearchResultsByWord($word, array("users"), array("work"));
                                    $words_records[] = $res;
                                    $total_found_by_word = count($res);
                                    unset($res);
                                }
                            }
                        }
                    }
                }

                $parsed_words ++;
            }

            $search_result = array();
            $parsed_words   =   count($words_records);
            //Ищем по каждому разделу запись, которая вошла в выборку по максимальному количеству слов
            if($parsed_words > 0) {
                //по каждому отработанному слову проверяем найденные разделы
                for ($i = 0; $i < $parsed_words; $i++) {
                    $found_sections = array_merge($found_sections, array_keys($words_records[$i]));
                }
                //все уникальные найденные разделы
                $found_sections = array_unique($found_sections);
                foreach ($found_sections as $section) {
                    $search_result[$section] = array();
                    for ($i = 0; $i < $parsed_words; $i++) {
                        if (isset($words_records[$i][$section])) {
                            foreach ($words_records[$i][$section] as $record => $total) {
                                if (array_key_exists($record, $search_result[$section])) {
                                    $search_result[$section][$record] = $search_result[$section][$record] + 1000000;
                                } else {
                                    $search_result[$section][$record] = $total;
                                }
                            }

                        }
                    }
                    arsort($search_result[$section]);
                }

                $user_ids = array_keys($search_result['users']);

                //Убираем лишние результаты поиска по более, чем одному слову
                $max_weight =   0;
                foreach($user_ids as $user_id) {
                    if($search_result['users'][$user_id]    >   $max_weight) {
                        $max_weight =   $search_result['users'][$user_id];
                    }
                }
                $max_user_ids   =   array();
                //там мы разбиваем, когда одно слово встречается в разных секциях целого и когда два слово входят в одну секцию
                if($max_weight  <  1000000)  {
                    $max_user_ids   =   $user_ids;
                }
                else {
                    foreach($user_ids as $user_id) {
                        if($search_result['users'][$user_id]   ==   $max_weight) {
                            $max_user_ids[] =   $user_id;
                        }
                    }
                }

                $found_records = User::whereIn('id',    $max_user_ids)->orderBy('lname')->orderBy('fname')->orderBy('mname')->get();
                foreach ($found_records as $record) {
                    $users_by_worktitle[] = array("record"  =>  $record,   "weight"    =>  $search_result['users'][$user_id]);
                }
                unset($found_records);
            }
        }

        //кусок поиска по департаменту
        $dep              = trim($request->input('dep'));
        $dep              = mb_substr($dep, 0, 100);
        $users_by_dep     =   array();
        //Орфография, опечатки
        $dict   = pspell_new ( 'ru', '', '', "utf-8", PSPELL_FAST);
        //Раскладка
        $corrector = new Text_LangCorrect();

        if(mb_strlen($dep) >= 3) {
            $dep = preg_replace("/[^0-9A-zА-яЁё]/ius", " ", $dep);
            $words = explode(" ", $dep);
            //итоговый массив со взвешенным списком
            $words_records = array();
            foreach ($words as $word) {
                $word = trim($word);
                if (mb_strlen($word, "UTF-8")) {

                    //в начале пытаемся поработать с раскладкой, потому что она круто отрабатывает всякую чушь, которую вводят на английской раскладке, вводя русские (там могут быть знаки преминания)
                    $oldword = $word;
                    $word = $corrector->parse($word, $corrector::KEYBOARD_LAYOUT);
                    //вот теперь можно убрать лишнее
                    $word = preg_replace("/[^0-9A-zА-яЁё]/ius", "", $word);
                    //с цифрами ничего делать не надо
                    if (mb_strlen($word) >= 3) {
                        /*Если человек вводит какое-то разумное слово, то если:
                            - он ошибся в транслитерации и еще допустил опечатку, то маловероятно, что выйдет
                            - если он ошибся в чем-то одном, то последовательное применение обоих методов сначала в одном порядке, потом в другом, дадут результат*/
                        //слово есть в словаре
                        $total_found_by_word = 0;

                        if (pspell_check($dict, $word)) {
                            $res = $this->getSearchResultsByWord($word, array("users"), array("work"));
                            $words_records[] = $res;
                            $total_found_by_word = count($res);
                            unset($res);
                        } //Слово не нашлось в словаре
                        else {
                            $oldword = preg_replace("/[^0-9A-zА-яЁё]/ius", "", $oldword);
                            $res = $this->getSearchResultsByWord($oldword, array("users"), array("work"));
                            $words_records[] = $res;
                            $total_found_by_word = count($res);
                            unset($res);

                            if(!$total_found_by_word) {
                                //пробуем в начале советы (опечатки, если было на русском)
                                $suggest = pspell_suggest($dict, $word);
                                //берем только первый вариант, остальные уже не то
                                if (count($suggest)) {
                                    $word = $suggest[0];
                                    //var_dump($word);
                                    $res = $this->getSearchResultsByWord($word, array("users"), array("work"));
                                    $words_records[] = $res;
                                    $total_found_by_word = count($res);
                                    unset($res);
                                }
                            }
                        }
                    }
                }

                $parsed_words ++;
            }

            $search_result = array();
            $parsed_words   =   count($words_records);
            //Ищем по каждому разделу запись, которая вошла в выборку по максимальному количеству слов
            if($parsed_words > 0) {
                //по каждому отработанному слову проверяем найденные разделы
                for ($i = 0; $i < $parsed_words; $i++) {
                    $found_sections = array_merge($found_sections, array_keys($words_records[$i]));
                }
                //все уникальные найденные разделы
                $found_sections = array_unique($found_sections);
                foreach ($found_sections as $section) {
                    $search_result[$section] = array();
                    for ($i = 0; $i < $parsed_words; $i++) {
                        if (isset($words_records[$i][$section])) {
                            foreach ($words_records[$i][$section] as $record => $total) {
                                if (array_key_exists($record, $search_result[$section])) {
                                    $search_result[$section][$record] = $search_result[$section][$record] + 1000000;
                                } else {
                                    $search_result[$section][$record] = $total;
                                }
                            }

                        }
                    }
                    arsort($search_result[$section]);
                }

                $user_ids = array_keys($search_result['users']);
                //Убираем лишние результаты поиска по более, чем одному слову
                $max_weight =   0;
                foreach($user_ids as $user_id) {
                    if($search_result['users'][$user_id]    >   $max_weight) {
                        $max_weight =   $search_result['users'][$user_id];
                    }
                }
                $max_user_ids   =   array();
                //там мы разбиваем, когда одно слово встречается в разных секциях целого и когда два слово входят в одну секцию
                if($max_weight  <  1000000)  {
                    $max_user_ids   =   $user_ids;
                }
                else {
                    foreach($user_ids as $user_id) {
                        if($search_result['users'][$user_id]   ==   $max_weight) {
                            $max_user_ids[] =   $user_id;
                        }
                    }
                }

                $found_records = User::whereIn('id',    $max_user_ids)->orderBy('lname')->orderBy('fname')->orderBy('mname')->get();
                foreach ($found_records as $record) {
                    $users_by_dep[] = array("record"  =>  $record,   "weight"    =>  $search_result['users'][$user_id]);
                }
                unset($found_records);
            }
        }


        //кусок поиска по дню рождения
        $bdates = trim($request->input('birthdate'));
        $users_by_birthday  =   array();
        $birthday_records   =   array();
        $user_ids   =   array();

        //надо посмотреть, период это или точная дата
        $bdates =   explode("-",    $bdates);
        $startDay   =   $startMonth =   $startYear  =   $endDay =   $endMonth   =   null;
        if(isset($bdates[0])    &&  isset($bdates[1])   &&  trim($bdates[0])  &&  trim($bdates[1])) {
            $year   =   date("Y");
            //Ситуация, когда вводят руками
            if(mb_strrpos(trim($bdates[0]), ".",  0, "UTF-8") === false) {
                $startDate  =   $this->getDatePartsFromString($bdates[0]);
                list($startDay, $startMonth)    =   $startDate;
                if(is_null($startDay)) {
                    $startDay   =   "01";
                }
            }
            else {
                $startDate  =  $this->getDatePartsFromFormattedString($bdates[0]);
                list($startDay, $startMonth)    =   $startDate;
            }
            if(mb_strrpos(trim($bdates[1]), ".",  0, "UTF-8") === false) {
                $endDate  =   $this->getDatePartsFromString($bdates[1]);
                list($endDay, $endMonth)    =   $endDate;
                if(is_null($endDay)) {
                    if(is_null($endMonth)) {
                        $endDay     =   "31";
                    }
                    else {
                        $endDay =   date("t",   strtotime("01." .   $endMonth .   "." .   $year));
                    }
                }
            }
            else {
                $endDate  =  $this->getDatePartsFromFormattedString($bdates[1]);
                list($endDay, $endMonth)    =   $endDate;
            }

            $searchDate1 =   $startDay  .   "." .   $startMonth  .   "." .   $year;
            $searchDate2 =   $endDay  .   "." .   $endMonth  .   "." .   $year;

            //При переходе через "Новый год работает неверно, нужно условие
            $dm =   date("m", strtotime($searchDate1));
            $dm1 =   date("m", strtotime($searchDate2));
            if($dm1 <   $dm) {
                $dt     = date("m-d", strtotime($searchDate1));
                $dt1    =   date("m-d", strtotime($searchDate2));

                $birthday_records = User::select("users.id", "users.name", "users.avatar", "users.avatar_round", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "users.mobile_phone", "users.in_office", "deps_peoples.work_title")
                    ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                    ->whereNull('deps_peoples.deleted_at')
                    ->whereRaw("DATE_FORMAT(birthday, '%m-%d') >=  '$dt'")
                    ->orWhereRaw("DATE_FORMAT(birthday, '%m-%d') <=  '$dt1'")->orderByRaw("MONTH(birthday) DESC")->orderByRaw("DAY(birthday) ASC")
                    ->orderBy("users.lname")->orderBy("users.fname")->orderBy("users.mname")->get();
            }
            else {
                $dt = date("m-d", strtotime($searchDate1));
                $dt1 = date("m-d", strtotime($searchDate2));

                $birthday_records = User::select("users.id", "users.name", "users.avatar", "users.avatar_round", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "users.mobile_phone", "users.in_office", "deps_peoples.work_title")
                    ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                    ->whereNull('deps_peoples.deleted_at')
                    ->whereRaw("DATE_FORMAT(birthday,  '%m-%d')    >=  '$dt'")
                    ->whereRaw("DATE_FORMAT(birthday,  '%m-%d')    <=  '$dt1'")->orderByRaw("MONTH(birthday) ASC")->orderByRaw("DAY(birthday) ASC")
                    ->orderBy("users.lname")->orderBy("users.fname")->orderBy("users.mname")->get();
            }
        }
        elseif (isset($bdates[0])    &&  trim($bdates[0])) {
            $year       =   date("Y");
            //Ситуация, когда вводят руками
            if(mb_strrpos(trim($bdates[0]), ".",   0,  "UTF-8")===false) {
                $startDate  =   $this->getDatePartsFromString($bdates[0]);
                list($startDay, $startMonth)    =   $startDate;
                if(is_null($startDay)) {
                    $startDay   =   "01";
                    if(is_null($startMonth)) {
                        $endDay     =   "31";
                    }
                    else {
                        $endDay =   date("t",   strtotime("01." .   $startMonth .   "." .   $year));
                    }
                }
            }
            else {
                $startDate  =  $this->getDatePartsFromFormattedString($bdates[0]);
                list($startDay, $startMonth,    $startYear)    =   $startDate;
            }

            //особая история поиска строго по месяцу одним словом
            if(!is_null($endDay)) {
                $searchDate1 =   $startDay  .   "." .   $startMonth  .   "." .   $year;
                $searchDate2 =   $endDay  .   "." .   $startMonth  .   "." .   $year;

                $dt     = date("m-d", strtotime($searchDate1));
                $dt1    =   date("m-d", strtotime($searchDate2));


                $birthday_records = User::select("users.id", "users.name", "users.avatar", "users.avatar_round", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "users.mobile_phone", "users.in_office", "deps_peoples.work_title")
                    ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                    ->whereNull('deps_peoples.deleted_at')
                    ->whereRaw("DATE_FORMAT(birthday, '%m-%d') >=  '$dt'")
                    ->WhereRaw("DATE_FORMAT(birthday, '%m-%d') <=  '$dt1'")->orderByRaw("MONTH(birthday) ASC")->orderByRaw("DAY(birthday) ASC")
                    ->orderBy("users.lname")->orderBy("users.fname")->orderBy("users.mname")->get();
            }
            else {
                if(!is_null($startYear)) {
                    $searchDate = $startDay  .   "." .   $startMonth  .   "." .   $startYear;
                    $dt = date("Y-m-d", strtotime($searchDate));
                    $birthday_records = User::select("users.id", "users.name", "users.avatar", "users.avatar_round", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "users.mobile_phone", "users.in_office", "deps_peoples.work_title")
                        ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                        ->whereNull('deps_peoples.deleted_at')
                        ->where(DB::raw("MONTH(birthday)"), '=', DB::raw("MONTH('$dt')"))->where(DB::raw("DAY(birthday)"), '=', DB::raw("DAY('$dt')"))
                        ->where(DB::raw("YEAR(birthday)"), '=', DB::raw("YEAR('$dt')"))->orderByRaw("MONTH(birthday) ASC")->orderByRaw("DAY(birthday) ASC")
                        ->orderBy("users.lname")->orderBy("users.fname")->orderBy("users.mname")->get();
                }
                else {
                    $searchDate = $startDay  .   "." .   $startMonth  .   "." .   $year;
                    $dt = date("Y-m-d", strtotime($searchDate));
                    $birthday_records = User::select("users.id", "users.name", "users.avatar", "users.avatar_round", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "users.mobile_phone", "users.in_office", "deps_peoples.work_title")
                        ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                        ->whereNull('deps_peoples.deleted_at')
                        ->where(DB::raw("MONTH(birthday)"), '=', DB::raw("MONTH('$dt')"))->where(DB::raw("DAY(birthday)"), '=', DB::raw("DAY('$dt')"))->orderByRaw("MONTH(birthday) ASC")->orderByRaw("DAY(birthday) ASC")
                        ->orderBy("users.lname")->orderBy("users.fname")->orderBy("users.mname")->get();
                }
            }
        }

        $users_by_birthday  =   $birthday_records;

        //var_dump(count($users_by_birthday));
        $total_attrs_in_search  =   0;
        $all_found_records  =   array();

        if(count($users)) {
            $total_attrs_in_search  ++;
        }
        if(count($users_by_dep)) {
            $total_attrs_in_search  ++;
        }
        if(count($users_by_worktitle)) {
            $total_attrs_in_search  ++;
        }
        if(count($users_by_room)) {
            $total_attrs_in_search  ++;
        }
        if(count($users_by_phone)) {
            $total_attrs_in_search  ++;
        }
        if(count($users_by_email)) {
            $total_attrs_in_search  ++;
        }
        if(count($users_by_birthday)) {
            $total_attrs_in_search  ++;
        }

        foreach($users as $user) {
            $record=    $user["record"];
            $weight=    $user["weight"];
            if(array_key_exists($record->id,  $all_found_records)) {
                //$all_found_records[$record->id]   =   $all_found_records[$record->id]   +   $weight;
                $all_found_records[$record->id] =   $all_found_records[$record->id]   + 1;
            }
            else {
                //$all_found_records[$record->id]   =   $weight;
                $all_found_records[$record->id] =   1;
            }
        }
        foreach($users_by_worktitle as $user) {
            $record=    $user["record"];
            $weight=    $user["weight"];
            if(array_key_exists($record->id,  $all_found_records)) {
                //$all_found_records[$record->id]   =   $all_found_records[$record->id]   +   $weight;
                $all_found_records[$record->id] =   $all_found_records[$record->id]   + 1;
            }
            else {
                //$all_found_records[$record->id]   =   $weight;
                $all_found_records[$record->id] =   1;
            }
        }
        foreach($users_by_dep as $user) {
            $record=    $user["record"];
            $weight=    $user["weight"];
            if(array_key_exists($record->id,  $all_found_records)) {
                //$all_found_records[$record->id]   =   $all_found_records[$record->id]   +   $weight;
                $all_found_records[$record->id] =   $all_found_records[$record->id]   + 1;
            }
            else {
                //$all_found_records[$record->id]   =   $weight;
                $all_found_records[$record->id] =   1;
            }
        }
        foreach($users_by_room as $user) {
            $record=    $user["record"];
            $weight=    $user["weight"];
            if(array_key_exists($record->id,  $all_found_records)) {
                //$all_found_records[$record->id]   =   $all_found_records[$record->id]   +   $weight;
                $all_found_records[$record->id] =   $all_found_records[$record->id]   + 1;
            }
            else {
                //$all_found_records[$record->id]   =   $weight;
                $all_found_records[$record->id] =   1;
            }
        }
        $index  =   count($users_by_birthday);
        foreach($users_by_birthday as $user) {
            if(array_key_exists($user->id,  $all_found_records)) {
                //$all_found_records[$user->id]   =   $all_found_records[$user->id]   +   $index;
                $all_found_records[$user->id] =   $all_found_records[$user->id]   + 1;
            }
            else {
                //$all_found_records[$user->id]   =   $index;
                $all_found_records[$user->id] =   1;
            }
            $index  --;
        }

        $index  =   count($users_by_phone);
        foreach($users_by_phone as $user) {
            if(array_key_exists($user->id,  $all_found_records)) {
                //$all_found_records[$user->id]   =   $all_found_records[$user->id]   +   $index;
                $all_found_records[$user->id] =   $all_found_records[$user->id]   + 1;
            }
            else {
                //$all_found_records[$user->id]   =   $index;
                $all_found_records[$user->id] =   1;
            }

            $index  --;
        }

        $index  =   count($users_by_email);
        foreach($users_by_email as $user) {
            if(array_key_exists($user->id,  $all_found_records)) {
                //$all_found_records[$user->id]   =   $all_found_records[$user->id]   +   $index;
                $all_found_records[$user->id] =   $all_found_records[$user->id]   + 1;
            }
            else {
                //$all_found_records[$user->id]   =   $index;
                $all_found_records[$user->id] =   1;
            }

            $index --;
        }

        //arsort($all_found_records);

        $user_ids = array_keys($all_found_records);
        //Убираем лишние результаты поиска по более, чем одному слову
        $max_weight =   0;
        foreach($user_ids as $user_id) {
            if($all_found_records[$user_id]    >   $max_weight) {
                $max_weight =   $all_found_records[$user_id];
            }
        }
        $max_user_ids   =   array();
        if($max_weight  ==   $total_attrs_in_search) {
            foreach($user_ids as $user_id) {
                if($all_found_records[$user_id]   ==   $max_weight) {
                    $max_user_ids[] =   $user_id;
                }
            }
        }


        unset($users_by_email);
        unset($users_by_phone);
        unset($users_by_room);
        unset($users_by_birthday);
        unset($users);
        unset($users_by_worktitle);
        unset($users_by_dep);

        //var_dump($all_found_records);
        $users  =   array();

        //$user_ids = array_keys($all_found_records);
        $found_records = User::select("users.id", "users.name", "users.avatar", "users.avatar_round", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "users.ip_phone", "users.mobile_phone", "users.in_office", "users.birthday", "deps_peoples.work_title")
            ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
            ->whereNull('deps_peoples.deleted_at')
            ->whereIn('users.id', $max_user_ids)->get();
        $assoc_records = array();
        foreach ($found_records as $record) {
            $assoc_records[$record->id] = $record;
        }
        if(count($assoc_records)) {
            foreach ($max_user_ids as $user_id) {
                $users[] = $assoc_records[$user_id];
            }
        }
        unset($found_records);
        unset($assoc_records);

        $phrase =   "";
        if($allname)    {
            $phrase =   "ФИО: " .   $allname;
        }
        if($phone)    {
            if($phrase) {
                $phrase    .=  ", ";
            }
            $phrase .=   "телефон: " .   $phone;
        }
        if($room)    {
            if($phrase) {
                $phrase    .=  ", ";
            }
            $phrase .=   "комната: " .   $room;
        }
        if($email)    {
            if($phrase) {
                $phrase    .=  ", ";
            }
            $phrase .=   "email: " .   $email;
        }
        if($worktitle)    {
            if($phrase) {
                $phrase    .=  ", ";
            }
            $phrase .=   "должность: " .   $worktitle;
        }
        if(isset($bdates[0])    &&  isset($bdates[1])   &&  trim($bdates[0])  &&  trim($bdates[1])) {
            if($phrase) {
                $phrase    .=  ", ";
            }
            $phrase .=   "день рождения, период с: " .   trim($bdates[0])  .   " по "  .   trim($bdates[1]);
        }
        elseif (isset($bdates[0])    &&  trim($bdates[0])) {
            if($phrase) {
                $phrase    .=  ", ";
            }
            $phrase .=   "день рождения: " .   trim($bdates[0]);
        }

        try {
            $user_id    =   0;
            if (Auth::check()) {
                $user_id    =   Auth::user()->id;
            }

            $sl = new Search_logs();
            $sl->user_id            =   $user_id;
            $sl->term               =   $phrase;
            $sl->total_res          =   count($users);
            $sl->section_results    =   json_encode(
                array(
                    'news'      =>  0,
                    'users'     =>  count($users),
                    'docs'      =>  0,
                    'books'     =>  0,
                    'razdels'   =>  0,
                    'deps'      =>  0
                ));
            $sl->save();
        }
        catch(Exception $e) {

        }

        return view('search.all', [ "users"  =>  $users,
            "deps"      =>  array(),
            "news"   =>  array(), "docs"  => array(),
            "books"  => array(),   "razdels"   =>  array(),
            "sections"  =>  $found_sections,
            "phrase"    =>  $phrase]);
    }

    private function getDatePartsFromString($str) {
        $months =   array(  "01"    =>  "ЯНВАРЬ",
                            "02"    =>  "ФЕВРАЛЬ",
                            "03"    =>  "МАРТ",
                            "04"    =>  "АПРЕЛЬ",
                            "05"    =>  "МАЙЯ",//единственный месяц, который не приводится к нормальной форме
                            "06"    =>  "ИЮНЬ",
                            "07"    =>  "ИЮЛЬ",
                            "08"    =>  "АВГУСТ",
                            "09"    =>  "СЕНТЯБРЬ",
                            "10"    =>  "ОКТЯБРЬ",
                            "11"    =>  "НОЯБРЬ",
                            "12"    =>  "ДЕКАБРЬ",
                            "15"    =>  "МАЯ");//жесткий хак, для работы по вводу "МАЙ");

        $day    =  null;
        $month  =   null;
        $date_parts =   explode(" ",    trim($str));
        if(count($date_parts)   >=  2) {
            for($i= 0; $i < count($date_parts); $i++) {
                if(trim($date_parts[$i])) {
                    if(is_null($day)) {
                        $day=   (int)trim($date_parts[$i]);
                        if($day <   10) {
                            $day    =   "0" .   $day;
                        }
                    }
                    else {
                        $month_name =   null;
                        $forms = Morphy::getBaseForm(trim(mb_strtoupper($date_parts[$i], "UTF-8")));
                        if($forms   !== false) {
                            if (count($forms)) {
                                $month_name = $forms[0];
                            } else {
                                $month_name = trim(mb_strtoupper($date_parts[$i], "UTF-8"));
                            }
                        }
                        else {
                            $month_name=  trim(mb_strtoupper($date_parts[$i], "UTF-8"));
                        }

                        $key    =   array_search($month_name,   $months);
                        if($key !== false) {
                            $month= $key;
                            if($month== "15") {
                                $month= "05";
                            }
                        }
                        else {
                            $month= "01";
                        }
                    }
                }
            }
        }
        else {
            $month_name =   null;
            $forms = Morphy::getBaseForm(trim(mb_strtoupper($date_parts[0], "UTF-8")));
            if($forms   !== false) {
                if (count($forms)) {
                    $month_name = $forms[0];
                } else {
                    $month_name = trim(mb_strtoupper($date_parts[0], "UTF-8"));
                }
            }
            else {
                $month_name=  trim(mb_strtoupper($date_parts[0], "UTF-8"));
            }
            $key    =   array_search($month_name,   $months);
            if($key !== false) {
                $month= $key;
                if($month== "15") {
                    $month= "05";
                }
            }
            else {
                $month= "01";
            }
        }

        return array($day,  $month);
    }

    private function getDatePartsFromFormattedString($str) {
        $day    =  null;
        $month  =   null;
        $year   =   null;
        $date_parts =   explode(".",    trim($str));
        if(count($date_parts)   >=  2) {
            for($i= 0; $i < count($date_parts); $i++) {
                if(trim($date_parts[$i])) {
                    if(is_null($day)) {
                        $day=   (int)trim($date_parts[$i]);
                        if($day <   10) {
                            $day    =   "0" .   $day;
                        }
                    }
                    else {
                        if(is_null($month)) {
                            $month=   (int)trim($date_parts[$i]);
                            if($month <   10) {
                                $month    =   "0" .   $month;
                            }
                        }
                        else {
                            $year=   (int)trim($date_parts[$i]);
                            if($year <   10) {
                                $year    =   "200" .   $month;
                            }
                            if(($year >=   10)    &&    ($year  <=  99)) {
                                $year    =   "19" .   $month;
                            }
                        }
                    }
                }
            }
        }
        else {
            $day=   (int)trim($date_parts[0]);
            if($day <   10) {
                $day    =   "0" .   $day;
            }
            $month  =   "01";
        }

        return array($day,  $month, $year);
    }
}
