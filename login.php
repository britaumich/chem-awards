<?php
require __DIR__ . '/vendor/autoload.php';

use Jumbojett\OpenIDConnectClient;

include('awards-config.php');
include('ldap.inc');

$oidc = new Jumbojett\OpenIDConnectClient($issuer, $cid, $secret);
$oidc->setCertPath('/opt/app-root/src/um-certs2020.pem');
$oidc->authenticate();
$oidc->requestUserInfo('sub');
$uniqname = $oidc->getVerifiedClaims('sub');
echo "<br>uniqname: ";
echo $uniqname;

$person = ldap_person($uniqname);
echo "<br><br>person";
var_export($person);

?>
