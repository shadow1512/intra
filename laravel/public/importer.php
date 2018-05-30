<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 30.05.2018
 * Time: 15:20
 */

$tok = null;

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
    $conn = mysqli_connect("localhost", "root", "fH24031958", "intradb") or die("No DB connection");
    $conn->set_charset("utf8");

    foreach($tree as $obj) {

        if($obj->Active === true) {

            if($obj->ExecutiveType == 0) {
                $res    =   mysqli_query($conn, "SELECT id FROM dep_keys WHERE key='" . $obj->UID . "'");
                if($res && $res->num_rows > 0) {

                }
                else {
                    $date = date("Y-m-d H:i:s");
                    mysqli_query($conn, "INSERT INTO deps (name, created_at, updated_at) VALUES ('" . $obj->Name . "', '" . $date . "', '" . $date . "')");
                    $dep_id = mysqli_insert_id($conn);
                    mysqli_query($conn, "INSERT INTO deps_keys (dep_id, key) VALUES ($dep_id, '" . $obj->UID . "')");
                }
            }
            else {

            }
        }

    }
}


?>