<?php
require __DIR__ . '/vendor/autoload.php';

use Jumbojett\OpenIDConnectClient;

include('awards-config.php');

$oidc = new Jumbojett\OpenIDConnectClient($issuer, $cid, $secret);
$oidc->setCertPath('/opt/app-root/src/um-certs2020.pem');
$oidc->authenticate();
$oidc->requestUserInfo('sub');
$uniqname = $oidc->getVerifiedClaims('sub');
$userinfo = $oidc->requestUserInfo();
echo "<br>uniqname: ";
echo $uniqname;

?>
