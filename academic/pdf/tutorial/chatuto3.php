<?php
require('../fpdf.php');
require_once('../../../src/common.php');



function getCourseGrade($Code)
    {
    
     global $dbConn;
     
       
    
     $sqlCoursegrade=sprintf("SELECT students_final_grades.Course_Code, students_final_grades.FinalGrade_College,students_eos_results.EOSAverage_College
                              FROM students_final_grades,students_eos_results
                              WHERE students_final_grades.Stud_RegNum='BIT/06/PE/017'
                              AND students_final_grades.AcYr='2006'
                              AND students_final_grades.Class_ID='BIT4'
                              AND students_final_grades.Stud_RegNum=students_eos_results.Stud_RegNum
                              AND students_eos_results.Semester='2'
                              AND students_final_grades.Course_Code='%s'",$Code);
                     
     $grade=$dbConn->query($sqlCoursegrade);
     
     $gradeObj=$grade->fetchRow(DB_FETCHMODE_OBJECT);
     
     $val[0]=$gradeObj->FinalGrade_College;
     $val[1]=$gradeObj->EOSAverage_College;
          
     return $val;
    
    }
    
class PDF extends FPDF
{

function getCourseName()
    {
    global $dbConn;
    
    
     $sqlCourseName="SELECT course.Course_Code,course.Course_Name
                     FROM course
                     WHERE course.Class_ID='BIT4'
                     ORDER BY course.Course_Code";
                     
                  
     $name=$dbConn->query($sqlCourseName);
     //print "<table border=1>";
     $i=1; $b=2; $c=3;
     $a=0; $inc=1;
     $datac=array();
    while($CourseName=$name->fetchRow(DB_FETCHMODE_OBJECT))
    {
     
     //foreach $Coursename as $CourseName
     //{
     $CourseCode=$CourseName->Course_Code;
     
     $cname=$CourseName->Course_Name;     
          
    $grade=getCourseGrade($CourseCode);
     $coursegrade=$grade[0];
     $average=$grade[1];
     
     if($coursegrade=='')
     {
        $coursegrade='-';
     }
     
     
     if($average=='')
     {
        //This part should be left blank
     }
     else
     {
       $avg1=$average;
     }
         
    $datac[$a]= $inc.';';
    $datac[$b]= $CourseCode.';';
    $datac[$i]= $cname.';';
    $datac[$c]="$coursegrade\n";
    //printReport($cname,$CourseCode,$coursegrade);
    $a=$a+4;
    $i=$i+4;
    $b=$b+4;
    $c=$c+4;
    $inc++;
    
    //}
     
    }
    
   $count = count($datac);
   $datac[$count+1]=';';
   $datac[$count+2]=';';
   $datac[$count+3]='Average;';
   $datac[$count+4]=$avg1;
   
    
    $f=fopen('cha.txt','w');
    foreach($datac as $i)
    {
	$txt=fwrite($f,$i);
    }
	fclose($f);
    }
    

function Header()
{
	global $title;

	//Arial bold 15
    $this->Image('poly_logo.jpg',90,6,17);
    $this->Ln(8);
	$this->SetFont('Arial','B',10);
	//Calculate width of title and position
	$w=$this->GetStringWidth($title)+120;
	$this->SetX((210-$w)/2);
	//Colors of frame, background and text
	//$this->SetDrawColor(0,80,180);
	//$this->SetFillColor(230,230,0);
	//$this->SetTextColor(220,50,50);
	//Thickness of frame (1 mm)
    $this->Ln(10);
	$this->SetLineWidth(1);
	//Title
    
	$this->Cell($w,9,$title,0,0,'C',0);
	//Line break
	$this->Ln(10);
}

function Footer()
{
	//Position at 1.5 cm from bottom
	$this->SetY(-15);
	//Arial italic 8
	$this->SetFont('Arial','I',8);
	//Text color in gray
	$this->SetTextColor(128);
	//Page number
	$this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}

function ChapterTitle($num,$label,$student)
{
	//Arial 12
	$this->SetFont('Arial','B',10);
	//Background color
	//$this->SetFillColor(200,220,255);
	//Title
	$this->Cell(0,6,"$label",0,0,'L',0);
	//Line break
	$this->Ln(6);
    $this->Cell(4,6,"C/O Polytechnic",0,0,'L',0);
    $this->Ln(8);
    $this->SetFont('Arial','',12);
    $this->Cell(15,6,"Dear $student",0,0,'L',0);
    $this->Ln(6);
}


function Heading($head,$academic_yr)
{
	
	//Arial bold 15
	$this->SetFont('Arial','B',12);
	//Calculate width of title and position
	$w=$this->GetStringWidth($head)+6;
	$this->SetX((210-$w)/2);
	//Colors of frame, background and text
	//$this->SetDrawColor(0,80,180);
	//$this->SetFillColor(230,230,0);
	//$this->SetTextColor(220,50,50);
	//Thickness of frame (1 mm)
	$this->SetLineWidth(1);
	//Title
	$this->Cell($w,9,"$head $academic_yr",0,0,'C',0);
	//Line break
	$this->Ln(10);
}




function ChapterBody($file)
{
	//Read text file
	$f=fopen($file,'r');
	$txt=fread($f,filesize($file));
	fclose($f);
	//Times 12
	$this->SetFont('Times','',10);
	//Output justified text
	$this->MultiCell(0,5,$txt);
	//Line break
	$this->Ln();
	//Mention in italics
	$this->SetFont('','I');
	//$this->Cell(0,5,'(end of excerpt)');
}

function PrintChapter($num,$title,$student)
{
	
	$this->ChapterTitle($num,$title,$student);
	//$this->ChapterBody($file);
}

function arrenger($date,$status,$course)
{
	$this->SetFont('Times','',10);
	$this->Cell(37,5,"At its meeting held on ");
    $this->SetFont('Times','B',10);
    $this->Cell(37,5,"$date ");
    $this->SetFont('Times','',10);
    $this->Cell(10,5,"Senate approved the College Assessment Committee's ");
    $this->Ln(4);
    $this->Cell(44,5,"recommendation that you "); 
    $this->SetFont('Times','B',10);
    $this->Cell(11,5,"$status "); 
    $this->SetFont('Times','',10);
    $this->Cell(0,5,"your examinations and be awarded "); 
    $this->Ln(4);
    $this->Cell(3,5,"a " );
    $this->SetFont('Times','B',10);
    $this->Cell(0,5,"$course" );
    $this->SetFont('Times','',10);
    $this->Ln(6);
    
    $this->Cell(0,5,"Your marks were as follows: "); 
	$this->Ln(4);
}
function salutation()
{
	$this->SetFont('Times','',10);
    $this->Ln(6);
	$this->Cell(0,5,"You will be advised of the graduation date in due course. Please accept my  ");
    $this->Ln(6);
    $this->Cell(13,5,"sincere "); 
    $this->SetFont('Times','B',10);
    $this->Cell(0,5,"CONGRATULATIONS "); 
    $this->SetFont('Times','',10);
    
	
}

function ending($principal,$award)
{
	$this->SetFont('Times','B',10);
    $this->Ln(8);
	$this->Cell(0,5,"$principal $award");
    $this->Ln(4);
    $this->Cell(0,5,"Principal "); 
    $this->Ln(12);
    $this->SetFont('Times','',10);
    $this->Cell(0,5,"Cc:      Dean "); 
    $this->Ln(4);
    $this->Cell(0,5,"           Personal File"); 
    $this->Ln(6);
	$this->Cell(0,5,"CM/NTB"); 
}

//cha table

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
//$this->SetFillColor(255,0,0);
    //$this->SetTextColor(255);
    //$this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
//$this->SetFont('Times','',12);
$this->SetFont('','');

    //Header
    //foreach($header as $col)
        //$this->Cell(30,7,$header[0],1);
        $this->SetFont('Times','B',10);
        $this->Cell(60,7,$header[1],1);
        $this->Cell(70,7,$header[2],1);
        $this->Cell(30,7,$header[3],1);
        $this->SetFont('Times','',12);
    $this->Ln();
    //Data
    
    foreach($data as $row)
    {
    
       $this->Cell(20,7,$row[0],1);
        $this->Cell(40,7,$row[1],1);
        if($row[2]=='Average')
        {
        $this->SetFont('Times','B',10);
        $this->Cell(70,7,$row[2],1);
        $this->SetFont('Times','',10);
        }
        else
        {
        $this->Cell(70,7,$row[2],1);
        }
        $this->Cell(30,7,$row[3],1);
            
        $this->Ln();
    }
}

function address($date,$princ)
{
global $dbConn;

$this->AddPage();
$this->SetFont('Times','B',8);
       $this->Cell(40,7,'PRINCIPAL',0);
       $this->SetFont('Times','',8);
        $this->Cell(90,7,'',0);
        $this->Cell(50,7,'Please address all correspondence to the principal',0);
        //$this->Cell(30,7,$row[3],1);
        
        $this->Ln(4);
       
       
       $sql="select CollegeName,UniversityName,AddressLine2,AddressLIne3,AddressLIne4,AddressLIne5
from college";
    $result=$dbConn->query($sql);
     
     $sqlObj=$result->fetchRow(DB_FETCHMODE_OBJECT);
    
     //$val[1]=$gradeObj->EOSAverage_College;
       
       
       
       $this->Cell(40,7,"$princ",0);
       $this->SetFont('Times','B',10);
        $this->Cell(90,7,'',0);
        $this->Cell(50,7, $sqlObj->CollegeName,0);
        //$this->Cell(30,7,$row[3],1);
        
        $this->Ln(4);
        
       $this->SetFont('Times','B',10);
        $this->Cell(130,7,'',0);
        $this->Cell(50,7,$sqlObj->AddressLine2,0);
        $this->Ln(4);
        
        
        $this->SetFont('Times','',8);
         $this->Cell(35,7,"Our Ref :",0);
         $this->SetFont('Times','B',10);
         $this->Cell(5,7,"PF",0);
       $this->SetFont('Times','B',10);
        $this->Cell(90,7,'',0);
        $this->Cell(50,7,$sqlObj->AddressLIne3,0);
        $this->Ln(4);
        
        $this->SetFont('Times','',8);
        $this->Cell(40,7,"Your Ref:",0);
        $this->SetFont('Times','B',10);
         $this->Cell(90,7,'',0);
        $this->Cell(50,7,$sqlObj->AddressLIne4,0);
        
        $this->Ln(4);
        $this->SetFont('Times','B',10);
        $this->Cell(130,7,'',0);
        $this->Cell(50,7,$sqlObj->AddressLIne5,0);
        $this->Ln(8);
        
        $this->SetFont('Times','',8);
        $this->Cell(15,7,"Date :",0);
        $this->SetFont('Times','',10);
        $this->Cell(115,7," $date",0);
        $this->SetFont('Times','',8);
        $this->Cell(50,7,'Tel: (265) 1 870 411',0);
        
         $this->Ln(4);
        $this->SetFont('Times','',8);
        $this->Cell(130,7,'',0);
        $this->Cell(50,7,'Fax: (265) 1 870 578',0);
        $this->Ln(4);
        
        $this->SetFont('Times','',8);
        $this->Cell(130,7,'',0);
        $this->Cell(50,7,'E-Mail: principal@poly.ac.mw',0);
        $this->Ln(4);
        $this->Ln(8);
}



}





$principal='Charles Mataya';
$princ='Charles Mataya, Ph.D., MS Ag Econ, Bsc. Agric, Dip. Econ';
$award = 'PhD';
$student= 'Ms Matavata';
$pdf=new PDF();
$title='UNIVERSITY OF MALAWI';
$head = 'END OF YEAR EXAMINATION RESULTS';
$academic_yr='2005/2006';
$date=date(' d \of F Y');
$status='PASS';
$course='BACHELOR OF BUSINESS ADMINISTRATION';

$pdf->SetTitle($title);

//$pdf->SetAuthor('Jules Verne');
$pdf->address($date,$princ);
$pdf->PrintChapter(1,'Felizarda Madalitso Matavata - BBA4',$student);

$pdf->Heading($head,$academic_yr);
$pdf->arrenger($date,$status,$course);


$header=array('NUMBER','COURSE ID','COURSE NAME','GRADE');
//Data loading


$pdf->getCourseName();
$data=$pdf->LoadData('cha.txt');

//etCourseName();
//$data=$pdf->LoadData($data);
//$pdf->SetFont('Arial','',14);
//$pdf->AddPage();
$pdf->BasicTable($header,$data);



$pdf->salutation();
$pdf->ending($principal,$award);
//$pdf->PrintChapter(2,'THE PROS AND CONS','20k_c2.txt');

$pdf->Output();
?>
