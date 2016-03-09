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
			$pdf->setFont('Arial', 'B', 24); 
			$x = 150;   
			$pdf->setFillColor('rgb', 0, 0, 0);   
			$pdf->text($x+120, 50, 'UNIVERSITY OF MALAWI');
			$pdf->setFont('Arial', 'B', 17); 
			$pdf->text($x+130, 70, strtoupper($org));     
			$pdf->setFillColor('rgb', 0, 0, 0);   
		}else
		{	
			#title for potriate
			$pdf->setFont('Arial', 'B', 23.7);
			$x = 50;  
			$pdf->setFillColor('rgb', 0, 0, 0); 
			$pdf->text($x+120, 50, 'UNIVERSITY OF MALAWI');
			$pdf->setFont('Arial', 'B', 14.7);
			$pdf->text($x+148, 70, strtoupper($org)); 
			$pdf->setFillColor('rgb', 0, 0, 0);   
		}
?>