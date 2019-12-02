<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Chemistry Awards System - University of Michigan</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META content="" name=KEYWORDS>
<META content="" name=description>
<link rel="stylesheet" href="../eebstyle.css">
</head>

<body>
<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/../support/awards_dbConnect.inc');
require_once('nav.php');

?>

<div align="center">
<?
$award_id = check_input($conn, $_REQUEST['award_id']);
$error = $_REQUEST[error];
if($error != ''){
      echo "<table><TR><TD align=center><span style=color:red><b>ERRORS!</b></span><TR><TD><span style=color:red>$error</span></table>";
  }

echo "<form name='form' method='post' action='faculty_awards_status.php'>";

//$sqla = "SELECT `id`, `Award_Name` FROM `awards_descr` ORDER BY FIELD(due_month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
$sqla = "SELECT `id`, `Award_Name` FROM `awards_descr` ORDER BY `Award_Name`";
$result = mysqli_query($conn, $sqla) or die("Query failed :".mysqli_error($conn));

echo "<br>Awards: ";
    echo "<select name='award_id'>";
        echo "<option select value=''> - choose  -</option>";
        while ($adata = mysqli_fetch_array($result, MYSQLI_BOTH))
        {
           echo "<option";
           if ($adata[id] == $award_id) { echo " selected"; }
           echo " value='$adata[id]'>$adata[Award_Name]</option>";
        }
    echo "</select><br><br>";

echo "<input type='submit' name='choose' value='Choose'>";

echo "</form>";
echo "<div align='center'><img src='../images/linecalendarpopup500.jpg'></div><Br>";



if (isset($_REQUEST[choose]) OR (check_input($conn, $_REQUEST['award_id']) !== "")) {
     $award_id = check_input($conn, $_REQUEST['award_id']);

if ($award_id !== "") {
$sqlf = "SELECT faculty_awards.id as dataid, faculty_awards.`uniqname` as uniqname, faculty_awards.faculty_id AS faculty_id, faculty.Name, award_status.`status`, `year`, `comment` FROM `faculty_awards`JOIN faculty ON faculty_awards.faculty_id = faculty.id, award_status WHERE faculty_awards.status = award_status.id AND award_id = $award_id ORDER BY year, award_status.status";
//echo $sqlf;
$resultf = mysqli_query($conn, $sqlf) or die("Query failed :".mysqli_error($conn)); 

$total=mysqli_num_rows($resultf);
 
echo "Who got it<br><br>";
echo "<table>";
echo "<th>Name<th>year<th>status<th>Comments<th>&nbsp;</th><th>&nbsp;</th></tr>";

echo "<form name='form2' action='add_faculty_to_award.php' method='post'>";
echo ('<td>');
$sqla = "SELECT id, uniqname, Name FROM faculty ORDER BY name ASC";
$resa = mysqli_query($conn, $sqla) or die("Query failed :".mysqli_error($conn));
print("<select name='faculty_id'>");
        print("<option select value='error'> - choose name -</option>");

        WHILE ($applicant_name = mysqli_fetch_array($resa, MYSQLI_BOTH))
        {
               echo "<option";
               echo " value='$applicant_name[id]'>$applicant_name[Name]</option>";
        }
echo "</select>";

echo ('<br><br><input type="text" size="25" maxsize="100" name="nonchemfaculty" value="" placeholder="-or enter a name-"></td>');

echo ('<td><input type="text" size="9" name="year" value=""></td>');
echo ('<td>');
$sqls = "SELECT `id`, `status` FROM `award_status`";
$ress = mysqli_query($conn, $sqls) or die("Query failed :".mysqli_error($conn));
print("<select name='status'>");
        print("<option select value='error'>-choose status-</option>");

        WHILE ($sdata = mysqli_fetch_array($ress, MYSQLI_BOTH))
        {
               echo "<option";
               echo " value='$sdata[id]'>$sdata[status]</option>";
        }
echo "</select>";
echo ('<td><input type="text" size="10" maxsize="200" name="comment" value=""></td>');
echo '<td><input type="hidden" name="award_id" value="' . $award_id . '"></td>';
echo ('<td> <input type="submit" name="submit" value="Add"></td>');
 echo('</form></td>');
while ( $faward = mysqli_fetch_array($resultf, MYSQLI_BOTH) ) 
{
    $dataid = $faward[dataid];
    $status = $faward['status'];
    $uniqname = $faward['uniqname'];
    $faculty_id = $faward['faculty_id'];
    $year = $faward['year'];
           echo "<tr><td>" . $faward['Name']. "</td>";
           echo "<td>" . $year. "</td>";
           echo "<td>" . $status . "</td>";
           echo "<td>" . $faward['comment']. "</td>";

  
    $table = "faculty_awards";
    echo '<td><a href="edit_faculty_award.php?dataid=' . $dataid . '&award_id=' . $award_id . '">Edit</a></td>';
    echo '<td><a href="delete_faculty_award.php?table=' . $table . '&dataid=' . $dataid . '&award_id=' . $award_id . '">Delete</a></td>';
         if ($status == "interested") {
           echo "<form name='form3' action='to_in_process.php' method='post'>";
           echo '<input type="hidden" name="faculty_id" value="' . $faculty_id . '">';
           echo '<input type="hidden" name="uniqname" value="' . $uniqname . '">';
           echo '<input type="hidden" name="dataid" value="' . $dataid . '">';
           echo '<input type="hidden" name="award_id" value="' . $award_id . '">';
           echo '<input type="hidden" name="prog_name" value="faculty_awards_status.php">';
           echo "<td><input type='submit' name='submit' value='inprocess' /></td>";
           echo('</form></td>');
         }
           echo "</td>";


} //while
$sqlnc = "SELECT faculty_awards_notchem.`id` AS id, `name`, `award_id`, `year`, `comment`, award_status.`status` AS status, comment FROM `faculty_awards_notchem`, award_status WHERE faculty_awards_notchem.status = award_status.id AND award_id = $award_id ORDER BY status, year";
$resultnc = mysqli_query($conn, $sqlnc) or die("Query failed :".mysqli_error($conn));

$total=mysqli_num_rows($resultnc);

if ($total !== 0 )  {
echo "<tr><th>Non Chemistry Awards</th>";
    while ( $awardnc = mysqli_fetch_array($resultnc, MYSQLI_BOTH) ) {
       $dataid = $awardnc[id];
       $status = $awardnc['status'];
       $name = $awardnc['name'];
       $year = $awardnc['year'];
       echo "<tr><td>" . $name . "</td>";
           echo "<td>" . $year. "</td>";
           echo "<td>" . $status . "</td>";
           echo "<td>" . $awardnc['comment']. "</td>";


    echo '<td>&nbsp;</td>';
    $table = "faculty_awards_notchem";
    echo '<td><a href="delete_faculty_award.php?table=' . $table . '&dataid=' . $dataid . '&award_id=' . $award_id . '">Delete</a></td>';


   }
}
echo "</table><br>";
}
}
?>
</body>
</html>
