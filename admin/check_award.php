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
$uniqname1 = $_SERVER['REDIRECT_REMOTE_USER'];
$uniqname = $purifier->purify($_REQUEST['uniqname']);
if ($uniqname == "") {
  $uniqname = $uniqname1;
}
//echo $uniqname;
if (is_admin($uniqname1)) {
echo "<form name='form' method='post' action='check_award.php'>";

//   echo "admin";
$sqlf = "SELECT id, uniqname, Name FROM faculty ORDER BY name ASC";
$resf = mysqli_query($conn, $sqlf) or die("Query failed :".mysqli_connect_error());
echo "<table><tr><td>";
echo "Select a faculty: ";
print("<select name='uniqname'>");
        print("<option select value='error'> - choose name -</option>");

        WHILE ($applicant_name = mysqli_fetch_array($resf, MYSQLI_BOTH))
        {
               echo "<option";
               if ($applicant_name[uniqname] == $uniqname) { echo " selected"; }
               echo " value='$applicant_name[uniqname]'>$applicant_name[Name]</option>";
        }
echo "</select>";
echo "<td>";
$sqlm = "SELECT DISTINCT due_month, if ( month(str_to_date(left(due_month, 3),'%b')) > 8, month(str_to_date(left(due_month, 3),'%b')), month(str_to_date(left(due_month, 3),'%b'))+12) AS num FROM awards_descr ORDER BY FIELD(due_month, 'September', 'October', 'November', 'December', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August')";
    $resultm = mysqli_query($conn, $sqlm) or die("Query failed :".mysqli_connect_error());
echo "Award Month: ";
$month = $purifier->purify($_REQUEST['month']);
if ($month == "" ) { $month = "%";}
    $sqlm ="SELECT DISTINCT due_month FROM `awards_descr` order by month(str_to_date(left(due_month, 3),'%b'))";
      $resm = mysqli_query($conn, $sqlm) or die("There was an error getting min date: ".mysqli_error($conn));
echo "<select name='month'>";
echo "<option select value='%'> - pick all  -</option>";
while ($months = mysqli_fetch_array($resm, MYSQLI_BOTH)) {
           echo "<option";
           if ($months[due_month] == $month) { echo " selected"; }
           echo " value='$months[due_month]'>$months[due_month]</option>";
}
echo "</select>";


echo "<td>Clusters: ";

$clustersids = array();
//    $clustersids = purica_array($conn, $_REQUEST[cluster_check]);
    $clustersids = $purifier->purify($_REQUEST[cluster_check]);
    if ($clustersids == NULL) {$clustersids = array();}
//echo '<pre>list id'; var_export($clustersids); echo '</pre>';

    $sql = "SELECT id, name FROM clusters ORDER BY id";
    $result = mysqli_query($conn, $sql) or die("Query failed :".mysqli_error($conn));
if (mysqli_num_rows($result) != 0) {
     while ( $clusters = mysqli_fetch_array($result, MYSQLI_BOTH) ) {
        $cname = $clusters[name];
        if ($cname == "None")  {
           echo "<td>";
        }
           echo "<input type='checkbox' name='cluster_check[";
           echo $clusters[id];
           echo "]' ";
           echo "value='$clusters[id]'";
           if (in_array($clusters[id], $clustersids)) {echo " checked"; }
           if ($cname == "None")  {
                echo ">all fields of chemistry";
           }
           else {
                echo ">$cname";
           }
     }
}
echo "</td></tr><tr><td colspan='4'>Search by Keywords (in Award Name and Awarded By) ";
$keyword_search = $purifier->purify($_REQUEST['keyword_search']);
echo '<input type="text" name="keyword_search" size = "50" placeholder="-- keywords, separated by commas --" value="' . $keyword_search . '" >';

echo "</td></tr></table><br>";
echo "<input type='submit' name='choose' value='Search'>";

echo "</form>";
echo "<div align='center'><img src='../images/linecalendarpopup500.jpg'></div><Br>";

}
if (isset($_REQUEST[submit])) {

$uniqname = $purifier->purify($_REQUEST['uniqname']);
$fac_id = $purifier->purify($_REQUEST['fac_id']);

     $id = $purifier->purify($_REQUEST['id']);
     $awardid = array();
     $awardid = $purifier->purify($_REQUEST[awardid]);
//echo '<pre>'; var_export($awardid); echo '</pre>';
  if (!is_null($awardid)) {

     $sql =  "INSERT INTO faculty_awards (faculty_id, uniqname, award_id, status, year) VALUES ";
     foreach ($awardid as $award_id) {
          $sql .= "(" . $fac_id . ", '" . $uniqname . "', " . $award_id . ", 1, '" . $current_year . "'), ";
     }
     $sql = substr($sql, 0, -2);
     $sql .= " ON DUPLICATE KEY UPDATE uniqname = '" . $uniqname . "'";
//echo $sql;
     $res = mysqli_query($conn, $sql) or die("There was an error updating faculty_award: ".mysqli_connect_error());
 }
 else {
   echo "no awards were selected";
 } 
}
if (isset($_REQUEST[choose]) OR ($uniqname !== "")) {
echo "<form name='form2' method='post' action='check_award.php'>";
if ($uniqname == "") {
$uniqname = $purifier->purify($_REQUEST['uniqname']);
    if ($uniqname == "") {
//         $uniqname = $_SERVER['REMOTE_USER'];
         $uniqname = $_SERVER['REDIRECT_REMOTE_USER'];
    } 
}
else {

$is_eligible = $purifier->purify($_REQUEST['is_eligible']);
if ($is_eligible == "") {
   $sqle = "SELECT eligibility_id FROM eligibility JOIN faculty ON rank = rank_id  WHERE faculty.uniqname = '$uniqname'";
   $resulte = mysqli_query($conn, $sqle) or die("Query failed :".mysqli_connect_error());
   $rows= array();
   while ($row = mysqli_fetch_array($resulte, MYSQLI_BOTH)) {
        $rows[] = $row[eligibility_id];
   }
   $sqlp = "select Year_PhD from  faculty WHERE faculty.uniqname = '$uniqname'";
   $resultp = mysqli_query($conn, $sqlp) or die("Query failed :".mysqli_connect_error());

   $res = mysqli_fetch_array($resultp, MYSQLI_BOTH);
   $Year_PhD = $res[Year_PhD];
   if ((date("Y") - $Year_PhD) < 11 ) {
           $rows[] = "8";
   };
   if ((date("Y") - $Year_PhD) < 6 ) {
           $rows[] = "7";
   };
//   echo '<pre>'; var_export($rows); echo '</pre>';
   $rowstr = implode(', ', $rows);
   $is_eligible = "IN ($rowstr)";
}
else {
   $is_eligible = " = '$is_eligible'";
}

   $cluster_check = array();
    $cluster_check = $purifier->purify($_REQUEST[cluster_check]);
    if (!empty($_REQUEST['cluster_check'])) {
        $clusterlist = implode(", ", $cluster_check);

      $where = " JOIN award_cluster ON awards_descr.id = award_cluster.award_id WHERE  award_cluster.cluster_id IN (" . $clusterlist . ") AND who_is_eligible $is_eligible AND due_month LIKE '%$month%'";

}
else {

       $where = " WHERE who_is_eligible $is_eligible AND due_month LIKE '%$month%'";
}
 
   if ($keyword_search !== "") {
           $where .= " AND (";
         foreach (explode(",", $keyword_search) as $key) {
           $key = trim($key);
           $where .= " (Award_Name LIKE '%" . $key . "%') OR (Awarded_By LIKE '%" . $key . "%') OR";
         }
         $where = substr($where, 0, -2);
         $where .= ")";
     }

     $sqlsearch = "SELECT `id`, `type`, `Award_Name`, `Awarded_By`, `Link_to_Website`, `Description`, `eligibility`, who_is_eligible, `comments`, due_month, due_day FROM awards_descr $where  ORDER BY month(str_to_date(left(due_month, 3),'%b'))";
//echo $sqlsearch;
//echo "<br>";

 $result = mysqli_query($conn, $sqlsearch) or die("There was an error searching for " . $uniqname); 
$total=mysqli_num_rows($result);

 if( $total == 0 )
 {
    echo "There were no results found. </Br>";
 }//

$sqlfac = "SELECT faculty.id AS fac_id, Name, rank.rank AS rank FROM faculty, rank WHERE faculty.rank = rank.id AND uniqname = '$uniqname'";
$resfac = mysqli_query($conn, $sqlfac) or die("There was an error updating faculty_letters: ".mysqli_connect_error());
$fdata = mysqli_fetch_array($resfac, MYSQLI_BOTH); 
$name = $fdata['Name']; 
$rank = $fdata['rank']; 
$fac_id = $fdata['fac_id']; 

echo $name . ", " . $rank; 
echo "<br>";
echo $total . ' awards eligible for "' . $rank; 
echo "<br>";
echo "Mark all awards to be considered and then click SUBMIT at the bottom."; 

$sqlawards = "SELECT award_id FROM faculty_awards WHERE faculty_id = '$fac_id' AND (status = 1 OR status = 10)";
//echo $sqlawards;
$resawards = mysqli_query($conn, $sqlawards) or die("There was an error: ".mysqli_connect_error());
$awids = array();
while ($aw = mysqli_fetch_array ($resawards, MYSQLI_BOTH)) {
    $awids [] = $aw[award_id];
}
//echo '<pre>'; var_export($awids); echo '</pre>';
echo "<Br><Br>";
 //show table headers for results
 
echo ("<table>
<tr>
	<th>Award Details</th>
	<th>Award Name (external link)</a></th>
	<th>Awarded By</a></th>
	<th>Brief Description</th>
	<th>Considered?</th>


</tr>
");

$search_id_list = array();
while ( $idata = mysqli_fetch_array($result, MYSQLI_BOTH) ) {
        $search_id_list[] = $idata[id];
}
$arr = serialize($search_id_list);
$result = mysqli_query($conn, $sqlsearch) or die("There was an error: ".mysqli_error($conn));

while ( $adata = mysqli_fetch_array($result, MYSQLI_BOTH) ) 
{
	
     $id = $adata[id]; 
	echo ("<tr>");
        echo "<td><a class='openbutton' href='award.php?search_id_list=$arr&award_id=$id'>Open</td>";

		
//		echo"<td>$adata[id]</td>";
		echo "<td><a href='$adata[Link_to_Website]' target='_blank'>$adata[Award_Name]</td>";
		echo "<td>$adata[Awarded_By]</td>";
//echo '<pre>'; var_export($awids); echo '</pre>';
?>
<td>
<input type="hidden" id="example_text"  value="<? echo $adata[eligibility]; ?>" /><br />
<input type="button" value="Open" onclick="example_popup('one')" />
</td>

<td>
<input type='checkbox' name='awardid[<? echo $id; ?>]' value='
<? echo $id . "'"; 
 if (in_array($id, $awids)) {echo " checked"; }
 echo ">";
?>
</td>


<?	
	print ("</tr>");
} //while
?>

 </table> 
<input type="hidden" name="uniqname" value="<?php echo $uniqname; ?>" />
<input type="hidden" name="fac_id" value="<?php echo $fac_id; ?>" />
<input type="hidden" name="cluster" value="<?php echo $cluster; ?>" />
<input type="hidden" name="month" value="<?php echo $month; ?>" />


       <br><input type="submit" name="submit" value="Submit">
<br><br><div align="center"><img src="../images/linecalendarpopup500.jpg"></div><Br>

        </form>
<?
}   // if $uniqname = ""
}  // if choose
?>
</body>
<script type="text/javascript">
function example_popup() {
    var w = window.open('', '', 'width=600,height=400,resizeable,scrollbars');
    w.document.write(document.getElementById('example_text').value);
    w.document.close(); // needed for chrome and safari
}
</script>

