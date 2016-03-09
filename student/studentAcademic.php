<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
$accpac = $_GET['accpac'];
include('studentMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Academic Records';
	$szTitle = 'Academic Records: Course Registered';
	$szSubSection = 'Exam Registered';
	//$additionalStyleSheet = './general.css';
	include("studentheader.php");

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
?>
<?php
if ((isset($_GET['CourseCode'])) && ($_GET['CourseCode'] != "")) {
  $coursecode = addslashes($_GET['CourseCode']);
  $regno = addslashes($_GET['RegNo']);
  $deleteSQL = "DELETE FROM examregister WHERE CourseCode='$coursecode' AND checked=0 AND RegNo='$regno'";
                    
  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($deleteSQL, $zalongwa) or die('This Records is Locked by the Examination Officer');

  //$deleteGoTo = "studentAcademic.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
 // header(sprintf("Location: %s", $deleteGoTo));
  }
?>

<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_coursecandidate = 13;
$pageNum_coursecandidate = 0;
if (isset($_GET['pageNum_coursecandidate'])) {
  $pageNum_coursecandidate = $_GET['pageNum_coursecandidate'];
}
$startRow_coursecandidate = $pageNum_coursecandidate * $maxRows_coursecandidate;

$colname_coursecandidate = "1";
if (isset($_COOKIE['RegNo'])) {
  $colname_coursecandidate = (get_magic_quotes_gpc()) ? $_COOKIE['RegNo'] : addslashes($_COOKIE['RegNo']);
}
mysql_select_db($database_zalongwa, $zalongwa);
$query_coursecandidate = "SELECT DISTINCT
								examregister.CourseCode, 
								course.Units, 
								course.CourseName, 
								examregister.RegNo,
								examregister.AYear
						FROM course INNER JOIN examregister ON course.CourseCode = examregister.CourseCode
							WHERE (((examregister.RegNo)='$RegNo')) ORDER BY AYear DESC, CourseCode ASC";
$query_limit_coursecandidate = sprintf("%s LIMIT %d, %d", $query_coursecandidate, $startRow_coursecandidate, $maxRows_coursecandidate);
$coursecandidate = mysql_query($query_limit_coursecandidate, $zalongwa) or die(mysql_error());
$row_coursecandidate = mysql_fetch_assoc($coursecandidate);

if (isset($_GET['totalRows_coursecandidate'])) {
  $totalRows_coursecandidate = $_GET['totalRows_coursecandidate'];
} else {
  $all_coursecandidate = mysql_query($query_coursecandidate);
  $totalRows_coursecandidate = mysql_num_rows($all_coursecandidate);
}
$totalPages_coursecandidate = ceil($totalRows_coursecandidate/$maxRows_coursecandidate)-1;

$queryString_coursecandidate = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_coursecandidate") == false && 
        stristr($param, "totalRows_coursecandidate") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_coursecandidate = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_coursecandidate = sprintf("&totalRows_coursecandidate=%d%s", $totalRows_coursecandidate, $queryString_coursecandidate);

@$CourseCode = $_GET['CourseCode'];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmCourseRegister")) {
  $insertSQL = sprintf("INSERT INTO coursecandidate (RegNo, CourseCode) VALUES (%s, %s)",
                       GetSQLValueString($_POST['regno'], "text"),
                       GetSQLValueString($_POST['coursecode'], "text"));

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($insertSQL, $zalongwa) or die("You have alrady registered for this course, <br>duplicate Records are not allowed");

  $insertGoTo = "studentindex.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<?php echo "<a href=\"studentCourselist.php\"> Add New Course </a>"; ?>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>


<table width="200" border="1" cellpadding="0" cellspacing="0">
            <tr>
            <td nowrap>Drop</td>
			<td nowrap>Year</td>
			  <td nowrap>Course</td>
			    <td nowrap>Units</td>
			  <td nowrap>Course Title</td>
            </tr>
            <?php do { ?>
            <tr>
                <td nowrap><?php $CourseCode = $row_coursecandidate['CourseCode']; echo "<a href=\"studentAcademic.php?CourseCode=$CourseCode&RegNo=$RegNo\"> Drop </a>"; ?></td> 
				<td nowrap><?php echo $row_coursecandidate['AYear']; ?></td>
				<td nowrap><?php echo $row_coursecandidate['CourseCode']; ?></td>
				<td nowrap><div align="center"><?php echo $row_coursecandidate['Units']; ?></div></td>
				<td nowrap><?php echo $row_coursecandidate['CourseName']; ?></td>
            </tr>
            <?php } while ($row_coursecandidate = mysql_fetch_assoc($coursecandidate)); ?>
</table>
          
            <p><a href="<?php printf("%s?pageNum_coursecandidate=%d%s", $currentPage, max(0, $pageNum_coursecandidate - 1), $queryString_coursecandidate); ?>">Previous Page</a> <span class="style64"><span class="style34">Records: <?php echo min($startRow_coursecandidate + $maxRows_coursecandidate, $totalRows_coursecandidate) ?>/<?php echo $totalRows_coursecandidate ?></span> <span class="style1">...</span></span><a href="<?php printf("%s?pageNum_coursecandidate=%d%s", $currentPage, min($totalPages_coursecandidate, $pageNum_coursecandidate + 1), $queryString_coursecandidate); ?>">Next Page</a> <span class="style64 style1"></span>
          
			  <?php

	# include the footer
	include("../footer/footer.php");
?>
            