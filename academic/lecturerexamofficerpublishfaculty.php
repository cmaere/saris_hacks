<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Examination';
	$szSubSection = 'Grade Book';
	$szTitle = 'Examination Grade Book';
	include('lecturerheader.php');
?>
<?php
$currentPage = $_SERVER["PHP_SELF"];
@$key=$_GET['course'];
@$ayear=$_GET['ayear'];
@$sem=$_GET['sem'];
$maxRows_ExamOfficerGradeBook = 10000;
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
$query_ExamOfficerGradeBook = "SELECT student.Name,        course.CourseCode,        course.CourseName, course.Units,       
examresult.RegNo,        examresult.ExamNo,        examresult.CourseCode,        examresult.Coursework,        
examresult.Exam,        examresult.Total,        examresult.Grade,        examresult.Remarks,        examresult.AYear, 
       examresult.checked,        examresult.user,        examresult.SemesterID 
	   FROM examresult    INNER JOIN course ON (examresult.CourseCode = course.CourseCode)    
	   INNER JOIN student ON (examresult.RegNo = student.RegNo) WHERE examresult.CourseCode LIKE '%$key%'
	   OR examresult.RegNo LIKE '%$key%' OR examresult.ExamNo LIKE '%$key%' OR student.Name LIKE '%$key%'";
}else{
$query_ExamOfficerGradeBook = "SELECT student.Name,        course.CourseCode,        course.CourseName,  course.Units,       
examresult.RegNo,        examresult.ExamNo,        examresult.CourseCode,        examresult.Coursework,        
examresult.Exam,        examresult.Total,        examresult.Grade,        examresult.Remarks,        examresult.AYear, 
       examresult.checked,        examresult.user,        examresult.SemesterID 
	   FROM examresult    INNER JOIN course ON (examresult.CourseCode = course.CourseCode)    
	   INNER JOIN student ON (examresult.RegNo = student.RegNo) WHERE ((examresult.CourseCode ='$key') AND (examresult.SemesterID='$sem') AND (AYear = '$ayear'))";
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
    <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
      
      <tr bgcolor="#FFFFCC" class="normaltext">
        <td height="28" colspan="3" align="center" valign="middle" nowrap>
        <div align="left" class="style24"> </div></td>
        <td colspan="2" align="center" valign="middle" bgcolor="#FFFFCC">
          </td>
      </tr>
      <tr>
        <td width="120" rowspan="4" valign="top"></td>
        <td width="36" height="14"></td>
        <td colspan="3" valign="top">
          <div align="left"></div></td>
      </tr>
      <tr>
        <td height="112"></td>
        <td colspan="3" align="left" valign="top"><div align="left">
            
            <p align="center"><span class="style66"><span class="style67">UNIVERSITY OF DAR ES SALAAM</span><br>
                </span><br>
				 <span class="style68">COURSE RECORD SHEET</span></span><br>
				  <span class="style71"> PROVISIONAL EXAMINATION RESULTS FOR <?php 
				$countgradeA=0;
				$countgradeBplus=0;
				$countgradeC=0;
				$countgradeB=0;
				$countgradeD=0;
				$countgradeE=0;
				$countgradeI=0;
				echo $row_ExamOfficerGradeBook['SemesterID']; ?> , <?php echo $row_ExamOfficerGradeBook['AYear']; ?><br>
            <?php echo $row_ExamOfficerGradeBook['CourseCode'].",  Units=".$row_ExamOfficerGradeBook['Units']; ?></span><br> <?php echo $row_ExamOfficerGradeBook['CourseName']; ?></p>
            <p align="left">&nbsp;</p>
            <table border="1" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td>S/N</td>
				<td>Name</td>
                <td>RegNo</td>
                <td>ExamNo</td>
				<td>Grade</td>
                <td>Remarks</td>
              </tr>
              <?php $i=1; do { ?>
              <tr>
                <td><?php echo $i; ?></td>
				<td><?php echo $row_ExamOfficerGradeBook['Name']; ?></td>
                <td><?php echo $row_ExamOfficerGradeBook['RegNo']; ?></td>
                <td><?php echo $row_ExamOfficerGradeBook['ExamNo']; ?></td>
				<td><?php $grade= $row_ExamOfficerGradeBook['Grade']; 
				
				if ($grade=='A')
					$countgradeA=$countgradeA+1;
				elseif($grade=='B+')
					$countgradeBplus=$countgradeBplus+1;
				elseif($grade=='B')
					$countgradeB=$countgradeB+1;
			    elseif($grade=='C')
					$countgradeC=$countgradeC+1;
			   elseif($grade=='D')
					$countgradeD=$countgradeD+1;
			   elseif($grade=='E')
					$countgradeE=$countgradeE+1;
			   else
					$countgradeI=$countgradeI+1;
				
				
				echo $row_ExamOfficerGradeBook['Grade']; ?></td>
                <td><?php echo $row_ExamOfficerGradeBook['Remarks']; ?></td>
              </tr>
              <?php $i=$i+1;} while ($row_ExamOfficerGradeBook = mysql_fetch_assoc($ExamOfficerGradeBook)); 
			  
			  	  
			  ?>
          </table>
        </div></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td height="88"></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td width="756"></td>
        <td width="18"></td>
        <td width="1671"></td>
      </tr>
      <tr>
        <td><img height="1" width="120" src="/images/spacer.gif"></td>
        <td><img height="1" width="5" src="/images/spacer.gif"></td>
        <td colspan="3"><div align="center"><?php
$i=$i-1;
echo "SUMMARY (GRADE TOTAL)<br><br>";
			     
			  echo "A=  ".$countgradeA.    ";  B+ = ".$countgradeBplus.  ";  B= ".$countgradeB.  ";    C=" .$countgradeC.  ";   D=".$countgradeD.  ";  E=". $countgradeE." ;    I=".$countgradeI.";  TOTAL RECORDS=$i<br><br>";
			  
?><img height="8" width="10" src="/images/spacer.gif"></div></td>
      </tr>
    </table>
 
<?php
mysql_free_result($ExamOfficerGradeBook);
include('../footer/footer.php');
?>
