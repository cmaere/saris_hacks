<?php
require('../fpdf.php');
require('../fpdi.php');
//require('toc.php');
require_once('../../../Connections/zalongwa.php');

class PDF extends FPDI
{


// rotating functions

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


// end rotating functions






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







// mature name

// 
function namemature($regno,$fill)
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
        $this->Cell(10,7,$sex,1,0,'L',$fill);
        $this->Cell(18,7,$history,1,0,'L',$fill);

        
    }
}


//end mature name
// name for year 4

// retrieve name function
function nameyear4($regno,$fill)
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
		if($history =='(Sus)' || $history == '(WD)' || $history == '(WD/P)' || $history == '(WD/M)' || $history == '(WD/V)' || $history == 'CP')
        {
            $this->Cell(14,7,'',1,0,'R',$fill);
    
        }
		else
		{
			
        $this->Cell(14,7,$history,1,0,'L',$fill);
		}

        
    }
}

// end name for year 4

// retrieve name function
function name($regno,$fill)
{

    $sql = "select Name as Name, Sex, yr_repeated, UPPER(RegNo) as regno from student where RegNo = '$regno'";

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
		 
		  $this->SetFont('','',9);
		 
        $this->Cell(35,7,$capregno,1,0,'L',$fill);
        $this->Cell(49,7,$name,1,0,'L',$fill);
        $this->Cell(6,7,$sex,1,0,'L',$fill);
        if($history =='(Sus)' || $history == '(WD)' || $history == '(WD/P)' || $history == '(WD/M)' || $history == '(WD/V)' || $history == 'CP')
        {
            $this->Cell(18,7,'',1,0,'R',$fill);
    
        }
        else if($trim == 'DF')
        {
            $trim2 =  trim($history,', DF');
            $this->Cell(18,7,$trim2,1,0,'L',$fill);
        }
        else
        {
            $this->Cell(18,7,$history,1,0,'L',$fill);
        }

        
    }


}

//ucm name

// retrieve name function
function nameucm($regno,$fill)
{

    $sql = "select Name as Name, Sex, yr_repeated, UPPER(RegNo) as regno from student where RegNo = '$regno'";

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
		 
		  $this->SetFont('','',9);
		 
        $this->Cell(40,7,$capregno,1,0,'L',$fill);
        $this->Cell(49,7,$name,1,0,'L',$fill);
        $this->Cell(7,7,$sex,1,0,'L',$fill);
        if($history =='(Sus)' || $history == '(WD)' || $history == '(WD/P)' || $history == '(WD/M)' || $history == '(WD/V)' || $history == 'CP')
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



//end ucm name

//Header contents

function headercontent($header,$year,$program,$semister)
{
	//die($semister);
	
	if( $semister == "Semester II")
	{
		
		$cat = 5;
		
	}
	else if( $semister == "Semester I")
	{
		
		$cat = 4;
		
	}


require_once('../../../Connections/sessioncontrol.php');
require_once('../../../Connections/zalongwa.php');
$this->Ln(4);
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
		else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 PAED')
		{
			$this->Cell(150,9,"Bachelor of Science in Nursing (Post Basic) Yr2 PAEDS",0,0,'C',0);
			
		}
		else
		{
			$this->Cell($w,9,$program,0,0,'C',0);	
		}
        
        
    }
    else
    {
		if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 PEAD')
		{
			$this->Cell($w,9,"Bachelor of Science in Nursing (Post Basic) Yr2 PEADS ".$semister,0,0,'C',0);
			
		}
		else
		{
	    	$this->Cell($w,9,$program.' '.$semister,0,0,'C',0);
		}
		
    }
	//Line break
	$this->Ln(10);

 
 //header
 // $this->SetFillColor(57,127,145);
    //$this->SetTextColor(255);
    $this->SetDrawColor(57,127,145);
    $this->SetLineWidth(.3);
   // $this->SetFont('','B',7);
 
 
 $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',8);

  

//Header
// cha upgrade split the semesters 
 $this->Ln(7);
  $this->Ln(7);
  
    // semester header
   
   //semesters header
	
	$length = 0;
	$lengthb = 0;
	if($semister == "Semester II")
	{
		
	$sqlia = "SELECT distinct e.CourseCode, c.CourseName FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester I' and e.programme = '$program' and assessment_status = 5  ORDER BY e.CourseCode";
	  $resultia = mysql_query($sqlia);
        while($rowia = mysql_fetch_array($resultia, MYSQL_ASSOC))
        {
            $course= $rowia['CourseCode'];
            //$cat= $rowb['assessment_status'];
            
            //$this->Cell(16,7,$course,1,0,'C',1);
			$length = $length + 14.5;
        }
		//$this->Cell(1,7,'',1,0,'C',1);
		
		$sqli = "SELECT distinct e.CourseCode, c.CourseName FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester II' and e.programme = '$program' and assessment_status = 5 ORDER BY e.CourseCode";
	  $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            //$cat= $rowb['assessment_status'];
            
            //$this->Cell(16,7,$course,1,0,'C',1);
			$lengthb = $lengthb + 14.5;
        }
		
	}
	else
	{
		
		
			
	$sqlia = "SELECT distinct e.CourseCode, c.CourseName FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester I' and e.programme = '$program'  ORDER BY e.CourseCode";
	  $resultia = mysql_query($sqlia);
        while($rowia = mysql_fetch_array($resultia, MYSQL_ASSOC))
        {
            $course= $rowia['CourseCode'];
            //$cat= $rowb['assessment_status'];
            
            //$this->Cell(16,7,$course,1,0,'C',1);
			$length = $length + 14.5;
        }
		
		
		
		
	}
	
	 $this->SetDrawColor(255,255,255);
	
	
	 $this->Cell(6,7,'',1,0,'L',$fill);
	 $this->Cell(35,7,'',1,0,'L',$fill);
     $this->Cell(49,7,'',1,0,'L',$fill);
     $this->Cell(6,7,'',1,0,'L',$fill);
	 $this->Cell(18,7,'',1,0,'L',$fill);
	  $this->SetDrawColor(0,0,0);
	 if($length == 14.5 && $semister == "Semester II")
	 {
	 	$this->Cell($length,7,'SEM 1',1,0,'C',$fill);
		 $this->Cell(1,7,'',1,0,'C',$fill);
		 
	 }else if($length == 0)
	 {
		 
		 
	 }
	 else
	 {
		 
		$this->Cell($length,7,'SEMESTER I',1,0,'C',$fill); 
		if($semister == "Semester II")
		{
			 $this->Cell(1,7,'',1,0,'C',$fill);
		}
	 }
	if($semister == "Semester II")
	{
	 $this->Cell($lengthb,7,'SEMESTER II',1,0,'C',$fill);
	
	
	}
	 
	 $this->Ln(7);
   
   //end semester header
   
    $this->SetFillColor(57,127,145);
    $this->SetTextColor(255);
    $this->SetDrawColor(0,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B',7);
  
  
  
  
   
    $w=array(6,35,49,6,18);
   for($i=0;$i<count($header);$i++)
   {
        $this->Cell($w[$i],7,$header[$i],1,0,'C',1);
    }
    if($semister != "Semester I")
	{
   // $sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc ";
	
	$length = 0;
	$lengthb = 0;
	$sqlia = "SELECT distinct e.CourseCode, c.CourseName FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester I' and e.programme = '$program' and assessment_status = 5 ORDER BY e.CourseCode";
	  $resultia = mysql_query($sqlia);
        while($rowia = mysql_fetch_array($resultia, MYSQL_ASSOC))
        {
            $course= $rowia['CourseCode'];
            //$cat= $rowb['assessment_status'];
            
            $this->Cell(14.5,7,$course,1,0,'C',1);
			$length = $length + 14.5;
        }
		$this->Cell(1,7,'',1,0,'C',1);
		
		$sqli = "SELECT distinct e.CourseCode, c.CourseName FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester II' and e.programme = '$program' and assessment_status = 5 ORDER BY e.CourseCode";
	  $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            //$cat= $rowb['assessment_status'];
            
            $this->Cell(14.5,7,$course,1,0,'C',1);
			$lengthb = $lengthb + 14.0;
        }
		
		//die("here".$length);
 $this->Cell(6,7,'AVG',1,0,'C',1);

        $this->Cell(14,7,'RECOMM',1,0,'C',1);
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',7);
     $this->Ln(7);
	
	
	
	
	}
	else
	{
		
	$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.programme = '$program' order by e.CourseCode asc ";	
	
	  $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            //$cat= $rowb['assessment_status'];
            
            $this->Cell(14.5,7,$course,1,0,'C',1);
        }
		
 $this->Cell(6,7,'AVG',1,0,'C',1);

        $this->Cell(14.5,7,'RECOMM',1,0,'C',1);
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',7);
     $this->Ln(7);
		
	}
	//die($sqli);	
	
}


//end header contents

// ucm header

function ucm_header($header,$year,$program,$semister)
{
	


require_once('../../../Connections/sessioncontrol.php');
require_once('../../../Connections/zalongwa.php');
$this->Ln(4);
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
    $this->Ln(9);
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
		else
		{
			$this->Cell($w,9,$program,0,0,'C',0);	
		}
        
        
    }
    else
    {
	    $this->Cell($w,9,$program.' '.$semister,0,0,'C',0);
    }
	//Line break
	$this->Ln(10);

 
 //header
  //$this->SetFillColor(57,127,145);
   // $this->SetTextColor(255);
    $this->SetDrawColor(57,127,145);
    $this->SetLineWidth(.3);
   // $this->SetFont('','B',7);
 
 
$this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',8);

  

//Header
// cha upgrade split the semesters 
 $this->Ln(7);
  $this->Ln(7);
   
   
   // semester header
   
   //semesters header
	
	$length = 0;
	$lengthb = 0;
	$sqlia = "SELECT distinct e.CourseCode, c.CourseName FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester I' and e.programme = '$program' and assessment_status = 5 ORDER BY e.CourseCode";
	  $resultia = mysql_query($sqlia);
        while($rowia = mysql_fetch_array($resultia, MYSQL_ASSOC))
        {
            $course= $rowia['CourseCode'];
            //$cat= $rowb['assessment_status'];
            
            //$this->Cell(16,7,$course,1,0,'C',1);
			$length = $length + 16;
        }
		//$this->Cell(1,7,'',1,0,'C',1);
		
		$sqli = "SELECT distinct e.CourseCode, c.CourseName FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester II' and e.programme = '$program' and assessment_status = 5 ORDER BY e.CourseCode";
	  $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            //$cat= $rowb['assessment_status'];
            
            //$this->Cell(16,7,$course,1,0,'C',1);
			$lengthb = $lengthb + 16;
        }
	
	 $this->SetDrawColor(255,255,255);
	
	 $this->Cell(6,7,'',1,0,'L',$fill);
	 $this->Cell(40,7,'',1,0,'L',$fill);
     $this->Cell(49,7,'',1,0,'L',$fill);
     $this->Cell(7,7,'',1,0,'L',$fill);
	 $this->Cell(12,7,'',1,0,'L',$fill);
	  $this->SetDrawColor(0,0,0);
	 if($length == 16)
	 {
	 	$this->Cell($length,7,'SEM 1',1,0,'C',$fill);
		 $this->Cell(1,7,'',1,0,'C',$fill);
		 
	 }else if($length == 0)
	 {
		 
		 
	 }
	 else
	 {
		 
		$this->Cell($length,7,'SEMESTER I',1,0,'C',$fill); 
		 $this->Cell(1,7,'',1,0,'C',$fill);
	 }
	
	 $this->Cell($lengthb,7,'SEMESTER II',1,0,'C',$fill);
	  
	 $this->Ln(7);
	 
	
   
   //end semester header
   
    $this->SetFillColor(57,127,145);
    $this->SetTextColor(255);
    $this->SetDrawColor(0,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B',7);
 
   
    $w=array(6,40,49,7,12);
   for($i=0;$i<count($header);$i++)
   {
        $this->Cell($w[$i],7,$header[$i],1,0,'C',1);
    }
    if($semister != "Semester I")
	{
   // $sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc ";
	
	$length = 0;
	$lengthb = 0;
	$sqlia = "SELECT distinct e.CourseCode, c.CourseName FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester I' and e.programme = '$program' and assessment_status = 5 ORDER BY e.CourseCode";
	  $resultia = mysql_query($sqlia);
        while($rowia = mysql_fetch_array($resultia, MYSQL_ASSOC))
        {
            $course= $rowia['CourseCode'];
            //$cat= $rowb['assessment_status'];
            
            $this->Cell(16,7,$course,1,0,'C',1);
			$length = $length + 16;
        }
		$this->Cell(1,7,'',1,0,'C',1);
		
		$sqli = "SELECT distinct e.CourseCode, c.CourseName FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester II' and e.programme = '$program' and assessment_status = 5 ORDER BY e.CourseCode";
	  $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            //$cat= $rowb['assessment_status'];
            
            $this->Cell(16,7,$course,1,0,'C',1);
			$lengthb = $lengthb + 16;
        }
		
		//die("here".$length);
 $this->Cell(6,7,'AVG',1,0,'C',1);

        $this->Cell(18,7,'RECOMM',1,0,'C',1);
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',8);
     $this->Ln(7);
	
	
	
	
	}
	else
	{
		
	$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.programme = '$program' order by e.CourseCode asc ";	
	
	  $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            //$cat= $rowb['assessment_status'];
            
            $this->Cell(16,7,$course,1,0,'C',1);
        }
		
 $this->Cell(8,7,'AVG',1,0,'C',1);

        $this->Cell(18,7,'RECOMM',1,0,'C',1);
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',8);
     $this->Ln(7);
		
	}
	//die($sqli);	
	
}



//end ucm header

// header
function header()
{
if ($this->header == 1)
{
	
global $header,$year,$program,$semister;



if ($semister == "Semester II")
{
    
	
				if($program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MID" || $program =="Bachelor of Science in Nursing (Post Basic) Yr2 PAED")
				{
					$this->mature_year2_header();
					
					
				}
				else if($program == "University Certificate in Midwifery")
				{
					$cat = 5;
					$this->ucm_header($header,$year,$program,$semister, $cat);
				}
				else if($program == "Bachelor of Science in Nursing and Midwifery (Generic) Yr4")
				{
					$this->year4_header();
					
				}
				else
				{
	
					
					$cat = 5;
					$this->headercontent($header,$year,$program,$semister, $cat);
				}
	
	
	
	
}
else
{
    $cat = 4;
	$this->headercontent($header,$year,$program,$semister, $cat);
}


      
}
} 

//scores function
function scores($sql3, $regno, $fill,$year)
{
	
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
	$trim7 =  trim($hist,'(TR), ');
	 
            if($sqlrows == 0)
                {
                
                $this->Cell(14.5,7,'--',1,0,'R',$fill);
                
                }
            while($rowc = mysql_fetch_array($result4, MYSQL_ASSOC))
            {
                
            $regnob = $rowc['RegNo'];
                
               
                $courseb = $rowc['CourseCode'];
               
                $examscore = $rowc['ExamScore'];
                if($hist == 'NP' || $trim2 == 'NP' || $trim3 == 'NP' || $trim4 == 'NP' || $trim5 == 'NP' || $trim6 == 'NP')
                {
                
                    $this->Cell(14.5,7,'--',1,0,'R',$fill);  
                }
                else if($examscore == 0 )
                { 
                    $this->Cell(14.5,7,'--',1,0,'R',$fill);  
                }
                else if($examscore < 50)
                {
                        $this->SetFont('','B',9);
                        
                    $this->Cell(14.5,7,number_format($examscore),1,0,'R',$fill); 
                    $this->SetFont('','',9);                    
                }
                else
                {
                
                    $this->Cell(14.5,7,number_format($examscore),1,0,'R',$fill); 
                
                }
                
                $tracker = 1;
                
               
                
                
            }
            
            
           
                   
                    
                    //$this->Cell(30,7,number_format($avg),1,0,'R',$fill);
                    
                    
                    
        }	
	
	
	
	
	
}
//scores ucm

//incomplete count

//scores function
function inc($sql3, $regno, $fill,$year)
{
	
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
	$trim7 =  trim($hist,'(TR), ');
            if($sqlrows == 0)
                {
                
                //$this->Cell(14.5,7,'--',1,0,'R',$fill);
				$inc = 1 ;
                
                }
            while($rowc = mysql_fetch_array($result4, MYSQL_ASSOC))
            {
                
            $regnob = $rowc['RegNo'];
                
               
                $courseb = $rowc['CourseCode'];
               
                $examscore = $rowc['ExamScore'];
                if($hist == 'NP' || $trim2 == 'NP' || $trim3 == 'NP' || $trim4 == 'NP' || $trim5 == 'NP' || $trim6 == 'NP')
                {
                
                   // $this->Cell(14.5,7,'--',1,0,'R',$fill);  
				   $inc = 1 ;
                }
                else if($examscore == 0 )
                { 
                    //$this->Cell(14.5,7,'--',1,0,'R',$fill); 
					$inc = 1 ; 
                }
                else if($examscore < 50)
                {
                       // $this->SetFont('','B',9);
                        
                    //$this->Cell(14.5,7,number_format($examscore),1,0,'R',$fill); 
					
                    //$this->SetFont('','',9);  
					$inc = 0 ;                  
                }
                else
                {
                
                   // $this->Cell(14.5,7,number_format($examscore),1,0,'R',$fill);
				   $inc = 0 ; 
                
                }
                
                $tracker = 1;
                
               
                
                
            }
            
            
           
                   
                    
                    //$this->Cell(30,7,number_format($avg),1,0,'R',$fill);
                    
                    
                    
        }	
	
	
	return($inc);
	
	
}


// end inc count




//scores function
function scoresucm($sql3, $regno, $fill,$year)
{
	
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
	$trim7 =  trim($hist,'(TR), ');
            if($sqlrows == 0)
                {
                
                $this->Cell(16,7,'--',1,0,'R',$fill);
                
                }
            while($rowc = mysql_fetch_array($result4, MYSQL_ASSOC))
            {
                
            $regnob = $rowc['RegNo'];
                
               
                $courseb = $rowc['CourseCode'];
               
                $examscore = $rowc['ExamScore'];
                if($hist == 'NP' || $trim2 == 'NP' || $trim3 == 'NP' || $trim4 == 'NP' || $trim5 == 'NP' || $trim6 == 'NP')
                {
                
                    $this->Cell(16,7,'--',1,0,'R',$fill);  
                }
                else if($examscore == 0 )
                { 
                    $this->Cell(16,7,'--',1,0,'R',$fill);  
                }
                else if($examscore < 50)
                {
                        $this->SetFont('','B',9);
                        
                    $this->Cell(16,7,number_format($examscore),1,0,'R',$fill); 
                    $this->SetFont('','',9);                    
                }
                else
                {
                
                    $this->Cell(16,7,number_format($examscore),1,0,'R',$fill); 
                
                }
                
                $tracker = 1;
                
               
                
                
            }
            
            
           
                   
                    
                    //$this->Cell(30,7,number_format($avg),1,0,'R',$fill);
                    
                    
                    
        }	
	
	
	
	
	
}



//end scores ucm
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
    $this->SetFont('','',9);
    //Data
    $fill=0;
require_once('../../../Connections/sessioncontrol.php');
require_once('../../../Connections/zalongwa.php');
//table of content index
//$this->TOC_Entry("Results Table", 1);

 if($semister != "Semester I")
	{
	//$sql2 = "select distinct er.RegNo from examregister er, examdate e where er.CourseCode = e.CourseCode and e.programme = '$program' and er.Ayear = $year and e.assessment_status = $cat and er.RegNo like '%/%/%' order by er.RegNo asc ";
	 $sql2 = "select distinct examregister.RegNo from examregister 
INNER JOIN  examdate 
ON examregister.CourseCode = examdate.CourseCode
INNER JOIN student
ON examregister.RegNo = student.RegNo
WHERE  examdate.programme = '$program' and examregister.Ayear = $year and examdate.assessment_status = $cat and examregister.RegNo like '%/%/%' order by student.Name asc";
	
	}
	else
	{
		//$sql2 = "select distinct er.RegNo from examregister er, examdate e where er.CourseCode = e.CourseCode and e.programme = '$program' and er.Ayear = $year  and er.RegNo like '%/%/%' order by er.RegNo asc ";
		if($program == "Bachelor of Science in Nursing (Post Basic) Yr2 PAED" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT" || $program == "University Certificate in Midwifery" || $program == "Bachelor of Science in Nursing (Post Basic) Yr1" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MID" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH")
		{
			
				$sql2 = "select distinct examregister.RegNo from examregister 
		INNER JOIN examdate 
		ON  examregister.CourseCode = examdate.CourseCode
		INNER JOIN student
		ON examregister.RegNo = student.RegNo
		where examdate.programme = '$program' and examregister.Ayear = $year and examdate.assessment_status = $cat and examregister.RegNo like '%/%/%' order by student.Name asc ";
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
	}
	
	//die($sql2);
	
    $result2 = mysql_query($sql2);
    $count = 1;
   $badseed = 0;
   $tracker2 = 0;
	$count2rep = 0;
	$count2ref=0;
	
 // die($sql2);
    while($row = mysql_fetch_array($result2, MYSQL_ASSOC))
    {
        $regno= $row['RegNo'];
		//$norepeat = 0;
        
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
//cha scores

if($semister != "Semester I")
	{
		//die("am here");
       // $sql3 = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc";
	   $sql3 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester I' and e.programme = '$program' and assessment_status = 5 ORDER BY e.CourseCode";
	   $sem1check = mysql_query($sql3);
	   $numrolls = mysql_num_rows($sem1check);
		$this->scores($sql3, $regno, $fill,$year);
		
		if($numrolls != 0)
		{
		
		 $this->Cell(1,7,'',1,0,'C',$fill);
		}
		
		$sql3 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester II' and e.programme = '$program' and assessment_status = 5 ORDER BY e.CourseCode";
		$this->scores($sql3, $regno, $fill,$year);
		$inc_status = $this->inc($sql3, $regno, $fill,$year);
		
        
	}
	else
	{
		$sql3 = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year  and e.programme = '$program' order by e.CourseCode asc";	
		$this->scores($sql3, $regno, $fill,$year);
		$inc_status = $this->inc($sql3, $regno, $fill,$year);
		
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
	  
	  	
		if($semister != "Semester I")
	{
         $sqlmin2 = "select MIN(ExamScore) as year4 from examresult 
where AYear = $year and RegNo = '$regno' and ExamCategory = $cat  ";
	
	         $sqlminclinical = "select MIN(e.ExamScore) as lowestclinical from examresult e, clinical_courses c
where e.CourseCode =  c.CourseCode and e.AYear = $year and RegNo = '$regno' and e.ExamCategory = $cat ";
        
            
           
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
		$resultminclinical = mysql_query($sqlminclinical);
        //die($sqlminb);	
			///
	    while($rowminclinical = mysql_fetch_array($resultminclinical, MYSQL_ASSOC))
            {
                 $lowestmarkclinical = $rowminclinical['lowestclinical'];
                 
            }	
			
	  
      
    if($hist =='(Sus)' || $trim == 'DF' || $hist == 'DF' || $hist == '(WD)' || $hist == '(WD/P)' || $history == '(WD/M)' || $history == '(WD/V)' || $hist == 'NP' || $trim2 == 'NP' || $trim3 == 'NP' || $trim4 == 'NP' || $trim5 == 'NP' || $trim6 == 'NP' || $hist == 'INC' || $hist == '(DCD)' )
    {
        $this->Cell(6,7,'',1,0,'R',$fill);
    
    }
    else if ($avg >= 69.5)
		{
		$this->SetFont('','B',9);
                        
                                                 
            $this->Cell(6,7,number_format($avg),1,0,'R',$fill);
		$this->SetFont('','',9);
		} 
	else if($lowestmark == '' || $lowestmark ==0)
		{
			
		$this->Cell(6,7,'',1,0,'R',$fill);	
			
		}
		else
		{
		 $this->Cell(6,7,number_format($avg),1,0,'R',$fill);

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
            $this->Cell(14,7,'WH',1,0,'R',$fill);
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
            $this->Cell(14,7,'SUS',1,0,'R',$fill);
             if($sex == 'F')
              {
                $countsus +=1;
              }
              else
              {
                $countsusm +=1;
              
              }
    
        }
		 else if($hist =='(SUS)')
        {
            $this->Cell(14,7,'SUS',1,0,'R',$fill);
             if($sex == 'F')
              {
                $countsus +=1;
              }
              else
              {
                $countsusm +=1;
              
              }
    
        }
		 else if($hist == '(TR)')
        {
            $this->Cell(14,7,'TR',1,0,'R',$fill);
             if($sex == 'F')
              {
                $counttr +=1;
              }
              else
              {
                $counttrm +=1;
              
              }
		}
		else if($hist == '(WD)' || (strpos($hist,'(WD/M)'))   || $hist == '(WD/P)' || $hist == '(WD/V)')
        {
			
			if($hist == '(WD/P)')
			{
            $this->Cell(14,7,'WD/P',1,0,'R',$fill);
			}
			//else if($hist == '(WD/M)')
			else if(strpos($hist,'(WD/M)'))
			{
            $this->Cell(14,7,'WD/M',1,0,'R',$fill);
			}
			else if($hist == '(WD/V)')
			{
            $this->Cell(14,7,'WD/V',1,0,'R',$fill);
			}
			else
			{
			$this->Cell(14,7,'WD',1,0,'R',$fill);	
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
            $this->Cell(14,7,'INC',1,0,'R',$fill);
             if($sex == 'F')
              {
                $countinc +=1;
              }
              else
              {
                $countincm +=1;
				
              
              }
		}
		else if($hist == '(DCD)')
        {
            $this->Cell(14,7,'DCD',1,0,'R',$fill);
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
            $this->Cell(14,7,'DF',1,0,'R',$fill);
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
            $this->Cell(14,7,'CP',1,0,'R',$fill);
             if($sex == 'F')
              {
                $countcp +=1;
              }
              else
              {
                $countcpm +=1;
              
              }
    
        }
			
		else if($avg <=49)
		{
			
			$this->Cell(14,7,'FW',1,0,'R',$fill);
			//$this->Cell(14,7,'INC',1,0,'R',$fill);
             if($sex == 'F')
              {
                $countfw +=1;
              }
              else
              {
                $countfwm +=1;
				
              
              }
			
			
		}
        
		else if($inc_status == 1)
		{
			
			$this->Cell(14,7,'INC',1,0,'R',$fill);
			//$this->Cell(14,7,'INC',1,0,'R',$fill);
             if($sex == 'F')
              {
                $countinc +=1;
              }
              else
              {
                $countincm +=1;
				
              
              }
			
			
		}
        
        else if($lowestmark  > 49.4)
        {   
	       
            $this->Cell(14,7,'PP',1,0,'R',$fill);
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
				$this->Cell(14,7,'INC',1,0,'R',$fill);
				
				 if($sex == 'F')
              {
                $countinc +=1;
              }
              else
              {
                $countincm +=1;
			  }
				
			}
			
			else if(($countref >= 1) && ($lowestmarkclinical < 49.5 ) && ($program != "Bachelor of Science in Nursing (Post Basic) Yr1"))
				{
					
					if($hist == 'R2(SW1,2)')		 					
					{
						$this->Cell(14,7,'FW',1,0,'R',$fill);	
					 if($sex == 'F')
              		{
                		$countfwf +=1;
             		 }
             		 else
              		{
               		   $countfwm +=1;
              
              		}		
					}
					else
					{
					$this->Cell(14,7,'',1,0,'R',$fill);
					if($sex == 'F')
              		{
                		$count2rep +=1;
             		 }
             		 else
              		{
               		   $countrepm +=1;
              
              		}	
					}	
				}
		
		
		
		
		
			else if(($countref >= 1) && ($hist =='(R1)') && ($program == "Bachelor of Science in Nursing (Post Basic) Yr1"))
				{
					$this->Cell(14,7,'FW',1,0,'R',$fill);
					if($sex == 'F')
              		{
                		$countfw +=1;
             		 }
             		 else
              		{
               		   $countfwm +=1;
              
              		}	
					
				}
			else
			{
				
				
				if($countref >= 3 && $program != "Bachelor of Science in Nursing and Midwifery (Generic) Yr1")
				{
					$this->Cell(14,7,'REP',1,0,'R',$fill);
					if($sex == 'F')
              		{
                		$count2rep +=1;
             		 }
             		 else
              		{
               		   $countrepm +=1;
              
              		}	
					
				}
				else if($countref >= 4 && $program == "Bachelor of Science in Nursing and Midwifery (Generic) Yr1")
				{
					$this->Cell(14,7,'REP',1,0,'R',$fill);
					if($sex == 'F')
              		{
                		$count2rep +=1;
             		 }
             		 else
              		{
               		   $countrepm +=1;
              
              		}	
					
				}
				
				
				else
				{
					
					$this->Cell(14,7,'REF',1,0,'R',$fill);
					$notrepeat +=1;
				
            
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
        }
        else if($countref > 3)
        {
             $this->Cell(14,7,'REP',1,0,'R',$fill);
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
            $notrepeat =0;
            $badseed = 0;
            $count +=1; 
	//$countrep = 0;
         
        
        

    }
   
   

$this->SetFont('','B',9);
//statistics

$this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(35,7,'',1,0,'L',$fill);
                        $this->Cell(73,7,'Highest Score',1,0,'L',$fill);
                         //$this->Cell(6,7,'',1,0,'L',$fill);
                        //$this->Cell(12,7,'',1,0,'L',$fill);
	 if($semister != "Semester I")
	{

//$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc ";



$sql3 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester I' and e.programme = '$program' and assessment_status = 5 order by e.CourseCode asc";
	   $sem1check = mysql_query($sql3);
	   $numrolls = mysql_num_rows($sem1check);
		//sem1 avarage
		 //$resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($sem1check, MYSQL_ASSOC))
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
                       $avg = $rowcst4['avg'];
                       

                      $this->Cell(14.5,7,number_format($avg),1,0,'R',$fill);
                    }
        }
		
		
		if($numrolls != 0)
		{
		
		 $this->Cell(1,7,'',1,0,'C',$fill);
		}
		
		$sql32 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester II' and e.programme = '$program' and assessment_status = 5 order by e.CourseCode asc";

//sem2 avarage
		 //$resulti = mysql_query($sqli);
		 $sem2check = mysql_query($sql32);
	   $numrolls = mysql_num_rows($sem2check);
        while($rowi = mysql_fetch_array($sem2check, MYSQL_ASSOC))
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
                       $avg = $rowcst4['avg'];
                       

                      $this->Cell(14.5,7,number_format($avg),1,0,'R',$fill);
                    }
        }





	}
	else
	{
		$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.programme = '$program' order by e.CourseCode asc ";
		
		
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
                       

                      $this->Cell(14.5,7,number_format($high),1,0,'R',$fill);
                    }
        }
		
		
		
	}
        
	

$this->Ln(7);
//array(4,35,49,7,12);
$this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(35,7,'',1,0,'L',$fill);
                        $this->Cell(73,7,'Lowest Score',1,0,'L',$fill);
                         //$this->Cell(6,7,'',1,0,'L',$fill);
                        //$this->Cell(12,7,'',1,0,'L',$fill);
 if($semister != "Semester I")
	{
//$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc ";


$sql3 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester I' and e.programme = '$program' and assessment_status = 5 order by e.CourseCode asc";
	   $sem1check = mysql_query($sql3);
	   $numrolls = mysql_num_rows($sem1check);
		//sem1 avarage
		 //$resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($sem1check, MYSQL_ASSOC))
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
                       $avg = $rowcst4['avg'];
                       

                      $this->Cell(14.5,7,number_format($avg),1,0,'R',$fill);
                    }
        }
		
		
		if($numrolls != 0)
		{
		
		 $this->Cell(1,7,'',1,0,'C',$fill);
		}
		
		$sql32 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester II' and e.programme = '$program' and assessment_status = 5 order by e.CourseCode asc";

//sem2 avarage
		 //$resulti = mysql_query($sqli);
		 $sem2check = mysql_query($sql32);
	   $numrolls = mysql_num_rows($sem2check);
        while($rowi = mysql_fetch_array($sem2check, MYSQL_ASSOC))
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
                       $avg = $rowcst4['avg'];
                       

                      $this->Cell(14.5,7,number_format($avg),1,0,'R',$fill);
                    }
        }





	}
	else
	{
	$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year  and e.programme = '$program' order by e.CourseCode asc ";
	
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
                       

                      $this->Cell(14.5,7,number_format($low),1,0,'R',$fill);
                    }
        }	
		
	}
        
        $this->Ln(7);
                        $this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(35,7,'',1,0,'L',$fill);
                        $this->Cell(73,7,'Average Score',1,0,'L',$fill);
                        // $this->Cell(6,7,'',1,0,'L',$fill);
                        //$this->Cell(12,7,'',1,0,'L',$fill);
	if($semister != "Semester I")
	{
		
		
//$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc ";

 $sql3 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester I' and e.programme = '$program' and assessment_status = 5 order by e.CourseCode asc";
	   $sem1check = mysql_query($sql3);
	   $numrolls = mysql_num_rows($sem1check);
		//sem1 avarage
		 //$resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($sem1check, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            $cat= $rowi['assessment_status'];
        
   $sql4st = "select AVG(ExamScore) as avg from examresult where CourseCode = '$course' and AYear = $year and ExamCategory = $cat and ExamScore <> '' ";
                //die($sql4st);
                   $result4st = mysql_query($sql4st) or die(mysql_error());
                   //$resul = mysql_fetch_assoc( $result4st);
                   
                    while($rowcst4 = mysql_fetch_array($result4st, MYSQL_ASSOC))
                    {
                        //$high = $rowc['high'];
                       $avg = $rowcst4['avg'];
                       

                      $this->Cell(14.5,7,number_format($avg),1,0,'R',$fill);
                    }
        }
		
		
		if($numrolls != 0)
		{
		
		 $this->Cell(1,7,'',1,0,'C',$fill);
		}
		
		$sql32 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester II' and e.programme = '$program' and assessment_status = 5 order by e.CourseCode asc";

//sem2 avarage
		 //$resulti = mysql_query($sqli);
		 $sem2check = mysql_query($sql32);
	   $numrolls = mysql_num_rows($sem2check);
        while($rowi = mysql_fetch_array($sem2check, MYSQL_ASSOC))
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
                       

                      $this->Cell(14.5,7,number_format($avg),1,0,'R',$fill);
                    }
        }




	}
	else
	{
		$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year  and e.programme = '$program' order by e.CourseCode asc ";
		
		
		
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
                       

                      $this->Cell(14.5,7,number_format($avg),1,0,'R',$fill);
                    }
        }
		
		
		
	}
	// total fail per module
		/*
		 $this->Ln(7);
                    $this->Cell(67,7,'Total Number Referred',1,0,'L',$fill);
                         
	if($semister != "Semester I")
	{
		
		
//$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc ";

 $sql3 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester I' and e.programme = '$program' and assessment_status = 5 order by e.CourseCode asc";
	   $sem1check = mysql_query($sql3);
	   $numrolls = mysql_num_rows($sem1check);
		//sem1 avarage
		 //$resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($sem1check, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            $cat= $rowi['assessment_status'];
        
   $sql4st = "select AVG(ExamScore) as avg from examresult where CourseCode = '$course' and AYear = $year and ExamCategory = $cat and ExamScore <> '' ";
                //die($sql4st);
                   $result4st = mysql_query($sql4st) or die(mysql_error());
                   //$resul = mysql_fetch_assoc( $result4st);
                   
                    while($rowcst4 = mysql_fetch_array($result4st, MYSQL_ASSOC))
                    {
                        //$high = $rowc['high'];
                       $avg = $rowcst4['avg'];
                       

                      $this->Cell(14.5,7,number_format($avg),1,0,'R',$fill);
                    }
        }
		
		
		if($numrolls != 0)
		{
		
		 $this->Cell(1,7,'',1,0,'C',$fill);
		}
		
		$sql32 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester II' and e.programme = '$program' and assessment_status = 5 order by e.CourseCode asc";

//sem2 avarage
		 //$resulti = mysql_query($sqli);
		 $sem2check = mysql_query($sql32);
	   $numrolls = mysql_num_rows($sem2check);
        while($rowi = mysql_fetch_array($sem2check, MYSQL_ASSOC))
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
                       $avg = $rowcst4['avg'];
                       

                      $this->Cell(14.5,7,number_format($avg),1,0,'R',$fill);
                    }
        }




	}
	else
	{
		$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year  and e.programme = '$program' order by e.CourseCode asc ";
		
		
		
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
                       $avg = $rowcst4['avg'];
                       

                      $this->Cell(14.5,7,number_format($avg),1,0,'R',$fill);
                    }
        }
		
		
		
	}

       */     //die($sql3);
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
                        $this->Cell(16,7,'PP',1,0,'L',$fill);
                        $this->Cell(16,7,'DF',1,0,'L',$fill);
                         $this->Cell(16,7,'REF',1,0,'L',$fill);
                        $this->Cell(16,7,'REP',1,0,'L',$fill);
                        $this->Cell(16,7,'WD',1,0,'L',$fill);
                        $this->Cell(16,7,'FW',1,0,'L',$fill);
                         $this->Cell(16,7,'SUS',1,0,'L',$fill);
                        $this->Cell(16,7,'DM',1,0,'L',$fill);
                        $this->Cell(16,7,'TR',1,0,'L',$fill);
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
                      
                         $this->Cell(16,7,number_format($counttr),1,0,'L',$fill);
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
                      
                        $this->Cell(16,7,number_format($counttrm),1,0,'L',$fill);
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
                       
                        $this->Cell(16,7,$counttrm + $counttr,1,0,'L',$fill);
                        $this->Cell(16,7,$countwhm + $countwh,1,0,'L',$fill);
                        $this->Cell(16,7,$countincm + $countinc,1,0,'L',$fill);
						 $this->Cell(16,7,$countdcdm + $countdcd,1,0,'L',$fill);
						 
						$this->overroll_summary($program,$total,$countppm,$countpp,$countdfm,$countdf,$countrefm,$count2ref,$countrepm,$count2rep,$countwdm,$countwd,$countsusm,$countsus,$countsusm,$countsus,$counttrm,$counttr,$countfwm,$countfw,$countwhm,$countwh,$countincm,$countinc,$countdcdm,$countdcd);
			
					 
}

/*
 Function overroll_summary1($program,$total,$countdistm,$countdist,$countcredm,$countcred,$countpassm,$countpass,$count2ref,$countrefm,$countwdm,$countwd,$countfwf,$countfwm,$countsusm,$countsusf,$countrepf,$countrepm,$countnpm,$countnp,$countincm,$countinc)
				 {
					$total = $total;
					$dis = $countdistm+$countdist;
                    $cr = $countcredm+$countcred;
                    $p = $countpassm+$countpass;   
                    $pp = $countpassm+$countpass; 
                    $ref = $countrefm+$count2ref;
					$wd = $countwdm+$countwd;
                    $fw = $countfwf+$countfwm;    
                    $sus = $countsusm+$countsusf;
                     $rep = $countrepf+$countrepm;					
                     $inc = $countincm+$countinc;  
                       
                        
                          if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 PAED')
		{
			$program = 'Bachelor of Science in Nursing (Post Basic) Yr2 PAEDS';
			
		}


                   $sql= "UPDATE `examsummary` SET `prog` = '$program',`DIS` = '$dis' ,`CR` = '$cr' ,`PP` = '$pp' ,`P` = '$p' ,`CP` = '' ,`DF` = '' ,`REP` = '$rep' ,`REF` = '$ref' ,`FW` = '$fw' ,`WD` = '$wd' ,`TR` = '' ,`INC` = '$inc',`SUS` = '$sus' ,`total` = '$total' Where prog = '$program'";	

                  mysql_query($sql) or die(mysql_error());
                         
				 }	
				 */
function  overroll_summary($program,$total,$countppm,$countpp,$countdfm,$countdf,$countrefm,$count2ref,$countrepm,$count2rep,$countwdm,$countwd,$countsusm,$countsus,$countsusm,$countsus,$counttrm,$counttr,$countfwm,$countfw,$countwhm,$countwh,$countincm,$countinc,$countdcdm,$countdcd)
{
	
	$pp = $countppm + $countpp;
	$ref = $countrefm + $count2ref;
	$df = $countdfm + $countdf;
	$rep = $countrepm + $count2rep;
	$wd = $countwdm + $countwd;
	$dcd = $countdcdm + $countdcd;
	$fw = $countfwm + $countfw;
	$inc = $countincm + $countinc;
	$sus = $countsusm + $countsus;
	$rep = $count2rep + $countrepm;
	$tr= $counttrm + $counttr;
	
	if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 PAED')
		{
			$program = 'Bachelor of Science in Nursing (Post Basic) Yr2 PAEDS';
			
		}
	
	
	
	//$sql= "INSERT INTO `examsummary` (`prog` ,`DIS` ,`CR` ,`PP` ,`P` ,`CP` ,`DF` ,`REP` ,`REF` ,`FW` ,`WD` ,`DCD` ,`INC` ,`total`) VALUES(
//'$program', '', '', '$pp', '', '', '$df', '$rep', '$ref', '', '$wd', '', '', '$total')";	

$sql= "UPDATE `examsummary` SET `prog` = '$program',`DIS` = '' ,`CR` = '' ,`PP` = '$pp' ,`P` = '$pp' ,`CP` = '' ,`DF` = '$df' ,`REP` = '$rep' ,`TR` = '$tr' ,`REF` = '$ref' ,`FW` = '$fw' ,`WD` = '$wd' ,`DCD` = '$dcd' ,`INC` = '$inc',`SUS` = '$sus' ,`total` = '$total' Where prog = '$program'";	
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
			else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 MID')
			{
				$prefix = 'MID';
			
			}
			else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH')
			{
				$prefix = 'ADULT-HE';
			
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
	$trim7 =  trim($hist,'(TR), ');
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
      
    if($hist =='(Sus)' || $trim == 'DF' || $hist == 'DF' || $hist == '(WD)' || $hist == '(WD/P)' || $hist == '(WD/M)' || $hist == '(WD/V)' || $hist == 'NP' || $trim2 == 'NP' || $trim3 == 'NP' || $trim4 == 'NP' || $trim5 == 'NP' || $trim6 == 'NP' || $hist == 'INC' )
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
         else if($hist =='(TR)' || $trim == '(TR)')
        {
            $this->Cell(16,7,'TR',1,0,'R',$fill);
             if($sex == 'F')
              {
                $counttr +=1;
              }
              else
              {
                $counttrm +=1;
              
              }
    
        }
        if($hist == '(WD)' || $hist == '(WD/P)' || $hist == '(WD/M)' || $hist == '(WD/V)')
        {
			if ($hist == '(WD)/P')
			{
            $this->Cell(16,7,'WD/P',1,0,'R',$fill);
			}
			else if ($hist == '(WD)/M')
			{
            $this->Cell(16,7,'WD/M',1,0,'R',$fill);
			}
			else if ($hist == '(WD)/V')
			{
            $this->Cell(16,7,'WD/V',1,0,'R',$fill);
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
			 else if($countref >=1 && $hist =='(R1)')
				{
					$this->Cell(14,7,'FW',1,0,'R',$fill);
					if($sex == 'F')
              		{
                		$countfw +=1;
             		 }
             		 else
              		{
               		   $countfwm +=1;
              
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
$this->Cell(273,7,'SUMMARY OF RESULTS',1,0,'L',$fill);
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
						$this->Cell(18,7,'TR',1,0,'L',$fill);
                        $this->Cell(18,7,'REP',1,0,'L',$fill);
                        $this->Cell(18,7,'CP',1,0,'L',$fill);
                        $this->Cell(18,7,'WH',1,0,'L',$fill);

                        
                    $sql4f = "select count(distinct examregister.RegNo) as female from course c, examregister
INNER JOIN student ON (examregister.RegNo = student.RegNo)
where examregister.CourseCode= c.CourseCode and examregister.AYear = $year
and c.Programme = $progcode and  c.prefix = '$prefix' and examregister.RegNo LIKE '%/%/%' and student.Sex = 'F'";
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
                      
                         $this->Cell(18,7,number_format($counttr),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countwh),1,0,'L',$fill);
                          $this->Cell(18,7,number_format($countinc),1,0,'L',$fill);
                        
$sql4f = "select count(distinct examregister.RegNo) as male from course c, examregister
INNER JOIN student ON (examregister.RegNo = student.RegNo)
where examregister.CourseCode= c.CourseCode and examregister.AYear = $year
and c.Programme = $progcode and  c.prefix = '$prefix' and examregister.RegNo LIKE '%/%/%' and student.Sex = 'M'";
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
                      
                        $this->Cell(18,7,number_format($counttrm),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countwhm),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countinc),1,0,'L',$fill);
//$this->AddPage('L');
$sql4f = "select count(distinct examregister.RegNo) as total from course c, examregister
INNER JOIN student ON (examregister.RegNo = student.RegNo)
where examregister.CourseCode= c.CourseCode and examregister.AYear = $year
and c.Programme = $progcode and  c.prefix = '$prefix' and examregister.RegNo LIKE '%/%/%' ";
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
                       
                        $this->Cell(18,7,$counttr + $counttrm,1,0,'L',$fill);
                        $this->Cell(18,7,$countwhm + $countwh,1,0,'L',$fill);
                        $this->Cell(18,7,$countincm + $countinc,1,0,'L',$fill);
						
						
						
		$this->overroll_summary($program,$total,$countppm,$countpp,$countdfm,$countdf,$countrefm,$count2ref,$countrepm,$count2rep,$countwdm,$countwd,$countsusm,$countsus,$countsusm,$countsus,$counttrm,$counttr,$countfwm,$countfw,$countwhm,$countwh,$countincm,$countinc,$countdcdm,$countdcd);				
             
}

//end mature entry

//year 4 recommendation for notification of results function

function recommendation($recomm,$regno,$semister,$year)
{
	
 $sql = "REPLACE INTO Recommendation VALUES('$regno','$semister','$recomm',$year)";	
 //die($sql);
	mysql_query($sql) or die(mysql_error());
	
	
	
}


// end  year 4 recommendation


//Year 4 function


//Colored table
function year4($year,$program,$semister)
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
	
	
	
	
    
//$sql2 = "select distinct er.RegNo from examregister er, examdate e where er.CourseCode = e.CourseCode and e.programme = '$program' and er.Ayear = $year  and er.RegNo like '%/%/%' order by er.RegNo asc ";

 
	$sql2 = "select distinct examregister.RegNo from examregister 
INNER JOIN  examdate 
ON examregister.CourseCode = examdate.CourseCode
INNER JOIN student
ON examregister.RegNo = student.RegNo
WHERE  examdate.programme = '$program' and examregister.Ayear = $year and examdate.assessment_status = $cat and examregister.RegNo like '%/%/%' order by student.Name asc";

    $result2 = mysql_query($sql2);
    $count = 1;
     $badseed = 0;
    $tracker2 = 0;
    $countdist = 0;
    $countcred = 0;
    $countpass = 0;
    $count2ref = 0;
    
    
    $countdistm = 0;
    $countcredm = 0;
    $countpassm = 0;
    $countrefm = 0;
    $countwdm = 0;
	$countfdm = 0;
	$countfwf = 0;
	$countrepf = 0;
	$countrepm = 0;
	$countsusf = 0;
	$countsusm = 0;
	$countinc = 0;
	$countincm = 0;
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
          
                    $this->nameyear4($regno,$fill);
               
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
        $sql3 = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year_previous and e.assessment_status = 5 and e.programme = '$program_previous' order by e.CourseCode asc ";
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
		if ($avg > 69.4)
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
    
    
        $sqlavg = "select AVG(ex.ExamScore) as avg from examresult ex, examdate e where ex.CourseCode  = e.CourseCode and e.programme = '$program' and ex.RegNo = '$regno' and ex.AYear = $year  and ex.ExamCategory = 5 and e.assessment_status = 5";
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
        
	/*	$sqlminclinical = "select MIN(e.ExamScore) as year4 from examresult e, clinical_courses c
where e.CourseCode =  c.CourseCode and e.AYear = $year and RegNo = '$regno' and e.ExamCategory = $cat ";
        
		$resultminclinical = mysql_query($sqlminclinical);
		
         while($rowminb = mysql_fetch_array($resultminb, MYSQL_ASSOC))
            {
                 $lowestmark_yr4_clinical = $rowminb['year4'];
                 
             */
        
        
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
			else if($hist == '(WD/P)')
			{
				
				$this->Cell(18,7,'WD/P',1,0,'R',$fill); 
               if($sex == 'F')
              {
                $countwd +=1;
              }
              else
              {
                $countiwdm +=1;
              
              }
			  
			}
			  
			else if($hist == '(DCD)')
			{
				
				$this->Cell(18,7,'DCD',1,0,'R',$fill);
				
               if($sex == 'F')
              {
                $countdcd +=1;
              }
              else
              {
                $countdcdm +=1;
              
              }

			
			}
			else if($hist == '(WD/M)')
			{
				
				$this->Cell(18,7,'WD/M',1,0,'R',$fill); 
               if($sex == 'F')
              {
                $countwd +=1;
              }
              else
              {
                $countiwdm +=1;
              
              }
				
			}
			else if($hist == '(WD/V)')
			{
				
				$this->Cell(18,7,'WD/V',1,0,'R',$fill); 
               if($sex == 'F')
              {
                $countwd +=1;
              }
              else
              {
                $countiwdm +=1;
              
              }
				
			}
            else if($inc == 1)
            {
				   if( strpos($hist, '(SUS)') !== false ) 
				   {
					 $this->Cell(18,7,'SUS',1,0,'R',$fill); 
			        $this->recommendation("SUSPENSION",$regno,$semister,$year);
			   
                     if($sex == 'F')
                       {
                        $countsusf +=1;
                       }
                     else
                       {
                       $countsusm +=1;
         
                        }  
				   }
				   else 
				   {
		   				
                    $this->Cell(18,7,'INC',1,0,'R',$fill); 
			        $this->recommendation("INCOMPLETE",$regno,$semister,$year);
			   
                     if($sex == 'F')
                       {
                       $countinc +=1;
                       }
                     else
                       {
                       $countincm +=1;
                       }
				   }	
            }
            else if( $lowestmark_yr4_clinical >= 70 && $lowestmark_yr4 >= 70 && $avg > 74.4 && $avg2 > 74.4 && $hist != '(AM 1)')
            {
              $this->Cell(18,7,'DIS',1,0,'R',$fill);
			  $this->recommendation("DISTINCTION",$regno,$semister,$year);
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
			  $this->recommendation("CREDIT",$regno,$semister,$year);
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
			  $this->recommendation("PASS",$regno,$semister,$year);
              if($sex == 'F')
              {
                $countpass +=1;
              }
              else
              {
                $countpassm +=1;
              }
            }
           
              else if($lowestmark_yr4 < 50 && $lowestmark_yr4_clinical < 50 )
            {
			  //if ($hist != '(AM 1)')
			  if( strpos($hist, '(R2)') !== false ) 
			  {
               $this->Cell(18,7,'FW',1,0,'R',$fill);
			  $this->recommendation("FWITHDRAW",$regno,$semister,$year);
              if($sex == 'F')
              {
                $countfwf +=1;
              }
              else
              {
                $countfwm +=1;
              }
			  }
			 else
			 {
				 $this->Cell(18,7,'REP',1,0,'R',$fill);
			  $this->recommendation("REPEAT",$regno,$semister,$year);
              if($sex == 'F')
              {
                $countrepf +=1;
              }
              else
              {
                $countrepm +=1;
              }
				 
				
			 }
			}  			
            else if($lowestmark_yr4 < 50 && $hist != '(AM 1)')
            {
				 
              $this->Cell(18,7,'REF',1,0,'R',$fill);
			  $this->recommendation("REFERAL",$regno,$semister,$year);
              if($sex == 'F')
              {
                $count2ref +=1;
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
   

$this->SetFont('','B',9);
//statistics

$this->statistics_rpt_yr4($year_previous,$year,$program_previous,$program,$cat);


//statistics
$this->Ln(7);
$this->Ln(7);

$this->Cell(273,7,'SUMMARY OF RESULTS',1,0,'L',$fill);
$this->Ln(7);
                        $this->Cell(50,7,'',1,0,'L',$fill);
                        $this->Cell(25,7,'No. of Stud',1,0,'L',$fill);
                        $this->Cell(18,7,'DIS',1,0,'L',$fill);
                        $this->Cell(18,7,'CR',1,0,'L',$fill);
                         $this->Cell(18,7,'P',1,0,'L',$fill);
                        $this->Cell(18,7,'REF',1,0,'L',$fill);
                        $this->Cell(18,7,'WD/M',1,0,'L',$fill);
                        $this->Cell(18,7,'FW',1,0,'L',$fill);
                         $this->Cell(18,7,'SUS',1,0,'L',$fill);
                        $this->Cell(18,7,'REP',1,0,'L',$fill);
                        $this->Cell(18,7,'TR',1,0,'L',$fill);
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
                        $this->Cell(18,7,number_format($count2ref),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countwd),1,0,'L',$fill);
						$this->Cell(18,7,number_format($countfwf),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countsusf),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countrepf),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($counttr),1,0,'L',$fill);
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
                        $this->Cell(18,7,number_format($countfwm),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countsusm),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countrepf),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($counttrm),1,0,'L',$fill);
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
                        $this->Cell(18,7,$countrefm+$count2ref,1,0,'L',$fill);
                        $this->Cell(18,7,$countwdm+$countwd,1,0,'L',$fill);
                        $this->Cell(18,7,$countfwf+$countfwm,1,0,'L',$fill);
                        $this->Cell(18,7,$countsusm+$countsusf,1,0,'L',$fill);
                        $this->Cell(18,7,($countrepf+$countrepm),1,0,'L',$fill);
                        $this->Cell(18,7,($counttr+$counttrm),1,0,'L',$fill);
                        $this->Cell(18,7,$countnpm+$countnp,1,0,'L',$fill);
                        $this->Cell(18,7,$countincm+$countinc,1,0,'L',$fill);
                         
                       // $this->Cell(18,7,'',1,0,'L',$fill);
					  $countppm= $countpassm;
					  $countpp= $countpass;
					  //$countpass= $countpp;
        		 $this->overroll_summary($program,$total,$countppm,$countpp,$countdfm,$countdf,$countrefm,$count2ref,$countrepm,$count2rep,$countwdm,$countwd,$countsusm,$countsus,$countsusm,$countsus,$counttrm,$counttr,$countfwm,$countfw,$countwhm,$countwh,$countincm,$countinc,$countdcdm,$countdcd);
                 
			  
			  /* 	// HUBERT added this to update the examsumary table, the initial overall summary fn call is commented 	
                 $this->overroll_summary1($program,$total,$countdistm,$countdist,$countcredm,$countcred,$countpassm,$countpass,$count2ref,$countrefm,$countwdm,$countwd,
				 $countfwf,$countfwm,$countsusm,$countsusf,$countrepf,$countrepm,$countnpm,$countnp,$countincm,$countinc);			
				*/        

						
						
						
						
//$this->statistics_rpt($year,$program,$cat);


    //$exam= $line["ExamScore"];
    
 
    
//$this->AddPage('L');

}



//end Year 4
//Mature year 2

//Colored table
function mature_year2($year,$program,$semister)
{

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
    //die($program_previous_code1);
    $program_previous_code = 1005 ;
    
    $sqlpro2 = "select ProgrammeName from program_year where ProgrammeCode = '$program_previous_code'";
	
    $resultpro2 = mysql_query($sqlpro2);
    while($rowpro2= mysql_fetch_array($resultpro2, MYSQL_ASSOC))
    {
        $program_previous= $rowpro2['ProgrammeName'];
    }
    $year_previous = $year - 1;
    
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
	   else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 MID')
    {
        $prefix = 'MID';
    
    }
	else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH')
    {
        $prefix = 'ADULT-HE';
    
    }
    
$sql2 = "select distinct er.RegNo from examregister er, course e where er.CourseCode = e.CourseCode and e.prefix = '$prefix' and er.Ayear = $year  and er.RegNo like '%/%/%' and e.Programme = 10052 order by er.RegNo asc ";
    //die($sql2);
    $result2 = mysql_query($sql2);
    $count = 1;
   $badseed = 0;
   $tracker2 = 0;
    $countdist = 0;
    $countcred = 0;
    $countpass = 0;
    $count2ref = 0;
    $countwd = 0;	
	   
	
    $countdistm = 0;
    $countcredm = 0;
    $countpassm = 0;
    $countrefm = 0;
    $countwdm = 0;
	$incchecker =0;
	$countincm =0;
	$countinc =0;
	$inc =0;
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
          
                    $this->namemature($regno,$fill);
               
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
        $sql3 = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode   and e.Ayear = $year_previous and e.assessment_status = 5 and e.programme = '$program_previous' order by e.CourseCode asc ";
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
				$incchecker =1;
                
                }
            while($rowc = mysql_fetch_array($result4, MYSQL_ASSOC))
            {
                
            $regnob = $rowc['RegNo'];
                
               
                $courseb = $rowc['CourseCode'];
               
                $examscore = $rowc['ExamScore'];
                if($examscore == 0)
                { 
                    $this->Cell(8,7,'--',1,0,'R',$fill);  
					$incchecker =1;
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
        
    // $sqlavg = "select AVG(ex.ExamScore) as avg from examresult ex, examdate e where ex.CourseCode  = e.CourseCode  and ex.RegNo = '$regno' and ex.AYear = $year_previous and ex.ExamCategory = 5 and e.assessment_status = 5 ";
     $sqlavg = "select DISTINCT ex.coursecode, ex.ExamScore as avg from examresult ex, examdate e where ex.CourseCode  = e.CourseCode  and ex.RegNo = '$regno' and ex.AYear = $year_previous and ex.ExamCategory = 5 and e.assessment_status = 5 ";
            
		$total = 0;
	    $ccount = 0;
	    $avg = 0;
	    
	    $resultavg = mysql_query($sqlavg);
	    while($rowavg = mysql_fetch_array($resultavg, MYSQL_ASSOC))
           {
	   
            $total += $rowavg['avg'];
		    $ccount = $ccount +1;
		
            }
	    
	       if ($ccount == 0 or $total == 0)
	         {
	          $avg = 0;
	         }
	       else
	          {
               $avg = $total / $ccount;
	           }
               
			   /* $resultavg = mysql_query($sqlavg);
            
            while($rowavg = mysql_fetch_array($resultavg, MYSQL_ASSOC))
            {
                 $avg = $rowavg['avg'];
            }
          */  
        if($hist =='(Sus)' || $trim == 'DF' || $hist == 'DF' || $hist == '(WD)' || $hist == '(WD/P)' || $history == '(WD/M)' || $history == '(WD/V)' || $hist == 'NP' || $trim2 == 'NP' || $trim3 == 'NP' || $trim4 == 'NP' || $trim5 == 'NP' || $trim6 == 'NP' || $hist == 'INC' || $hist == '(DCD)' )
        {
        $this->Cell(8,7,'',1,0,'R',$fill);
         //$this->Cell(8,7,number_format($avg),1,0,'R',$fill);
         } 
		 elseif($incchecker == 1)
		 {
		 $this->SetFont('','',9);
		 $this->Cell(8,7,'',1,0,'R',$fill);	 
		 }
		elseif ($avg > 69)
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
        $sql3 = "select distinct examdate.CourseCode, examdate.assessment_status  
from examdate 
INNER JOIN course ON (examdate.CourseCode = course.CourseCode)
INNER JOIN submitresult ON (examdate.CourseCode = submitresult.courseCode)
where  examdate.CourseCode = submitresult.courseCode and examdate.Ayear = $year and examdate.assessment_status = 5
and course.prefix Like '%$prefix%' and course.Programme = 10052
  order by examdate.CourseCode asc ";
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
            
            
            if($sqlrows == 0 && $badseed !=1 )
                {
                
                $this->Cell(8,7,'--',1,0,'R',$fill);
				$incchecker =1;
                
                }
            while($rowc = mysql_fetch_array($result4, MYSQL_ASSOC))
            {
                
            $regnob = $rowc['RegNo'];
                
               
                $courseb = $rowc['CourseCode'];
               
                $examscore = $rowc['ExamScore'];
                if($examscore == 0)
                { 
                    $this->Cell(8,7,'--',1,0,'R',$fill);  
					$incchecker =1;
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
    
    
        $sqlavg = "select AVG(ex.ExamScore) as avg from examresult ex where  ex.RegNo = '$regno' and ex.AYear = $year  and ex.ExamCategory = 5";
            //die($sqlavg);
            //die($sql4);
            $resultavg = mysql_query($sqlavg);
            //die($sqlavg);
            
            while($rowavg = mysql_fetch_array($resultavg, MYSQL_ASSOC))
            {
                 $avg2 = $rowavg['avg'];
                 //die($avg2);
            }
			      
        if($hist =='(Sus)' || $trim == 'DF' || $hist == 'DF' || $hist == '(WD)' || $hist == '(WD/P)' || $history == '(WD/M)' || $history == '(WD/V)' || $hist == 'NP' || $trim2 == 'NP' || $trim3 == 'NP' || $trim4 == 'NP' || $trim5 == 'NP' || $trim6 == 'NP' || $hist == 'INC' || $hist == '(DCD)' )
        {
        $this->Cell(8,7,'',1,0,'R',$fill);
         //$this->Cell(8,7,number_format($avg),1,0,'R',$fill);
         } 
		 elseif( $incchecker == 1)
		 {
		 $this->Cell(8,7,'',1,0,'R',$fill);	 
		 }
		elseif ($avg > 69)
		//if ($avg2 > 69)
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
where e.CourseCode = c.CourseCode and e.AYear = $year_previous and e.RegNo = '$regno' and e.ExamCategory = $cat ";
        $resultmin = mysql_query($sqlmin);
        //die($sqlmin);
            
            while($rowmin = mysql_fetch_array($resultmin, MYSQL_ASSOC))
            {
                 $lowestmark_yr3 = $rowmin['year3'];
                 
            }
            
                    /* $sqlminb = "select MIN(e.ExamScore) as year4 from examresult e, clinical_courses c
where e.CourseCode =  c.CourseCode  and c.Programme_Code = $program_previous_code1 and c.prefix = '$prefix' and e.AYear = $year and RegNo = '$regno' and e.ExamCategory = $cat "; */

                        $sqlminb = "select MIN(e.ExamScore) as year4 from examresult e, clinical_courses c
where e.CourseCode =  c.CourseCode  and c.Programme_Code = $program_previous_code1 and e.AYear = $year and RegNo = '$regno' and e.ExamCategory = $cat ";
					   
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
			 if($incchecker ==1)
				{
					$this->Cell(20,7,'INC',1,0,'R',$fill);
					$this->recommendation("INCOMPLETE",$regno,$semister,$year);
              if($sex == 'F')
              {
                $countinc +=1;
              }
              else
              {
                $countincm +=1;
              }
				}
				else if($lowestmark_yr4_clinical > 69.4 && $lowestmark_yr4 >= 70 && $avg > 74.4 && $avg2 > 74.4 )
            {
              $this->Cell(20,7,'DIS',1,0,'R',$fill);
			  $this->recommendation("DISTINCTION",$regno,$semister,$year);
              if($sex == 'F')
              {
                $countdist +=1;
              }
              else
              {
                $countdistm +=1;
              
              }
            }
            else if($lowestmark_yr4_clinical > 64.4 && $lowestmark_yr4 >= 60 && $avg > 64.4 && $avg2 > 64.4 )
            {
              $this->Cell(20,7,'CR',1,0,'R',$fill);
			  $this->recommendation("CREDIT",$regno,$semister,$year);
              if($sex == 'F')
              {
                $countcred +=1;
              }
              else
              {
                $countcredm +=1;
              }
            }
              else if($lowestmark_yr4 >= 50 )
            {
              $this->Cell(20,7,'P',1,0,'R',$fill);
			  $this->recommendation("PASS",$regno,$semister,$year);
               if($sex == 'F')
              {
                $countpass +=1;
              }
              else
              {
                $countpassm +=1;
              }
            }
               
            else if($lowestmark_yr4 < 50 )
            {
				
					
					
              $this->Cell(20,7,'REF',1,0,'R',$fill);
			  $this->recommendation("REFERAL",$regno,$semister,$year);
              if($sex == 'F')
              {
                $count2ref +=1;
              }
              else
              {
                $countrefm +=1;
              }
				
            }
            else if($avg2 < 50 )
            {
              $this->Cell(20,7,'WD',1,0,'R',$fill);
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
        
            $this->Cell(20,7,'',1,0,'R',$fill);
            }
            $this->Ln(7);
            $fill=!$fill;
			$incchecker = 0;
        }            
         $badseed = 0;
        $count +=1; 
		//$incchecker = 0;
         
        
        

    }
   
$this->Ln(5);
$this->SetFont('','B',9);
//statistics

$this->statistics_rpt($year_previous,$year,$program_previous,$program,$cat,$prefix);


//$this->statistics_rpt($year,$program,$cat);


    //$exam= $line["ExamScore"];
    
//statistics
$this->Ln(5);
$this->Ln(7);
$this->Cell(273,7,'SUMMARY OF RESULTS',1,0,'L',$fill);
$this->Ln(7);
                        $this->Cell(50,7,'',1,0,'L',$fill);
                        $this->Cell(35,7,'No. of Stud',1,0,'L',$fill);
                        $this->Cell(18,7,'DS',1,0,'L',$fill);
                        $this->Cell(18,7,'CR',1,0,'L',$fill);
                         $this->Cell(18,7,'P',1,0,'L',$fill);
                        $this->Cell(18,7,'REF',1,0,'L',$fill);
                        $this->Cell(18,7,'WD',1,0,'L',$fill);
                        $this->Cell(18,7,'FW',1,0,'L',$fill);
                         $this->Cell(18,7,'SUS',1,0,'L',$fill);
                        $this->Cell(18,7,'DM',1,0,'L',$fill);
                        $this->Cell(18,7,'WH',1,0,'L',$fill);
                        $this->Cell(18,7,'TR',1,0,'L',$fill);
                        $this->Cell(18,7,'INC',1,0,'L',$fill);
                        
                        
                    $sql4f = "select count(distinct examregister.RegNo) as female from course c, examregister
INNER JOIN student ON (examregister.RegNo = student.RegNo)
where examregister.CourseCode= c.CourseCode and examregister.AYear = $year
and c.Programme = $program_previous_code1 and c.prefix = '$prefix' and examregister.RegNo LIKE '%/%/%' and student.Sex = 'F'";
//die($sql4f);
                   $result4f = mysql_query($sql4f) or die(mysql_error());
                    while($rowcst4 = mysql_fetch_array($result4f, MYSQL_ASSOC))
                    {
                       $female = $rowcst4['female'];
                    }
                    
                    
                       
                        
$this->Ln(7);
                        $this->Cell(50,7,'FEMALE',1,0,'L',$fill);
                        $this->Cell(35,7,number_format($female),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countdist),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countcred),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countpass),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($count2ref),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countwd),1,0,'L',$fill);
                        $this->Cell(18,7,'0',1,0,'L',$fill);
                        $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,number_format($counttr),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countinc),1,0,'L',$fill);
                       // $this->Cell(18,7,'',1,0,'L',$fill);
                        
$sql4f = "select count(distinct examregister.RegNo) as male from course c, examregister
INNER JOIN student ON (examregister.RegNo = student.RegNo)
where examregister.CourseCode= c.CourseCode and examregister.AYear = $year
and c.Programme = $program_previous_code1 and c.prefix = '$prefix' and examregister.RegNo LIKE '%/%/%' and student.Sex = 'M'";
//die($sql4f);
                   $result4f = mysql_query($sql4f) or die(mysql_error());
                    while($rowcst4 = mysql_fetch_array($result4f, MYSQL_ASSOC))
                    {
                       $male = $rowcst4['male'];
                    }
                    
                    
                       
                        
$this->Ln(7);
                       $this->Cell(50,7,'MALE',1,0,'L',$fill);
                        $this->Cell(35,7,number_format($male),1,0,'L',$fill);
                       $this->Cell(18,7,number_format($countdistm),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countcredm),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countpassm),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countrefm),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countwdm),1,0,'L',$fill);
                      $this->Cell(18,7,'0',1,0,'L',$fill);
                        $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,number_format($counttrm),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countincm),1,0,'L',$fill);
                        //$this->Cell(18,7,'',1,0,'L',$fill);
//$this->AddPage('L');
$sql4f = "select count(distinct examregister.RegNo) as total from course c, examregister
INNER JOIN student ON (examregister.RegNo = student.RegNo)
where examregister.CourseCode= c.CourseCode and examregister.AYear = $year
and c.Programme = $program_previous_code1 and c.prefix = '$prefix' and examregister.RegNo LIKE '%/%/%' ";
//die($sql4f);
                   $result4f = mysql_query($sql4f) or die(mysql_error());
                    while($rowcst4 = mysql_fetch_array($result4f, MYSQL_ASSOC))
                    {
                       $total = $rowcst4['total'];
                    }
                    
                    
                       
                        
$this->Ln(7);
                        $this->Cell(50,7,'TOTAL',1,0,'L',$fill);
                        $this->Cell(35,7,number_format($total),1,0,'L',$fill);
                        $this->Cell(18,7,$countdistm+$countdist,1,0,'L',$fill);
                        $this->Cell(18,7,$countcredm+$countcred,1,0,'L',$fill);
                         $this->Cell(18,7,$countpassm+$countpass,1,0,'L',$fill);
                        $this->Cell(18,7,$countrefm+$count2ref,1,0,'L',$fill);
                        $this->Cell(18,7,$countwdm+$countwd,1,0,'L',$fill);
                        $this->Cell(18,7,$countfwf+$countfwm,1,0,'L',$fill);
                        $this->Cell(18,7,$countsusm+$countsusf,1,0,'L',$fill);
                         $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,$counttr+$counttrm,1,0,'L',$fill);
                         $this->Cell(18,7,$countinc+$countincm,1,0,'L',$fill);
                        //$this->Cell(18,7,'',1,0,'L',$fill);

             $countppm= $countpassm;
			 $countpp= $countpass;
						
//$this->AddPage('L');
      $this->overroll_summary($program,$total,$countppm,$countpp,$countdfm,$countdf,$countrefm,$count2ref,$countrepm,$count2rep,$countwdm,$countwd,$countsusm,$countsus,$countsusm,$countsus,$countfwm,$countfw,$countwhm,$countwh,$countdcdm,$countdcd,$counttr,$counttrm,$countinc,$countincm);
}


//end Mature year 2

// Year 4 Header

function year4_header()
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
    $this->Ln(12);
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
    $sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year-1 and e.assessment_status = 5 and e.programme = '$program_previous' order by e.CourseCode asc ";
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


//end Year 4 Header

// mature year 2 Header

function mature_year2_header()
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
        if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 COM')
        {
        $prog = trim($program, 'Yr2 COM');
    
        $this->Cell($w,9,$prog.' Year 2: Community Health Nursing',0,0,'C',0);
        }
        else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 EDU')
        {
            $prog = trim($program, 'Yr2 EDU');
    
            $this->Cell($w,9,$prog.' Year 2 : Nursing Education',0,0,'C',0);
        }
        else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 MGT')
        {
            $prog = trim($program, 'Yr2 MGT');
    
            $this->Cell($w,9,$prog.' Year 2 : Health Service Management',0,0,'C',0);
        }
		 else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 PAED')
        {
            $prog = trim($program, 'Yr2 PEAD');
    
            $this->Cell($w,9,$prog.' Year 2 : Paediatric Nursing',0,0,'C',0);
        }
		else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 MID')
        {
            $prog = trim($program, 'Yr2 MID');
    
            $this->Cell($w,9,$prog.' Year 2 : Midwifery',0,0,'C',0);
        }
		else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH')
        {
            $prog = trim($program, 'Yr2 ADULT-HEALTH');
    
            $this->Cell($w,9,$prog.' Year 2 : Adult Health',0,0,'C',0);
        }
        //$this->Cell($w,9,$program.' End of Year',0,0,'C',0);
        
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
    $this->SetFont('','B',9);
 
 


  

//Header

 
   
    $w=array(6,35,50,10,18);
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
    
    
    $program_previous_code = 1005;
    
    $sqlpro2 = "select ProgrammeName from program_year where ProgrammeCode = $program_previous_code";
    $resultpro2 = mysql_query($sqlpro2);
    while($rowpro2= mysql_fetch_array($resultpro2, MYSQL_ASSOC))
    {
        $program_previous= $rowpro2['ProgrammeName'];
    }
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
	  else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 MID')
    {
        $prefix = 'MID';
    
    }
	else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH')
    {
        $prefix = 'ADULT-HE';
    
    }
    $times = 1;
    $times2 = 1;
    $sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year-1   and e.assessment_status = 5 and e.programme = '$program_previous' order by e.CourseCode asc ";
        //die($sqli);
        $resulti = mysql_query($sqli);
        $this->SetTextRotation(90);
        $space = 130;
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            //$cat= $rowb['assessment_status'];
            
           // $this->Rotate(90);
            
             //$this->text(166,175,"DATE RECEIVED");
           //$this->Cell(12,30,$course,1,0,'L',1);
     $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
            //$this->Write(0,"hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh");
            //$this->Cell(7,12,'here you',1,0,'R',0);  
              $this->Text($space,52,$course);
            $times +=1;
            $space = $space + 8;
        }
        //$this->Write(0,'AVG Yr1');
        $this->Text($space,52,'AVG Yr1');
        // $this->Rotate(0);
           $this->SetTextRotation(0);
           
         //$this->Cell(12,7,'AVG',1,0,'C',1);
       
        //line separator
         //$this->SetFillColor(0,0,0);
        $this->Cell(2,7,'',0,0,'',0);
         $this->SetFillColor(57,127,145);
         //close line separator
         
         
           $sqli2 = "select distinct examdate.CourseCode, examdate.assessment_status  
from examdate 
INNER JOIN course ON (examdate.CourseCode = course.CourseCode)
INNER JOIN submitresult ON (examdate.CourseCode = submitresult.courseCode)
where  examdate.CourseCode = submitresult.courseCode and examdate.Ayear = $year and examdate.assessment_status = 5
and course.prefix Like '%$prefix%' and course.Programme = 10052
  order by examdate.CourseCode asc ";
        //die($sqli2);
        $resulti2 = mysql_query($sqli2);
        $this->SetTextRotation(90);
        $space2 = $space + 10;
        while($rowi2 = mysql_fetch_array($resulti2, MYSQL_ASSOC))
        {
            $course2= $rowi2['CourseCode'];
            //$cat= $rowb['assessment_status'];
             $this->Text($space2,52,$course2);
            $times2 +=1;
            $space2 = $space2 + 8;
           
        }
        $this->Text($space2,52,'AVG Yr2');
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
        $this->Cell(20,7,'RECOMM',1,0,'C',1);
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',9);
     $this->Ln(7);
      
} 


// end mature year 2 header

//statistics function

function statistics_rpt($year_previous,$year,$program_previous,$program,$cat,$prefix)
{



$this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(35,7,'',1,0,'L',$fill);
                        $this->Cell(50,7,'Highest Score',1,0,'L',$fill);
                         $this->Cell(10,7,'',1,0,'L',$fill);
                        $this->Cell(18,7,'',1,0,'L',$fill);
                        
$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year_previous   and e.assessment_status = 5 and e.programme = '$program_previous' order by e.CourseCode asc ";
        $resulti = mysql_query($sqli);
       //die($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            $cat= $rowi['assessment_status'];
			
        if($program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 PAED" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MID" || "Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH" )
		{
			
			 $sql4st = "select MAX(examresult.ExamScore) as avg from examregister 
INNER JOIN course ON (examregister.CourseCode = course.CourseCode)
INNER JOIN examresult ON (examregister.RegNo = examresult.RegNo)
 where 
course.prefix = '$prefix' 
and examregister.Ayear = $year 
and examregister.RegNo like '%/%/%' 
and course.Programme = 10052 
and examresult.AYear=$year_previous
and examresult.CourseCode = '$course'
and examresult.ExamCategory = $cat
order by examregister.RegNo asc ";

//die($sql4st);
			
		}
		else
		{
   $sql4st = "select MAX(e.ExamScore) as avg from examresult e, student s 
    where e.RegNo = s.RegNo and e.CourseCode = '$course' and e.AYear =  $year_previous and e.ExamCategory = $cat  and e.ExamScore <> 0 ";
		}
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
         
     $sqli = "select distinct examdate.CourseCode, examdate.assessment_status  
from examdate 
INNER JOIN course ON (examdate.CourseCode = course.CourseCode)
INNER JOIN submitresult ON (examdate.CourseCode = submitresult.courseCode)
where  examdate.CourseCode = submitresult.courseCode and examdate.Ayear = $year and examdate.assessment_status = 5
and course.prefix Like '%$prefix%' and course.Programme = 10052
  order by examdate.CourseCode asc ";
  
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
                         $this->Cell(10,7,'',1,0,'L',$fill);
                        $this->Cell(18,7,'',1,0,'L',$fill);
$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year_previous   and e.assessment_status = 5 and e.programme = '$program_previous' order by e.CourseCode asc ";
        $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            $cat= $rowi['assessment_status'];
        
		 if($program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 PAED" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MID" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH")
		{
			
			 $sql4st = "select MIN(examresult.ExamScore) as avg from examregister 
INNER JOIN course ON (examregister.CourseCode = course.CourseCode)
INNER JOIN examresult ON (examregister.RegNo = examresult.RegNo)
 where 
course.prefix = '$prefix' 
and examregister.Ayear = $year 
and examregister.RegNo like '%/%/%' 
and course.Programme = 10052 
and examresult.AYear=$year_previous
and examresult.CourseCode = '$course'
and examresult.ExamCategory = $cat
order by examregister.RegNo asc ";
			
		}
		else
		{
   $sql4st = "select MIN(e.ExamScore) as avg from examresult e, student s where e.RegNo = s.RegNo and e.CourseCode = '$course' and e.AYear =  $year_previous and e.ExamCategory = $cat  and e.ExamScore <> 0";
		}
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
    $sqli = "select distinct examdate.CourseCode, examdate.assessment_status  
from examdate 
INNER JOIN course ON (examdate.CourseCode = course.CourseCode)
INNER JOIN submitresult ON (examdate.CourseCode = submitresult.courseCode)
where  examdate.CourseCode = submitresult.courseCode and examdate.Ayear = $year and examdate.assessment_status = 5
and course.prefix Like '%$prefix%' and course.Programme = 10052
  order by examdate.CourseCode asc ";
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
                         $this->Cell(10,7,'',1,0,'L',$fill);
                        $this->Cell(18,7,'',1,0,'L',$fill);
$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year_previous   and e.assessment_status = 5 and e.programme = '$program_previous' order by e.CourseCode asc ";
        $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            $cat= $rowi['assessment_status'];
       	
        if($program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 PAED" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MID" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH")
		{
			
			 $sql4st = "select AVG(examresult.ExamScore) as avg from examregister 
INNER JOIN course ON (examregister.CourseCode = course.CourseCode)
INNER JOIN examresult ON (examregister.RegNo = examresult.RegNo)
 where 
course.prefix = '$prefix' 
and examregister.Ayear = $year 
and examregister.RegNo like '%/%/%' 
and course.Programme = 10052 
and examresult.AYear=$year_previous
and examresult.CourseCode = '$course'
and examresult.ExamCategory = $cat
order by examregister.RegNo asc ";

//die($sql4st);
			
		}
		else
		{ 
   $sql4st = "select AVG(e.ExamScore) as avg from examresult e, student s where e.RegNo = s.RegNo and e.CourseCode = '$course' and e.AYear =  $year_previous and e.ExamCategory = $cat  and e.ExamScore <> 0 ";
		}
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
       $sqli = "select distinct examdate.CourseCode, examdate.assessment_status  
from examdate 
INNER JOIN course ON (examdate.CourseCode = course.CourseCode)
INNER JOIN submitresult ON (examdate.CourseCode = submitresult.courseCode)
where  examdate.CourseCode = submitresult.courseCode and examdate.Ayear = $year and examdate.assessment_status = 5
and course.prefix Like '%$prefix%' and course.Programme = 10052
  order by examdate.CourseCode asc ";
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

// end statistics

// statistics year 4

function statistics_rpt_yr4($year_previous,$year,$program_previous,$program,$cat)
{



$this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(35,7,'',1,0,'L',$fill);
                        $this->Cell(50,7,'Highest Score',1,0,'L',$fill);
                         $this->Cell(8,7,'',1,0,'L',$fill);
                        $this->Cell(14,7,'',1,0,'L',$fill);
                        
$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year_previous and e.assessment_status = $cat and e.programme = '$program_previous' order by e.CourseCode asc ";
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
$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year_previous and e.assessment_status = $cat and e.programme = '$program_previous' order by e.CourseCode asc ";
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
$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year_previous and e.assessment_status = $cat and e.programme = '$program_previous' order by e.CourseCode asc ";
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



// end statistics year4 

//program header

function progheader($program,$semister, $year)
{	

//die("here jj");
	$this->header = 0;
	//$pdf->footer = 0;

    $this->AddPage('L','',false);
	$this->SetFont('Arial','B',13);
	$this->Ln(7);
	//$this->TOC_Entry($program, 0);
	
	//year in full
	$this->Cell(30,9,"PROGRAMME :		",0,0,'C',0);
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
			else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 MID')
			{
				$prefix = 'MID';
			
			}
			else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH')
			{
				$prefix = 'ADULT-HE';
			
			}
			
	  
        if($program == 'Bachelor of Science in Nursing and Midwifery (Generic) Yr1')
        {
        $prog = trim($program, 'Yr1');
    
        $this->Cell(150,9,$prog.' Year 1',0,0,'C',0);
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
         else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr1')
        {
            $prog = trim($program, 'Yr1');
    
            $this->Cell(150,9,$prog.' Year 1',0,0,'C',0);
        }
		else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 PAED')
		{
			$this->Cell(160,9,"Bachelor of Science in Nursing (Post Basic) Year 2 : Paediatric Nursing",0,0,'C',0);
			
		}
		else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH')
		{
			$this->Cell(165,9,"Bachelor of Science in Nursing (Post Basic) Year 2 : Adult Health Nursing",0,0,'C',0);
			
		}
		else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 COM')
		{
			$this->Cell(177,9,"Bachelor of Science in Nursing (Post Basic) Year 2 : Community Health Nursing",0,0,'C',0);
			
		}
		else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 MID')
		{
			$this->Cell(150,9,"Bachelor of Science in Nursing (Post Basic) Year 2 : Midwifery",0,0,'C',0);
			
		}
		else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 EDU')
		{
			$this->Cell(160,9,"Bachelor of Science in Nursing (Post Basic) Year 2 : Nursing Education",0,0,'C',0);
			
		}
		
		else
		{
			 
			$this->Cell(150,9,$program,0,0,'C',0);
		}
	
	
	//close year in full
	
   
	
    //Data
	 if($semister == "Semester II")
	 {
		 
		 //die("here pp");
		 if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 COM' || $program == 'Bachelor of Science in Nursing (Post Basic) Yr2 EDU' || $program == 'Bachelor of Science in Nursing (Post Basic) Yr2 MGT' || $program == 'Bachelor of Science in Nursing (Post Basic) Yr2 PAED' || $program == "Bachelor of Science in Nursing and Midwifery (Generic) Yr4"  || $program == 'Bachelor of Science in Nursing (Post Basic) Yr2 MID' || $program == 'Bachelor of Science in Nursing (Post Basic) Yr2 PAED' || $program == 'Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH' )
		 {
			 $year_1 = $year - 1;
					 
					 $this->Ln(7);
			 $this->Ln(7);
			 //$this->TOC_Entry("Modules for Semester I", 1);
			 
			 if($program == "Bachelor of Science in Nursing and Midwifery (Generic) Yr4")
			 {
			 $this->Cell(30,9,"Modules ",0,0,'C',0);
			 $this->Cell(30,9, "Year 3 ",0,0,'C',0);
			 	$program_1 = "Bachelor of Science in Nursing and Midwifery (Generic) Yr3";
			 }
			 else
			 {
				 $this->Cell(30,9,"Courses ",0,0,'C',0);
				 $this->Cell(30,9, "Year 1 ",0,0,'C',0);
				 $program_1 = "Bachelor of Science in Nursing (Post Basic) Yr1"; 
			 }
			 
			 $this->Ln(7);
			 $this->Ln(7);
			 $this->SetFillColor(224,235,255);
			$this->SetTextColor(0);
			$this->SetFont('Arial','B',13);
			$fill=0;
			 $this->Cell(60,7,"CODE",1,0,'L',$fill);
			 if($program == "Bachelor of Science in Nursing and Midwifery (Generic) Yr4")
			 {
				 
			 $this->Cell(200,7,"MODULE",1,0,'L',$fill);
			 }
			 else
			 {
				  $this->Cell(200,7,"COURSE",1,0,'L',$fill);
			 }
			 
			 $this->Ln(7);
			 $this->SetFont('Arial','',13);
			
			
		$sqli = "SELECT distinct e.CourseCode, c.CourseName FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year_1  and e.programme = '$program_1' ORDER BY e.CourseCode";
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
			//$this->Ln(7);
			//$this->TOC_Entry("Modules for Semester II", 1);
			 
			  if($program == "Bachelor of Science in Nursing and Midwifery (Generic) Yr4")
			 {
				$this->Cell(30,9,"Modules  ",0,0,'C',0);
			 	$this->Cell(30,9, "Year 4 ",0,0,'C',0);
			 	$program_1 = "Bachelor of Science in Nursing and Midwifery (Generic) Yr3";
			 }
			 else
			 {
				 $this->Cell(30,9,"Courses ",0,0,'C',0);
				 $this->Cell(30,9, "Year 2 ",0,0,'C',0);
				 $program_1 = "Bachelor of Science in Nursing (Post Basic) Yr1"; 
			 }
			 $this->Ln(7);
			 $this->Ln(7);
			 $this->SetFillColor(224,235,255);
			$this->SetTextColor(0);
			$this->SetFont('Arial','B',13);
				 $fill=0;
				 
			 $this->Cell(60,7,"CODE",1,0,'L',$fill);
			 if($program == "Bachelor of Science in Nursing and Midwifery (Generic) Yr4")
			 {
				 
			 $this->Cell(200,7,"MODULE",1,0,'L',$fill);
			  $this->Ln(7);
			 $this->SetFont('Arial','',13);
			 
			 $sqli = "SELECT distinct e.CourseCode, c.CourseName FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year  and e.programme = '$program' ORDER BY e.CourseCode";
			 }
			 else
			 {
				  $this->Cell(200,7,"COURSE",1,0,'L',$fill);
				  $this->Ln(7);
			 $this->SetFont('Arial','',13);
			
			
		$sqli = "SELECT distinct examdate.CourseCode, course.CourseName FROM `examdate` 
INNER JOIN course ON (course.CourseCode= examdate.CourseCode)
INNER JOIN program_year ON (program_year.ProgrammeName= examdate.programme)
WHERE examdate.CourseCode = course.CourseCode and course.prefix LIKE '%$prefix%' and program_year.ProgrammeCode=10052 and examdate.Ayear = $year  and examdate.assessment_status = 5  ORDER BY examdate.CourseCode";
			 }
			
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
			//die("uuuu");
			 $this->Ln(7);
			 $this->Ln(7);
			 //$this->TOC_Entry("Modules for Semester I", 1);
			// die("here u");
			 if($program == "Bachelor of Science in Nursing (Post Basic) Yr2 PAED" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT" || $program == "University Certificate in Midwifery" || $program == "Bachelor of Science in Nursing (Post Basic) Yr1" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MID"  || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH")
		{  
			 	$this->Cell(20,9,"Courses",0,0,'C',0);
			 }
			 else
			 {
				$this->Cell(20,9,"Modules  ",0,0,'C',0); 
			 }
			 
			 $this->Cell(30,9, "Semester I",0,0,'C',0);
			 $this->Ln(7);
			 $this->Ln(7);
			 $this->SetFillColor(224,235,255);
			$this->SetTextColor(0);
			$this->SetFont('Arial','B',13);
			$fill=0;
			 $this->Cell(60,7,"CODE",1,0,'L',$fill);
			  if($program == "Bachelor of Science in Nursing (Post Basic) Yr2 PAED" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT" || $program == "University Certificate in Midwifery" || $program == "Bachelor of Science in Nursing (Post Basic) Yr1" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MID"  || $program == 'Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH')
		{  
				 $this->Cell(200,7,"COURSE",1,0,'L',$fill);
			 }
			 else
			 {
				 $this->Cell(200,7,"MODULE",1,0,'L',$fill);
			 }
			 $this->Ln(7);
			 $this->SetFont('Arial','',13);
			
			
		$sqli = "SELECT distinct e.CourseCode, c.CourseName FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Semister = 'Semester I' and e.Ayear = $year  and e.assessment_status = 5 and e.programme = '$program' ORDER BY e.CourseCode";
		
		
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
			 if($program == "Bachelor of Science in Nursing (Post Basic) Yr1" || $program == "University Certificate in Midwifery")
			 {
			 	$this->Cell(30,9,"Courses for ",0,0,'C',0);
			 }
			 else
			 {
				$this->Cell(30,9,"Modules ",0,0,'C',0); 
			 }
			 $this->Cell(30,9,$semister,0,0,'C',0);
			 $this->Ln(7);
			 $this->Ln(7);
			 $this->SetFillColor(224,235,255);
			$this->SetTextColor(0);
			$this->SetFont('Arial','B',13);
				 $fill=0;
				 
			 $this->Cell(60,7,"CODE",1,0,'L',$fill);
			if($program == "Bachelor of Science in Nursing (Post Basic) Yr1" || $program == "University Certificate in Midwifery")
			 {
				 $this->Cell(200,7,"COURSE",1,0,'L',$fill);
			 }
			 else
			 {
				 $this->Cell(200,7,"MODULE",1,0,'L',$fill);
			 }
			 $this->Ln(7);
			 $this->SetFont('Arial','',13);
			 
			 $sqli = "SELECT distinct e.CourseCode, c.CourseName FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = '$semister' and e.assessment_status = 5  and e.programme = '$program' ORDER BY e.CourseCode";
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
	 else
	 {
	 
		 $this->Ln(7);
	$this->Ln(7);
	 //$this->TOC_Entry("Modules for Semester I", 1);
	 if($program == "Bachelor of Science in Nursing (Post Basic) Yr2 PAED" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT" || $program == "University Certificate in Midwifery" || $program == "Bachelor of Science in Nursing (Post Basic) Yr1" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MID"  || $program == 'Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH')
		{  
				  $this->Cell(30,9,"Courses  ",0,0,'C',0);
			 }
			 else
			 {
				 $this->Cell(30,9,"Modules  ",0,0,'C',0);
			 }
	
	 $this->Cell(30,9,$semister,0,0,'C',0);
	 $this->Ln(7);
	 $this->Ln(7);
	 $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('Arial','B',13);
		  $fill=0;
	 $this->Cell(60,7,"CODE",1,0,'L',$fill);
    if($program == "Bachelor of Science in Nursing (Post Basic) Yr2 PAED" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT"  || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MID" || $program == "University Certificate in Midwifery" || $program == "Bachelor of Science in Nursing (Post Basic) Yr1" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH")
		{  
				 $this->Cell(200,7,"COURSE",1,0,'L',$fill);
			 }
			 else
			 {
				 $this->Cell(200,7,"MODULE",1,0,'L',$fill);
			 }
	 $this->Ln(7);
	 $this->SetFont('Arial','',13);
		 
		 $sqli = "SELECT distinct e.CourseCode, c.CourseName FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = '$semister' and e.programme = '$program' and e.assessment_status = 5  ORDER BY e.CourseCode";
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
	
	 $this->Cell(200,7,"END OF ".($year -1) ."/".$year." ACADEMIC YEAR",0,0,'C',0);
	$this->Ln(7);
	
	 $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('Arial','B',11);
		  $fill=0;
	 $this->Cell(10,7,"SN",1,0,'L',$fill);
     $this->Cell(108,7,"PROGRAMME",1,0,'L',$fill);
	 $this->Cell(10,7,"DIS",1,0,'L',$fill);
	 $this->Cell(10,7,"CR",1,0,'L',$fill);
	 $this->Cell(10,7,"P",1,0,'L',$fill);
	 $this->Cell(10,7,"CP",1,0,'L',$fill);
	 $this->Cell(10,7,"DF",1,0,'L',$fill);
	 $this->Cell(10,7,"REP",1,0,'L',$fill);
	 $this->Cell(10,7,"REF",1,0,'L',$fill);
	 $this->Cell(10,7,"FW",1,0,'L',$fill);
	 $this->Cell(10,7,"WD",1,0,'L',$fill);
	 $this->Cell(10,7,"TR",1,0,'L',$fill);
	 $this->Cell(10,7,"INC",1,0,'L',$fill);
	 $this->Cell(10,7,"DCD",1,0,'L',$fill);
	 
	 $this->Cell(10,7,"SUS",1,0,'L',$fill);
	 $this->Cell(40,7,"TOTAL STUDENTS",1,0,'L',$fill);
	 
	 
	 $this->Ln(7);
	 $this->SetFont('Arial','',11);
		 
		 $sqli = "SELECT `prog`,`DIS`,`CR`,`PP`,`P`,`CP`,`DF`,`REP`,`REF`,`FW`,`WD`,TR,`DCD`,SUS,`INC`,`total` FROM `examsummary` order by prog";
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
			$INC= $rowi['INC'];
			$DCD= $rowi['DCD'];			
			$SUS= $rowi['SUS'];
			$total= $rowi['total'];
	 $this->Cell(10,7,$i,1,0,'L',$fill);
     $this->Cell(108,7,$program,1,0,'L',$fill);
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
	  $this->Cell(10,7,$DCD,1,0,'L',$fill);
	  $this->Cell(10,7,$SUS,1,0,'L',$fill);

	 $this->Cell(40,7,$total,1,0,'L',$fill);
	 		$this->Ln(7);
			$i++;
			$TREP = $REP + $TREP; 
			$TDCD = $DCD + $TDCD; 
			$TFW = $FW + $TFW; 
			$TSUS = $SUS + $TSUS;
			$TINC = $INC + $TINC;
			
		}
		$sqli2 = "SELECT SUM(CR) AS TCR, SUM(PP) AS TPP,SUM(REF) AS TREF,SUM(WD) AS TWD, SUM(TR) AS TTR, SUM(INC) AS TINC, SUM(total) AS TOTAL FROM `examsummary` ";
//die($sqli);
 $this->SetFont('Arial','B',11);
		$i = 1;
        $resulti = mysql_query($sqli2);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
			$fill=!$fill;
			$TCR = $rowi['TCR'];
            $TPP = $rowi['TPP'];
            $TREF= $rowi['TREF'];
			$TTR= $rowi['TTR'];
			$TWD= $rowi['TWD'];
			$TINC= $rowi['TINC'];
			$TOTAL= $rowi['TOTAL'];
			$this->Cell(10,7,'',1,0,'L',$fill);
     $this->Cell(108,7,'TOTAL',1,0,'L',$fill);
	 $this->Cell(10,7,'0',1,0,'L',$fill);
	 $this->Cell(10,7,$TCR,1,0,'L',$fill);
	 $this->Cell(10,7,$TPP,1,0,'L',$fill);
	 $this->Cell(10,7,'0',1,0,'L',$fill);
	 $this->Cell(10,7,'0',1,0,'L',$fill);
	 $this->Cell(10,7,$TREP,1,0,'L',$fill);
	 $this->Cell(10,7,$TREF,1,0,'L',$fill);
	 $this->Cell(10,7,$TFW,1,0,'L',$fill);
	 $this->Cell(10,7,$TWD,1,0,'L',$fill);
	 $this->Cell(10,7,$TTR,1,0,'L',$fill);
	 $this->Cell(10,7,$TINC,1,0,'L',$fill);
	  $this->Cell(10,7,$TDCD,1,0,'L',$fill);
	   $this->Cell(10,7,$TSUS,1,0,'L',$fill);
	 $this->Cell(40,7,$TOTAL,1,0,'L',$fill);
		}
	
	
}


//ucm grades.....



function ucm_table($year,$program,$semister)
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
    $this->SetFont('','',9);
    //Data
    $fill=0;
require_once('../../../Connections/sessioncontrol.php');
require_once('../../../Connections/zalongwa.php');
//table of content index
//$this->TOC_Entry("Results Table", 1);

 if($semister != "Semester I")
	{
	//$sql2 = "select distinct er.RegNo from examregister er, examdate e where er.CourseCode = e.CourseCode and e.programme = '$program' and er.Ayear = $year and e.assessment_status = $cat and er.RegNo like '%/%/%' order by er.RegNo asc ";
	$sql2 = "select distinct examregister.RegNo from examregister 
INNER JOIN  examdate 
ON examregister.CourseCode = examdate.CourseCode
INNER JOIN student
ON examregister.RegNo = student.RegNo
WHERE  examdate.programme = '$program' and examregister.Ayear = $year and examdate.assessment_status = $cat and examregister.RegNo like '%/%/%' order by student.Name asc";
	
	}
	else
	{
		//$sql2 = "select distinct er.RegNo from examregister er, examdate e where er.CourseCode = e.CourseCode and e.programme = '$program' and er.Ayear = $year  and er.RegNo like '%/%/%' order by er.RegNo asc ";	
		$sql2 = "select distinct examregister.RegNo from examregister 
INNER JOIN examdate 
ON  examregister.CourseCode = examdate.CourseCode
INNER JOIN student
ON examregister.RegNo = student.RegNo
where examdate.programme = '$program' and examregister.Ayear = $year and examdate.assessment_status = $cat and examregister.RegNo like '%/%/%' order by student.Name asc ";
	}
	
    $result2 = mysql_query($sql2);
     $count = 1;
   $badseed = 0;
   $tracker2 = 0;
    $countdist = 0;
    $countcred = 0;
    $countpass = 0;
    $count2ref = 0;
    $countwd = 0;
    
    $countdistm = 0;
    $countcredm = 0;
    $countpassm = 0;
    $countrefm = 0;
    $countwdm = 0;
    $inc = 0;
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
          
                    $this->nameucm($regno,$fill);
               
        } 
        else
        {
            $this->Cell(6,7,number_format($count),1,0,'R',$fill); 
          
                    $this->nameucm($regno,$fill);
            $badseed = 1;
            //$count = $count - 1;
        }
        $tracker = 0;
        
        //die($regno);
        //echo "$regno <br>";
//generation of courses for a specific programme
//cha scores

if($semister != "Semester I")
	{
		//die("am here");
       // $sql3 = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc";
	   $sql3 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester I' and e.programme = '$program' and e.assessment_status = 5 ORDER BY e.CourseCode";
	   //die($sql3);
	   $sem1check = mysql_query($sql3);
	   $numrolls = mysql_num_rows($sem1check);
		$this->scoresucm($sql3, $regno, $fill,$year);
		
		if($numrolls != 0)
		{
		
		 $this->Cell(1,7,'',1,0,'C',$fill);
		}
		
		$sql3 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester II' and e.programme = '$program' and assessment_status = 5 ORDER BY e.CourseCode";
		$this->scoresucm($sql3, $regno, $fill,$year);
		
        
	}
	else
	{
		$sql3 = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year  and e.programme = '$program' order by e.CourseCode asc";	
		$this->scoresucm($sql3, $regno, $fill,$year);
		
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
			
			///
	  
	  // recommendation check for ucm
	  
	  
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
			
			if($regno == "KCN/BScN/06/026/UCM")
			{ 
			//die("here".$lowestmark_yr4_clinical);
			 }
			//end recommendation check
	  
      
    if($hist =='(Sus)' || $trim == 'DF' || $hist == 'DF' || $hist == '(WD)' || $hist == '(WD/M)' || $hist == '(WD/V)' || $hist == '(WD/P)' || $hist == 'NP' || $trim2 == 'NP' || $trim3 == 'NP' || $trim4 == 'NP' || $trim5 == 'NP' || $trim6 == 'NP' || $hist == 'INC' || $hist == '(DCD)' || $hist == '(TR)' )
    { 
        $this->Cell(6,7,'',1,0,'R',$fill);
    
    }
    else if ($avg >= 69.5)
		{
		$this->SetFont('','B',9);
                        
                                                 
            $this->Cell(6,7,number_format($avg),1,0,'R',$fill);
		$this->SetFont('','',9);
		}
		else if($lowestmark_yr4 == '' || $lowestmark_yr4 == 0)
		{
			
		$this->Cell(6,7,'',1,0,'R',$fill);	
			
		}
		else
		{
		 $this->Cell(6,7,number_format($avg),1,0,'R',$fill);

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
	
	$sqlmin2non = "select ExamScore as rep from examresult 
where AYear = $year and RegNo = '$regno'   and CourseCode =  'PMID SC 501' ";	
	
        $resultmin2non = mysql_query($sqlmin2non);
            
            while($rowmin2 = mysql_fetch_array($resultmin2non, MYSQL_ASSOC))
            {
                 $nonnursing = $rowmin2['rep'];
                 
            }    
			 $sqlmin2not = "select MIN(ExamScore) as year4 from examresult 
where AYear = $year and RegNo = '$regno' and ExamCategory = $cat and CourseCode <> 'PMID SC 501' ";
        $resultmin2not = mysql_query($sqlmin2not);
		
            
            while($rowmin2 = mysql_fetch_array($resultmin2not, MYSQL_ASSOC))
            {
                 $lowestmark_notnon = $rowmin2['year4'];
                 
            }
        
      
       
        if($hist == 'NP' || $trim2 == 'NP' || $trim3 == 'NP' || $trim4 == 'NP' || $trim5 == 'NP' || $trim6 == 'NP')
        {
            $this->Cell(18,7,'WH',1,0,'R',$fill);
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
            $this->Cell(18,7,'SUS',1,0,'R',$fill);
             if($sex == 'F')
              {
                $countsus +=1;
              }
              else
              {
                $countsusm +=1;
              
              }
    
        }
		 else if($hist =='(TR)')
        {
            $this->Cell(18,7,'TR',1,0,'R',$fill);
             if($sex == 'F')
              {
                $counttr +=1;
              }
              else
              {
                $counttrm +=1;
              
              }
    
        }
		else if($hist == '(WD)' || $hist == '(WD/P)' || $hist == '(WD/M)' || $hist == '(WD/V)')
        {
			
			if($hist == '(WD/P)')
			{
            $this->Cell(18,7,'WD/P',1,0,'R',$fill);
			}
			else if($hist == '(WD/M)')
			{
            $this->Cell(18,7,'WD/M',1,0,'R',$fill);
			}
			else if($hist == '(WD/V)')
			{
            $this->Cell(18,7,'WD/V',1,0,'R',$fill);
			}
			else
			{
			$this->Cell(18,7,'WD',1,0,'R',$fill);	
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
		else if($hist == '(DCD)')
        {
            $this->Cell(18,7,'DCD',1,0,'R',$fill);
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
            $this->Cell(18,7,'DF',1,0,'R',$fill);
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
            $this->Cell(18,7,'CP',1,0,'R',$fill);
			$this->recommendation("COMPENSATORY PASS",$regno,$semister,$year);
             if($sex == 'F')
              {
                $countcp +=1;
              }
              else
              {
                $countcpm +=1;
              
              }
    
        }
		else if( $lowestmark_yr4_clinical >= 70 && $lowestmark_yr4 >= 70 && $avg > 74.4 && $hist != '(AM 1)')
            {
				  $this->Cell(18,7,'DIS',1,0,'R',$fill);
				  $this->recommendation("DISTINCTION",$regno,$semister,$year);
				  if($sex == 'F')
				  {
					$countdist +=1;
				  }
				  else
				  {
					$countdistm +=1;
				  
				  }
            }
			
            else if($lowestmark_yr4_clinical > 64 && $lowestmark_yr4 > 59 && $avg > 64.4 && $hist != '(AM 1)' )
            {
            
           
				  $this->Cell(18,7,'CR',1,0,'R',$fill);
				  $this->recommendation("CREDIT",$regno,$semister,$year);
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
			  $this->recommendation("PASS",$regno,$semister,$year);
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
				
				if($lowestmark == '' || $lowestmark == 0)
			{
				$this->Cell(18,7,'INC',1,0,'R',$fill);
				$this->recommendation("INCOMPLETE",$regno,$semister,$year);
				 if($sex == 'F')
              {
                $countinc +=1;
              }
              else
              {
                $countincm +=1;
			  }
			}
			else if($countref >= 3)
        {
             $this->Cell(18,7,'FW',1,0,'R',$fill);
			 $this->recommendation("FAIL AND WITHDRAW",$regno,$semister,$year);
              if($sex == 'F')
              {
                $count2fw += 1;
              }
              else
              {
                $countfwm +=1;
              
              }
        }
			else if($lowestmark_yr4 < 50 && $lowestmark_yr4 > 0)
			{
              $this->Cell(18,7,'REF',1,0,'R',$fill);
              if($sex == 'F')
              {
				  
                $count2ref +=1;
				//die("here and ref is ".$countref);
              }
              else
              {
                $countrefm +=1;
              }
			}
            }
            else if($avg < 50 && $hist != '(AM 1)')
            {
              $this->Cell(15,7,'WD',1,0,'R',$fill);
                if($sex == 'F')
              {
                $countwd +=1;
              }
              else
              {
                $countwdm +=1;
              }
            }
        
        else if($lowestmark  > 49.4)
        {
            $this->Cell(18,7,'P',1,0,'R',$fill);
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
		if($lowestmark_yr4 == '' || $lowestmark_yr4 == 0 )
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
		else
		{
			
            $this->Cell(18,7,'REFF',1,0,'R',$fill);
            
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
             $this->Cell(18,7,'REP',1,0,'R',$fill);
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
   
   

$this->SetFont('','B',9);
//statistics
//$header = 0;
$this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(40,7,'',1,0,'L',$fill);
                        $this->Cell(68,7,'Highest Score',1,0,'L',$fill);
                        // $this->Cell(6,7,'',1,0,'L',$fill);
                        //$this->Cell(12,7,'',1,0,'L',$fill);
	 if($semister != "Semester I")
	{

//$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc ";



$sql3 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester I' and e.programme = '$program' and assessment_status = 5 order by e.CourseCode asc";
	   $sem1check = mysql_query($sql3);
	   $numrolls = mysql_num_rows($sem1check);
		//sem1 avarage
		 //$resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($sem1check, MYSQL_ASSOC))
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
                       $avg = $rowcst4['avg'];
                       

                      $this->Cell(16,7,number_format($avg),1,0,'R',$fill);
                    }
        }
		
		
		if($numrolls != 0)
		{
		
		 $this->Cell(1,7,'',1,0,'C',$fill);
		}
		
		$sql32 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester II' and e.programme = '$program' and assessment_status = 5 order by e.CourseCode asc";

//sem2 avarage
		 //$resulti = mysql_query($sqli);
		 $sem2check = mysql_query($sql32);
	   $numrolls = mysql_num_rows($sem2check);
        while($rowi = mysql_fetch_array($sem2check, MYSQL_ASSOC))
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
                       $avg = $rowcst4['avg'];
                       

                      $this->Cell(16,7,number_format($avg),1,0,'R',$fill);
                    }
        }





	}
	else
	{
		$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.programme = '$program' order by e.CourseCode asc ";
		
		
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
                       

                      $this->Cell(16,7,number_format($high),1,0,'R',$fill);
                    }
        }
		
		
		
	}
        
	

$this->Ln(7);
//array(4,35,49,7,12);
$this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(40,7,'',1,0,'L',$fill);
                        $this->Cell(68,7,'Lowest Score',1,0,'L',$fill);
                        // $this->Cell(6,7,'',1,0,'L',$fill);
                        //$this->Cell(12,7,'',1,0,'L',$fill);
 if($semister != "Semester I")
	{
//$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc ";


$sql3 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester I' and e.programme = '$program' and assessment_status = 5 order by e.CourseCode asc";
	   $sem1check = mysql_query($sql3);
	   $numrolls = mysql_num_rows($sem1check);
		//sem1 avarage
		 //$resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($sem1check, MYSQL_ASSOC))
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
                       $avg = $rowcst4['avg'];
                       

                      $this->Cell(16,7,number_format($avg),1,0,'R',$fill);
                    }
        }
		
		
		if($numrolls != 0)
		{
		
		 $this->Cell(1,7,'',1,0,'C',$fill);
		}
		
		$sql32 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester II' and e.programme = '$program' and assessment_status = 5 order by e.CourseCode asc";

//sem2 avarage
		 //$resulti = mysql_query($sqli);
		 $sem2check = mysql_query($sql32);
	   $numrolls = mysql_num_rows($sem2check);
        while($rowi = mysql_fetch_array($sem2check, MYSQL_ASSOC))
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
                       $avg = $rowcst4['avg'];
                       

                      $this->Cell(16,7,number_format($avg),1,0,'R',$fill);
                    }
        }





	}
	else
	{
	$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year  and e.programme = '$program' order by e.CourseCode asc ";
	
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
                       

                      $this->Cell(16,7,number_format($low),1,0,'R',$fill);
                    }
        }	
		
	}
        
        $this->Ln(7);
                        $this->Cell(6,7,'',1,0,'L',$fill);
                        $this->Cell(40,7,'',1,0,'L',$fill);
                        $this->Cell(68,7,'Average Score',1,0,'L',$fill);
                        // $this->Cell(6,7,'',1,0,'L',$fill);
                        //$this->Cell(12,7,'',1,0,'L',$fill);
	if($semister != "Semester I")
	{
		
		
//$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year and e.assessment_status = $cat and e.programme = '$program' order by e.CourseCode asc ";

 $sql3 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester I' and e.programme = '$program' and assessment_status = 5 order by e.CourseCode asc";
	   $sem1check = mysql_query($sql3);
	   $numrolls = mysql_num_rows($sem1check);
		//sem1 avarage
		 //$resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($sem1check, MYSQL_ASSOC))
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
                       

                      $this->Cell(16,7,number_format($avg),1,0,'R',$fill);
                    }
        }
		
		
		if($numrolls != 0)
		{
		
		 $this->Cell(1,7,'',1,0,'C',$fill);
		}
		
		$sql32 = "SELECT distinct e.CourseCode,  e.assessment_status FROM `examdate` e , course c WHERE e.CourseCode = c.CourseCode and e.Ayear = $year and e.Semister = 'Semester II' and e.programme = '$program' and assessment_status = 5 order by e.CourseCode asc";

//sem2 avarage
		 //$resulti = mysql_query($sqli);
		 $sem2check = mysql_query($sql32);
	   $numrolls = mysql_num_rows($sem2check);
        while($rowi = mysql_fetch_array($sem2check, MYSQL_ASSOC))
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
                       

                      $this->Cell(16,7,number_format($avg),1,0,'R',$fill);
                    }
        }




	}
	else
	{
		$sqli = "select distinct e.CourseCode, e.assessment_status  from examdate e, submitresult s where  e.CourseCode = s.courseCode and e.Ayear = $year  and e.programme = '$program' order by e.CourseCode asc ";
		
		
		
		 $resulti = mysql_query($sqli);
        while($rowi = mysql_fetch_array($resulti, MYSQL_ASSOC))
        {
            $course= $rowi['CourseCode'];
            $cat= $rowi['assessment_status'];
        
   $sql4st = "select AVG(ExamScore) as avg from examresult where CourseCode = '$course' and AYear = $year and ExamCategory = $cat AND ExamScore <> '' ";
                //die($sql4st);
                   $result4st = mysql_query($sql4st) or die(mysql_error());
                   //$resul = mysql_fetch_assoc( $result4st);
                   
                    while($rowcst4 = mysql_fetch_array($result4st, MYSQL_ASSOC))
                    {
                        //$high = $rowc['high'];
                       $avg = $rowcst4['avg'];
                       

                      $this->Cell(16,7,number_format($avg),1,0,'R',$fill);
                    }
        }
	}
       

            //die($sql3);
            //die($sql4);
			
			
            
//statistics
$this->header = 0;

$this->Ln(7);
$this->Ln(7);

$this->Cell(286,7,'SUMMARY OF RESULTS',1,0,'L',$fill);
$this->Ln(7);
                        $this->Cell(45,7,'',1,0,'L',$fill);
                        $this->Cell(25,7,'No. of Stud',1,0,'L',$fill);
                        $this->Cell(18,7,'DIS',1,0,'L',$fill);
                        $this->Cell(18,7,'CR',1,0,'L',$fill);
                         $this->Cell(18,7,'P',1,0,'L',$fill);
                        $this->Cell(18,7,'REF',1,0,'L',$fill);
                        $this->Cell(18,7,'WD',1,0,'L',$fill);
                        $this->Cell(18,7,'FW',1,0,'L',$fill);
                         $this->Cell(18,7,'SUS',1,0,'L',$fill);
                        $this->Cell(18,7,'DM',1,0,'L',$fill);
                        $this->Cell(18,7,'TR',1,0,'L',$fill);
                        $this->Cell(18,7,'WH',1,0,'L',$fill);
                        $this->Cell(18,7,'INC',1,0,'L',$fill);
						$this->Cell(18,7,'DCD',1,0,'L',$fill);
                        
                        
                        
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
                        $this->Cell(45,7,'FEMALE',1,0,'L',$fill);
                        $this->Cell(25,7,number_format($female),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countdist),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countcred),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countpass),1,0,'L',$fill);
                        $this->Cell(18,7,'1',1,number_format($count2ref),'L',$fill);
                        $this->Cell(18,7,number_format($countwd),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($count2fw),1,0,'L',$fill);
                        $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,number_format($counttr),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countnp),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countinc),1,0,'L',$fill);
						 $this->Cell(18,7,number_format($countdcd),1,0,'L',$fill);
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
                        $this->Cell(45,7,'MALE',1,0,'L',$fill);
                        $this->Cell(25,7,number_format($male),1,0,'L',$fill);
                       $this->Cell(18,7,number_format($countdistm),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countcredm),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countpassm),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countrefm),1,0,'L',$fill);
                        $this->Cell(18,7,number_format($countwdm),1,0,'L',$fill);
                      $this->Cell(18,7,number_format($countfwm),1,0,'L',$fill);
                        $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,'0',1,0,'L',$fill);
                          $this->Cell(18,7,number_format($counttrm),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countnpm),1,0,'L',$fill);
                         $this->Cell(18,7,number_format($countincm),1,0,'L',$fill);
						 $this->Cell(18,7,number_format($countdcdm),1,0,'L',$fill);
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
                        $this->Cell(45,7,'TOTAL',1,0,'L',$fill);
                        $this->Cell(25,7,number_format($total),1,0,'L',$fill);
                        $this->Cell(18,7,$countdistm+$countdist,1,0,'L',$fill);
                        $this->Cell(18,7,$countcredm+$countcred,1,0,'L',$fill);
                         $this->Cell(18,7,$countpassm+$countpass,1,0,'L',$fill);
                        $this->Cell(18,7,$count2ref+$countrefm,1,0,'L',$fill);
                        $this->Cell(18,7,$countwdm+$countwd,1,0,'L',$fill);
                        $this->Cell(18,7,$countfwm+$count2fw,1,0,'L',$fill);
                        $this->Cell(18,7,'0',1,0,'L',$fill);
                         $this->Cell(18,7,'0',1,0,'L',$fill);
                          $this->Cell(18,7,$counttr+$counttrm,1,0,'L',$fill);
                         $this->Cell(18,7,$countnpm+$countnp,1,0,'L',$fill);
                         $this->Cell(18,7,$countincm+$countinc,1,0,'L',$fill);
						 $this->Cell(18,7,$countdcdm+$countdcd,1,0,'L',$fill);
				        // $this->Cell(18,7,'',1,0,'L',$fill);
						
				     
}






//ucme end




}
$pdf=new PDF();
//Column titles
//$pdf2= new PDF_TOC(); 
//$pdf= new PDF_TOC(); 
$pdf->header = 0;
 $pdf->SetFont('Arial','B',13);
	

$pdf->startPageNums(0);


$year = $_GET["year"];
//$year = 2012;
//$program = $_GET["program"];


$semister = $_GET["semister"];
//die($semister);

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
			
			if ($program == "University Certificate in Midwifery")
			{
			$rulecount3 = 0;
			$rulecount2 = 0;
				if($rulecount == 0)
				{
				$pdf->header = 0;
				$tplidx = $pdf->importPage(14, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->TOC_Entry("Assessment Rules and Regulations for University Certificate in Midwifery ", 0);
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				
				$tplidx = $pdf->importPage(15, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				
				$tplidx = $pdf->importPage(16, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				
				$tplidx = $pdf->importPage(17, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				
				$tplidx = $pdf->importPage(18, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				
				$tplidx = $pdf->importPage(19, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->useTemplate($tplidx, 0, 0, 0); 
				}
				
				$rulecount ++;
			
			}
	elseif($program == "Bachelor of Science in Nursing (Post Basic) Yr2 PAED" || $program == "University Certificate in Midwifery " || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT"  || $program == "Bachelor of Science in Nursing (Post Basic) Yr1" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT")
	{
			$rulecount = 0;
			$rulecount3 = 0;
				if($rulecount2 == 0)
				{
				$pdf->header = 0;
				$tplidx = $pdf->importPage(8, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->TOC_Entry("Assessment Rules and Regulations for Post Basic Programmes ", 0);
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
				
				$rulecount2 ++;
				
			}
			
			else
			{
				$rulecount = 0;
				$rulecount2 = 0;
				if($rulecount3 == 0)
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
				}
				
				$rulecount3 ++;
				
				
			}
			
			
	/*		
	if($program == "Bachelor of Science in Nursing (Post Basic) Yr2 PAED" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT" || $program == "University Certificate in Midwifery" || $program == "Bachelor of Science in Nursing (Post Basic) Yr1" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MID"  || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH")
	{
			$rulecount2 = 0;
				if($rulecount == 0)
				{
				$pdf->header = 0;
				$tplidx = $pdf->importPage(8, '/MediaBox'); 
				$pdf->addPage('L'); 
				$pdf->TOC_Entry("Assessment Rules and Regulations for Bachelor of Science in Nursing (Post Basic)", 0);
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
				}
				
				$rulecount2 ++;
				
				
			} */
			
			
			
			$pdf->progheader($program,$semister, $year);
			$pdf->header = 1;
			
			if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 COM')
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
				$pdf->TOC_Entry('Bachelor of Science in Nursing (Post Basic) Year 2 PAEDIATRIC', 0);
			
			}
			else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 MID')
			{
				$pdf->TOC_Entry('Bachelor of Science in Nursing (Post Basic) Year 2 MIDWIFERY', 0);
			
			}
			else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr1')
			{
				$pdf->TOC_Entry('Bachelor of Science in Nursing (Post Basic) Year 1', 0);
			
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
			else if($program == 'Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH')
			{
				$pdf->TOC_Entry('Bachelor of Science in Nursing (Post Basic) Year 2 ADULT HEALTH', 0);
			
			}
			
			else
			{
			
			$pdf->TOC_Entry($program, 0);
			}
				
			//$pdf->progheader($program,$semister, $year);
			$pdf->header = 1;
			//$pdf->TOC_Entry($program, 0);
			
        

			
			if($semister == "Semester I")
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
			else
			{
				
				if($program == "Bachelor of Science in Nursing (Post Basic) Yr2 PAED" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MID" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || $program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT" ||  $program == "Bachelor of Science in Nursing (Post Basic) Yr2 ADULT-HEALTH")
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
 $pdf->header = 0;
$pdf->insertTOC(1);
$pdf->Output();
?>
