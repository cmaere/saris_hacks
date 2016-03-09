<?php
	$pdf->setFont('Arial', 'I', 8);     
	$pdf->text(530.28, 820.89, 'Page '.$pg); 
	$pdf->text(50, 820.89, $city.' '.$today = date("d-m-Y H:i:s"));  
	$pdf->text(300, 820.89, $copycount);   
	$ytitle = $yadd;
	$pdf->setFillColor('rgb', 1, 0, 0);   
	$pdf->setFont('Arial', '', 13);     
	$pdf->text(180, $ytitle, 'EXAMINATIONS RESULTS CONTINUE'); 
	$pdf->setFillColor('rgb', 0, 0, 0);   
	$pdf->setFont('Arial', '', 9.5);     
	#title line
	$pdf->line(50, $ytitle+3, 570, $ytitle+3);
?>