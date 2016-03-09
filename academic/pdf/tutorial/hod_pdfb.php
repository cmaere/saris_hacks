<?php
require('../fpdf.php');
//require('button.php');

class PDF extends FPDF
{

// retrieve name function
function name($regno,$fill)
{

    $sql = "select Name, Sex from student where RegNo = '$regno'";

    $result = mysql_query($sql);
    while($row = mysql_fetch_array($result, MYSQL_ASSOC))
    {
        $name= $row['Name'];
        $sex= $row['Sex'];
        $this->Cell(45,7,$regno,1,0,'L',$fill);
        $this->Cell(60,7,$name,1,0,'L',$fill);
        $this->Cell(10,7,$sex,1,0,'L',$fill);

        
    }


}
function header()
{
global $header,$year,$program,$semister,$deptid;

require_once('../../../Connections/sessioncontrol.php');
require_once('../../../Connections/zalongwa.php');
$this->Ln(4);
	$this->SetFont('','B',13);
	//Calculate width of title and position
	$w=$this->GetStringWidth($program)+120;
	$this->SetX((210-$w)/2);
	//Colors of frame, background and text
	//$this->SetDrawColor(0,80,180);
	//$this->SetFillColor(230,230,0);
	//$this->SetTextColor(220,50,50);
	//Thickness of frame (1 mm)
    $this->Ln(7);
    $this->Cell($w,9,"KAMUZU COLLEGE OF NURSING",0,0,'C',0);
    $this->Ln(7);
    $this->Cell($w,9,"DEPARTMENTAL ASSESSMENT EXAM RESULTS ",0,0,'C',0);
    $this->Ln(13);
	$this->SetLineWidth(1);
	//Title
    
	$this->Cell($w,9,$program.' '.$semister,0,0,'C',0);
	//Line break
	$this->Ln(10);
$sql = "select DeptName from department where DeptID = $deptid";
$result = mysql_query($sql);
        while($row = mysql_fetch_array($result, MYSQL_ASSOC))
        {
            $dept= $row['DeptName'];
            //$cat= $rowb['assessment_status'];
            
            $this->Cell($w,9,$dept,0,0,'C',0);
        }
 
 //header
  $this->SetFillColor(57,127,145);
    $this->SetTextColor(255);
    $this->SetDrawColor(57,127,145);
    $this->SetLineWidth(.3);
    $this->SetFont('','B',9);
 
 


  

//Header
 $this->Ln(7);
  $this->Ln(7);
   
    $w=array(6,45,60,10);
   for($i=0;$i<count($header);$i++)
   {
        $this->Cell($w[$i],7,$header[$i],1,0,'C',1);
    }
    $count1 = 0;
    $sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and s.dept = $deptid and e.Ayear = $year and e.Semister = '$semister' and e.programme = '$program'";
        $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            
            //$cat= $rowb['assessment_status'];
            
            $this->Cell(20,7,$course,1,0,'C',1);
        }
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',9);
     $this->Ln(7);
    
} 

//Colored table
function FancyTable($year,$program,$deptid,$semister)
{


    //Colors, line width and bold font
 
    //Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',9);
    //Data
    $fill=0;
require_once('../../../Connections/sessioncontrol.php');
require_once('../../../Connections/zalongwa.php');


$sql2 = "select distinct er.RegNo from examregister er, examdate e where er.CourseCode = e.CourseCode and e.programme = '$program' and er.Ayear = $year and er.Semester = '$semister' and er.RegNo like '%/%' order by er.RegNo asc ";
    $result2 = mysql_query($sql2);
    $count = 1;
   
    while($row = mysql_fetch_array($result2, MYSQL_ASSOC))
    {
        $regno= $row['RegNo'];
        $this->Cell(6,7,number_format($count),1,0,'R',$fill);   
        $tracker = 0;
        //die($regno);
        //echo "$regno <br>";
//generation of courses for a specific programme
        $sql3 = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and s.dept = $deptid and e.Ayear = $year and e.Semister = '$semister' and e.programme = '$program'";
        $result3 = mysql_query($sql3);
        
        while($rowb = mysql_fetch_array($result3, MYSQL_ASSOC))
        {
            $course= $rowb['CourseCode'];
            $cat= $rowb['assessment_status'];
            
            $counta +=1;
            
            $sql4 = "select UPPER(RegNo) as RegNo, CourseCode, ExamScore from examresult where CourseCode = '$course' and RegNo = '$regno' and AYear = $year and ExamCategory = $cat  ";
            //die($sql3);
            //die($sql4);
            $result4 = mysql_query($sql4);
            
            while($rowc = mysql_fetch_array($result4, MYSQL_ASSOC))
            {
            $regnob = $rowc['RegNo'];
                if($tracker == 0)
                {
                    $this->name($regnob,$fill);
                
                }
               
                $courseb = $rowc['CourseCode'];
                $examscore = $rowc['ExamScore'];
                 
                $this->Cell(20,7,number_format($examscore),1,0,'R',$fill);   
                
                $tracker = 1;
                
            }
             $sqlavg = "select AVG(ExamScore) as avg from examresult where  RegNo = '$regno' and AYear = $year and ExamCategory = $cat  ";
            //die($sql3);
            //die($sql4);
            $resultavg = mysql_query($sqlavg);
            
            while($rowavg = mysql_fetch_array($resultavg, MYSQL_ASSOC))
            {
                 $avg = $rowavg['avg'];
            }
            $this->Cell(20,7,'avg'.number_format($avg),1,0,'R',$fill);   
           
                   
        }
         
        $count +=1; 
        $fill=!$fill;
        $this->Ln(7);

    }
   







    //$exam= $line["ExamScore"];
    
 
    
$this->AddPage('L');

}

function Footer()
{
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $pagenum = $this->PageNo();
    $this->Cell(0,10,'Page '.$pagenum.'',0,0,'C');
}


}
$pdf=new PDF();
//Column titles

$year = $_GET["year"];
$program = $_GET["program"];
$deptid = $_GET["deptid"];
$semister = $_GET["semister"];

//die("$yr,$class,$code");
$header=array('SN','REG NO.', 'NAME OF STUDENT','SEX');
//Data loading
//$data=$pdf->LoadData('pdf/tutorial/countries.txt');
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');

$pdf->FancyTable($year,$program,$deptid,$semister);
//$pdf->LoadData();
$pdf->Output();
?>