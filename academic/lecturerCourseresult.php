<?php 
#start pdf
if (isset($_POST['PDF']) && ($_POST['PDF'] == "Print PDF")){
//if (isset($_POST['search']) && ($_POST['search'] == "Search Results")) { 

	#get post variables
	//$rawkey = addslashes(trim($_POST['key']));
	//$key = ereg_replace("[[:space:]]+", " ",$rawkey);
	$year = trim(addslashes($_POST['ayear']));
	$coursecode = trim(addslashes($_POST['Hall']));	
	
	#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
	require_once('../Connections/zalongwa.php');
				
	#Get Organisation Name
	$qorg = "SELECT * FROM organisation";
	$dborg = mysql_query($qorg);
	$row_org = mysql_fetch_assoc($dborg);
	$org = $row_org['Name'];
	$address = $row_org['Address'];
	$phone = $row_org['tel'];
	$fax = $row_org['fax'];
	$email = $row_org['email'];
	$website = $row_org['website'];
	$city = $row_org['city'];
	
	# get all students for this course
	$qregno="SELECT DISTINCT RegNo FROM 
				 examresult 
					 WHERE (AYear='$year' AND CourseCode = '$coursecode') ORDER BY RegNo";	
	$dbregno = mysql_query($qregno) or die("No Exam Results for the course - $coursecode - in the year - $year ");
	$total_rows = mysql_num_rows($dbregno);
	
	if($total_rows>0){
		#getcourse information
		$qcourseinfo = "SELECT * FROM course WHERE coursecode = '$coursecode'";
		$dbcourseinfo = mysql_query($qcourseinfo);
		$row_courseinfo = mysql_fetch_assoc($dbcourseinfo);
		$coursename=$row_courseinfo['CourseName'];
		$coursedept=$row_courseinfo['Department'];
		$courseunit=$row_courseinfo['Units'];
		$courseyear=$row_courseinfo['YearOffered'];
		
		#start pdf
		include('includes/PDF.php');
		$pdf = &PDF::factory('p', 'a4');      // Set up the pdf object. 
		$pdf->open();                         // Start the document. 
		$pdf->setCompression(true);           // Activate compression. 
		$pdf->addPage();  
		$pdf->setFont('Arial', 'I', 8);     
		$pdf->text(530.28, 825.89, 'Page '.$pg);   
		$pdf->text(50, 825.89, 'Printed On '.$today = date("d-m-Y H:i:s"));   

		#put page header
	
		$x=60;
		$y=74;
		$i=1;
		$pg=1;
		$pdf->text(530.28, 825.89, 'Page '.$pg);   

		//$i=1;
		#count unregistered
		$j=0;
		#count sex
		$fmcount = 0;
		$mcount = 0;
		$fcount = 0;

		#print header for landscape paper layout 
		include '../includes/orgname.php';
		
		$pdf->setFillColor('rgb', 0, 0, 0);    
		$pdf->setFont('Arial', '', 13);      
		//$pdf->text($x+190, $y+14, 'COURSE RECORD SHEET');
	    $pdf->text($x+180, $y+14, 'ACADEMIC YEAR: '.$year); 
		$pdf->text($x+6, $y+34, strtoupper($coursedept)); 
 		#reset values of x,y
		$x=50; $y=$y+40;
 		#table course details
		$pdf->line($x, $y, 570.28, $y);
		$pdf->line($x, $y+15, 570.28, $y+15); 
		$pdf->line($x, $y+30, 570.28, $y+30); 
		$pdf->line($x, $y, $x, $y+30); 
		$pdf->line($x+68, $y, $x+68, $y+30);
		$pdf->line($x+468, $y, $x+468, $y+30);
		$pdf->line(570.28, $y, 570.28, $y+30);
		$pdf->text($x, $y+12, 'Code'); 
		$pdf->text($x+70, $y+12, 'Course Title'); 
		$pdf->text($x+470, $y+12, 'Credits'); 
		$pdf->text($x, $y+27, $coursecode); 
		$pdf->text($x+70, $y+27, $coursename); 
		$pdf->text($x+470, $y+27, $courseunit);

		#reset the value of y
		$y=$y+40;
		#if exam type is Final Exam
		$pdf->setFont('Arial', 'B', 9); 
		$pdf->line($x, $y, 570.28, $y); 
		$pdf->line($x, $y+15, 570.28, $y+15); 
		$pdf->line($x, $y, $x, $y+15); 			$pdf->text($x+2, $y+12, 'S/No');
		$pdf->line($x+35, $y, $x+35, $y+15);	$pdf->text($x+40, $y+12, 'Name');
		$pdf->line($x+196, $y, $x+196, $y+15);	$pdf->text($x+200, $y+12, 'Sex');
		$pdf->line($x+231, $y, $x+231, $y+15);	$pdf->text($x+235, $y+12, 'RegNo');
		$pdf->line($x+340, $y, $x+340, $y+15);	$pdf->text($x+341, $y+12, 'C.A/40');
		$pdf->line($x+370, $y, $x+370, $y+15);	$pdf->text($x+371, $y+12, 'E.Y/60');
		$pdf->line($x+400, $y, $x+400, $y+15);	$pdf->text($x+401, $y+12, 'TOTAL'); 
		$pdf->line($x+430, $y, $x+430, $y+15);	$pdf->text($x+431, $y+12, 'GRADE'); 
		$pdf->line($x+463, $y, $x+463, $y+15);	$pdf->text($x+465, $y+12, 'Remark');
		$pdf->line(570.28, $y, 570.28, $y+15);   
		$pdf->setFont('Arial', '', 9); 
      
		#get coursename
		$qcourse = "Select CourseName, Department, StudyLevel from course where CourseCode = '$coursecode'";
		$dbcourse = mysql_query($qcourse);
		$row_course = mysql_fetch_array($dbcourse);
		$coursename = $row_course['CourseName'];
		$coursefaculty = $row_course['Department'];

		#initiate grade counter
		$countgradeA=0;
		$countgradeBplus=0;
		$countgradeB=0;
		$countgradeC=0;
		$countgradeD=0;
		$countgradeE=0;
		$countgradeI=0;

		$countgradeAm=0;
		$countgradeBplusm=0;
		$countgradeBm=0;
		$countgradeCm=0;
		$countgradeDm=0;
		$countgradeEm=0;
		$countgradeIm=0;

		$countgradeAf=0;
		$countgradeBplusf=0;
		$countgradeBf=0;
		$countgradeCf=0;
		$countgradeDf=0;
		$countgradeEf=0;
		$countgradeIf=0;
		#print title
		$sn=0;
		while($row_regno = mysql_fetch_array($dbregno)){
				$key= $row_regno['RegNo'];
				$course= $coursecode;
				$ayear = $year;
				$units= $row_course['Units'];
				$sn=$sn+1;
				$remarks = 'remarks';
				$grade='';

				#get name and sex of the candidate
				$qstudent = "SELECT Name, Sex from student WHERE RegNo = '$key'";
				$dbstudent = mysql_query($qstudent) or die("Mwanafunzi huyu hana matokeo"); 
				$row_result = mysql_fetch_array($dbstudent);
				$name = $row_result['Name'];
				$sex = strtoupper($row_result['Sex']);
				
				# grade marks
				$RegNo = $key;
				include 'includes/choose_studylevel.php';

			  #update grade counter
			   if ($grade=='A'){
				$countgradeA=$countgradeA+1;
					if($sex=='M'){
						$countgradeAm=$countgradeAm+1;
					}else{
						$countgradeAf=$countgradeAf+1;
					}
				}elseif($grade=='B+'){
					$countgradeBplus=$countgradeBplus+1;
					if($sex=='M'){
						$countgradeBplusm=$countgradeBplusm+1;
					}else{
						$countgradeBplusf=$countgradeBplusf+1;
					}
				}elseif($grade=='B'){
					$countgradeB=$countgradeB+1;
					if($sex=='M'){
						$countgradeBm=$countgradeBm+1;
					}else{
						$countgradeBf=$countgradeBf+1;
					}
			    }elseif($grade=='C'){
					$countgradeC=$countgradeC+1;
					if($sex=='M'){
						$countgradeCm=$countgradeCm+1;
					}else{
						$countgradeCf=$countgradeCf+1;
					}
			   }elseif($grade=='D'){
					$countgradeD=$countgradeD+1;
					if($sex=='M'){
						$countgradeDm=$countgradeDm+1;
					}else{
						$countgradeDf=$countgradeDf+1;
					}
			   }elseif($grade=='E'){
					$countgradeE=$countgradeE+1;
					if($sex=='M'){
						$countgradeEm=$countgradeEm+1;
					}else{
						$countgradeEf=$countgradeEf+1;
					}
			   }else{
					$countgradeI=$countgradeI+1;
					if($sex=='M'){
						$countgradeIm=$countgradeIm+1;
					}else{
						$countgradeIf=$countgradeIf+1;
					}
				}
			 // }
			 
				
		#display results
		
		#calculate summary areas
		$yind = $y+15;
		$dataarea = 820.89-$yind;
		if ($dataarea< 20){
				$pdf->addPage();  
	
				$x=50;
				$y=80;
				$pg=$pg+1;
				$tpg =$pg;
				$pdf->setFont('Arial', 'I', 8);     
				$pdf->text(530.28, 820.89, 'Page '.$pg);  
				$pdf->text(300, 820.89, $copycount);    
				$pdf->text(50, 825.89, 'Printed On '.$today = date("d-m-Y H:i:s"));   
				$yind = $y; 
				$pdf->setFont('Arial', '', 10); 
				#if exam type is Final Exam
				$pdf->setFont('Arial', 'B', 9); 
				$pdf->line($x, $y, 570.28, $y); 
				$pdf->line($x, $y+15, 570.28, $y+15); 
				$pdf->line($x, $y, $x, $y+15); 			$pdf->text($x+2, $y+12, 'S/No');
				$pdf->line($x+35, $y, $x+35, $y+15);	$pdf->text($x+40, $y+12, 'Name');
				$pdf->line($x+196, $y, $x+196, $y+15);	$pdf->text($x+200, $y+12, 'Sex');
				$pdf->line($x+231, $y, $x+231, $y+15);	$pdf->text($x+235, $y+12, 'RegNo');
				$pdf->line($x+340, $y, $x+340, $y+15);	$pdf->text($x+341, $y+12, 'C.A/40');
				$pdf->line($x+370, $y, $x+370, $y+15);	$pdf->text($x+371, $y+12, 'E.Y/60');
				$pdf->line($x+400, $y, $x+400, $y+15);	$pdf->text($x+401, $y+12, 'TOTAL'); 
				$pdf->line($x+430, $y, $x+430, $y+15);	$pdf->text($x+431, $y+12, 'GRADE'); 
				$pdf->line($x+463, $y, $x+463, $y+15);	$pdf->text($x+465, $y+12, 'Remark');
				$pdf->line(570.28, $y, 570.28, $y+15);   
				$pdf->setFont('Arial', '', 9); 
		}
		if ($test2score ==-1){
			$test2score = 'PASS';
		}
		if ($aescore ==-1){
			$aescore = 'PASS';
		} 
		if ($marks == -2) {
			$marks = 'PASS'; 
		}
		$y=$y+15;
			$pdf->setFont('Arial', '', 8.7);    
		$pdf->line($x, $y, 570.28, $y);
		$pdf->line($x, $y+15, 570.28, $y+15); 
		$pdf->line($x, $y, $x, $y+15); 			$pdf->text($x+2, $y+12, $sn);
		$pdf->line($x+35, $y, $x+35, $y+15);	$pdf->text($x+40, $y+12, $name);
		$pdf->line($x+196, $y, $x+196, $y+15);	$pdf->text($x+200, $y+12, strtoupper($sex));
		$pdf->line($x+231, $y, $x+231, $y+15);	$pdf->text($x+232, $y+12, strtoupper($key));
		$pdf->line($x+340, $y, $x+340, $y+15);	$pdf->text($x+342, $y+12, $test2score);
		$pdf->line($x+370, $y, $x+370, $y+15);	$pdf->text($x+372, $y+12, $aescore);
		$pdf->line($x+400, $y, $x+400, $y+15);	$pdf->text($x+402, $y+12, $marks); 
		$pdf->line($x+430, $y, $x+430, $y+15);	$pdf->text($x+432, $y+12, $marks); //$grade); 
		$pdf->line($x+463, $y, $x+463, $y+15);	$pdf->text($x+465, $y+12, $remark);
		$pdf->line(570.28, $y, 570.28, $y+15);   
		$pdf->setFont('Arial', '', 10);
	}
 }
	#calculate summary areas
	$yind = $y+25;
	$summaryarea = 820.89-$yind;
	if ($summaryarea<90){
			$pdf->addPage();  

			$x=50;
			$y=80;
			$pg=$pg+1;
			$tpg =$pg;
			$pdf->setFont('Arial', 'I', 8);     
			$pdf->text(530.28, 820.89, 'Page '.$pg);  
			$pdf->text(300, 820.89, $copycount);    
		    $pdf->text(50, 825.89, 'Printed On '.$today = date("d-m-Y H:i:s"));   
			$yind = $y; 
			$pdf->setFont('Arial', 'I', 10);     
    }else{
	require_once('../Connections/sessioncontrol.php');
   	require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Examination';
	$szSubSection = 'Course Result';
	$szTitle = 'Course Record Sheet Examination Result';
	include('lecturerheader.php');		
	echo 'Ooops!<br>Zalongwa System Found No Records Related to Your Course Selection.';
		exit;
	 }
	@$pdf->setFont('Arial', '', 10); 
	$b=$y+25;
	if ($b<820.89){
		# results summary table 
		$y=$b;
		#draw a line
		$pdf->line($x, $y, 570.28, $y);       
		$pdf->line($x, $y+56, 570.28, $y+56); 
		$pdf->line($x, $y, $x, $y+56); 
		$pdf->line(570.28, $y, 570.28, $y+56);
		#vertical lines
		$pdf->line($x+65, $y, $x+65, $y+56); 	$pdf->line($x+112, $y+14, $x+112, $y+42);  
		$pdf->line($x+145, $y, $x+145, $y+56); 	$pdf->line($x+182, $y+14, $x+182, $y+42);  
		$pdf->line($x+225, $y, $x+225, $y+56); 	$pdf->line($x+272, $y+14, $x+272, $y+42);
		$pdf->line($x+305, $y, $x+305, $y+56); 	$pdf->line($x+352, $y+14, $x+352, $y+42);
		$pdf->line($x+385, $y, $x+385, $y+56); 	$pdf->line($x+417, $y+14, $x+417, $y+42);
		$pdf->line($x+455, $y, $x+455, $y+56); 	$pdf->line($x+487, $y+14, $x+487, $y+42);
		
		#horizontal lines
		$pdf->line($x, $y+14, 570.28, $y+14); 
		$pdf->line($x, $y+28, 570.28, $y+28);  
		$pdf->line($x, $y+42, 570.28, $y+42); 
		#row 1 text
		$pdf->text($x+2, $y+12, 'Grade   '); 
		$pdf->text($x+105, $y+12, '  A   ');
		$pdf->text($x+175, $y+12, '  B+  ');
		$pdf->text($x+265, $y+12, '  B   ');
		$pdf->text($x+345, $y+12, '  C   ');
		$pdf->text($x+410, $y+12, '  D   ');
		$pdf->text($x+480, $y+12, '  F   ');
		#row 2 text
		$pdf->text($x+2, $y+24, 'Gender  '); 
		$pdf->text($x+95, $y+24, 'M        F');
		$pdf->text($x+165, $y+24, 'M        F');
		$pdf->text($x+255, $y+24, 'M        F');
		$pdf->text($x+335, $y+24, 'M        F');
		$pdf->text($x+400, $y+24, 'M        F');
		$pdf->text($x+470, $y+24, 'M        F');
		#row 3 text
		$pdf->text($x+2, $y+37, 'Subtotal  '); 
		$pdf->text($x+95, $y+37, $countgradeAm.'        '.$countgradeAf);
		$pdf->text($x+165, $y+37, $countgradeBplusm.'        '.$countgradeBplusf);
		$pdf->text($x+255, $y+37, $countgradeBm.'        '.$countgradeBf);
		$pdf->text($x+335, $y+37, $countgradeCm.'        '.$countgradeCf);
		$pdf->text($x+400, $y+37, $countgradeDm.'        '.$countgradeDf);
		$pdf->text($x+470, $y+37, $countgradeEm.'        '.$countgradeEf);
		#row 4 text
		/*
		$pdf->text($x+2, $y+53, 'Gandtotal  '); 
		$pdf->text($x+111, $y+53, $countgradeA);
		$pdf->text($x+181, $y+53, $countgradeBplus);
		$pdf->text($x+271, $y+53, $countgradeB);
		$pdf->text($x+351, $y+53, $countgradeC);
		$pdf->text($x+416, $y+53, $countgradeD);
		$pdf->text($x+486, $y+53, $countgradeE);
		*/
		$pdf->text($x+2, $y+53, 'Gandtotal  '); 
		$pdf->text($x+95, $y+53, $countgradeA.'('.number_format($countgradeA*100/$sn,1).'%)');
		$pdf->text($x+165, $y+53, $countgradeBplus.'('.number_format($countgradeBplus*100/$sn,1).'%)');
		$pdf->text($x+255, $y+53, $countgradeB.'('.number_format($countgradeB*100/$sn,1).'%)');
		$pdf->text($x+335, $y+53, $countgradeC.'('.number_format($countgradeC*100/$sn,1).'%)');
		$pdf->text($x+400, $y+53, $countgradeD.'('.number_format($countgradeD*100/$sn,1).'%)');
		$pdf->text($x+470, $y+53, $countgradeE.'('.number_format($countgradeE*100/$sn,1).'%)');

	#reset the value of y
	$y=$y+56;
	#print signature lines
	$pdf->text(120.28, $y+35, '.............................................................                                   ............................');    						
	$pdf->text(130.28, $y+45, '							Course Lecturer\'s Name                                                       Signature');   	
	$pdf->text(120.28, $y+60, '.............................................................                                   ............................');    						
	$pdf->text(130.28, $y+75, '	Date Approved by the Head of the Department                          Signature');   
		
	$pdf->text(120.28, $y+90, '.............................................................                                   ............................');    						
	$pdf->text(130.28, $y+105, 'Date Approved by the Dean of the Faculty                                  Signature');   	
	}					
	#calculate signature areas
	$yind = $y+120;
	$indarea = 820.89-$yind;
	if ($indarea< 203){
			$pdf->addPage();  

			$x=50;
			$y=80;
			$pg=$pg+1;
			$tpg =$pg;
			$pdf->setFont('Arial', 'I', 8);     
			$pdf->text(530.28, 820.89, 'Page '.$pg);  
			$pdf->text(300, 820.89, $copycount);    
		    $pdf->text(50, 825.89, 'Printed On '.$today = date("d-m-Y H:i:s"));   
			$yind = $y; 
    }
	/*
	$pdf->setFont('Arial', 'I', 9); 
	#include points calculation keys
	include 'includes/pointskey.php';
	$x=50;
	$y= $yind + 44;

	#include grade scale
	include 'includes/gradescale.php';
	*/
	
 #output file
 $filename = ereg_replace("[[:space:]]+", "",$coursecode);
 $pdf->output($filename.'.pdf');
}#end if isset pdf
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Examination';
	$szSubSection = 'Course Result';
	$szTitle = 'Course Record Sheet Examination Result';
	include('lecturerheader.php');
	
mysql_select_db($database_zalongwa, $zalongwa);
$query_AcademicYear = "SELECT AYear FROM academicyear ORDER BY AYear DESC";
$AcademicYear = mysql_query($query_AcademicYear, $zalongwa) or die(mysql_error());
$row_AcademicYear = mysql_fetch_assoc($AcademicYear);
$totalRows_AcademicYear = mysql_num_rows($AcademicYear);

mysql_select_db($database_zalongwa, $zalongwa);
//$query_Hostel = "SELECT CourseCode FROM course ORDER BY CourseCode";

#get current year
$qcurrentyear = 'SELECT AYear FROM academicyear where Status = 1';
$dbcurrentyear = mysql_query($qcurrentyear);
$row_current = mysql_fetch_array($dbcurrentyear);
$ayear = $row_current['AYear'];

if ($privilege ==3) {
$query_Hostel = "
		SELECT DISTINCT course.CourseCode 
		FROM examregister 
			INNER JOIN course ON (examregister.CourseCode = course.CourseCode)
		WHERE (examregister.AYear ='$ayear') 
		AND (examregister.RegNo='$username')  ORDER BY course.CourseCode DESC";
}else{
$query_Hostel = "
		SELECT CourseCode FROM course ORDER BY CourseCode";
}

$Hostel = mysql_query($query_Hostel, $zalongwa) or die('query ,$query_Hostel, not executed');
$row_Hostel = mysql_fetch_assoc($Hostel);
$totalRows_Hostel = mysql_num_rows($Hostel);

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

if (isset($_POST['print']) && ($_POST['print'] == "PreView")) {
#get post variables
$year = trim(addslashes($_POST['ayear']));
$coursecode = trim(addslashes($_POST['Hall']));

# get all students for this course
$qregno="SELECT DISTINCT RegNo FROM 
			 examresult 
				 WHERE (AYear='$year' AND CourseCode = '$coursecode') ORDER BY RegNo";	
$dbregno = mysql_query($qregno) or die("No Exam Results for the course - $coursecode - in the year - $year ");
$total_rows = mysql_num_rows($dbregno);

	if($total_rows>0){
	#initialise the table
	?>

	<table width="200" border="1" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><div align="center"><strong>S/No</strong></div></td>
                    <td nowrap><div align="center"><strong>Name</strong></div></td>
					<td nowrap><div align="center"><strong>RegNo</strong></div></td>
					<td><div align="center"><strong>CWK </strong></div></td>
					<td><div align="center"><strong>Exam </strong></div></td>
					<td><div align="center"><strong>Sup </strong></div></td>
					<td><div align="center"><strong>Special </strong></div></td>
					<td><div align="center"><strong>Project </strong></div></td>
					<td><div align="center"><strong>TP </strong></div></td>
					<td><div align="center"><strong>PT </strong></div></td>
                    <td><div align="center"><strong>Total</strong></div></td>
                    <td><div align="center"><strong>Grade</strong></div></td>
                    <td><div align="center"><strong>Remarks</strong></div></td>
                  </tr>
	<?php
		#get coursename
		$qcourse = "Select CourseName, Department, StudyLevel from course where CourseCode = '$coursecode'";
		$dbcourse = mysql_query($qcourse);
		$row_course = mysql_fetch_array($dbcourse);
		$coursename = $row_course['CourseName'];
		$coursefaculty = $row_course['Department'];

		
		#initiate grade counter
		$countgradeA=0;
		$countgradeBplus=0;
		$countgradeB=0;
		$countgradeC=0;
		$countgradeD=0;
		$countgradeE=0;
		$countgradeI=0;

		#print title
		echo 'Year: '.$year.'<br>';
		echo 'Course: '.$coursecode.' - '.$coursename;
		#initialise s/no
		$sn=0;
		
		while($row_regno = mysql_fetch_array($dbregno)){
				$key= $row_regno['RegNo'];
				$course= $coursecode;
				$ayear = $year;
				$units= $row_course['Units'];
				$sn=$sn+1;
				$remarks = 'remarks';
				$grade='';
				
				#get year exam done
				$examyear  = $year; //result of the year
				$examyear  = substr($examyear ,0,4);
				
				#get name and entry year of the candidate
				$qstudent = "SELECT Name from student WHERE RegNo = '$key'";
				$dbstudent = mysql_query($qstudent) or die("Mwanafunzi huyu hana matokeo"); 
				$row_result = mysql_fetch_array($dbstudent);
				$name = $row_result['Name'];
				
				$RegNo = $key;

				include 'includes/choose_studylevel.php';
						
					
				#display results
				?>
                  <tr>
				  <td>
				  	<?php if ($privilege==2){
					//echo "<a href=\"lecturerEditsingleresult.php?Candidate=$key&Course=$course\">$sn</a>" ;
					echo $sn;
					}else{
					echo $sn;
					}?>
					</td>
                    <td nowrap><div align="left"><?php echo $name ?> </div></td>
					<td><div align="center"><?php echo strtoupper($key) ?> </div></td>
					<td><div align="center"><?php if ($test2score ==-1)echo 'PASS';else echo $test2score; ?> </div></td>
					<td><div align="center"><?php if ($aescore ==-1)echo 'PASS'; else echo $aescore; ?> </div></td>
					<td><div align="center"><?php echo $supscore ?> </div></td>
					<td><div align="center"><?php echo $spscore ?> </div></td>
					<td><div align="center"><?php echo $proscore ?> </div></td>
					<td><div align="center"><?php echo $tpscore ?> </div></td>		
					<td><div align="center"><?php echo $ptscore ?> </div></td>			
                    <td><div align="center"><?php if ($marks == -2) echo 'PASS'; else echo $marks; ?> </div></td>
                    <td><div align="center"><?php echo $grade ?> </div></td>
                    <td><div align="center"><?php echo $remark ?></div></td>
                  </tr>
				<?php
			#update grade counter
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

		}
		?>
</table>
<p></p>
<table width="476" border="0" align="center">
  <tr>
    <td colspan="15" nowrap>INTERNAL EXAMINER SUMMARY (GRADE TOTAL) </td>
  </tr>
  <tr>
    <td width="35" nowrap><div align="center">A =</div></td>
    <td width="31" nowrap><?php echo $countgradeA?>
    <div align="center"></div></td>
    <td width="46" nowrap><div align="center">B+ =</div></td>
    <td width="31" nowrap><?php echo $countgradeBplus?>
    <div align="center"></div></td>
    <td width="32" nowrap><div align="center">B =</div></td>
    <td width="21" nowrap><?php echo $countgradeB?>
    <div align="center"></div></td>
    <td width="24" nowrap><div align="center">C =</div></td>
    <td width="21" nowrap><?php echo $countgradeC?>
    <div align="center"></div></td>
    <td width="24" nowrap><div align="center">D =</div></td>
    <td width="21" nowrap><?php echo $countgradeD?>
    <div align="center"></div></td>
    <td width="22" nowrap><div align="center">E =</div></td>
    <td width="21" nowrap><?php echo $countgradeE?>
    <div align="center"></div></td>
    <td width="18" nowrap><div align="center">I =</div></td>
    <td width="21" nowrap><?php echo $countgradeI?>
    <div align="center"></div></td>
    <td width="46">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" nowrap>Internal Exam Officer Signature:</td>
    <td colspan="4" nowrap>........................</td>
    <td colspan="2" nowrap>Date:</td>
    <td colspan="4" nowrap>...................</td>
  </tr>
  <tr>
    <td colspan="15">EXTERNAL EXAMINER SUMMARY (GRADE TOTAL) </td>
  </tr>
  <tr>
    <td nowrap><div align="center">A =</div></td>
    <td nowrap><div align="center"></div></td>
    <td nowrap><div align="center">B+ =</div></td>
    <td nowrap><div align="center"></div></td>
    <td nowrap><div align="center">B =</div></td>
    <td nowrap><div align="center"></div></td>
    <td nowrap><div align="center">C =</div></td>
    <td nowrap><div align="center"></div></td>
    <td nowrap><div align="center">D =</div></td>
    <td nowrap><div align="center"></div></td>
    <td nowrap><div align="center">E =</div></td>
    <td nowrap><div align="center"></div></td>
    <td nowrap><div align="center">I =</div></td>
    <td nowrap><div align="center"></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5" nowrap>Internal Exam Officer Signature:</td>
    <td colspan="4">........................</td>
    <td colspan="2">Date:</td>
    <td colspan="4">...................</td>
  </tr>
  <tr>
    <td colspan="11"><div align="right"><strong>TOTAL CANDIDATES: </strong></div></td>
    <td colspan="4"><strong>
      <?php echo $sn?>
    </strong></td>
  </tr>
</table>

<?php
	//close if total statement
	}else{
			echo 'No Results Founds, Try Again <br>';
			# redisplay the form incase results werenot found
			?>
		
			   <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" name="courseresults" id="courseresults">
					<fieldset >
						<legend>Search Course Results</legend>
					<table width="255" border="0" >
				<tr>
				  <td width="113" nowrap><div align="right"></div></td>
				  <td width="132" bordercolor="#ECE9D8" ><span class="style67">
				  </span></td>
				</tr>
				<tr>
				  <td nowrap><div align="right">Academic Year: </div></td>
				  <td ><select name="ayear" id="select2">
				  <option value="0">SelectAcademicYear</option>
					<?php
		do {  
		?>
					<option value="<?php echo $row_AcademicYear['AYear']?>"><?php echo $row_AcademicYear['AYear']?></option>
					<?php
		} while ($row_AcademicYear = mysql_fetch_assoc($AcademicYear));
		  $rows = mysql_num_rows($AcademicYear);
		  if($rows > 0) {
			  mysql_data_seek($AcademicYear, 0);
			  $row_AcademicYear = mysql_fetch_assoc($AcademicYear);
		  }
		?>
				  </select></td>
				</tr>
				<tr>
				  <td nowrap><div align="right"> Course Code:</div></td>
				  <td ><select name="Hall" id="select">
				  <option value="0">Select Course Code</option>
					<?php
		do {  
		?>
					<option value="<?php echo $row_Hostel['CourseCode']?>"><?php echo $row_Hostel['CourseCode']?></option>
					  <?php
		} while ($row_Hostel = mysql_fetch_assoc($Hostel));
		  $rows = mysql_num_rows($Hostel);
		  if($rows > 0) {
			  mysql_data_seek($Hostel, 0);
			  $row_Hostel = mysql_fetch_assoc($Hostel);
		  }
		?>
				  </select></td>
				</tr>
        <tr>
          <td nowrap><div align="right"></div></td>
          <td nowrap><input type="submit" name="print"  id="print" value="PreView"></td>
          <td nowrap><input type="submit" name="PDF2"  id="PDF2" value="Print PDF"></td>
        </tr>
			  </table>
			  </fieldset>
</form>
		<?php
		}
}else{

?>

       <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" name="courseresult" id="housingvacantRoom">
           
				<legend>Search Course Results</legend>
			<table width="255" border="0" >
        <tr>
          <td width="113" nowrap><div align="right"></div></td>
          <td width="132" colspan="2" bordercolor="#ECE9D8" ><span class="style67">
          </span></td>
        </tr>
        <tr>
          <td nowrap><div align="right">Academic Year: </div></td>
          <td colspan="2" ><select name="ayear" id="select2">
		  <option value="0">SelectAcademicYear</option>
            <?php
do {  
?>
            <option value="<?php echo $row_AcademicYear['AYear']?>"><?php echo $row_AcademicYear['AYear']?></option>
            <?php
} while ($row_AcademicYear = mysql_fetch_assoc($AcademicYear));
  $rows = mysql_num_rows($AcademicYear);
  if($rows > 0) {
      mysql_data_seek($AcademicYear, 0);
	  $row_AcademicYear = mysql_fetch_assoc($AcademicYear);
  }
?>
          </select></td>
        </tr>
        <tr>
          <td nowrap><div align="right"> Course Code:</div></td>
          <td colspan="2"><select name="Hall" id="select">
		  <option value="0">Select Course Code</option>
            <?php
do {  
?>
            <option value="<?php echo $row_Hostel['CourseCode']?>"><?php echo $row_Hostel['CourseCode']?></option>
              <?php
} while ($row_Hostel = mysql_fetch_assoc($Hostel));
  $rows = mysql_num_rows($Hostel);
  if($rows > 0) {
      mysql_data_seek($Hostel, 0);
	  $row_Hostel = mysql_fetch_assoc($Hostel);
  }
?>
          </select></td>
        </tr>
        <tr>
         
          <td nowrap><input type="submit" name="print"  id="print" value="PreView"></td>
          <td nowrap><input type="submit" name="PDF"  id="PDF" value="Print PDF"></td>
        </tr>
      </table>
	  </fieldset>
                    <input type="hidden" name="MM_search" value="room">
</form>
<?php
}
include('../footer/footer.php');
?>
