<?php
	$pdf->setFont('Arial', 'I', 8);     
	$pdf->text(530.28, 820.89, 'Page '.$pg); 
	$pdf->text(300, 820.89, $copycount);   
	$pdf->text(50, 820.89, 'Dar-es-Salaam, '.$today = date("d-m-Y H:i:s"));  
	$pdf->text(118, 130, $regno.':'); 
	$pdf->text(210, 130, $cname); 
	$pdf->text(118, 142, $programme); 
	
	#draw a line
	$pdf->line($x, 145, 570.28, 145);        
	$pdf->line($x, $y+3, 570.28, $y+3);       
	$yval=$y+3;
	#create table header
	$pdf->text($x, $y, $rowayear->AYear); 
	$pdf->text($x+70, $y, 'Course Name'); 
	$pdf->text($x+412, $y, 'Unit'); 
	$pdf->text($x+436, $y, 'Grade'); 
	$pdf->text($x+471, $y, 'Point'); 
	if(($degree==632)||($degree==633)){
	 $pdf->text($x+499, $y, 'GPA'); 
	}
?>