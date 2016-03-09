<?php require_once('../Connections/zalongwa.php'); ?>
<?php
session_start();
header("Cache-control: private"); // IE 6 Fix. 
@$auth_level = $_SESSION['auth_level'];
@$RegNo = $_SESSION['RegNo'];
@$username = $_SESSION['username'];

echo "<a href=\"lecturerindex.php?username=$username\">Home</a><br>";
echo "UNIVERSITY OF DAR ES SALAAM<br>";
					echo "COMPUTER SCIENCE DEPARTMENT <br>";
					echo "STUDENT EXAMRESULTS TRANSCRIPTS <br><hr>";
					
//if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
//$reg = $_POST['regno'];
@$checkdegree = $_POST['checkdegree'];
@$checkyear = $_POST['checkyear'];
@$checkdept = $_POST['checkdept'];

$c=0;

if (($checkdegree=='on') && ($checkyear == 'on') && ($checkdept == 'on')){
$reg = $_POST['regno'];
$deg=$_POST['degree'];
$year = $_POST['ayear'];
$dept = $_POST['dept'];

	for(;;){
		if($c > sizeof($reg)){
				break;
			}
			@$updateSQL = "SELECT student.Name,
										   student.ProgrammeofStudy,
										   examresult.RegNo,
										   examresult.ExamNo,
										   examresult.CourseCode,
										   course.Units,
										   examresult.Grade,
										   examresult.AYear,
										   examresult.Remarks,
										   examresult.Status,
										   examresult.SemesterID,
										   examresult.Comment,
										   course.Department
							FROM course
							   INNER JOIN examresult ON (course.CourseCode = examresult.CourseCode)
							   INNER JOIN student ON (examresult.RegNo = student.RegNo)
							WHERE 
										   (
											  (student.ProgrammeofStudy = '$deg')
										   and 
											  (examresult.RegNo = '$reg[$c]')
										   and 
											  (course.Department= '$dept')
										   and 
											  (examresult.AYear = '$year')
										   )";
           	$result = mysql_query($updateSQL) or die("Mwanafunzi huyu hana matokeo"); 
			$query = @mysql_query($updateSQL) or die("Cannot query the database.<br>" . mysql_error());
			$row_result = mysql_fetch_array($result);
			$name = $row_result['Name'];
			$degree = $row_result['ProgrammeofStudy'];
			
			if (mysql_num_rows($query) > 0){
			
					$totalunit=0;
					$unittaken=0;
					$sgp=0;
					$totalsgp=0;
					$gpa=0;
					
			echo "$c". ".$name  $reg[$c] '$degree'";
			echo "<table border='1'>";
			echo "<tr><td> S/No </td><td>Year </td><td> Course</td><td> Unit </td><td> Grade </td><td> Remarks</td><td> Core/Option</td></tr>";
			$i=1;
				while($result = mysql_fetch_array($query)) {
					
					
					$unit = $result['Units'];
					$totalunit=$totalunit+$unit;
					
					$ayear = $result['AYear'];
					$remarks = $result['Remarks'];
					$status = $result['Status'];
					$grade = $result['Grade'];
					$comment = $result['Comment'];
					//$semester = $result['SemesterID'];
					
					
					if ($grade=='A'){
							$point=5;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B+'){
							$point=4;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B'){
							$point=3;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='C'){
							$point=2;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='D'){
							$point=1;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='E'){
							$point=0;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}else
							$sgp='';
							
					$coursecode = $result['CourseCode'];
					//$totalRows_result = mysql_num_rows($result);
					
								echo "<tr><td>$i</td>";
								echo "<td>$ayear</td>";
								echo "<td>$coursecode</td>";
								echo "<td>$unit</td>";
								echo "<td>$grade</td>";
								echo "<td>$remarks</td>";
								echo "<td>$status</td></tr>";
							$i=$i+1;
						}
						echo "</table>";
						echo "Total Units =". $totalunit.";   Units Completed = ".$unittaken.  ";   Total Points = ".$totalsgp. ";   GPA = ".@substr($totalsgp/$unittaken, 0,3)."<br><hr>";
					}else{ 
					if(!@$reg[$c]){}
						}
					//mysql_close($zalongwa);
					//mysql_free_result($result);
					$c++;
			 }
		
}elseif (($checkyear == 'on') && ($checkdept == 'on')){	
$reg = $_POST['regno'];
$year = $_POST['ayear'];
$dept = $_POST['dept'];
	for(;;){
		if($c > sizeof($reg)){
				break;
			}
			@$updateSQL = "SELECT student.Name,
										   student.ProgrammeofStudy,
										   examresult.RegNo,
										   examresult.ExamNo,
										   examresult.CourseCode,
										   course.Units,
										   examresult.Grade,
										   examresult.AYear,
										   examresult.Remarks,
										   examresult.Status,
										   examresult.SemesterID,
										   examresult.Comment,
										   course.Department
							FROM course
							   INNER JOIN examresult ON (course.CourseCode = examresult.CourseCode)
							   INNER JOIN student ON (examresult.RegNo = student.RegNo)
							WHERE 
										   (
											  (examresult.RegNo = '$reg[$c]')
										   and 
											  (course.Department= '$dept')
										   and 
											  (examresult.AYear = '$year')
										   )";
           	$result = mysql_query($updateSQL) or die("Mwanafunzi huyu hana matokeo"); 
			$query = @mysql_query($updateSQL) or die("Cannot query the database.<br>" . mysql_error());
			$row_result = mysql_fetch_array($result);
			$name = $row_result['Name'];
			$degree = $row_result['ProgrammeofStudy'];
			
			if (mysql_num_rows($query) > 0){
			
					$totalunit=0;
					$unittaken=0;
					$sgp=0;
					$totalsgp=0;
					$gpa=0;
					
			echo "$c". ".$name  $reg[$c] '$degree'";
			echo "<table border='1'>";
			echo "<tr><td> S/No </td><td>Year </td><td> Course</td><td> Unit </td><td> Grade </td><td> Remarks</td><td> Core/Option</td></tr>";
			$i=1;
				while($result = mysql_fetch_array($query)) {
					
					
					$unit = $result['Units'];
					$totalunit=$totalunit+$unit;
					
					$ayear = $result['AYear'];
					$remarks = $result['Remarks'];
					$status = $result['Status'];
					$grade = $result['Grade'];
					$comment = $result['Comment'];
					//$semester = $result['SemesterID'];
					
					
					if ($grade=='A'){
							$point=5;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B+'){
							$point=4;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B'){
							$point=3;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='C'){
							$point=2;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='D'){
							$point=1;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='E'){
							$point=0;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}else
							$sgp='';
							
					$coursecode = $result['CourseCode'];
					//$totalRows_result = mysql_num_rows($result);
					
								echo "<tr><td>$i</td>";
								echo "<td>$ayear</td>";
								
								echo "<td>$coursecode</td>";
								echo "<td>$unit</td>";
								echo "<td>$grade</td>";
								echo "<td>$remarks</td>";
								echo "<td>$status</td></tr>";
							$i=$i+1;
						}
						echo "</table>";
						echo "Total Units =". $totalunit.";   Units Completed = ".$unittaken.  ";   Total Points = ".$totalsgp. ";   GPA = ".@substr($totalsgp/$unittaken, 0,3)."<br><hr>";
					}else{ 
					if(!@$reg[$c]){}
						}
					//mysql_close($zalongwa);
					//mysql_free_result($result);
					$c++;
			 }
}elseif ($checkdegree=='on'){
$reg = $_POST['regno'];
$deg=$_POST['degree'];

for(;;){
		if($c > sizeof($reg)){
				break;
			}
			@$updateSQL = "SELECT student.Name,
			student.ProgrammeofStudy,
       examresult.RegNo,
       examresult.ExamNo,
       examresult.CourseCode,
       course.Units,
       examresult.Grade,
       examresult.AYear,
       examresult.Remarks,
       examresult.Status,
	   examresult.SemesterID,
	   examresult.Comment
FROM examresult
   INNER JOIN course ON (examresult.CourseCode = course.CourseCode)
   INNER JOIN student ON (examresult.RegNo = student.RegNo)
				WHERE  student.ProgrammeofStudy = '$deg' AND examresult.RegNo = '$reg[$c]' ";
           	$result = mysql_query($updateSQL) or die("Mwanafunzi huyu hana matokeo"); 
			$query = @mysql_query($updateSQL) or die("Cannot query the database.<br>" . mysql_error());
			$row_result = mysql_fetch_array($result);
			$name = $row_result['Name'];
			$degree = $row_result['ProgrammeofStudy'];
			
			if (mysql_num_rows($query) > 0){
			
					$totalunit=0;
					$unittaken=0;
					$sgp=0;
					$totalsgp=0;
					$gpa=0;
					
			echo "$c". ".$name  $reg[$c] '$degree'";
			echo "<table border='1'>";
			echo "<tr><td> S/No </td><td>Year </td><td> Course</td><td> Unit </td><td> Grade </td><td> Remarks</td><td> Core/Option</td></tr>";
			$i=1;
				while($result = mysql_fetch_array($query)) {
					
					
					$unit = $result['Units'];
					$totalunit=$totalunit+$unit;
					
					$ayear = $result['AYear'];
					$remarks = $result['Remarks'];
					$status = $result['Status'];
					$grade = $result['Grade'];
					$comment = $result['Comment'];
					
					
					
					if ($grade=='A'){
							$point=5;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B+'){
							$point=4;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B'){
							$point=3;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='C'){
							$point=2;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='D'){
							$point=1;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='E'){
							$point=0;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}else
							$sgp='';
							
					$coursecode = $result['CourseCode'];
					//$totalRows_result = mysql_num_rows($result);
					
								echo "<tr><td>$i</td>";
								echo "<td>$ayear</td>";
								
								echo "<td>$coursecode</td>";
								echo "<td>$unit</td>";
								echo "<td>$grade</td>";
								echo "<td>$remarks</td>";
								echo "<td>$status</td></tr>";
							$i=$i+1;
						}
						echo "</table>";
						echo "Total Units =". $totalunit.";   Units Completed = ".$unittaken.  ";   Total Points = ".$totalsgp. ";   GPA = ".@substr($totalsgp/$unittaken, 0,3)."<br><hr>";
					}else{ 
					if(!@$reg[$c]){}
						}
					//mysql_close($zalongwa);
					//mysql_free_result($result);
					$c++;
			 }
		
}else{
$reg = $_POST['regno'];

for(;;){
		if($c > sizeof($reg)){
				break;
			}
			@$updateSQL = "SELECT student.Name,
			student.ProgrammeofStudy,
       examresult.RegNo,
       examresult.ExamNo,
       examresult.CourseCode,
       course.Units,
       examresult.Grade,
       examresult.AYear,
       examresult.Remarks,
       examresult.Status,
	   examresult.SemesterID,
	   examresult.Comment
FROM examresult
   INNER JOIN course ON (examresult.CourseCode = course.CourseCode)
   INNER JOIN student ON (examresult.RegNo = student.RegNo)
				WHERE  examresult.RegNo = '$reg[$c]' ";
           	$result = mysql_query($updateSQL) or die("Mwanafunzi huyu hana matokeo"); 
			$query = @mysql_query($updateSQL) or die("Cannot query the database.<br>" . mysql_error());
			$row_result = mysql_fetch_array($result);
			$name = $row_result['Name'];
			$degree = $row_result['ProgrammeofStudy'];
			
			if (mysql_num_rows($query) > 0){
			
					$totalunit=0;
					$unittaken=0;
					$sgp=0;
					$totalsgp=0;
					$gpa=0;
					
			echo "$c". ".$name  $reg[$c] '$degree'";
			echo "<table border='1'>";
			echo "<tr><td> S/No </td><td>Year </td><td> Course</td><td> Unit </td><td> Grade </td><td> Remarks</td><td> Core/Option</td></tr>";
			$i=1;
				while($result = mysql_fetch_array($query)) {
					
					
					$unit = $result['Units'];
					$totalunit=$totalunit+$unit;
					
					$ayear = $result['AYear'];
					$remarks = $result['Remarks'];
					$status = $result['Status'];
					$grade = $result['Grade'];
					$comment = $result['Comment'];
										
					
					if ($grade=='A'){
							$point=5;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B+'){
							$point=4;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B'){
							$point=3;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='C'){
							$point=2;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='D'){
							$point=1;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='E'){
							$point=0;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}else
							$sgp='';
							
					$coursecode = $result['CourseCode'];
					//$totalRows_result = mysql_num_rows($result);
					
								echo "<tr><td>$i</td>";
								echo "<td>$ayear</td>";
								echo "<td>$coursecode</td>";
								echo "<td>$unit</td>";
								echo "<td>$grade</td>";
								echo "<td>$remarks</td>";
								echo "<td>$status</td></tr>";
							$i=$i+1;
						}
						echo "</table>";
						echo "Total Units =". $totalunit.";   Units Completed = ".$unittaken.  ";   Total Points = ".$totalsgp. ";   GPA = ".@substr($totalsgp/$unittaken, 0,3)."<br><hr>";
					}else{ 
					if(!@$reg[$c]){}
						}
					//mysql_close($zalongwa);
					//mysql_free_result($result);
					$c++;
			 }
		}			
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang=en-US>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="keywords" content="ZALONGWA, zalongwa, Student Information System (SIS), accommodation information system, database system, examination database system, student records system, database system">
<meta name="description" content="School Management Information System, Database System, Juma Lungo Lungo Lungo Lungo, Database System , Student Information System (SIS), Accommodation Record Keeping, Examination Results System, student Normninal Roll Database">
<meta name="rating" content="General">
<meta name="Generator" content="Macromedia Dreamweaver  UtraDev 4.1">
<meta name="authors" content="Juma Hemed Lungo">
<meta name="robots" content="all">

<meta http-equiv="Content-Language" content="pt">
<meta name="VI60_defaultClientScript" content="JavaScript">

<title>OUT Student Information System</title>

</head>