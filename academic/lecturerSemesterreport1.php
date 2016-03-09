<?php
#start pdf
if (isset($_POST['PDF']) && ($_POST['PDF'] == "Print PDF")){
	#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
	require_once('../Connections/zalongwa.php');

	#Get Organisation Name
	$qorg = "SELECT * FROM organisation";
	$dborg = mysql_query($qorg);
	$row_org = mysql_fetch_assoc($dborg);
	$org = $row_org['Name'];

	@$checkdegree = addslashes($_POST['checkdegree']);
	@$checkyear = addslashes($_POST['checkyear']);
	@$checkdept = addslashes($_POST['checkdept']);
	@$checkcohot = addslashes($_POST['checkcohot']);

	$prog=$_POST['degree'];
	$cohotyear = $_POST['cohot'];
	$ayear = $_POST['ayear'];
	$qprog= "SELECT ProgrammeCode, Title FROM programme WHERE ProgrammeCode='$prog'";
	$dbprog = mysql_query($qprog);
	$row_prog = mysql_fetch_array($dbprog);
	$progname = $row_prog['Title'];
		
	//calculate year of study
	$entry = intval(substr($cohotyear,0,4));
	$current = intval(substr($ayear,0,4));
	$yearofstudy=$current-$entry;
	
	if($yearofstudy==0){
		$class="FIRST YEAR";
		}elseif($yearofstudy==1){
		$class="SECOND YEAR";
		}elseif($yearofstudy==2){
		$class="THIRDD YEAR";
		}elseif($yearofstudy==3){
		$class="FOURTH YEAR";
		}elseif($yearofstudy==4){
		$class="FIFTH YEAR";
		}elseif($yearofstudy==5){
		$class="SIXTH YEAR";
		}elseif($yearofstudy==6){
		$class="SEVENTHND YEAR";
		}else{
		$class="";
	}
	
	if (($checkdegree=='on') && ($checkyear == 'on') && ($checkcohot == 'on')){
		$deg=addslashes($_POST['degree']);
		$year = addslashes($_POST['ayear']);
		$cohot = addslashes($_POST['cohot']);
		$dept = addslashes($_POST['dept']);
		$sem = addslashes($_POST['sem']);
		
		#determine total number of columns
		$qstd = "SELECT RegNo FROM student WHERE (ProgrammeofStudy = '$deg') AND (EntryYear = '$cohot')";
		$dbstd = mysql_query($qstd);
		$totalcolms = 0;
		while($rowstd = mysql_fetch_array($dbstd)) {
			$stdregno = $rowstd['RegNo'];
			$qstdcourse = "SELECT DISTINCT coursecode FROM examresult WHERE RegNo='$stdregno' and AYear='$year' and Semester = '$sem'";
			$dbstdcourse = mysql_query($qstdcourse);
			$totalstdcourse = mysql_num_rows($dbstdcourse);
			if ($totalstdcourse>$totalcolms){
				$totalcolms = $totalstdcourse;
			}
		}
		#start pdf
		include('includes/PDF.php');
		$pdf = &PDF::factory('l', 'a4');      
		$pdf->open();                         
		$pdf->setCompression(true);           
		$pdf->addPage();  
		$pdf->setFont('Arial', 'I', 8);     
		$pdf->text(50, 580.28, 'Printed On '.$today = date("d-m-Y H:i:s"));  		
 
		#put page header
		$x=60;
		$y=50;
		$i=1;
		$pg=1;
		$pdf->text(800.89,580.28, 'Page '.$pg);   
		
		#count unregistered
		$j=0;
		#count sex
		$fmcount = 0;
		$mcount = 0;
		$fcount = 0;
		
		#print header for landscape paper layout 
		$playout ='l'; 
		include '../includes/orgname.php';
		
		$pdf->setFillColor('rgb', 0, 0, 0);    
		$pdf->setFont('Arial', '', 13);      
		$pdf->text($x+185, $y+14, 'ACADEMIC YEAR: '.$year.' - '.$sem); 
		$pdf->text($x+200, $y+28, 'PROGRAMME RECORD SHEET');
		$pdf->text($x+6, $y+48, $class.' - '.strtoupper($progname)); 
		#reset values of x,y
		$x=50; $y=$y+54;
		
		#set table header
		$pdf->setFont('Arial', '', 8); 
		$pdf->line($x, $y, 825.89, $y); 
		$pdf->line($x, $y+15, 825.89, $y+15); 
		$pdf->line($x, $y, $x, $y+15); 			$pdf->text($x+2, $y+12, 'S/No');
		$pdf->line($x+25, $y, $x+25, $y+15);	$pdf->text($x+27, $y+12, 'RegNo');
		#adjust x vlaue
		$x=$x-85;
		$pdf->line($x+176, $y, $x+176, $y+15);	$pdf->text($x+180, $y+12, 'Sex');
		$pdf->line($x+200, $y, $x+200, $y+15);	$pdf->text($x+275, $y+12, 'COURSES'); 
		#set colm width
		$clmw = 32;
		#get column width factor
		$cwf = 32;
		#calculate course clumns widths
		$cw = $cwf*$totalcolms;
		$x=$x+200;
		$pdf->line($x+$cw, $y, $x+$cw, $y+15);	$pdf->text($x+$cw+1, $y+12, 'Credit');
		$pdf->line($x+$cw+25, $y, $x+$cw+25, $y+15);	$pdf->text($x+$cw+27, $y+12, 'Point'); 
		$pdf->line($x+$cw+60, $y, $x+$cw+60, $y+15);	$pdf->text($x+$cw+67, $y+12, 'GPA'); 
		$pdf->line($x+$cw+95, $y, $x+$cw+95, $y+15);	$pdf->text($x+$cw+97, $y+12, 'Remark');
		$pdf->line(825.89, $y, 825.89, $y+15);  
		$y=$y+15;
		
		#query student list
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
										(examresult.RegNo='$regno') AND 
										(examresult.AYear='$year') AND
										examresult.Semester = '$sem'";	
					$dbcourse = mysql_query($qcourse);
					$dbcourseUnit = mysql_query($qcourse);
					$total_rows = mysql_num_rows($dbcourse);
					
					if($total_rows>0){
		
					#initialise
					$unit=0;
					$totalunit=0;
					$unittaken=0;
					$sgp=0;
					$totalsgp=0;
					$gpa=0;
					$key = $regno; 
					$x=50;
					
					#print student info
					$pdf->setFont('Arial', '', 7); 
					$pdf->line($x, $y, 825.89, $y); 
					$pdf->line($x, $y+45, 825.89, $y+45); 
					$pdf->line($x, $y, $x, $y+45); 			$pdf->text($x+2, $y+26, $i);
					$pdf->line($x+25, $y, $x+25, $y+45);	 $pdf->text($x+27, $y+25, strtoupper($regno)); 
					$x=$x-85;
					$pdf->line($x+176, $y, $x+176, $y+45);	$pdf->text($x+183, $y+26, strtoupper($sex));
					$pdf->line($x+200, $y, $x+200, $y+45);	
					
					#calculate course clumns widths
					$clnspace = $x+200; 
						while($rowcourse = mysql_fetch_array($dbcourse)) { 
							$stdcourse = $rowcourse['CourseCode']; 
							$coursefull = explode(" ",$stdcourse);
							$courseleft = $coursefull[0];
							$courseright = $coursefull[1];
							//$pdf->text($clnspace+2, $y+12, $courseleft);
							//$pdf->text($clnspace+2, $y+24, $courseright);
							$pdf->text($clnspace+2, $y+12, $stdcourse);
							$pdf->line($clnspace, $y+28, $clnspace+$clmw, $y+28);
							$pdf->line($clnspace+$clmw, $y, $clnspace+$clmw, $y+45);
							$clnspace = $clnspace+$clmw;	
						 } 
					#reset colm space
					$clmspace = $x+200; 
							while($row_course = mysql_fetch_array($dbcourseUnit)){
								$course= $row_course['CourseCode'];
								$unit = $row_course['Units'];
								$name = $row_course['CourseName'];
								$coursefaculty = $row_course['Department'];
								$sn=$sn+1;
								$remarks = 'remarks';
								$grade='';
								
								$RegNo = $key;
								#insert grading results
								include 'includes/choose_studylevel.php';
						
						if ($supp=='!'){
						$pdf->text($clmspace+5, $y+24, '('.$marks.')');
						$pdf->text($clmspace+8, $y+40, $grade.''.$supp);
						$supp=''; 	$fsup='!';
						}else{
						$pdf->text($clmspace+5, $y+24, '('.$marks.')');
						$pdf->text($clmspace+8, $y+40, $grade);
						}
						$pdf->line($clmspace+$clmw, $y, $clmspace+$clmw, $y+45);
						$clmspace = $clmspace+$clmw;	
						}
						#fill blank space
						 $emptycolm = $totalcolms - $total_rows;
						 while ($emptycolm  >0){
							$pdf->line($clmspace+$clmw, $y, $clmspace+$clmw, $y+45);
							$clmspace = $clmspace+$clmw;	
							$emptycolm = $emptycolm-1;
							$pdf->line($clnspace, $y+28, $clnspace+$clmw, $y+28);
                            $clnspace = $clnspace+$clmw;
						 }

						$gpa = @substr($totalsgp/$unittaken, 0,3);
						#calculate courses column width
						$cw = $cwf*$totalcolms;
						$x=$x+200;
								$pdf->line($x+$cw, $y, $x+$cw, $y+45);	if ($igrade<>'I'){$pdf->text($x+$cw+1, $y+24, $unittaken); }
								$pdf->line($x+$cw+25, $y, $x+$cw+25, $y+45);	if ($igrade<>'I'){$pdf->text($x+$cw+27, $y+24, $totalsgp); }
								$pdf->line($x+$cw+60, $y, $x+$cw+60, $y+45);	if ($igrade<>'I'){$pdf->text($x+$cw+67, $y+24, $gpa); }
								
								#get student remarks
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
											$gpagrade='A';
														}
										elseif ($gpa >= 3.5){
											$remark = 'PASS';
											$gpagrade= 'B+';
											}
										elseif ($gpa >= 2.7){
											$remark = 'PASS';
											$gpagrade= 'B';
											}
										elseif ($gpa >= 2.0){
											$remark = 'PASS';
											$gpagrade= 'C';
											}
										elseif ($gpa >= 1.0){
											$remark = 'FAIL';
											$gpagrade= 'D';
											}
										else{
											$remark = 'FAIL';
											$gpagrade= 'E';
											}
										}
								$pdf->line($x+$cw+60, $y, $x+$cw+60, $y+45);	if ($igrade<>'I'){$pdf->text($x+$cw+67, $y+40, $gpagrade); }
								$pdf->line($x+$cw+60, $y+28, $x+$cw+95, $y+28);
								$pdf->line($x+$cw+95, $y, $x+$cw+95, $y+45);	if ($igrade<>'I'){$pdf->text($x+$cw+97, $y+14, $remark);}
								$pdf->line(825.89, $y, 825.89, $y+45); 
						
						#check failed exam
						if ($fexm=='#'){
						$pdf->text($x+$cw+97, $y+26, $fexm.' = Fail Exam'); $fexm = ''; $remark ='';
						}
						#check failed exam
						if ($fcwk=='*'){
						$pdf->text($x+$cw+97, $y+38, $fcwk.' = Fail CWK'); $fcwk = ''; $remark ='';
						}
						if ($fsup=='!'){
						$pdf->text($x+$cw+97, $y+42, $fsup.' = Supp'); $fsup = ''; $remark ='';
						}
						if ($igrade=='I'){
						$pdf->text($x+$cw+137, $y+42, $igrade.' = Inc.'); $igrade = ''; $remark ='';
						}
						if ($egrade=='*'){
						$pdf->text($x+$cw+137, $y+26, $egrade.' = C/Repeat.'); $egrade = ''; $remark ='';
						}
					  $i=$i+1;
					  $y=$y+45;
					  if($y>=530.28){
					  #start new page
						$pdf->addPage();  
						$pdf->setFont('Arial', 'I', 8);     
						$pdf->text(50, 580.28, 'Printed On '.$today = date("d-m-Y H:i:s"));   
						
						$x=50;
						$y=50;
						$pg=$pg+1;
						$pdf->text(800.89,580.28, 'Page '.$pg);   
						
						#count unregistered
						$j=0;
						#count sex
						$fmcount = 0;
						$mcount = 0;
						$fcount = 0;
						#set table header
						$pdf->setFont('Arial', '', 9); 
						$pdf->line($x, $y, 825.89, $y); 
						$pdf->line($x, $y+15, 825.89, $y+15); 
						$pdf->line($x, $y, $x, $y+15); 			$pdf->text($x+2, $y+12, 'S/No');
						$pdf->line($x+25, $y, $x+25, $y+15);	$pdf->text($x+27, $y+12, 'RegNo');
						#adjust x vlaue
						$x=$x-85;
						$pdf->line($x+176, $y, $x+176, $y+15);	$pdf->text($x+180, $y+12, 'Sex');
						$pdf->line($x+200, $y, $x+200, $y+15);	$pdf->text($x+275, $y+12, 'COURSES'); 
						
						#calculate course clumns widths
						$cw = $cwf*$totalcolms;
						$x=$x+200;
						$pdf->line($x+$cw, $y, $x+$cw, $y+15);	$pdf->text($x+$cw+1, $y+12, 'Credit');
						$pdf->line($x+$cw+25, $y, $x+$cw+25, $y+15);	$pdf->text($x+$cw+27, $y+12, 'Point'); 
						$pdf->line($x+$cw+60, $y, $x+$cw+60, $y+15);	$pdf->text($x+$cw+67, $y+12, 'GPA'); 
						$pdf->line($x+$cw+95, $y, $x+$cw+95, $y+15);	$pdf->text($x+$cw+97, $y+12, 'Remark');
						$pdf->line(825.89, $y, 825.89, $y+15);  
						$y=$y+15;
						
					  }
				   } //ends if $total_rows
				}//ends $rowstudent loop

#start new page for the keys
$space = 530.28 - $y;
$yind = $y+20; 
if($space<70){
	$pdf->addPage();  

			$x=50;
			$y=50;
			$pg=$pg+1;
			$tpg =$pg;
			$pdf->setFont('Arial', 'I', 8);     
			$pdf->text(50, 530.28, 'Printed On '.$today = date("d-m-Y H:i:s"));   
			$pg=$pg+1;
			$pdf->text(800.89,530.28, 'Page '.$pg);   
			$yind = $y; 
 }
 	$pdf->text(450, $yind, 'Signature of The Dean:  ………………………     ');  
	$pdf->text(450, $yind+12, 'Date: ………………………………………………………       ');
 	$pdf->text(450, $yind+24, 'Signature of the Chairperson of the Senate:  …………………………');  
	$pdf->text(450, $yind+36, 'Date: …………………………………………………………');  

	$pdf->setFont('Arial', 'I', 9); 
	$pdf->text(190.28, $yind, '          ######## END OF EXAM RESULTS ########');   
	#include points calculation keys
	include 'includes/pointskey.php';
	$x=50;
	$y= $yind + 44;
	#table 1
	include 'includes/gradescale.php';

	 #output file
	 $filename = ereg_replace("[[:space:]]+", "",$progname);
	 $pdf->output($filename.'.pdf');
	 }
}//end of print pdf
?>
<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Examination';
	$szSubSection = 'Semester Report';
	$szTitle = ' Semster Reports Examination Results';
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
$query_sem = "SELECT Semester FROM terms ORDER BY Semester";
$sem = mysql_query($query_sem, $zalongwa) or die(mysql_error());
$row_sem = mysql_fetch_assoc($sem);
$totalRows_sem = mysql_num_rows($sem);

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

@$checkdegree = addslashes($_POST['checkdegree']);
@$checkyear = addslashes($_POST['checkyear']);
@$checksem = addslashes($_POST['checksem']);
$checkcohot = addslashes($_POST['checkcohot']);

$c=0;

if (($checkdegree=='on') && ($checkyear == 'on') && ($checksem == 'on')&& ($checkcohot == 'on')){
echo "<br><br>Examination Results for Academic Year - ".$ayear;

$deg=addslashes($_POST['degree']);
$year = addslashes($_POST['ayear']);
$cohot = addslashes($_POST['cohot']);
$sem = addslashes($_POST['sem']); 
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
									(AYear='$year') AND 
									(course.Programme = '$deg') AND 
									(Semester = '$sem')";	
			$dbcourse = mysql_query($qcourse);
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
							<?php while($rowcourse = mysql_fetch_array($dbcourse)) { ?>
							<td><div align="center"><?php echo $rowcourse['CourseCode']; ?></div></td> 
							<?php } ?>
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
				?>
				
				<td><div align="center"><?php echo $grade;?></div></td>
				<?php } //ends while row_course loop
				
				?>
				<td><div align="center"><?php $gunits = $unittaken +$gunits; echo $unittaken;  ?></div></td>
				<td><div align="center"><?php $gpoints = $totalsgp + $gpoints; echo $totalsgp;  ?></div></td>
				<td><div align="center"><?php $gpa = @substr($totalsgp/$unittaken, 0,3); echo $gpa ?></div></td>
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
echo "<br><br>Examination Results for Academic Year - ".$year;
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
									(AYear='$year') ";	
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
							<?php while($rowcourse = mysql_fetch_array($dbcourse)) { ?>
							<td><div align="center"><?php echo $rowcourse['CourseCode']; ?></div></td> 
							<?php } ?>
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
				?>
				
				<td><div align="center"><?php echo $grade;?></div></td>
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

$deg=addslashes($_POST['degree']);
$year = addslashes($_POST['ayear']);
$cohot = addslashes($_POST['cohot']);
echo "<br><br>Examination Results for Academic Year - ".$year;

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
			$qcourse="SELECT DISTINCT course.Units, course.Department, course.StudyLevel, examresult.CourseCode FROM 
						course INNER JOIN examresult ON (course.CourseCode = examresult.CourseCode)
							 WHERE 
									(RegNo='$regno') AND
									(course.Programme = '$deg') 
						";	
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
							<?php while($rowcourse = mysql_fetch_array($dbcourse)) { ?>
							<td><div align="center"><?php echo $rowcourse['CourseCode']; ?></div></td> 
							<?php } ?>
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
				?>
				
				<td><div align="center"><?php echo $grade;?></div></td>
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
			<table width="200" border="0" bgcolor="#CCCCCC">
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
                  <td><input name="checksem" type="checkbox" id="checksem" value="on"></td>
                  <td nowrap><div align="left">Semester: </div></td>
                  <td><div align="left">
                    <select name="sem" id="sem">
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
                  <td colspan="2"><input name="action" type="submit" id="action" value="Print Results"></td>
          		  <td nowrap><input type="submit" name="PDF"  id="PDF" value="Print PDF"></td>
                </tr>
              </table>
              <input name="MM_update" type="hidden" id="MM_update" value="form1">       
  </div>
</form>
<?php
}
include('../footer/footer.php');
?>