<?php
require 'vendor/autoload.php';

$issuer = 'http://connect.openathens.net';
$cid = 'YOUR_OPENATHENS_CLIENT_ID';
$secret = 'YOUR_OPENATHENS_CLIENT_SECRET';
$oidc = new Jumbojett\OpenIDConnectClient($issuer, $cid, $secret);

$oidc->authenticate();
$oidc->requestUserInfo('sub');

$session = array();
foreach($oidc as $key=> $value) {
    if(is_array($value)){
            $v = implode(', ', $value);
    }else{
            $v = $value;
    }
    $session[$key] = $v;
}


session_start();
$_SESSION['attributes'] = $session;

header("Location: ./attributes.php");

?>
