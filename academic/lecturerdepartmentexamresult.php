<?php require_once('../Connections/zalongwa.php'); ?>
<?php
session_start();
header("Cache-control: private"); // IE 6 Fix. 
@$auth_level = $_SESSION['auth_level'];
@$RegNo = $_SESSION['RegNo'];
@$username = $_SESSION['username'];

$currentPage = $_SERVER["PHP_SELF"];
@$key=$_GET['course'];
@$ayear=$_GET['ayear'];
$maxRows_ExamOfficerGradeBook = 1000;
$pageNum_ExamOfficerGradeBook = 0;
$query = "UPDATE examresult SET checked = 1 WHERE CourseCode ='$key' AND AYear = '$ayear'";
$result = mysql_query($query) or die("Siwezi kuingiza data.<br>" . mysql_error());
//mysql_free_result($result);

if (isset($_GET['pageNum_ExamOfficerGradeBook'])) {
  $pageNum_ExamOfficerGradeBook = $_GET['pageNum_ExamOfficerGradeBook'];
}
$startRow_ExamOfficerGradeBook = $pageNum_ExamOfficerGradeBook * $maxRows_ExamOfficerGradeBook;

$maxRows_ExamOfficerGradeBook = 1000;;
$pageNum_ExamOfficerGradeBook = 0;
if (isset($_GET['pageNum_ExamOfficerGradeBook'])) {
  $pageNum_ExamOfficerGradeBook = $_GET['pageNum_ExamOfficerGradeBook'];
}
$startRow_ExamOfficerGradeBook = $pageNum_ExamOfficerGradeBook * $maxRows_ExamOfficerGradeBook;

mysql_select_db($database_zalongwa, $zalongwa);
if (isset($_GET['content'])) {
  $key=$_GET['content'];
$query_ExamOfficerGradeBook = "SELECT student.Name,        course.CourseCode,        course.CourseName,        
examresult.RegNo,        examresult.ExamNo,        examresult.CourseCode,        examresult.Coursework,        
examresult.Exam,        examresult.Total,        examresult.Grade,        examresult.Remarks,        examresult.AYear, 
       examresult.checked,        examresult.user,        examresult.SemesterID 
	   FROM examresult    INNER JOIN course ON (examresult.CourseCode = course.CourseCode)    
	   INNER JOIN student ON (examresult.RegNo = student.RegNo) WHERE examresult.CourseCode LIKE '%$key%'
	   OR examresult.RegNo LIKE '%$key%' OR examresult.ExamNo LIKE '%$key%' OR student.Name LIKE '%$key%'";
}else{
$query_ExamOfficerGradeBook = "SELECT student.Name,        course.CourseCode,        course.CourseName,        
examresult.RegNo,        examresult.ExamNo,        examresult.CourseCode,        examresult.Coursework,        
examresult.Exam,        examresult.Total,        examresult.Grade,        examresult.Remarks,        examresult.AYear, 
       examresult.checked,        examresult.user,        examresult.SemesterID 
	   FROM examresult    INNER JOIN course ON (examresult.CourseCode = course.CourseCode)    
	   INNER JOIN student ON (examresult.RegNo = student.RegNo) WHERE examresult.CourseCode ='$key'";
}
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