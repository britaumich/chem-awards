<?php
require __DIR__ . '/vendor/autoload.php';

use Jumbojett\OpenIDConnectClient;

$oidc = new OpenIDConnectClient('https://shibboleth.umich.edu',
                                '677abbb6-ba16-4c95-b8a8-84760247592d',
                                '7867e424-5bff-44de-8951-1350b4a59a1f');
$oidc->setCertPath('/opt/app-root/src/um-certs2020.pem');
$oidc->authenticate();
$uniqname1 = $oidc->requestUserInfo('preferred_username');

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
