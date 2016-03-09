<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
include('studentMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Academic Records';
	$szTitle = 'Exam Registration: Please Pick a Course';
	$szSubSection = 'Exam Register';
	include("studentheader.php");
?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

//get submitted vaules
$course = addslashes($_POST['course']);
$ayear = addslashes($_POST['Ayear']);

//query current Year
$qyear = "SELECT AYear from academicyear WHERE Status = 1";
$dbyear = mysql_query($qyear);
$row_year = mysql_fetch_assoc($dbyear);
$currentYear = $row_year['AYear'];
/*if($currentYear<>$ayear){
echo "You Cannot Register For This Year:".$ayear."<br> Registration Rejected !!";
exit;
}*/

$ayear = 2012;
//get total registered student
$qregistered = "
		SELECT DISTINCT COUNT(course.CourseCode) as Total, 
								course.CourseCode,
									examregister.AYear 						 
		FROM examregister 
			INNER JOIN course ON (examregister.CourseCode = course.CourseCode)
		WHERE (examregister.AYear ='$ayear') AND examregister.CourseCode = '$course'
		GROUP BY course.CourseCode";
$dbregistered = mysql_query($qregistered);
$row_registered = mysql_fetch_assoc($dbregistered);
$totalRegistered = $row_registered['Total'];

//get course capacity
$qcapacity = "SELECT Capacity from course where CourseCode = '$course'";
$dbcapacity = mysql_query($qcapacity);
$row_capacity = mysql_fetch_assoc($dbcapacity);
$capacity = $row_capacity['Capacity'];

 if($totalRegistered < $capacity + 1){
	  $insertSQL = sprintf("INSERT INTO examregister (AYear, Semester, RegNo, CourseCode, Recorder, Checked) 
	  													VALUES (%s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['Ayear'], "text"),
						   GetSQLValueString($_POST['semester'], "text"),
						   GetSQLValueString($_POST['regno'], "text"),
						   GetSQLValueString($_POST['course'], "text"),
						   GetSQLValueString($_POST['user'], "text"),
						   GetSQLValueString($_POST['checked'], "text"));
	
	  mysql_select_db($database_zalongwa, $zalongwa);
	  $Result1 = mysql_query($insertSQL, $zalongwa) or die(mysql_error());
	  echo '<meta http-equiv = "refresh" content ="0; 
							url = studentAcademic.php">';
	}else{
	echo "Registration Not Possible, Reached Course Capacity Limit <br>";
	echo "Choose another Course";
	}
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_ExamOfficerGradeBook = 20;
$pageNum_ExamOfficerGradeBook = 0;
if (isset($_GET['pageNum_ExamOfficerGradeBook'])) {
  $pageNum_ExamOfficerGradeBook = $_GET['pageNum_ExamOfficerGradeBook'];
}
$startRow_ExamOfficerGradeBook = $pageNum_ExamOfficerGradeBook * $maxRows_ExamOfficerGradeBook;
/*
mysql_select_db($database_zalongwa, $zalongwa);
$query_ExamOfficerGradeBook = "SELECT CourseCode, AYear, SemesterID FROM examresult";
$query_limit_ExamOfficerGradeBook = sprintf("%s LIMIT %d, %d", $query_ExamOfficerGradeBook, $startRow_ExamOfficerGradeBook, $maxRows_ExamOfficerGradeBook);
$ExamOfficerGradeBook = mysql_query($query_limit_ExamOfficerGradeBook, $zalongwa) or die(mysql_error());
$row_ExamOfficerGradeBook = mysql_fetch_assoc($ExamOfficerGradeBook);
*/
if (isset($_GET['totalRows_ExamOfficerGradeBook'])) {
  $totalRows_ExamOfficerGradeBook = $_GET['totalRows_ExamOfficerGradeBook'];
} else {
  $all_ExamOfficerGradeBook = mysql_query($query_ExamOfficerGradeBook);
  @$totalRows_ExamOfficerGradeBook = mysql_num_rows(@$all_ExamOfficerGradeBook);
}
$totalPages_ExamOfficerGradeBook = ceil($totalRows_ExamOfficerGradeBook/$maxRows_ExamOfficerGradeBook)-1;

mysql_select_db($database_zalongwa, $zalongwa);
$query_Ayear = "SELECT AYear FROM academicyear ORDER BY AYear DESC";
$Ayear = mysql_query($query_Ayear, $zalongwa) or die(mysql_error());
$row_Ayear = mysql_fetch_assoc($Ayear);
$totalRows_Ayear = mysql_num_rows($Ayear);

mysql_select_db($database_zalongwa, $zalongwa);
$query_semester = "SELECT Description FROM terms ORDER BY Semester ASC";
$semester = mysql_query($query_semester, $zalongwa) or die(mysql_error());
$row_semester = mysql_fetch_assoc($semester);
$totalRows_semester = mysql_num_rows($semester);

mysql_select_db($database_zalongwa, $zalongwa);
$query_course = "SELECT CourseCode, CourseName FROM course ORDER BY CourseCode ASC";
$course = mysql_query($query_course, $zalongwa) or die(mysql_error());
$row_course = mysql_fetch_assoc($course);
$totalRows_course = mysql_num_rows($course);

mysql_select_db($database_zalongwa, $zalongwa);
$query_lecturer = "SELECT RegNo FROM student ORDER BY RegNo ASC";
$lecturer = mysql_query($query_lecturer, $zalongwa) or die(mysql_error());
$row_lecturer = mysql_fetch_assoc($lecturer);
$totalRows_lecturer = mysql_num_rows($lecturer);

$queryString_ExamOfficerGradeBook = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_ExamOfficerGradeBook") == false && 
        stristr($param, "totalRows_ExamOfficerGradeBook") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_ExamOfficerGradeBook = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_ExamOfficerGradeBook = sprintf("&totalRows_ExamOfficerGradeBook=%d%s", $totalRows_ExamOfficerGradeBook, $queryString_ExamOfficerGradeBook);
 
$browser  =  $_SERVER["HTTP_USER_AGENT"];   
$ip  =  $_SERVER["REMOTE_ADDR"];   

$sql="INSERT INTO stats(ip,browser,received,page) VALUES('$ip','$browser',now(),'$username')";   
$result = mysql_query($sql) or die("Siwezi kuingiza data.<br>" . mysql_error());
?> 
<?php
#get values
@$CourseCode = $_GET['CourseCode'];

$RegNo = "KCN/BScN/12/009";

?>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
              <table width="59%"  border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
                <tr>
                  <td width="17%" nowrap><div align="right">Academic Year: </div></td>
                  <td width="16%"><select name="Ayear" id="Ayear">
				  <option value="0">[Select Academic Year]</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_Ayear['AYear']?>"><?php echo $row_Ayear['AYear']?></option>
                    <?php
} while ($row_Ayear = mysql_fetch_assoc($Ayear));
  $rows = mysql_num_rows($Ayear);
  if($rows > 0) {
      mysql_data_seek($Ayear, 0);
	  $row_Ayear = mysql_fetch_assoc($Ayear);
  }
?>
                  </select></td>
                  <td width="15%" nowrap><div align="right">Course Code: </div></td>
                  <td width="52%"><input name="course" type="hidden" id="course" value="<?php echo $CourseCode; ?>"><?php echo $CourseCode; ?></td>
                </tr>
                <tr>
                  <td nowrap><div align="right">Semester:</div></td>
                  <td><select name="semester" id="semester">
				  <option value="0">[Select Semester]</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_semester['Description']?>"><?php echo $row_semester['Description']?></option>
                    <?php
} while ($row_semester = mysql_fetch_assoc($semester));
  $rows = mysql_num_rows($semester);
  if($rows > 0) {
      mysql_data_seek($semester, 0);
	  $row_semester = mysql_fetch_assoc($semester);
  }
?>
                  </select></td>
                  <td nowrap><div align="right">
                    <input name="user" type="hidden" id="user" value="<?php echo $username;?>">
                    <input name="checked" type="hidden" id="checked" value="0">
                    Your RegNo: </div></td>
                  <td><input name="regno" type="hidden" id="regno" value="<?php echo $RegNo; ?>">
                  <?php echo $RegNo; ?></td>
                </tr>
                <tr>
                  <td colspan="4"><div align="center">
                      <input type="submit" name="Submit" value="Save Records">
                  </div></td>
                </tr>
  </table>
                <input type="hidden" name="MM_insert" value="form1">
</form>
        
<?php
@mysql_free_result($ExamOfficerGradeBook);

@mysql_free_result($Ayear);

@mysql_free_result($semester);

@mysql_free_result($course);

@mysql_free_result($lecturer);
mysql_close($zalongwa);
?>