<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Administration';
	$szSubSection = 'Class Size';
	$szTitle = 'Class Size';
	include('lecturerheader.php');
#Control Refreshing the page
#if not refreshed set refresh = 0
@$refresh = 0;
#------------
mysql_select_db($database_zalongwa, $zalongwa);
$query_AYear = "SELECT AYear FROM academicyear ORDER BY AYear DESC";
$AYear = mysql_query($query_AYear, $zalongwa) or die(mysql_error());
$row_AYear = mysql_fetch_assoc($AYear);
$totalRows_AYear = mysql_num_rows($AYear);

mysql_select_db($database_zalongwa, $zalongwa);
$query_dept = "SELECT DeptName FROM department ORDER BY DeptName ASC";
$dept = mysql_query($query_dept, $zalongwa) or die(mysql_error());
$row_dept = mysql_fetch_assoc($dept);
$totalRows_dept = mysql_num_rows($dept);

$browser  =  $_SERVER["HTTP_USER_AGENT"];   
$ip  =  $_SERVER["REMOTE_ADDR"];   
$sql="INSERT INTO stats(ip,browser,received,page) VALUES('$ip','$browser',now(),'$username')";   
$result = mysql_query($sql) or die("Siwezi kuingiza data.<br>" . mysql_error());

#process form submission
$editFormAction = $_SERVER['PHP_SELF'];
if ((isset($_POST["frmSubmit"])) && ($_POST["frmSubmit"] == "yes")) {
#set refresh = 1
$refresh = 1;
#..............
@$ayear = $_POST['ayear'];
@$dept = $_POST['dept'];

if ($privilege ==3) {
$query_ExamOfficerGradeBook = "
		SELECT DISTINCT COUNT(course.CourseCode) as Total, course.CourseCode,
						examresult.AYear, 
						course.Department, 
						examresult.SemesterID 
		FROM examresult 
			INNER JOIN course ON (examresult.CourseCode = course.CourseCode)
			WHERE (examresult.AYear ='$ayear') 
		AND course.Department = '$dept' GROUP BY course.CourseCode";
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_ExamOfficerGradeBook = 1000;
$pageNum_ExamOfficerGradeBook = 0;
if (isset($_GET['pageNum_ExamOfficerGradeBook'])) {
  $pageNum_ExamOfficerGradeBook = $_GET['pageNum_ExamOfficerGradeBook'];
}
$startRow_ExamOfficerGradeBook = $pageNum_ExamOfficerGradeBook * $maxRows_ExamOfficerGradeBook;
  
if ($privilege ==2) {
$query_ExamOfficerGradeBook = "
		SELECT DISTINCT COUNT(course.CourseCode) as Total, course.CourseCode,
						examresult.AYear, 
						course.Department, 
						examresult.SemesterID 
		FROM examresult 
			INNER JOIN course ON (examresult.CourseCode = course.CourseCode)
		WHERE (examresult.AYear ='$ayear') 
		AND course.Department = '$dept' GROUP BY course.CourseCode";
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
 
?>
 
<table width="100%"  border="1" cellpadding="0" cellspacing="0">
              <tr>
                <td><span class="style34"><strong>Academic Year</strong></span></td>
                <td><span class="style34"><strong>Semester</strong></span></td>
                <td><span class="style34"><strong>Course</strong></span></td>
                <td><div align="center"><span class="style34"><strong>Total</strong></span></div></td>
                 </tr>
              <?php do { ?>
              <tr>
                <td><?php $ayear = $row_ExamOfficerGradeBook['AYear']; echo $ayear = $row_ExamOfficerGradeBook['AYear']; ?></td>
                <td><?php $sem = $row_ExamOfficerGradeBook['SemesterID']; echo $row_ExamOfficerGradeBook['SemesterID']; ?></td>
                <td><?php $course = $row_ExamOfficerGradeBook['CourseCode']; echo $row_ExamOfficerGradeBook['CourseCode']; ?></td>
                <td><div align="center"><?php echo $row_ExamOfficerGradeBook['Total'];?></div></td>
           		  </tr>
              <?php } while ($row_ExamOfficerGradeBook = mysql_fetch_assoc($ExamOfficerGradeBook)); ?>
</table>
            <p>
              <?php
}
#display the form when refresh is zero
if ($refresh == 0) {
?> 
<form action="<?php echo $editFormAction ?>" method="post" enctype="multipart/form-data" name="form1">
              <table width="200" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td nowrap>Academic Year: </td>
                  <td><select name="ayear" id="ayear">
                      <?php
do {  
?>
                      <option value="<?php echo $row_AYear['AYear']?>"><?php echo $row_AYear['AYear']?></option>
                      <?php
} while ($row_AYear = mysql_fetch_assoc($AYear));
  $rows = mysql_num_rows($AYear);
  if($rows > 0) {
      mysql_data_seek($AYear, 0);
	  $row_AYear = mysql_fetch_assoc($AYear);
  }
?>
                  </select></td>
                </tr>
                <tr>
                  <td nowrap>Department:</td>
                  <td><select name="dept" id="dept">
                      <?php
do {  
?>
                      <option value="<?php echo $row_dept['DeptName']?>"><?php echo $row_dept['DeptName']?></option>
                      <?php
} while ($row_dept = mysql_fetch_assoc($dept));
  $rows = mysql_num_rows($dept);
  if($rows > 0) {
      mysql_data_seek($dept, 0);
	  $row_dept = mysql_fetch_assoc($dept);
  }
?>
                  </select></td>
                </tr>
                <tr>
                  <td><input name="frmSubmit" type="hidden" id="frmSubmit" value="yes"></td>
                  <td><input type="submit" name="action" value="View Courses"></td>
                </tr>
  </table>
</form>
<?php
}
include('../footer/footer.php');
?>