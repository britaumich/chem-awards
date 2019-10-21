<?
require_once('noinject.php');

ini_set('display_errors', 'On');
$conn = mysqli_connect("localhost","appspche_award","lkUb_GUW]w.v") or die("couldn't connect");
mysqli_select_db($conn, "appspche_awards") or die("couldn't get the db:".mysqli_connect_error());
$today = date("Y-m-d");
$today_dt = new DateTime($today);
$current_year = academicYear($today_dt); 
//echo $current_year;
//$report_year = "2016-2017"; 
$report_year = "2018"; 
//$committee_email = "ajmcneil@umich.edu"; 
$committee_email = "chem-awards@umich.edu"; 

// MySQL 5.7 default settings forbid to select a colum that is not in the
// group by. As an immediate solution
// we can remove this constraint in the current MySQL session.
$stmt = prepare('SELECT @@SESSION.sql_mode');
$res = $stmt->execute($conn) or die($stmt->error);
$row = mysqli_fetch_row($res);
$sql_mode_current =  array_shift($row);
//echo "sql_mode_current: ";
//echo $sql_mode_current;

 // remove ONLY_FULL_GROUP_BY from the list (select distinc(id) .... order by created doesn't work othervize)
  $sql_mode_altered = implode(',', array_diff(explode(',', $sql_mode_current), array('ONLY_FULL_GROUP_BY')));
 // remove STRICT_TRANS_TABLES from the list (to make '0000-00-00' possible to insert into date fields)
         
  $sql_mode_altered = implode(',', array_diff(explode(',', $sql_mode_altered), array('STRICT_TRANS_TABLES')));
//echo "sql_mode_current: ";
//echo $sql_mode_altered;
   if ($sql_mode_altered != $sql_mode_current)
  { 
    $stmt = prepare("SET SESSION sql_mode='".$sql_mode_altered."'");
    $stmt->execute($conn) or die($stmt->error);
  } 
////

function check_input($conn, $data)
{
    $data = mysqli_real_escape_string($conn, $data);
//    $data = trim($data);
//    $data = stripslashes($data);
//    $data = htmlspecialchars($data);
    return $data;
}
$other_admins = array('dstpierr', 'brita', 'jmontg','kubarych','katfost', 'ajmcneil', 'deboerm', 'rtkenn', 'ryancb', 'smald', 'matzger', 'mssanfor', 'rsension', 'nszym', 'jpwolfe', 'albfrank', 'garnerb');
function is_admin()
{
        global $other_admins;
//        return array_search($_SERVER['REMOTE_USER'], $other_admins) !== FALSE;
        return array_search($_SERVER['REDIRECT_REMOTE_USER'], $other_admins) !== FALSE;
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
 *
 * @uses htmlspecialchars
 *
 * @param (array)
 * @return (array) the sanitized array
 */
function purica_array ($conn, $data = array()) {
        if (!is_array($data) || !count($data)) {
                return array();
        }
        foreach ($data as $k => $v) {
                if (!is_array($v) && !is_object($v)) {
//                      $data[$k] = htmlspecialchars(trim($v));
                        $data[$k] = mysqli_real_escape_string($conn, $v);
                }
                if (is_array($v)) {
                        $data[$k] = purica_array($v);
                }
        }
        return $data;
}
?>
