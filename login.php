<?php
require __DIR__ . '/vendor/autoload.php';

use Jumbojett\OpenIDConnectClient;

include('awards-config.php');
include('ldap.inc');

$issuer = 'https://shibboleth.umich.edu';
$cid = '677abbb6-ba16-4c95-b8a8-84760247592d';
$secret = '7867e424-5bff-44de-8951-1350b4a59a1f';

$oidc = new Jumbojett\OpenIDConnectClient($issuer, $cid, $secret);
$oidc->addScope(array('openid profile email edumember'));
$oidc->setCertPath('/opt/app-root/src/um-certs2020.pem');
$oidc->authenticate();
$oidc->requestUserInfo('sub');
$uniqname = $oidc->getVerifiedClaims('sub');
echo "<br>uniqname: ";
echo $uniqname;

$person = ldap_person($uniqname);
echo "<br><br>person";
var_export($person);

echo "<br><br>oidc: <br>";
var_export($oidc);

?>
