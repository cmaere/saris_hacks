<?php 
#start pdf
if (isset($_POST['PrintPDF']) && ($_POST['PrintPDF'] == "Print PDF")) {
	#get post variables
	$rawkey = addslashes(trim($_POST['key']));
	$inst= addslashes(trim($_POST['cmbInst']));
	$cat= addslashes(trim($_POST['cat']));
	$award= addslashes(trim($_POST['award']));
	$key = ereg_replace("[[:space:]]+", " ",$rawkey);
	#get content table raw height
	$rh= addslashes(trim($_POST['sex']));

	#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
	require_once('../Connections/zalongwa.php');
	
	#process report title
	if($cat=='1'){
	$rtitle='STATEMENT OF EXAMINATIONS RESULTS';
	$xtitle = 180;
	}elseif($cat=='2'){
	$rtitle='PROGRESS REPORT ON STUDENT ACADEMIC PERFORMANCE';
	$xtitle = 130;
	}
	$titlelenth = strlen($inst);
	if ($titlelenth<30){
	$xinst = 190;
	}else{
	$xinst = 161;
	}
	
	#check if u belongs to this faculty
	$qFacultyID = "SELECT FacultyID from faculty WHERE FacultyName = '$inst'";
	$dbFacultyID = mysql_query($qFacultyID);
	$rowFacultyID = mysql_fetch_array($dbFacultyID);
	$studentFacultyID = $rowFacultyID['FacultyID'];
	
	#Get Organisation Name
	$qorg = "SELECT * FROM organisation";
	$dborg = mysql_query($qorg);
	$row_org = mysql_fetch_assoc($dborg);
	$pname = $row_org['ParentName'];
	$org = $row_org['Name'];
	$post = $row_org['Address'];
	$phone = $row_org['tel'];
	$fax = $row_org['fax'];
	$email = $row_org['email'];
	$website = $row_org['website'];
	$city = $row_org['city'];

	include('includes/PDF.php');

	$i=0;
	$pg=1;
	$tpg =$pg;
	//academic year
$sql = "SELECT DISTINCT AYear FROM examresult WHERE RegNo = '$key' and checked=1 ORDER BY AYear DESC LIMIT 0,1";
$result_AYear=mysql_query($sql);
        while ($line = mysql_fetch_array($result_AYear, MYSQL_ASSOC)) 
                    {
						$ayear = $line["AYear"];
						//$ayear = 2011;
					}



	$qstudent = "SELECT * from student 
inner join examresult on examresult.RegNo = student.RegNo
WHERE student.regno = '$key' and examresult.AYear = '$ayear'
LIMIT 0,1";

//die($qstudent );
	$dbstudent = mysql_query($qstudent); 
	$row_result = mysql_fetch_array($dbstudent);
		$sname = $row_result['Name'];
		$regno = $row_result['RegNo'];
		$coursecodea = $row_result['CourseCode'];
		//$degree = $row_result['ProgrammeofStudy'];
		$sex = $row_result['Sex'];
		$dbirth = $row_result['DBirth'];
		$entry = $row_result['EntryYear'];
		$faculty = $row_result['Faculty'];
		$citizen = $row_result['Nationality'];
		$address = $row_result['Address'];
		$gradyear = $row_result['GradYear'];
		$admincriteria = $row_result['MannerofEntry'];
		$campus = $row_result['Campus'];
		$faculty = $row_result['Faculty'];
		$subjectid = $row_result['Subject'];
		$photo = $row_result['Photo'];
		$checkit = strlen($photo);
		
		
		$sqldeg ="SELECT examregister.`CourseCode`, course.Programme, course.prefix from examregister  inner join course on course.CourseCode = examregister.CourseCode WHERE examregister.RegNo = '$key' and examregister.AYear = '$ayear' and examregister.CourseCode <> 'SOC 601' ORDER BY examregister.CourseCode DESC LIMIT 0,1";
		$dbstudent2 = mysql_query($sqldeg); 
	$row_result2 = mysql_fetch_array($dbstudent2);
	$degree = $row_result2['Programme'];
	$prefix = $row_result2['prefix'];
	//$tr = trim($prefix,'COM');
	
		//die($prefix.' here');
		
		if ($checkit > 8){
		
		$imgfile = '../admission/'.$photo;
		#resize photo
			$full_url = $photo;
			$imageInfo = @getimagesize($imgfile);
			$src_width = $imageInfo[0];
			$src_height = $imageInfo[1];
			
			$dest_width = 80;//$src_width / $divide;
			$dest_height = 80;//$src_height / $divide;
			
			$src_img = @imagecreatefromjpeg($imgfile);
			$dst_img = imagecreatetruecolor($dest_width,$dest_height);
			@imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height);
			@imagejpeg($dst_img,$full_url);
			@imagedestroy($src_img);
		#new resized image file
		$imgfile = $full_url;
		#NB: ili hii ifanye kazi lazima images folder kwenye academic liwe writable!!!
		
		}else{
		$nophoto = 1;
		}
		
		#get degree name
		$qdegree = "Select Title FROM programme WHERE ProgrammeCode = '$degree'";
		$dbdegree = mysql_query($qdegree);
		$row_degree = mysql_fetch_array($dbdegree);
		$programme = $row_degree['Title'];
		
		#get subject combination
		$qsubjectcomb = "SELECT SubjectName FROM subjectcombination WHERE SubjectID='$subjectid'";
		$dbsubjectcom = mysql_query($qsubjectcomb);
		$row_subjectcom = mysql_fetch_assoc($dbsubjectcom);
		$counter = mysql_num_rows($dbsubjectcom );
		if ($counter>0){
		$subject = $row_subjectcom['SubjectName'];
		}

	//require 'PDF.php';                    // Require the lib. 
	$pdf = &PDF::factory('p', 'a4');      // Set up the pdf object. 
	$pdf->open();                         // Start the document. 
	$pdf->setCompression(true);           // Activate compression. 
	$pdf->addPage();  
	
	#print header
	include 'includes/transtemplate.php';
	
	$ytitle = $yadd+52;
	$pdf->setFillColor('rgb', 1, 0, 0);   
	$pdf->setFont('Arial', '', 13);     
	//$pdf->text(150, $ytitle, $rtitle); 
	$pdf->setFillColor('rgb', 0, 0, 0);    

	#title line
	$pdf->line(50, $ytitle+3, 570, $ytitle+3);

	$pdf->setFont('Arial', 'B', 10.3);     
	#set page header content fonts
	#line1
	/*
	$pdf->line(50, $ytitle+3, 50, $ytitle+15);       
	$pdf->line(383, $ytitle+3, 383, $ytitle+15);       
	$pdf->line(432, $ytitle+3, 432, $ytitle+15);
	$pdf->line(570, $ytitle+3, 570, $ytitle+15);       
	$pdf->line(50, $ytitle+15, 570, $ytitle+15); */
	#format name
	$candname = explode(",",$sname);
	$surname = $candname[0];
	$othername = $candname[1];
	
	$sql = "SELECT date FROM senate_date WHERE programme_code = '$degree'";
		 $result_AYear=mysql_query($sql);
        while ($line = mysql_fetch_array($result_AYear, MYSQL_ASSOC)) 
                    {
						$senate_date = $line["date"];
					}
$sqlb = "SELECT Recomm FROM Recommendation WHERE RegNo = '$key' and AYear = $ayear";
//die($sqlb);
		 $result_AYear=mysql_query($sqlb);
        while ($line = mysql_fetch_array($result_AYear, MYSQL_ASSOC)) 
                    {
						$recomm = $line["Recomm"];
					}
	
					// verifying pass or fail
	
					$sqlmin2 = "select COUNT(ExamScore) as rep from examresult 
			where AYear = $ayear and RegNo = '$key'  and ExamCategory = 5  and ExamScore < 50 ";	
				
			        $resultmin2 = mysql_query($sqlmin2);
            
			            while($rowmin2 = mysql_fetch_array($resultmin2, MYSQL_ASSOC))
			            {
			                 $countref = $rowmin2['rep'];
                 
			            }    
						
						if($countref == 0 )
						{
							
	
	//$senate_date = "27th September 2012";
	
	if($degree == 10014)
	{
		$pdf->setFont('Arial', 'I', 10.3);$pdf->text(50, $ytitle+18, 'Dear '.$othername.' '.$surname.' ('.$regno.')'); 
 	 //$pdf->Line(7);
	 $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+38, 'I am pleased to inform you that at its meeting held on '. $senate_date.' Senate approved');
	  $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+48, 'a recommendation from the College Assessment Committee that you should pass and be awarded the '); 
	   $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+60, 'Degree of Bachelor of Science in Nursing and Midwifery with '. $recomm.'.'); 
	   
	   $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+80, 'Your overall performance in the examinations was as follows:-'); 

		
	}
	else if($degree == 1003)
	{
		$pdf->setFont('Arial', 'I', 10.3);$pdf->text(50, $ytitle+18, 'Dear '.$othername.' '.$surname.' ('.$regno.')'); 
 	 //$pdf->Line(7);
	 $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+38, 'I am pleased to inform you that at its meeting held on '. $senate_date.' Senate approved');
	  $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+48, 'a recommendation from the College Assessment Committee that you should pass and be awarded the '); 
	   $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+60, 'University Certificate in Midwifery with '. $recomm.'.'); 
	   
	   $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+80, 'Your overall performance in the examinations was as follows:-'); 

		
	}
	else if($degree == 10052 && $prefix == 'COM')
	{
		$pdf->setFont('Arial', 'I', 10.3);$pdf->text(50, $ytitle+18, 'Dear '.$othername.' '.$surname.' ('.$regno.')'); 
 	 //$pdf->Line(7);
	 $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+38, 'I am pleased to inform you that at its meeting held on '. $senate_date.' Senate approved');
	  $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+48, 'a recommendation from the College Assessment Committee that you should pass and be awarded the '); 
	   $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+60, 'Degree of Bachelor of Science in Community Health Nursing  with '. $recomm.'.'); 
	   
	   $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+80, 'Your overall performance in the examinations was as follows:-'); 
		
	}
	else if($degree == 10052 && $prefix == 'MGT')
	{
		$pdf->setFont('Arial', 'I', 10.3);$pdf->text(50, $ytitle+18, 'Dear '.$othername.' '.$surname.' ('.$regno.')'); 
 	 //$pdf->Line(7);
	 $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+38, 'I am pleased to inform you that at its meeting held on '. $senate_date.' Senate approved');
	  $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+48, 'a recommendation from the College Assessment Committee that you should pass and be awarded the '); 
	   $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+60, 'Degree of Bachelor of Science in Health Services Management  with '. $recomm.'.'); 
	   
	   $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+80, 'Your overall performance in the examinations was as follows:-'); 
		
	}
	else if($degree == 10052 && $prefix == 'ED')
	{
		$pdf->setFont('Arial', 'I', 10.3);$pdf->text(50, $ytitle+18, 'Dear '.$othername.' '.$surname.' ('.$regno.')'); 
 	 //$pdf->Line(7);
	 $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+38, 'I am pleased to inform you that at its meeting held on '. $senate_date.' Senate approved');
	  $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+48, 'a recommendation from the College Assessment Committee that you should pass and be awarded the '); 
	   $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+60, 'Degree of Bachelor of Science in Nursing Education  with '. $recomm.'.'); 
	   
	   $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+80, 'Your overall performance in the examinations was as follows:-'); 
		
	}
	else
	{
	 $pdf->setFont('Arial', 'I', 10.3);$pdf->text(50, $ytitle+18, 'Dear '.$othername.' '.$surname.' ('.$regno.')'); 
 	 //$pdf->Line(7);
	 $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+38, 'I am pleased to inform you that at its meeting held on '. $senate_date.' Senate approved');
	  $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+48, 'a recommendation from the College Assessment Committee that you should pass and proceed to'); 
	   $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+58, 'the next year of studies.'); 
	   
	   $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+78, 'Your overall performance in the examinations was as follows:-'); 

	}
	
	
}
else if ($countref > 0 && $countref < 3)
{
	
 $pdf->setFont('Arial', 'I', 10.3);$pdf->text(50, $ytitle+18, 'Dear '.$othername.' '.$surname.' ('.$regno.')'); 
	 //$pdf->Line(7);
 $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+38, 'I write to inform you that at its meeting held on '. $senate_date.' Senate approved');
  $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+48, 'a recommendation from the College Assessment Committee that you should be referred to'); 
   $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+58, 'the Failed Modules.'); 
   
   $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+78, 'Your overall performance in the examinations was as follows:-'); 
	
	
	
	
}
else if ($countref > 2)
{
	
 $pdf->setFont('Arial', 'I', 10.3);$pdf->text(50, $ytitle+18, 'Dear '.$othername.' '.$surname.' ('.$regno.')'); 
	 //$pdf->Line(7);
 $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+38, 'I write to inform you that at its meeting held on '. $senate_date.' Senate approved');
  $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+48, 'a recommendation from the College Assessment Committee that you should repeat to'); 
   $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+58, 'the Failed Modules.'); 
   
   $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $ytitle+78, 'Your overall performance in the examinations was as follows:-'); 
	
	
	
	
}
	
	#Format grad year
	$graddate = explode("-",$gradyear);
	$gradday = $graddate[2];
	$gradmon = $graddate[1];
	$grady = $graddate[0];

	
	

	$sub =$subjectid;
	if($sub<>0){
		#line7
		$pdf->line(50, $ytitle+75, 50, $ytitle+87);       
		$pdf->line(570, $ytitle+75, 570, $ytitle+87);       
		$pdf->line(50, $ytitle+87, 570, $ytitle+87); 
		$pdf->setFont('Arial', 'B', 10.3);  $pdf->text(50, $ytitle+85, 'MAJOR STUDY AREA:'); $pdf->text(175, $ytitle+85,$subject); 
	}
	#initialize x and y
	$x=50;
	$y=$ytitle+98;
	#initialise total units and total points
	$annualUnits=0;
	$annualPoints=0;
	$gtotalcourse=0;
	$gtmarks=0;
	
	$yval=$y+33;
	$y=$y+13;

	#set page body content fonts
	$pdf->setFont('Arial', '', 9);     

	//query academeic year
	$qayear = "SELECT DISTINCT AYear FROM examresult WHERE RegNo = '$regno' and checked=1 ORDER BY AYear DESC LIMIT 0,1";
	$dbayear = mysql_query($qayear);
	
	#initialise ayear
	$acyear = 0;
	//query exam results sorted per years
	while($rowayear = mysql_fetch_object($dbayear)){
		$acyear = $acyear +1;
		$currentyear = $rowayear->AYear;
		if ($temp ==2)
		{
			#use muchs sorting order by semester
			$query_examresult = "
								  SELECT DISTINCT course.CourseName, 
												  course.Units, 
												  course.StudyLevel, 
												  course.Department, 
												  examresult.CourseCode, 
												  examresult.Status 
								  FROM 
										course INNER JOIN examresult ON (course.CourseCode = examresult.CourseCode)
								  WHERE (examresult.RegNo='$regno') AND 
										(examresult.AYear = '$currentyear') AND 
										(examresult.Checked='1')  AND
										course.Programme = $degree
							      ORDER BY examresult.AYear, examresult.coursecode ASC";	
		}else
		{
			$query_examresult = "
								  SELECT DISTINCT course.CourseName, 
												  course.Units, 
												  course.StudyLevel, 
												  course.Department, 
												  examresult.CourseCode, 
												  examresult.Status 
								  FROM 
										course INNER JOIN examresult ON (course.CourseCode = examresult.CourseCode)
								  WHERE (examresult.RegNo='$regno') AND 
										(examresult.AYear = '$currentyear') AND 
										(examresult.Checked='1') AND
										course.Programme = $degree
								  ORDER BY examresult.AYear, examresult.coursecode ASC";	
		}
		
		//die($query_examresult);
		$result = mysql_query($query_examresult); 
		$query = @mysql_query($query_examresult);
		$dbcourseUnit = mysql_query($query_examresult);
		
		if (mysql_num_rows($query) > 0){
				
						$totalunit=0;
						$unittaken=0;
						$sgp=0;
						$totalsgp=0;
						$gpa=0;
						$totalcourse=0;
						$jtmarks=0;
				#check if u need to sart a new page
				$blank=$y-12;
				$space = 820.89 - $blank;
				if ($space<150){
				#start new page
				$pdf->addPage();  
				
					$x=50;
					$yadd=50;
	
					$y=80;
					$pg=$pg+1;
					$tpg =$pg;
					#insert transcript footer
					include 'includes/transcriptfooter.php';
				}
				#create table header
				if($degree==1001){
					if($temp==2){
					$pdf->text($x, $y-$rh, 'FIRST YEAR EXAMINATIONS RESULTS: '.$rowayear->AYear); 
					}else{
					$pdf->text($x, $y-$rh, 'FIRST YEAR EXAMINATIONS RESULTS: '.$rowayear->AYear); 
					}
				}elseif($degree==10012){
					if($temp==2){
					$pdf->text($x, $y-$rh, 'SECOND YEAR EXAMINATIONS RESULTS: '.$rowayear->AYear); 
					}else{
					$pdf->text($x, $y-$rh, 'SECOND YEAR EXAMINATIONS RESULTS: '.$rowayear->AYear); 
					}
				}elseif($degree==10013){
					$pdf->text($x, $y-$rh, 'THIRD YEAR EXAMINATIONS RESULTS: '.$rowayear->AYear); 
				}elseif($degree==10014){
					$pdf->text($x, $y-$rh, 'FOURTH YEAR EXAMINATIONS RESULTS: '.$rowayear->AYear); 
				}elseif($acyear==5){
					$pdf->text($x, $y-$rh, 'FIFTH YEAR EXAMINATIONS RESULTS: '.$rowayear->AYear); 
				}elseif($acyear==6){
					$pdf->text($x, $y-$rh, 'SIXTH YEAR EXAMINATIONS RESULTS: '.$rowayear->AYear); 
				}elseif($acyear==7){
					$pdf->text($x, $y-$rh, 'SEVENTH YEAR EXAMINATIONS RESULTS: '.$rowayear->AYear); 
				}
				#check result tables to use
					$pdf->text($x+10, $y, 'Code'); 
					$pdf->text($x+70, $y, 'Course Title'); 
					$pdf->text($x+471, $y, 'GRADE'); 
					
					#calculate results
					$i=1;
					while($row_course = mysql_fetch_array($dbcourseUnit)){
						$course= $row_course['CourseCode'];
						$unit = $row_course['Units'];
						$cname = $row_course['CourseName'];
						$coursefaculty = $row_course['Department'];
						$sn=$sn+1;
						$remarks = 'remarks';
						$grade='';
						# grade marks
						$RegNo = $regno;
						include'includes/choose_studylevel.php';
							
							$totalcourse = $totalcourse + 1;
							$gtotalcourse = $gtotalcourse + 1;	
							$gtmarks = $gtmarks + $marks;
							$jtmarks = $jtmarks + $marks;
							$coursecode = $course;
							
							#print results
							$pdf->text($x+3, $y+$rh, substr($coursecode,0,15)); 
							$pdf->text($x+70, $y+$rh, substr($cname,0,73)); 
							$pdf->text($x+477, $y+$rh, round($marks,1)); 
							#check if the page is full
							$x=$x;
							#draw a line
							$pdf->line($x, $y-$rh+2, 570.28, $y-$rh+2);        
							$pdf->line($x, $y-$rh+2, $x, $y);       
							$pdf->line(570.28, $y-$rh+2, 570.28, $y);      
							$pdf->line($x, $y-$rh+2, $x, $y+$rh+4);              
							$pdf->line(570.28, $y-$rh+2, 570.28, $y+$rh+4);      
							$pdf->line($x+468, $y-$rh+2, $x+468, $y+$rh+4);     
							$pdf->line($x+43+20, $y-$rh+2, $x+43+20, $y+$rh+2); 
							#get space for next year
							$y=$y+$rh;
	
							if ($y>800){
								#put page header
								$pdf->addPage();  
	
								$x=50;
								$y=100;
								$pg=$pg+1;
								$tpg =$pg;
							#insert transcript footer
							include 'includes/transcriptfooter.php';
							}
							#draw a line
							$pdf->line($x, $y+$rh+2, 570.28, $y+$rh+2);       
							$pdf->line($x, $y-$rh+2, $x, $y+$rh+2); 
							$pdf->line(570.28, $y-$rh+2, 570.28, $y+$rh+2);      
							$pdf->line($x+468, $y-$rh+2, $x+468, $y+$rh+2);       
							$pdf->line($x+43+20, $y-$rh+2, $x+43+20, $y+$rh+2);      
					  }//ends while loop
					  #check degree
							$pdf->setFont('Arial', 'BI', 9.5);     
							$pdf->text($x+70, $y+$rh+1, 'Average');
							$pdf->text($x+477, $y+$rh+1, round(number_format($jtmarks/$totalcourse,1))); 
							$pdf->setFont('Arial', '', 9.5); 
						#check x,y values
						$y=$y+3.5*$rh;
						if ($y==800){
							$pdf->addPage();  

							#put page header
							$x=50;
							$y=80;
							$pg=$pg+1;
							$tpg =$pg;
							#insert transcript content header
							include 'includes/transciptheader';						}
						
	 }
						#get annual units and Points
						$annualUnits = $annualUnits+$unittaken;
						$annualPoints = $annualPoints+$totalsgp;

  }
	$avgGPA=@substr($annualPoints/$annualUnits, 0,3);
	#specify degree classification
	if ($award==1){
		if($avgGPA>=4.4){
				$degreeclass = 'First Class (Honours)';
			}elseif($avgGPA>=3.5){
				$degreeclass = 'Uppersecond Class (Honours)';
			}elseif($avgGPA>=2.7){
				$degreeclass = 'Lowersecond Class (Honours)';
			}elseif($avgGPA>=2.0){
				$degreeclass = 'Pass';
			}else{
				$degreeclass = 'FAIL';
			}
	}elseif($award==2){
		if($avgGPA>=4.0){
				$degreeclass = 'Distinction';
			}elseif($avgGPA>=3.0){
				$degreeclass = 'Credit';
			}elseif($avgGPA>=2.0){
				$degreeclass = 'Pass';
			}else{
				$degreeclass = 'FAIL';
			}
	}

	$sblank=$y-20;
	$sspace = 820.89 - $sblank;
	if ($sspace<80){
			#start new page
			#put page header
			$pdf->addPage();  

			$x=50;
			$y=80;
			$pg=$pg+1;
			$tpg =$pg;
			#insert transcript footer
			include 'includes/transcriptfooter.php';
	}
	$sub =$subject;
	if($thesisresult>0){
		#print final year project title
		$pdf->line($x, $y-20, 570, $y-20); 
		$pdf->line($x, $y-20, $x, $y-8);       
		$pdf->line(570, $y-20, 570, $y-8);       
		$pdf->line($x, $y-8, 570, $y-8); 
		$pdf->setFont('Arial', 'B', 10.3);  $pdf->text($x+70, $y-10, 'Title of the Final Year Project/Independent Study/Thesis of '.$thesisyear);  
		
		$pdf->line($x, $y-8, $x, $y+4);       
		$pdf->line(570, $y-8, 570, $y+4);       
		$pdf->line($x, $y+4, 570, $y+4); 
		$pdf->setFont('Arial', 'I', 10.3); $pdf->text($x, $y+2, substr($thesis,0,107)); 
	}
	$b=$y+27;
	if ($b<820.89){
		

	// below words
	if($degree == 10014)
	{
	 $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $y+57, 'You will be awarded a Bachelor of Science Degree in Nursing and Midwifery at a University Congregation ');
	  $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $y+72, 'to be held on a date to be communicated in due course.'); 
	}
	else if($degree == 1003)
	{
	 $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $y+57, 'You will be awarded a University Certificate in Nursing and Midwifery at a University Congregation ');
	  $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $y+72, 'to be held on a date to be communicated in due course.'); 
	}
	else if($degree == 10052 && $prefix == 'MGT')
	{
	 $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $y+57, 'You will be awarded a Bachelor of Science Degree in Health Services Management at a University Congregation ');
	  $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $y+72, 'to be held on a date to be communicated in due course.'); 
	}
	else if($degree == 10052 && $prefix == 'COM')
	{
	 $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $y+57, 'You will be awarded a Bachelor of Science Degree in Community Health Nursing at a University Congregation ');
	  $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $y+72, 'to be held on a date to be communicated in due course.'); 
	}
	else if($degree == 10052 && $prefix == 'ED')
	{
	 $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $y+57, 'You will be awarded a Bachelor of Science Degree in Nursing Education at a University Congregation ');
	  $pdf->setFont('Arial', '', 10.3);$pdf->text(50, $y+72, 'to be held on a date to be communicated in due course.'); 
	}
	else if($countref > 0 && $countref < 3)
	{
		$pdf->setFont('Arial', '', 10.3);$pdf->text(50, $y+57, 'Please begin preparing for supplementary examination which will be administered on the date to be announced. ');	
		
	}
	else if($countref > 2 )
	{
		$pdf->setFont('Arial', '', 10.3);$pdf->text(50, $y+57, 'Please report to the College at the beginning of the semester which will be offering these modules.  ');	
		
	}
	else
	{
		$pdf->setFont('Arial', '', 10.3);$pdf->text(50, $y+57, 'Please accept my congratulations on your well-deserved success in the examinations. ');	
		
	}
			
	#print signature lines
	$pdf->setFont('Arial', '', 10.3);$pdf->text(50, $y+107, 'Yours sincerely'); 
	$pdf->text(59.28, $y+137, '                                 .........................................                             ................................'); 
	$pdf->text(60.28, $y+150, $signatory2); 
	$pdf->setFont('Arial', 'B', 10.3);    	   						
	$pdf->text(60.28, $y+165, $signatory);    	
	}					
	#print the key index
	$pdf->setFont('Arial', 'I', 8); 
	$yind = $y+87;
	
	#check if there is enough printing area
	$indarea = 820.89-$yind;
	if ($indarea< 100){
			$pdf->addPage();  

			$x=50;
			$y=80;
			$pg=$pg+1;
			$tpg =$pg;
			$pdf->setFont('Arial', 'I', 8);     
			$pdf->text(530.28, 820.89, 'Page '.$pg);  
			$pdf->text(300, 820.89, $copycount);    
			$pdf->text(50, 820.89, 'Lilongwe, '.$today = date("d-m-Y H:i:s")); 
			$yind = $y; 
    }
	
	//include 'includes/transcriptkeys.php';
	#delete imgfile
	@unlink($imgfile); 
	#print the file
	$pdf->output($key.'.pdf');              // Output the 
}/*ends is isset*/
#ends pdf
#get connected to the database and verfy current session
require_once('../Connections/sessioncontrol.php');
require_once('../Connections/zalongwa.php');
# initialise globals
include('lecturerMenu.php');

# include the header
global $szSection, $szSubSection;
$szSection = 'Examination';
$szSubSection = 'Cand. Statement';
$szTitle = 'Student\'s Statement of Examination Results';
include('lecturerheader.php');

mysql_select_db($database_zalongwa, $zalongwa);
$query_campus = "SELECT FacultyName FROM faculty WHERE FacultyID='$userFaculty' ORDER BY FacultyName ASC";
$campus = mysql_query($query_campus, $zalongwa) or die(mysql_error());
$row_campus = mysql_fetch_assoc($campus);
$totalRows_campus = mysql_num_rows($campus);


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
if (isset($_POST['search']) && ($_POST['search'] == "PreView")) {
#get post variables
$rawkey = $_POST['key'];
$key = ereg_replace("[[:space:]]+", " ",$rawkey);


}else{

?>

<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" name="studentRoomApplication" id="studentRoomApplication">
            <table width="284" border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
        <tr>
          <td colspan="9" nowrap><div align="center"></div>          </td>
        </tr>
        <tr>
          <td width="110"><div align="right"><strong></strong></div></td>
          <td colspan="8" bordercolor="#ECE9D8" bgcolor="#CCCCCC"><span class="style67">
            <input name="cmbInst" type="hidden" id="cmbInst" value="<?php echo $row_campus['FacultyName']?>">
          </span></td>
        </tr>
        <tr>
          <td nowrap><div align="right"><strong>Programme:
            </strong></div>            <div align="center"></div></td>
          <td colspan="2" nowrap><div align="right">Degree</div></td>
          <td width="35" nowrap><input type="radio" value="1" id="award1" name="award" checked></td>
          <td colspan="2" nowrap><div align="right">Diploma</div></td>
          <td width="35" nowrap><input type="radio" value="2" id="award2" name="award" ></td>
          <td width="89" nowrap><div align="right">Certificate</div></td>
		  <td width="30" nowrap><input type="radio" value="3" id="award2" name="award" ></td>
        </tr>
		<tr> 
		<td align="right"><strong>Category:</strong></td> 
		<td colspan="4">Finalist:
		  <input type="radio" value="1" id="cat" name="cat" checked>  </td> 
		<td colspan="4">Continuing:
		  <input type="radio" value="2" id="cat" name="cat">		</td>
		</tr> 
		<tr>
          <td><div align="right"><strong><span class="style67">RegNo:</span></strong></div></td>
          <td colspan="8" bordercolor="#ECE9D8" bgcolor="#CCCCCC"><span class="style67">
          <input name="key" type="text" id="key" size="40" maxlength="40">
          </span></td>
        </tr>
		<tr> 
			<td align="right" nowrap><strong>Table:</strong></td> 
			<td width="35"><div align="center">11<input type="radio" value="11" id="sex" name="sex"></div></td> 
			<td width="35"><div align="center">12<input type="radio" value="12" id="sex" name="sex" checked></div></td> 
			<td width="35"><div align="center">13<input type="radio" value="13" id="sex" name="sex" ></div></td> 
			<td width="35"><div align="center">14<input type="radio" value="14" id="sex" name="sex" ></div></td> 
			<td width="35"><div align="center">15<input type="radio" value="15" id="sex" name="sex" ></div></td> 
			<td width="35"><div align="center">16<input type="radio" value="16" id="sex" name="sex" ></div></td> 
			<td width="89"> <div align="center">-</div></td> 
			<td><div align="left">17
		      <input type="radio" value="17" id="sex" name="sex" >
			</div></td>
		</tr>
        <tr>
          <td nowrap><div align="right"><strong> </strong></div></td>
          <td bgcolor="#CCCCCC" colspan="8">
            
            <div align="center">
              <input name="PrintPDF" type="submit" id="PrintPDF" value="Print PDF">
            </div>
            </div></td>
          </tr>
  </table>
</form>
<?php
}
include('../footer/footer.php');
?>