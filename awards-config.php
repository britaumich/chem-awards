<?php
require_once('noinject.php');
require_once('library/HTMLPurifier.auto.php');
$purifier = new HTMLPurifier();
global $purifier;

date_default_timezone_set('America/Detroit');
$today = date("Y-m-d");
$today_dt = new DateTime($today);
$current_year = academicYear($today_dt);
$report_year = "2019";
$committee_email = "brita@umich.edu";
//$committee_email = "chem-awards@umich.edu";
$uploaddir = '/home/appdevch/upload/awards-files/';
global $uploaddir;

//connect to the database
ini_set('display_errors', 'On');
//$server = getenv('db_host', true) ?: getenv('db_host');
//$server = getenv('db_hostname', true) ?: getenv('db_hostname');
$server = '172.30.85.217'
$user = getenv('db_user', true) ?: getenv('db_user');
$pass = getenv('db_password', true) ?: getenv('db_password');
$database = getenv('db_name', true) ?: getenv('db_name');

echo $user;
echo ("<br>");
echo $server;
echo ("<br>");
echo $database;
echo ("<br>");
//exit;

$conn = mysqli_connect($server, $user, $pass) or die("couldn't connect");
mysqli_select_db($conn, $database) or die("couldn't get the db:".mysqli_connect_error());
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$other_admins = array('rsmoke', 'brita');
function is_admin($uniqname)
{
        global $other_admins;
        return array_search($uniqname, $other_admins) !== FALSE;
}
if(isset($_SERVER['REMOTE_USER'])){
    $uniqname1 = $_SERVER['REMOTE_USER'];
    $uniqname = $_SERVER['REMOTE_USER'];
}
else {
     $uniqname1 = 'brita';
     $uniqname = 'brita';
}
function academicYear(DateTime $userDate) {
    $currentYear = $userDate->format('Y');
    $cutoff = new DateTime($userDate->format('Y') . '/06/31 23:59:59');
    if ($userDate < $cutoff) {
        return ($currentYear-1) . '-' . $currentYear;
    }
    return $currentYear . '-' . ($currentYear+1);
}
/**
 * Sanitize a multidimensional array
 * @uses htmlspecialchars
 * @param (array)
 * @return (array) the sanitized array
 */
function purica_array ($conn, $data = array()) {
        if (!is_array($data) || !count($data)) {
                return array();
        }
        foreach ($data as $k => $v) {
                if (!is_array($v) && !is_object($v)) {
                        $data[$k] = mysqli_real_escape_string($conn, $v);
                }
                if (is_array($v)) {
                        $data[$k] = purica_array($conn, $v);
                }
        }
        return $data;
}
?>

