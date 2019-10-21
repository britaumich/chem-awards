<?php
require_once "../dbConnect.inc";
$award_id = check_input($conn, $_REQUEST['award_id']);
$fac_id = check_input($conn, $_REQUEST['faculty_id']);
$nonchemfaculty = check_input($conn, $_REQUEST['nonchemfaculty']);
$status = check_input($conn, $_REQUEST['status']);
$year = check_input($conn, $_REQUEST['year']);
$comment = check_input($conn, $_REQUEST['comment']);
$keyword_search = check_input($conn, $_REQUEST['keyword_search']);

if (isset($_REQUEST['award_id'])) {
   if (($status == 'error') OR (!($nonchemfaculty == '' XOR $fac_id == 'error'))) {
       // generate error message
       $error = 'ERROR: status or faculty name is empty!';
    // if either field is blank, display the form again
     header("Location: add_nominations.php?error=$error&award_id=$award_id&keyword_search=$keyword_search");
 }
 else {
   if ($fac_id !== 'error') {
     $sql = "INSERT INTO `faculty_awards`(`faculty_id`, `award_id`, `status`, `year`, `comment`) VALUES ($fac_id, $award_id, '$status', '$year', '$comment')";
   }
   else {
     $sql = "INSERT INTO `faculty_awards_notchem`(`name`, `award_id`, `status`, `year`, `comment`) VALUES ('$nonchemfaculty', $award_id, '$status', '$year', '$comment')";
   }
//echo $sql;
//exit;
    $result = mysqli_query($conn, $sql);
    if (!($result)) {
      $error = urlencode(mysqli_error($conn));
     header("Location: add_nominations.php?error=$error&award_id=$award_id&keyword_search=$keyword_search");
    }
    else {
        // once saved, redirect back to the view page
     header("Location: add_nominations.php?award_id=$award_id&keyword_search=$keyword_search");
    }
 }
}










?>
