<?php
require_once "../dbConnect.inc";
$dataid = check_input($conn, $_REQUEST['dataid']);
$prog_name = check_input($conn, $_REQUEST['prog_name']);
        $sql = "DELETE FROM faculty_awards WHERE id = '$dataid'";
        $result = mysqli_query($conn, $sql);
        if (!($result)) {
                  $error = urlencode(mysqli_error($conn));
            $back = $prog_name . "?&error=" . $error;
            header("Location: $back");  

        }
        else {
            $back = $prog_name;
            header("Location: $back");  

        }

?>
