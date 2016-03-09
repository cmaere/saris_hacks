<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Examination';
	$szSubSection = 'Cumulative Points';
	$szTitle = 'Examination Cumulative Points';
	include('lecturerheader.php');
$editFormAction = $_SERVER['PHP_SELF'];

mysql_select_db($database_zalongwa, $zalongwa);
$query_studentlist = "SELECT RegNo, Name, ProgrammeofStudy FROM student ORDER BY ProgrammeofStudy  ASC";
$studentlist = mysql_query($query_studentlist, $zalongwa) or die(mysql_error());
$row_studentlist = mysql_fetch_assoc($studentlist);
$totalRows_studentlist = mysql_num_rows($studentlist);

mysql_select_db($database_zalongwa, $zalongwa);
$query_degree = "SELECT ProgrammeCode, ProgrammeName FROM programme ORDER BY ProgrammeName ASC";
$degree = mysql_query($query_degree, $zalongwa) or die(mysql_error());
$row_degree = mysql_fetch_assoc($degree);
$totalRows_degree = mysql_num_rows($degree);

mysql_select_db($database_zalongwa, $zalongwa);
$query_ayear = "SELECT AYear FROM academicyear ORDER BY AYear DESC";
$ayear = mysql_query($query_ayear, $zalongwa) or die(mysql_error());
$row_ayear = mysql_fetch_assoc($ayear);
$totalRows_ayear = mysql_num_rows($ayear);

mysql_select_db($database_zalongwa, $zalongwa);
$query_dept = "SELECT Faculty, DeptName FROM department ORDER BY DeptName, Faculty ASC";
$dept = mysql_query($query_dept, $zalongwa) or die(mysql_error());
$row_dept = mysql_fetch_assoc($dept);
$totalRows_dept = mysql_num_rows($dept);
?>
<?php
			
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
?>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>

<h4 align="center">

<?php 
$prog=$_POST['degree'];
$cohotyear = $_POST['cohot'];
$ayear = $_POST['ayear'];
$qprog= "SELECT ProgrammeCode, Title FROM programme WHERE ProgrammeCode='$prog'";
$dbprog = mysql_query($qprog);
$row_prog = mysql_fetch_array($dbprog);
$progname = $row_prog['Title'];
$qyear= "SELECT AYear FROM academicyear WHERE AYear='$cohotyear'";
$dbyear = mysql_query($qyear);
$row_year = mysql_fetch_array($dbyear);
$year = $row_year['AYear'];
echo $progname;
echo " - ".$year;
?>
<br>
</h4>
<?php

//$reg = $_POST['regno'];
@$checkdegree = addslashes($_POST['checkdegree']);
@$checkyear = addslashes($_POST['checkyear']);
@$checkcohot = addslashes($_POST['checkcohot']);

$c=0;

if (($checkdegree=='on') && ($checkyear == 'on') && ($checkcohot == 'on')){

$deg=addslashes($_POST['degree']);
$year = addslashes($_POST['ayear']);
$cohot = addslashes($_POST['cohot']);

//query student list
$qstudent = "SELECT Name, RegNo, Sex, ProgrammeofStudy FROM student WHERE (ProgrammeofStudy = '$deg') AND (EntryYear = '$cohot') ORDER BY RegNo";
$dbstudent = mysql_query($qstudent);
$totalstudent = mysql_num_rows($dbstudent);
$i=1;
	while($rowstudent = mysql_fetch_array($dbstudent)) {
			$name = $rowstudent['Name'];
			$regno = $rowstudent['RegNo'];
			$sex = $rowstudent['Sex'];

			# get all courses for this candidate
			$qcourse="SELECT DISTINCT course.Units, course.Department, course.StudyLevel, examresult.CourseCode FROM 
						course INNER JOIN examresult ON (course.CourseCode = examresult.CourseCode)
							 WHERE 
									(RegNo='$regno') AND 
							 		(course.Programme = '$deg') AND 
									(AYear='$year')";	
			$dbcourse = mysql_query($qcourse) or die("No Exam Results for the Candidate - $key ");
			$dbcourseUnit = mysql_query($qcourse);
			$total_rows = mysql_num_rows($dbcourse);
			
			if($total_rows>0){

			#initialise
			$totalunit=0;
			$unittaken=0;
			$sgp=0;
			$totalsgp=0;
			$gpa=0;
			$key = $regno; 
			?>
			
			<table width="100%" height="100%" border="1" cellpadding="0" cellspacing="0">
				  <tr>
					<td width="20" rowspan="2" nowrap scope="col"><div align="left"></div> <?php echo $i ?></td>
					<td width="160" rowspan="2" nowrap scope="col"><?php echo $name.": ".$regno; ?> </td>
					<td width="13" rowspan="2" nowrap><div align="center"><?php echo $sex ?></div></td>
							
							<td><div align="center">Units</div></td>
							<td><div align="center">Points</div></td>
							<td><div align="center">GPA</div></td>
							<td><div align="center">Grade</div></td>
							<td scope="col">Remarks</td>
				  </tr>
				  <tr>
					<?php while($row_course = mysql_fetch_array($dbcourseUnit)){
						$course= $row_course['CourseCode'];
						$unit = $row_course['Units'];
						$name = $row_course['CourseName'];
						$coursefaculty = $row_course['Department'];
						$sn=$sn+1;
						$remarks = 'remarks';							
						$RegNo = $key;
						#insert grading results
						include 'includes/choose_studylevel.php';
				
					} //ends while row_course loop
				
				?>
				<td><div align="center"><?php $gunits = $unittaken +$gunits; echo $unittaken;  ?></div></td>
				<td><div align="center"><?php $gpoints = $totalsgp + $gpoints; echo $totalsgp;  ?></div></td>
				<td><div align="center"><?php $gpa = @substr($totalsgp/$unittaken, 0,3); echo $gpa; ?></div></td>
				<td><div align="center"><?php #get student remarks
				$qremarks = "SELECT Remark FROM studentremark WHERE RegNo='$regno'";
				$dbremarks = mysql_query($qremarks);
				$row_remarks = mysql_fetch_assoc($dbremarks);
				$totalremarks = mysql_num_rows($dbremarks);
				$studentremarks = $row_remarks['Remark'];
				if(($totalremarks>0)&&($studentremarks<>'')){
					$remark = $studentremarks;
				}else{
				
						if ($gpa  >= 4.4){
							$remark = 'PASS';
							echo 'A';
										}
						elseif ($gpa >= 3.5){
							$remark = 'PASS';
							echo 'B+';
							}
						elseif ($gpa >= 2.7){
							$remark = 'PASS';
							echo 'B';
							}
						elseif ($gpa >= 2.0){
							$remark = 'PASS';
							echo 'C';
							}
						elseif ($gpa >= 1.0){
							$remark = 'FAIL';
							echo 'D';
							}
						else{
							$remark = 'FAIL';
							echo 'E';
							}
						}
				?></div></td>
				<td><div align="left">
					<?php echo $remark;
					?>	
		      </div></td>
		    </tr>
         </table>
         <?php $i=$i+1;
		   } //ends if $total_rows
		}//ends $rowstudent loop

}elseif (($checkdegree=='on') && ($checkcohot == 'on')){				
//query student list

$deg=$_POST['degree'];
$year = $_POST['ayear'];
$cohot = $_POST['cohot'];
$dept = $_POST['dept'];

//query student list
$qstudent = "SELECT Name, RegNo, Sex, ProgrammeofStudy FROM student WHERE (ProgrammeofStudy = '$deg') AND (EntryYear = '$cohot') ORDER BY RegNo";
$dbstudent = mysql_query($qstudent);
$totalstudent = mysql_num_rows($dbstudent);
$i=1;
	while($rowstudent = mysql_fetch_array($dbstudent)) {
			$name = $rowstudent['Name'];
			$regno = $rowstudent['RegNo'];
			$sex = $rowstudent['Sex'];

			# get all courses for this candidate
			$qcourse="SELECT DISTINCT course.Units, course.Department, examresult.CourseCode FROM 
						course INNER JOIN examresult ON (course.CourseCode = examresult.CourseCode)
							 WHERE RegNo='$regno'";	
			$dbcourse = mysql_query($qcourse) or die("No Exam Results for the Candidate - $key ");
			$dbcourseUnit = mysql_query($qcourse);
			$total_rows = mysql_num_rows($dbcourse);
			
			if($total_rows>0){

			#initialise
			$totalunit=0;
			$unittaken=0;
			$sgp=0;
			$totalsgp=0;
			$gpa=0;
			$key = $regno; 
			?>
			
			<table width="100%" height="100%" border="1" cellpadding="0" cellspacing="0">
				  <tr>
					<td width="20" rowspan="2" nowrap scope="col"><div align="left"></div> <?php echo $i ?></td>
					<td width="160" rowspan="2" nowrap scope="col"><?php echo $name.": ".$regno; ?> </td>
					<td width="13" rowspan="2" nowrap><div align="center"><?php echo $sex ?></div></td>
							
							<td><div align="center">Units</div></td>
							<td><div align="center">Points</div></td>
							<td><div align="center">GPA</div></td>
							<td><div align="center">Grade</div></td>
							<td scope="col">Remarks</td>
				  </tr>
				  <tr>
					<?php while($row_course = mysql_fetch_array($dbcourseUnit)){
						$course= $row_course['CourseCode'];
						$unit = $row_course['Units'];
						$name = $row_course['CourseName'];
						$coursefaculty = $row_course['Department'];
						$sn=$sn+1;
						$remarks = 'remarks';							
							
							#query Coursework
							$qtest2 = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$key' AND ExamCategory=4";
							$dbtest2=mysql_query($qtest2);
							$total_test2 = mysql_num_rows($dbtest2);
							$row_test2=mysql_fetch_array($dbtest2);
							$value_test2score=$row_test2['ExamScore'];
							if(($total_test2>0)&&($value_test2score<>'')){
								$test2date=$row_test2['ExamDate'];
								$test2score=$value_test2score;
							}else{
								$remarks = "Inc";
								$test2score='';
							}

							#query Annual Exam
							$qae = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$key' AND ExamCategory=5";
							$dbae=mysql_query($qae);
							$total_ae = mysql_num_rows($dbae);
							$row_ae=mysql_fetch_array($dbae);
							$value_aescore=$row_ae['ExamScore'];
							if(($total_ae>0)&&($value_aescore<>'')){
								$aedate=$row_ae['ExamDate'];
								$aescore=$value_aescore;
							}else{
								$remarks = "Inc";
								$aescore='';
							}
							
							#query Special Exam
							$qsp = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$key' AND ExamCategory=7";
							$dbsp=mysql_query($qsp);
							$row_sp=mysql_fetch_array($dbsp);
							$row_sp_total=mysql_num_rows($dbsp);
							$svalue_pcore=$row_sp['ExamScore'];
							if(($row_sp_total>0)&&($svalue_pcore<>'')){
								$spdate=$row_sp['ExamDate'];
								$spcore=$svalue_pcore;
								$remarks = "sp";
							}
							
							#Check if has a Supplimentatary Exam
							$qsup = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$key' AND ExamCategory=6";
							$dbsup=mysql_query($qsup);
							$row_sup=mysql_fetch_array($dbsup);
							$row_sup_total=mysql_num_rows($dbsup);
							$supdate=$row_sup['ExamDate'];
							$supscore=$row_sup['ExamScore'];

							#get total marks
							if (($row_sup_total>0)&&($supscore<>'')){
								$marks=$supscore;
							}elseif(($row_sp_total>0)&&($spscore<>'')){
								$marks = $test2score + $spcore;
							}else{
								$marks = $test2score + $aescore;
							}
							
						#grade marks
						if($remarks =='Inc'){
						$grade='I';
						$remark = 'Inc.';
						$point=0;
						$sgp=$point*$unit;
						$totalsgp=$totalsgp+$sgp;
						$unittaken=$unittaken+$unit;
						}elseif($marks ==-2){
								$grade='PASS';
								$remark = 'PASS';
						}else{
							if($marks>=70){
								if ($coursefaculty <> 'Department of Sciences'){
										if ($test2score<16){
										$remark = 'Fail CWK';
										$grade='';
										$point=0;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
										}elseif ($aescore<24){
										$remark = 'Fail Exam';
										$grade='';
										$point=0;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
									}else{
										$grade='A';
										$remark = 'PASS';
										$point=5;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
										}
								}else{
								$grade='A';
								$remark = 'PASS';
								$point=5;
								$sgp=$point*$unit;
								$totalsgp=$totalsgp+$sgp;
								$unittaken=$unittaken+$unit;
								}
							}elseif($marks>=60){
								if ($coursefaculty <> 'Department of Sciences'){
										if ($test2score<16){
										$remark = 'Fail CWK';
										$grade='';
										$point=0;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
										}elseif ($aescore<24){
										$remark = 'Fail Exam';
										$grade='';
										$point=0;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
									}else{
										$grade='B+';
										$remark = 'PASS';
										$point=4;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
										}
								}else{
								$grade='B+';
								$remark = 'PASS';
								$point=4;
								$sgp=$point*$unit;
								$totalsgp=$totalsgp+$sgp;
								$unittaken=$unittaken+$unit;
								}
							}elseif($marks>=50){
								if ($coursefaculty <> 'Department of Sciences'){
										if ($test2score<16){
										$remark = 'Fail CWK';
										$grade='';
										$point=0;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
										}elseif ($aescore<24){
										$remark = 'Fail Exam';
										$grade='';
										$point=0;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
									}else{
										$grade='B';
										$remark = 'PASS';
										$point=3;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
										}
								}else{
								$grade='B';
								$remark = 'PASS';
								$point=3;
								$sgp=$point*$unit;
								$totalsgp=$totalsgp+$sgp;
								$unittaken=$unittaken+$unit;
								}
							}elseif($marks>=40){
								if ($coursefaculty <> 'Department of Sciences'){
										if ($test2score<16){
										$remark = 'Fail CWK';
										$grade='';
										$point=0;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
										}elseif ($aescore<24){
										$remark = 'Fail Exam';
										$grade='';
										$point=0;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
									}else{
										$grade='C';
										$remark = 'PASS';
										$point=2;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
										}
								}else{
								$grade='C';
								$remark = 'PASS';
								$point=2;
								$sgp=$point*$unit;
								$totalsgp=$totalsgp+$sgp;
								$unittaken=$unittaken+$unit;
								}
							}elseif($marks>=30){
								$grade='D';
								$remark = 'FAIL';
								$point=1;
								$sgp=$point*$unit;
								$totalsgp=$totalsgp+$sgp;
								$unittaken=$unittaken+$unit;
							}else{
								$grade='E';
								$remark = 'FAIL';
								$point=0;
								$sgp=$point*$unit;
								$totalsgp=$totalsgp+$sgp;
								$unittaken=$unittaken+$unit;
							}
				}//	ends grade remarks
				?>
				
				
				<?php } //ends while row_course loop
				
				?>
				<td><div align="center"><?php $gunits = $unittaken +$gunits; echo $unittaken;  ?></div></td>
				<td><div align="center"><?php $gpoints = $totalsgp + $gpoints; echo $totalsgp;  ?></div></td>
				<td><div align="center"><?php $gpa = @substr($totalsgp/$unittaken, 0,3); echo $gpa; ?></div></td>
				<td><div align="center"><?php #get student remarks
				$qremarks = "SELECT Remark FROM studentremark WHERE RegNo='$regno'";
				$dbremarks = mysql_query($qremarks);
				$row_remarks = mysql_fetch_assoc($dbremarks);
				$totalremarks = mysql_num_rows($dbremarks);
				$studentremarks = $row_remarks['Remark'];
				if(($totalremarks>0)&&($studentremarks<>'')){
					$remark = $studentremarks;
				}else{
				
						if ($gpa  >= 4.4){
							$remark = 'PASS';
							echo 'A';
										}
						elseif ($gpa >= 3.5){
							$remark = 'PASS';
							echo 'B+';
							}
						elseif ($gpa >= 2.7){
							$remark = 'PASS';
							echo 'B';
							}
						elseif ($gpa >= 2.0){
							$remark = 'PASS';
							echo 'C';
							}
						elseif ($gpa >= 1.0){
							$remark = 'FAIL';
							echo 'D';
							}
						else{
							$remark = 'FAIL';
							echo 'E';
							}
						}
				?></div></td>
				<td><div align="left">
					<?php echo $remark;
					?>	
		      </div></td>
		    </tr>
         </table>
         <?php $i=$i+1;
		   } //ends if $total_rows
		}//ends $rowstudent loop
 
}elseif ($checkcohot == 'on'){				
//query student list

$deg=addslashes($_POST['degree']);
$year = addslashes($_POST['ayear']);
$cohot = addslashes($_POST['cohot']);

//query student list
$qstudent = "SELECT Name, RegNo, Sex, ProgrammeofStudy FROM student WHERE EntryYear = '$cohot' ORDER BY RegNo";
$dbstudent = mysql_query($qstudent);
$totalstudent = mysql_num_rows($dbstudent);
$i=1;
	while($rowstudent = mysql_fetch_array($dbstudent)) {
			$name = $rowstudent['Name'];
			$regno = $rowstudent['RegNo'];
			$sex = $rowstudent['Sex'];

			# get all courses for this candidate
			$qcourse="SELECT DISTINCT course.Units, course.Department, examresult.CourseCode FROM 
						course INNER JOIN examresult ON (course.CourseCode = examresult.CourseCode)
							 WHERE RegNo='$regno'";	
			$dbcourse = mysql_query($qcourse) or die("No Exam Results for the Candidate - $key ");
			$dbcourseUnit = mysql_query($qcourse);
			$total_rows = mysql_num_rows($dbcourse);
			
			if($total_rows>0){

			#initialise
			$totalunit=0;
			$unittaken=0;
			$sgp=0;
			$totalsgp=0;
			$gpa=0;
			$key = $regno; 
			?>
			
			<table width="100%" height="100%" border="1" cellpadding="0" cellspacing="0">
				  <tr>
					<td width="20" rowspan="2" nowrap scope="col"><div align="left"></div> <?php echo $i ?></td>
					<td width="160" rowspan="2" nowrap scope="col"><?php echo $name.": ".$regno; ?> </td>
					<td width="13" rowspan="2" nowrap><div align="center"><?php echo $sex ?></div></td>
							
							<td><div align="center">Units</div></td>
							<td><div align="center">Points</div></td>
							<td><div align="center">GPA</div></td>
							<td><div align="center">Grade</div></td>
							<td scope="col">Remarks</td>
				  </tr>
				  <tr>
					<?php while($row_course = mysql_fetch_array($dbcourseUnit)){
						$course= $row_course['CourseCode'];
						$unit = $row_course['Units'];
						$name = $row_course['CourseName'];
						$coursefaculty = $row_course['Department'];
						$sn=$sn+1;
						$remarks = 'remarks';							
							
							#query Coursework
							$qtest2 = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$key' AND ExamCategory=4";
							$dbtest2=mysql_query($qtest2);
							$total_test2 = mysql_num_rows($dbtest2);
							if($total_test2>0){
								$row_test2=mysql_fetch_array($dbtest2);
								$test2date=$row_test2['ExamDate'];
								$test2score=$row_test2['ExamScore'];
							}else{
								$remarks = "Inc";
								$test2score='';
							}

							#query Coursework
							$qtest2 = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$key' AND ExamCategory=4";
							$dbtest2=mysql_query($qtest2);
							$total_test2 = mysql_num_rows($dbtest2);
							$row_test2=mysql_fetch_array($dbtest2);
							$value_test2score=$row_test2['ExamScore'];
							if(($total_test2>0)&&($value_test2score<>'')){
								$test2date=$row_test2['ExamDate'];
								$test2score=$value_test2score;
							}else{
								$remarks = "Inc";
								$test2score='';
							}

							#query Annual Exam
							$qae = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$key' AND ExamCategory=5";
							$dbae=mysql_query($qae);
							$total_ae = mysql_num_rows($dbae);
							$row_ae=mysql_fetch_array($dbae);
							$value_aescore=$row_ae['ExamScore'];
							if(($total_ae>0)&&($value_aescore<>'')){
								$aedate=$row_ae['ExamDate'];
								$aescore=$value_aescore;
							}else{
								$remarks = "Inc";
								$aescore='';
							}
							
							#query Special Exam
							$qsp = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$key' AND ExamCategory=7";
							$dbsp=mysql_query($qsp);
							$row_sp=mysql_fetch_array($dbsp);
							$row_sp_total=mysql_num_rows($dbsp);
							$svalue_pcore=$row_sp['ExamScore'];
							if(($row_sp_total>0)&&($svalue_pcore<>'')){
								$spdate=$row_sp['ExamDate'];
								$spcore=$svalue_pcore;
								$remarks = "sp";
							}
							
							#Check if has a Supplimentatary Exam
							$qsup = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$key' AND ExamCategory=6";
							$dbsup=mysql_query($qsup);
							$row_sup=mysql_fetch_array($dbsup);
							$row_sup_total=mysql_num_rows($dbsup);
							$supdate=$row_sup['ExamDate'];
							$supscore=$row_sup['ExamScore'];

							#get total marks
							if (($row_sup_total>0)&&($supscore<>'')){
								$marks=$supscore;
							}elseif(($row_sp_total>0)&&($spscore<>'')){
								$marks = $test2score + $spcore;
							}else{
								$marks = $test2score + $aescore;
							}
							
						#grade marks
						if($remarks =='Inc'){
						$grade='I';
						$remark = 'Inc.';
						$point=0;
						$sgp=$point*$unit;
						$totalsgp=$totalsgp+$sgp;
						$unittaken=$unittaken+$unit;
						}elseif($marks ==-2){
								$grade='PASS';
								$remark = 'PASS';
						}else{
							if($marks>=70){
								if ($coursefaculty <> 'Department of Sciences'){
										if ($test2score<16){
										$remark = 'Fail CWK';
										$grade='';
										$point=0;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
										}elseif ($aescore<24){
										$remark = 'Fail Exam';
										$grade='';
										$point=0;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
									}else{
										$grade='A';
										$remark = 'PASS';
										$point=5;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
										}
								}else{
								$grade='A';
								$remark = 'PASS';
								$point=5;
								$sgp=$point*$unit;
								$totalsgp=$totalsgp+$sgp;
								$unittaken=$unittaken+$unit;
								}
							}elseif($marks>=60){
								if ($coursefaculty <> 'Department of Sciences'){
										if ($test2score<16){
										$remark = 'Fail CWK';
										$grade='';
										$point=0;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
										}elseif ($aescore<24){
										$remark = 'Fail Exam';
										$grade='';
										$point=0;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
									}else{
										$grade='B+';
										$remark = 'PASS';
										$point=4;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
										}
								}else{
								$grade='B+';
								$remark = 'PASS';
								$point=4;
								$sgp=$point*$unit;
								$totalsgp=$totalsgp+$sgp;
								$unittaken=$unittaken+$unit;
								}
							}elseif($marks>=50){
								if ($coursefaculty <> 'Department of Sciences'){
										if ($test2score<16){
										$remark = 'Fail CWK';
										$grade='';
										$point=0;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
										}elseif ($aescore<24){
										$remark = 'Fail Exam';
										$grade='';
										$point=0;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
									}else{
										$grade='B';
										$remark = 'PASS';
										$point=3;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
										}
								}else{
								$grade='B';
								$remark = 'PASS';
								$point=3;
								$sgp=$point*$unit;
								$totalsgp=$totalsgp+$sgp;
								$unittaken=$unittaken+$unit;
								}
							}elseif($marks>=40){
								if ($coursefaculty <> 'Department of Sciences'){
										if ($test2score<16){
										$remark = 'Fail CWK';
										$grade='';
										$point=0;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
										}elseif ($aescore<24){
										$remark = 'Fail Exam';
										$grade='';
										$point=0;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
									}else{
										$grade='C';
										$remark = 'PASS';
										$point=2;
										$sgp=$point*$unit;
										$totalsgp=$totalsgp+$sgp;
										$unittaken=$unittaken+$unit;
										}
								}else{
								$grade='C';
								$remark = 'PASS';
								$point=2;
								$sgp=$point*$unit;
								$totalsgp=$totalsgp+$sgp;
								$unittaken=$unittaken+$unit;
								}
							}elseif($marks>=30){
								$grade='D';
								$remark = 'FAIL';
								$point=1;
								$sgp=$point*$unit;
								$totalsgp=$totalsgp+$sgp;
								$unittaken=$unittaken+$unit;
							}else{
								$grade='E';
								$remark = 'FAIL';
								$point=0;
								$sgp=$point*$unit;
								$totalsgp=$totalsgp+$sgp;
								$unittaken=$unittaken+$unit;
							}
				}//	ends grade remarks
				?>

				<?php } //ends while row_course loop
				
				?>
				<td><div align="center"><?php $gunits = $unittaken +$gunits; echo $unittaken;  ?></div></td>
				<td><div align="center"><?php $gpoints = $totalsgp + $gpoints; echo $totalsgp;  ?></div></td>
				<td><div align="center"><?php $gpa = @substr($totalsgp/$unittaken, 0,3); echo $gpa; ?></div></td>
				<td><div align="center"><?php #get student remarks
				$qremarks = "SELECT Remark FROM studentremark WHERE RegNo='$regno'";
				$dbremarks = mysql_query($qremarks);
				$row_remarks = mysql_fetch_assoc($dbremarks);
				$totalremarks = mysql_num_rows($dbremarks);
				$studentremarks = $row_remarks['Remark'];
				if(($totalremarks>0)&&($studentremarks<>'')){
					$remark = $studentremarks;
				}else{
				
						if ($gpa  >= 4.4){
							$remark = 'PASS';
							echo 'A';
										}
						elseif ($gpa >= 3.5){
							$remark = 'PASS';
							echo 'B+';
							}
						elseif ($gpa >= 2.7){
							$remark = 'PASS';
							echo 'B';
							}
						elseif ($gpa >= 2.0){
							$remark = 'PASS';
							echo 'C';
							}
						elseif ($gpa >= 1.0){
							$remark = 'FAIL';
							echo 'D';
							}
						else{
							$remark = 'FAIL';
							echo 'E';
							}
						}
				?></div></td>
				<td><div align="left">
					<?php echo $remark;
					?>	
		      </div></td>
		    </tr>
         </table>
         <?php $i=$i+1;
		   } //ends if $total_rows
		}//ends $rowstudent loop
 }
}else{
?>

<form name="form1" method="post" action="<?php echo $editFormAction ?>">
            <div align="center">
			<table width="200" border="0">
                <tr>
                  <td colspan="3"><span class="style61">if you want to filter the results by  criteria <span class="style34">Tick the corresponding check box first</span> then select appropriately </span></td>
                </tr>
                <tr>
                  <td nowrap><input name="checkdegree" type="checkbox" id="checkdegree" value="on"></td>
                  <td nowrap><div align="left">Degree Programme:</div></td>
                  <td>
                      <div align="left">
                        <select name="degree" id="degree">
                          <?php
do {  
?>
                          <option value="<?php echo $row_degree['ProgrammeCode']?>"><?php echo $row_degree['ProgrammeName']?></option>
                          <?php
} while ($row_degree = mysql_fetch_assoc($degree));
  $rows = mysql_num_rows($degree);
  if($rows > 0) {
      mysql_data_seek($degree, 0);
	  $row_degree = mysql_fetch_assoc($degree);
  }
?>
                        </select>
                    </div></td></tr>
                <tr>
                  <td><input name="checkcohot" type="checkbox" id="checkcohot" value="on"></td>
                  <td nowrap><div align="left">Cohort of the  Year: </div></td>
                  <td><div align="left">
                    <select name="cohot" id="cohot">
                        <?php
do {  
?>
                        <option value="<?php echo $row_ayear['AYear']?>"><?php echo $row_ayear['AYear']?></option>
                        <?php
} while ($row_ayear = mysql_fetch_assoc($ayear));
  $rows = mysql_num_rows($ayear);
  if($rows > 0) {
      mysql_data_seek($ayear, 0);
	  $row_ayear = mysql_fetch_assoc($ayear);
  }
?>
                    </select>
                  </div></td>
                </tr>
            	<tr>
                  <td><input name="checkyear" type="checkbox" id="checkyear" value="on"></td>
                  <td nowrap><div align="left">Results of the  Year: </div></td>
                  <td><div align="left">
                    <select name="ayear" id="ayear">
                        <?php
do {  
?>
                        <option value="<?php echo $row_ayear['AYear']?>"><?php echo $row_ayear['AYear']?></option>
                        <?php
} while ($row_ayear = mysql_fetch_assoc($ayear));
  $rows = mysql_num_rows($ayear);
  if($rows > 0) {
      mysql_data_seek($ayear, 0);
	  $row_ayear = mysql_fetch_assoc($ayear);
  }
?>
                    </select>
                  </div></td>
                </tr>
                <tr>
                  <td colspan="3"><div align="center"></div></td>
                </tr>
              </table>

              <input name="action" type="submit" id="action" value="Print Results"> 
              <input name="MM_update" type="hidden" id="MM_update" value="form1">       
  </div>
</form>
<?php
}
include('../footer/footer.php');
?>