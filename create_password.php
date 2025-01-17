<?php 
$string = "123456";
$output=FALSE;
$key=hash('sha256', SECRET_KEY);
$iv=substr(hash('sha256', SECRET_IV),0,16);
$output=openssl_encrypt($string, METHOD, $key, 0, $iv);
$output=base64_encode($output);
echo $output;