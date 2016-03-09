<?php

	$pdf->image('../images/logo.jpg', 280, 73);   
	$pdf->setFont('Arial', 'I', 8);     
	$pdf->text(530.28, 820.89, 'Page '.$pg);   
	//$pdf->setFont('Arial', 'B', 23);   
	//$pdf->text(66, 50, strtoupper($org));   
	//$pdf->text(50, 50, strtoupper($org));   
	include ('../includes/orgname.php');  
	$pdf->setFillColor('rgb', 0, 0, 0);  
	$pdf->setFont('Arial', 'I', 8);     
 
	$yadd=85;
	#organisation Addresses
	$pdf->setFont('Arial', '', 11.3);     
	$pdf->text(115, $yadd, 'Phone: '.$phone);
	$pdf->text(115, $yadd+12, 'Fax: '.$fax);    
	$pdf->text(115, $yadd+24, 'Email: '.$email);
	$pdf->text(350, $yadd, strtoupper($post));    
	$pdf->text(350, $yadd+12, strtoupper($city)); 
	$pdf->text(350, $yadd+24, $website);  
	
?>