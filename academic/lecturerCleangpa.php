<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Examination';
	$szSubSection = 'Elective Courses';
	$szTitle = 'Clean GPA by Removing Optional Courses';
	include('lecturerheader.php');
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

if (isset($_GET['Counts']) && ($_GET['Counts'] == "yes"))  {
#get posted variables
$course = $_GET['CourseCode'];
$std = $_GET['RegNo'];
$qupdate = "UPDATE examresult SET Count=0 WHERE RegNo = '$std' AND CourseCode = '$course'";
$result = mysql_query($qupdate);
echo "Database Updated";
exit;
}elseif (isset($_GET['CourseCode']))  {
#get posted variables
$course = $_GET['CourseCode'];
$std = $_GET['RegNo'];
$qupdate = "UPDATE examresult SET Count=1 WHERE RegNo = '$std' AND CourseCode = '$course'";
$result = mysql_query($qupdate);
echo "Database Updated";
exit;
}

if (isset($_POST['search']) && ($_POST['search'] == "Search")) {
#get post variables
$rawkey = $_POST['key'];
$key = ereg_replace("[[:space:]]+", " ",$rawkey);
//select student
$qstudent = "SELECT * from student WHERE RegNo = '$key'";
$dbstudent = mysql_query($qstudent) or die("Mwanafunzi huyu hana matokeo".  mysql_error()); 
$row_result = mysql_fetch_array($dbstudent);
			$name = $row_result['Name'];
			$regno = $row_result['RegNo'];
			$degree = $row_result['ProgrammeofStudy'];
			
			//get degree name
			$qdegree = "Select Title from programme where ProgrammeCode = '$degree'";
			$dbdegree = mysql_query($qdegree);
			$row_degree = mysql_fetch_array($dbdegree);
			$programme = $row_degree['Title'];
			
			echo  "$name - $regno <br> $programme";	

				
//query academeic year
$qayear = "SELECT DISTINCT AYear from examresult WHERE RegNo = '$key' ORDER BY AYear ASC";
$dbayear = mysql_query($qayear);

//query exam results sorted per years
while($rowayear = mysql_fetch_object($dbayear)){
$currentyear = $rowayear->AYear;

			# get all courses for this candidate
			$qcourse="SELECT DISTINCT course.Units, course.Department, course.CourseName, course.StudyLevel, examresult.CourseCode, examresult.Status FROM 
						course INNER JOIN examresult ON (course.CourseCode = examresult.CourseCode)
							 WHERE 
									(RegNo='$key') AND (AYear='$currentyear')";	
			$dbcourse = mysql_query($qcourse) or die("No Exam Results for the Candidate - $key ");
			$total_rows = mysql_num_rows($dbcourse);
			
			if($total_rows>0){
			#initialise s/no
			$sn=0;
			#print name and degree
			//select student
				$qstudent = "SELECT Name, RegNo, ProgrammeofStudy from student WHERE RegNo = '$key'";
				$dbstudent = mysql_query($qstudent) or die("Mwanafunzi huyu hana matokeo".  mysql_error()); 
				$row_result = mysql_fetch_array($dbstudent);
				$name = $row_result['Name'];
				$regno = $row_result['RegNo'];
				$degree = $row_result['ProgrammeofStudy'];
				
				//get degree name
				$qdegree = "Select Title from programme where ProgrammeCode = '$degree'";
				$dbdegree = mysql_query($qdegree);
				$row_degree = mysql_fetch_array($dbdegree);
				$programme = $row_degree['Title'];
							
				#initialise
				$unit =0;
				$totalunit=0;
				$unittaken=0;
				$sgp=0;
				$totalsgp=0;
				$gpa=0;
?>
<table width="100%" height="100%" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td scope="col"><?php echo $rowayear->AYear;?></td>
	<td scope="col">Course</td>
    <td scope="col">Unit</td>
    <td scope="col">Grade</td>
    <td scope="col">Point</td>
	<td scope="col">Remarks</td>
	<td scope="col">Status</td>
    <td scope="col">GPA</td>
  </tr>
  <?php
				$unit =0;
				while($row_course = mysql_fetch_array($dbcourse)){
					$course= $row_course['CourseCode'];
					$unit = $row_course['Units'];
					$name = $row_course['CourseName'];
					if($row_course['Status']==1){
					$status ='Core';
					}else{
					$status = 'Elective';
					}
					$coursefaculty = $row_course['Department'];
					$sn=$sn+1;
					$remarks = 'remarks';

					$RegNo = $key;
					#insert grading results
					include 'includes/choose_studylevel.php';
				#display results
				?>
	<tr>
     <td scope="col"><div align="left"><?php echo "<a href=\"lecturerCleangpa.php?CourseCode=$course&RegNo=$regno\">$course</a>";?></div></td>
     <td scope="col"><div align="left"><?php echo $name;?></div></td>
     <td scope="col"><div align="center"><?php echo $unit;?></div></td>
     <td scope="col"><div align="center"><?php echo $grade;?></div></td>
	 <td scope="col"><div align="center"><?php echo $sgp;?></div></td>
	 <td scope="col"><div align="center"><?php echo $remark;?></div></td>
	 <td scope="col"><div align="center"><?php echo $status;?></div></td>
    <td scope="col"></td>
  </tr>
  <?php }?>
  	<tr>
     <td scope="col"></td>
     <td scope="col"></td>
     <td scope="col"><div align="center"><?php echo $unittaken;?></div></td>
     <td scope="col"></td>
	 <td scope="col"><div align="center"><?php echo $totalsgp;?></div></td>
	 <td scope="col"></td>
	 <td scope="col"></td>
    <td scope="col"><div align="center"><?php echo substr($totalsgp/$unittaken, 0,3);?></div></td>
  </tr>
</table>
<?php }
}
?>
<hr>
While calculating GPA, these courses were not taken into consideration! 
<hr>
<?php //select student
$qstudent = "SELECT * from student WHERE RegNo = '$key'";
$dbstudent = mysql_query($qstudent) or die("Mwanafunzi huyu hana matokeo".  mysql_error()); 
$row_result = mysql_fetch_array($dbstudent);
			$name = $row_result['Name'];
			$regno = $row_result['RegNo'];
			$degree = $row_result['ProgrammeofStudy'];
			
			//get degree name
			$qdegree = "Select Title from programme where ProgrammeCode = '$degree'";
			$dbdegree = mysql_query($qdegree);
			$row_degree = mysql_fetch_array($dbdegree);
			$programme = $row_degree['Title'];
			
			echo  "$name - $regno <br> $programme";	

				
//query academeic year
$qayear = "SELECT DISTINCT AYear from examresult WHERE RegNo = '$key' ORDER BY AYear ASC";
$dbayear = mysql_query($qayear);

//query exam results sorted per years
while($rowayear = mysql_fetch_object($dbayear)){
$currentyear = $rowayear->AYear;

			# get all courses for this candidate
			$qcourse="SELECT DISTINCT course.Units, course.Department, course.CourseName, course.StudyLevel, examresult.CourseCode, examresult.Status FROM 
						course INNER JOIN examresult ON (course.CourseCode = examresult.CourseCode)
							 WHERE 
									(RegNo='$key') AND 
									(AYear='$currentyear') AND (Count=1)";	
			$dbcourse = mysql_query($qcourse) or die("No Exam Results for the Candidate - $key ");
			$total_rows = mysql_num_rows($dbcourse);
			
			if($total_rows>0){
			#initialise s/no
			$sn=0;
			#print name and degree
			//select student
				$qstudent = "SELECT Name, RegNo, ProgrammeofStudy from student WHERE RegNo = '$key'";
				$dbstudent = mysql_query($qstudent); 
				$row_result = mysql_fetch_array($dbstudent);
				$name = $row_result['Name'];
				$regno = $row_result['RegNo'];
				$degree = $row_result['ProgrammeofStudy'];
				
				//get degree name
				$qdegree = "Select Title from programme where ProgrammeCode = '$degree'";
				$dbdegree = mysql_query($qdegree);
				$row_degree = mysql_fetch_array($dbdegree);
				$programme = $row_degree['Title'];
							
?>
<table width="100%" height="100%" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td scope="col"><?php echo $rowayear->AYear;?></td>
	<td scope="col">Course</td>
    <td scope="col">Unit</td>
    <td scope="col">Grade</td>
    <td scope="col">Point</td>
	<td scope="col">Remarks</td>
	<td scope="col">Status</td>
    <td scope="col">GPA</td>
  </tr>
  <?php
				while($row_course = mysql_fetch_array($dbcourse)){
					$course= $row_course['CourseCode'];
					$unit = $row_course['Units'];
					$name = $row_course['CourseName'];
					if($row_course['Status']==1){
					$status ='Core';
					}else{
					$status = 'Elective';
					}
					$coursefaculty = $row_course['Department'];
					$sn=$sn+1;
					$remarks = 'remarks';

					$RegNo = $key;
					#insert grading results
					include 'includes/choose_studylevel.php';
				#display results

				?>
	<tr>
     <td scope="col"><div align="left"><?php echo "<a href=\"lecturerCleangpa.php?CourseCode=$course&RegNo=$regno&Counts=yes\">$course</a>";?></div></td>
     <td scope="col"><div align="left"><?php echo $name;?></div></td>
     <td scope="col"><div align="center"><?php echo $unit;?></div></td>
     <td scope="col"><div align="center"><?php echo $grade;?></div></td>
	 <td scope="col"><div align="center"><?php echo $sgp;?></div></td>
	 <td scope="col"><div align="center"><?php echo $remark;?></div></td>
	 <td scope="col"><div align="center"><?php echo $status;?></div></td>
    <td scope="col"></td>
  </tr>
  <?php }?>
  	<tr>
     <td scope="col"></td>
     <td scope="col"></td>
     <td scope="col"><div align="center"><?php echo $unittaken;?></div></td>
     <td scope="col"></td>
	 <td scope="col"><div align="center"><?php echo $totalsgp;?></div></td>
	 <td scope="col"></td>
	 <td scope="col"></td>
    <td scope="col"><div align="center"><?php echo substr($totalsgp/$unittaken, 0,3);?></div></td>
  </tr>
</table>
<?php }
  }//ends while rowayear	
mysql_close($zalongwa);

}else{

?>

<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" name="studentRoomApplication" id="studentRoomApplication">
            <table width="284" border="0">
        <tr>
          <td colspan="2" nowrap><div align="center"></div>
          </td>
        </tr>
        <tr>
          <td width="111"><div align="right"><span class="style67">RegNo:</span></div></td>
          <td width="157" bordercolor="#ECE9D8" bgcolor="#CCCCCC"><span class="style67">
          <input name="key" type="text" id="key" size="40" maxlength="40">
          </span></td>
        </tr>
        <tr>
          <td nowrap><div align="right"> </div></td>
          <td bgcolor="#CCCCCC"><input type="submit" name="search" value="Search"></td>
        </tr>
      </table>
</form>
<?php
}
include('../footer/footer.php');
?>