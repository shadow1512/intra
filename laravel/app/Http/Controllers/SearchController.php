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
use Text_LangCorrect;

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

        $news = $users = $deps = $books = $razdels  =  $docs    =    $found_sections =   array();

        $phrase = trim($request->input('phrase'));
        $phrase = mb_substr($phrase, 0, 100);

        //Орфография, опечатки
        $dict   = pspell_new ( 'ru', '', '', "utf-8", PSPELL_BAD_SPELLERS);
        //Раскладка
        $corrector = new Text_LangCorrect();

        if(mb_strlen($phrase) >= 3) {
            $phrase = preg_replace("/[^0-9A-zА-яЁё]/iu", " ", $phrase);
            $words = explode(" ", $phrase);
            //итоговый массив со взвешенным списком
            $words_records = array();
            foreach($words as $word) {
                $word=  trim($word);
                if(mb_strlen($word, "UTF-8")) {
                    //Если email, то со словом ничего не надо делать
                    $validator = Validator::make(array('word'   =>  $word), [
                        'word'           =>  'email',
                    ]);
                    //Если цифры или слова
                    if ($validator->fails()) {
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

                            //var_dump($word);
                            if(pspell_check($dict,  $word)) {
                                //echo 'a';
                                $res= $this->getSearchResultsByWord($word);
                                $words_records[]    =   $res;
                                $total_found_by_word    =   count($res);
                                unset($res);
                            }
                            //Слово не нашлось в словаре
                            else {
                                //сначала ищем как есть, вдруг, это правильно?
                                $oldword = preg_replace("/[^0-9A-zА-яЁё]/iu", "", $oldword);
                                $res= $this->getSearchResultsByWord($oldword);
                                $words_records[]    =   $res;
                                $total_found_by_word    =   count($res);
                                unset($res);
                                //Ну, а если уж не нашлось, то можно пробовать другое
                                if(!$total_found_by_word) {
                                    //пробуем в начале советы (опечатки, если было на русском)
                                    $suggest    =   pspell_suggest($dict,   $word);
                                    //var_dump($suggest);
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

                    var_dump($search_result);
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
                            $found_records = User::select("users.id", "users.name", "users.avatar", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "users.birthday", "deps_peoples.work_title")
                                ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
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


        return view('search.all', [ "news"   =>  $news, "users"  =>  $users, "docs"  => $docs,
                                    "books"  => $books,   "razdels"   =>  $razdels,   "deps"  =>  $deps,
                                    "sections"  =>  $found_sections,
                                    "phrase"    =>  $phrase]);
    }

    private function getSearchResultsByWord($word,  $sections_to_find   =   array(),    $partials_to_find   =   array()) {
        //var_dump('byword_search_start');
        $word_records = array();

        //отдельно обработали синонимы к слову и получили все записи, отсортированные по количеству совпадений отдельным словам
        $syns_records   =   $this->getSearchResultsBySyns(mb_strtoupper($word,  "UTF-8"));
        //синонимы закончены

        $forms = Morphy::getBaseForm(trim(mb_strtoupper($word, "UTF-8")));
        if($forms   !== false) {
            if (count($forms)) {
                $word = $forms[0];
            } else {
                $word = trim(mb_strtoupper($word, "UTF-8"));
            }
        }
        else {
            $word=  trim(mb_strtoupper($word, "UTF-8"));
        }
        //var_dump($word);

        //Продолжаем со словом
        if(count($sections_to_find) &&  count($partials_to_find)) {
            $word_search_records = Terms::where('baseterm', 'LIKE', $word)
                ->whereIn('section',  $sections_to_find)
                ->whereIn('partial',  $partials_to_find)
                ->get();

        }
        if(count($sections_to_find) &&  !count($partials_to_find)) {
            $word_search_records = Terms::where('baseterm', 'LIKE', $word)
                ->whereIn('section',  $sections_to_find)
                ->get();
        }
        if(!count($sections_to_find) &&  count($partials_to_find)) {
            $word_search_records = Terms::where('baseterm', 'LIKE', $word)
                ->whereIn('partial',  $partials_to_find)
                ->get();
        }
        if(!count($sections_to_find) &&  !count($partials_to_find)) {
            $word_search_records = Terms::where('baseterm', 'LIKE', $word)->get();
        }
        //var_dump(count($word_search_records));
        //если у слова были синонимы, по ним что-то нашлось, а по самому слову нет - искать по подстроке не будем. Результат по слову = результат по синонимам
        if(count($syns_records) && !count($word_search_records)) {
            $word_records    =   $syns_records;
        }
        //если что-то нашли по слову
        if(count($word_search_records)) {
            //var_dump("sorting");
            $by_razdels = array();
            //Нам интересно искать по разделам
            foreach($word_search_records as $record) {
                $by_razdels[$record->section][] =   $record->record;
            }

            //var_dump($by_razdels);
            /*Нам не наплевать, что слово может встретиться для одной записи несколько раз. Например, для сотрудника в имени и отчестве
                (Иван Иванович) или в новости в заголовке и тексе (или в тексте несколько раз). Куда важнее по каким разным поисковым терминам
                запись вошла в выборку. Но при прочих равных надо учитывать количество вхождений*/
            foreach($by_razdels as $section =>  $records) {
                $by_razdels[$section]  =   array_count_values($records);
                arsort($by_razdels[$section]);
            }

            //var_dump($by_razdels);

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
        $dict   = pspell_new ( 'ru', '', '', "utf-8", PSPELL_FAST);
        //Раскладка
        $corrector = new Text_LangCorrect();

        if(mb_strlen($allname) >= 3) {
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
                            $res = $this->getSearchResultsByWord($word, array("users"));
                            $words_records[] = $res;
                            $total_found_by_word = count($res);
                            unset($res);
                        } //Слово не нашлось в словаре
                        else {
                            $oldword = preg_replace("/[^0-9A-zА-яЁё]/ius", "", $oldword);
                            $res = $this->getSearchResultsByWord($oldword, array("users"));
                            $words_records[] = $res;
                            $total_found_by_word = count($res);
                            unset($res);

                            if(!$total_found_by_word) {
                                //пробуем в начале советы (опечатки, если было на русском)
                                $suggest = pspell_suggest($dict, $word);
                                //var_dump($suggest);
                                //берем только первый вариант, остальные уже не то
                                if (count($suggest)) {
                                    $word = $suggest[0];
                                    //var_dump($word);
                                    $res = $this->getSearchResultsByWord($word, array("users"));
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
        $room   =   (int)$room;
        $users_by_room  =   array();
        $user_ids   =   array();
        if($room) {
            $room_records   =   User::where('room', '=',    $room)->orderBy("lname")->orderBy("fname")->orderBy("mname")->get();
            /*foreach($room_records   as $record) {
                $user_ids[] =   $record->id;
            }
            $room_sim_records   =   User::where('room', 'LIKE',    '%'  .   $room   .   '%')->get();
            foreach($room_sim_records   as $record) {
                if(!in_array($record->id,   $user_ids)) {
                    $user_ids[] =   $record->id;
                    $room_records->push($record);
                }
            }*/

            $users_by_room  =   $room_records;
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
            $phone_records   =   User::where('phone', '=',    $phone)
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

                $found_records = User::find($max_user_ids);
                $assoc_records = array();
                foreach ($found_records as $record) {
                    $assoc_records[$record->id] = $record;
                }
                foreach ($user_ids as $user_id) {
                    if(isset($assoc_records[$user_id])) {
                        $users_by_worktitle[] = array("record"  =>  $assoc_records[$user_id],   "weight"    =>  $search_result['users'][$user_id]);
                    }
                }
                unset($found_records);
                unset($assoc_records);
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

                $found_records = User::find($max_user_ids);
                $assoc_records = array();
                foreach ($found_records as $record) {
                    $assoc_records[$record->id] = $record;
                }
                if(count($assoc_records)) {
                    foreach ($user_ids as $user_id) {
                        if(isset($assoc_records[$user_id])) {
                            $users_by_dep[] = array("record"  =>  $assoc_records[$user_id],   "weight"    =>  $search_result['users'][$user_id]);
                        }
                    }
                }
                unset($found_records);
                unset($assoc_records);
            }
        }


        //кусок поиска по дню рождения
        $bdates = trim($request->input('birthdate'));
        $users_by_birthday  =   array();
        $user_ids   =   array();

        //надо посмотреть, период это или точная дата
        $bdates =   explode("-",    $bdates);
        if(isset($bdates[0])    &&  isset($bdates[1])   &&  trim($bdates[0])  &&  trim($bdates[1])) {

            $year   =   date("Y");

            $searchDate1 =   trim($bdates[0])  .   "." .   $year;
            $searchDate2 =   trim($bdates[1])  .   "." .   $year;

            //При переходе через "Новый год работает неверно, нужно условие
            $dm =   date("m", strtotime($searchDate1));
            $dm1 =   date("m", strtotime($searchDate2));
            if($dm1 <   $dm) {
                $dt     = date("m-d", strtotime($searchDate1));
                $dt1    =   date("m-d", strtotime($searchDate2));

                $birthday_records = User::select("users.id", "users.name", "users.avatar", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "deps_peoples.work_title")
                    ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                    ->whereRaw("DATE_FORMAT(birthday, '%m-%d') >=  '$dt'")
                    ->orWhereRaw("DATE_FORMAT(birthday, '%m-%d') <=  '$dt1'")->orderByRaw("MONTH(birthday)",    "ASC")->orderByRaw("DAY(birthday)", "ASC")->get();

            }
            else {
                $dt = date("m-d", strtotime($searchDate1));
                $dt1 = date("m-d", strtotime($searchDate2));

                $birthday_records = User::select("users.id", "users.name", "users.avatar", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "deps_peoples.work_title")
                    ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                    ->whereRaw("DATE_FORMAT(birthday,  '%m-%d')    >=  '$dt'")
                    ->whereRaw("DATE_FORMAT(birthday,  '%m-%d')    <=  '$dt1'")->orderByRaw("MONTH(birthday)",    "ASC")->orderByRaw("DAY(birthday)", "ASC")->get();

            }
            $users_by_birthday  =   $birthday_records;
        }
        elseif (isset($bdates[0])    &&  trim($bdates[0])) {
            $year       =   date("Y");

            $searchDates    =   explode(".",    trim($bdates[0]));
            if(count($searchDates) < 3) {
                $searchDate =   trim($bdates[0])  .   "." .   $year;
                $dt = date("Y-m-d", strtotime($searchDate));
                $birthday_records = User::select("users.id", "users.name", "users.avatar", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "deps_peoples.work_title")
                    ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                    ->where(DB::raw("MONTH(birthday)"), '=',    DB::raw("MONTH('$dt')"))->where(DB::raw("DAY(birthday)"), '=',    DB::raw("DAY('$dt')"))->orderByRaw("MONTH(birthday)",    "ASC")->orderByRaw("DAY(birthday)", "ASC")
                    ->orderBy("users.lname")->orderBy("users.fname")->orderBy("users.mname")->get();
            }
            else {
                $dt = date("Y-m-d", strtotime(trim($bdates[0])));
                $birthday_records = User::select("users.id", "users.name", "users.avatar", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "deps_peoples.work_title")
                    ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
                    ->where(DB::raw("MONTH(birthday)"), '=',    DB::raw("MONTH('$dt')"))->where(DB::raw("DAY(birthday)"), '=',    DB::raw("DAY('$dt')"))
                    ->where(DB::raw("YEAR(birthday)"), '=',    DB::raw("YEAR('$dt')"))->orderByRaw("MONTH(birthday)",    "ASC")->orderByRaw("DAY(birthday)", "ASC")
                    ->orderBy("users.lname")->orderBy("users.fname")->orderBy("users.mname")->get();
            }

            $users_by_birthday  =   $birthday_records;
        }

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
        $index  =   count($users_by_room);
        foreach($users_by_room as $user) {
            echo $user->id . "\r\n";
            if(array_key_exists($user->id,  $all_found_records)) {
                //$all_found_records[$user->id]   =   $all_found_records[$user->id]   +   $index;
                $all_found_records[$user->id] =   $all_found_records[$record->id]   + 1;
                echo $user->id. "\r\n";
            }
            else {
                //$all_found_records[$user->id]   =   $index;
                $all_found_records[$user->id] =   1;
            }
            $index  --;
        }

        $index  =   count($users_by_birthday);
        foreach($users_by_birthday as $user) {
            if(array_key_exists($user->id,  $all_found_records)) {
                //$all_found_records[$user->id]   =   $all_found_records[$user->id]   +   $index;
                $all_found_records[$user->id] =   $all_found_records[$record->id]   + 1;
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
                $all_found_records[$user->id] =   $all_found_records[$record->id]   + 1;
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
                $all_found_records[$user->id] =   $all_found_records[$record->id]   + 1;
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
        $found_records = User::select("users.id", "users.name", "users.avatar", "users.fname", "users.lname", "users.mname", "users.position", "users.email", "users.phone", "users.birthday", "deps_peoples.work_title")
            ->leftJoin('deps_peoples', 'users.id', '=', 'deps_peoples.people_id')
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

        return view('search.all', [ "users"  =>  $users,
            "deps"      =>  array(),
            "news"   =>  array(), "docs"  => array(),
            "books"  => array(),   "razdels"   =>  array(),
            "sections"  =>  $found_sections,
            "phrase"    =>  $phrase]);
    }
}
