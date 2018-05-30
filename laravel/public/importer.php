<?php
/**
 * Created by PhpStorm.
 * User: viach
 * Date: 30.05.2018
 * Time: 15:20
 */

$tok = null;

$ch = curl_init('http://172.16.0.76/Test/EseddApi/Authenticate/GetToken/');
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
    $headers = explode("\r\n\r\n", $header);
    foreach($headers as $parts) {
        $part = explode(":", $parts);
        if($part[0] == "Token") {
            $tok = trim($part[1]);
        }
    }
}

curl_close($ch);

var_dump($tok);

?>