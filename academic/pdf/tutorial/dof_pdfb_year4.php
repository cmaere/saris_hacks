<?php
require('../fpdf.php');
//require('button.php');

class PDF extends FPDF
{


//rotate function another one
var $TextRotation;       //Text Rotation in degrees
var $TextRotationMatrix; //Text Rotation as a PDF Text Transformation Matrix

function SetTextRotation($degrees)
    {
       $this->TextRotation = $degrees;
       
       $radians = deg2rad((float)$degrees);       
       $this->TextRotationMatrix = sprintf('%.2f',cos($radians)).' '.sprintf('%.2f',(sin($radians)*-1.0)).' '.
              sprintf('%.2f',sin($radians)).' '.sprintf('%.2f',cos($radians)).' '; 
    }
    
    function Text($x,$y,$txt)
    {
     //Output a string
     if ($this->TextRotation  == 0)
     {
      $s=sprintf('BT %.2f %.2f Td (%s) Tj ET',$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
     }
     else
     {
      $s=sprintf('BT '.$this->TextRotationMatrix.'%.2f %.2f Tm (%s) Tj ET',
         $x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
     }
     
     if($this->underline && $txt!='')
      $s.=' '.$this->_dounderline($x,$y,$txt);
     if($this->ColorFlag)
      $s='q '.$this->TextColor.' '.$s.' Q';
     $this->_out($s);
    }
    function Cell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='')
    {
     //Output a cell
     $k=$this->k;
     if($this->y+$h>$this->PageBreakTrigger && !$this->InFooter && $this->AcceptPageBreak())
     {
      //Automatic page break
      $x=$this->x;
      $ws=$this->ws;
      if($ws>0)
      {
       $this->ws=0;
       $this->_out('0 Tw');
      }
      $this->AddPage($this->CurOrientation);
      $this->x=$x;
      if($ws>0)
      {
       $this->ws=$ws;
       $this->_out(sprintf('%.3f Tw',$ws*$k));
      }
     }
     if($w==0)
      $w=$this->w-$this->rMargin-$this->x;
     $s='';
     if($fill==1 || $border==1)
     {
      if($fill==1)
       $op=($border==1) ? 'B' : 'f';
      else
       $op='S';
      $s=sprintf('%.2f %.2f %.2f %.2f re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
     }
     if(is_string($border))
     {
      $x=$this->x;
      $y=$this->y;
      if(strpos($border,'L')!==false)
       $s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
      if(strpos($border,'T')!==false)
       $s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
      if(strpos($border,'R')!==false)
       $s.=sprintf('%.2f %.2f m %.2f %.2f l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
      if(strpos($border,'B')!==false)
       $s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
     }
     if($txt!=='')
     {
      if($align=='R')
       $dx=$w-$this->cMargin-$this->GetStringWidth($txt);
      elseif($align=='C')
       $dx=($w-$this->GetStringWidth($txt))/2;
      else
       $dx=$this->cMargin;
      if($this->ColorFlag)
       $s.='q '.$this->TextColor.' ';
      $txt2=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
      
      if ($this->TextRotation  == 0)
      {
       $s.=sprintf('BT %.2f %.2f Td (%s) Tj ET',($this->x+$dx)*$k,
          ($this->h-($this->y+.5*$h+.3*$this->FontSize))*$k,$txt2);
      }
      else
      {
       $s.=sprintf('BT '.$this->TextRotationMatrix.'%.2f %.2f Tm (%s) Tj ET',($this->x+$dx)*$k,
          ($this->h-($this->y+.5*$h+.3*$this->FontSize))*$k,$txt2);
      }
      
      if($this->underline)
       $s.=' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
      if($this->ColorFlag)
       $s.=' Q';
      if($link)
       $this->Link($this->x+$dx,$this->y+.5*$h-.5*$this->FontSize,$this->GetStringWidth($txt),$this->FontSize,$link);
     }
     if($s)
      $this->_out($s);
     $this->lasth=$h;
     if($ln>0)
     {
      //Go to next line
      $this->y+=$h;
      if($ln==1)
       $this->x=$this->lMargin;
     }
     else
      $this->x+=$w;
    }







//rotate

 function Rotate($angle,$x=-1,$y=-1) { 

        if($x==-1) 
            $x=$this->x; 
        if($y==-1) 
            $y=$this->y; 
        if($this->angle!=0) 
            $this->_out('Q'); 
        $this->angle=$angle; 
        if($angle!=0) 

        { 
            $angle*=M_PI/180; 
            $c=cos($angle); 
            $s=sin($angle); 
            $cx=$x*$this->k; 
            $cy=($this->h-$y)*$this->k; 
             
            $this->_out(sprintf('q %.5f %.5f %.5f %.5f %.2f %.2f cm 1 0 0 1 %.2f %.2f cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy)); 
        } 
    } 


// retrieve name function
function name($regno,$fill)
{

    $sql = "select Name, Sex, yr_repeated,UPPER(RegNo) as regno from student where RegNo = '$regno'";

    $result = mysql_query($sql);
    while($row = mysql_fetch_array($result, MYSQL_ASSOC))
    {
        $name= $row['Name'];
        $sex = $row['Sex'];
        $regno = $row['regno'];
        $history = $row['yr_repeated'];
       
        if($history == '0')
        {
            $history= ' ';
        }
        $this->Cell(35,7,$regno,1,0,'L',$fill);
        $this->Cell(50,7,$name,1,0,'L',$fill);
        $this->Cell(8,7,$sex,1,0,'L',$fill);
        $this->Cell(14,7,$history,1,0,'L',$fill);

        
    }
}
//statistics function

function statistics_rpt($year_previous,$year,$program_previous,$program,$cat)
{



$this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(35,7,'',1,0,'L',$fill);
                        $this->Cell(50,7,'Highest Score',1,0,'L',$fill);
                         $this->Cell(8,7,'',1,0,'L',$fill);
                        $this->Cell(14,7,'',1,0,'L',$fill);
                        
$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program_previous' order by e.CourseCode asc ";
        $resulti = mysql_query($sqli);
       //die($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            $cat= $rowi['assessment_status'];
        
   $sql4st = "select MAX(ExamScore) as avg from examresult where CourseCode = '$course' and AYear = $year_previous and ExamCategory = $cat  and ExamScore <> 0";
                //die($sql4st);
                   $result4st = mysql_query($sql4st) or die(mysql_error());
                   //$resul = mysql_fetch_assoc( $result4st);
                   
                    while($rowcst4 = mysql_fetch_array($result4st, MYSQL_ASSOC))
                    {
                        //$high = $rowc['high'];
                       $high= $rowcst4['avg'];
                       

                      $this->Cell(8,7,number_format($high),1,0,'R',$fill);
                    }
        }
        
        
        $this->Cell(8,7,'',1,0,'R',$fill);

//line separator
         $this->SetFillColor(0,0,0);
        $this->Cell(2,7,'',0,0,'C',1);
         $this->SetFillColor(224,235,255);
         //close line separator
         
      $sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc ";
        $resulti = mysql_query($sqli);
       //die($sqli);
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
                       

                      $this->Cell(8,7,number_format($high),1,0,'R',$fill);
                    }
        }  

$this->Cell(8,7,'',1,0,'R',$fill);
         $this->Cell(18,7,'',1,0,'L',$fill); 

$this->Ln(7);
$this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(35,7,'',1,0,'L',$fill);
                        $this->Cell(50,7,'Lowest Score',1,0,'L',$fill);
                         $this->Cell(8,7,'',1,0,'L',$fill);
                        $this->Cell(14,7,'',1,0,'L',$fill);
$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program_previous' order by e.CourseCode asc ";
        $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            $cat= $rowi['assessment_status'];
        
   $sql4st = "select MIN(ExamScore) as avg from examresult where CourseCode = '$course' and AYear = $year_previous and ExamCategory = $cat  and ExamScore <> 0";
                //die($sql4st);
                   $result4st = mysql_query($sql4st) or die(mysql_error());
                   //$resul = mysql_fetch_assoc( $result4st);
                   
                    while($rowcst4 = mysql_fetch_array($result4st, MYSQL_ASSOC))
                    {
                        //$high = $rowc['high'];
                       $low = $rowcst4['avg'];
                       

                      $this->Cell(8,7,number_format($low),1,0,'R',$fill);
                    }
        }
        
        
        $this->Cell(8,7,'',1,0,'R',$fill);

//line separator
         $this->SetFillColor(0,0,0);
        $this->Cell(2,7,'',0,0,'C',1);
         $this->SetFillColor(224,235,255);
         //close line separator
    $sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc ";
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
                       

                      $this->Cell(8,7,number_format($low),1,0,'R',$fill);
                    }
        }
        
        $this->Cell(8,7,'',1,0,'R',$fill);
         $this->Cell(18,7,'',1,0,'L',$fill); 
        
        $this->Ln(7);
                        $this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(35,7,'',1,0,'L',$fill);
                        $this->Cell(50,7,'Average Score',1,0,'L',$fill);
                         $this->Cell(8,7,'',1,0,'L',$fill);
                        $this->Cell(14,7,'',1,0,'L',$fill);
$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program_previous' order by e.CourseCode asc ";
        $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            $cat= $rowi['assessment_status'];
        
   $sql4st = "select AVG(ExamScore) as avg from examresult where CourseCode = '$course' and AYear = $year_previous and ExamCategory = $cat ";
                //die($sql4st);
                   $result4st = mysql_query($sql4st) or die(mysql_error());
                   //$resul = mysql_fetch_assoc( $result4st);
                   
                    while($rowcst4 = mysql_fetch_array($result4st, MYSQL_ASSOC))
                    {
                        //$high = $rowc['high'];
                       $avg = $rowcst4['avg'];
                       

                      $this->Cell(8,7,number_format($avg),1,0,'R',$fill);
                    }
        }
        
        $this->Cell(8,7,'',1,0,'R',$fill);

//line separator
         $this->SetFillColor(0,0,0);
        $this->Cell(2,7,'',0,0,'C',1);
         $this->SetFillColor(224,235,255);
         //close line separator
       $sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc ";
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
                       

                      $this->Cell(8,7,number_format($avg),1,0,'R',$fill);
                    }
        }     //die($sql3);
            //die($sql4);
         $this->Cell(8,7,'',1,0,'R',$fill);
         $this->Cell(18,7,'',1,0,'L',$fill);   




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
    $this->Cell($w,9,"FACULTY ASSESSMENT EXAM RESULTS ".$year,0,0,'C',0);
    $this->Ln(13);
	$this->SetLineWidth(1);
	//Title
    
	   if($semister == 'Semester II')
    {
        if($program == 'Bachelor of Science in Nursing and Midwifery (Generic) Yr4')
        {
        $prog = trim($program, 'Yr4');
    
        $this->Cell($w,9,$prog.' Year 4',0,0,'C',0);
        }
       
        
    }
    else
    {
	    $this->Cell($w,9,$program.' '.$semister,0,0,'C',0);
    }
	$this->Ln(10);

 
 //header
  $this->SetFillColor(57,127,145);
    $this->SetTextColor(255);
    $this->SetDrawColor(57,127,145);
    $this->SetLineWidth(.3);
    $this->SetFont('','B',8);
 
 


  

//Header

 
   
    $w=array(6,35,50,8,14);
     $this->Cell(119,7,'',0,0,'C',0);
   for($i=0;$i<count($header);$i++)
   {
        //$this->Cell($w[$i],7,$header[$i],1,0,'C',1);
       

    }
    $sqlpro = "select ProgrammeCode from program_year where ProgrammeName = '$program'";
    $resultpro = mysql_query($sqlpro);
    
    while($rowpro= mysql_fetch_array($resultpro, MYSQL_ASSOC))
    {
        $program_previous_code1= $rowpro['ProgrammeCode'];
    } 
    $program_previous_code = $program_previous_code1 - 1 ;
    
    $sqlpro2 = "select ProgrammeName from program_year where ProgrammeCode = $program_previous_code";
    $resultpro2 = mysql_query($sqlpro2);
    while($rowpro2= mysql_fetch_array($resultpro2, MYSQL_ASSOC))
    {
        $program_previous= $rowpro2['ProgrammeName'];
    }
    
    $times = 1;
    $times2 = 1;
    $sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = 5 and e.programme = '$program_previous' order by e.CourseCode asc ";
        $resulti = mysql_query($sqli);
        $this->SetTextRotation(90);
        $space = 126;
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            //$cat= $rowb['assessment_status'];
            
           // $this->Rotate(90);
            
             //$this->text(166,175,"DATE RECEIVED");
           //$this->Cell(12,30,$course,1,0,'L',1);
     $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
              $this->Text($space,54,$course);
            $times +=1;
            $space = $space + 8;
        }
        //$this->Write(0,'AVG Yr1');
        $this->Text($space,54,'AVG Yr3');
        // $this->Rotate(0);
           $this->SetTextRotation(0);
           
         //$this->Cell(12,7,'AVG',1,0,'C',1);
       
        //line separator
         //$this->SetFillColor(0,0,0);
        $this->Cell(2,7,'',0,0,'',0);
         $this->SetFillColor(57,127,145);
         //close line separator
         
         
           $sqli2 = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = 5 and e.programme = '$program' order by e.CourseCode asc ";
        $resulti2 = mysql_query($sqli2);
        $this->SetTextRotation(90);
        $space2 = $space + 10;
        while($rowi2 = mysql_fetch_array($resulti2, MYSQL_ASSOC))
        {
            $course2= $rowi2['CourseCode'];
            //$cat= $rowb['assessment_status'];
            $this->Text($space2,54,$course2);
            $times2 +=1;
            $space2 = $space2 + 8;
           
        }
        $this->Text($space2,54,'AVG Yr4');
        $this->SetTextRotation(0);
 
 //title
 $this->SetTextColor(255);
 $this->Ln(7);
 $this->Ln(8);
for($i=0;$i<count($header);$i++)
   {
        $this->Cell($w[$i],7,$header[$i],1,0,'C',1);
       

    }
    
    for($a=0; $a<$times; $a++)
    {
        
    $this->Cell(8,7,' ',0,0,'R',0);  
    
    }
    
    $this->SetFillColor(0,0,0);
        $this->Cell(2,7,'',0,0,'C',1);
    
    for($a=0; $a<$times2; $a++)
    {
        
    $this->Cell(8,7,' ',0,0,'R',0);  
    
    }
         $this->SetFillColor(57,127,145);
        $this->Cell(18,7,'RECOMM',1,0,'C',1);
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',9);
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
    $this->SetFont('','',9);
    //Data
    $fill=0;
require_once('../../../Connections/sessioncontrol.php');
require_once('../../../Connections/zalongwa.php');
//die($program);

$sqlpro = "select ProgrammeCode from program_year where ProgrammeName = '$program'";
    $resultpro = mysql_query($sqlpro);
    
    while($rowpro= mysql_fetch_array($resultpro, MYSQL_ASSOC))
    {
        $program_previous_code1= $rowpro['ProgrammeCode'];
    } 
    $program_previous_code = $program_previous_code1 - 1 ;
    
    $sqlpro2 = "select ProgrammeName from program_year where ProgrammeCode = '$program_previous_code'";
    $resultpro2 = mysql_query($sqlpro2);
    while($rowpro2= mysql_fetch_array($resultpro2, MYSQL_ASSOC))
    {
        $program_previous= $rowpro2['ProgrammeName'];
    }
    $year_previous = $year - 1;
    
$sql2 = "select distinct er.RegNo from examregister er, examdate e where er.CourseCode = e.CourseCode and e.programme = '$program' and er.Ayear = $year  and er.RegNo like '%/%/%' order by er.RegNo asc ";
    $result2 = mysql_query($sql2);
    $count = 1;
   $badseed = 0;
   $tracker2 = 0;
    $countdist = 0;
    $countcred = 0;
    $countpass = 0;
    $countref = 0;
    $countwd = 0;
    
    $countdistm = 0;
    $countcredm = 0;
    $countpassm = 0;
    $countrefm = 0;
    $countwdm = 0;
    $inc = 0;
   //die($sql2);
    while($row = mysql_fetch_array($result2, MYSQL_ASSOC))
    {
        $regno= $row['RegNo'];
        
        $sql4b = "select UPPER(ex.RegNo) as RegNo, e.CourseCode, ex.ExamScore from examresult ex, examdate e where ex.CourseCode  = e.CourseCode and ex.RegNo = '$regno' and ex.AYear = $year  and e.assessment_status = 5";
            
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
            $badseed = 1;
            $count = $count - 1;
        }
        $tracker = 0;
        
        //die($regno);
        //echo "$regno <br>";
//generation of courses for a specific programme
        $sql3 = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = 5 and e.programme = '$program_previous' order by e.CourseCode asc ";
        $result3 = mysql_query($sql3);
        //die($sql3);
        while($rowb = mysql_fetch_array($result3, MYSQL_ASSOC))
        {
            $course= $rowb['CourseCode'];
            $cat= $rowb['assessment_status'];
            
            
            
            $sql4 = "select UPPER(RegNo) as RegNo, CourseCode, ExamScore from examresult where CourseCode = '$course' and RegNo = '$regno' and AYear = $year_previous and ExamCategory = $cat  ";
            //die($sql3);
            //die($sql4);
            $result4 = mysql_query($sql4);
            $sqlrows = mysql_num_rows($result4);
            
            //die($sql4);
            if($sqlrows == 0 && $badseed !=1 )
                {
                
                $this->Cell(8,7,'--',1,0,'R',$fill);
                $inc = 1;
                
                }
            while($rowc = mysql_fetch_array($result4, MYSQL_ASSOC))
            {
                
            $regnob = $rowc['RegNo'];
                
               
                $courseb = $rowc['CourseCode'];
               
                $examscore = $rowc['ExamScore'];
                if($examscore == 0)
                { 
                    $this->Cell(8,7,'--',1,0,'R',$fill);  
                    $inc = 1;
                }
                else if($examscore < 50)
                {
                        $this->SetFont('','B',9);
                        
                    $this->Cell(8,7,number_format($examscore),1,0,'R',$fill); 
                    $this->SetFont('','',9);                    
                }
                else
                {
                
                    $this->Cell(8,7,number_format($examscore),1,0,'R',$fill); 
                
                }
                
                $tracker = 1;
                
               
                
                
            }
            
            
           
                   
                    
                    //$this->Cell(30,7,number_format($avg),1,0,'R',$fill);
                    
                    
                    
        }
        
     $sqlavg = "select AVG(ex.ExamScore) as avg from examresult ex, examdate e where ex.CourseCode  = e.CourseCode  and ex.RegNo = '$regno' and ex.AYear = $year_previous and ex.ExamCategory = 5 ";
            //die($sql3);
            //die($sql4);
            $resultavg = mysql_query($sqlavg);
            
            while($rowavg = mysql_fetch_array($resultavg, MYSQL_ASSOC))
            {
                 $avg = $rowavg['avg'];
            }
		if ($avg > 69)
		{
		$this->SetFont('','B',9);
                        
                                                 
            $this->Cell(8,7,number_format($avg),1,0,'R',$fill);
		$this->SetFont('','',9);
		}
		else
		{
        $this->SetFont('','B',9);
		 $this->Cell(8,7,number_format($avg),1,0,'R',$fill);
        $this->SetFont('','',9);
		}   
    
        //line separator
         $this->SetFillColor(0,0,0);
        $this->Cell(2,7,'',0,0,'C',1);
         $this->SetFillColor(224,235,255);
         //close line separator
    
    
    //generation of courses for a specific programme current year
        $sql3 = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = 5 and e.programme = '$program' order by e.CourseCode asc ";
        $result3 = mysql_query($sql3);
        //die($sql3);
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
            if($sqlrows == 0 && $badseed !=1 )
                {
                
                $this->Cell(8,7,'--',1,0,'R',$fill);
                $inc = 1;
                
                }
            while($rowc = mysql_fetch_array($result4, MYSQL_ASSOC))
            {
                
            $regnob = $rowc['RegNo'];
                
               
                $courseb = $rowc['CourseCode'];
               
                $examscore = $rowc['ExamScore'];
                
                if($hist == 'NP')
                {
                    $this->Cell(8,7,'--',1,0,'R',$fill);  
                
                }
                else if($examscore == 0)
                { 
                    $this->Cell(8,7,'--',1,0,'R',$fill);  
                    $inc = 1;
                }
                else if($examscore < 50)
                {
                        $this->SetFont('','B',9);
                        
                    $this->Cell(8,7,number_format($examscore),1,0,'R',$fill); 
                    $this->SetFont('','',9);                    
                }
                else
                {
                
                    $this->Cell(8,7,number_format($examscore),1,0,'R',$fill); 
                
                }
                
                $tracker = 1;
                
               
                
                
            }
            
            
           
                   
                    
                    //$this->Cell(30,7,number_format($avg),1,0,'R',$fill);
                    
                    
                    
        }
    
    
        $sqlavg = "select AVG(ex.ExamScore) as avg from examresult ex, examdate e where ex.CourseCode  = e.CourseCode and e.programme = '$program' and ex.RegNo = '$regno' and ex.AYear = $year  and ex.ExamCategory = 5 and e.assessment_status	 = 5";
            //die($sqlavg);
            //die($sql4);
            $resultavg = mysql_query($sqlavg);
            
            while($rowavg = mysql_fetch_array($resultavg, MYSQL_ASSOC))
            {
                 $avg2 = $rowavg['avg'];
                 //die($avg2);
            }
        if($inc == 1 || $hist == 'NP')
            {
               $this->Cell(8,7,'',1,0,'R',$fill); 
            }
            else if ($avg2 > 69)
		{
		    $this->SetFont('','B',9);
            $this->Cell(8,7,number_format($avg2),1,0,'R',$fill);
		    $this->SetFont('','',9);
		}
		else
		{
        $this->SetFont('','B',9);
		 $this->Cell(8,7,number_format($avg2),1,0,'R',$fill);
         $this->SetFont('','',9);

		}   
           
        //recommendation check
        
         $sqlmin = "select MIN(e.ExamScore) as year3 from examresult e, clinical_courses c
where e.CourseCode =  c.CourseCode and e.AYear = $year_previous and RegNo = '$regno' and e.ExamCategory = $cat ";
        $resultmin = mysql_query($sqlmin);
        //die($sqlmin);
            
            while($rowmin = mysql_fetch_array($resultmin, MYSQL_ASSOC))
            {
                 $lowestmark_yr3 = $rowmin['year3'];
                 
            }
            
                     $sqlminb = "select MIN(e.ExamScore) as year4 from examresult e, clinical_courses c
where e.CourseCode =  c.CourseCode and e.AYear = $year and RegNo = '$regno' and e.ExamCategory = $cat ";
        $resultminb = mysql_query($sqlminb);
        //die($sqlminb);
            
            while($rowminb = mysql_fetch_array($resultminb, MYSQL_ASSOC))
            {
                 $lowestmark_yr4_clinical = $rowminb['year4'];
                 
            }
                 $sqlmin2 = "select MIN(ExamScore) as year4 from examresult 
where AYear = $year and RegNo = '$regno' and ExamCategory = $cat  ";
        $resultmin2 = mysql_query($sqlmin2);
            
            while($rowmin2 = mysql_fetch_array($resultmin2, MYSQL_ASSOC))
            {
                 $lowestmark_yr4 = $rowmin2['year4'];
                 
            }
        
        
       
        
        
        if($badseed !=1)
        {
            //numbers has to come from database
            
            //die("here".$lowestmark_yr4_clinical);
            
    $sql = "select Name, Sex, yr_repeated,UPPER(RegNo) as regno from student where RegNo = '$regno'";

    $result = mysql_query($sql);
    while($row = mysql_fetch_array($result, MYSQL_ASSOC))
    {
        
        $sex = $row['Sex'];
        $hist = $row['yr_repeated'];
    }
            if($hist == 'NP')
            {
                $this->Cell(18,7,'WH',1,0,'R',$fill); 
                if($sex == 'F')
              {
                $countnp +=1;
              }
              else
              {
                $countnpm +=1;
              
              }   
            }
            else if($inc == 1)
            {
               $this->Cell(18,7,'INC',1,0,'R',$fill); 
               if($sex == 'F')
              {
                $countinc +=1;
              }
              else
              {
                $countincm +=1;
              
              }
            }
            else if( $lowestmark_yr4_clinical >= 70 && $lowestmark_yr4 >= 70 && $avg > 74 && $avg2 > 74 && $hist != '(AM 1)')
            {
              $this->Cell(18,7,'DIS',1,0,'R',$fill);
              if($sex == 'F')
              {
                $countdist +=1;
              }
              else
              {
                $countdistm +=1;
              
              }
            }
            else if($lowestmark_yr4_clinical > 64.4 && $lowestmark_yr4 >= 59.4 && $avg > 64.4 && $avg2 > 64.4 && $hist != '(AM 1)' )
            {
            
           
              $this->Cell(18,7,'CR',1,0,'R',$fill);
              if($sex == 'F')
              {
                $countcred +=1;
              }
              else
              {
                $countcredm +=1;
              }
            }
              else if($lowestmark_yr4 >= 50  && $hist != '(AM 1)')
            {
              $this->Cell(18,7,'P',1,0,'R',$fill);
              if($sex == 'F')
              {
                $countpass +=1;
              }
              else
              {
                $countpassm +=1;
              }
            }
               
            else if($lowestmark_yr4 < 50 && $hist != '(AM 1)')
            {
              $this->Cell(18,7,'REF',1,0,'R',$fill);
              if($sex == 'F')
              {
                $countref +=1;
              }
              else
              {
                $countrefm +=1;
              }
            }
            else if($avg2 < 50 && $hist != '(AM 1)')
            {
              $this->Cell(18,7,'WD',1,0,'R',$fill);
                if($sex == 'F')
              {
                $countwd +=1;
              }
              else
              {
                $countwdm +=1;
              }
            }
            else
            {
        
            $this->Cell(18,7,'',1,0,'R',$fill);
            }
            $this->Ln(7);
            $fill=!$fill;
        }            
         $badseed = 0;
        $count +=1; 
         
        
    $inc = 0;    

    }
   
$this->Ln(7);
$this->SetFont('','B',9);
//statistics

$this->statistics_rpt($year_previous,$year,$program_previous,$program,$cat);


//statistics
$this->Ln(7);
$this->Ln(7);
                        $this->Cell(50,7,'',1,0,'L',$fill);
                        $this->Cell(25,7,'No. of Stud',1,0,'L',$fill);
                        $this->Cell(18,7,'DIS',1,0,'L',$fill);
                        $this->Cell(18,7,'CR',1,0,'L',$fill);
                         $this->Cell(18,7,'P',1,0,'L',$fill);
                        $this->Cell(18,7,'REF',1,0,'L',$fill);
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
                        $this->Cell(18,7,number_format($countdist),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countcred),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countpass),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countref),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countwd),1,0,'L',$fill);
                        $this->Cell(18,7,'0',1,0,'L',$fill);
                        $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countnp),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countinc),1,0,'L',$fill);
                        //$this->Cell(18,7,'',1,0,'L',$fill);
                        
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
                       $this->Cell(18,7,number_format($countdistm),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countcredm),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countpassm),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countrefm),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countwdm),1,0,'L',$fill);
                        $this->Cell(18,7,'0',1,0,'L',$fill);
                        $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,'0',1,0,'L',$fill);
                          $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countnpm),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countincm),1,0,'L',$fill);
                        //$this->Cell(18,7,'',1,0,'L',$fill);
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
                        $this->Cell(18,7,$countdistm+$countdist,1,0,'L',$fill);
                        $this->Cell(18,7,$countcredm+$countcred,1,0,'L',$fill);
                         $this->Cell(18,7,$countpassm+$countpass,1,0,'L',$fill);
                        $this->Cell(18,7,$countrefm+$countref,1,0,'L',$fill);
                        $this->Cell(18,7,$countwdm+$countwd,1,0,'L',$fill);
                        $this->Cell(18,7,'0',1,0,'L',$fill);
                        $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,'0',1,0,'L',$fill);
                          $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,$countnpm+$countnp,1,0,'L',$fill);
                         $this->Cell(18,7,$countincm+$countinc,1,0,'L',$fill);
                         
                       // $this->Cell(18,7,'',1,0,'L',$fill);


//$this->statistics_rpt($year,$program,$cat);


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
    $num = $pagenum + 27;
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