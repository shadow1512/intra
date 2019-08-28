<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\User;
use App\User_Dinner_Bills;
use DOMDocument;

class getdinnerbills extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dinnerbills:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract dinner bills for each employee from the file';

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
        $total_inserted =   0;

        $ch = curl_init('http://intra.lan.kodeks.net/cooking/');
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($status_code == 200) {
            /*Внимание!! Тут отключается вывод ошибок в буфер и ошибки передаются на обработку в скрипты.
            Связано это с тем, что он ругается на <br>, например, а такие теги присутствуют в получаемом документе*/
            libxml_use_internal_errors(true);
            $res=   iconv("windows-1251",    "utf-8",   $res);
            $doc = new DomDocument('1.0', 'utf-8');
            preg_match("/<body[^>]*>(.*?)<\/body>/ius", $res, $matches);
            if (count($matches)) {
                $doc->loadHTML("<?xml encoding=\"utf-8\" ?><body>"   .   $matches[1] .   "</body>");
                if($doc) {
                    $deps  =   $doc->getElementsByTagName("a");
                    if($deps   &&  ($deps->count() >   0)) {
                        foreach($deps   as $dep) {
                            $ch = curl_init('http://intra.lan.kodeks.net/cooking/' . $dep->getAttribute('href'));
                            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $res_users = curl_exec($ch);
                            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            curl_close($ch);
                            if ($status_code == 200) {
                                $res_users = iconv("windows-1251", "utf-8", $res_users);
                                $doc_users = new DomDocument('1.0', 'utf-8');
                                preg_match("/<body[^>]*>(.*?)<\/body>/ius", $res_users, $matches_users);
                                if (count($matches_users)) {
                                    $doc_users->loadHTML("<?xml encoding=\"utf-8\" ?><body>" . $matches_users[1] . "</body>");
                                    if ($doc_users) {
                                        $users_and_bills = $doc_users->getElementsByTagName("td");
                                        $numusers   =   $users_and_bills->count();
                                        if ($users_and_bills && ($numusers > 0)) {
                                            for($i  =   0;  $i< $numusers;  $i  =   $i+2) {
                                                $user   =   $users_and_bills->item($i);

                                                $names  =   preg_replace("/\s/ius",    " ", $user->textContent);
                                                $lname = $fname = $mname = "";
                                                $names = explode( " ", $names);
                                                for($j= 0;  $j<count($names);   $j++) {
                                                    if(empty(trim($names[$j]))) {
                                                        unset($names[$j]);
                                                    }
                                                }
                                                $names  =   array_values($names);
                                                if (isset($names[0])) {
                                                    $lname = preg_replace("/[^А-яЁё]/ius",    "", $names[0]);
                                                }
                                                if (isset($names[1])) {
                                                    $fname = preg_replace("/[^А-яЁё]/ius",    "", $names[1]);
                                                    //Набор косяков в названии сотрудников
                                                    if($fname   ==  "Анстасия") {
                                                        $fname  =   "Анастасия";
                                                    }
                                                    if($fname   ==  "Екатерна") {
                                                        $fname  =   "Екатерина";
                                                    }
                                                    if($fname   ==  "Алексагндра") {
                                                        $fname  =   "Александра";
                                                    }
                                                    if($fname   ==  "Кирил") {
                                                        $fname  =   "Кирилл";
                                                    }
                                                }
                                                if (isset($names[2])) {
                                                    $mname = preg_replace("/[^А-яЁё]/ius",    "", $names[2]);
                                                }
                                                if ($lname && $fname) {
                                                    $linkUser   =   User::where("fname",    "LIKE", $fname)->where("lname", "LIKE", $lname)->first();
                                                    if ($linkUser) {
                                                        $linkRowUser = User::where("fname",    "LIKE", $fname)->where("lname", "LIKE", $lname)->where("mname",  "LIKE", $mname)->first();
                                                        if($linkRowUser) {
                                                            $linkUser   =   $linkRowUser;
                                                        }
                                                        else {
                                                            Log::info("DINNER: Не найдена связь для сотрудника (отчество!) "  .   $user->textContent   .   ". http://intra.lan.kodeks.net/cooking/"  .   $dep->getAttribute('href')   .   "\r\n");
                                                        }

                                                        $db =   new User_Dinner_Bills();
                                                        $db->summ       =   (int)$users_and_bills->item($i+1)->textContent;
                                                        $db->user_id    =   $linkUser->id;
                                                        $db->date_created   =   date("Y-m-d");
                                                        $db->save();
                                                        $total_inserted ++;
                                                    }
                                                    else {
                                                        Log::info("DINNER: Не найдена связь для сотрудника "  .   $user->textContent   .   ". http://intra.lan.kodeks.net/cooking/"  .   $dep->getAttribute('href')   .   "\r\n");
                                                    }
                                                }
                                                else {
                                                    Log::info("DINNER: Не выделены имя и фамилия для сотрудника:  "  .   $user->textContent   .   ". http://intra.lan.kodeks.net/cooking/"  .   $dep->getAttribute('href')   .   "\r\n");
                                                }
                                            }
                                        }
                                        else {
                                            Log::info("DINNER: Пустой список сотрудников: http://intra.lan.kodeks.net/cooking/"  .   $dep->getAttribute('href')   .   "\r\n");
                                        }
                                    }
                                    else {
                                        Log::info("DINNER: Ошибка преобразования файла со списком сотрудников. Проблема со структурой: http://intra.lan.kodeks.net/cooking/"  .   $dep->getAttribute('href')   .   "\r\n");
                                    }
                                }
                                else {
                                    Log::info("DINNER: Нет тега body. Проблема со структурой: http://intra.lan.kodeks.net/cooking/"  .   $dep->getAttribute('href')   .   "\r\n");
                                }
                            }
                            else {
                                Log::info("DINNER: Ошибка получения файла со списком сотрудников: http://intra.lan.kodeks.net/cooking/"  .   $dep->getAttribute('href')   .   "\r\n");
                            }
                        }
                    }
                    else {
                        Log::info("DINNER: Пустой список департаментов.\r\n");
                    }
                }
                else {
                    Log::info("DINNER: Ошибка преобразования файла со списком департаментов. Проблема со структурой\r\n");
                }
            }
            else {
                Log::info("DINNER: Нет тега body. Проблема со структурой\r\n");
            }
        }
        else {
            Log::info("DINNER: Ошибка получения файла со списком департаментов\r\n");
        }
    }
}
