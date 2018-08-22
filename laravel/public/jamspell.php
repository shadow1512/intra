<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 30.05.2018
 * Time: 15:20
 */

//exec("curl -d \"ствкан\" http://localhost:8080/candidates", $out);

$ch = curl_init('http://localhost/candidates');
curl_setopt($ch, CURLOPT_PORT, 8080);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$res = curl_exec($ch);
var_dump($res);
curl_close($ch);
//var_dump($out);
?>