<?php
require_once "../dbConnect.inc";


if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {

   
    $id = check_input($conn, $_REQUEST['id']);
    $result = mysqli_query($conn, "DELETE FROM faculty_letters WHERE id =$id")  or die(mysqli_error($conn));

     header("Location: faculty.php");  
}

?>
