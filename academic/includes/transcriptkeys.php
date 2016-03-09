<?php
$pdf->addPage();  
$yind = 12;
	$pdf->setFont('Arial', '', 8); 
	$pdf->text(190.28, $yind, '          ######## KEYS OF TRANSCRIPT ########');   
	$pdf->text(50, $yind + 12, '1. The Transcript will be valid only if it bears the Institution Seal');
	$pdf->text(50, $yind + 24, '2.	Key to the Grades for Examinations: SEE THE TABLE BELOW ');
	$x=50;
	$y= $yind + 30;
	#table 1
	include 'gradescale.php';
    
    if($degree == 1002)
    {
	
    
    $pdf->setFont('Arial', 'B', 8); 
	    $pdf->text(50, $y + 42, '3. COURSES FOR THEORETICAL INSTRUCTION IN NURSING ');
		$pdf->text($x+477, $y + 42, 'HOURS'); 
	$pdf->setFont('Arial', '', 8); 
	    $y= $y + 40;
		include 'theoretical_course.php';
        
        		
	#table 3
	$pdf->setFont('Arial', 'B', 8); 
    	$y = $y+16;
	    $pdf->text(50, $y, '4. COURSES FOR CLINICAL EXPERIENCE IN NURSING');
		$pdf->text($x+477, $y, 'HOURS'); 
	$pdf->setFont('Arial', '', 8); 
		include 'clinical_course.php';

        
    
    }
    else if($degree == 1003)
   {

     #table 2
	$pdf->setFont('Arial', 'B', 8); 
	    $pdf->text(50, $y + 42, '3. COURSES FOR THEORETICAL INSTRUCTION IN NURSING ');
		$pdf->text($x+477, $y + 42, 'HOURS'); 
	$pdf->setFont('Arial', '', 8); 
	    $y= $y + 40;
		include 'theoretical_course.php';
#table 3
	$pdf->setFont('Arial', 'B', 8); 
    	$y = $y+16;
	    $pdf->text(50, $y, '4. COURSES FOR CLINICAL EXPERIENCE IN NURSING');
		$pdf->text($x+477, $y, 'HOURS'); 
	$pdf->setFont('Arial', '', 8); 
		include 'clinical_course.php';
#table 3
	$pdf->setFont('Arial', 'B', 8); 
    	$y = $y+16;
	    $pdf->text(50, $y, '5. PROCEDURE');
		$pdf->text($x+477, $y, 'Not Required'); 
	$pdf->setFont('Arial', '', 8); 
		include 'procedure_ucm.php';
#table 3
	$pdf->setFont('Arial', 'B', 8); 
    	$y = $y+16;
	    $pdf->text(50, $y, '6. CASE STUDY');
		$pdf->text($x+477, $y, 'Not Required'); 
	$pdf->setFont('Arial', '', 8); 
		include 'casestudy_ucm.php';







   }
    else
    {
	
	#table 2
	$pdf->setFont('Arial', 'B', 8); 
	    $pdf->text(50, $y + 42, '3. COURSES FOR THEORETICAL INSTRUCTION IN NURSING ');
		$pdf->text($x+477, $y + 42, 'HOURS'); 
	$pdf->setFont('Arial', '', 8); 
	    $y= $y + 40;
		include 'theoretical_course.php';
        /*
    $pdf->setFont('Arial', 'B', 8); 
	    $pdf->text(50, $y + 42, '4. COURSES FOR THEORETICAL INSTRUCTION IN MIDWIFERY ');
		$pdf->text($x+477, $y + 42, 'HOURS'); 
	$pdf->setFont('Arial', '', 8); 
	    $y= $y + 40;
		include 'theoretical_course_mid.php';*/
		
	#table 3
	$pdf->setFont('Arial', 'B', 8); 
    	$y = $y+16;
	    $pdf->text(50, $y, '5. COURSES FOR CLINICAL EXPERIENCE IN NURSING');
		$pdf->text($x+477, $y, 'HOURS'); 
	$pdf->setFont('Arial', '', 8); 
		include 'clinical_course.php';
        /*
        $pdf->setFont('Arial', 'B', 8); 
    	$y = $y+16;
	    $pdf->text(50, $y, '6. COURSES FOR CLINICAL EXPERIENCE IN MIDWIFERY');
		$pdf->text($x+477, $y, 'HOURS'); 
	$pdf->setFont('Arial', '', 8); 
		include 'clinical_course_mid.php';
        
         $pdf->setFont('Arial', 'B', 8); 
    	$y = $y+16;
	    $pdf->text(50, $y, '6. CASE STUDIES');
		$pdf->text($x+477, $y, 'HOURS'); 
	$pdf->setFont('Arial', '', 8); 
		include 'clinical_course_case.php';*/
        
        }

	#save print history
	if($realcopy==2){
		$printhistory = "INSERT INTO transcriptcount(RegNo, received, user) VALUES('$key',now(),'$username')";
		$result = mysql_query($printhistory);	
	}
?>