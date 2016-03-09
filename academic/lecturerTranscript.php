<?php 
#start pdf
if (isset($_POST['PrintPDF']) && ($_POST['PrintPDF'] == "Print PDF")) {
	#get post variables
	$rawkey = addslashes(trim($_POST['key']));
	$key = ereg_replace("[[:space:]]+", " ",$rawkey);
	#get content table raw height
	$rh= addslashes(trim($_POST['sex']));
	$temp= addslashes(trim($_POST['temp']));
	$award= addslashes(trim($_POST['award']));
	$realcopy= addslashes(trim($_POST['real']));
	
	#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
	require_once('../Connections/zalongwa.php');
	# check if is a trial print
	if($realcopy==1){
		$copycount = 'TRIAL COPY';
	}
	#check if is a reprint
	$qtranscounter = "SELECT RegNo, received FROM transcriptcount where RegNo='$key'";
	$dbtranscounter = mysql_query($qtranscounter);
	@$transcounter = mysql_num_rows($dbtranscounter);
	
	if ($transcounter>0){
		$row_transcounter = mysql_fetch_array($dbtranscounter);
		$lastprinted = $row_result['received'];
	}
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

	$qstudent = "SELECT * from student WHERE RegNo = '$key' or Name='$key'";
	$dbstudent = mysql_query($qstudent); 
	$row_result = mysql_fetch_array($dbstudent);
		$sname = $row_result['Name'];
		$regno = $row_result['RegNo'];
		$degree = $row_result['ProgrammeofStudy'];
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
		$qdegree = "Select ProgrammeName FROM program_year WHERE ProgrammeCode = '$degree'";
		$dbdegree = mysql_query($qdegree);
		$row_degree = mysql_fetch_array($dbdegree);
		$programme = $row_degree['ProgrammeName'];
		
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
	if($temp ==1){
	#include transcript address
	include 'includes/transtemplate.php';
	}
	
	$ytitle = $yadd+52;
	$pdf->setFillColor('rgb', 1, 0, 0);   
	$pdf->setFont('Arial', '', 13);     
	$pdf->text(150, $ytitle+6, 'TRANSCRIPT OF EXAMINATIONS RESULTS'); 
	$pdf->setFillColor('rgb', 0, 0, 0);    
	
	$ytitle=$ytitle+11;
	#title line
	$pdf->line(50, $ytitle, 570, $ytitle);

	$pdf->setFont('Arial', 'B', 10.3);     
	#set page header content fonts
	#line1
	$pdf->line(50, $ytitle, 50, $ytitle+15);       
	$pdf->line(353, $ytitle, 353, $ytitle+15);       
	$pdf->line(402, $ytitle, 402, $ytitle+15);
	$pdf->line(570, $ytitle, 570, $ytitle+15);       
	$pdf->line(50, $ytitle+15, 570, $ytitle+15); 
	#format name
	$candname = explode(",",$sname);
	$surname = $candname[0];
	$othername = $candname[1];

	$pdf->setFont('Arial', 'B', 10.3);  $pdf->text(50, $ytitle+13, 'NAME:'); $pdf->setFont('Arial', 'I', 10.3); $pdf->text(90, $ytitle+13, strtoupper($surname).', '.ucwords(strtolower($othername))); 
	$pdf->setFont('Arial', 'B', 10.3); 	$pdf->text(355, $ytitle+13, 'SEX:'); $pdf->setFont('Arial', 'I', 10.3); $pdf->text(385, $ytitle+13, $sex); 
	$pdf->setFont('Arial', 'B', 10.3);  $pdf->text(405, $ytitle+13, 'REGNO.:'); $pdf->setFont('Arial', 'I', 10.3); $pdf->text(450, $ytitle+13, $regno); 
	
	#line2
	$pdf->line(50, $ytitle+15, 50, $ytitle+27);       
	$pdf->line(188, $ytitle+15, 188, $ytitle+27);       
	$pdf->line(570, $ytitle+15, 570, $ytitle+27);       
	$pdf->line(50, $ytitle+27, 570, $ytitle+27); 
	
	$pdf->setFont('Arial', 'B', 10.3);  $pdf->text(50, $ytitle+25, 'CITIZENSHIP:'); $pdf->setFont('Arial', 'I', 10.3); $pdf->text(118, $ytitle+25, $citizen); 
	$pdf->setFont('Arial', 'B', 10.3); 	$pdf->text(190, $ytitle+25, 'ADDRESS:'); $pdf->setFont('Arial', 'I', 10.3); $pdf->text(250, $ytitle+25, $address); 
	#line3
	$pdf->line(50, $ytitle+27, 50, $ytitle+39);       
	$pdf->line(188, $ytitle+27, 188, $ytitle+39);       
	$pdf->line(383, $ytitle+27, 383, $ytitle+39);       
	$pdf->line(570, $ytitle+27, 570, $ytitle+39);       
	$pdf->line(50, $ytitle+39, 570, $ytitle+39); 
	
	#Format grad year
	$graddate = explode("-",$gradyear);
	$gradday = $graddate[2];
	$gradmon = $graddate[1];
	$grady = $graddate[0];

	$pdf->setFont('Arial', 'B', 10.3);  $pdf->text(50, $ytitle+37, 'BIRTH DATE:'); $pdf->setFont('Arial', 'I', 10.3); $pdf->text(120, $ytitle+37, $dbirth); 
	$pdf->setFont('Arial', 'B', 10.3); 	$pdf->text(190, $ytitle+37, 'ADMITTED:'); $pdf->setFont('Arial', 'I', 10.3); $pdf->text(250, $ytitle+37, $entry); 
	$pdf->setFont('Arial', 'B', 10.3);  $pdf->text(385, $ytitle+37, 'COMPLETED:'); $pdf->setFont('Arial', 'I', 10.3); $pdf->text(456, $ytitle+37, $gradday.' - '.$gradmon.' - '.$grady); 
	#line5
	$pdf->line(50, $ytitle+39, 50, $ytitle+51);       
	$pdf->line(238, $ytitle+39, 238, $ytitle+51);       
	$pdf->line(570, $ytitle+39, 570, $ytitle+51);       
	$pdf->line(50, $ytitle+51, 570, $ytitle+51); 

	$pdf->setFont('Arial', 'B', 10.3);  $pdf->text(50, $ytitle+49, 'CAMPUS:'); $pdf->setFont('Arial', 'I', 10.3); $pdf->text(100, $ytitle+49, $campus); 
	$pdf->setFont('Arial', 'B', 10.3); 	$pdf->text(240, $ytitle+49, 'FACULTY:'); $pdf->setFont('Arial', 'I', 10.3); $pdf->text(290, $ytitle+49, $faculty); 

	#line6
	$pdf->line(50, $ytitle+51, 50, $ytitle+63);       
	$pdf->line(570, $ytitle+51, 570, $ytitle+63);       
	$pdf->line(50, $ytitle+63, 570, $ytitle+63); 
	$pdf->setFont('Arial', 'B', 10.3);  $pdf->text(50, $ytitle+61, 'NAME OF PROGRAMME:'); $pdf->setFont('Arial', 'I', 10.3); $pdf->text(175, $ytitle+61, $programme); 

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
	$y=$ytitle+83;
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
	$qayear = "SELECT DISTINCT AYear FROM examresult WHERE RegNo = '$regno' and checked=1 ORDER BY AYear ASC";
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
										(examresult.Checked='1') 
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
										(examresult.Checked='1') 
								  ORDER BY examresult.AYear, examresult.coursecode ASC";	
		}
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
				if($acyear==1){
					if($temp==2){
					$pdf->text($x, $y-$rh, 'FIRST YEAR EXAMINATIONS RESULTS: '.$rowayear->AYear); 
					}else{
					$pdf->text($x, $y-$rh, 'FIRST YEAR EXAMINATIONS RESULTS: '.$rowayear->AYear); 
					}
				}elseif($acyear==2){
					if($temp==2){
					$pdf->text($x, $y-$rh, 'SECOND YEAR EXAMINATIONS RESULTS: '.$rowayear->AYear); 
					}else{
					$pdf->text($x, $y-$rh, 'SECOND YEAR EXAMINATIONS RESULTS: '.$rowayear->AYear); 
					}
				}elseif($acyear==3){
					$pdf->text($x, $y-$rh, 'THIRD YEAR EXAMINATIONS RESULTS: '.$rowayear->AYear); 
				}elseif($acyear==4){
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
					$pdf->text($x+75, $y, 'Course Title'); 
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
							$pdf->text($x+3, $y+$rh, substr($coursecode,0,13)); 
							$pdf->text($x+75, $y+$rh, substr($cname,0,73)); 
							$pdf->text($x+477, $y+$rh, @number_format($marks)); 
							#check if the page is full
							$x=$x;
							#draw a line
							$pdf->line($x, $y-$rh+2, 570.28, $y-$rh+2);        
							$pdf->line($x, $y-$rh+2, $x, $y);       
							$pdf->line(570.28, $y-$rh+2, 570.28, $y);      
							$pdf->line($x, $y-$rh+2, $x, $y+$rh+4);              
							$pdf->line(570.28, $y-$rh+2, 570.28, $y+$rh+4);      
							$pdf->line($x+468, $y-$rh+2, $x+468, $y+$rh+4);  
                            //Vertical line first colum                            
							$pdf->line($x+70, $y-$rh+2, $x+70, $y+$rh+2); 
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
							$pdf->line($x+70, $y-$rh+2, $x+70, $y+$rh+2);      
					  }//ends while loop
					  #check degree
							$pdf->setFont('Arial', 'BI', 9.5);     
							$pdf->text($x+75, $y+$rh+1, 'Average');
							$pdf->text($x+477, $y+$rh+1, @number_format($jtmarks/$totalcourse,0)); 
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
  }
	$avgGPA=@number_format($gtmarks/$gtotalcourse,1); 
	#specify degree classification
		if($avgGPA>=75){
				$degreeclass = 'Distinction';
			}elseif($avgGPA>=65){
				$degreeclass = 'Credit';
			}elseif($avgGPA>=50){
				$degreeclass = 'Pass';
			}else{
				$degreeclass = 'FAIL';
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
	$pdf->setFont('Arial', 'B', 10.3);  $pdf->text($x, $y+24, 'OVERALL AVERAGE:'); $pdf->text($x+115, $y+24, @number_format($gtmarks/$gtotalcourse,0)); 
	$pdf->setFont('Arial', 'B', 10.3); 	$pdf->text($x+220, $y+24, 'CLASSIFICATION:'); $pdf->text($x+320, $y+24, $degreeclass);
	$pdf->line($x, $y+27, 570.28, $y+27); 
	$b=$y+27;
	if ($b<820.89){
	#print signature lines
	$pdf->text(59.28, $y+57, '                                 .........................................                             ................................');    						
	$pdf->text(60.28, $y+67, $signatory);    	
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
	
	include 'includes/transcriptkeys.php';
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
require_once('lecturerMenu.php');

# include the header
global $szSection, $szSubSection;
$szSection = 'Examination';
$szSubSection = 'Cand. Transcript';
$szTitle = 'Transcript of Examination Results';
require_once('lecturerheader.php');

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
<a href="lecturerTranscriptcount.php">Transcript Report</a>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" name="studentRoomApplication" id="studentRoomApplication">
<table width="284" border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
        <tr>
          <td colspan="9" nowrap><div align="left"></div></td>
        </tr>
        <tr>
          <td nowrap><div align="right"><strong>Template:
            </strong></div>            <div align="center"></div></td>
          <td colspan="3" nowrap><div align="right">Transcript:</div></td>
          <td nowrap><input type="radio" value="1" id="radio" name="temp" checked></td>
          <td colspan="4" nowrap><div align="right"></div></td>
	    </tr>
        <tr>
          <td nowrap><div align="right"><strong>Award:
            </strong></div>            <div align="center"></div></td>
          <td colspan="8" nowrap><div align="left">
            <select name="award" id="award">
              <option value="1" selected>Degree</option>
              <option value="2">Postgraduate </option>
              <option value="3">Diploma</option>
              <option value="4">Certificate</option>
              <option value="5">Short Course</option>
            </select>
            </div>            <div align="right"></div>            <div align="right"></div></td>
        </tr>
        <tr>
          <td align="right" nowrap><strong> RegNo/FullName:</strong></td>
          <td colspan="8" bordercolor="#ECE9D8" bgcolor="#CCCCCC"><span class="style67">
          <input name="key" type="text" id="key" size="40" maxlength="40">
          </span></td><td align="right" nowrap><strong> Example (Maere, Charlie)</strong></td>
        </tr>
		<tr> 
			<td align="right" nowrap><strong>Table:</strong></td> 
			<td width="35"><div align="center">11<input type="radio" value="11" id="sex" name="sex"></div></td> 
			<td width="35"><div align="center">12<input type="radio" value="12" id="sex" name="sex" checked></div></td> 
			<td width="35"><div align="center">13<input type="radio" value="13" id="sex" name="sex" ></div></td> 
			<td width="35"><div align="center">14<input type="radio" value="14" id="sex" name="sex" ></div></td> 
			<td width="35"><div align="center">15<input type="radio" value="15" id="sex" name="sex" ></div></td> 
			<td width="35"><div align="center">16<input type="radio" value="16" id="sex" name="sex" ></div></td> 
			<td width="35"><div align="center">-</div></td> 
			<td><div align="left">17<input type="radio" value="17" id="sex" name="sex" ></div></td>
		</tr>
        <tr>
          <td nowrap><div align="right"><strong>Confirmed:
            </strong></div>            
            <div align="center"></div></td>
          <td colspan="3" nowrap><div align="right">No</div></td>
          <td nowrap><input type="radio" value="1" id="real" name="real" checked></td>
          <td colspan="2" nowrap><div align="right"></div></td>
          <td nowrap><div align="right">Yes</div></td>
          <td nowrap><div align="left">
            <input type="radio" value="2" id="real" name="real" >
          </div></td>
        </tr>
        <tr>
          <td nowrap><div align="right"> </div></td>
          <td colspan="8" bgcolor="#CCCCCC">
            
            <div align="center">
              <input name="PrintPDF" type="submit" id="PrintPDF" value="Print PDF">
              </div></td>
        </tr>
  </table>
</form>
<p>&nbsp;</p>
<?php
}
include('../footer/footer.php');
?>
