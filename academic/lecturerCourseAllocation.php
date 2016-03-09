<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Administration';
	$szSubSection = 'Course Allocation';
	$szTitle = 'Lecturer Course Allocation';
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
  $insertSQL = sprintf("INSERT INTO examregister (RegNo, CourseCode, AYear, Semester, Recorder) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['lecturer'], "text"),
                       GetSQLValueString($_POST['course'], "text"),
                       GetSQLValueString($_POST['Ayear'], "text"),
                       GetSQLValueString($_POST['semester'], "text"),
					   GetSQLValueString($_POST['user'], "text"));

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
$query_ExamOfficerGradeBook = "SELECT CourseCode, AYear, Semester FROM examregister";
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
$query_lecturer = "SELECT UserName, FullName, Position FROM security WHERE Position = 'Lecturer' ORDER BY FullName";
$lecturer = mysql_query($query_lecturer, $zalongwa) or die(mysql_error());
$row_lecturer = mysql_fetch_assoc($lecturer);
$totalRows_lecturer = mysql_num_rows($lecturer);

$maxRows_courseallocation = 50;
$pageNum_courseallocation = 0;
if (isset($_GET['pageNum_courseallocation'])) {
  $pageNum_courseallocation = $_GET['pageNum_courseallocation'];
}
$startRow_courseallocation = $pageNum_courseallocation * $maxRows_courseallocation;

mysql_select_db($database_zalongwa, $zalongwa);
$query_courseallocation = "SELECT security.UserName, 
security.FullName,                 
security.Position,                 
examregister.RegNo,                 
examregister.AYear,                 
examregister.Semester,                 
examregister.CourseCode 
FROM examregister    
	INNER JOIN security ON (examregister.RegNo = security.UserName)
	 ORDER BY  examregister.AYear DESC, examregister.CourseCode ASC";
$query_limit_courseallocation = sprintf("%s LIMIT %d, %d", $query_courseallocation, $startRow_courseallocation, $maxRows_courseallocation);
$courseallocation = mysql_query($query_limit_courseallocation, $zalongwa) or die(mysql_error());
$row_courseallocation = mysql_fetch_assoc($courseallocation);

if (isset($_GET['totalRows_courseallocation'])) {
  $totalRows_courseallocation = $_GET['totalRows_courseallocation'];
} else {
  $all_courseallocation = mysql_query($query_courseallocation);
  $totalRows_courseallocation = mysql_num_rows($all_courseallocation);
}
$totalPages_courseallocation = ceil($totalRows_courseallocation/$maxRows_courseallocation)-1;

$queryString_courseallocation = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_courseallocation") == false && 
        stristr($param, "totalRows_courseallocation") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_courseallocation = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_courseallocation = sprintf("&totalRows_courseallocation=%d%s", $totalRows_courseallocation, $queryString_courseallocation);

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
              <table width="54%"  border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
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
                    Lecturer ID: </div></td>
                  <td><select name="lecturer" id="lecturer">
                    <?php
do {  
?>
                    <option value="<?php echo $row_lecturer['UserName']?>"><?php echo $row_lecturer['FullName']?></option>
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
                <p>
                  <input type="hidden" name="MM_insert" value="form1">
</p>
                <p>LECTURER COURSE ALLOCATION                </p>
                <table width="451" border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
                  <tr>
                    <td width="93" nowrap><strong>Full Name</strong></td>
                    <td width="53"><strong>Position</strong></td>
                    <td width="109" nowrap><strong>Academic Year</strong></td>
                    <td width="86"><strong>Semester</strong></td>
                    <td width="76"><strong>Course</strong></td>
					<td width="76"><strong>Drop</strong></td>
                  </tr>
                  <?php do { ?>
                  <tr>
                    <td nowrap><?php $user=$row_courseallocation['UserName']; echo $row_courseallocation['FullName']; ?></td>
                    <td nowrap><?php echo $row_courseallocation['Position']; ?></td>
                    <td nowrap><?php $ayear=$row_courseallocation['AYear']; echo $row_courseallocation['AYear']; ?></td>
                    <td nowrap><?php echo $row_courseallocation['Semester']; ?></td>
                    <td nowrap><?php $key = $row_courseallocation['CourseCode']; echo $row_courseallocation['CourseCode']; ?></td>
					 <td><?php print "<a href=\"lecturerexalresultdelete.php?RegNo=$user&ayear=$ayear&key=$key\">Drop</a>";?></td>

                  </tr>
                  <?php } while ($row_courseallocation = mysql_fetch_assoc($courseallocation)); ?>
  </table>
</form>
<a href="<?php printf("%s?pageNum_courseallocation=%d%s", $currentPage, max(0, $pageNum_courseallocation - 1), $queryString_courseallocation); ?>">Previous Page </a> Records: <?php echo min($startRow_courseallocation + $maxRows_courseallocation, $totalRows_courseallocation) ?>/<?php echo $totalRows_courseallocation ?> <a href="<?php printf("%s?pageNum_courseallocation=%d%s", $currentPage, min($totalPages_courseallocation, $pageNum_courseallocation + 1), $queryString_courseallocation); ?>">Next Page</a>

<?php

include('../footer/footer.php');
?>