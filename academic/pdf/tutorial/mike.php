<?php
require('../fpdf.php');

class PDF extends FPDF
{
//Page header
function Header()
{
	//Logo
	//$this->Image('poly_logo.jpg',6,4);
	//Arial bold 15
	//$this->SetFont('Arial','B',15);
	//Move to the right
	//$this->Cell(80);
	//Title
	$this->Cell(30,10,'Acceptanee Letter',0,0,'C');
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

//Load data
function LoadData($file)
{
	//Read file lines
	$lines=file($file);
	$data=array();
	foreach($lines as $line)
		$data[]=explode(';',chop($line));
	return $data;
}

//Simple table
function BasicTable($header,$data)
{
	//Header
	foreach($header as $col)
		$this->Cell(40,7,$col,1);
	$this->Ln();
	//Data
	foreach($data as $row)
	{
		foreach($row as $col)
			$this->Cell(40,6,$col,1);
		$this->Ln();
	}
}

//Better table
function ImprovedTable($header,$data)
{
	//Column widths
	$w=array(40,35,40,45);
	//Header
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C');
	$this->Ln();
	//Data
	foreach($data as $row)
	{
		$this->Cell($w[0],6,$row[0],'LR');
		$this->Cell($w[1],6,$row[1],'LR');
		$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
		$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
		$this->Ln();
	}
	//Closure line
	$this->Cell(array_sum($w),0,'','T');
}

//Colored table
function FancyTable($header,$data)
{
	//Colors, line width and bold font
	$this->SetFillColor(0,0,255);
	$this->SetTextColor(255,255,255);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.3);
	$this->SetFont('Arial','',10);
	//Header
	$w=array(40,35,40,45);
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C',1);
	$this->Ln();
	//Color and font restoration
	$this->SetFillColor(224,235,255);
	$this->SetTextColor(0);
	$this->SetFont('Arial','',10);
	//Data
	$fill=0;
	foreach($data as $row)
	{
		$this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
		$this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
		$this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
		$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
		$this->Ln();
		$fill=!$fill;
	}
	$this->Cell(array_sum($w),0,'','T');
}

}

//Instanciation of inherited class
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

//Address
$pdf->SetFont('Arial','',10);
//for($i=1;$i<=40;$i++)
	$pdf->Cell(0,5,'Dear Mr Micheal Chibaka,',0,0);
    $pdf->Ln(4);
    $pdf->Cell(0,5,'We are pleased to offer you a place at the Malawi Polytechnic as follows',0,1);
    $pdf->Ln(10);
//Column titles
$header=array('Subject','Capital','Area (sq km)','Pop. (thousands)');
//Data loading
$data=$pdf->LoadData('countries.txt');
$pdf->SetFont('Arial','',10);
$pdf->FancyTable($header,$data);
 $pdf->Ln(10);   
 $pdf->Cell(0,5,'We hope you will enjoy your stay at Malawi Polytechnic',0,1);
 $pdf->Ln(10);
 $pdf->Cell(0,5,'Signed by : _____________________________',0,1);
 $pdf->Cell(0,5,'                          (Principal)',0,1);
    $pdf->Ln(10); 
    
$pdf->Output();
?>
