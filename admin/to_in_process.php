<?php
require_once "../dbConnect.inc";
$uniqname = check_input($conn, $_REQUEST['uniqname']);
$dataid = check_input($conn, $_REQUEST['dataid']);
$year = check_input($conn, $_REQUEST['year']);
$faculty_id = check_input($conn, $_REQUEST['faculty_id']);
$prog_name = check_input($conn, $_REQUEST['prog_name']);
if ($uniqname == '') {
   $sqlu = "SELECT uniqname from faculty WHERE id = $faculty_id";
   $resultu = mysqli_query($conn, $sqlu) or die("Query failed :".mysqli_error($conn));
   $udata = mysqli_fetch_array($resultu, MYSQLI_BOTH);
   $uniqname = $udata['uniqname'];
}
$award_id = check_input($conn, $_REQUEST['award_id']);
   $sql =  "INSERT INTO award_progress (uniqname, award_id, year) VALUES ('$uniqname', $award_id, '$year')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $sql = "UPDATE faculty_awards SET status = '10' WHERE id = '$dataid'";
        $result = mysqli_query($conn, $sql);
        if (!($result)) {
                  $error = urlencode(mysqli_error($conn));
            $back = $prog_name . "?award_id=" . $award_id . "&error=" . $error;;
            header("Location: $back");  

        }
        else {
            $back = $prog_name . "?award_id=" . $award_id;
            header("Location: $back");  

        }
    }
    else {
      $error = mysqli_error($conn);
      $back = $prog_name . "?award_id=" . $award_id . "&error=" . $error;
            header("Location: $back");  
  }

?>
