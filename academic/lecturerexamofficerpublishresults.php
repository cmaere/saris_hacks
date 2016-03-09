<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Administration';
	$szSubSection = 'Publish Exam';
	$szTitle = 'Publishing and or Unpublishing Exam Results';
	include('lecturerheader.php');
?>

<?php
$currentPage = $_SERVER["PHP_SELF"];

//populate academic year combo box
mysql_select_db($database_zalongwa, $zalongwa);
$query_AYear = "SELECT AYear FROM academicyear ORDER BY AYear DESC";
$AYear = mysql_query($query_AYear, $zalongwa) or die(mysql_error());
$row_AYear = mysql_fetch_assoc($AYear);
$totalRows_AYear = mysql_num_rows($AYear);

//populate semester combo box
mysql_select_db($database_zalongwa, $zalongwa);
$query_sem = "SELECT Semester FROM terms ORDER BY Semester ASC";
$sem = mysql_query($query_sem, $zalongwa) or die(mysql_error());
$row_sem = mysql_fetch_assoc($sem);
$totalRows_sem = mysql_num_rows($sem);

//populate coursecode combo box
mysql_select_db($database_zalongwa, $zalongwa);
$query_course = "SELECT CourseCode FROM course ORDER BY CourseCode ASC";
$course = mysql_query($query_course, $zalongwa) or die(mysql_error());
$row_course = mysql_fetch_assoc($course);
$totalRows_course = mysql_num_rows($course);

if(isset($_POST['confirm']) && ($_POST['confirm']=='Confirm')){

$currentPage = $_SERVER["PHP_SELF"];
@$key=$_POST['course'];
@$ayear=$_POST['ayear'];
@$sem=$_POST['sem'];
@$act=$_POST['action'];



$maxRows_ExamOfficerGradeBook = 10000;
$pageNum_ExamOfficerGradeBook = 0;
//check whether to publish or Unpublish
if(intval($act)==1){

//publish results
$query = "UPDATE examresult SET checked = 1 WHERE CourseCode ='$key' AND AYear = '$ayear'";
}elseif(intval($act)==0){
//unpublish results
$query = "UPDATE examresult SET checked = 0 WHERE CourseCode ='$key' AND AYear = '$ayear'";
}else{
echo "Please Choose Action, Either Publish or Unpublish!";
exit;
}
$result = mysql_query($query) or die("Siwezi kuingiza data.<br>" . mysql_error());

/*
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
examresult.RegNo,        examresult.CourseCode,        examresult.Coursework,        
examresult.Exam,        examresult.Total,        examresult.Grade,        examresult.Remarks,        examresult.AYear, 
       examresult.checked,        examresult.user
	   FROM examresult    INNER JOIN course ON (examresult.CourseCode = course.CourseCode)    
	   INNER JOIN student ON (examresult.RegNo = student.RegNo) WHERE examresult.CourseCode LIKE '%$key%'
	   OR examresult.RegNo LIKE '%$key%' OR examresult.ExamNo LIKE '%$key%' OR student.Name LIKE '%$key%'";
}else{

$query_ExamOfficerGradeBook = "SELECT student.Name,        course.CourseCode,        course.CourseName,  course.Units,       
examresult.RegNo,            examresult.CourseCode,        examresult.Coursework,        
examresult.Exam,        examresult.Total,        examresult.Grade,        examresult.Remarks,        examresult.AYear, 
       examresult.checked,        examresult.user,        examresult.SemesterID 
	   FROM examresult    INNER JOIN course ON (examresult.CourseCode = course.CourseCode)    
	   INNER JOIN student ON (examresult.RegNo = student.RegNo) WHERE ((examresult.CourseCode ='$key') AND (AYear = '$ayear'))";
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
        <div align="left" class="style24"><span class="style29"><span class="style42"><font face="Verdana"><b><a href="/academic/lecturerindex.php">Home</a></b></font></span> </span> </div></td>
        <td colspan="2" align="center" valign="middle" bgcolor="#FFFFCC">
          <form action="/academic/lecturerexamofficerpublishresults.php" method="get" class="style24">
            <div align="right"><span class="style42"><font face="Verdana"><b>Search</b></font></span> <font color="006699" face="Verdana"><b>
              <input type="text" name="content" size="15">
              </b></font><font color="#FFFF00" face="Verdana"><b>
              <input type="submit" value="GO" name="go">
            </b></font> </div>
        </form></td>
      </tr>
      <tr>
        <td width="120" rowspan="4" valign="top"></td>
        <td width="36" height="14"></td>
        <td colspan="3" valign="top">
          <div align="left"><?php echo "<a href=\"lecturerexamofficerpublishfaculty.php?course=$key&ayear=$ayear&sem=$sem\">Faculty Report</a>";
				
				?> |<?php echo "<a href=\"lecturerexamofficerpublishnotice.php?course=$key&ayear=$ayear&sem=$sem\">Notice Board Report</a>";
				
				?></div></td>
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
            <table border="1" align="center">
              <tr>
                <td>S/N</td>
				<td>Name</td>
                <td>RegNo</td>
                <td>ExamNo</td>
				<td>Course</td>
                <td>CWK</td>
                <td>Exam</td>
                <td>Total</td>
                <td>Grade</td>
                <td>Remarks</td>
              </tr>
              <?php $i=1; do { ?>
              <tr>
                <td><?php echo $i; ?></td>
				<td><?php echo $row_ExamOfficerGradeBook['Name']; ?></td>
                <td><?php echo $row_ExamOfficerGradeBook['RegNo']; ?></td>
                <td><?php echo $row_ExamOfficerGradeBook['ExamNo']; ?></td>
				 <td><?php echo $row_ExamOfficerGradeBook['CourseCode']; ?></td>
                <td><?php echo $row_ExamOfficerGradeBook['Coursework']; ?></td>
                <td><?php echo $row_ExamOfficerGradeBook['Exam']; ?></td>
                <td><?php echo $row_ExamOfficerGradeBook['Total']; ?></td>
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
			  echo "Internal Examiner Signature:..................       Moderator Signature:..........................<br><br>";
			  
			  echo "EXTERNAL EXAMINER SUMMARY (GRADE TOTAL)<br><br>";
			  echo "A=       B+=    B=   C=     D=    E=    I=    <br><br>";
			  echo "Signature:............................                         Date:.............................";

?><img height="8" width="10" src="/images/spacer.gif"></div></td>
      </tr>
    </table>
 
<?php
*/
echo "Database Update Succeful!";
}else{
//openup a form
?>
<form name="form1" method="post" action="<?php echo $currentPage;?> ">
  <table width="200" border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
      <th nowrap scope="row"><div align="right">Academic Year: </div></th>
      <td><select name="ayear" id="ayear">
	  <option value="">-----------------</option>
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
      <th scope="row"><div align="right">Semester:</div></th>
      <td><select name="sem" id="sem">
	  <option value="">-----------------</option>
	   <?php
do {  
?>
 <option value="<?php echo $row_sem['Semester']?>"><?php echo $row_sem['Semester']?></option>
                        <?php
} while ($row_sem = mysql_fetch_assoc($sem));
  $rows = mysql_num_rows($sem);
  if($rows > 0) {
      mysql_data_seek($sem, 0);
	  $row_sem = mysql_fetch_assoc($sem);
  }
?>
            </select></td>
    </tr>
    <tr>
      <th scope="row"><div align="right">Course Code: </div></th>
      <td><select name="course" id="select2">
	  <option value="">-----------------</option>
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
      <th nowrap scope="row"><div align="right">Choose Action:</div></th>
      <td><select name="action" id="select3">
	  <option value="">-----------------</option>
        <option value="1">Publish</option>
        <option value="0">Unpublish</option>
                        </select></td>
    </tr>
    <tr>
      <th scope="row">&nbsp;</th>
      <td><input name="confirm" type="submit" id="confirm" value="Confirm"></td>
    </tr>
  </table>
</form>

<?php

}
@mysql_free_result(@$ExamOfficerGradeBook);
include('../footer/footer.php');
?>