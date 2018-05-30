<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 30.05.2018
 * Time: 15:20
 */

$ch = curl_init('http://172.16.0.76/Test/EseddApi/Authenticate/GetToken/');
//curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, 'integra:Att3r0D0min4tu5');
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
var_dump($status_code);
var_dump($res);
curl_close($ch);

?>