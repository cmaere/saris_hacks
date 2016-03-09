<?php
		#draw a line
		$pdf->line($x, $y, 570.28, $y);       
		$pdf->line($x, $y, $x, $y+28); 
		$pdf->line(570.28, $y, 570.28, $y+28);
		#vertical lines
		$pdf->line($x+65, $y, $x+65, $y+28);  
		$pdf->line($x+145, $y, $x+145, $y+28); 
		$pdf->line($x+225, $y, $x+225, $y+28); 
		$pdf->line($x+305, $y, $x+305, $y+28); 
		$pdf->line($x+385, $y, $x+385, $y+28); 
		$pdf->line($x+455, $y, $x+455, $y+28); 
		
		#horizontal lines
		$pdf->line($x, $y+14, 570.28, $y+14); 
		$pdf->line($x, $y+28, 570.28, $y+28);  
		#row 1 text
		$pdf->text($x+2, $y+12, 'Marks   '); 
		$pdf->text($x+85, $y+12, '  0% - 44%  ');
		$pdf->text($x+155, $y+12, '  45% - 49%  ');
		$pdf->text($x+235, $y+12, '  50% - 64%  ');
		$pdf->text($x+315, $y+12, '  65% - 74%  ');
		$pdf->text($x+390, $y+12, '  75% - 100%   ');
		$pdf->text($x+480, $y+12, '     ');
		#row 2 text
		$pdf->text($x+2, $y+24, 'Remarks  '); 
		$pdf->text($x+65, $y+24, '  Undoubted Failure   ');
		$pdf->text($x+147, $y+24, '  Marginal Failure  ');
		$pdf->text($x+245, $y+24, '  Pass  ');
		$pdf->text($x+325, $y+24, '  Credit   ');
		$pdf->text($x+395, $y+24, '  Distinction   ');
		$pdf->text($x+470, $y+24, '    ');
?>