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

if(isset($argv[1]) && ($argv[1] == 'struct')) {
    $dep    =   mysqli_query($conn, "SELECT id FROM deps WHERE parent_id is NULL");
    $row_dep    =   $dep->fetch_array(MYSQLI_ASSOC);
    if($row_dep) {
        createDepartmentStructure($conn, $row_dep["id"]);
    }
    exit();
}
if(isset($argv[1]) && ($argv[1] == 'oldstruct')) {
    createOldDepartmentStructure($conn, 536000001);
    exit();
}
if(isset($argv[1]) && ($argv[1] == 'structlink')) {
    createDepsLink($conn);
    exit();
}
if(isset($argv[1]) && ($argv[1] == 'parents')) {
    createDepartmentParents($conn);
    exit();
}
if(isset($argv[1]) && ($argv[1] == 'chefs')) {
    updateChefs($conn);
    exit();
}
if(isset($argv[1]) && ($argv[1] == 'clean')) {
    cleanStructData($conn);
    exit();
}

$fp_err =   null;
//$ch = curl_init('http://172.16.0.223/SedKodeks/eseddapi/Authenticate/GetToken/984dca20-c795-4b90-b4d2-a2f4640b83f2');
$ch = curl_init('http://172.16.0.223/SedKodeks/eseddapi/Authenticate/GetToken/174ad969-eb42-4b3e-a6e3-972c6ef9d3de');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
//curl_setopt($ch, CURLOPT_USERPWD, 'work\slava_u_s:fH10081958');
curl_setopt($ch, CURLOPT_USERPWD, 'work\abelyaevskiy:1Wolfh0und1');
//curl_setopt($ch, CURLOPT_VERBOSE, true);
//curl_setopt($ch, CURLOPT_FAILONERROR, true);
//curl_setopt($ch, CURLOPT_STDERR, $fp_err);
//curl_setopt($ch, CURLOPT_USERPWD, 'integra:Att3r0D0min4tu5');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if($status_code == 200) {
    $header_size = curl_getinfo($ch,CURLINFO_HEADER_SIZE);
    $header = substr($res, 0, $header_size);
    if (preg_match('#^Token:\s*(.*)$#mi', $header, $m)) {
        $tok = trim($m[1]);
    }
}
else {
    print_r($status_code . "/r/n");
}

curl_close($ch);

if($tok) {
    $ch = curl_init('http://172.16.0.223/SedKodeks/eseddapi/GlobalCatalogue/GetGKObjects');
//curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Token: $tok"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if($status_code == 200) {
        $tree = json_decode($res);
        foreach($tree as $obj) {
            if($obj->Active === true) {

                if($obj->ExecutiveType == 0) {
                    print("dep\r\n");
                    $res    =   mysqli_query($conn, "SELECT dep_id FROM deps_keys WHERE `key`='" . $obj->UID . "'");
                    if($res && $res->num_rows > 0) {
                        print("action:update\r\n");
                        $row = $res->fetch_assoc();
                        $mdate = date("Y-m-d H:i:s", strtotime($obj->ModifyDate));
                        $dep    =   mysqli_query($conn, "SELECT updated_at FROM deps WHERE id=" . $row['dep_id']);
                        if($dep && $dep->num_rows > 0) {
                            $rowdep = $dep->fetch_assoc();
                            if($mdate > $rowdep['updated_at']){
                                mysqli_query($conn, "UPDATE deps SET `name`='" . $obj->Name . "', `updated_at`='" . date("Y-m-d H:i:s") . "' WHERE id=" . $row['dep_id']);
                            }

                        }
                    }
                    else {
                        print("action:insert\r\n");
                        $date = date("Y-m-d H:i:s");
                        mysqli_query($conn, "INSERT INTO deps (name, created_at, updated_at) VALUES ('" . $obj->Name . "', '" . $date . "', '" . $date . "')");
                        $dep_id = mysqli_insert_id($conn);
                        mysqli_query($conn, "INSERT INTO deps_keys (`key`, `dep_id`, `parent_key`) VALUES ('" . $obj->UID . "', $dep_id, '" . $obj->Parent . "')");
                    }
                }
                else {
                    print("user\r\n");
                    $date = date("Y-m-d H:i:s");
                    $res    =   mysqli_query($conn, "SELECT user_id, sid FROM user_keys WHERE `key`='" . $obj->UID . "'");
                    if($res && $res->num_rows > 0) {
                        print("action:update\r\n");
                        $row = $res->fetch_assoc();
                        $mdate = date("Y-m-d H:i:s", strtotime($obj->ModifyDate));
                        $user    =   mysqli_query($conn, "SELECT updated_at FROM users WHERE id=" . $row['user_id']);
                        if($user && $user->num_rows > 0) {
                            $rowuser = $user->fetch_assoc();
                            if ($mdate > $rowuser['updated_at']) {
                                mysqli_query($conn,
                                    "UPDATE users SET `name`='" . $obj->Name . "', `fname`='"   .   preg_replace("/[^А-я]/ius",    "", $obj->FirstName) .
                                    "', `mname`='" .    preg_replace("/[^А-я]/ius",    "", $obj->Patronymic) . "', `lname`='" .
                                    preg_replace("/[^А-я]/ius",    "", $obj->Surname) . "', `updated_at`='" . $date . "' WHERE id="  .   $row["user_id"]);
                            }

                            $url_data = 'http://172.16.0.223/SedKodeks/eseddapi/Access/GetUser?uid=' . $obj->UID;
                            $chauthdata = curl_init($url_data);
                            //curl_setopt($ch, CURLOPT_HEADER, true);
                            curl_setopt($chauthdata, CURLINFO_HEADER_OUT, true);
                            curl_setopt($chauthdata, CURLOPT_HTTPHEADER, array("Token: $tok"));
                            curl_setopt($chauthdata, CURLOPT_RETURNTRANSFER, true);
                            $resauthdata = curl_exec($chauthdata);
                            $status_code_data = curl_getinfo($chauthdata, CURLINFO_HTTP_CODE);
                            if ($status_code_data == 200) {
                                $obj_authdata = json_decode($resauthdata);
                                $pruz   =  0;
                                if($obj_authdata->Pruz) {
                                    $pruz   =  1;
                                }
                                //Хак для Насти Рябининой
                                if($sid ==  "S-1-5-21-3953116633-1604536341-3751884121-10009") {
                                    $obj_authdata->UserSID  =   "S-1-5-21-3953116633-1604536341-3751884121-10009";
                                }
                                $query  =   "UPDATE user_keys SET `parent_key`='" . $obj->Parent . "',  `sid`='" . $obj_authdata->UserSID . "',  `ad_deleted`=$pruz, `user_login`='" . addslashes($obj_authdata->Username) . "' WHERE user_id=" . $row["user_id"];
                                $updres =   mysqli_query($conn, $query);
                                if(!$updres) {
                                    printf("Error: %s\n", mysqli_error($conn));
                                }
                            } else {
                                mysqli_query($conn, "UPDATE user_keys SET `parent_key`='" . $obj->Parent . "' WHERE user_id=" . $row["user_id"]);
                                print("error:no AD data for " . $row["user_id"] . "\r\n");
                            }

                            $dep = mysqli_query($conn, "SELECT dep_id FROM deps_keys WHERE `key`='" . $obj->Parent . "'");
                            if ($dep && $dep->num_rows > 0) {
                                $rowdep = $dep->fetch_assoc();
                                $chef = 0;
                                $post   =   "";

                                $ch_data = curl_init('http://172.16.0.223/SedKodeks/eseddapi/GlobalCatalogue/GetGKObjectByUID?uid=' . $obj->UID);
                                //curl_setopt($ch, CURLOPT_HEADER, true);
                                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Token: $tok"));
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                $res_data = curl_exec($ch_data);
                                $status_code = curl_getinfo($ch_data, CURLINFO_HTTP_CODE);
                                if($status_code == 200) {
                                    $obj_data = json_decode($res);
                                    if ($obj->IsChief) {
                                        $chef = 1;
                                    }
                                    if ($obj_data->Post) {
                                        $post = $obj_data->Post;
                                    }
                                }
                                $query = "UPDATE deps_peoples SET `work_title`='" . $post . "', `created_at`='" . $date . "', `updated_at`='" . $date . "', `chef`=$chef WHERE dep_id=" . $rowdep['dep_id'] . " AND user_id=" . $row["user_id"];
                                mysqli_query($conn, $query);
                            }
                        }
                    }
                    else {
                        print("action:insert\r\n");
                        $ch = curl_init('http://172.16.0.223/SedKodeks/eseddapi/GlobalCatalogue/GetGKObjectByUID?uid=' . $obj->UID);
                        //curl_setopt($ch, CURLOPT_HEADER, true);
                        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Token: $tok"));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $res = curl_exec($ch);
                        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        if($status_code == 200) {
                            $obj = json_decode($res);
                            $date = date("Y-m-d H:i:s");
                            //Хак для ошибки в СЭДД, которую они потом поправят
                            if($obj->Patronymic ==  "Иванова") {
                                $obj->Patronymic    =   "Ивановна";
                            }
                            $insres = mysqli_query($conn,
                                "INSERT INTO users (`name`, `role_id`, `fname`, `mname`, `lname`, `phone`, `email`, `room`, `mobile_phone`, created_at, updated_at) 
                                    VALUES ('" . $obj->Name . "', 2, '" . preg_replace("/[^А-я]/ius",    "", $obj->FirstName) . "', '" .
                                            preg_replace("/[^А-я]/ius",    "", $obj->Patronymic) . "', '" .
                                            preg_replace("/[^А-я]/ius",    "", $obj->Surname) . "', '" . $obj->Phone . "',
                                            '" . $obj->EMail . "', '" . $obj->Address . "', '" . $obj->MobilePhone . "', '" . $date . "', '" . $date . "')");
                            if(!$insres) {
                                printf("Error: %s\n", mysqli_error($conn));
                            }
                            $user_id = mysqli_insert_id($conn);
                            $chauthdata = curl_init('http://172.16.0.223/SedKodeks/eseddapi/Access/GetUser?uid=' . $obj->UID);
                            //curl_setopt($ch, CURLOPT_HEADER, true);
                            curl_setopt($chauthdata, CURLINFO_HEADER_OUT, true);
                            curl_setopt($chauthdata, CURLOPT_HTTPHEADER, array("Token: $tok"));
                            curl_setopt($chauthdata, CURLOPT_RETURNTRANSFER, true);
                            $resauthdata = curl_exec($chauthdata);
                            $status_code_data = curl_getinfo($chauthdata, CURLINFO_HTTP_CODE);
                            if($status_code_data == 200) {
                                $obj_authdata = json_decode($resauthdata);
                                $pruz   =   0;
                                if($obj_authdata->Pruz) {
                                    $pruz   =   1;
                                }
                                //Хак для Настя Рябининой
                                if($obj->UID    ==  "4027a2c2-c369-4bea-8fe5-4a9664c612b1") {
                                    $obj_authdata->UserSID  =   "S-1-5-21-3953116633-1604536341-3751884121-10009";
                                }

                                $query  =   "INSERT INTO user_keys (`key`, `user_id`, `parent_key`,  `sid`,  `ad_deleted`, `user_login`) VALUES ('" . $obj->UID . "', $user_id, '" . $obj->Parent . "', '"  .   $obj_authdata->UserSID  .   "', "   .   $pruz .   ",  '"  .   addslashes($obj_authdata->Username) .   "')";
                                $insres =mysqli_query($conn, $query);
                                if(!$insres) {
                                    printf("Error: %s\n", mysqli_error($conn));
                                }
                            }
                            else {
                                mysqli_query($conn, "INSERT INTO user_keys (`key`, `user_id`, `parent_key`) VALUES ('" . $obj->UID . "', $user_id, '" . $obj->Parent . "')");
                                print("error:no AD data for $user_id\r\n");
                            }

                            $dep    =   mysqli_query($conn, "SELECT dep_id FROM deps_keys WHERE `key`='" . $obj->Parent . "'");
                            if($dep && $dep->num_rows > 0) {
                                $rowdep = $dep->fetch_assoc();
                                $chef = 0;
                                if($obj->Leader) {
                                    $chef = 1;
                                }
                                $query = "INSERT INTO deps_peoples (`dep_id`, `people_id`, `work_title`, `created_at`, `updated_at`, `chef`)
                                                        VALUES (" . $rowdep["dep_id"] . ", $user_id, '" . $obj->Post . "', '" . $date . "', '" . $date . "', $chef)";
                                $insres =   mysqli_query($conn, $query);

                                if(!$insres) {
                                    printf("Error: %s\n", mysqli_error($conn));
                                    printf("Error: %s\n", $query);exit();
                                }
                            }
                        }
                    }
                }
            }
            else {
                if($obj->ExecutiveType == 0) {
                    print("dep: no active\r\n");
                    $res = mysqli_query($conn, "SELECT dep_id FROM deps_keys WHERE `key`='" . $obj->UID . "'");
                    if ($res && $res->num_rows > 0) {
                        print("action:delete\r\n");
                        $row = $res->fetch_assoc();
                        mysqli_query($conn, "UPDATE deps SET `deleted_at`='" . date("Y-m-d H:i:s") . "', `updated_at`='" . date("Y-m-d H:i:s") . "' WHERE id=" . $row['dep_id']);
                    }
                }
                else {
                    print("user: no active\r\n");
                    $res = mysqli_query($conn, "SELECT user_id FROM users_keys WHERE `key`='" . $obj->UID . "'");
                    if ($res && $res->num_rows > 0) {
                        print("action:delete\r\n");
                        $row = $res->fetch_assoc();
                        mysqli_query($conn, "UPDATE users SET `deleted_at`='" . date("Y-m-d H:i:s") . "', `updated_at`='" . date("Y-m-d H:i:s") . "' WHERE id=" . $row['user_id']);
                    }
                }
            }
        }
    }
    else {
        var_dump($status_code);
    }
}

function createDepartmentParents($conn) {
    $deps = mysqli_query($conn, "SELECT * FROM deps_keys");
    if($deps) {
        while($row = $deps->fetch_assoc()) {
            if($row['parent_key'] != '00000000-0000-0000-0000-000000000000') {
                $parent_dep = mysqli_query($conn, "SELECT dep_id FROM deps_keys WHERE `key`='" . $row['parent_key'] . "'");
                if($parent_dep && $parent_dep->num_rows > 0) {
                    $parent_id = $parent_dep->fetch_assoc();
                    $parent_id = $parent_id["dep_id"];
                    mysqli_query($conn, "UPDATE deps SET parent_id=$parent_id WHERE id=" . $row['dep_id']);
                }
            }
        }

    }
}

function createDepartmentStructure($conn, $parent_id) {
    $code = new HierCode(CODE_LENGTH);
    $parent_code    = null;

    if($parent_id != 2) {
        $parent_dep = mysqli_query($conn, "SELECT * FROM deps WHERE id=" . $parent_id);
        if($parent_dep && $parent_dep->num_rows > 0) {
            $parent_row     = $parent_dep->fetch_assoc();
            $parent_code    = $parent_row["parent_id"];
        }
    }
    else {
        for($i = 0; $i < CODE_LENGTH; $i++) {
            $parent_code .= $code->digit_to_char[0];
        }

    }

    $deps = mysqli_query($conn, "SELECT * FROM deps WHERE parent_id=" . $parent_id);
    if($deps) {
        $index = 0;
        $dep_code = $parent_code;
        while($row = $deps->fetch_assoc()) {
            if($index == 0) {
                for($i = 0; $i < CODE_LENGTH; $i++) {
                    $dep_code .= $code->digit_to_char[0];
                }
            }
            else {
                $code->setValue($dep_code);
                $dep_code = $code->getNextCode();
            }

            mysqli_query($conn, "UPDATE deps SET parent_id='" . $dep_code . "' WHERE id=" . $row["id"]);
            createDepartmentStructure($conn, $row["id"]);
            $index ++;
        }
    }
}

function createOldDepartmentStructure($conn, $parent_id) {
    $code = new HierCode(CODE_LENGTH);
    $parent_code    = null;

    if($parent_id != 536000001) {
        $parent_dep = mysqli_query($conn, "SELECT * FROM deps_temporal WHERE source_id=" . $parent_id);
        if($parent_dep && $parent_dep->num_rows > 0) {
            $parent_row     = $parent_dep->fetch_assoc();
            $parent_code    = $parent_row["parent_code"];
        }
    }

    $deps = mysqli_query($conn, "SELECT * FROM deps_temporal WHERE parent_id=" . $parent_id);
    if($deps) {
        $index = 0;
        $dep_code = $parent_code;
        while($row = $deps->fetch_assoc()) {
            if($index == 0) {
                for($i = 0; $i < CODE_LENGTH; $i++) {
                    $dep_code .= $code->digit_to_char[0];
                }
            }
            else {
                $code->setValue($dep_code);
                $dep_code = $code->getNextCode();
            }

            mysqli_query($conn, "UPDATE deps_temporal SET parent_code='" . $dep_code . "' WHERE source_id=" . $row["source_id"]);
            createOldDepartmentStructure($conn, $row["source_id"]);
            $index ++;
        }
    }
}

function createDepsLink($conn) {
    $deps = mysqli_query($conn, "SELECT * FROM deps_temporal");
    if($deps) {
        while ($row = $deps->fetch_assoc()) {
            $linkdep = mysqli_query($conn, "SELECT id FROM deps WHERE name LIKE '" . $row['name'] . "%' AND LENGTH(parent_id)=" . mb_strlen($row['parent_code'], "UTF-8") .   " LIMIT 1");
            if ($linkdep) {
                $rowlink    =   $linkdep->fetch_assoc();
                mysqli_query($conn, "UPDATE deps_temporal SET sedd_dep_id="  .   $rowlink['id']    .   " WHERE id=" .   $row["id"]);
            }
        }
    }
}

function updateChefs($conn) {

    $max    =   2;
    $levels     =   mysqli_query($conn, "SELECT MAX(LENGTH(parent_id)) as max FROM deps");
    $levels_row = $levels->fetch_array(MYSQLI_ASSOC);
    if($levels_row) {
        $max    =   $levels_row["max"];
    }
    $positions = mysqli_query($conn, "SELECT * FROM deps_peoples");
    if($positions) {
        while($row = $positions->fetch_assoc()) {
            $dep    =   mysqli_query($conn, "SELECT parent_id FROM deps WHERE id=" . $row["dep_id"]);
            $row_dep    =   $dep->fetch_array(MYSQLI_ASSOC);
            if($row_dep && ($row["chef"] == 1)) {
                $cur_length =   mb_strlen($row_dep["parent_id"], "UTF-8");
                $chef   =  $max - $cur_length;
                mysqli_query($conn, "UPDATE deps_peoples SET chef=" .   $chef   .   " WHERE id="    .   $row["id"]);
            }
        }
    }
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

    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");
}

?>