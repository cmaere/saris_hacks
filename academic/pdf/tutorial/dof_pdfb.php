<?php
require('../fpdf.php');
//require('button.php');

class PDF extends FPDF
{

// retrieve name function
function name($regno,$fill)
{

    $sql = "select Name, Sex, yr_repeated, UPPER(RegNo) as regno from student where RegNo = '$regno'";

    $result = mysql_query($sql);
    while($row = mysql_fetch_array($result, MYSQL_ASSOC))
    {
        $name= $row['Name'];
        $sex = $row['Sex'];
        $capregno= $row['regno'];
        $history = $row['yr_repeated'];
       
        if($history == '0')
        {
            $history= ' ';
        }
         $trim =  trim($history,'(Readm), ');
        $this->Cell(35,7,$capregno,1,0,'L',$fill);
        $this->Cell(49,7,$name,1,0,'L',$fill);
        $this->Cell(8,7,$sex,1,0,'L',$fill);
        if($history =='(Sus)' || $history == '(WD)' || $history == 'CP')
        {
            $this->Cell(12,7,'',1,0,'R',$fill);
    
        }
        else if($trim == 'DF')
        {
            $trim2 =  trim($history,', DF');
            $this->Cell(12,7,$trim2,1,0,'L',$fill);
        }
        else
        {
            $this->Cell(12,7,$history,1,0,'L',$fill);
        }

        
    }


}
function header()
{
global $header,$year,$program,$semister;
if ($semister == "Semester II")
{
    $cat = 5;
}
else
{
    $cat = 4;
}
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
    
    $this->Cell($w,9,"FACULTY ASSESSMENT EXAM RESULTS ".$year,0,0,'C',0);
    $this->Ln(13);
	$this->SetLineWidth(1);
	//Title
    
	  if($semister == 'Semester II')
    {
        if($program == 'Bachelor of Science in Nursing and Midwifery (Generic) Yr1')
        {
        $prog = trim($program, 'Yr1');
    
        $this->Cell($w,9,$prog.' Year 1',0,0,'C',0);
        }
        else if($program == 'Bachelor of Science in Nursing and Midwifery (Generic) Yr2')
        {
            $prog = trim($program, 'Yr2');
    
            $this->Cell($w,9,$prog.' Year 2',0,0,'C',0);
        }
        else if($program == 'Bachelor of Science in Nursing and Midwifery (Generic) Yr3')
        {
            $prog = trim($program, 'Yr3');
    
            $this->Cell($w,9,$prog.' Year 3',0,0,'C',0);
        }
         else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr1')
        {
            $prog = trim($program, 'Yr1');
    
            $this->Cell($w,9,$prog.' Year 1',0,0,'C',0);
        }
        
        
    }
    else
    {
	    $this->Cell($w,9,$program.' '.$semister,0,0,'C',0);
    }
	//Line break
	$this->Ln(10);

 
 //header
  $this->SetFillColor(57,127,145);
    $this->SetTextColor(255);
    $this->SetDrawColor(57,127,145);
    $this->SetLineWidth(.3);
    $this->SetFont('','B',7);
 
 


  

//Header
 $this->Ln(7);
  $this->Ln(7);
   
    $w=array(6,35,49,8,12);
   for($i=0;$i<count($header);$i++)
   {
        $this->Cell($w[$i],7,$header[$i],1,0,'C',1);
    }
    if($semister != "Semester I")
	{
    $sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc ";
	}
	else
	{
		
	$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.programme = '$program' order by e.CourseCode asc ";	
		
	}
	//die($sqli);
        $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            //$cat= $rowb['assessment_status'];
            
            $this->Cell(15,7,$course,1,0,'C',1);
        }
 $this->Cell(8,7,'AVG',1,0,'C',1);

        $this->Cell(16,7,'RECOMM',1,0,'C',1);
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',8);
     $this->Ln(7);
    
} 

//Colored table
function FancyTable($year,$program,$semister)
{


$sqlp = "select ProgrammeCode from program_year where ProgrammeName = '$program'";
           
            $resultp = mysql_query($sqlp);
            
            while($rowp = mysql_fetch_array($resultp, MYSQL_ASSOC))
            {
                 $progcode = $rowp['ProgrammeCode'];
            }
            
            //die($sqlp);

if ($semister == "Semester II")
{
    $cat = 5;
}
else
{
    $cat = 4;
}
    //Colors, line width and bold font
 
    //Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',8);
    //Data
    $fill=0;
require_once('../../../Connections/sessioncontrol.php');
require_once('../../../Connections/zalongwa.php');

 if($semister != "Semester I")
	{
	$sql2 = "select distinct er.RegNo from examregister er, examdate e where er.CourseCode = e.CourseCode and e.programme = '$program' and er.Ayear = $year and e.assessment_status = $cat and er.RegNo like '%/%/%' order by er.RegNo asc ";
	}
	else
	{
		$sql2 = "select distinct er.RegNo from examregister er, examdate e where er.CourseCode = e.CourseCode and e.programme = '$program' and er.Ayear = $year  and er.RegNo like '%/%/%' order by er.RegNo asc ";	
	}
    $result2 = mysql_query($sql2);
    $count = 1;
   $badseed = 0;
   $tracker2 = 0;
	$count2rep = 0;
  // die($sql2);
    while($row = mysql_fetch_array($result2, MYSQL_ASSOC))
    {
        $regno= $row['RegNo'];
        
        if($count==3)
        {
        
         //die($regno);
        }
        
        $sql4b = "select UPPER(ex.RegNo) as RegNo, e.CourseCode, ex.ExamScore from examresult ex, examdate e where ex.CourseCode  = e.CourseCode and e.programme = '$program' and ex.RegNo = '$regno' and ex.AYear = $year ";
            
            $result4b = mysql_query($sql4b);
            $sqlrowsb = mysql_num_rows($result4b);
            //die($sql4b);
        if($sqlrowsb !=0)
        {
            $this->Cell(6,7,number_format($count),1,0,'R',$fill); 
          
                    $this->name($regno,$fill);
               
        } 
        else
        {
            $this->Cell(6,7,number_format($count),1,0,'R',$fill); 
          
                    $this->name($regno,$fill);
            $badseed = 1;
            //$count = $count - 1;
        }
        $tracker = 0;
        
        //die($regno);
        //echo "$regno <br>";
//generation of courses for a specific programme
if($semister != "Semester I")
	{
        $sql3 = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc";
        
	}
	else
	{
		$sql3 = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year  and e.programme = '$program' order by e.CourseCode asc";	
		
	}
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
            
             $sql = "select Name, Sex, yr_repeated,UPPER(RegNo) as regno from student where RegNo = '$regno'";

    $result = mysql_query($sql);
    while($row = mysql_fetch_array($result, MYSQL_ASSOC))
    {
        
        $sex = $row['Sex'];
        $hist = $row['yr_repeated'];
    }
    $trim4 =  trim($hist,'(R1), ');
    $trim5 =  trim($hist,'(LW), ');
    $trim6 =  trim($hist,'(SW), ');
     $trim2 =  trim($hist,'DF, ');
      $trim3 =  trim($hist,'(Readm), DF, ');
            if($sqlrows == 0)
                {
                
                $this->Cell(15,7,'--',1,0,'R',$fill);
                
                }
            while($rowc = mysql_fetch_array($result4, MYSQL_ASSOC))
            {
                
            $regnob = $rowc['RegNo'];
                
               
                $courseb = $rowc['CourseCode'];
               
                $examscore = $rowc['ExamScore'];
                if($hist == 'NP' || $trim2 == 'NP' || $trim3 == 'NP' || $trim4 == 'NP' || $trim5 == 'NP' || $trim6 == 'NP')
                {
                
                    $this->Cell(15,7,'--',1,0,'R',$fill);  
                }
                else if($examscore == 0 )
                { 
                    $this->Cell(15,7,'--',1,0,'R',$fill);  
                }
                else if($examscore < 50)
                {
                        $this->SetFont('','B',9);
                        
                    $this->Cell(15,7,number_format($examscore),1,0,'R',$fill); 
                    $this->SetFont('','',9);                    
                }
                else
                {
                
                    $this->Cell(15,7,number_format($examscore),1,0,'R',$fill); 
                
                }
                
                $tracker = 1;
                
               
                
                
            }
            
            
           
                   
                    
                    //$this->Cell(30,7,number_format($avg),1,0,'R',$fill);
                    
                    
                    
        }
		if($semister != "Semester I")
	{
        
        $sqlavg = "select AVG(ex.ExamScore) as avg from examresult ex, examdate e where ex.CourseCode  = e.CourseCode and e.programme = '$program' and ex.RegNo = '$regno' and ex.AYear = $year  and ex.ExamCategory = $cat and e.assessment_status	 = $cat";
	}
	else
	{
		$sqlavg = "select AVG(ex.ExamScore) as avg from examresult ex, examdate e where ex.CourseCode  = e.CourseCode and e.programme = '$program' and ex.RegNo = '$regno' and ex.AYear = $year  ";
		
	}
            //die($sql3);
            //die($sqlavg);
            $resultavg = mysql_query($sqlavg);
            
            while($rowavg = mysql_fetch_array($resultavg, MYSQL_ASSOC))
            {
                 $avg = $rowavg['avg'];
            }
            
             $sql = "select Name, Sex, yr_repeated,UPPER(RegNo) as regno from student where RegNo = '$regno'";

    $result = mysql_query($sql);
    while($row = mysql_fetch_array($result, MYSQL_ASSOC))
    {
        
        $sex = $row['Sex'];
        $hist = $row['yr_repeated'];
    }
        //$trim4 =  trim($hist,'(R1), ');
         //$trim5 =  trim($hist,'(LW), ');
        //$trim6 =  trim($hist,'(SW), ');
      $trim =  trim($hist,'(Readm), ');
      //$trim2 =  trim($hist,'DF, ');
      //$trim3 =  trim($hist,'(Readm), DF, ');
      
    if($hist =='(Sus)' || $trim == 'DF' || $hist == 'DF' || $hist == '(WD)' || $hist == 'NP' || $trim2 == 'NP' || $trim3 == 'NP' || $trim4 == 'NP' || $trim5 == 'NP' || $trim6 == 'NP' )
    {
        $this->Cell(8,7,'',1,0,'R',$fill);
    
    }
    else if ($avg > 69)
		{
		$this->SetFont('','B',9);
                        
                                                 
            $this->Cell(8,7,number_format($avg),1,0,'R',$fill);
		$this->SetFont('','',9);
		}
		else
		{
		 $this->Cell(8,7,number_format($avg),1,0,'R',$fill);

		}   
		if($semister != "Semester I")
	{
         $sqlmin2 = "select MIN(ExamScore) as year4 from examresult 
where AYear = $year and RegNo = '$regno' and ExamCategory = $cat  ";
	}
	else
	{
		 $sqlmin2 = "select MIN(ExamScore) as year4 from examresult 
where AYear = $year and RegNo = '$regno'   ";
		
	}
        $resultmin2 = mysql_query($sqlmin2);
            
            while($rowmin2 = mysql_fetch_array($resultmin2, MYSQL_ASSOC))
            {
                 $lowestmark = $rowmin2['year4'];
                 
            }
            if($semister != "Semester I")
	{
        $sqlmin2 = "select COUNT(ExamScore) as rep from examresult 
where AYear = $year and RegNo = '$regno' and ExamCategory = $cat  and ExamScore < 50 ";
	}
	else
	{
		$sqlmin2 = "select COUNT(ExamScore) as rep from examresult 
where AYear = $year and RegNo = '$regno'   and ExamScore < 50 ";	
	}
        $resultmin2 = mysql_query($sqlmin2);
            
            while($rowmin2 = mysql_fetch_array($resultmin2, MYSQL_ASSOC))
            {
                 $countref = $rowmin2['rep'];
                 
            }    
        
      
       
        if($hist == 'NP' || $trim2 == 'NP' || $trim3 == 'NP' || $trim4 == 'NP' || $trim5 == 'NP' || $trim6 == 'NP')
        {
            $this->Cell(16,7,'WH',1,0,'R',$fill);
            if($sex == 'F')
              {
                $countwh +=1;
              }
              else
              {
                $countwhm +=1;
              
              }
        }
        else if($hist =='(Sus)')
        {
            $this->Cell(16,7,'SUS',1,0,'R',$fill);
             if($sex == 'F')
              {
                $countsus +=1;
              }
              else
              {
                $countsusm +=1;
              
              }
    
        }
       
        if($hist == '(WD)')
        {
            $this->Cell(16,7,'WD',1,0,'R',$fill);
             if($sex == 'F')
              {
                $countwd +=1;
              }
              else
              {
                $countwdm +=1;
              
              }
        
        }
        else if($hist =='DF' || $trim == 'DF')
        {
            $this->Cell(16,7,'DF',1,0,'R',$fill);
             if($sex == 'F')
              {
                $countdf +=1;
              }
              else
              {
                $countdfm +=1;
              
              }
    
        }
         else if($hist =='CP')
        {
            $this->Cell(16,7,'CP',1,0,'R',$fill);
             if($sex == 'F')
              {
                $countcp +=1;
              }
              else
              {
                $countcpm +=1;
              
              }
    
        }
        
        else if($lowestmark  > 49.4)
        {
            $this->Cell(16,7,'PP',1,0,'R',$fill);
             if($sex == 'F')
              {
                $countpp +=1;
              }
              else
              {
                $countppm +=1;
              
              }
        }
        else if($lowestmark  < 50 && $countref < 4)
        {
            $this->Cell(16,7,'REF',1,0,'R',$fill);
            
             if($sex == 'F')
              {
                $count2ref +=1;
              }
              else
              {
                $countrefm +=1;
              
              }
        }
        else if($countref > 3)
        {
             $this->Cell(16,7,'REP',1,0,'R',$fill);
              if($sex == 'F')
              {
                $count2rep += 1;
              }
              else
              {
                $countrepm +=1;
              
              }
        }
            
            $this->Ln(7);
            $fill=!$fill;
                   
         $badseed = 0;
        $count +=1; 
	//$countrep = 0;
         
        
        

    }
   
   
$this->Ln(7);
$this->SetFont('','B',9);
//statistics

$this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(35,7,'',1,0,'L',$fill);
                        $this->Cell(49,7,'Highest Score',1,0,'L',$fill);
                         $this->Cell(8,7,'',1,0,'L',$fill);
                        $this->Cell(12,7,'',1,0,'L',$fill);
	 if($semister != "Semester I")
	{

$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc ";
	}
	else
	{
		$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.programme = '$program' order by e.CourseCode asc ";
		
		
	}
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
                       

                      $this->Cell(15,7,number_format($high),1,0,'R',$fill);
                    }
        }

$this->Ln(7);
$this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(35,7,'',1,0,'L',$fill);
                        $this->Cell(49,7,'Lowest Score',1,0,'L',$fill);
                         $this->Cell(8,7,'',1,0,'L',$fill);
                        $this->Cell(12,7,'',1,0,'L',$fill);
 if($semister != "Semester I")
	{
$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc ";
	}
	else
	{
	$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year  and e.programme = '$program' order by e.CourseCode asc ";	
		
	}
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
                       

                      $this->Cell(15,7,number_format($low),1,0,'R',$fill);
                    }
        }
        $this->Ln(7);
                        $this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(35,7,'',1,0,'L',$fill);
                        $this->Cell(49,7,'Average Score',1,0,'L',$fill);
                         $this->Cell(8,7,'',1,0,'L',$fill);
                        $this->Cell(15,7,'',1,0,'L',$fill);
	if($semister != "Semester I")
	{
$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc ";
	}
	else
	{
		$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year  and e.programme = '$program' order by e.CourseCode asc ";
	}
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
                       

                      $this->Cell(15,7,number_format($avg),1,0,'R',$fill);
                    }
        }

            //die($sql3);
            //die($sql4);
            
//statistics
$this->Ln(7);
$this->Ln(7);
$this->Ln(7);
                         $this->Cell(50,7,'',1,0,'L',$fill);
                        $this->Cell(25,7,'No. of Stud',1,0,'L',$fill);
                        $this->Cell(18,7,'PP',1,0,'L',$fill);
                        $this->Cell(18,7,'DF',1,0,'L',$fill);
                         $this->Cell(18,7,'REF',1,0,'L',$fill);
                        $this->Cell(18,7,'REP',1,0,'L',$fill);
                        $this->Cell(18,7,'WD',1,0,'L',$fill);
                        $this->Cell(18,7,'FW',1,0,'L',$fill);
                         $this->Cell(18,7,'SUS',1,0,'L',$fill);
                        $this->Cell(18,7,'DM',1,0,'L',$fill);
                        $this->Cell(18,7,'CP',1,0,'L',$fill);
                        $this->Cell(18,7,'WH',1,0,'L',$fill);
                         $this->Cell(18,7,'INC',1,0,'L',$fill);
                        
                        
                    $sql4f = "select count(distinct examregister.RegNo) as female from course c, examregister
INNER JOIN student ON (examregister.RegNo = student.RegNo)
where examregister.CourseCode= c.CourseCode and examregister.AYear = $year
and c.Programme = $progcode and examregister.RegNo LIKE '%/%/%' and student.Sex = 'F'";
//die($sql4f);
                   $result4f = mysql_query($sql4f) or die(mysql_error());
                    while($rowcst4 = mysql_fetch_array($result4f, MYSQL_ASSOC))
                    {
                       $female = $rowcst4['female'];
                    }
                    
                    
                       
                        
$this->Ln(7);
                        $this->Cell(50,7,'FEMALE',1,0,'L',$fill);
                        $this->Cell(25,7,number_format($female),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countpp),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countdf),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($count2ref),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($count2rep),1,0,'L',$fill);
                        
                        $this->Cell(18,7,number_format($countwd),1,0,'L',$fill);
                        $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countsus),1,0,'L',$fill);
                         $this->Cell(18,7,'0',1,0,'L',$fill);
                      
                         $this->Cell(18,7,number_format($countcp),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countwh),1,0,'L',$fill);
                          $this->Cell(18,7,number_format($countinc),1,0,'L',$fill);
                        
$sql4f = "select count(distinct examregister.RegNo) as male from course c, examregister
INNER JOIN student ON (examregister.RegNo = student.RegNo)
where examregister.CourseCode= c.CourseCode and examregister.AYear = $year
and c.Programme = $progcode and examregister.RegNo LIKE '%/%/%' and student.Sex = 'M'";
//die($sql4f);
                   $result4f = mysql_query($sql4f) or die(mysql_error());
                    while($rowcst4 = mysql_fetch_array($result4f, MYSQL_ASSOC))
                    {
                       $male = $rowcst4['male'];
                    }
                    
                    
                       
                        
$this->Ln(7);
                        $this->Cell(50,7,'MALE',1,0,'L',$fill);
                        $this->Cell(25,7,number_format($male),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countppm),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countdfm),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countrefm),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countrepm),1,0,'L',$fill);
                        
                        $this->Cell(18,7,number_format($countwdm),1,0,'L',$fill);
                        $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countsusm),1,0,'L',$fill);
                         $this->Cell(18,7,'0',1,0,'L',$fill);
                      
                        $this->Cell(18,7,number_format($countcpm),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countwhm),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countinc),1,0,'L',$fill);
//$this->AddPage('L');
$sql4f = "select count(distinct examregister.RegNo) as total from course c, examregister
INNER JOIN student ON (examregister.RegNo = student.RegNo)
where examregister.CourseCode= c.CourseCode and examregister.AYear = $year
and c.Programme = $progcode and examregister.RegNo LIKE '%/%/%' ";
//die($sql4f);
                   $result4f = mysql_query($sql4f) or die(mysql_error());
                    while($rowcst4 = mysql_fetch_array($result4f, MYSQL_ASSOC))
                    {
                       $total = $rowcst4['total'];
                    }
                    
                    
                       
                        
$this->Ln(7);
                        $this->Cell(50,7,'TOTAL',1,0,'L',$fill);
                        $this->Cell(25,7,number_format($total),1,0,'L',$fill);
                        $this->Cell(18,7,$countppm + $countpp,1,0,'L',$fill);
                        $this->Cell(18,7,$countdfm + $countdf,1,0,'L',$fill);
                         $this->Cell(18,7,$countrefm + $count2ref,1,0,'L',$fill);
                        $this->Cell(18,7,$countrepm + $count2rep,1,0,'L',$fill);
                        
                        $this->Cell(18,7,$countwdm + $countwd,1,0,'L',$fill);
                        $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,$countsusm + $countsus,1,0,'L',$fill);
                         $this->Cell(18,7,'0',1,0,'L',$fill);
                       
                        $this->Cell(18,7,$countcpm + $countcp,1,0,'L',$fill);
                        $this->Cell(18,7,$countwhm + $countwh,1,0,'L',$fill);
                        $this->Cell(18,7,$countincm + $countinc,1,0,'L',$fill);

}

function Footer()
{
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $pagenum = $this->PageNo();
    $num = $pagenum + 1;
    $this->Cell(0,10,date(d.'-'.m.'-'.Y).'  This Report was generated from SARIS developed by ICT Department KCN',0,0,'C');
    $this->Ln(5);
    $this->Cell(0,10,'Page '.$num.'',0,0,'C');
}


}
$pdf=new PDF();
//Column titles

$year = $_GET["year"];
$program = $_GET["program"];

$semister = $_GET["semister"];

//die("$yr,$class,$code");
$header=array('SN','REG NO.', 'NAME OF STUDENT','SEX', 'HISTORY');
//Data loading
//$data=$pdf->LoadData('pdf/tutorial/countries.txt');
$pdf->SetFont('Arial','',9);
$pdf->AddPage('L');

$pdf->FancyTable($year,$program,$semister);
//$pdf->LoadData();
$pdf->Output();
?>
