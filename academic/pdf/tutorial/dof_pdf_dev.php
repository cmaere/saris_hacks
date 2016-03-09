<?php
require('../fpdf.php');
//require('button.php');

class PDF extends FPDF
{

// retrieve name function
function name($regno,$fill)
{

    $sql = "select Name, Sex, yr_repeated from student where RegNo = '$regno'";

    $result = mysql_query($sql);
    while($row = mysql_fetch_array($result, MYSQL_ASSOC))
    {
        $name= $row['Name'];
        $sex = $row['Sex'];
        $history = $row['yr_repeated'];
       
        if($history == '0')
        {
            $history= ' ';
        }
        $this->Cell(40,7,$regno,1,0,'L',$fill);
        $this->Cell(55,7,$name,1,0,'L',$fill);
        $this->Cell(10,7,$sex,1,0,'L',$fill);
        $this->Cell(18,7,$history,1,0,'L',$fill);

        
    }


}
function header()
{
global $header,$year,$program,$semister;

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
    $this->Cell($w,9,"FACULTY ASSESSMENT EXAM RESULTS ",0,0,'C',0);
    $this->Ln(13);
	$this->SetLineWidth(1);
	//Title
    
	$this->Cell($w,9,$program.' '.$semister,0,0,'C',0);
	//Line break
	$this->Ln(10);

 
 //header
  $this->SetFillColor(57,127,145);
    $this->SetTextColor(255);
    $this->SetDrawColor(57,127,145);
    $this->SetLineWidth(.3);
    $this->SetFont('','B',9);
 
 


  

//Header
 $this->Ln(7);
  $this->Ln(7);
   
    $w=array(6,40,55,10,18);
   for($i=0;$i<count($header);$i++)
   {
        $this->Cell($w[$i],7,$header[$i],1,0,'C',1);
    }
    
    $sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.Semister = '$semister' and e.programme = '$program' order by e.CourseCode asc ";
        $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            //$cat= $rowb['assessment_status'];
            
            $this->Cell(20,7,$course,1,0,'C',1);
        }
        $this->Cell(30,7,'RECOMM',1,0,'C',1);
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',9);
     $this->Ln(7);
    
} 

//Colored table
function FancyTable($year,$program,$semister)
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
   $badseed = 0;
   $tracker2 = 0;
    while($row = mysql_fetch_array($result2, MYSQL_ASSOC))
    {
        $regno= $row['RegNo'];
        
        $sql4b = "select UPPER(ex.RegNo) as RegNo, e.CourseCode, ex.ExamScore from examresult ex, examdate e where ex.CourseCode  = e.CourseCode and e.programme = '$program' and ex.RegNo = '$regno' and ex.AYear = $year ";
            
            $result4b = mysql_query($sql4b);
            $sqlrowsb = mysql_num_rows($result4b);
        if($sqlrowsb !=0)
        {
            $this->Cell(6,7,number_format($count),1,0,'R',$fill); 
          
                    $this->name($regno,$fill);
               
        } 
        else
        {
            $badseed = 1;
            $count = $count - 1;
        }
        $tracker = 0;
        
        //die($regno);
        //echo "$regno <br>";
//generation of courses for a specific programme
        $sql3 = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.Semister = '$semister' and e.programme = '$program' order by e.CourseCode asc";
        $result3 = mysql_query($sql3);
        
        while($rowb = mysql_fetch_array($result3, MYSQL_ASSOC))
        {
            $course= $rowb['CourseCode'];
            $cat= $rowb['assessment_status'];
            
            
            
            $sql4 = "select UPPER(RegNo) as RegNo, CourseCode, ExamScore from examresult where CourseCode = '$course' and RegNo = '$regno' and AYear = $year and ExamCategory = $cat  ";
            //die($sql3);
            //die($sql4);
            $result4 = mysql_query($sql4);
            $sqlrows = mysql_num_rows($result4);
            
            
            if($sqlrows == 0 && $badseed !=1 )
                {
                
                $this->Cell(20,7,'--',1,0,'R',$fill);
                
                }
            while($rowc = mysql_fetch_array($result4, MYSQL_ASSOC))
            {
                
            $regnob = $rowc['RegNo'];
                
               
                $courseb = $rowc['CourseCode'];
               
                $examscore = $rowc['ExamScore'];
                if($examscore == 0)
                { 
                    $this->Cell(20,7,'--',1,0,'R',$fill);  
                }
                else if($examscore < 50)
                {
                        $this->SetFont('','B',9);
                        
                    $this->Cell(20,7,number_format($examscore),1,0,'R',$fill); 
                    $this->SetFont('','',9);                    
                }
                else
                {
                
                    $this->Cell(20,7,number_format($examscore),1,0,'R',$fill); 
                
                }
                
                $tracker = 1;
                
               
                
                
            }
            
            
           
                   
                    
                    //$this->Cell(30,7,number_format($avg),1,0,'R',$fill);
                    
                    
                    
        }
        if($badseed !=1)
        {
            $this->Cell(30,7,'',1,0,'R',$fill);
            $this->Ln(7);
            $fill=!$fill;
        }            
         $badseed = 0;
        $count +=1; 
         
        
        

    }
   
$this->Ln(7);
$this->SetFont('','B',9);
//statistics

$this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(40,7,'',1,0,'L',$fill);
                        $this->Cell(55,7,'Highest Score',1,0,'L',$fill);
                         $this->Cell(10,7,'',1,0,'L',$fill);
                        $this->Cell(18,7,'',1,0,'L',$fill);
$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.Semister = '$semister' and e.programme = '$program' order by e.CourseCode asc ";
        $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            $cat= $rowi['assessment_status'];
        
   $sql4st = "select MAX(ExamScore) as avg from examresult where CourseCode = '$course' and AYear = $year and ExamCategory = $cat  and ExamScore <> 0";
                //die($sql4st);
                   $result4st = mysql_query($sql4st) or die(mysql_error());
                   //$resul = mysql_fetch_assoc( $result4st);
                   
                    while($rowcst4 = mysql_fetch_array($result4st, MYSQL_ASSOC))
                    {
                        //$high = $rowc['high'];
                       $high= $rowcst4['avg'];
                       

                      $this->Cell(20,7,number_format($high),1,0,'R',$fill);
                    }
        }

$this->Ln(7);
$this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(40,7,'',1,0,'L',$fill);
                        $this->Cell(55,7,'Lowest Score',1,0,'L',$fill);
                         $this->Cell(10,7,'',1,0,'L',$fill);
                        $this->Cell(18,7,'',1,0,'L',$fill);
$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.Semister = '$semister' and e.programme = '$program' order by e.CourseCode asc ";
        $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            $cat= $rowi['assessment_status'];
        
   $sql4st = "select MIN(ExamScore) as avg from examresult where CourseCode = '$course' and AYear = $year and ExamCategory = $cat  and ExamScore <> 0";
                //die($sql4st);
                   $result4st = mysql_query($sql4st) or die(mysql_error());
                   //$resul = mysql_fetch_assoc( $result4st);
                   
                    while($rowcst4 = mysql_fetch_array($result4st, MYSQL_ASSOC))
                    {
                        //$high = $rowc['high'];
                       $low = $rowcst4['avg'];
                       

                      $this->Cell(20,7,number_format($low),1,0,'R',$fill);
                    }
        }
        $this->Ln(7);
                        $this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(40,7,'',1,0,'L',$fill);
                        $this->Cell(55,7,'Average Score',1,0,'L',$fill);
                         $this->Cell(10,7,'',1,0,'L',$fill);
                        $this->Cell(18,7,'',1,0,'L',$fill);
$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.Semister = '$semister' and e.programme = '$program' order by e.CourseCode asc ";
        $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            $cat= $rowi['assessment_status'];
        
   $sql4st = "select AVG(ExamScore) as avg from examresult where CourseCode = '$course' and AYear = $year and ExamCategory = $cat ";
                //die($sql4st);
                   $result4st = mysql_query($sql4st) or die(mysql_error());
                   //$resul = mysql_fetch_assoc( $result4st);
                   
                    while($rowcst4 = mysql_fetch_array($result4st, MYSQL_ASSOC))
                    {
                        //$high = $rowc['high'];
                       $avg = $rowcst4['avg'];
                       

                      $this->Cell(20,7,number_format($avg),1,0,'R',$fill);
                    }
        }

            //die($sql3);
            //die($sql4);
            


    //$exam= $line["ExamScore"];
    
 
    
//$this->AddPage('L');

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

//$year = $_GET["year"];
$year = 2012;
//$program = $_GET["program"];

$semister = $_GET["semister"];

$sqli = "SELECT distinct `programme`,`Semister` FROM `examdate` WHERE `Ayear` = 2012 and `Semister` = "Semester I" order by `programme` asc ";
        $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $program= $rowi['programme'];
            $semister= $rowi['Semister'];

//die("$yr,$class,$code");
$header=array('SN','REG NO.', 'NAME OF STUDENT','SEX', 'HISTORY');
//Data loading
//$data=$pdf->LoadData('pdf/tutorial/countries.txt');
$pdf->SetFont('Arial','',9);
$pdf->AddPage('L');

$pdf->FancyTable($year,$program,$semister);
//$pdf->LoadData();
		}
$pdf->Output();
?>