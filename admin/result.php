<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/awards_dbConnect.inc');
$award_id = check_input($conn, $_REQUEST['award_id']);
$year = check_input($conn, $_REQUEST['year']);
$resultyear = check_input($conn, $_REQUEST['resultyear']);
$uniqname = check_input($conn, $_REQUEST['uniqname']);
$faculty_id = check_input($conn, $_REQUEST['faculty_id']);
if (isset($_REQUEST['submit'])) {
 $st = check_input($conn, $_REQUEST['submit']);
 if ($st == 'Received') { $status = 5; }
 if ($st == 'Declined') { $status = 3; }
 
}
if (isset($_REQUEST['award_id'])) {
     $sql = "UPDATE `faculty_awards` SET status = '$status', year = '$resultyear' WHERE faculty_id = $faculty_id AND award_id = $award_id AND year = '$year' AND status = '4'";
//echo $sql;
    $result = mysqli_query($conn, $sql);
    if (!($result)) {
      $error = urlencode(mysqli_error($conn));
     header("Location: nominations.php?error=$error");
    }
    else {
      $sql = "UPDATE award_progress SET got_result = IF($status = 5, 'received', 'declined') WHERE uniqname = '$uniqname' AND award_id = $award_id AND year = '$year' AND submitted = 'yes'";
      $result = mysqli_query($conn, $sql);
    if (!($result)) {
      $error = urlencode(mysqli_error($conn));
     header("Location: nominations.php?error=$error");
    }
        // once saved, redirect back to the view page
     header("Location: nominations.php");
    }
 }

?>
