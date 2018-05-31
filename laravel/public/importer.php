<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 30.05.2018
 * Time: 15:20
 */
require_once __DIR__ . '/hiercode.php';

$conn = mysqli_connect("localhost", "phpmyadmin", "dhgstef", "intradb") or die("No DB connection");
$conn->set_charset("utf8");
$tok = null;

if(isset($argv[1]) && ($argv[1] == 'struct')) {
    createDepartmentStructure($conn, 2);
    exit();
}
if(isset($argv[1]) && ($argv[1] == 'parents')) {
    createDepartmentParents($conn);
    exit();
}
$ch = curl_init('http://172.16.0.76/Test/EseddApi/Authenticate/GetToken/984dca20-c795-4b90-b4d2-a2f4640b83f2');
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, 'integra:Att3r0D0min4tu5');
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

curl_close($ch);

$ch = curl_init('http://172.16.0.76/Test/EseddApi/GlobalCatalogue/GetGKObjects');
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
                $res    =   mysqli_query($conn, "SELECT dep_id FROM deps_keys WHERE `key`='" . $obj->UID . "'");
                if($res && $res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                    $mdate = date("Y-m-d H:i:s", strtotime($obj->ModifyDate));
                    $dep    =   mysqli_query($conn, "SELECT updated_at FROM deps WHERE id=" . $row['dep_id']);
                    if($dep && $dep->num_rows > 0) {
                        $rowdep = $dep->fetch_assoc();
                        if($mdate > $rowdep['updated_at']){
                            mysqli_query($conn, "UPDATE deps (name, updated_at) VALUES ('" . $obj->Name . "', '" . date("Y-m-d H:i:s") . "')");
                        }

                    }
                }
                else {
                    $date = date("Y-m-d H:i:s");
                    mysqli_query($conn, "INSERT INTO deps (name, created_at, updated_at) VALUES ('" . $obj->Name . "', '" . $date . "', '" . $date . "')");
                    $dep_id = mysqli_insert_id($conn);
                    mysqli_query($conn, "INSERT INTO deps_keys (`key`, `dep_id`, `parent_key`) VALUES ('" . $obj->UID . "', $dep_id, '" . $obj->Parent . "')");
                }
            }
            else {
                $res    =   mysqli_query($conn, "SELECT user_id FROM user_keys WHERE `key`='" . $obj->UID . "'");
                if($res && $res->num_rows > 0) {

                }
                else {
                    $ch = curl_init('http://172.16.0.76/Test/EseddApi/GlobalCatalogue/GetGKObjectByUID?uid=' . $obj->UID);
                    //curl_setopt($ch, CURLOPT_HEADER, true);
                    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Token: $tok"));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $res = curl_exec($ch);
                    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    if($status_code == 200) {
                        $obj = json_decode($res);

                        $date = date("Y-m-d H:i:s");
                        $insres = mysqli_query($conn,
                            "INSERT INTO users (`name`, `role_id`, `fname`, `mname`, `lname`, `phone`, `email`, `room`, `mobile_phone`, created_at, updated_at) 
                                    VALUES ('" . $obj->Name . "', 2, '" . $obj->FirstName . "', '" . $obj->Patronymic . "', '" . $obj->Surname . "', '" . $obj->Phone . "',
                                            '" . $obj->EMail . "', '" . $obj->Address . "', '" . $obj->MobilePhone . "', '" . $date . "', '" . $date . "')");
                        if(!$insres) {
                            printf("Error: %s\n", mysqli_error($conn));
                        }
                        $user_id = mysqli_insert_id($conn);
                        mysqli_query($conn, "INSERT INTO user_keys (`key`, `user_id`, `parent_key`) VALUES ('" . $obj->UID . "', $user_id, '" . $obj->Parent . "')");

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

?>