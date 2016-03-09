<?php

    //die($degree);
	
		#print results
$y = 410;
$x = 50;
		$cname1 = 'Antenatal';
		$cname2 = 'Breech';
		$cname3 = 'Vaccum extraction';
				
		
		$chours1 = 1;
		$chours2 = 1;
		$chours3 = 1;
		

		$pdf->text($x+8, $y+$rh, $cname1); 
		$pdf->text($x+480, $y+$rh, $chours1); 
		$y = $y+12;

		$pdf->text($x+8, $y+$rh, $cname2); 
		$pdf->text($x+480, $y+$rh, $chours2); 
		$y = $y+12;

		$pdf->text($x+8, $y+$rh, $cname3); 
		$pdf->text($x+480, $y+$rh, $chours3); 
		$y = $y+12;
		
	

		