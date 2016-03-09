<?php
require('../fpdf.php');
require('../fpdi.php');
//require('toc.php');
require_once('../../../Connections/zalongwa.php');

class PDF extends FPDI
{


//table of content
var $_toc=array();
    var $_numbering=false;
	 var $_numberingb=false;
    var $_numberingFooter=false;
    var $_numPageNum=1;
	
	
    function AddPage($orientation='') {
        parent::AddPage($orientation);
        if($this->_numbering)
            $this->_numPageNum++;
		else if($this->_numberingb)
			$this->_numPageNum++;
    }

    function startPageNums($type) {
		if($type == 1)
		{
			$this->_numberingb=true;
        	$this->_numberingFooter=true;
		}
		else
		{
        	$this->_numbering=true;
        	$this->_numberingFooter=true;
		}
    }

    function stopPageNums() {
        $this->_numbering=false;
    }

    function numPageNo() {
        return $this->_numPageNum;
    }

function TOC_Entry($txt, $level=0) {
        $this->_toc[]=array('t'=>$txt, 'l'=>$level, 'PP'=>$this->numPageNo());
    }

    function insertTOC( $location=1,
                        $labelSize=14,
                        $entrySize=13,
                        $tocfont='Arial',
                        $label='Table of Contents'
                        ) {
        //make toc at end
        $this->stopPageNums();
        $this->AddPage('L');
        $tocstart=$this->page;

        $this->SetFont($tocfont, 'B', $labelSize);
        $this->Cell(0, 5, $label, 0, 1, 'C');
        $this->Ln(10);

        foreach($this->_toc as $t) {

            //Offset
            $level=$t['l'];
            if($level>0)
                $this->Cell($level*8);
            $weight='';
            if($level==0)
                $weight='B';
            $str=$t['t'];
            $this->SetFont($tocfont, $weight, $entrySize);
            $strsize=$this->GetStringWidth($str);
            $this->Cell($strsize+2, $this->FontSize+2, $str);

            //Filling dots
            $this->SetFont($tocfont, '', $entrySize);
            $PageCellSize=$this->GetStringWidth($t['PP'])+2;
            $w=$this->w-$this->lMargin-$this->rMargin-$PageCellSize-($level*8)-($strsize+2);
            $nb=$w/$this->GetStringWidth('.');
            $dots=str_repeat('.', $nb);
            $this->Cell($w, $this->FontSize+2, $dots, 0, 0, 'R');

            //Page number
            $this->Cell($PageCellSize, $this->FontSize+2, $t['PP'], 0, 1, 'R');
        }

        //grab it and move to selected location
        $n=$this->page;
        $n_toc = $n - $tocstart + 1;
        $last = array();

        //store toc pages
        for($i = $tocstart;$i <= $n;$i++)
            $last[]=$this->pages[$i];

        //move pages
        for($i=$tocstart - 1;$i>=$location-1;$i--)
            $this->pages[$i+$n_toc]=$this->pages[$i];

        //Put toc pages at insert point
        for($i = 0;$i < $n_toc;$i++)
            $this->pages[$location + $i]=$last[$i];
    }


//end table of content










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
        if($history =='(Sus)' || $history == '(WD)' || $history == '(WD/P)' || $history == 'CP')
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
if ($this->header == 1)
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

	$this->SetFont('Arial','B',13);
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
	$this->Ln(7);
    $this->Ln(7);
	$this->SetLineWidth(1);
	//Title
    
	  
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
        
        
   
	    //$this->Cell($w,9,$program.' '.$semister,0,0,'C',0);
    
	//Line break
	//$this->Ln(10);

 
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
//table of content index
//$this->TOC_Entry("Results Table", 1);

 if($semister != "Semester I")
	{
	$sql2 = "select distinct er.RegNo from examregister er, examdate e where er.CourseCode = e.CourseCode and e.programme = '$program' and er.Ayear = $year and e.assessment_status = $cat and er.RegNo like '%/%/%' order by er.RegNo asc ";
	
	/*$sql2 = "select distinct examregister.RegNo from examregister 
INNER JOIN  examdate 
ON examregister.CourseCode = examdate.CourseCode
INNER JOIN student
ON examregister.RegNo = student.RegNo
WHERE  examdate.programme = '$program' and examregister.Ayear = $year and examdate.assessment_status = $cat and examregister.RegNo like '%/%/%' order by student.Name asc";
	*/
	
	}
	else
	{
		if($program == "Bachelor of Science in Nursing (Post Basic) Yr2 PAED" )//|| $program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT" || $program == "University Certificate in Midwifery" || $program == "Bachelor of Science in Nursing (Post Basic) Yr1" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT")
		{
			$sql2=" select distinct examregister.RegNo from examregister 
INNER JOIN student 	ON examregister.RegNo = student.RegNo
WHERE examregister.AYear = '$year'  AND examregister.CourseCode = 'NSG SC 605' AND
examregister.RegNo like '%/%/%' order by student.Name asc 
        ";
			
	       /*			$sql2 = "select distinct examregister.RegNo from examregister 
		INNER JOIN examdate ON  examregister.CourseCode = examdate.CourseCode
		INNER JOIN student
		ON examregister.RegNo = student.RegNo
		where examdate.programme = '$program' and examregister.Ayear = $year and examdate.assessment_status = $cat and examregister.RegNo like '%/%/%' order by student.Name asc ";
		*/
		}
		else
		{
			$sql2 = "select distinct examregister.RegNo from examregister 
		INNER JOIN examdate 
		ON  examregister.CourseCode = examdate.CourseCode
		INNER JOIN student
		ON examregister.RegNo = student.RegNo
		where examdate.programme = '$program' and examregister.Ayear = $year and examregister.RegNo like '%/%/%' order by student.Name asc ";

			
		}
		//$sql2 = "select distinct er.RegNo from examregister er, examdate e where er.CourseCode = e.CourseCode and e.programme = '$program' and er.Ayear = $year  and er.RegNo like '%/%/%' order by er.RegNo asc ";	
		
		/*$sql2 = "select distinct examregister.RegNo from examregister 
INNER JOIN examdate 
ON  examregister.CourseCode = examdate.CourseCode
INNER JOIN student
ON examregister.RegNo = student.RegNo
where examdate.programme = '$program' and examregister.Ayear = $year  and examregister.RegNo like '%/%/%' order by student.Name asc ";
	*/	
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
      
    if($hist =='(Sus)' || $trim == 'DF' || $hist == 'DF' || $hist == '(WD)' || $hist == '(WD/P)' || $hist == 'NP' || $trim2 == 'NP' || $trim3 == 'NP' || $trim4 == 'NP' || $trim5 == 'NP' || $trim6 == 'NP' || $hist == 'INC' || $hist == 'DCD' )
    {
        $this->Cell(8,7,'',1,0,'R',$fill);
    
    }
    else if ($avg >= 69.5)
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
       
        if($hist == '(WD)' || $hist == '(WD/P)')
        {
			if($hist == '(WD/P)')
			{
            	$this->Cell(16,7,'WD/P',1,0,'R',$fill);
			}
			else
			{
				$this->Cell(16,7,'WD',1,0,'R',$fill);
			}
			
             if($sex == 'F')
              {
                $countwd +=1;
              }
              else
              {
                $countwdm +=1;
              
              }
        
        }
		else if($hist == 'INC')
        {
            $this->Cell(16,7,'INC',1,0,'R',$fill);
             if($sex == 'F')
              {
                $countinc +=1;
              }
              else
              {
                $countincm +=1;
				
              
              }
		}
		else if($hist == 'DCD')
        {
            $this->Cell(16,7,'DCD',1,0,'R',$fill);
             if($sex == 'F')
              {
                $countdcd +=1;
              }
              else
              {
                $countdcdm +=1;
				
              
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
            $this->Cell(16,7,'P',1,0,'R',$fill);
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
			if($lowestmark == '' || $lowestmark == 0)
			{
				$this->Cell(16,7,'INC',1,0,'R',$fill);
				
				 if($sex == 'F')
              {
                $countinc +=1;
              }
              else
              {
                $countincm +=1;
			  }
				
			}
			else
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
        }
        else if($countref > 3)
        {   
           if($lowestmark == '' )
			{
				$this->Cell(16,7,'INC',1,0,'R',$fill);
				
				 if($sex == 'F')
              {
                $countinc +=1;
              }
              else
              {
                $countincm +=1;
			  }
				
			}
            else
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
        }
            
            $this->Ln(7);
            $fill=!$fill;
                   
         $badseed = 0;
        $count +=1; 
	//$countrep = 0;
         
        
        

    }
   
   


$this->SetFont('','B',9);
//statistics
$this->header = 1;
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
		// total pass per module
		
		 $this->Ln(7);
                        $this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(35,7,'',1,0,'L',$fill);
                        $this->Cell(49,7,'Total Number Passed',1,0,'L',$fill);
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
        
   $sql4st = "select COUNT(ExamScore) as pass from examresult where CourseCode = '$course' and AYear = $year and ExamCategory = $cat AND ExamScore > 49  ";
                //die($sql4st);
                   $result4st = mysql_query($sql4st) or die(mysql_error());
                   //$resul = mysql_fetch_assoc( $result4st);
                   
                    while($rowcst4 = mysql_fetch_array($result4st, MYSQL_ASSOC))
                    {
                        //$high = $rowc['high'];
                       $pass = $rowcst4['pass'];
                       

                      $this->Cell(15,7,number_format($pass),1,0,'R',$fill);
                    }
        }
		
		//end pass per module


// total fail per module
		
		 $this->Ln(7);
                        $this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(35,7,'',1,0,'L',$fill);
                        $this->Cell(49,7,'Total Number Referred',1,0,'L',$fill);
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
        
   $sql4st = "select COUNT(ExamScore) as failed from examresult where CourseCode = '$course' and AYear = $year and ExamCategory = $cat AND ExamScore < 50 AND ExamScore > 0  ";
                //die($sql4st);
                   $result4st = mysql_query($sql4st) or die(mysql_error());
                   //$resul = mysql_fetch_assoc( $result4st);
                   
                    while($rowcst4 = mysql_fetch_array($result4st, MYSQL_ASSOC))
                    {
                        //$high = $rowc['high'];
                       $failed = $rowcst4['failed'];
                       

                      $this->Cell(15,7,number_format($failed),1,0,'R',$fill);
                    }
        }
		
		//end pass per module


            //die($sql3);
            //die($sql4);
            
//statistics	
$this->header = 0;

$this->Ln(7);
$this->Ln(7);
$this->Ln(7);
/*if($program == 'Bachelor of Science in Nursing and Midwifery (Generic) Yr2')
{
	$this->Ln(7);
	$this->Ln(7);
	$this->Ln(7);
}*/
$this->Cell(252,7,'SUMMARY OF RESULTS',1,0,'L',$fill);
$this->Ln(7);
                         $this->Cell(35,7,'',1,0,'L',$fill);
                        $this->Cell(25,7,'No. of Students',1,0,'L',$fill);
                        $this->Cell(16,7,'P',1,0,'L',$fill);
                        $this->Cell(16,7,'DF',1,0,'L',$fill);
                         $this->Cell(16,7,'REF',1,0,'L',$fill);
                        $this->Cell(16,7,'REP',1,0,'L',$fill);
                        $this->Cell(16,7,'WD',1,0,'L',$fill);
                        $this->Cell(16,7,'FW',1,0,'L',$fill);
                         $this->Cell(16,7,'SUS',1,0,'L',$fill);
                        $this->Cell(16,7,'DM',1,0,'L',$fill);
                        $this->Cell(16,7,'CP',1,0,'L',$fill);
                        $this->Cell(16,7,'WH',1,0,'L',$fill);
                         $this->Cell(16,7,'INC',1,0,'L',$fill);
						 $this->Cell(16,7,'DCD',1,0,'L',$fill);
  
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
                    
                    
                       
                        
						if($countwd > 0)
						{
							$totalstudentfemale = $female;	
						}
						else
						{
							$totalstudentfemale = $female;
						}
						$totalpp = $countppm + $countpp;
$this->Ln(7);
						$PercentFemalePass = round((($countpp/$total) * 100),0);
                        $this->Cell(35,7,'FEMALE',1,0,'L',$fill);
                        $this->Cell(25,7,number_format($female),1,0,'L',$fill);
                        $this->Cell(16,7,number_format($countpp),1,0,'L',$fill);
                        $this->Cell(16,7,number_format($countdf),1,0,'L',$fill);
                         $this->Cell(16,7,number_format($count2ref),1,0,'L',$fill);
                        $this->Cell(16,7,number_format($count2rep),1,0,'L',$fill);
                        
                        $this->Cell(16,7,number_format($countwd),1,0,'L',$fill);
                        $this->Cell(16,7,number_format($countfw),1,0,'L',$fill);
                         $this->Cell(16,7,number_format($countsus),1,0,'L',$fill);
                         $this->Cell(16,7,'0',1,0,'L',$fill);
                      
                         $this->Cell(16,7,number_format($countcp),1,0,'L',$fill);
                         $this->Cell(16,7,number_format($countwh),1,0,'L',$fill);
                          $this->Cell(16,7,number_format($countinc),1,0,'L',$fill);
						  $this->Cell(16,7,number_format($countdcd),1,0,'L',$fill);
                        
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
                    
                    
                       
						if($countwdm > 0)
						{
							$totalstudentmale = $male - $countwdm;	
						}
						else
						{
							$totalstudentmale = $male;
						}
                        
$this->Ln(7);
			$PercentMalePass = round((($countppm/$total) * 100),0);
                        $this->Cell(35,7,'MALE',1,0,'L',$fill);
                        $this->Cell(25,7,number_format($male),1,0,'L',$fill);
                         $this->Cell(16,7,number_format($countppm),1,0,'L',$fill);
                        $this->Cell(16,7,number_format($countdfm),1,0,'L',$fill);
                         $this->Cell(16,7,number_format($countrefm),1,0,'L',$fill);
                        $this->Cell(16,7,number_format($countrepm),1,0,'L',$fill);
                        
                        $this->Cell(16,7,number_format($countwdm),1,0,'L',$fill);
                        $this->Cell(16,7,number_format($countfwm),1,0,'L',$fill);
                         $this->Cell(16,7,number_format($countsusm),1,0,'L',$fill);
                         $this->Cell(16,7,'0',1,0,'L',$fill);
                      
                        $this->Cell(16,7,number_format($countcpm),1,0,'L',$fill);
                        $this->Cell(16,7,number_format($countwhm),1,0,'L',$fill);
                        $this->Cell(16,7,number_format($countincm),1,0,'L',$fill);
						 $this->Cell(16,7,number_format($countdcdm),1,0,'L',$fill);
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
                    
  
/*
$this->Cell(268,7,'SUMMARY OF RESULTS',1,0,'L',$fill);
$this->Ln(7);
                         $this->Cell(35,7,'',1,0,'L',$fill);
                        $this->Cell(25,7,'No. of Students',1,0,'L',$fill);
                        $this->Cell(16,7,'P',1,0,'L',$fill);
						$this->Cell(16,7,'P%',1,0,'L',$fill);
                        $this->Cell(16,7,'DF',1,0,'L',$fill);
                         $this->Cell(16,7,'REF',1,0,'L',$fill);
                        $this->Cell(16,7,'REP',1,0,'L',$fill);
                        $this->Cell(16,7,'WD',1,0,'L',$fill);
                        $this->Cell(16,7,'FW',1,0,'L',$fill);
                         $this->Cell(16,7,'SUS',1,0,'L',$fill);
                        $this->Cell(16,7,'DM',1,0,'L',$fill);
                        $this->Cell(16,7,'CP',1,0,'L',$fill);
                        $this->Cell(16,7,'WH',1,0,'L',$fill);
                         $this->Cell(16,7,'INC',1,0,'L',$fill);
						 $this->Cell(16,7,'DCD',1,0,'L',$fill);
  
 $sql4f = "select count(distinct examregister.RegNo) as total from course c, examregister
INNER JOIN student ON (examregister.RegNo = student.RegNo)
where examregister.CourseCode= c.CourseCode and examregister.AYear = $year
and c.Programme = '$progcode' and examregister.RegNo LIKE '%/%/%' ";
//die($sql4f);
                   $result4f = mysql_query($sql4f) or die(mysql_error());
                    while($rowcst4 = mysql_fetch_array($result4f, MYSQL_ASSOC))
                    {
                       $total = $rowcst4['total'];
                    }
                                     
                        
                    $sql4f = "select count(distinct examregister.RegNo) as female from course c, examregister
INNER JOIN student ON (examregister.RegNo = student.RegNo)
where examregister.CourseCode= c.CourseCode and examregister.AYear = $year
and c.Programme = '$progcode' and examregister.RegNo LIKE '%/%/%' and student.Sex = 'F'";
//die($sql4f);
                   $result4f = mysql_query($sql4f) or die(mysql_error());
                    while($rowcst4 = mysql_fetch_array($result4f, MYSQL_ASSOC))
                    {
                       $female = $rowcst4['female'];
                    }
                    
                    
                       
                        
						if($countwd > 0)
						{
							$totalstudentfemale = $female;	
						}
						else
						{
							$totalstudentfemale = $female;
						}
						$totalpp = $countppm + $countpp;
$this->Ln(7);
						$PercentFemalePass = round((($countpp/$total) * 100),0);
                        $this->Cell(35,7,'FEMALE',1,0,'L',$fill);
                        $this->Cell(25,7,number_format($female),1,0,'L',$fill);
                        $this->Cell(16,7,number_format($countpp),1,0,'L',$fill);
						$this->Cell(16,7,$PercentFemalePass ,1,0,'L',$fill);
                        $this->Cell(16,7,number_format($countdf),1,0,'L',$fill);
                         $this->Cell(16,7,number_format($count2ref),1,0,'L',$fill);
                        $this->Cell(16,7,number_format($count2rep),1,0,'L',$fill);
                        
                        $this->Cell(16,7,number_format($countwd),1,0,'L',$fill);
                        $this->Cell(16,7,'0',1,0,'L',$fill);
                         $this->Cell(16,7,number_format($countsus),1,0,'L',$fill);
                         $this->Cell(16,7,'0',1,0,'L',$fill);
                      
                         $this->Cell(16,7,number_format($countcp),1,0,'L',$fill);
                         $this->Cell(16,7,number_format($countwh),1,0,'L',$fill);
                          $this->Cell(16,7,number_format($countinc),1,0,'L',$fill);
						  $this->Cell(16,7,number_format($countdcd),1,0,'L',$fill);
                        
$sql4f = "select count(distinct examregister.RegNo) as male from course c, examregister
INNER JOIN student ON (examregister.RegNo = student.RegNo)
where examregister.CourseCode= c.CourseCode and examregister.AYear = $year
and c.Programme = '$progcode' and examregister.RegNo LIKE '%/%/%' and student.Sex = 'M'";
//die($sql4f);
                   $result4f = mysql_query($sql4f) or die(mysql_error());
                    while($rowcst4 = mysql_fetch_array($result4f, MYSQL_ASSOC))
                    {
                       $male = $rowcst4['male'];
                    }
                    
                    
                       
						if($countwdm > 0)
						{
							$totalstudentmale = $male - $countwdm;	
						}
						else
						{
							$totalstudentmale = $male;
						}
                        
$this->Ln(7);
			$PercentMalePass = round((($countppm/$total) * 100),0);
                        $this->Cell(35,7,'MALE',1,0,'L',$fill);
                        $this->Cell(25,7,number_format($male),1,0,'L',$fill);
                         $this->Cell(16,7,number_format($countppm),1,0,'L',$fill);
						 $this->Cell(16,7,$PercentMalePass ,1,0,'L',$fill);
                        $this->Cell(16,7,number_format($countdfm),1,0,'L',$fill);
                         $this->Cell(16,7,number_format($countrefm),1,0,'L',$fill);
                        $this->Cell(16,7,number_format($countrepm),1,0,'L',$fill);
                        
                        $this->Cell(16,7,number_format($countwdm),1,0,'L',$fill);
                        $this->Cell(16,7,'0',1,0,'L',$fill);
                         $this->Cell(16,7,number_format($countsusm),1,0,'L',$fill);
                         $this->Cell(16,7,'0',1,0,'L',$fill);
                      
                        $this->Cell(16,7,number_format($countcpm),1,0,'L',$fill);
                        $this->Cell(16,7,number_format($countwhm),1,0,'L',$fill);
                        $this->Cell(16,7,number_format($countincm),1,0,'L',$fill);
						 $this->Cell(16,7,number_format($countdcdm),1,0,'L',$fill);
//$this->AddPage('L');
$sql4f = "select count(distinct examregister.RegNo) as total from course c, examregister
INNER JOIN student ON (examregister.RegNo = student.RegNo)
where examregister.CourseCode= c.CourseCode and examregister.AYear = $year
and c.Programme = '$progcode' and examregister.RegNo LIKE '%/%/%' ";
//die($sql4f);
                   $result4f = mysql_query($sql4f) or die(mysql_error());
                    while($rowcst4 = mysql_fetch_array($result4f, MYSQL_ASSOC))
                    {
                       $total = $rowcst4['total'];
                    }
                    */
                    
                       
                        
$this->Ln(7);
                        $this->Cell(35,7,'TOTAL',1,0,'L',$fill);
                        $this->Cell(25,7,number_format($total),1,0,'L',$fill);
                        $this->Cell(16,7,$countppm + $countpp,1,0,'L',$fill);
                        $this->Cell(16,7,$countdfm + $countdf,1,0,'L',$fill);
                         $this->Cell(16,7,$countrefm + $count2ref,1,0,'L',$fill);
                        $this->Cell(16,7,$countrepm + $count2rep,1,0,'L',$fill);
                        
                        $this->Cell(16,7,$countwdm + $countwd,1,0,'L',$fill);
                        $this->Cell(16,7,$countfwm + $countfw,1,0,'L',$fill);
                         $this->Cell(16,7,$countsusm + $countsus,1,0,'L',$fill);
                         $this->Cell(16,7,'0',1,0,'L',$fill);
                       
                        $this->Cell(16,7,$countcpm + $countcp,1,0,'L',$fill);
                        $this->Cell(16,7,$countwhm + $countwh,1,0,'L',$fill);
                        $this->Cell(16,7,$countincm + $countinc,1,0,'L',$fill);
						 $this->Cell(16,7,$countdcdm + $countdcd,1,0,'L',$fill);
						 $this->overroll_summary($program,$total,$countppm,$countpp,$countdfm,$countdf,$countrefm,$count2ref,$countrepm,$count2rep,$countwdm,$countwd,$countsusm,$countsus,$countsusm,$countsus,$countcpm,$countcp,$countfwm,$countfw,$countwhm,$countwh,$countincm,$countinc,$countdcdm,$countdcd);
						                        
                       /*
					   $this->Cell(35,7,'TOTAL',1,0,'L',$fill);
                        $this->Cell(25,7,number_format($total),1,0,'L',$fill);
                        $this->Cell(16,7,$countppm + $countpp,1,0,'L',$fill);
						$this->Cell(16,7,$PercentFemalePass + $PercentMalePass,1,0,'L',$fill);
                        $this->Cell(16,7,$countdfm + $countdf,1,0,'L',$fill);
                         $this->Cell(16,7,$countrefm + $count2ref,1,0,'L',$fill);
                        $this->Cell(16,7,$countrepm + $count2rep,1,0,'L',$fill);
                        
                        $this->Cell(16,7,$countwdm + $countwd,1,0,'L',$fill);
                        $this->Cell(16,7,'0',1,0,'L',$fill);
                         $this->Cell(16,7,$countsusm + $countsus,1,0,'L',$fill);
                         $this->Cell(16,7,'0',1,0,'L',$fill);
                       
                        $this->Cell(16,7,$countcpm + $countcp,1,0,'L',$fill);
                        $this->Cell(16,7,$countwhm + $countwh,1,0,'L',$fill);
                        $this->Cell(16,7,$countincm + $countinc,1,0,'L',$fill);
						 $this->Cell(16,7,$countdcdm + $countdcd,1,0,'L',$fill);
						 $this->overroll_summary($program,$total,$countppm,$countpp,$countdfm,$countdf,$countrefm,$count2ref,$countrepm,$count2rep,$countwdm,$countwd,$countsusm,$countsus,$countsusm,$countsus,$countcpm,$countcp,$countwhm,$countwh,$countincm,$countinc,$countdcdm,$countdcd);
						*/ 
}

/*
function  overroll_summary($program,$total,$countppm,$countpp,$countdfm,$countdf,$countrefm,$count2ref,$countrepm,$count2rep,$countwdm,$countwd,$countsusm,$countsus,$countsusm,$countsus,$countcpm,$countcp,$countwhm,$countwh,$countincm,$countinc,$countdcdm,$countdcd)
{
	
	$pp = $countppm + $countpp;
	$ref = $countrefm + $count2ref;
	$df = $countdfm + $countdf;
	$rep = $countrepm + $count2rep;
	$wd = $countwdm + $countwd;
	
	
	//$sql= "INSERT INTO `saris_year1`.`examsummary` (`prog` ,`DIS` ,`CR` ,`PP` ,`P` ,`CP` ,`DF` ,`REP` ,`REF` ,`FW` ,`WD` ,`DCD` ,`INC` ,`total`) VALUES(
//'$program', '', '', '$pp', '', '', '$df', '$rep', '$ref', '', '$wd', '', '', '$total')";	

$sql= "UPDATE `saris_year1`.`examsummary` SET `prog` = '$program',`DIS` = '' ,`CR` = '' ,`PP` = '' ,`P` = '$pp' ,`CP` = '' ,`DF` = '$df' ,`REP` = '$rep' ,`REF` = '$ref' ,`FW` = '' ,`WD` = '$wd' ,`DCD` = '' ,`INC` = '' ,`total` = '$total' Where prog = '$program'";	
//die($sql);
mysql_query($sql) or die(mysql_error());
	
}
*/
function  overroll_summary($program,$total,$countppm,$countpp,$countdfm,$countdf,$countrefm,$count2ref,$countrepm,$count2rep,$countwdm,$countwd,$countsusm,$countsus,$countsusm,$countsus,$countcpm,$countcp,$countfwm,$countfw,$countwhm,$countwh,$countincm,$countinc,$countdcdm,$countdcd)
{
	
	$pp = $countppm + $countpp;
	$ref = $countrefm + $count2ref+ $rep + $fw ;
	$df = $countdfm + $countdf;
	$rep = $countrepm + $count2rep;
	$wd = $countwdm + $countwd;
	$dcd = $countdcdm + $countdcd;
	$fw = $countfwm + $countfw;
	$inc = $countincm + $countinc;
	$sus = $countsusm + $countsus;
	
	if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 PEAD')
		{
			$program = 'Bachelor of Science in Nursing (Post Basic) Yr2 PEADS';
			
		}
//$sqlcheck = "SELECT * FROM  "
	
	//$sql= "INSERT INTO `examsummary` (`prog` ,`DIS` ,`CR` ,`PP` ,`P` ,`CP` ,`DF` ,`REP` ,`REF` ,`FW` ,`WD` ,`DCD` ,`INC` ,`total`) VALUES(
//'$program', '', '', '$pp', '', '', '$df', '$rep', '$ref', '', '$wd', '', '', '$total')";	


$sql= "UPDATE `examsummary` SET `prog` = '$program',`DIS` = '' ,`CR` = '' ,`PP` = '$pp'  ,`P` = '',`CP` = '' ,`DF` = '$df' ,`REP` = '' ,`REF` = '$ref' ,`FW` = '' ,`WD` = '$wd ' ,`DCD` = '$dcd' ,`INC` = '$inc',`SUS` = '$sus' ,`total` = '$total' Where prog = '$program'";	
//die($sql);
mysql_query($sql) or die(mysql_error());
	
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
 

function romanic_number($integer, $upcase = true)
{
    $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1);
    $return = '';
    while($integer > 0)
    {
        foreach($table as $rom=>$arb)
        {
            if($integer >= $arb)
            {
                $integer -= $arb;
                $return .= $rom;
                break;
            }
        }
    }

    return $return;
}


function Footerb() {
        if($this->_numberingFooter==false)
            return;
        //Go to 1.5 cm from bottom
        $this->SetY(-15);
        //Select Arial italic 8
        $this->SetFont('Arial', 'I', 8);
		$this->Cell(0,10,date(d.'-'.m.'-'.Y).'  This Report was generated from SARIS developed by ICT Department KCN',0,0,'C');
    	$this->Ln(5);
		$num = $this->numPageNo();
		if($this->_numberingb==true)
		{
       		 $this->Cell(0, 7, $this->romanic_number($num), 0, 0, 'C');
		}
		else
		{
			$this->Cell(0, 7, $this->numPageNo(), 0, 0, 'C');
		}
        if($this->_numbering==false)
            $this->_numberingFooter=false;
		else if($this->_numberingb==false)
            $this->_numberingFooter=false;
    }











//for mature entry post basic

function PostBasic($year,$program,$semister)
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

			if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 COM')
			{
				$prefix = 'COM';
			
			}
			else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 MGT')
			{
				$prefix = 'MGT';
			
			}
			else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 EDU')
			{
				$prefix = 'ED';
			
			}
			else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 PAED')
			{
				$prefix = 'PEAD';
			
			}
			
		$sql2 = "select distinct er.RegNo from examregister er, course e where er.CourseCode = e.CourseCode and e.prefix = '$prefix' and er.Ayear = $year  and er.RegNo like '%/%/%' and e.Programme = 10052 order by er.RegNo asc ";
	
	
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
      
    if($hist =='(Sus)' || $trim == 'DF' || $hist == 'DF' || $hist == '(WD)' || $hist == '(WD/P)' || $hist == 'NP' || $trim2 == 'NP' || $trim3 == 'NP' || $trim4 == 'NP' || $trim5 == 'NP' || $trim6 == 'NP' || $hist == 'INC' )
    {
        $this->Cell(8,7,'',1,0,'R',$fill);
    
    }
    else if ($avg >= 70)
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
       
        if($hist == '(WD)' || $hist == '(WD/P)')
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
		else if($hist == 'INC')
        {
            $this->Cell(16,7,'INC',1,0,'R',$fill);
             if($sex == 'F')
              {
                $countinc +=1;
              }
              else
              {
                $countincm +=1;
				
              
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
            $this->Cell(16,7,'P',1,0,'R',$fill);
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
$this->Ln(7);
$this->SetFont('','B',9);
//statistics
$this->header = 0;
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
		
$this->header = 0;

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
and c.Programme = '$progcode' and  c.prefix = '$prefix' and examregister.RegNo LIKE '%/%/%' and student.Sex = 'F'";
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
and c.Programme = '$progcode' and  c.prefix = '$prefix' and examregister.RegNo LIKE '%/%/%' and student.Sex = 'M'";
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
and c.Programme = '$progcode' and  c.prefix = '$prefix' and examregister.RegNo LIKE '%/%/%' ";
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

//end mature entry

//program header

function progheader($program,$semister, $year)
{	
	$this->header = 0;
	//$pdf->footer = 0;

    $this->AddPage('L','',false);
	$this->SetFont('Arial','B',13);
	$this->Ln(7);
	//$this->TOC_Entry($program, 0);
    $this->Cell(30,9,"PROGRAMME :		",0,0,'C',0);
	
	
	if($program == 'Bachelor of Science in Nursing and Midwifery (Generic) Yr1')
        {
        $prog = trim($program, 'Yr1');
    	$this->Cell(150,9,$prog.' Year 1',0,0,'C',0);
       // $this->Cell($w,9,$prog.' Year 1',0,0,'C',0);
        }
        else if($program == 'Bachelor of Science in Nursing and Midwifery (Generic) Yr2')
        {
            $prog = trim($program, 'Yr2');
    
           $this->Cell(150,9,$prog.' Year 2',0,0,'C',0);
        }
        else if($program == 'Bachelor of Science in Nursing and Midwifery (Generic) Yr3')
        {
            $prog = trim($program, 'Yr3');
    
            $this->Cell(150,9,$prog.' Year 3',0,0,'C',0);
        }
		 else if($program == 'Bachelor of Science in Nursing and Midwifery (Generic) Yr4')
        {
            $prog = trim($program, 'Yr4');
    
            $this->Cell(150,9,$prog.' Year 4',0,0,'C',0);
        }
		 else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 EDU')
        {
            $prog = trim($program, 'Yr2 EDU');
    
            $this->Cell(150,9,$prog.' Year 2 EDUCATION',0,0,'C',0);
        }
		 else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 PAED')
        {
            $prog = trim($program, 'Yr2 PAED');
    
            $this->Cell(150,9,$prog.' Year 2 PEADIATRIC',0,0,'C',0);
        }
		 else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr1')
        {
            $prog = trim($program, 'Yr1');
    
            $this->Cell(150,9,$prog.' Year 1 ',0,0,'C',0);
        }
	
    //Data
	 if($semister == "Semester II")
	 {
		 
		
	 $this->Ln(7);
	 $this->Ln(7);
	 //$this->TOC_Entry("Modules for Semester I", 1);
	 $this->Cell(30,9,"Modules for ",0,0,'C',0);
	 $this->Cell(30,9, "Semester I",0,0,'C',0);
	 $this->Ln(7);
	 $this->Ln(7);
	 $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('Arial','B',13);
    $fill=0;
	 $this->Cell(60,7,"CODE",1,0,'L',$fill);
     $this->Cell(200,7,"MODULE",1,0,'L',$fill);
	 $this->Ln(7);
	 $this->SetFont('Arial','',13);
	
	
$sqli = "SELECT distinct e.CourseCode, c.CourseName FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester I' and e.programme = '$program'";
//die($sqli);

        $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
			$fill=!$fill;
            $course= $rowi['CourseCode'];
            $coursename= $rowi['CourseName'];
			
			$this->Cell(60,7,$course,1,0,'L',$fill);
			$this->Cell(200,7,$coursename,1,0,'L',$fill);
	 		$this->Ln(7);
			
			
		}
		  $this->SetFont('Arial','B',13);
		  
		
		$this->Ln(7);
	$this->Ln(7);
	//$this->TOC_Entry("Modules for Semester II", 1);
	 $this->Cell(30,9,"Modules for ",0,0,'C',0);
	 $this->Cell(30,9,$semister,0,0,'C',0);
	 $this->Ln(7);
	 $this->Ln(7);
	 $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('Arial','B',13);
		 $fill=0;
		 
	 $this->Cell(60,7,"CODE",1,0,'L',$fill);
     $this->Cell(200,7,"MODULE",1,0,'L',$fill);
	 $this->Ln(7);
	 $this->SetFont('Arial','',13);
	 
	 $sqli = "SELECT distinct e.CourseCode, c.CourseName FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = '$semister' and e.programme = '$program'";
//die($sqli);

        $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
			$fill=!$fill;
            $course= $rowi['CourseCode'];
            $coursename= $rowi['CourseName'];
			
			$this->Cell(60,7,$course,1,0,'L',$fill);
			$this->Cell(200,7,$coursename,1,0,'L',$fill);
	 		$this->Ln(7);
			
			
		}
		
		
	 }
	 else
	 {
	 
		 $this->Ln(7);
	$this->Ln(7);
	 //$this->TOC_Entry("Modules for Semester I", 1);
	 $this->Cell(30,9,"Modules for ",0,0,'C',0);
	 $this->Cell(30,9,$semister,0,0,'C',0);
	 $this->Ln(7);
	 $this->Ln(7);
	 $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('Arial','B',13);
		  $fill=0;
	 $this->Cell(60,7,"CODE",1,0,'L',$fill);
     $this->Cell(200,7,"MODULE",1,0,'L',$fill);
	 $this->Ln(7);
	 $this->SetFont('Arial','',13);
		 
		 $sqli = "SELECT distinct e.CourseCode, c.CourseName FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = '$semister' and e.programme = '$program'";
//die($sqli);

        $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
			$fill=!$fill;
            $course= $rowi['CourseCode'];
            $coursename= $rowi['CourseName'];
			
			$this->Cell(60,7,$course,1,0,'L',$fill);
			$this->Cell(200,7,$coursename,1,0,'L',$fill);
	 		$this->Ln(7);
			
			
		}
		 
	 }
	

	
}

// overall summary
function summary($year)
{
	$this->AddPage('L','',false);
	$this->TOC_Entry("Summary of Results", 0);
	$this->SetFont('Arial','B',13);
	$this->Ln(7);
	 $this->Cell(200,7,"END OF SEMESTER I  - ".$year." ACADEMIC YEAR",0,0,'C',0);
	$this->Ln(7);
	
	 $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('Arial','B',11);
		  $fill=0;
	 $this->Cell(10,7,"SN",1,0,'L',$fill);
     $this->Cell(110,7,"PROGRAMME",1,0,'L',$fill);
	 $this->Cell(10,7,"DIS",1,0,'L',$fill);
	 $this->Cell(10,7,"CR",1,0,'L',$fill);
	 $this->Cell(10,7,"P",1,0,'L',$fill);
	 $this->Cell(10,7,"CP",1,0,'L',$fill);
	 $this->Cell(10,7,"DF",1,0,'L',$fill);
	 $this->Cell(10,7,"REP",1,0,'L',$fill);
	 $this->Cell(10,7,"REF",1,0,'L',$fill);
	 $this->Cell(10,7,"DCD",1,0,'L',$fill);
	 $this->Cell(10,7,"WD",1,0,'L',$fill);
	 $this->Cell(10,7,"TR",1,0,'L',$fill);
	 $this->Cell(10,7,"INC",1,0,'L',$fill);
	 $this->Cell(40,7,"TOTAL NO. OF STUD",1,0,'L',$fill);
	 
	 
	 $this->Ln(7);
	 $this->SetFont('Arial','',11);
		 
		 $sqli = "SELECT `prog`,`DIS`,`CR`,`PP`,`P`,`CP`,`DF`,`REP`,`REF`,`FW`,`WD`,TR,`DCD`,`INC`,`total` FROM `examsummary` ";
//die($sqli);
		$i = 1;
        $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
			$fill=!$fill;
            $program = $rowi['prog'];
            $DIS= $rowi['DIS'];
			$CR= $rowi['CR'];
			$PP= $rowi['PP'];
			$P= $rowi['P'];
			$CP= $rowi['CP'];
			$DF= $rowi['DF'];
			$REP= $rowi['REP'];
			$REF= $rowi['REF'];
			$FW= $rowi['FW'];
			$WD= $rowi['WD'];
			$TR= $rowi['TR'];
			$DCD= $rowi['DCD'];
			$INC= $rowi['INC'];
			$total= $rowi['total'];
	 $this->Cell(10,7,$i,1,0,'L',$fill);
     $this->Cell(110,7,$program,1,0,'L',$fill);
	 $this->Cell(10,7,$DIS,1,0,'L',$fill);
	 $this->Cell(10,7,$CR,1,0,'L',$fill);
	 $this->Cell(10,7,$PP,1,0,'L',$fill);
	 $this->Cell(10,7,$CP,1,0,'L',$fill);
	 $this->Cell(10,7,$DF,1,0,'L',$fill);
	 $this->Cell(10,7,$REP,1,0,'L',$fill);
	 $this->Cell(10,7,$REF,1,0,'L',$fill);
	 $this->Cell(10,7,$FW,1,0,'L',$fill);
	 $this->Cell(10,7,$WD,1,0,'L',$fill);
	 $this->Cell(10,7,$TR,1,0,'L',$fill);
	 $this->Cell(10,7,$INC,1,0,'L',$fill);
	 $this->Cell(40,7,$total,1,0,'L',$fill);
	 		$this->Ln(7);
			$i++;
			
		}
		$sqli2 = "SELECT SUM(PP) AS TPP,SUM(REF) AS TREF,SUM(WD) AS TWD, SUM(total) AS TOTAL FROM `examsummary` ";
//die($sqli);
 $this->SetFont('Arial','B',11);
		$i = 1;
        $resulti = mysql_query($sqli2);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
			$fill=!$fill;
            $TPP = $rowi['TPP'];
            $TREF= $rowi['TREF'];
			$TWD= $rowi['TWD'];
			$TOTAL= $rowi['TOTAL'];
			$this->Cell(10,7,'',1,0,'L',$fill);
     $this->Cell(110,7,'TOTAL',1,0,'L',$fill);
	 $this->Cell(10,7,'0',1,0,'L',$fill);
	 $this->Cell(10,7,'0',1,0,'L',$fill);
	 $this->Cell(10,7,$TPP,1,0,'L',$fill);
	 $this->Cell(10,7,'0',1,0,'L',$fill);
	 $this->Cell(10,7,'0',1,0,'L',$fill);
	 $this->Cell(10,7,'0',1,0,'L',$fill);
	 $this->Cell(10,7,$TREF,1,0,'L',$fill);
	 $this->Cell(10,7,'0',1,0,'L',$fill);
	 $this->Cell(10,7,$TWD,1,0,'L',$fill);
	 $this->Cell(10,7,'0',1,0,'L',$fill);
	 $this->Cell(10,7,'0',1,0,'L',$fill);
	 $this->Cell(40,7,$TOTAL,1,0,'L',$fill);
		}
	
	
}


}
$pdf=new PDF();
//Column titles
//$pdf2= new PDF_TOC(); 
//$pdf= new PDF_TOC(); 
 $pdf->SetFont('Arial','B',13);
	

$pdf->startPageNums(2);


$year = $_GET["year"];
//$year = 2012;
//$program = $_GET["program"];


$semister = $_GET["semister"];
$pagecount = $pdf->setSourceFile('../coverb.pdf'); 
$pdf->header = 0;
				$tplidx2 = $pdf->importPage(4, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->TOC_Entry("Abbreviations", 0);
				$pdf->useTemplate($tplidx2, 0, 0, 0); 
				
		


$pagecount = $pdf->setSourceFile('../coverc.pdf'); 

	
$rulecount = 0;
$rulecount2 = 0;

$pdf->summary($year);


$sqli = "SELECT distinct `programme`,`Semister` FROM `examdate` WHERE `Ayear` = $year and `Semister` = '$semister'  order by `programme` asc ";
        $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $program= $rowi['programme'];
            $semister= $rowi['Semister'];
			
			// adding rules and regulations
	
			//if($program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT" || $program == "University Certificate in Midwifery")
			if($program == "Bachelor of Science in Nursing (Post Basic) Yr2 PAED" || $program == "University Certificate in Midwifery " || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT"  || $program == "Bachelor of Science in Nursing (Post Basic) Yr1" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT")
				{
			     $rulecount2 = 0;
				if($rulecount == 0)
				{
					/*
				$pdf->header = 0;
				$tplidx = $pdf->importPage(2, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0);
				 
				$tplidx = $pdf->importPage(3, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				
				$tplidx = $pdf->importPage(4, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0); */
				
                $pdf->header = 0;
				$tplidx = $pdf->importPage(8, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->TOC_Entry("Assessment Rules and Regulations for PostBasic Programmes ", 0);
				$pdf->useTemplate($tplidx, 0, 0, 0);
				 
				$tplidx = $pdf->importPage(9, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				
				$tplidx = $pdf->importPage(10, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				
				$tplidx = $pdf->importPage(11, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0);
				
				$tplidx = $pdf->importPage(12, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0);
				
				$tplidx = $pdf->importPage(13, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0);
								
				}
				
				$rulecount ++;
				
			}
			else
			{   
		         $rulecount = 0;
				if($rulecount2 == 0)
				{
					$pdf->header = 0;
				$tplidx = $pdf->importPage(2, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->TOC_Entry("Assessment Rules and Regulations for Bachelor of Science in Nursing and Midwifery ", 0);
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				
				$tplidx = $pdf->importPage(3, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				
				$tplidx = $pdf->importPage(4, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				
				$tplidx = $pdf->importPage(5, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				
				$tplidx = $pdf->importPage(6, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				
				$tplidx = $pdf->importPage(7, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				/*$pdf->header = 0;
				$tplidx = $pdf->importPage(5, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->TOC_Entry("Regulations for Bachelor of Science in Nursing and Midwifery ", 0);
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				
				$tplidx = $pdf->importPage(6, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				
				$tplidx = $pdf->importPage(7, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				*/
				
				}
				
				$rulecount2 ++;
				
				
			}
			
			
			$pdf->progheader($program,$semister, $year);
			$pdf->header = 1;
			
			if($program == 'Bachelor of Science in Nursing (Post Basic) Yr1')
			{
				$pdf->TOC_Entry('Bachelor of Science in Nursing (Post Basic) Year 1', 0);
			
			}
			elseif($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 COM')
			{
				$pdf->TOC_Entry('Bachelor of Science in Nursing (Post Basic) Year 2 COMMUNITY', 0);
			
			}
			
			elseif($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 COM')
			{
				$pdf->TOC_Entry('Bachelor of Science in Nursing (Post Basic) Year 2 COMMUNITY', 0);
			
			}
			else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 MGT')
			{
				$pdf->TOC_Entry('Bachelor of Science in Nursing (Post Basic) Year 2 MANAGEMENT', 0);
			
			}
			else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 EDU')
			{
				$pdf->TOC_Entry('Bachelor of Science in Nursing (Post Basic) Year 2 EDUCATION', 0);
			
			}
			else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 PAED')
			{
				$pdf->TOC_Entry('Bachelor of Science in Nursing (Post Basic) Year 2 PEADIATRIC', 0);
			
			}
			else if($program == 'Bachelor of Science in Nursing and Midwifery (Generic) Yr1')
			{
				$pdf->TOC_Entry('Bachelor of Science in Nursing and Midwifery (Generic) Year 1', 0);
			
			}
			else if($program == 'Bachelor of Science in Nursing and Midwifery (Generic) Yr2')
			{
				$pdf->TOC_Entry('Bachelor of Science in Nursing and Midwifery (Generic) Year 2', 0);
			
			}
			else if($program == 'Bachelor of Science in Nursing and Midwifery (Generic) Yr3')
			{
				$pdf->TOC_Entry('Bachelor of Science in Nursing and Midwifery (Generic) Year 3', 0);
			
			}
			else if($program == 'Bachelor of Science in Nursing and Midwifery (Generic) Yr4')
			{
				$pdf->TOC_Entry('Bachelor of Science in Nursing and Midwifery (Generic) Year 4', 0);
			
			}
			else
			{
			 $pdf->TOC_Entry($program, 0);	
			}
			
			
			
        
			
			if($semister == "Semester I")
			{
				
				if($program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT")
				{
	
					//die("$yr,$class,$code");
					$header=array('SN','REG NO.', 'NAME OF STUDENT','SEX', 'HISTORY');
					//Data loading
					//$data=$pdf->LoadData('pdf/tutorial/countries.txt');
					$pdf->SetFont('Arial','',9);
					$pdf->AddPage('L');
					
					$pdf->PostBasic($year,$program,$semister);
					//$pdf->LoadData();
				}
				else
				{
					//die("$yr,$class,$code");
					$header=array('SN','REG NO.', 'NAME OF STUDENT','SEX', 'HISTORY');
					//Data loading
					//$data=$pdf->LoadData('pdf/tutorial/countries.txt');
					$pdf->SetFont('Arial','',9);
					$pdf->AddPage('L');
					
					$pdf->FancyTable($year,$program,$semister);
					//$pdf->LoadData();
					
				}
			}
			else
			{
				
				
				
				if($program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT")
				{
	
					//die("$yr,$class,$code");
					$header=array('SN','REG NO.', 'NAME OF STUDENT','SEX', 'HISTORY');
					//Data loading
					//$data=$pdf->LoadData('pdf/tutorial/countries.txt');
					$pdf->SetFont('Arial','',9);
					$pdf->AddPage('L');
					
					$pdf->mature_year2($year,$program,$semister);
					//$pdf->LoadData();
				}
				else if($program == "Bachelor of Science in Nursing and Midwifery (Generic) Yr4")
				{
					$header=array('SN','REG NO.', 'NAME OF STUDENT','SEX', 'HISTORY');
					//Data loading
					//$data=$pdf->LoadData('pdf/tutorial/countries.txt');
					$pdf->SetFont('Arial','',9);
					$pdf->AddPage('L');
					
					$pdf->year4($year,$program,$semister);
					
				}
				else if($program == "University Certificate in Midwifery")
				{
					$header=array('SN','REG NO.', 'NAME OF STUDENT','SEX', 'HISTORY');
					//Data loading
					//$data=$pdf->LoadData('pdf/tutorial/countries.txt');
					$pdf->SetFont('Arial','',9);
					$pdf->AddPage('L');
					
					$pdf->ucm_table($year,$program,$semister);
					
				}
				else
				{
				
				//die("am here");
				//die("$yr,$class,$code");
					$header=array('SN','REG NO.', 'NAME OF STUDENT','SEX', 'HISTORY');
					//Data loading
					//$data=$pdf->LoadData('pdf/tutorial/countries.txt');
					$pdf->SetFont('Arial','',9);
					$pdf->AddPage('L');
					
					$pdf->FancyTable($year,$program,$semister);
				}
					
					
					//$pdf->LoadData();
			}
		}
		
//$pdf= new PDF_TOC();
 
$pdf->insertTOC(1);
$pdf->Output();
?>
