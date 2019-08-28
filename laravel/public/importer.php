<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 30.05.2018
 * Time: 15:20
 */
require_once __DIR__ . '/hiercode.php';

//изначально запускается простое копирование данных из СЭДД, данные переносятся как есть
//Потом запускается parents режим - чтобы на основании кодов СЭДД правильно выстроить зависимости подразделений
//Потом запускается struct режим - чтобы на основании правильных зависимостей построить структуру, основанную на кодах
//Потом запускается chefs режим - чтобы на основании структуру подразделений правильно проставить веса сотрудникам
$conn = mysqli_connect("localhost", "phpmyadmin", "dhgstef", "intradb") or die("No DB connection");
$conn->set_charset("utf8");
$tok = null;

if(isset($argv[1]) && ($argv[1] == 'setadmin')) {
    if(isset($argv[2]) && isset($argv[3])) {
        $level  =   (int)$argv[3];
        if($level > 0) {
            createModerator($conn,  $argv[2],   $level);
        }
        else {
            print("Передан неверный уровень прав\r\n");
        }
    }
    else {
        print("Передан неверное количество аргументов\r\n");
    }
    exit();
}

if(isset($argv[1]) && ($argv[1] == 'getdinnerbills')) {
    getDinnerBills($conn);
    exit();
}

function createModerator($conn, $email, $level) {

    $person_obj     =   mysqli_query($conn, "SELECT id FROM users WHERE email LIKE '"   .   $email  .   "' LIMIT 1");
    if($person_obj && ($person_obj->num_rows > 0)) {
        if($level   ==  "admin") {
            $level  =   1;
        }
        if($level   ==  "moderate_content") {
            $level  =   3;
        }
        if($level   ==  "moderate_persons") {
            $level  =   4;
        }
        $person =   $person_obj->fetch_assoc();
        mysqli_query($conn, "UPDATE users SET role_id=$level WHERE id=" .   $person["id"]) or die(mysqli_error($conn));

        print("Права предоставлены\r\n");
    }
    else {
        print("Пользователь с таким контактом не найден\r\n");
    }


}

function getDinnerBills($conn) {
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
                                                    $linkuser = mysqli_query($conn, "SELECT id FROM users WHERE fname LIKE '" . $fname . "' AND lname LIKE '" . $lname . "'");
                                                    if ($linkuser    &&  $linkuser->num_rows  >   0) {
                                                        $linkRowUser    =   null;
                                                        if($linkuser->num_rows  >   1) {
                                                            $linkuser = mysqli_query($conn, "SELECT id FROM users WHERE fname LIKE '" . $fname . "' AND lname LIKE '" . $lname . "' AND mname LIKE '" . $mname . "'");
                                                            if ($linkuser    &&  $linkuser->num_rows  >   0) {
                                                                $linkRowUser = $linkuser->fetch_assoc();
                                                            }
                                                            else {
                                                                print("Не найдена связь для сотрудника (отчество!) "  .   $user->textContent   .   ". http://intra.lan.kodeks.net/cooking/"  .   $dep->getAttribute('href')   .   "\r\n");
                                                            }
                                                        }
                                                        else {
                                                            $linkRowUser = $linkuser->fetch_assoc();
                                                        }

                                                        if(!is_null($linkRowUser)) {
                                                            $summ   =   (int)$users_and_bills->item($i+1)->textContent;
                                                            $insres = mysqli_query($conn,
                                                                "INSERT INTO users_dinner_bills (`user_id`, `date_created`, `summ`) VALUES ("   .   $linkRowUser["id"]  .   ", '"   .   date("Y-m-d")   .   "', $summ)");
                                                            if(!$insres) {
                                                                printf("Error: %s\n", mysqli_error($conn)   .   "\r\n");
                                                            }
                                                            else {
                                                                $total_inserted ++;
                                                            }
                                                        }
                                                    }
                                                    else {
                                                        print("Не найдена связь для сотрудника "  .   $user->textContent   .   ". http://intra.lan.kodeks.net/cooking/"  .   $dep->getAttribute('href')   .   "\r\n");
                                                    }
                                                }
                                                else {
                                                    print("Не выделены имя и фамилия для сотрудника:  "  .   $user->textContent   .   ". http://intra.lan.kodeks.net/cooking/"  .   $dep->getAttribute('href')   .   "\r\n");
                                                }
                                            }
                                        }
                                        else {
                                            print("Пустой список сотрудников: http://intra.lan.kodeks.net/cooking/"  .   $dep->getAttribute('href')   .   "\r\n");
                                        }
                                    }
                                    else {
                                        print("Ошибка преобразования файла со списком сотрудников. Проблема со структурой: http://intra.lan.kodeks.net/cooking/"  .   $dep->getAttribute('href')   .   "\r\n");
                                    }
                                }
                                else {
                                    print("Нет тега body. Проблема со структурой: http://intra.lan.kodeks.net/cooking/"  .   $dep->getAttribute('href')   .   "\r\n");
                                }
                            }
                            else {
                                print("Ошибка получения файла со списком сотрудников: http://intra.lan.kodeks.net/cooking/"  .   $dep->getAttribute('href')   .   "\r\n");
                            }
                    }
                }
                else {
                    print("Пустой список департаментов.\r\n");
                }
            }
            else {
                print("Ошибка преобразования файла со списком департаментов. Проблема со структурой\r\n");
            }
        }
        else {
            print("Нет тега body. Проблема со структурой\r\n");
        }
    }
    else {
        print("Ошибка получения файла со списком департаментов\r\n");
    }

    print("Загружено $total_inserted счетов\r\n");
}

function cleanStructData($conn) {

    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

    mysqli_query($conn, "TRUNCATE deps") or die(mysqli_error($conn));
    mysqli_query($conn, "TRUNCATE deps_keys") or die(mysqli_error($conn));
    mysqli_query($conn, "TRUNCATE deps_peoples") or die(mysqli_error($conn));
    mysqli_query($conn, "TRUNCATE users") or die(mysqli_error($conn));
    mysqli_query($conn, "TRUNCATE user_contacts") or die(mysqli_error($conn));
    mysqli_query($conn, "TRUNCATE user_keys") or die(mysqli_error($conn));
    mysqli_query($conn, "TRUNCATE deps_temporal") or die(mysqli_error($conn));
    mysqli_query($conn, "TRUNCATE terms") or die(mysqli_error($conn));
    mysqli_query($conn, "TRUNCATE profiles_saved") or die(mysqli_error($conn));

    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

    print("Очищены таблицы deps, deps_keys, deps_peoples, users, user_contacts, user_keys, deps_temporal, terms\r\n");
}

?>