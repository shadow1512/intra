<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 30.05.2018
 * Time: 15:20
 */

exec("curl -d \"ствкан\" http://localhost:8080/candidates", $out);

/*$ch = curl_init('http://localhost:8080/candidates');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$res = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if($status_code == 200) {
    var_dump($res);
}

curl_close($ch);*/
var_dump($out);
?>