<?php
		#Get Organisation Name
		$qorg = "SELECT Name FROM organisation";
		$dborg = mysql_query($qorg);
		$row_org = mysql_fetch_assoc($dborg);
		$org = $row_org['Name'];
		
		#print header
		if ($playout == 'l')
		{
			#tite for landscape
			$pdf->setFont('Arial', 'B', 26); 
			$x = 150;   
			$pdf->setFillColor('rgb', 0, 0, 0);   
			$pdf->text($x, 50, strtoupper($org)); 
			$pdf->text($x+120, 74, 'OF TUMAINI UNIVERSITY');     
			$pdf->setFillColor('rgb', 0, 0, 0);   
		}else
		{	
			#title for potriate
			$pdf->setFont('Arial', 'B', 23.7);
			$x = 50;  
			$pdf->setFillColor('rgb', 0, 0, 0);   
			$pdf->text($x, 50, strtoupper($org)); 
			$pdf->text($x+120, 74, 'OF TUMAINI UNIVERSITY');     
			$pdf->setFillColor('rgb', 0, 0, 0);   
		}
?>