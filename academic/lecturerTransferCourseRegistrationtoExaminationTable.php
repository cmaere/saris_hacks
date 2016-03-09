<?php require_once('../Connections/zalongwa.php'); ?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
 do { 
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "ExamResults")) {
  $insertSQL = sprintf("INSERT INTO examresult (RegNo, ExamNo, CourseCode, Coursework, Exam, AYear, checked) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['RegNo'], "text"),
                       GetSQLValueString($_POST['txtExamNo'], "text"),
                       GetSQLValueString($_POST['coursecose'], "text"),
                       GetSQLValueString($_POST['txtCWK'], "int"),
                       GetSQLValueString($_POST['txtExam'], "int"),
                       GetSQLValueString($_POST['AYear'], "text"),
                       GetSQLValueString(isset($_POST['checked']) ? "true" : "", "defined","1","0"));

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($insertSQL, $zalongwa) or die(mysql_error());
}
} while ($row_coursecandidate = mysql_fetch_assoc($coursecandidate));

mysql_select_db($database_zalongwa, $zalongwa);
$query_coursecandidate = "SELECT student.Name, CourseCandidate.RegNo, CourseCandidate.CourseCode, CourseCandidate.AYear FROM student INNER JOIN CourseCandidate ON student.RegNo = CourseCandidate.RegNo WHERE CourseCandidate.CourseCode = 'EV 200'";
$coursecandidate = mysql_query($query_coursecandidate, $zalongwa) or die(mysql_error());
$row_coursecandidate = mysql_fetch_assoc($coursecandidate);
$totalRows_coursecandidate = mysql_num_rows($coursecandidate);
?>
<form action="<?php echo $editFormAction; ?>" method="POST" name="ExamResults" id="ExamResults">
Click Save to Save Examination Results:<input name="save" type="submit" value="Save Results">
<table width="200" border="1">
    <tr>
     <td>CHECKED</td>
	  <td>YEAR</td>
      <td>COURSE</td>
      <td>NAME</td>
      <td>REGNO</td>
      <td>EXAMNO</td>
      <td>CWK</td>
      <td>EXAM</td>
    </tr>
    <?php do { ?>
    <tr>
      <td><div align="center">
          <input name="checked" type="checkbox" id="checked" value="checkbox">
              </div></td>
      <td><?php echo $row_coursecandidate['AYear']; ?>
        <input name="AYear" type="hidden" id="AYear"value="<?php echo $row_coursecandidate['AYear']; ?>"></td>
      <td><?php echo $row_coursecandidate['CourseCode']; ?>
        <input name="coursecose" type="hidden" id="coursecose"value="<?php echo $row_coursecandidate['CourseCode']; ?>"> </td>
      <td><?php echo $row_coursecandidate['Name']; ?></td>
      <td><?php echo $row_coursecandidate['RegNo']; ?>
        <input name="RegNo" type="hidden" id="RegNo"value="<?php echo $row_coursecandidate['RegNo']; ?>"></td>
      <td><input name="txtExamNo" type="text" id="txtExamNo"></td>
      <td><input name="txtCWK" type="text" id="txtCWK" size="6"></td>
      <td><input name="txtExam" type="text" id="txtExam" size="6"></td>
    </tr>
    <?php } while ($row_coursecandidate = mysql_fetch_assoc($coursecandidate)); ?>
</table>
<input type="hidden" name="MM_insert" value="ExamResults">
</form>
<?php
mysql_free_result($coursecandidate);
?>
