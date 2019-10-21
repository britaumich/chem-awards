<?php
require_once "../dbConnect.inc";
$award_id = check_input($conn, $_REQUEST['award_id']);
$table = check_input($conn, $_REQUEST['table']);
$keyword_search = check_input($conn, $_REQUEST['keyword_search']);


if (isset($_REQUEST['dataid']) && is_numeric($_REQUEST['dataid'])) {
   
    $dataid = check_input($conn, $_REQUEST['dataid']);
    $result = mysqli_query($conn, "DELETE FROM $table WHERE id =$dataid")  or die(mysqli_error($conn));
    header("Location: add_nominations.php?award_id=$award_id&keyword_search=$keyword_search");

}

?>
