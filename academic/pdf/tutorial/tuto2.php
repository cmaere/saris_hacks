<?php
require('../fpdf.php');

class PDF extends FPDF
{
//Page header
function Header()
{
	//Logo
	
    this->Image('poly_logo.jpg',4,2);
    $this->Ln(10);
	//Arial bold 15
	$this->SetFont('Arial','B',15);
	//Move to the right
	$this->Cell(80);
	//Title
	$this->Cell(80,80,'Acceptance Letter',0,0);
	//Draw address
    $this->Ln(10);
    $this->SetFont('Arial','B',8);
    $this->Cell(0,5,'University Of Malawi',0,1,'C');
    $this->Cell(0,5,'Private Bag 303',0,1,'C');
    $this->Cell(0,5,'Chichiri Blantyre 3',0,1,'C');
	//Line break
    $this->Ln(40);
}

//Page footer
function Footer()
{
	//Position at 1.5 cm from bottom
	$this->SetY(-15);
	//Arial italic 8
	$this->SetFont('Arial','I',8);
	//Page number
	$this->Cell(0,10,'(c) University Of Malawi - Polytechnic . Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

//Instanciation of inherited class
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

//Address
$pdf->SetFont('Times','',10);
//for($i=1;$i<=40;$i++)
	$pdf->Cell(0,5,'Dear Mr Micheal Chibaka,',0,0);
    $pdf->Ln(4);
    $var = "At the College Assessment Meeting this semester, the Committee confirmed that your performance in Semester One was unsatisfactory.  You had a Marginal Failure Mark in one course.  Your overall performance is as follows:

tableofresults 
On the basis of these marks, it is unlikely that you will pass the final examinations unless you improve your performance.  Please be advised that students who fail to satisfy examiners in final assessment (which include continuous and first semester's assessment) may be required to take supplementary examinations or repeat the year or withdraw from college.

You are earnestly being urged to see your Dean or Head of Department concerned as soon as possible, to discuss your work and consider ways in which you can improve your academic performance.  If I can be of further assistance, please do not hesitate to see me.

";
    $pdf->Cell(0,5,$var,0,1);
    $pdf->Cell(0,5,'We are pleased to offer you a place at the Malawi Polytechnic as follows',0,1);
    $pdf->Cell(0,5,'We are pleased to offer you a place at the Malawi Polytechnic as follows',0,1);
    
    
$pdf->Output();
?>
