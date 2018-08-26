<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 30.05.2018
 * Time: 15:20
 */

//exec("curl -d \"ствкан\" http://localhost:8080/candidates", $out);

$ch = curl_init();
$url = "fix";
if(isset($argv[1])) {
    $url = $argv[1];
}
$field = "дериво";
if(isset($argv[2])) {
    $field = $argv[2];
}
curl_setopt($ch, CURLOPT_URL, 'http://localhost/' . $url);
curl_setopt($ch, CURLOPT_PORT, 8080);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, array("text"=>$field));
$res = curl_exec($ch);

var_dump($res);
curl_close($ch);
//var_dump($out);
?>