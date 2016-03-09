<?php

    //die($degree);
	
		#print results
$y = 260;
$x = 50;
		$cname1 = 'Complete assessment of pregnant woman at first antenatal visit';
		$cname2 = 'Complete assessment of pregnant woman on subsequent antenatal';
		$cname3 = 'Vaginal examinations including pelvic assessment';
		$cname4 = 'Spontaneous vetex deliver';
		$cname5 = 'Episiotomy with repair under local anaesthesia';
		$cname6 = 'Deliver by vacuum extraction';
		$cname7 = 'Repair of perineal laceration under local anaesthesia';
		$cname8 = 'Breech delivery';
		$cname9 = 'Multiple delivery';
		$cname10= 'Management of postnatal mother and their infants';
		$cname11= 'Postnatal assessments of mother and infant at 6 weeks';

		
		$chours1 = 20;
		$chours2 = 60;
		$chours3 = 20;
		$chours4 = 40;
		$chours5 = 5;
		$chours6 = 6;
		$chours7 = 3;
		$chours8 = 2;
		$chours9 = 2;
		$chours10 = 40;
		$chours11 = 10;

		$pdf->text($x+8, $y+$rh, $cname1); 
		$pdf->text($x+480, $y+$rh, $chours1); 
		$y = $y+12;

		$pdf->text($x+8, $y+$rh, $cname2); 
		$pdf->text($x+480, $y+$rh, $chours2); 
		$y = $y+12;

		$pdf->text($x+8, $y+$rh, $cname3); 
		$pdf->text($x+480, $y+$rh, $chours3); 
		$y = $y+12;
		
	

		$pdf->text($x+8, $y+$rh, $cname4); 
		$pdf->text($x+480, $y+$rh, $chours4); 
		$y = $y+12;

		$pdf->text($x+8, $y+$rh, $cname5); 
		$pdf->text($x+480, $y+$rh, $chours5); 	
		$y = $y+12;

		$pdf->text($x+8, $y+$rh, $cname6); 
		$pdf->text($x+480, $y+$rh, $chours6); 
		$y = $y+12;

		$pdf->text($x+8, $y+$rh, $cname7); 
		$pdf->text($x+480, $y+$rh, $chours7); 
		$y = $y+12;

		$pdf->text($x+8, $y+$rh, $cname8); 
		$pdf->text($x+480, $y+$rh, $chours8); 
		$y = $y+12;

		$pdf->text($x+8, $y+$rh, $cname9); 
		$pdf->text($x+480, $y+$rh, $chours9); 
		$y = $y+12;

		$pdf->text($x+8, $y+$rh, $cname10); 
		$pdf->text($x+480, $y+$rh, $chours10); 
		$y = $y+12;

		$pdf->text($x+8, $y+$rh, $cname11); 
		$pdf->text($x+480, $y+$rh, $chours11); 
		$y = $y+12;

?>