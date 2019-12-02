<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/awards_dbConnect.inc');
$award_id = check_input($conn, $_REQUEST['award_id']);
$table = check_input($conn, $_REQUEST['table']);


if (isset($_REQUEST['dataid']) && is_numeric($_REQUEST['dataid'])) {
   
    $dataid = check_input($conn, $_REQUEST['dataid']);
    $result = mysqli_query($conn, "DELETE FROM $table WHERE id =$dataid")  or die(mysqli_error($conn));
    header("Location: faculty_awards_status.php?award_id=$award_id");

}

?>
