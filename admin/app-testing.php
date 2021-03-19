<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<head>
<title>Chemistry Award  - University of Michigan</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META content="" name=KEYWORDS>
<META content="" name=description>
<link rel="stylesheet" href="../eebstyle.css">
<link rel="shortcut icon" href="favicon.ico">
</head>
<body>
<?php  
require_once('../awards-config.php');
require_once('nav.php');
require_once "../php_mail.inc";
$errorid = $purifier->purify($_REQUEST['errorid']);

// if the recomtext field is empty 
if(isset($_POST['recomtext']) && $_REQUEST['recomtext'] != ""){
// let the spammer think that they got their message through
$recomtext = $purifier->purify($_REQUEST['recomtext']);
echo $recomtext;
   echo "<h1>Thanks</h1>";
exit;
}
if(isset($_POST['submit'])) {

     $recid = 0;
     $recname = "-";
      $replacefile = $purifier->purify($_REQUEST['replacefile']);
      $uniqname = $purifier->purify($_REQUEST['uniqname1']);
  $lettertype = "cv";
  $lettertype1 = "cv";
  if($uniqname =='' ){ $uniqname = $purifier->purify($_REQUEST['uniqname']); }
  if($uniqname =='' ){ $error.="Please select a faculty!<br />"; }

$tmp_name = $_FILES['recfilename']['tmp_name'];
   $type = $_FILES['recfilename']['type'];
   $name = $_FILES['recfilename']['name'];
   $size = $_FILES['recfilename']['size'];
   $file_extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));

  if($name =='')  {
     $error.="Please select a PDF or DOC file!<br />";
  }
     elseif (!($file_extension == "pdf" || $file_extension == "doc" || $file_extension == "docx")){
         $error.="Your file is not a PDF or DOC. Please select a correct file!<br />";
     } //elseif
  else {
    $pdf = 1;
  }

  if($error != ''){
     if($pdf == 1) {
           $error.="Please select a pdf file again! (for security reasons the script can't remember a file name)<br />";
     }
     echo "<table><TR><TD><span style=color:red>$error</span></table>";
  }
  else {
  //if ($replacefile == "yes") {
    // delete the old file
    // $sql = "DELETE FROM faculty_letters WHERE uniqname = '$uniqname' AND type = '$lettertype'";
//echo $sql;
    //$res = mysqli_query($conn, $sql) or die("There was an error replacing file in faculty_letters: ".mysqli_error($conn));
  //}
          // rename and upload the file
     if ($_FILES['recfilename']['error'] === UPLOAD_ERR_OK) {
        // upload ok

        $filename = $lettertype . $recname . $uniqname . "-" . time() . "." . $file_extension;
        $uploadfile = $uploaddir . $filename;
        $upload_date = date("m-d-Y");
    //    $sql = "INSERT faculty_letters (uniqname, rec_id, link, type, upload_date) VALUES('$uniqname', $recid, '$filename', '$lettertype', '$upload_date')";
//echo $sql;
     //   $res = mysqli_query($conn, $sql) or die("There was an error updating faculty_letters: ".mysqli_error($conn));

        if (move_uploaded_file($tmp_name, $uploadfile)) {
           chmod($uploadfile,0644);
               echo "The file has been uploaded.";
               $again = "yes";
           }
        else {
          echo "error uploading file";
          exit;
       }    
  }

}

?>
<input type="hidden" name="uniqname" value="<?php echo $uniqname; ?>" />

<?php $ip = getenv("REMOTE_ADDR"); 
if ($reclastname == "") { $reclastname = $purifier->purify($_REQUEST['reclastname']); }
if ($recfirstname == "") { $recfirstname = $purifier->purify($_REQUEST['recfirstname']); }
if ($errorid == 0) {
?>
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<?php
}
?>
<input type="hidden" name="errorid" value="<?php echo $errorid; ?>" />
<?php
}
?>
<div align="center"><h2>Upload a CV <br><br><h2>
</div></h2>
<form method="post" action="letter.php" enctype="multipart/form-data">
<strong>Select a Faculy: </strong> 
 <?php

$lettertype = "cv";
$lettertype1 = "cv";
if ($again == "yes") {
    $uniqname = "";
    $lettertype = "cv";
    $lettertype1 = "cv";
}
   //     $sql = "SELECT uniqname, Name FROM faculty ORDER BY name ASC";
    //    $result = mysqli_query($conn, $sql) or die("Query failed :".mysqli_error($conn));
        print("<select name='uniqname' id='uniqname'>");
        print("<option select value=''> - choose name -</option>");
        echo "<option value='name-one'>Name One</option>";
        echo "<option value='name-two'>Name Two</option>";
        echo "<option value='name-three'>Name Three</option>";
        echo "<option value='name-four'>Name Four</option>";
        // WHILE ($applicant_name = mysqli_fetch_array($result, MYSQLI_BOTH))
        // {
        //         echo "<option";
        //         if ($applicant_name['uniqname'] == $uniqname) { echo " selected"; }
        //         echo " value='$applicant_name[uniqname]'>$applicant_name[Name]</option>";
        // }
?>
</select>

<br><br>
<img src="../images/box650top.jpg"><div class="box650mid"><div class="pad15and10">
<h3>Upload File</h3>
<Br>
Must be <strong>ONE file</strong> and be in <strong>PDF or DOC format</strong>. Maximum file size is 20 MB.
<br><br>
<b>File:</b> <input type="file" name="recfilename"><br>
</div></div>
<img src="../images/box650btm.jpg">

<br>
<br>
<input type="checkbox" name="replacefile" value="yes"> Check to replace the file<br><br>
<input type="submit" name="submit" value="Submit Form" />

<br>
<br>
<bR><div align="center"><img src="../images/linecalendarpopup500.jpg"></div>
</form>

</body> 
</html>
