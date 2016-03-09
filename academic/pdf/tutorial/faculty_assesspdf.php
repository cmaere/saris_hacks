<?php
require('pdf/fpdf.php');
require('button.php');

class PDF extends FPDF
{


function header()
{
global $header,$yr,$class,$code;

//die("here 2 ".$class);
$query_coursecode = "
		SELECT ProgrammeName
		FROM program_year
	
		 WHERE ProgrammeCode = '$code'";


$resultb=mysql_query($query_coursecode);
while ($line = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    //$class= $line["prefix"];
    $pr= $line["ProgrammeName"];
    
    
}
$this->Ln(4);
	$this->SetFont('','B',13);
	//Calculate width of title and position
	$w=$this->GetStringWidth($pr)+120;
	$this->SetX((210-$w)/2);
	//Colors of frame, background and text
	//$this->SetDrawColor(0,80,180);
	//$this->SetFillColor(230,230,0);
	//$this->SetTextColor(220,50,50);
	//Thickness of frame (1 mm)
    $this->Ln(13);
	$this->SetLineWidth(1);
	//Title
    
	$this->Cell($w,9,$pr,0,0,'C',0);
	//Line break
	$this->Ln(10);

 
 //header
  $this->SetFillColor(57,127,145);
    $this->SetTextColor(255);
    $this->SetDrawColor(57,127,145);
    $this->SetLineWidth(.3);
    $this->SetFont('','B',9);
 
 
require_once('../Connections/sessioncontrol.php');
require_once('../Connections/zalongwa.php');
//$class = 20;


if(($code ==2001) || ($code ==2002))
{
    
       //die("here");

                $test = "SELECT  DISTINCT e.CourseCode, c.Programme
        FROM  examresult e, course c 
        WHERE 
        e.CourseCode = c.CourseCode AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
        AND e.ExamCategory ='5'
        AND c.Programme LIKE '%$code%'

        ORDER BY e.CourseCode ASC";

        $testb = "SELECT  COUNT(DISTINCT e.CourseCode) AS cha
        FROM  examresult e, course c 
        WHERE 
        e.CourseCode = c.CourseCode AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
       AND e.ExamCategory ='5'
        AND c.Programme LIKE '%$code%'
        ORDER BY e.CourseCode ASC";




        $test2 = "SELECT  DISTINCT e.RegNo, s.Name,s.Sex
        FROM  examresult e, course c , student s
        WHERE 
        e.CourseCode = c.CourseCode AND
        s.RegNo = e.RegNo AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
        AND e.ExamCategory ='5'
         AND c.Programme = '$code'
        ORDER BY s.Name ASC";       
    
    
   
    
    



}
else if($code ==10052)
{
    
       

                $test = "SELECT  DISTINCT e.CourseCode, c.Programme
        FROM  examresult e, course c 
        WHERE 
        e.CourseCode = c.CourseCode AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
        AND e.ExamCategory ='5'
        AND c.Programme = '$code'
        AND e.CourseCode LIKE '%$class%'
        ORDER BY e.CourseCode ASC";

        $testb = "SELECT  COUNT(DISTINCT e.CourseCode) AS cha
        FROM  examresult e, course c 
        WHERE 
        e.CourseCode = c.CourseCode AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
       AND e.ExamCategory ='5'
       AND c.Programme = '$code'
        AND e.CourseCode LIKE '%$class%'
        ORDER BY e.CourseCode ASC";




        $test2 = "SELECT  DISTINCT e.RegNo, s.Name,s.Sex
        FROM  examresult e, course c , student s
        WHERE 
        e.CourseCode = c.CourseCode AND
        s.RegNo = e.RegNo AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
        AND e.ExamCategory ='5'
         AND c.Programme = '$code'
        AND e.CourseCode LIKE '%$class%'
        ORDER BY s.Name ASC";       
    
    
   
    
    



}

else if($code == 10024)
{
$code2= $code -2;

$test = "SELECT  DISTINCT e.CourseCode, c.Programme
FROM  examresult e, course c 
WHERE 
e.CourseCode = c.CourseCode AND
 e.AYear > '2008'

AND c.Programme > $code2 AND c.Programme <= $code

ORDER BY c.Programme, e.CourseCode ASC
";

$testb = "SELECT  COUNT(DISTINCT e.CourseCode) AS cha
FROM  examresult e, course c 
WHERE 
e.CourseCode = c.CourseCode AND
 e.AYear > '2008'

AND c.Programme > $code2 AND c.Programme <= $code

ORDER BY c.Programme, e.CourseCode ASC
";




$test2 = "SELECT  DISTINCT e.RegNo, s.Name,s.Sex
FROM  examresult e, course c , student s
WHERE 
e.CourseCode = c.CourseCode AND
s.RegNo = e.RegNo AND
 e.AYear LIKE CONVERT( _utf8 '2010'
USING latin1 ) 
COLLATE latin1_swedish_ci
AND e.ExamCategory ='5'
AND c.Programme = '$code'
ORDER BY s.Name ASC";

}

else if($code == 1004)
{
$code2= $code -2;

$test = "SELECT  DISTINCT e.CourseCode, c.Programme
FROM  examresult e, course c 
WHERE 
e.CourseCode = c.CourseCode AND
 e.AYear > '2008'

AND c.Programme > $code2 AND c.Programme <= $code

ORDER BY c.Programme, e.CourseCode ASC
";

$testb = "SELECT  COUNT(DISTINCT e.CourseCode) AS cha
FROM  examresult e, course c 
WHERE 
e.CourseCode = c.CourseCode AND
 e.AYear > '2008'

AND c.Programme > $code2 AND c.Programme <= $code

ORDER BY c.Programme, e.CourseCode ASC
";




$test2 = "SELECT  DISTINCT e.RegNo, s.Name,s.Sex
FROM  examresult e, course c , student s
WHERE 
e.CourseCode = c.CourseCode AND
s.RegNo = e.RegNo AND
 e.AYear LIKE CONVERT( _utf8 '2010'
USING latin1 ) 
COLLATE latin1_swedish_ci
AND e.ExamCategory ='5'
AND c.Programme = '$code'
ORDER BY s.Name ASC";

}

else
{

$test = "SELECT  DISTINCT e.CourseCode, c.Programme
FROM  examresult e, course c 
WHERE 
e.CourseCode = c.CourseCode AND
 e.AYear LIKE CONVERT( _utf8 '2010'
USING latin1 ) 
COLLATE latin1_swedish_ci

AND c.Programme = '$code'
AND e.ExamCategory ='5'
ORDER BY e.CourseCode ASC";

$testb = "SELECT  COUNT(DISTINCT e.CourseCode) AS cha
FROM  examresult e, course c 
WHERE 
e.CourseCode = c.CourseCode AND
 e.AYear LIKE CONVERT( _utf8 '2010'
USING latin1 ) 
COLLATE latin1_swedish_ci

AND c.Programme = '$code'
AND e.ExamCategory ='5'
ORDER BY e.CourseCode ASC";




$test2 = "SELECT  DISTINCT e.RegNo, s.Name,s.Sex
FROM  examresult e, course c , student s
WHERE 
e.CourseCode = c.CourseCode AND
s.RegNo = e.RegNo AND
 e.AYear LIKE CONVERT( _utf8 '2010'
USING latin1 ) 
COLLATE latin1_swedish_ci
AND e.ExamCategory ='5'
AND c.Programme = '$code'
ORDER BY s.Name ASC";

}


  $countb = 0;
  $notassess =0;
  $REF = 0;
$resultb=mysql_query($testb);
while ($line = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    $c= $line["cha"];
    
    
}

$score = array();
$cour = array();
$course1 = array();
$max = array();
$min = array();
$avgb = array();
$i = 0;
$result=mysql_query($test);
//echo "<b>$p</b><br><table border=1><tr><td><b>SN</td><td width = '300'><b><td width = '400'><b>T";

//Header
 $this->Ln(7);
  $this->Ln(7);
   $this->Ln(7);
    $this->Ln(7);
    $w=array(6,45,60,10);
   for($i=0;$i<count($header);$i++)
   {
        $this->Cell($w[$i],7,$header[$i],1,0,'C',1);
    }
    $addpicd = 130;
    $i = 0;
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    $course= $line["CourseCode"];
    $cour[$i] = $line["CourseCode"];
    $AYear[$i] = $line["Programme"];
    
    
    $maxsql = "Select max(`ExamScore`) as max from examresult
    
where `AYear`='$yr' and `CourseCode`='$course'";
    //echo "<td><b>$course</td>";
    //image cha
    
    $text = new textPNG;
    

	$msg = $course;
    $size = 25;
    $rot = 58;
    $pad = 10;
	 $text->size = $size; // size in points
     $text->msg = $msg; // text to display
	if (isset($font)) $text->font = $font; // font to use (include directory if needed).
	$text->rot = $rot; // rotation
    
	 $text->pad = $pad; // padding in pixels around text.
	 $text->red = 45; // text color
	 $text->grn = 67; // ..
	$text->blu = 87; // ..
	 $text->bg_red = 255; // background color.
	 $text->bg_grn = 255; // ..
	$text->bg_blu = 255; // ..
	if (isset($tr)) $text->transparent = $tr; // transparency flag (boolean).

	$text->draw('temp/'.$i.'.png'); // GO!!!!!
    
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',9);
    
    
    
    
     
     if($AYear[$i] == 10024 && $AYear[$i-1] == 10023 )
                        {
                            $this->SetFillColor(57,127,145);
                            $this->SetTextColor(255);
                            $this->SetDrawColor(57,127,145);
                             $this->SetLineWidth(.3);
                             $this->SetFont('','B',9);

                            $this->Cell(2,7,'',1,0,'R',1);   
                            
                             $this->SetFillColor(224,235,255);
                            $this->SetTextColor(0);
                             $this->SetFont('','',9);
                             
                             
                             $addpicd+=2;
                        
                        }
                        
                    $this->Cell(6,7,'',1,0,'C',1);
                    
     $this->Image('temp/'.$i.'.png',$addpicd,50,12);
     
     $addpicd +=6;
    $resultmax=mysql_query($maxsql);
    while ($linemax = mysql_fetch_array($resultmax, MYSQL_ASSOC)) 
    {
    $max[$i] =  $linemax["max"];   
    
    }
     $minsql = "Select min(`ExamScore`) as min from examresult
where `AYear`='$yr' and `CourseCode`='$course' and ExamScore !='' ";
    //echo "<td><b>$course</td>";
    $resultmin=mysql_query($minsql);
    while ($linemin = mysql_fetch_array($resultmin, MYSQL_ASSOC)) 
    {
    $min[$i] =  $linemin["min"];   
    
    }
    $avgsql = "Select avg(`ExamScore`) as avg from examresult
where `AYear`='$yr' and `CourseCode`='$course'";
    //echo "<td><b>$course</td>";
    $resultavg=mysql_query($avgsql);
    while ($lineavg = mysql_fetch_array($resultavg, MYSQL_ASSOC)) 
    {
    $avgb[$i] =  $lineavg["avg"];   
    
    }
    $i++;
    
}

$this->SetFillColor(57,127,145);
    $this->SetTextColor(255);
    $this->SetDrawColor(57,127,145);
    $this->SetLineWidth(.3);
    $this->SetFont('','B',9);
    
    
    if ($code == 10024)
  {
  
  $this->Cell(17,7,"AVG-Yr3",1,0,'L',1);
  $this->Cell(17,7,"AVG-Yr4",1,0,'L',1);
  
  }
  else
  {
    $this->Cell(6,7,"AVG",1,0,'C',1);
  }

 
 
 $this->Cell(32,7,"RECOMM",1,0,'C',1);

//$this->Ln();

  $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',9);
    
 
    
 //header end   
 
    $this->Ln(7);
    if($code ==10024)
    {
    
             for($i=0;$i<count($header);$i++)
           {
                $this->Cell($w[$i],7,'',1,0,'C',0);
            }
          
            $this->Cell(6,7,' Year 3',1,0,'L',0);
        for($i=2;$i<count($cour);$i++)
           {
           $this->Cell(6,7,'',1,0,'C',0);
            if($AYear[$i] == 10024 && $AYear[$i-1] == 10023 )
                        {
                            $this->SetFillColor(57,127,145);
                            $this->SetTextColor(255);
                            $this->SetDrawColor(57,127,145);
                             $this->SetLineWidth(.3);
                             $this->SetFont('','B',9);

                            $this->Cell(2,7,'',1,0,'R',1);   
                            
                             $this->SetFillColor(224,235,255);
                            $this->SetTextColor(0);
                             $this->SetFont('','',9);
                             $this->Cell(6,7,' Year 4',1,0,'L',0);
                        
                        }
                
                
            }
            $this->Cell(17,7,"",1,0,'L',0);
            $this->Cell(17,7,"",1,0,'L',0);
             $this->Cell(30,7,"",1,0,'C',0);

            $this->Ln(7);
    }
	//Save ordinate
	$this->y0=$this->GetY();


} 

//Load data

//Colored table
function FancyTable($header,$yr,$class,$code)
{



    //Colors, line width and bold font
   


require_once('../Connections/sessioncontrol.php');
require_once('../Connections/zalongwa.php');
//$class = 20;
//initialising values

 $distrate_m =0;
$creditrate_m = 0;
$passrate_m = 0;
$comppass_m = 0;
$dif_m = 0;
$ref_m = 0;
$repeat_m = 0;
$inc_m = 0;
$trans_m = 0;
$wd_m = 0;
$fw_m = 0;
$sus_m = 0;
$dm_m = 0;
 $distrate_f =0;
$creditrate_f = 0;
$passrate_f = 0;
$comppass_f = 0;
$dif_f = 0;
$ref_f = 0;
$repeat_f = 0;
$inc_f = 0;
$trans_f = 0;
$wd_f = 0;
$fw_f = 0;
$sus_f = 0;
$dm_f = 0;


if(($code ==2001) || ($code ==2002))
{
    
       //die("here");

                $test = "SELECT  DISTINCT e.CourseCode, c.Programme
        FROM  examresult e, course c 
        WHERE 
        e.CourseCode = c.CourseCode AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
        AND e.ExamCategory ='5'
        AND c.Programme LIKE '%$code%'

        ORDER BY e.CourseCode ASC";

        $testb = "SELECT  COUNT(DISTINCT e.CourseCode) AS cha
        FROM  examresult e, course c 
        WHERE 
        e.CourseCode = c.CourseCode AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
       AND e.ExamCategory ='5'
        AND c.Programme LIKE '%$code%'
        ORDER BY e.CourseCode ASC";




        $test2 = "SELECT  DISTINCT e.RegNo, s.Name,s.Sex
        FROM  examresult e, course c , student s
        WHERE 
        e.CourseCode = c.CourseCode AND
        s.RegNo = e.RegNo AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
        AND e.ExamCategory ='5'
         AND c.Programme = '$code'
        ORDER BY s.Name ASC";       
    
    
   
    
    



}
else if($code ==10052)
{
    
       

                $test = "SELECT  DISTINCT e.CourseCode, c.Programme
        FROM  examresult e, course c 
        WHERE 
        e.CourseCode = c.CourseCode AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
        AND e.ExamCategory ='5'
        AND c.Programme = '$code'
        AND e.CourseCode LIKE '%$class%'
        ORDER BY e.CourseCode ASC";

        $testb = "SELECT  COUNT(DISTINCT e.CourseCode) AS cha
        FROM  examresult e, course c 
        WHERE 
        e.CourseCode = c.CourseCode AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
       AND e.ExamCategory ='5'
       AND c.Programme = '$code'
        AND e.CourseCode LIKE '%$class%'
        ORDER BY e.CourseCode ASC";




        $test2 = "SELECT  DISTINCT e.RegNo, s.Name,s.Sex
        FROM  examresult e, course c , student s
        WHERE 
        e.CourseCode = c.CourseCode AND
        s.RegNo = e.RegNo AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
        AND e.ExamCategory ='5'
         AND c.Programme = '$code'
        AND e.CourseCode LIKE '%$class%'
        ORDER BY s.Name ASC";       
    
    
   
    
    



}


else if($code == 10024)
{
$code2= $code -2;

$test = "SELECT  DISTINCT e.CourseCode, c.Programme
FROM  examresult e, course c 
WHERE 
e.CourseCode = c.CourseCode AND
 e.AYear > '2008'

AND c.Programme > $code2 AND c.Programme <= $code

ORDER BY c.Programme, e.CourseCode ASC
";

$testb = "SELECT  COUNT(DISTINCT e.CourseCode) AS cha
FROM  examresult e, course c 
WHERE 
e.CourseCode = c.CourseCode AND
 e.AYear > '2008'

AND c.Programme > $code2 AND c.Programme <= $code

ORDER BY c.Programme, e.CourseCode ASC
";


$testbc = "SELECT  COUNT(DISTINCT e.CourseCode) AS cha
FROM  examresult e, course c 
WHERE 
e.CourseCode = c.CourseCode AND
 e.AYear = '2010'

AND c.Programme > $code2 AND c.Programme <= $code

ORDER BY c.Programme, e.CourseCode ASC
";



$test2 = "SELECT  DISTINCT e.RegNo, s.Name,s.Sex
FROM  examresult e, course c , student s
WHERE 
e.CourseCode = c.CourseCode AND
s.RegNo = e.RegNo AND
 e.AYear LIKE CONVERT( _utf8 '2010'
USING latin1 ) 
COLLATE latin1_swedish_ci
AND e.ExamCategory ='5'
AND c.Programme = '$code'
ORDER BY s.Name ASC";

$resultbc=mysql_query($testbc);
while ($line = mysql_fetch_array($resultbc, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    $countstatus= $line["cha"];
    
    
}


}

else
{




$test = "SELECT  DISTINCT e.CourseCode, c.Programme
FROM  examresult e, course c 
WHERE 
e.CourseCode = c.CourseCode AND
 e.AYear LIKE CONVERT( _utf8 '2010'
USING latin1 ) 
COLLATE latin1_swedish_ci

AND c.Programme = '$code'
AND e.ExamCategory ='5'
ORDER BY e.CourseCode ASC";

$testb = "SELECT  COUNT(DISTINCT e.CourseCode) AS cha
FROM  examresult e, course c 
WHERE 
e.CourseCode = c.CourseCode AND
 e.AYear LIKE CONVERT( _utf8 '2010'
USING latin1 ) 
COLLATE latin1_swedish_ci
AND c.Programme = '$code'
AND e.ExamCategory ='5'
ORDER BY e.CourseCode ASC";


$testbc = "SELECT  COUNT(DISTINCT e.CourseCode) AS cha
FROM  examresult e, course c 
WHERE 
e.CourseCode = c.CourseCode AND
 e.AYear = '2010'

AND c.Programme > $code2 AND c.Programme <= $code

ORDER BY c.Programme, e.CourseCode ASC
";

$test2 = "SELECT  DISTINCT e.RegNo, s.Name,s.Sex
FROM  examresult e, course c , student s
WHERE 
e.CourseCode = c.CourseCode AND
s.RegNo = e.RegNo AND
 e.AYear LIKE CONVERT( _utf8 '2010'
USING latin1 ) 
COLLATE latin1_swedish_ci
AND e.ExamCategory ='5'
AND c.Programme = '$code'
ORDER BY s.Name ASC";

}

$query_coursecode = "
		SELECT ProgrammeName
		FROM program_year
	
		 WHERE ProgrammeCode = '$code'";


$resultb=mysql_query($query_coursecode);
while ($line = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    //$class= $line["prefix"];
    $pr= $line["ProgrammeName"];
    
    
}

//die($pr."--".$code);

  $countb = 0;
  $notassess =0;
$resultb=mysql_query($testb);
while ($line = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    $c= $line["cha"];
    
    
}



$score = array();
$cour = array();
$course1 = array();
$max = array();
$min = array();
$avgb = array();
$i = 0;
$result=mysql_query($test);
//echo "<b>$p</b><br><table border=1><tr><td><b>SN</td><td width = '300'><b><td width = '400'><b>T";

//Header
    $w=array(10,50,50,10);
   for($i=0;$i<count($header);$i++)
   {
       // $this->Cell($w[$i],7,$header[$i],1,0,'C',1);
    }
    
    $i = 0;
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    $course= $line["CourseCode"];
   // die($course);
    $cour[$i] = $line["CourseCode"];
    
    $p= $line["Programme"];
    
    $maxsql = "Select max(`ExamScore`) as max from examresult
where `AYear`='$yr' and `CourseCode`='$course' AND ExamCategory ='5'";
    //echo "<td><b>$course</td>";
    
     //$this->Cell(15,7,$course,1,0,'C',1);
     
     
    $resultmax=mysql_query($maxsql);
    while ($linemax = mysql_fetch_array($resultmax, MYSQL_ASSOC)) 
    {
    $max[$i] =  $linemax["max"];   
    
    }
     $minsql = "Select min(`ExamScore`) as min from examresult
where `AYear`='$yr' and `CourseCode`='$course' and ExamScore !='' AND ExamCategory ='5' ";
    //echo "<td><b>$course</td>";
    $resultmin=mysql_query($minsql);
    while ($linemin = mysql_fetch_array($resultmin, MYSQL_ASSOC)) 
    {
    $min[$i] =  $linemin["min"];   
    
    }
    $avgsql = "Select avg(`ExamScore`) as avg from examresult
where `AYear`='$yr' and `CourseCode`='$course' AND ExamCategory ='5'";
    //echo "<td><b>$course</td>";
    $resultavg=mysql_query($avgsql);
    while ($lineavg = mysql_fetch_array($resultavg, MYSQL_ASSOC)) 
    {
    $avgb[$i] =  $lineavg["avg"];   
    
    }
    $i++;
    
}

 //$this->Cell(15,7,"AVG",1,0,'C',1);
 //$this->Cell(30,7,"RECOMM",1,0,'C',1);

//$this->Ln();


    //Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',9);
    //Data
    $fill=0;

//student records



$result2=mysql_query($test2);
$sn = 1;
$fill=0;
$rec=0;
while ($line = mysql_fetch_array($result2, MYSQL_ASSOC)) 
{




     $dis_status = ' ';                                   
    $reg= $line["RegNo"]; 
    $sex= $line["Sex"]; 
    $testc = "SELECT Name,Status,yr_repeated FROM student WHERE RegNo ='$reg'";
    $resultc=mysql_query($testc);
          
  
while ($line2 = mysql_fetch_array($resultc, MYSQL_ASSOC)) 
{
                                          
    $name1= $line2["Name"];
    $std_status = $line2["Status"];
    $yr_rpt = $line2["yr_repeated"];
    
    
    }
    $this->SetFont('','',9);
        if($reg == 'KCN/BScN/08/016' ||$reg =='kcn/bscn/08/016' )
        {
        
            $dis_status = '--->CM1'; 
        
        
        }
          if($reg == 'kcn/bscn/08/064'  || $reg == 'KCN/BScN/08/018' )
        {
        
            $wh = 'wh';
        
        
        }
        if($std_status == 6)
        {
          $dis_status = '--->Repeater(yr'.$yr_rpt.')';  
        }
        else if($std_status == 9 )
        {
          $wh = 'wh';
                     
        }
        
        else if($std_status == 10 )
        {
          $wh = 'dif';
                     
        }
       else if($std_status == 11 )
        {
          $wh = 'trans';
                     
        }
        if($yr_rpt == 7 )
        {
          $dis_status = '--->Re-admit';
                     
        }
         
        
        
        
        
        
        $name_dis = $name1.' '.$dis_status;
        
        
        
    //$exam= $line["ExamScore"];
    $this->Cell(6,7,number_format($sn),1,0,'R',$fill);   
    $this->Cell(45,7,$reg,1,0,'L',$fill);
    $this->Cell(60,7,$name_dis,1,0,'L',$fill);
    $this->Cell(10,7,$sex,1,0,'L',$fill);


    
   // echo "<td>$sn</td><td>$reg</td><td>$name1";
   
   if($code == 10024 || $code == 1003 || $code == 10042)
   {
   
   $yr2 = $yr-1;
   $test3_avg = "SELECT  ROUND(AVG(`ExamScore`),0) as avarage
    FROM  `examresult` 
    WHERE  `AYear` = '$yr'
    AND ExamCategory ='5'
    AND  `RegNo` =  '$reg' ORDER BY CourseCode ASC";
    $result3_avg=mysql_query($test3_avg);
    
    while ($line = mysql_fetch_array($result3_avg, MYSQL_ASSOC)) 
    {
    
        $student_avg1= $line["avarage"];
        
        
        //die($student_avg1);
        }
        $test3_avg = "SELECT  ROUND(AVG(`ExamScore`),0) as avarage
    FROM  `examresult` 
    WHERE  `AYear` = '$yr2'
    AND ExamCategory ='5'
    AND  `RegNo` =  '$reg' ORDER BY CourseCode ASC";
    $result3_avg=mysql_query($test3_avg);
    
    while ($line = mysql_fetch_array($result3_avg, MYSQL_ASSOC)) 
    {
    
        $student_avg2= $line["avarage"];
        //die($student_avg1);
        }
        
        
        
        
   
   $test3 = "SELECT  `CourseCode` ,  `ExamScore` , AYear
    FROM  `examresult` 
    WHERE  `AYear` > 2008
   AND ExamCategory ='5'
    AND  `RegNo` =  '$reg' ORDER BY  AYear,CourseCode ASC";
    $result3=mysql_query($test3);
   
   
   }
   else
   {
   $test3_avg = "SELECT  ROUND(AVG(`ExamScore`),0) as avarage
    FROM  `examresult` 
    WHERE  `AYear` = '$yr'
    AND ExamCategory ='5'
    AND  `RegNo` =  '$reg' ORDER BY CourseCode ASC";
    $result3_avg=mysql_query($test3_avg);
    
    while ($line = mysql_fetch_array($result3_avg, MYSQL_ASSOC)) 
    {
    
        $student_avg1= $line["avarage"];
        //die($student_avg1);
        }
    $test3 = "SELECT  `CourseCode` ,  `ExamScore` , AYear
    FROM  `examresult` 
    WHERE  `AYear` = '$yr'
    AND ExamCategory ='5'
    AND  `RegNo` =  '$reg' ORDER BY CourseCode ASC";
    $result3=mysql_query($test3);
    }
    $i=0;
    $counta = 0;
    $cred = 0; 
    $pco = 0;    
    
   
    while ($line = mysql_fetch_array($result3, MYSQL_ASSOC)) 
    {
    
        $score[$i]= $line["ExamScore"];
        $AYear[$i] = $line["AYear"];
        $course_cha = $line["CourseCode"];
        
        
        
        
        //die($score[$i]);
        
        if($course_cha == 'NSG 401' && $score[$i] >=65 && $score[$i] <= 74 )
        {
         $cred = $cred + 1;   
         //die($cred. 'here');
        
        }
         if($course_cha == 'NSG 404' && $score[$i] >=65 &&  $score[$i] <= 74)
        {
         $cred = $cred + 1;   
        
        }
       // die('hehehe'.$countstatus);
        //die($AYear[$i]);
       
        
        
        $course1[$i] = $line["CourseCode"];
        
       

        
        
        if( $score[$i] < 50 && $score[$i])
        {
        
        $counta +=1;
        
        }
        else if( $score[$i] == " " || $score[$i] == 0)
        {
        
        //die('herehere');
       $score[$i] = '--';
        $notassess +=1;
        
        }
        
        //$programme =
        //die($course1[$i]."heree");
        
             $i++;
        
     
    }
    
  
    
    
    //pass remark
    $passd1 = "SELECT  COUNT(`ExamScore`) as score
    FROM  `examresult` 
    WHERE  `AYear` > '2008'
    AND ExamCategory ='5'
    AND  `RegNo` =  '$reg' 
    AND  ExamScore >= 50  
    ORDER BY CourseCode ASC";
    $resultpassd=mysql_query($passd1);
    
    while ($line = mysql_fetch_array($resultpassd, MYSQL_ASSOC)) 
    {
    
        $pass_1= $line["score"];
        //die($pass_1);
        }
    
    //credit remark
    
    $diu1 = "SELECT  COUNT(`ExamScore`) as score
    FROM  `examresult` 
    WHERE  `AYear` = '$yr'
    AND ExamCategory ='5'
    AND  `RegNo` =  '$reg' 
    AND  ExamScore >= 60 
    ORDER BY CourseCode ASC";
    $resultdiu=mysql_query($diu1);
    
    while ($line = mysql_fetch_array($resultdiu, MYSQL_ASSOC)) 
    {
    
        $scorediu= $line["score"];
        //die($student_avg1);
        }
        $diu12 = "SELECT  COUNT(`ExamScore`) as score
    FROM  `examresult` 
    WHERE  `AYear` = '$yr'
    AND ExamCategory ='5'
    AND  `RegNo` =  '$reg' 
    AND  ExamScore >= 70
    ORDER BY CourseCode ASC";
    $resultdiu2b=mysql_query($diu12);
    
    while ($line = mysql_fetch_array($resultdiu2b, MYSQL_ASSOC)) 
    {
    
        $scorediur= $line["score"];
        //die($student_avg1);
        }
 
    $diu2 = "
SELECT  COUNT(`ExamScore`) as score
    FROM  `examresult` 
    WHERE  `AYear` = '2010'
    AND ExamCategory ='5'
    AND  `RegNo` =  '$reg' 
    AND  CourseCode = 'NSG 401'   AND ExamScore >= 65";
    $resultdiu2=mysql_query($diu2);
    
    while ($line = mysql_fetch_array($resultdiu2, MYSQL_ASSOC)) 
    {
    
        $scorediu2= $line["score"];
        //die($student_avg1);
        }


$diu2 = "SELECT  COUNT(`ExamScore`) as score
    FROM  `examresult` 
    WHERE  `AYear` = '2010'
    AND ExamCategory ='5'
    AND  `RegNo` =  '$reg' 
    AND  CourseCode = 'MID 501'   AND ExamScore >= 65";
    $resultdiu2=mysql_query($diu2);
    
    while ($line = mysql_fetch_array($resultdiu2, MYSQL_ASSOC)) 
    {
    
        $scorediuucm= $line["score"];
        //die($student_avg1);
        }


        $diu3 = "
SELECT  COUNT(`ExamScore`) as score
    FROM  `examresult` 
    WHERE  `AYear` = '2010'
    AND ExamCategory ='5'
    AND  `RegNo` =  '$reg' 
    AND  CourseCode = 'NSG 404'   AND ExamScore >= 65";
    $resultdiu3=mysql_query($diu3);
    
    while ($line = mysql_fetch_array($resultdiu3, MYSQL_ASSOC)) 
    {
    
        $scorediu3= $line["score"];
        //die($student_avg1);
        }
    if($scorediu == $countstatus && $scorediu2 == 1 && $scorediu2 == 1 && $student_avg2 >= 65 && $student_avg1 >= 65 && $score[$i] <= 74)
    {
        //die('here'.$reg);
       // die($scorediu.'atlast');
       $credit = 'passed';
    }
    //die($countstatus.'d' );
    
     if($code==1003 && $scorediu == 4 && $scorediuucm == 1   && $student_avg1 >= 65 && $score[$i] <= 74)
    {
        //die('here'.$reg);
       // die($scorediu.'atlast');
       $credit = 'passed';
    }
    
    
    
       if($scorediu == $countstatus  && $student_avg2 >= 75 && $student_avg1 >= 75 && $score[$i] <= 100)
    {
        //die('here'.$reg);
       // die($scorediu.'atlast'); 
       //die('ll'.$reg);
       $credit = 'dist';
    }
    
    if($pass_1 == $c)
    {
        
        $passfull= 'passed';
        
        
    
    }
    
     if($reg == 'kcn/bscn/08/064'  || $reg == 'KCN/BScN/08/018' )
        {
        
            $wh = 'wh';
        
        
        }
    
    
    if($counta == 0 && $notassess == 0)
    {
    
    $countb +=1;
    
    }
    
    if($notassess >= 1)
    {
        $notassessb +=1;
    
    }
    
 
        $x=0;
        $sum=0;
         if($reg == 'kcn/bscn/08/064'  || $reg == 'KCN/BScN/08/018' || $reg == 'kcn/bscn/09/091' || $reg == 'kcn/bscn/09/091' || $reg =='kcn/bscn/09/098' ||$reg  == 'KCN/BScN/08/046')
        {
        
           // $wh = 'wh';
           // $rec=100;
        $this->Cell(6,7,'--',1,0,'R',$fill);
        $this->Cell(6,7,'--',1,0,'R',$fill);
        $this->Cell(6,7,'--',1,0,'R',$fill);
        $this->Cell(6,7,'--',1,0,'R',$fill);
        $this->Cell(6,7,'--',1,0,'R',$fill);
        $this->Cell(6,7,'--',1,0,'R',$fill);
        $this->Cell(6,7,'--',1,0,'R',$fill);
        $this->Cell(6,7,'--',1,0,'R',$fill);
        $this->Cell(6,7,'--',1,0,'R',$fill);
        $this->Cell(6,7,'--',1,0,'R',$fill);
        }
        
    for ($i=0; $i<$c; $i++)
    {
    
                        
      
    
     $this->SetFont('','',9);
     if($reg == 'kcn/bscn/08/064'  || $reg == 'KCN/BScN/08/018' || $reg == 'kcn/bscn/09/091' || $reg == 'kcn/bscn/09/091' || $reg =='kcn/bscn/09/098' || $reg  == 'KCN/BScN/08/046')
        {
        
        if($reg =='kcn/bscn/09/098')
        {
        
         $wh = 'trans';
          $rec=100;
        }
        {
            $wh = 'wh';
            $rec=100;
            }
        
        
        
        }
        
        
                else
     {
        if($x <> $c)
        { 

               if($reg == 'KCN/BScN/08/045' && $course1[$i] =='REP 308')
                {
                //die();
                
                
                }
                else if($reg == 'kcn/bscn/08/049' && $course1[$i] =='REP 308')
                {
                
                
                }
                 else if($reg == 'kcn/bscn/08/049' && $course1[$i] =='RES 304')
                {
                
                
                }
                else if($reg == 'KCN/BScN/08/046' && $course1[$i] =='REP 308')
                {
                        $this->Cell(6,7,'--',1,0,'R',$fill); 
                        //$this->Cell(6,7,'--',1,0,'R',$fill); 
                        //$this->Cell(6,7,'--',1,0,'R',$fill); 
                
                }
                else if($reg == 'KCN/BScN/08/046' && $course1[$i] =='REP 309')
                {
                
                //die();
                        $this->Cell(6,7,'--',1,0,'R',$fill); 
                        //$this->Cell(6,7,'--',1,0,'R',$fill); 
                        //$this->Cell(6,7,'--',1,0,'R',$fill); 
                
                }
                else
        
            if ($cour[$i] == $course1[$i])
            {
                        //if($score[$i] == "" || $score[$i] == " ")
                           // {
                            //    $score[$i] = 0;
                            //}  
                           //
                          // die($score[$i].'---'.$i);
                          
                           if (number_format($score[$i]) < 50 && number_format($score[$i]) <> 0  )
                           {
                           
                          
                            if($course1[$i] =='PMID SC 501' && $score[$i] < 50 )
                            { 
                            
                            
                              $ucmcheck = $score[$i];
                              
                              if(rec ==1)
                              {
                                //die($ucmcheck.'rec='.$rec); 
                                $ucmcheck2 = $score[$i];                                
                              }
                             
                            }
                            else
                            if($course1[$i] =='SOC 501' && $score[$i] < 50 )
                            { 
                            
                            
                                $postcheck= $score[$i]; 
                             
                                            
                                 
                            }
                            else
                            {
                            
                            $ucmcheck ='dummy';
                            }

                                    if($AYear[$i] == 2010 && $AYear[$i-1] == 2009 )
                                {
                                    $this->SetFillColor(57,127,145);
                                    $this->SetTextColor(255);
                                    $this->SetDrawColor(57,127,145);
                                     $this->SetLineWidth(.3);
                                     $this->SetFont('','B',9);

                                    $this->Cell(2,7,'',1,0,'R',1);   
                                    
                                     $this->SetFillColor(224,235,255);
                                    $this->SetTextColor(0);
                                     $this->SetFont('','',9);
                                
                                }
                                
                                
                                
                                $this->SetFont('','B');
                            $this->Cell(6,7,number_format($score[$i]),1,0,'R',$fill);
                            $rec +=1;     

                        //echo "<td><b>$score[$i]</td>";
                        }
                        else if (number_format($score[$i]) >= 50)
                        {
                        
                                if($AYear[$i] == 2010 && $AYear[$i-1] == 2009 )
                                {
                                    $this->SetFillColor(57,127,145);
                                    $this->SetTextColor(255);
                                    $this->SetDrawColor(57,127,145);
                                     $this->SetLineWidth(.3);
                                     $this->SetFont('','B',9);

                                    $this->Cell(2,7,'',1,0,'R',1);   
                                    
                                     $this->SetFillColor(224,235,255);
                                    $this->SetTextColor(0);
                                     $this->SetFont('','',9);
                                
                                }
                                $this->SetFont('','',9);
                         $this->Cell(6,7,number_format($score[$i]),1,0,'R',$fill);   
                        //echo "<td>$score[$i]</td>";
                            
                        }
                        else
                        {
                        
                        
                        $this->Cell(6,7,'--',1,0,'R',$fill); 
                        $rec = 100;  
                        }
                        
                        $sum = $sum + $score[$i];
                        
            
            }
            else if ($cour[$i] <> $course1[$i])
            {
            
            
            
                $a = $i;
                for ($x=$i; $x<$c; $x++)
                {
                
                
                
                //die($cour[$x].'--'.$course1[$a].'--'.$score[$a].'---'.$i);

                
                    if ( $cour[$x] == $course1[$a] )
                    {
                    //die($score[$a]."check val");
                   
                        if($reg == "KCN/BSCN/09/091" && $course1[$a] =='NUR 202' )
                        {
                            //die(number_format($score[$i]).'checking');
                          // die($score[$i].' jweojWq '. $course1[$i].' '.$cour[$x]);
                        
                        }
                        
                        if($score[$a]=='--')
                        {
                        $this->Cell(6,6,'--',1,0,'R',$fill); 
                        //$rec = 100;                          
                        }
                      else if (number_format($score[$a]) < 50 && number_format($score[$a]) <> 0)
                            {
                         
                                $ucmcheck = $score[$a];    
                          
                                if($AYear[$a] == 2010 && $AYear[$a-1] == 2009 )
                                 {
                                    $this->SetFillColor(57,127,145);
                                    $this->SetTextColor(255);
                                    $this->SetDrawColor(57,127,145);
                                     $this->SetLineWidth(.3);
                                     $this->SetFont('','B',9);

                                    $this->Cell(2,7,'',1,0,'R',1);   
                                    
                                     $this->SetFillColor(224,235,255);
                                    $this->SetTextColor(0);
                                     $this->SetFont('','',9);
                        
                                } 
                                 $this->Cell(6,6,number_format($score[$a]),1,0,'R',$fill);  
                            
                            
                                $this->SetFont('','B');
                          
                                     $rec +=1; 
                             }
                             else if (number_format($score[$a]) >= 50)
                            {    

                                            if($AYear[$a] == 2010 && $AYear[$a-1] == 2009 )
                                    {
                                        $this->SetFillColor(57,127,145);
                                        $this->SetTextColor(255);
                                        $this->SetDrawColor(57,127,145);
                                         $this->SetLineWidth(.3);
                                         $this->SetFont('','B',9);

                                        $this->Cell(2,7,'',1,0,'R',1);   
                                        
                                         $this->SetFillColor(224,235,255);
                                        $this->SetTextColor(0);
                                         $this->SetFont('','',9);
                                    
                                    }
                                $this->SetFont('','',9);
                                
                                   
                                        $this->Cell(6,6,number_format($score[$a]),1,0,'R',$fill);  
                                     $x=$c;
                                     
                                    
                                     
                            }
                             else
                             {
                             $this->Cell(6,6,'--',1,0,'R',$fill);  
                             $rec = 100;  
                             }
                                                     
                                        
                            //echo "<td><b>$score[$a]</td>";
                        
                                               
                        $sum = $sum + $score[$a];
                         $a +=1;
                     }
                     
                                           
                
                }
                
                //</tr><tr>";
            
            }
            else
            {
             
             if($AYear[$i] == 2010 && $AYear[$i-1] == 2009 )
                        {
                            $this->SetFillColor(57,127,145);
                            $this->SetTextColor(255);
                            $this->SetDrawColor(57,127,145);
                             $this->SetLineWidth(.3);
                             $this->SetFont('','B',9);
                             

                            $this->Cell(2,7,'',1,0,'R',1);   
                            
                             $this->SetFillColor(224,235,255);
                            $this->SetTextColor(0);
                             $this->SetFont('','',9);
                              $AYear[$a - 1] = 2010;
                        
                        }
                        $this->Cell(6,6,'--',1,0,'R',$fill); 
                        $rec = 100;                        
                //echo "<td><b>--</td>";
            
            }
    //
       
        }       
   
    
  //  $x +=1;
  }
    }
    $avg = $sum/$c;
    
  //die($class."here");
  
    if ($code == 1003 && $reg=='0501/23/BScN/UCM')
  {
  
        if($avg < 50)
        {
         $this->Cell(6,6,'--',1,0,'R',$fill); 
         $this->SetFont('','B');
         $this->Cell(8,6,number_format($student_avg1),1,0,'R',$fill);         
            //echo "<td><b><font color='red'>".number_format($avg,1,'.',',')."</font></b></td>";
        }
        else
        {
        $this->Cell(17,6,'--',1,0,'R',$fill); 
        // $this->Cell(17,6,number_format($student_avg2),1,0,'R',$fill);   
         $this->Cell(17,6,number_format($student_avg1),1,0,'R',$fill); 
                //echo "<td><b>".number_format($avg,1,'.',',')."</b></td>";
        }
    }
    else
  if ($code == 10024)
  {
   $this->SetFont('','B');
        if($avg < 50)
        {
         $this->Cell(17,6,number_format($student_avg2),1,0,'R',$fill); 
         $this->Cell(17,6,number_format($student_avg1),1,0,'R',$fill);         
            //echo "<td><b><font color='red'>".number_format($avg,1,'.',',')."</font></b></td>";
        }
        else
        {
         $this->Cell(17,6,number_format($student_avg2),1,0,'R',$fill);   
         $this->Cell(17,6,number_format($student_avg1),1,0,'R',$fill); 
                //echo "<td><b>".number_format($avg,1,'.',',')."</b></td>";
        }
    }
    else
    {
     $this->SetFont('','B');
     if($avg < 50)
        {
         $this->Cell(8,6,number_format($student_avg1),1,0,'R',$fill);   
            //echo "<td><b><font color='red'>".number_format($avg,1,'.',',')."</font></b></td>";
        }
        else
        {
         $this->Cell(8,6,number_format($student_avg1),1,0,'R',$fill);   
                //echo "<td><b>".number_format($avg,1,'.',',')."</b></td>";
                }
    
    
    }
    
    //gender for summmary purpose
    
    $gender_summary = "SELECT  RegNo,Sex FROM student WHERE RegNo = '$reg' ";
           //die($gender_summary);
    $result_gender_summary=mysql_query($gender_summary);
    
    while ($line = mysql_fetch_array($result_gender_summary, MYSQL_ASSOC)) 
    {
    
        $gender_sum = $line["Sex"];
        //die($gender_sum.'eeeeeeeeeee');
        //die($student_avg1);
    }
    
   //end of gender query
    
    
    
    
        
        if($rec == 0)
        {
        
        //die($cred. 'hweere');
        
            if($code == 10024)
            {
                    //die($cred.'here');
            
            
                if($credit == 'passed')
                {
                    $this->Cell(30,6,'CR',1,0,'R',$fill);
                    $credit = 'fail';
                    
                    if($gender_sum=='F')
                    {
                    
                        $creditrate_f +=1;
                    }
                    else
                    {
                        $creditrate_m +=1;
                    
                    }
                    
                    
                    
                }
                else if($credit == 'dist')
                {
                    $this->Cell(30,6,'DIS',1,0,'R',$fill);
                    $credit = 'fail';
                    
                    if($gender_sum=='F')
                    {
                    
                        $distrate_f +=1;
                    }
                    else
                    {
                        $distrate_m +=1;
                    
                    }
                    
                    
                }
                else
                if($passfull=='passed')
                {
                
                 $this->Cell(30,6,'P',1,0,'R',$fill);
                 $passfull = 'fail';
                 
                 if($gender_sum=='F')
                    {
                    
                        $passrate_f +=1;
                    }
                    else
                    {
                        $passrate_m +=1;
                    
                    }
                 
                }
                
                else
                {
                    
                    // $this->Cell(30,6,'PASS',1,0,'R',$fill);
                }
                               

            }
            else
            {
                if($code ==1003)
                {
                
                
                    if($credit == 'passed')
                {
                    $this->Cell(30,6,'CR',1,0,'R',$fill);
                    $credit = 'fail';
                    
                    if($gender_sum=='F')
                    {
                        //die()
                        $creditrate_f +=1;
                    }
                    else
                    {
                        $creditrate_m +=1;
                    
                    }
                    
                    
                }
                else if($credit == 'dist')
                {
                    $this->Cell(30,6,'DIS',1,0,'R',$fill);
                    $credit = 'fail';
                }
                else
                {
                
                        if($wh == 'wh')
                        {
                     
                            $this->Cell(30,6,'WD',1,0,'R',$fill);
                            $wh = '';
                            if($gender_sum=='F')
                            {
                            
                               $wd_f +=1;
                            }
                        else
                        {
                            $wd_m +=1;
                        
                        }
                        
                        
                        
                        }
                        else if($wh == 'trans')
                        {
                             if($gender_sum=='F')
                            {
                            
                               $trans_f +=1;
                            }
                            else
                            {
                                $trans_m +=1;
                            
                            }
                            
                            $this->Cell(30,6,'TR',1,0,'R',$fill);
                            
                         $wh = '';
                        }
                        else if($wh == 'dif')
                        {
                        
                        
                           $this->Cell(30,6,'DEF',1,0,'R',$fill); 
                            $wh = '';
                        
                        }
                        else
                        {
                    
                    $this->Cell(30,6,'P',1,0,'R',$fill);
                    
                    
                     if($gender_sum =='F')
                    {
                        $passrate_f +=1;
                        
                    }
                    else
                    {
                        $passrate_m +=1;
                    
                    }
                    }
                    }
                    
                }
                else
                {
                
                
                
                $this->Cell(30,6,'PP',1,0,'R',$fill);
                
                    if($gender_sum =='F')
                    {
                        $passrate_f +=1;
                        
                    }
                    else
                    {
                        $passrate_m +=1;
                    
                    }
                
                
                }
            }
         }
         
         else if($rec >=100)
         {
         
         
         
            if($wh == 'wh')
            {
            
                        if($reg =='kcn/bscn/09/098')
                {
                
                $this->Cell(30,6,'TR',1,0,'R',$fill);
                }
                else
                {
        

         
            $this->Cell(30,6,'WD',1,0,'R',$fill);
            }
            $wh = '';
            
            if($gender_sum=='F')
                    {
                    
                       $wd_f +=1;
                    }
                    else
                    {
                        $wd_m +=1;
                    
                    }
            
            }
            
             else if($wh == 'trans')
                        {
                             if($gender_sum=='F')
                            {
                            
                               $trans_f +=1;
                            }
                            else
                            {
                                $trans_m +=1;
                            
                            }
                            
                            $this->Cell(30,6,'TR',1,0,'R',$fill);
                            
                         $wh = '';
                        }
            
            else if($wh == 'dif')
            {
            
            
               $this->Cell(30,6,'DEF',1,0,'R',$fill); 
                $wh = '';
            
            }
            else
            {
                $this->Cell(30,6,'INC',1,0,'R',$fill);
                
                
                 if($gender_sum =='F')
                    {
                          $inc_f +=1;
                        
                    }
                    else
                    {
                        $inc_m +=1;
                    
                    }
            
            }
         
         }
          else if($rec == 1)
         {
         
            
             if($code ==1003 && $ucmcheck >=45 && $ucmcheck < 50)
                {
                
                 
                
                $this->Cell(30,6,'CP',1,0,'R',$fill); 
                
                
                 if($gender_sum =='F')
                    {
                          $comppass_f +=1;
                        
                    }
                    else
                    {
                        $comppass_m +=1;
                    
                    }
                
                $ucmcheck=0;
                //$ucmcheck=0;
                }
                else
                if($code ==1005 && $postcheck >=45 && $postcheck < 50)
                {
                
                
                
                $this->Cell(30,6,'CP',1,0,'R',$fill);

                 if($gender_sum =='F')
                    {
                          $comppass_f +=1;
                        
                    }
                    else
                    {
                        $comppass_m +=1;
                    
                    }
                
                $postcheck=0;
                //$ucmcheck=0;
                }
                else if($code ==1003 && $ucmcheck == '')
                {
                    $this->Cell(30,6,'INC',1,0,'R',$fill); 
                    if($gender_sum =='F')
                    {
                          $inc_f +=1;
                        
                    }
                    else
                    {
                        $inc_m +=1;
                    
                    }
                    //$ucmcheck=0;
                $ucmcheck=0;
                }
                
                else
                {
                
                $this->Cell(30,6,'REF',1,0,'R',$fill); 
                $REF +=1; 
                
                
                    if($gender_sum =='F')
                    {
                         $ref_f +=1;
                        
                    }
                    else
                    {
                         $ref_m +=1;
                    
                    }
               
            
            }
         
         }
         else if($rec == 2)
         {
            if($code ==1003 && $ucmcheck2 < 45 && $ucmcheck2 <> '' )
            {
            
            //die($ucmcheck2.'ee' );
            
             if($gender_sum =='F')
                    {
                         $repeat_f +=1;
                        
                    }
                    else
                    {
                         $repeat_m +=1;
                    
                    }
            
                $this->Cell(30,6,'REP',1,0,'R',$fill);
                $ucmcheck2 =0;
            }
            else
            {
             if($gender_sum =='F')
                    {
                         $ref_f +=1;
                        
                    }
                    else
                    {
                         $ref_m +=1;
                    
                    }
                $this->Cell(30,6,'REF',1,0,'R',$fill);
            }
            $REF +=1;
                        
         
         }
         else if($rec >= 3)
         {
         
            
            
            if($wh == 'wh')
            {
         
            $this->Cell(30,6,'WD',1,0,'R',$fill);
            
            
            
                if($gender_sum=='F')
                    {
                    
                       $wd_f +=1;
                    }
                    else
                    {
                        $wd_m +=1;
                    
                    }
            }
            else if($wh == 'dif')
            {
            
            
               $this->Cell(30,6,'DEF',1,0,'R',$fill); 
            
            }
            else
            {
        
            if($code ==1001 || $code ==10013)
            {
            
            
                    if($rec > 3)
                    {
                            if($gender_sum =='F')
                        {
                             $repeat_f +=1;
                            
                        }
                        else
                        {
                             $repeat_m +=1;
                        
                        }
            
            
             $this->Cell(30,6,'REP',1,0,'R',$fill); 
            $rept +=1;
                    }
                    else
                    {
                        if($gender_sum =='F')
                        {
                             $ref_f +=1;
                            
                        }
                        else
                        {
                             $ref_m +=1;
                        
                        }
                     $this->Cell(30,6,'REF',1,0,'R',$fill); 
                     $REF +=1; 
                    }
            
            
            }
            else  if($code ==1003)
            {
                $this->Cell(30,6,'FW',1,0,'R',$fill);
                if($gender_sum =='F')
                    {
                         $fw_f +=1;
                        
                    }
                    else
                    {
                          $fw_m +=1;
                    
                    }
            }
            else
            {
            if($gender_sum =='F')
                    {
                         $repeat_f +=1;
                        
                    }
                    else
                    {
                         $repeat_m +=1;
                    
                    }
            
            
             $this->Cell(30,6,'REP',1,0,'R',$fill); 
            $rept +=1;
            }
            }
         }
          else if($rec >= 5 && $code=1001)
         {
         
            $this->Cell(30,6,'FW',1,0,'R',$fill); 
            $rept +=1;
            if($gender_sum =='F')
                    {
                         $fw_f +=1;
                        
                    }
                    else
                    {
                          $fw_m +=1;
                    
                    }
         
         }
         else if($rec >= 4 && $code=10012)
         {
         
            $this->Cell(30,6,'FW',1,0,'R',$fill); 
            $rept +=1;
         if($gender_sum =='F')
                    {
                         $fw_f +=1;
                        
                    }
                    else
                    {
                          $fw_m +=1;
                    
                    }
         }
         else if($rec >= 5 && $code=10013)
         {
         
            $this->Cell(30,6,'FW',1,0,'R',$fill); 
            $rept +=1;
            if($gender_sum =='F')
                    {
                         $fw_f +=1;
                        
                    }
                    else
                    {
                          $fw_m +=1;
                    
                    }
         
         }
         else if($rec >= 3 && $code=1002)
         {
         
            $this->Cell(30,6,'FW',1,0,'R',$fill); 
            $rept +=1;
            
            if($gender_sum =='F')
                    {
                         $fw_f +=1;
                        
                    }
                    else
                    {
                          $fw_m +=1;
                    
                    }
         
         }
         
        $this->Ln();
    // echo "<td><td></tr><tr>";
     
      $this->SetFont('','');
     //reset array
     for ($r=0; $r<$c; $r++)
     {
     $score[$r] = 0;
     $course1[$i] = '';
     
     }
     
     
     $sn +=1;
     
     
     $fill=!$fill;
     $rec = 0;
     
     
     $cred = 0;  
     
     
     
     
}
$this->Ln(3);

//echo "<tr><td>&nbsp;</tr><tr><td><td><td><b>Highest Score:";
 for ($r=0; $r<$c; $r++)
     {
     //echo "<td><b>$max[$r]</td>";
     
     }
     //echo "</tr><tr><td><td><td><b>Lowest Score:";
     for ($r=0; $r<$c; $r++)
     {
     //echo "<td><b>$min[$r]</td>";
     
     }
     //echo "</tr><tr><td><td><td><b>Avarage Score:";
     for ($r=0; $r<$c; $r++)
     {
    // echo "<td><b>".number_format($avgb[$r],1,'.',',')."</td>";
     
     }
     $sn -=1;
     



$this->AddPage('L');

//end student records
//
    
    
    //calling summary method
    //die($passrate_m.'eeeeeeeeeee');
    $this->summary($pr,$passrate_m,$passrate_f,$creditrate_m,$creditrate_f,$distrate_f,$distrate_m,$code,$comppass_m,$dif_m,$ref_m,$repeat_m,$inc_m,$trans_m,$wd_m,$fw_m,$sus_m,$dm_m ,$comppass_f,$dif_f,$ref_f,$repeat_f,$inc_f,$trans_f,$wd_f,$fw_f,$sus_f,$dm_f);
    
    
}
//summary of grades

function summary($pr,$passrate_m,$passrate_f,$creditrate_m,$creditrate_f,$distrate_f,$distrate_m,$code,$comppass_m,$dif_m,$ref_m,$repeat_m,$inc_m,$trans_m,$wd_m,$fw_m,$sus_m,$dm_m ,$comppass_f,$dif_f,$ref_f,$repeat_f,$inc_f,$trans_f,$wd_f,$fw_f,$sus_f,$dm_f)
{




//require_once('../Connections/sessioncontrol.php');
require_once('../Connections/zalongwa.php');

$this->AddPage('L');

    //Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',9);
    //Data
    $fill=0;

  
    
   //die('pass rate female '.$passrate_f.'male '.$passrate_m);

$this->SetFont('','B');

$this->Cell(0,6,$pr." SUMMARY OF RESULTS ",1,1,'L',1);
$this->Ln(5);
if($code == 10024 || $code == 10052 || $code == 1003 )
{
              
 $this->Cell(35,6,'',1,0,'R',1); 
 $this->Cell(25,6,'No. of Stud',1,0,'R',1); 
 $this->Cell(10,6,'DIS',1,0,'R',1); 
 $this->Cell(10,6,'CR',1,0,'R',1); 
 $this->Cell(10,6,'P',1,0,'R',1); 
 $this->Cell(10,6,'CP',1,0,'R',1); 
 $this->Cell(10,6,'DF',1,0,'R',1); 
 $this->Cell(10,6,'REF',1,0,'R',1); 
  $this->Cell(10,6,'REP',1,0,'R',1);
  $this->Cell(10,6,'INC',1,0,'R',1);
 $this->Cell(10,6,'TR',1,0,'R',1);
  $this->Cell(10,6,'WD',1,0,'R',1);
    $this->Cell(10,6,'FW',1,0,'R',1);
     $this->Cell(10,6,'SUS',1,0,'R',1);
      $this->Cell(10,6,'DM',1,0,'R',1);
      
      }
       else if($code ==1005)
      {
      
               $this->Cell(35,6,'',1,0,'R',1); 
 $this->Cell(25,6,'No. of Stud',1,0,'R',1); 
 
 $this->Cell(10,6,'PP',1,0,'R',1); 
 $this->Cell(10,6,'CP',1,0,'R',1); 
 $this->Cell(10,6,'DF',1,0,'R',1); 
 $this->Cell(10,6,'REF',1,0,'R',1); 
  $this->Cell(10,6,'REP',1,0,'R',1);
  
 $this->Cell(10,6,'TR',1,0,'R',1);
  $this->Cell(10,6,'WD',1,0,'R',1);
    $this->Cell(10,6,'FW',1,0,'R',1);
     $this->Cell(10,6,'SUS',1,0,'R',1);
      $this->Cell(10,6,'DM',1,0,'R',1);
      
      
      }
      else
      {
      
               $this->Cell(35,6,'',1,0,'R',1); 
 $this->Cell(25,6,'No. of Stud',1,0,'R',1); 
 
 $this->Cell(10,6,'PP',1,0,'R',1); 
 $this->Cell(10,6,'DF',1,0,'R',1); 
 $this->Cell(10,6,'REF',1,0,'R',1); 
  $this->Cell(10,6,'REP',1,0,'R',1);
  
 $this->Cell(10,6,'TR',1,0,'R',1);
  $this->Cell(10,6,'WD',1,0,'R',1);
    $this->Cell(10,6,'FW',1,0,'R',1);
     $this->Cell(10,6,'SUS',1,0,'R',1);
      $this->Cell(10,6,'DM',1,0,'R',1);
      
      
      }
      
      
 $this->Ln(5);
 
 $diu2 = "SELECT  COUNT(DISTINCT e.RegNo) as num, s.Sex
FROM  examresult e, course c , student s
WHERE 
e.CourseCode = c.CourseCode AND
s.RegNo = e.RegNo AND
 e.AYear LIKE CONVERT( _utf8 '2010'
USING latin1 ) 
COLLATE latin1_swedish_ci

AND c.Programme = '$code'
GROUP BY s.Sex
	";
 //die($code);
   $resultdiu2=mysql_query($diu2);
    
  while ($line = mysql_fetch_array($resultdiu2, MYSQL_ASSOC)) 
    {
   //die('heheheeewwh');
    
    
        $num= $line["num"];
        $sex = $line["Sex"];
                    //die($student_avg1);
                    
   //year                  
//year four Generic template

                
                    
            if($code == 10024 || $code == 10052 || $code == 1003 )
            {                 
                    
              
             if($sex =='F')
             {
              $total_student = $total_student + number_format($num);
                 $this->Cell(35,6,'FEMALE',1,0,'R',1); 
                 $this->Cell(25,6,number_format($num),1,0,'R',1); 
                 $this->Cell(10,6,$distrate_f,1,0,'R',1); 
                 $this->Cell(10,6,$creditrate_f,1,0,'R',1); 
                  $this->Cell(10,6,$passrate_f,1,0,'R',1); 
                  $this->Cell(10,6,$comppass_f,1,0,'R',1); 
                 $this->Cell(10,6,$dif_f,1,0,'R',1); 
                 $this->Cell(10,6,$ref_f,1,0,'R',1); 
                  $this->Cell(10,6,$repeat_f,1,0,'R',1);
                  $this->Cell(10,6,$inc_f,1,0,'R',1);
                 $this->Cell(10,6,$trans_f,1,0,'R',1);
                  $this->Cell(10,6,$wd_f,1,0,'R',1);
                   $this->Cell(10,6,$fw_f,1,0,'R',1);
                    $this->Cell(10,6,$sus_f,1,0,'R',1);
                     $this->Cell(10,6,$dm_f,1,0,'R',1);
                      
                  }
                  else
                  {
                  
                  $total_student = $total_student + number_format($num);
                     $this->Cell(35,6,'MALE',1,0,'R',1);
                     $this->Cell(25,6,number_format($num),1,0,'R',1); 
                     $this->Cell(10,6,$distrate_m,1,0,'R',1); 
                     $this->Cell(10,6,$creditrate_m,1,0,'R',1); 
                     $this->Cell(10,6,$passrate_m,1,0,'R',1); 
                    $this->Cell(10,6,$comppass_m,1,0,'R',1); 
                     $this->Cell(10,6,$dif_m,1,0,'R',1); 
                     $this->Cell(10,6,$ref_m,1,0,'R',1); 
                      $this->Cell(10,6,$repeat_m,1,0,'R',1);
                      $this->Cell(10,6,$inc_m,1,0,'R',1);
                     $this->Cell(10,6,$trans_m,1,0,'R',1);
                      $this->Cell(10,6,$wd_m,1,0,'R',1);
                       $this->Cell(10,6,$fw_m,1,0,'R',1);
                        $this->Cell(10,6,$sus_m,1,0,'R',1);
                         $this->Cell(10,6,$dm_m,1,0,'R',1);
                         
                                                  
             }
             
             }
             
             else if($code == 1005)
             {
             
                   
             if($sex =='F')
             {
              $total_student = $total_student + number_format($num);
                 $this->Cell(35,6,'FEMALE',1,0,'R',1); 
                 $this->Cell(25,6,number_format($num),1,0,'R',1); 
                  $this->Cell(10,6,$passrate_f,1,0,'R',1); 
                  $this->Cell(10,6,$comppass_f,1,0,'R',1); 
                 $this->Cell(10,6,$dif_f,1,0,'R',1); 
                 $this->Cell(10,6,$ref_f,1,0,'R',1); 
                  $this->Cell(10,6,$repeat_f,1,0,'R',1);
                   $this->Cell(10,6,$trans_f,1,0,'R',1);
                  $this->Cell(10,6,$wd_f,1,0,'R',1);
                   $this->Cell(10,6,$fw_f,1,0,'R',1);
                    $this->Cell(10,6,$sus_f,1,0,'R',1);
                     $this->Cell(10,6,$dm_f,1,0,'R',1);
                      
                  }
                  else
                  {
                  
                  $total_student = $total_student + number_format($num);
                     $this->Cell(35,6,'MALE',1,0,'R',1);
                     $this->Cell(25,6,number_format($num),1,0,'R',1); 
                     $this->Cell(10,6,$passrate_m,1,0,'R',1); 
                     $this->Cell(10,6,$comppass_m,1,0,'R',1); 
                      $this->Cell(10,6,$dif_m,1,0,'R',1); 
                     $this->Cell(10,6,$ref_m,1,0,'R',1); 
                      $this->Cell(10,6,$repeat_m,1,0,'R',1);
                     $this->Cell(10,6,$trans_m,1,0,'R',1);
                      $this->Cell(10,6,$wd_m,1,0,'R',1);
                       $this->Cell(10,6,$fw_m,1,0,'R',1);
                        $this->Cell(10,6,$sus_m,1,0,'R',1);
                         $this->Cell(10,6,$dm_m,1,0,'R',1);
                         
                                                  
             }
             
             
             }
             
             else
             
             {
             
             // year 1 to 3 generic
             
              
             if($sex =='F')
             {
              $total_student = $total_student + number_format($num);
                 $this->Cell(35,6,'FEMALE',1,0,'R',1); 
                 $this->Cell(25,6,number_format($num),1,0,'R',1); 
                  $this->Cell(10,6,$passrate_f,1,0,'R',1); 
                 $this->Cell(10,6,$dif_f,1,0,'R',1); 
                 $this->Cell(10,6,$ref_f,1,0,'R',1); 
                  $this->Cell(10,6,$repeat_f,1,0,'R',1);
                   $this->Cell(10,6,$trans_f,1,0,'R',1);
                  $this->Cell(10,6,$wd_f,1,0,'R',1);
                   $this->Cell(10,6,$fw_f,1,0,'R',1);
                    $this->Cell(10,6,$sus_f,1,0,'R',1);
                     $this->Cell(10,6,$dm_f,1,0,'R',1);
                      
                  }
                  else
                  {
                  
                  $total_student = $total_student + number_format($num);
                     $this->Cell(35,6,'MALE',1,0,'R',1);
                     $this->Cell(25,6,number_format($num),1,0,'R',1); 
                     $this->Cell(10,6,$passrate_m,1,0,'R',1); 
                      $this->Cell(10,6,$dif_m,1,0,'R',1); 
                     $this->Cell(10,6,$ref_m,1,0,'R',1); 
                      $this->Cell(10,6,$repeat_m,1,0,'R',1);
                     $this->Cell(10,6,$trans_m,1,0,'R',1);
                      $this->Cell(10,6,$wd_m,1,0,'R',1);
                       $this->Cell(10,6,$fw_m,1,0,'R',1);
                        $this->Cell(10,6,$sus_m,1,0,'R',1);
                         $this->Cell(10,6,$dm_m,1,0,'R',1);
                         
                                                  
             }
             
        }
             
             
             //end of year 1 to 3
             
            
    $this->Ln();
    
    }
    
    $tdist = $distrate_m + $distrate_f;
    $tcredit = $creditrate_m + $creditrate_f;
    $tpass= $passrate_m + $passrate_f;
    $tcomppass = $comppass_m + $comppass_f;
    //die($tcomppass);
     $tref = $ref_m +$ref_f;
     $twd = $wd_m +$wd_f;
    $tfw = $fw_m+$fw_f;
    $tinc= $inc_f + $inc_m;
     //totals
      if($code == 10024 || $code == 10052 || $code == 1003 )
            {                 
                    
             $this->Cell(35,6,'TOTALS',1,0,'R',1);
                     $this->Cell(25,6,$total_student,1,0,'R',1); 
                     $this->Cell(10,6,$tdist,1,0,'R',1); 
                     $this->Cell(10,6,$tcredit,1,0,'R',1); 
                     $this->Cell(10,6,$tpass,1,0,'R',1); 
                    $this->Cell(10,6,$tcomppass,1,0,'R',1); 
                     $this->Cell(10,6,$tdif,1,0,'R',1); 
                     $this->Cell(10,6,$tref,1,0,'R',1); 
                      $this->Cell(10,6,$trepeat,1,0,'R',1);
                      $this->Cell(10,6,$tinc,1,0,'R',1);
                     $this->Cell(10,6,$trans_m,1,0,'R',1);
                      $this->Cell(10,6,$twd,1,0,'R',1);
                       $this->Cell(10,6,$tfw,1,0,'R',1);
                        $this->Cell(10,6,$sus_m,1,0,'R',1);
                         $this->Cell(10,6,$dm_m,1,0,'R',1);
                }
                else if($code == 1005)
                {
                
                    $this->Cell(35,6,'TOTALS',1,0,'R',1);
                     $this->Cell(25,6,$total_student,1,0,'R',1); 
                    
                     $this->Cell(10,6,$tpass,1,0,'R',1); 
                    $this->Cell(10,6,$tcomppass,1,0,'R',1); 
                     $this->Cell(10,6,$dif_m,1,0,'R',1); 
                     $this->Cell(10,6,$tref,1,0,'R',1); 
                      $this->Cell(10,6,$trepeat,1,0,'R',1);
                      
                     $this->Cell(10,6,$trans_m,1,0,'R',1);
                      $this->Cell(10,6,$twd,1,0,'R',1);
                       $this->Cell(10,6,$tfw,1,0,'R',1);
                        $this->Cell(10,6,$tsus,1,0,'R',1);
                         $this->Cell(10,6,$tdm,1,0,'R',1);
                
                }
                else
                {
                
                    $this->Cell(35,6,'TOTALS',1,0,'R',1);
                     $this->Cell(25,6,$total_student,1,0,'R',1); 
                    
                     $this->Cell(10,6,$tpass,1,0,'R',1); 
                    
                     $this->Cell(10,6,$dif_m,1,0,'R',1); 
                     $this->Cell(10,6,$tref,1,0,'R',1); 
                      $this->Cell(10,6,$trepeat,1,0,'R',1);
                      
                     $this->Cell(10,6,$trans_m,1,0,'R',1);
                      $this->Cell(10,6,$twd,1,0,'R',1);
                       $this->Cell(10,6,$tfw,1,0,'R',1);
                        $this->Cell(10,6,$tsus,1,0,'R',1);
                         $this->Cell(10,6,$tdm,1,0,'R',1);
                
                }
                         
             
 // end of year 4 template
 
 
 
 
 //year 1 to 3 generic template
 
 
 
 
 
    //$this->Cell(array_sum($w),0,'','T');

}





function Footer()
{
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $pagenum = $this->PageNo() + 18;
    $this->Cell(0,10,'Page '.$pagenum.'',0,0,'C');
}


}
$pdf=new PDF();
//Column titles
$yr=2010;

//die("$yr,$class,$code");
$header=array('SN','REG NO.', 'NAME OF STUDENT','SEX');
//Data loading
//$data=$pdf->LoadData('pdf/tutorial/countries.txt');
$pdf->SetFont('Arial','',10);
$pdf->AddPage('L');

$pdf->FancyTable($header,$yr,$class,$code);
//$pdf->LoadData();
$pdf->Output();
?>