<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Chemistry Awards System - University of Michigan</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META content="" name=KEYWORDS>
<META content="" name=description>
<link rel="stylesheet" href="eebstyle.css">
</head>

<body>
<div class="bodypad">
<div align="center"><br>
<div class="facrecbox1"><div class="textalignleft pad15and10">
<div align="center"><br><br><h1>Chemistry Awards<br></h1><br>
<bR><div align="center"><img src="images/linecalendarpopup500.jpg"></div><Br>
<?php
include('awards-config.php');
//$path = '/opt/app-root/src/vendor/phpseclib/phpseclib/phpseclib';
//set_include_path(get_include_path() . PATH_SEPARATOR . $path);
//include('/opt/app-root/src/vendor/phpseclib/phpseclib/phpseclib/Crypt/RSA.php');
// authentication
// require __DIR__ . '/vendor/autoload.php';

// use Jumbojett\OpenIDConnectClient;

// $oidc = new OpenIDConnectClient('https://shibboleth.umich.edu',
//                                 '677abbb6-ba16-4c95-b8a8-84760247592d',
//                                 '7867e424-5bff-44de-8951-1350b4a59a1f');
// $oidc->setCertPath('/opt/app-root/src/um-certs2020.pem');
// $oidc->authenticate();
// $uniqname1 = $oidc->requestUserInfo('preferred_username');

if (is_admin($uniqname1)) {
?>
<form action="admin/app-testing.php">
    <input type="submit" value="Admin Site" />
</form>
<?php
}
?>
<form action="faculty/index.php">
    <input type="submit" value="Faculty Site" />
</form>
</div>
<bR><div align="center"><img src="images/linecalendarpopup500.jpg"></div><Br>

</body>
</html>

