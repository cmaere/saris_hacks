<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Administration';
	$szSubSection = 'Exam Register';
	$szTitle = 'Student Exam Register';
	include('lecturerheader.php');
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
  $insertSQL = sprintf("INSERT INTO examresult (RegNo, CourseCode, AYear, SemesterID, user, checked) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['lecturer'], "text"),
                       GetSQLValueString($_POST['course'], "text"),
                       GetSQLValueString($_POST['Ayear'], "text"),
                       GetSQLValueString($_POST['semester'], "text"),
					   GetSQLValueString($_POST['user'], "text"),
					   GetSQLValueString($_POST['checked'], "text"));

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($insertSQL, $zalongwa) or die(mysql_error());
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_ExamOfficerGradeBook = 20;
$pageNum_ExamOfficerGradeBook = 0;
if (isset($_GET['pageNum_ExamOfficerGradeBook'])) {
  $pageNum_ExamOfficerGradeBook = $_GET['pageNum_ExamOfficerGradeBook'];
}
$startRow_ExamOfficerGradeBook = $pageNum_ExamOfficerGradeBook * $maxRows_ExamOfficerGradeBook;

mysql_select_db($database_zalongwa, $zalongwa);
$query_ExamOfficerGradeBook = "SELECT CourseCode, AYear, SemesterID FROM examresult";
$query_limit_ExamOfficerGradeBook = sprintf("%s LIMIT %d, %d", $query_ExamOfficerGradeBook, $startRow_ExamOfficerGradeBook, $maxRows_ExamOfficerGradeBook);
$ExamOfficerGradeBook = mysql_query($query_limit_ExamOfficerGradeBook, $zalongwa) or die(mysql_error());
$row_ExamOfficerGradeBook = mysql_fetch_assoc($ExamOfficerGradeBook);

if (isset($_GET['totalRows_ExamOfficerGradeBook'])) {
  $totalRows_ExamOfficerGradeBook = $_GET['totalRows_ExamOfficerGradeBook'];
} else {
  $all_ExamOfficerGradeBook = mysql_query($query_ExamOfficerGradeBook);
  $totalRows_ExamOfficerGradeBook = mysql_num_rows($all_ExamOfficerGradeBook);
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
$query_lecturer = "SELECT RegNo, Name FROM student ORDER BY RegNo ASC";
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
 
?>
            <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
              <table width="65%"  border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
                <tr>
                  <td width="17%" nowrap><div align="right">Academic Year: </div></td>
                  <td width="16%"><select name="Ayear" id="Ayear">
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
                  <td width="52%"><select name="course" id="course">
                    <?php
do {  
?>
                    <option value="<?php echo $row_course['CourseCode']?>"><?php echo $row_course['CourseCode']?></option>
                    <?php
} while ($row_course = mysql_fetch_assoc($course));
  $rows = mysql_num_rows($course);
  if($rows > 0) {
      mysql_data_seek($course, 0);
	  $row_course = mysql_fetch_assoc($course);
  }
?>
                  </select></td>
                </tr>
                <tr>
                  <td nowrap><div align="right">Semester:</div></td>
                  <td><select name="semester" id="semester">
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
                    Student RegNo: </div></td>
                  <td><select name="lecturer" id="lecturer">
                    <?php
do {  
?>
                    <option value="<?php echo $row_lecturer['RegNo']?>"><?php echo $row_lecturer['RegNo'].": ".$row_lecturer['Name']?></option>
                    <?php
} while ($row_lecturer = mysql_fetch_assoc($lecturer));
  $rows = mysql_num_rows($lecturer);
  if($rows > 0) {
      mysql_data_seek($lecturer, 0);
	  $row_lecturer = mysql_fetch_assoc($lecturer);
  }
?>
                  </select></td>
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
mysql_free_result($ExamOfficerGradeBook);

mysql_free_result($Ayear);

mysql_free_result($semester);

mysql_free_result($course);

mysql_free_result($lecturer);
include('../footer/footer.php');
?>