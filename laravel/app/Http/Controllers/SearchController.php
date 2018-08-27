<?php

namespace App\Http\Controllers;

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

        $phrase = trim($request->input('phrase'));
        $phrase = mb_substr($phrase, 0, 100);

        //Орфография, опечатки
        $conf = pspell_config_create ( 'ru', '', '', "koi8-r");
        pspell_config_mode ( $conf, PSPELL_NORMAL);
        pspell_config_personal($conf,   storage_path('app/public/dict/pspell_custom.aspell.ru.rws'));
        $dict   =   pspell_new_config($conf);
        //Раскладка
        $corrector = new Text_LangCorrect();

        if(mb_strlen($phrase) > 3) {
            $words = explode(" ", $phrase);
            //итоговый массив со взвешенным списком
            $words_records = array();
            foreach($words as $word) {
                var_dump($word);
                //Если email, то со словом ничего не надо делать
                $validator = Validator::make(array('word'   =>  $word), [
                    'word'           =>  'email',
                ]);
                //Если цифры или слова
                if ($validator->fails()) {
                    var_dump('word_way');
                    $word = preg_replace("/[^0-9A-zА-я]/", "", $word);
                    //с цифрами ничего делать не надо
                    if(!is_int($word) && (mb_strlen($word) >= 3)) {
                        var_dump('letters_way');
                        /*Если человек вводит какое-то разумное слово, то если:
                            - он ошибся в транслитерации и еще допустил опечатку, то маловероятно, что выйдет
                            - если он ошибся в чем-то одном, то последовательное применение обоих методов сначала в одном порядке, потом в другом, дадут результат*/
                        //слово есть в словаре
                        if(pspell_check($dict,  mb_convert_encoding($word,  "KOI8-R",   "UTF-8"))) {
                            var_dump('voc_present');
                            $words_records[]    =   $this->getSearchResultsByWord($word);
                        }
                        //Слово не нашлось в словаре
                        else {
                            var_dump('not_in_voc');
                            //пробуем в начале советы (опечатки, если было на русском)
                            $suggest    =   pspell_suggest($dict,   mb_convert_encoding($word,  "KOI8-R",   "UTF-8"));
                            //берем только первый вариант, остальные уже не то
                            if(count($suggest)) {
                                $word=  mb_convert_encoding($suggest[0],  "UTF-8",  "KOI8-R");
                                var_dump($word);
                                $words_records[]    =   $this->getSearchResultsByWord($word);
                            }
                            //если нет предложений, значит, нужно попробовать сменить раскладку
                            else {
                                var_dump('correction');
                                $word=  $corrector->parse($word, $corrector::KEYBOARD_LAYOUT);
                                var_dump($word);
                                $words_records[]    =   $this->getSearchResultsByWord($word);
                            }
                        }
                    }
                    //цифры
                    if(is_int($word)) {
                        var_dump('digit_way');
                        $digit_results  =   array();
                        $word_search_records  =  Terms::where('baseterm', 'LIKE', $word)->get();
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
                    var_dump('email_way');
                    //email будем искать только по той части, что до @, просто потому, что все, что после люди путают
                    $email_parts    =   explode("@",    $word);
                    if(count($email_parts) > 1) {
                        $email_part=   $email_parts[0];
                        $email_results  =   array();
                        $word_search_records  =  Terms::where('baseterm', 'LIKE', $email_part.  '%')->get();
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

            var_dump($words_records);
        }
        else {
            var_dump("empty");
        }

        $news = $users = $docs = $books = array();
        return view('search.all', ["news"   =>  $news, "users"  =>  $users, "docs"  => $docs, "books"  => $books]);
    }

    private function getSearchResultsByWord($word) {
        var_dump('byword_search_start');
        $word_records = array();

        $word = Morphy::getBaseForm(trim(mb_strtoupper($word, "UTF-8")));
        var_dump($word);
        //отдельно обработали синонимы к слову и получили все записи, отсортированные по количеству совпадений отдельным словам
        $syns_records   =   $this->getSearchResultsBySyns($word);
        //синонимы закончены

        //Продолжаем со словом
        $word_search_records  =  Terms::where('baseterm', 'LIKE', $word)->get();
        var_dump($word_search_records);
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
                asort($by_razdels[$section]);
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
                                if(isset($record,   $by_razdels[$section])) {
                                    $total_value += $by_razdels[$section][$record] * 10000;
                                }
                                if(isset($record,   $syns_records[$section])) {
                                    $total_value += $syns_records[$section][$record];
                                }
                                $section_records[$record]    = $total_value;
                            }
                        }
                        else {
                            $records=   array_keys($by_razdels[$section]);
                            foreach($records as $record) {
                                $section_records[$record]    = $by_razdels[$section][$record] * 10000;
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
                    asort($section_records);
                    $word_records[$section]    =    $section_records;
                }
            }
            else {
                $word_records   =   $by_razdels;
            }
        }
    }

    //Функция поиска результатов по синониму к слову
    private function getSearchResultsBySyns($word) {
        $syns   =   Syns::where('term','LIKE',  $word)->get();
        $syns_records = array();
        if(count($syns)) {
            //Сюда будем складывать обработанные результаты по каждой найденной фразе-синониму
            $parsed_syn_phrases =   0;
            foreach($syns as $syn) {
                //Сюда будем складывать обработанные результаты по каждому слову из фразы-синонима
                $syn_records = array();
                $syn_words = explode(" ", $syn);

                /*переменная, в которой храним количество проверенных слов во фразе-синониме, чтобы потом сравнивать с количеством вхождений найденных записей
                    в итоговую выборку. Цель - вычислить максимальное соответствие заданной фразе*/
                $parsed_syn_words = 0;
                foreach($syn_words as $syn_word) {
                    $syn_word = preg_replace("/[^0-9A-zА-я]/", "", $syn_word);
                    //с цифрами ничего делать не надо
                    if(mb_strlen($syn_word) >= 3) {
                        $syn_word = Morphy::getBaseForm(trim(mb_strtoupper($syn_word, "UTF-8")));
                        $syn_records[]  =  Terms::where('baseterm', 'LIKE', $syn_word)->get();
                        $parsed_syn_words   ++;
                    }

                }
                $by_razdels = array();
                $record_word_counter = array();
                //Ищем по каждому разделу запись, которая вошла в выборку по максимальному количеству слов
                if($parsed_syn_words > 0) {
                    //по каждому отработанному слову проверяем найденные записи в индексе
                    for($i = 0; $i < $parsed_syn_words; $i++) {
                        //Нам интересно искать по разделам
                        foreach($syn_records[$i] as $record) {
                            $by_razdels[$record->section][$i][] =   $record->record;
                        }
                        /*Нам не наплевать, что слово может встретиться для одной записи несколько раз. Например, для сотрудника в имени и отчестве
                            (Иван Иванович) или в новости в заголовке и тексе (или в тексте несколько раз). Куда важнее по каким разным поисковым терминам
                            запись вошла в выборку. Но при прочих равных надо учитывать количество вхождений*/
                        foreach($by_razdels as $section =>  $by_word) {
                            $record_word_counter[$section][$i]  =   array_count_values($by_razdels[$section][$i]);
                            $by_razdels[$section][$i]           =   array_unique($by_razdels[$section][$i]);
                        }
                    }
                    //Теперь считаем сколько раз запись вошла в результат по каждому слову
                    foreach($by_razdels as $section =>  $by_word) {
                        $by_razdels[$section]['total'] = array();
                        for($i = 0; $i < $parsed_syn_words; $i++) {
                            $by_razdels[$section]['total'] = array_merge($by_razdels[$section]['total'],    $by_razdels[$section][$i]);
                            unset($by_razdels[$section][$i]);
                        }
                        $record_word_counter[$section]['total']  =   array_count_values($by_razdels[$section]['total']);

                        foreach($record_word_counter[$section]['total'] as $record  =>  $numcount) {
                            /*Чем по большему количеству слов нашлась запись, тем выше ценность (умножаем на 1000).
                            На 1000 потому что, если бы было на 10, то можно было бы 10 присутствиями одного слова в тексте компенсировать присутствие двух слов*/
                            $record_word_counter[$section]['total'][$record]    =   $numcount   *   1000;
                            for($i = 0; $i < $parsed_syn_words; $i++) {
                                //если были несколько вхождений для одного слова, то надо добавить
                                if(array_key_exists($record,    $record_word_counter[$section][$i])) {
                                    $record_word_counter[$section]['total'][$record] = $record_word_counter[$section]['total'][$record] + $record_word_counter[$section][$i][$record];
                                }
                            }
                        }

                        asort($record_word_counter[$section]['total']);
                    }
                }

                //Складдываем в одно хранилище для всех записей синонимов
                if(count($record_word_counter)) {

                    foreach($record_word_counter as $section    =>  $record_counter) {
                        if(!isset($syns_records[$section])) {
                            $syns_records[$section] =   $record_counter['total'];
                        }
                        else {
                            foreach($record_counter as $record  => $weight) {
                                if(array_key_exists($record,    $syns_records[$section])) {
                                    $syns_records[$section][$record]    =   $syns_records[$section][$record] + $weight;
                                }
                                else {
                                    $syns_records[$section][$record] = $weight;
                                }
                            }
                        }
                        asort($syns_records[$section]);
                    }

                    $parsed_syn_phrases ++;
                    unset($record_word_counter);
                }
            }

        }

        return $syns_records;
    }
}
