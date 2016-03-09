<?php
	if ($userFaculty<>'44'){
	//echo '<span class="style1"><br><b>Privilege Violation Error!</b><br>You have no Rights to Print a <b>Candidate Trascript</b><br>-ZALONGWA DATABASE ADMINISTRATOR</span>';
	//echo '<meta http-equiv = "refresh" content ="4; url = lecturerTranscript.php">';
	//exit;
	}
	#signatory
	$signatory = '                                     College Registrar                                                 Date ';

	$pdf->image('../images/logo.jpg', 280, 76);   
	$pdf->setFont('Arial', 'I', 8);     
	$pdf->text(530.28, 820.89, 'Page '.$pg);   
	$pdf->setFont('Arial', 'B', 26);   
	//$pdf->text(66, 50, strtoupper($org));   
    include('../includes/orgname.php');
  	$pdf->setFont('Arial', 'I', 8);     
	$pdf->text(50, 820.89, $city.' '.$today = date("d-m-Y H:i:s"));   
	$pdf->text(300, 820.89, $copycount);   
 
	$yadd=109;
	#University Addresses
	$pdf->setFont('Arial', '', 11.3);     
	$pdf->text(115, $yadd, 'Phone: '.$phone);
	$pdf->text(115, $yadd+12, 'Fax: '.$fax);    
	$pdf->text(115, $yadd+24, 'Email: '.$email);
	$pdf->text(350, $yadd, strtoupper($post));    
	$pdf->text(350, $yadd+12, strtoupper($city)); 
	$pdf->text(350, $yadd+24, $website);  
	
	if ($nophoto == 1){
		# print no image box  
	}else{
		#candidate photo box
        //charlie chingwaru msosa modified the trancript photo to be implemented at a later stage
		/*$pdf->line(490, 55, 490, 135);       // leftside. 
		$pdf->line(490, 55, 570, 55);        // upperside. 
		$pdf->line(570, 55, 570, 135);       // rightside. 
		$pdf->line(490, 135, 570, 135);       // bottom side. 
		$pdf->image($imgfile, 490, 55);  */
	}
?>