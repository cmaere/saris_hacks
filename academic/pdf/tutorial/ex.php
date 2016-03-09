<?php
require("toc.php");
$pdf= new PDF_TOC(); 
$pdf->SetFont('Times','',12);
$pdf->AddPage();
$pdf->Cell(0,5,'Cover',0,1,'C');
$pdf->AddPage();
$pdf->startPageNums();
$pdf->Cell(0,5,'TOC1',0,1,'L');
$pdf->TOC_Entry('TOC1', 0);
$pdf->Cell(0,5,'TOC1.1',0,1,'L');
$pdf->TOC_Entry('TOC1.1', 1);
$pdf->AddPage();
$pdf->Cell(0,5,'TOC2',0,1,'L');
$pdf->TOC_Entry('TOC2', 0);
$pdf->AddPage();
for($i=3;$i<=80;$i++){
	$pdf->Cell(0,5,'TOC'.$i,0,1,'L');
	$pdf->TOC_Entry('TOC'.$i, 0);
}
$pdf->stopPageNums();
$pdf->AddPage();
$pdf->Cell(0,5,'Unnumbered page',0,1,'L');
//Generate and insert TOC at page 2
$pdf->insertTOC(2);
$pdf->Output();
?>
