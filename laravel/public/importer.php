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
        $tok = $m[1];
    }
}

print $tok;
echo "\r\n";
curl_close($ch);


$ch = curl_init('http://172.16.0.76/Test/EseddApi/GlobalCatalogue/GetGKObjects?uid=null');
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_HEADER, CURLHEADER_UNIFIED);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Token: $tok"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
var_dump($status_code);

var_dump($res);
?>