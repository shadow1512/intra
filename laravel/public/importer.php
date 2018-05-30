<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 30.05.2018
 * Time: 15:20
 */
$conn = mysqli_connect("localhost", "phpmyadmin", "dhgstef", "intradb") or die("No DB connection");
$conn->set_charset("utf8");
$tok = null;

if(isset($argv[1]) && ($argv[1] == 'struct')) {
    createDepartmentStructure($conn);
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
                $res    =   mysqli_query($conn, "SELECT dep_id FROM dep_keys WHERE key='" . $obj->UID . "'");
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

            }
        }

    }
}

function createDepartmentStructure($conn) {
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

?>