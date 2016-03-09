<?php 
#start pdf
if (isset($_POST['printPDF']) && ($_POST['printPDF'] == "Print PDF")) {
	
	#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
	require_once('../Connections/zalongwa.php');
	include('includes/PDF.php');
	
	#get post values
	$year = addslashes($_POST['cohot']);
	$degree = addslashes($_POST['degree']);
	$list = addslashes($_POST['list']);
	$faculty = addslashes($_POST['faculty']);
	$display = addslashes($_POST['display']);
	$ryear = addslashes($_POST['ayear']);
	$semester = addslashes($_POST['semester']);
	#create report title
	if($display==1){
	$title = 'LIST OF ALL STUDENTS ACCEPTED IN '.$ryear.' ACADEMIC YEAR';
	}elseif($display==2){
	$title = 'LIST OF STUDENTS REGISTERED IN '.$ryear.' ACADEMIC YEAR';
	}else{
	$title = 'LIST OF STUDENTS NOT REGISTERED IN '.$ryear.' ACADEMIC YEAR';
	}
	#get programme name
	$qprogram = "SELECT ProgrammeName FROM programme WHERE ProgrammeCODE ='$degree'";
	$dbprogram = mysql_query($qprogram);
	$row_program = mysql_fetch_assoc($dbprogram);
	$pname = $row_program['ProgrammeName'];
	#create grouplist tiltes
	if($list==1){
	$listitle = 'OF ALL STUDENTS';
	}elseif($list==2){
	$listitle = $pname;
	}else{
	$listitle = $faculty;
	}
	if ($list ==1){
		$sql = "SELECT student.Id,
				   student.Name,
				   student.RegNo,
				   student.Sex,
				   student.Faculty,
				   student.EntryYear,
				   student.Sponsor,
				   student.Status,
				   student.ProgrammeofStudy
       
				FROM student
				WHERE 
  					 (
      					(student.EntryYear='$year') AND
						(student.ProgrammeofStudy <> '101	03')
   					 )
								ORDER BY  student.Faculty, 
								student.ProgrammeofStudy, 
								student.Name";
	}elseif ($list ==2){
		$sql = "SELECT student.Id,
				   student.Name,
				   student.RegNo,
				   student.Sex,
				   student.Faculty,
				   student.EntryYear,
				   student.Sponsor,
				   student.Status,
				   student.ProgrammeofStudy
				FROM student
				WHERE 
  					 (
						(student.EntryYear='$year') AND 
						(student.ProgrammeofStudy = '$degree') AND
						(student.ProgrammeofStudy <> '10103')
   					 )
						ORDER BY  student.Faculty, 
						student.ProgrammeofStudy, student.Name";
		}else{
				$sql = "SELECT student.Id,
				   student.Name,
				   student.RegNo,
				   student.Sex,
				   student.Faculty,
				   student.EntryYear,
				   student.Sponsor,
				   student.ProgrammeofStudy,
				   student.Status
				FROM faculty, student
				WHERE 
  					 (
      					(student.faculty = faculty.FacultyName) AND
						(student.EntryYear='$year') AND 
						(student.faculty = '$faculty')AND
						(student.ProgrammeofStudy <> '10103')
   					 )
						ORDER BY  student.Faculty, 
						student.ProgrammeofStudy, student.Name";
		}

		$result = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
		$query = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
		
		$all_query = mysql_query($query);
		$totalRows_query = mysql_num_rows($query);
		/* Printing Results in html */
		if (mysql_num_rows($query) > 0)
		{
				#Get Organisation Name
				$qorg = "SELECT * FROM organisation";
				$dborg = mysql_query($qorg);
				$row_org = mysql_fetch_assoc($dborg);
				$org = $row_org['Name'];
				$ptname = $row_org['ParentName'];
				$address = $row_org['Address'];
				$phone = $row_org['tel'];
				$fax = $row_org['fax'];
				$email = $row_org['email'];
				$website = $row_org['website'];
				$city = $row_org['city'];
				
				#get degree programme
				$qprogram = "SELECT ProgrammeName FROM programme WHERE ProgrammeCODE ='$degree'";
				$dbprogram = mysql_query($qprogram);
				$row_program = mysql_fetch_assoc($dbprogram);
				$degree = $row_program['ProgrammeName'];
				
				$pdf = &PDF::factory('p', 'a4');      // Set up the pdf object. 
				$pdf->open();                         // Start the document. 
				$pdf->setCompression(true);           // Activate compression. 
				$pdf->addPage();  
				#put page header
			
				$x=50;
				$y=200;
				$i=1;
				$pg=1;
				//$i=1;
				#count unregistered
				$j=0;
				#count sex
				$$fmcount = 0;
				$$mcount = 0;
				$$fcount = 0;
				#print header
				$pdf->image('../images/logo.jpg', 260, 37);   
				$pdf->setFont('Arial', 'I', 8);     
				$pdf->text(530.28, 825.89, 'Page '.$pg);  
				if($display==1){
				$pdf->text(50, 825.89, 'Printed On '.$today = date("d-m-Y H:i:s"));   
				}else{
				$pdf->text(50, 825.89, 'Printed On '.$today = date("d-m-Y H:i:s"));   
				} 
				
				include '../includes/orgheader.php';
				$pdf->setFillColor('rgb', 0, 0, 0);   
				$pdf->setFont('Arial', '', 13);     
				$pdf->text(80, 170, strtoupper($listitle).': '. $year.' - NOMINAL ROLL REPORT ('.$class.$sups.') YEAR STUDENTS'); 
				$pdf->setFillColor('rgb', 0, 0, 0);   
				
				$pdf->setFillColor('rgb', 0, 0, 0);   
				$pdf->setFont('Arial', 'B', 11);   
				$pdf->setFillColor('rgb', 0, 0, 0);   
		
				$pdf->text($x, $y, 'S/N'); 
				$pdf->text($x+40, $y, 'Name'); 
				$pdf->text($x+240, $y, 'RegNo'); 
				$pdf->text($x+405, $y, 'Sex'); 
				$pdf->text($x+427, $y, 'Programme'); 
				$pdf->setFont('Arial', '', 11);     
				
				$pdf->line($x, $y-15, 570.28, $y-15);       
				$pdf->line($x, $y+3, 570.28, $y+3);       
				$pdf->line($x, $y-15, $x, $y+3);              
				$pdf->line($x+25, $y-15, $x+25, $y+3);              
				$pdf->line($x+221, $y-15, $x+221, $y+3);             
				$pdf->line($x+402, $y-15, $x+402, $y+3);             
				$pdf->line($x+425, $y-15, $x+425, $y+3);             
				$pdf->line(570.28, $y-15, 570.28, $y+3);      
				$pdf->line($x, $y+19, 570.28, $y+19);      
				
				while($result = mysql_fetch_array($query)) {
					$id = stripslashes($result["Id"]);
					$Name = stripslashes($result["Name"]);
					$RegNo = stripslashes($result["RegNo"]);
					$sex = stripslashes($result["Sex"]);
					$degreecode = stripslashes($result["ProgrammeofStudy"]);
					$faculty = stripslashes($result["Faculty"]);
					$sponsor = stripslashes($result["Sponsor"]);
					
					#get study programe name
					$qprogram = "SELECT ProgrammeName FROM programme WHERE ProgrammeCODE ='$degreecode'";
					$dbprogram = mysql_query($qprogram);
					$row_program = mysql_fetch_assoc($dbprogram);
					$degree = $row_program['ProgrammeName'];
					#check if the candidate has registered to a course in this current year
					$qstatus = "SELECT DISTINCT RegNo FROM examresult WHERE RegNo='$RegNo' AND AYear='$ryear'";
					$dbstatus = mysql_query($qstatus);
					$statusvalue = mysql_num_rows($dbstatus);
					if($statusvalue>0){
						
						$qstatus = "SELECT DISTINCT RegNo FROM examregister WHERE RegNo='$RegNo' AND AYear='$ryear' AND Semester = '$semester'";
						
					$dbstatus = mysql_query($qstatus);
						$statusvalue = mysql_num_rows($dbstatus);
						if($statusvalue>0){
							$status  = stripslashes($result["Status"]);
							}else{
							$status = 'Not Registered';
							$j=$j+1;
							}
					}else{
					#check in examregister
					//die("in here right?");
						$qstatus = "SELECT DISTINCT RegNo FROM examregister WHERE RegNo='$RegNo' AND AYear='$ryear' AND Semester = '$semester'";
						//die($qstatus);
						$dbstatus = mysql_query($qstatus);
						$statusvalue = mysql_num_rows($dbstatus);
						if($statusvalue>0){
							$status  = stripslashes($result["Status"]);
							}else{
							$status = 'Not Registered';
							$j=$j+1;
							}
					}
					#get line color
					$remainder = $i%2;
					if ($remainder==0){
						$linecolor = 1;
					}else{
					 $linecolor = 2;//'bgcolor="#FFFFFF"';
					}
					
					if($display==1){
						if($status === 'Not Registered'){
						$pdf->setFillColor('rgb', 1, 0, 0); 
                        $pdf->text($x+336, $y+15, '(NOT REG)'); 
						}else{
						$pdf->setFillColor('rgb', 0, 0, 0);  
						}
						
						$pdf->text($x, $y+15, $i); 
						$pdf->text($x+30, $y+15, $Name); 
						$pdf->text($x+226, $y+15, $RegNo); 
						$pdf->text($x+405, $y+15, $sex); 
						$pdf->text($x+427, $y+15, substr($degree,0,26)); 
			            $pdf->setFillColor('rgb', 0, 0, 0);
						$i=$i+1;
						if ($sex=='F'){
							$fcount = $fcount +1;
						}elseif($sex=='M'){
							$mcount = $mcount +1;
						}else{
							$fmcount = $fmcount +1;
						}
                        // cha edit nominal roll lines
						$x=$x;
						$y=$y+15;
						$pdf->line(50, $y-15, 50, $y);               
						$pdf->line($x, $y+3, 570.28, $y+3);     
						$pdf->line($x, $y-15, $x, $y+3);              
						$pdf->line($x+25, $y-15, $x+25, $y+3);               
						$pdf->line($x+221, $y-15, $x+221, $y+3);               
						$pdf->line($x+402, $y-15, $x+402, $y+3);  
                            //line on gender                        
						$pdf->line($x+425, $y-15, $x+425, $y+3);               
						$pdf->line(570.28, $y-15, 570.28, $y+3);      
					}elseif($display==2){
						if($status <>'Not Registered'){
						$pdf->text($x, $y+15, $i); 
						$pdf->text($x+30, $y+15, $Name); 
						$pdf->text($x+226, $y+15, $RegNo); 
						$pdf->text($x+405, $y+15, $sex); 
						$pdf->text($x+427, $y+15, substr($degree,0,26)); 
						
						$i=$i+1;
						if ($sex=='F'){
							$fcount = $fcount +1;
						}elseif($sex=='M'){
							$mcount = $mcount +1;
						}else{
							$fmcount = $fmcount +1;
						}
						$x=$x;
						$y=$y+15;
						$pdf->line(50, $y-15, 50, $y);               
						$pdf->line($x, $y+3, 570.28, $y+3);     
						$pdf->line($x, $y-15, $x, $y+3);              
						$pdf->line($x+35, $y-15, $x+35, $y+3);               
						$pdf->line($x+231, $y-15, $x+231, $y+3);               
						$pdf->line($x+340, $y-15, $x+340, $y+3);                
						$pdf->line($x+370, $y-15, $x+370, $y+3);               
						$pdf->line(570.28, $y-15, 570.28, $y+3);      
					  }
					}else{
						if($status === 'Not Registered'){
						$pdf->text($x, $y+15, $i); 
						$pdf->text($x+30, $y+15, $Name); 
						$pdf->text($x+226, $y+15, $RegNo); 
						$pdf->text($x+405, $y+15, $sex); 
						$pdf->text($x+427, $y+15, substr($degree,0,26));  
						$i=$i+1;
						if ($sex=='F'){
							$fcount = $fcount +1;
						}elseif($sex=='M'){
							$mcount = $mcount +1;
						}else{
							$fmcount = $fmcount +1;
						}
						$x=$x;
						$y=$y+15;
						$pdf->line(50, $y-15, 50, $y);               
						$pdf->line($x, $y+3, 570.28, $y+3);     
						$pdf->line($x, $y-15, $x, $y+3);              
						$pdf->line($x+35, $y-15, $x+35, $y+3);               
						$pdf->line($x+231, $y-15, $x+231, $y+3);               
						$pdf->line($x+340, $y-15, $x+340, $y+3);                
						$pdf->line($x+370, $y-15, $x+370, $y+3);               
						$pdf->line(570.28, $y-15, 570.28, $y+3);      
					  }
					}

			
					if ($y>800){
						#put page header
						//include('PDFTranscriptPageHeader.inc');
						$pdf->addPage();  
						$x=50;
						$y=50;
						$pg=$pg+1;
				
						$pdf->setFont('Arial', 'I', 11);     
						$pdf->text(530.28, 825.89, 'Page '.$pg);   
						if($display==1){
						$pdf->text(50, 825.89, 'Printed On '.$today = date("d-m-Y H:i:s"));   
						}else{
						$pdf->text(50, 825.89, 'Printed On '.$today = date("d-m-Y H:i:s"));   
						} 
						
						$pdf->setFillColor('rgb', 0, 0, 0);   
						$pdf->setFont('Arial', 'B', 11);   
						$pdf->setFillColor('rgb', 0, 0, 0);   

						$pdf->text($x, $y, 'S/N'); 
				$pdf->text($x+40, $y, 'Name'); 
				$pdf->text($x+240, $y, 'RegNo'); 
				$pdf->text($x+405, $y, 'Sex'); 
				$pdf->text($x+427, $y, 'Programme'); 
				$pdf->setFont('Arial', '', 11);     
				
				$pdf->line($x, $y-15, 570.28, $y-15);       
				$pdf->line($x, $y+3, 570.28, $y+3);       
				$pdf->line($x, $y-15, $x, $y+3);              
				$pdf->line($x+25, $y-15, $x+25, $y+3);              
				$pdf->line($x+221, $y-15, $x+221, $y+3);             
				$pdf->line($x+402, $y-15, $x+402, $y+3);             
				$pdf->line($x+425, $y-15, $x+425, $y+3);             
				$pdf->line(570.28, $y-15, 570.28, $y+3);      
				$pdf->line($x, $y+19, 570.28, $y+19);       
					}
			 }//ends while loop
					if ($y>763){
						#put page header
						//include('PDFTranscriptPageHeader.inc');
						$pdf->addPage();  
						$x=50;
						$y=50;
						$pg=$pg+1;
				
						$pdf->setFont('Arial', 'I', 11);     
						$pdf->text(530.28, 825.89, 'Page '.$pg);   
						if($display==1){
						$pdf->text(50, 825.89, 'Printed On '.$today = date("d-m-Y H:i:s"));   
						}else{
						$pdf->text(50, 825.89, 'Printed On '.$today = date("d-m-Y H:i:s"));   
						} 
						
						$pdf->setFillColor('rgb', 0, 0, 0);   
						$pdf->setFont('Arial', 'B', 11);   
						$pdf->setFillColor('rgb', 0, 0, 0);   

						$pdf->text($x, $y, 'S/N'); 
                        $pdf->text($x+50, $y, 'Name'); 
                        $pdf->text($x+250, $y, 'RegNo'); 
                        $pdf->text($x+415, $y, 'Sex'); 
                        $pdf->text($x+450, $y, 'Programme'); 
                        $pdf->setFont('Arial', '', 8);     
                        
                        $pdf->line($x, $y-15, 570.28, $y-15);       
                        $pdf->line($x, $y+3, 570.28, $y+3);       
                        $pdf->line($x, $y-15, $x, $y+3);              
                        $pdf->line($x+35, $y-15, $x+35, $y+3);              
                        $pdf->line($x+231, $y-15, $x+231, $y+3);             
                        $pdf->line($x+412, $y-15, $x+412, $y+3);             
                        $pdf->line($x+446, $y-15, $x+446, $y+3);             
                        $pdf->line(570.28, $y-15, 570.28, $y+3);      
                        $pdf->line($x, $y+19, 570.28, $y+19);      
					}
			$pdf->setFillColor('rgb', 0, 0, 0);
			$gt=$i-1;
			$pdf->text(50, $y+20, 'Grand Total: '.$gt);  

			if ($display==1){
			$pdf->setFillColor('rgb', 1, 0, 0);
			$pdf->text(50, $y+40, 'Total Unregistered Students  are: '.$j.'('.round($j/$gt*100,2).'%) - SEE THE RED LINES!');  
			$pdf->setFillColor('rgb', 0, 0, 0);
			}
			$pdf->text(50, $y+60, 'Total Female Students are: '.$fcount.'('.round($fcount/$gt*100,2).'%)');  
			$pdf->text(50, $y+80, 'Total Male Students are: '.$mcount.'('.round($mcount/$gt*100,2).'%)'); 
			if($fmcount<>0){
			$pdf->text(50, $y+100, 'Total Male/Female Unspecified Students are '.$fmcount.'('.round($fmcount/$gt*100,2).'%)'); 
			}

			//$pdf->text(200.28, 800.89, '.........................................................        ............................');   // Text at x=100 and y=100. 						
			//$pdf->text(200.28, 810.89, '          For Chief Academic Officer                    Date');   // Text at x=100 and y=100. 						
			$pdf->setFont('Arial', 'I', 8);     // Set font to arial bold italic 12 pt. 
			$pdf->output($year.'-nominalroll'.'.pdf');              // Output the 
		}else{echo "Sorry, No Records Found <br>";
	}
	exit;
	
} 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('admissionMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Admission Process';
	$szSubSection = 'Nominal Roll';
	$szTitle = 'Nominal Roll';
	include('admissionheader.php');

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

mysql_select_db($database_zalongwa, $zalongwa);
$query_AcademicYear = "SELECT AYear FROM academicyear ORDER BY AYear DESC";
$AcademicYear = mysql_query($query_AcademicYear, $zalongwa) or die(mysql_error());
$row_AcademicYear = mysql_fetch_assoc($AcademicYear);
$totalRows_AcademicYear = mysql_num_rows($AcademicYear);

mysql_select_db($database_zalongwa, $zalongwa);
$query_Hostel = "SELECT ProgrammeCode, ProgrammeName FROM programme ORDER BY ProgrammeName ASC";
$Hostel = mysql_query($query_Hostel, $zalongwa) or die(mysql_error());
$row_Hostel = mysql_fetch_assoc($Hostel);
$totalRows_Hostel = mysql_num_rows($Hostel);

mysql_select_db($database_zalongwa, $zalongwa);
$query_faculty = "SELECT FacultyID, FacultyName FROM faculty ORDER BY FacultyName ASC";
$faculty = mysql_query($query_faculty, $zalongwa) or die(mysql_error());
$row_faculty = mysql_fetch_assoc($faculty);
$totalRows_faculty = mysql_num_rows($faculty);

//Print Room Allocation Report
if (isset($_POST['print']) && ($_POST['print'] == "PreView")) {
#get post variables
$year = addslashes($_POST['cohot']);
$degree = addslashes($_POST['degree']);
$list = addslashes($_POST['list']);
$faculty = addslashes($_POST['faculty']);
$display = addslashes($_POST['display']);
$ryear = addslashes($_POST['ayear']);

	if ($list ==1){
		$sql = "SELECT student.Id,
				   student.Name,
				   student.RegNo,
				   student.Sex,
				   student.Faculty,
				   student.EntryYear,
				   student.Sponsor,
				   student.Status,
				   student.ProgrammeofStudy
       
				FROM student
				WHERE 
  					 (
      					(student.EntryYear='$year') AND
						(student.ProgrammeofStudy <> '10103')
   					 )
								ORDER BY  student.Faculty, 
								student.ProgrammeofStudy, 
								student.Name";
	}elseif ($list ==2){
		$sql = "SELECT student.Id,
				   student.Name,
				   student.RegNo,
				   student.Sex,
				   student.Faculty,
				   student.EntryYear,
				   student.Sponsor,
				   student.Status,
				   student.ProgrammeofStudy
				FROM student
				WHERE 
  					 (
						(student.EntryYear='$year') AND 
						(student.ProgrammeofStudy = '$degree') AND
						(student.ProgrammeofStudy <> '10103')
   					 )
						ORDER BY  student.Faculty, 
						student.ProgrammeofStudy, student.Name";
		}else{
				$sql = "SELECT student.Id,
				   student.Name,
				   student.RegNo,
				   student.Sex,
				   student.Faculty,
				   student.EntryYear,
				   student.Sponsor,
				   student.ProgrammeofStudy,
				   student.Status
				FROM faculty, student
				WHERE 
  					 (
      					(student.faculty = faculty.FacultyName) AND
						(student.EntryYear='$year') AND 
						(student.faculty = '$faculty')AND
						(student.ProgrammeofStudy <> '10103')
   					 )
						ORDER BY  student.Faculty, 
						student.ProgrammeofStudy, student.Name";
		}
	$result = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
	$query = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

	$all_query = mysql_query($query);
	$totalRows_query = mysql_num_rows($query);
	/* Printing Results in html */
	if (mysql_num_rows($query) > 0){
		#Get Organisation Name
		$qorg = "SELECT Name FROM organisation";
		$dborg = mysql_query($qorg);
		$row_org = mysql_fetch_assoc($dborg);
		$org = $row_org['Name'];
		
		#get degree programme
		$qprogram = "SELECT ProgrammeName FROM programme WHERE ProgrammeCODE ='$degree'";
		$dbprogram = mysql_query($qprogram);
		$row_program = mysql_fetch_assoc($dbprogram);
		$degree = $row_program['ProgrammeName'];
		?>
<style type="text/css">
<!--
.style1 {color: #990000}
.style2 {font-size: 14px}
.style3 {
	font-size: 14;
	font-weight: bold;
}
.style4 {color: #000000}
-->
</style>

		
		<table width="100%"  border="0">
          <tr>
            <td></td>
          </tr>
          <tr>
            <td><div align="center" class="style4">
              <h1><?php echo $org?></h1>
            </div></td>
          </tr>
		  <?php if ($faculty<> '0'){?>
          <tr>
            <td bgcolor="#FFFFFF"><div align="center">
              <h3><?php echo $faculty?></h3>
            </div></td>
          </tr>
		  <?php } ?>
          <tr>
            <td><div align="center">
              <h4>Cohot <?php echo $year?> Nominal Roll</h4>
            </div></td>
          </tr>
          <tr>
            <td bgcolor="#FFFFFF"><div align="center">
              <h4>A <?php echo $ryear?> Status Report</h4>
            </div></td>
          </tr>
		  <?php if ($degree<> '0'){?>
          <tr>
            <td><div align="center">
              <h4><span class="style4"> <?php echo $degree?></span></h4>
            </div></td>
          </tr>
		   <?php } ?>
        </table>
		<table border='1' cellpadding='0' cellspacing='0'>
		<tr ><td> S/No </td><td> Name </td>
		<td> RegNo </td><td> Sex </td><td> Degree </td><td> Faculty </td><td> Sponsor </td><td> Status </td></tr>
		<?php
		$i=1;
		#count unregistered
		$j=0;
		#count sex
		$$fmcount = 0;
		$$mcount = 0;
		$$fcount = 0;
		while($result = mysql_fetch_array($query)) {
				$id = stripslashes($result["Id"]);
				$Name = stripslashes($result["Name"]);
				$RegNo = stripslashes($result["RegNo"]);
				$sex = stripslashes($result["Sex"]);
				$degreecode = stripslashes($result["ProgrammeofStudy"]);
				$faculty = stripslashes($result["Faculty"]);
				$sponsor = stripslashes($result["Sponsor"]);
				
				#get study programe name
				$qprogram = "SELECT ProgrammeName FROM programme WHERE ProgrammeCODE ='$degreecode'";
				$dbprogram = mysql_query($qprogram);
				$row_program = mysql_fetch_assoc($dbprogram);
				$degree = $row_program['ProgrammeName'];
				#check if the candidate has registered to a course in this current year
				$qstatus = "SELECT DISTINCT RegNo FROM examresult WHERE RegNo='$RegNo' AND AYear='$ryear'";
				$dbstatus = mysql_query($qstatus);
				$statusvalue = mysql_num_rows($dbstatus);
				if($statusvalue>0){
				$status  = stripslashes($result["Status"]);
				}else{
				#check in examregister
					$qstatus = "SELECT DISTINCT RegNo FROM examregister WHERE RegNo='$RegNo' AND AYear='$ryear' AND Semester = '$semester'";
					//die($qstatus);
					$dbstatus = mysql_query($qstatus);
					$statusvalue = mysql_num_rows($dbstatus);
					if($statusvalue>0){
						$status  = stripslashes($result["Status"]);
						}else{
						$status = 'Not Registered';
						$j=$j+1;
						}
				}
				#get line color
				$remainder = $i%2;
				if ($remainder==0){
					$linecolor = 'bgcolor="#FFFFCC"';
				}else{
				 $linecolor = 'bgcolor="#FFFFFF"';
				}

				if($display==1){
					echo "<tr><td $linecolor><a href=\"admissionRegistrationForm.php?id=$id&RegNo=$RegNo\">$i</a></td>";
					?>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $Name?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $RegNo?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $sex?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $degree?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $faculty?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $sponsor?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $status?></td>
					<?php
					echo "</tr>";
				    $i=$i+1;
					if ($sex=='F'){
						$fcount = $fcount +1;
					}elseif($sex=='M'){
						$mcount = $mcount +1;
					}else{
						$fmcount = $fmcount +1;
					}
				}elseif($display==2){
					if($status <>'Not Registered'){
					echo "<tr><td $linecolor><a href=\"admissionRegistrationForm.php?id=$id&RegNo=$RegNo\">$i</a></td>";
					?>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $Name?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $RegNo?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $sex?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $degree?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $faculty?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $sponsor?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $status?></td>
					<?php
					echo "</tr>";
					$i=$i+1;
						if ($sex=='F'){
							$fcount = $fcount +1;
						}elseif($sex=='M'){
							$mcount = $mcount +1;
						}else{
							$fmcount = $fmcount +1;
						}
					}				
				}else{
					if($status === 'Not Registered'){
					echo "<tr><td $linecolor><a href=\"admissionRegistrationForm.php?id=$id&RegNo=$RegNo\">$i</a></td>";
					?>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $Name?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $RegNo?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $sex?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $degree?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $faculty?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $sponsor?></td>
					<td <?php echo $linecolor?> nowrap><?php echo ($status === 'Not Registered')?'<span class="style1">':'';?><?php echo $status?></td>
					<?php
					echo "</tr>";
						$i=$i+1;
							if ($sex=='F'){
								$fcount = $fcount +1;
							}elseif($sex=='M'){
								$mcount = $mcount +1;
							}else{
								$fmcount = $fmcount +1;
							}
					}
				}
				#end while loop
				}
			echo "</table>";
			#print statistics
			$gt=$i-1;
			echo 'Grand Total: '.$gt;
			echo '<hr>';
			if ($display==1){
				echo 'Total Unregistered Students  are: '.$j.'('.round($j/$gt*100,2).'%)';
			}
				echo '<hr> Total Female Students are: '.$fcount.'('.round($fcount/$gt*100,2).'%)';
				echo '<hr> Total Male Students are: '.$mcount.'('.round($mcount/$gt*100,2).'%)';
			if($fmcount<>0){
				echo '<hr> Total Male/Female Unspecified Students are '.$fmcount.'('.round($fmcount/$gt*100,2).'%)';
			}
			}else{
					echo "Sorry, No Records Found <br>";
				}
}else{

?>

<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" name="studentRoomApplication" id="studentRoomApplication">
            <table  border="1" cellpadding="0" cellspacing="0" >
        <tr>
          <td colspan="5" nowrap><div align="center">PRINTING NOMINAL ROLL </div></td>
          </tr>
		  <tr>
			  <td rowspan="2" nowrap><div align="right">GROUP LIST:</div></td>
			  <td  nowrap><div align="center">All Students</div></td>
			  <td nowrap ><div align="center">Programme</div></td>
			  <td nowrap ><div align="center">Faculty</div></td>
	          <td nowrap >&nbsp;</td>
		  </tr>
		  <tr>
		    <td  nowrap><div align="center"><input name="list" type="radio" value="1"></div></td>
		    <td nowrap ><div align="center"><input name="list" type="radio" value="2" checked></div></td>
		    <td nowrap ><div align="center"><input name="list" type="radio" value="3"></div></td>
		    <td nowrap >&nbsp;</td>
		  </tr>
        <tr>
          <td nowrap><div align="right">REPORTING  YEAR: </div></td>
          <td colspan="4" ><select name="ayear" id="select2">
		 <option value="0">--------------------------------</option>
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
			  <td rowspan="2" nowrap><div align="right">DISPLAY OPTIONS:</div></td>
			  <td  nowrap><div align="center">All Students</div></td>
			  <td nowrap ><div align="center">Registered</div></td>
			  <td nowrap ><div align="center">Not Registered </div></td>
	          <td nowrap >&nbsp;</td>
		  </tr>
		  <tr>
		    <td  nowrap><div align="center">
		      <input name="display" type="radio" value="1" checked>
		    </div></td>
		    <td nowrap ><div align="center"><input name="display" type="radio" value="2"></div></td>
		    <td nowrap ><div align="center"><input name="display" type="radio" value="3"></div></td>
		    <td nowrap >&nbsp;</td>
		  </tr>
        <tr>
          <td nowrap><div align="right">STUDENT COHORT: </div></td>
          <td colspan="4" ><select name="cohot" id="select2">
		  <option value="0">--------------------------------</option>
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
          <td nowrap><div align="right">SEMESTER: </div></td>
          <td colspan="4" ><select name="semester" id="semester">
		  <option value="0">--------------------------------</option>
          <option value="Semester I">Semester I</option>
          <option value="Semester II">Semester II</option>
            
          </select></td>
        </tr>
        
        
        
        
        
        
        <tr>
          <td nowrap><div align="right"> FACULTY/COLLEGE:</div></td>
          <td colspan="4" ><select name="faculty" id="select">
		  <option value="0">--------------------------------</option>
            <?php
do {  
?>
            <option value="<?php echo $row_faculty['FacultyName']?>"><?php echo $row_faculty['FacultyName']?></option>
            <?php
} while ($row_faculty = mysql_fetch_assoc($faculty));
  $rows = mysql_num_rows($faculty);
  if($rows > 0) {
      mysql_data_seek($faculty, 0);
	  $row_faculty = mysql_fetch_assoc($faculty);
  }
?>
          </select></td>
        </tr>
        <tr>
          <td nowrap><div align="right">  PROGRAMME:</div></td>
          <td colspan="4" ><select name="degree" id="select">
		   <option value="0">--------------------------------</option>
            <?php
do {  
?>
            <option value="<?php echo $row_Hostel['ProgrammeCode']?>"><?php echo $row_Hostel['ProgrammeName']?></option>
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
          <td >
		    <div align="right">
		      <input name="print" type="submit" id="print" value="PreView">
		        </div></td>
          <td >&nbsp;</td>
          <td >
            <div align="left">
              <input name="printPDF" type="submit" id="printPDF" value="Print PDF">
            </div></td>
          <td >&nbsp;</td>
        </tr>
      </table>
                    <input type="hidden" name="MM_insert" value="housingRoomApplication">
          </form>
<?php
}
mysql_free_result($AcademicYear);

mysql_free_result($Hostel);
include('../footer/footer.php');
?>
