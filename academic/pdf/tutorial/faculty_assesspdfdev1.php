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
else if($code ==100523)
{
    
       
       
       $code2= $code -2;

$test = "SELECT  DISTINCT e.CourseCode, c.Programme
FROM  examresult e, course c 
WHERE 
e.CourseCode = c.CourseCode AND
 e.AYear > '2008'

AND c.Programme >=10050  AND c.Programme <= $code
AND c.prefix LIKE '%ED%'
ORDER BY c.Programme, e.CourseCode ASC
";

$testb = "SELECT  COUNT(DISTINCT e.CourseCode) AS cha
FROM  examresult e, course c 
WHERE 
e.CourseCode = c.CourseCode AND
 e.AYear > '2008'

AND c.Programme >=10050  AND c.Programme <= $code
AND c.prefix LIKE '%ED%'
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
AND c.Programme >10050 AND c.Programme <= $code
AND c.prefix LIKE '%ED%'
ORDER BY s.Name ASC";

       
       

    
    
   
//die('heheheh here');    
    



}

else if($code == 10024 || $code == 10042)
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
  $reff = 0;
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
    
    
    
    //die($course);
    
    
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
    
    
    //die($AYear[$i].'here');
    
     
     if($AYear[$i] == 10024 && $AYear[$i-1] == 10023) 
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
                        else if($AYear[$i] > 10050 && $AYear[$i-1] == 10050) 
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
                        else if($AYear[$i] == 10042 && $AYear[$i-1] == 10041) 
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
    //die($AYear[$i].'haha');
     if($code == 10024 || $code == 10042 || $code > 10050)  
  {
  
  $this->Cell(17,7,"AVG-Yr3",1,0,'L',1);
  $this->Cell(17,7,"AVG-Yr4",1,0,'L',1);
  
  }
  
  else
  {
    $this->Cell(6,7,"AVG",1,0,'C',1);
  }

 
 
 $this->Cell(26,7,"RECOMM",1,0,'C',1);

//$this->Ln();

  $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','',9);
    
 
    
 //header end   
 
    $this->Ln(7);
    if($code == 10024 || $code == 10042|| $code > 10050)
    {
    
             for($i=0;$i<count($header);$i++)
           {
                $this->Cell($w[$i],7,'',1,0,'C',0);
            }
          
            $this->Cell(6,7,' PREVIOUS YEAR',1,0,'L',0);
        for($i=2;$i<count($cour);$i++)
           {
           $this->Cell(6,7,'',1,0,'C',0);
            if(($AYear[$i] == 10024 && $AYear[$i-1] == 10023) || ($AYear[$i] == 10042 && $AYear[$i-1] == 10041) || ($AYear[$i] > 10050 && $AYear[$i-1] == 10050) )
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
                             $this->Cell(6,7,' FINAL YEAR',1,0,'L',0);
                        
                        }
                
                
            }
            $this->Cell(17,7,"",1,0,'L',0);
            $this->Cell(17,7,"",1,0,'L',0);
             $this->Cell(26,7,"",1,0,'C',0);

            $this->Ln(7);
    }
	//Save ordinate
	$this->y0=$this->GetY();


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




//Colored table
function FancyTable($header,$yr,$class,$code)
{



    //Colors, line width and bold font
   


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
else if($code ==100523)
{
    
       

                
       $code2= $code -2;

$test = "SELECT  DISTINCT e.CourseCode, c.Programme
FROM  examresult e, course c 
WHERE 
e.CourseCode = c.CourseCode AND
 e.AYear > '2008'

AND c.Programme >=10050  AND c.Programme <= $code
AND c.prefix LIKE '%ED%'
ORDER BY c.Programme, e.CourseCode ASC
";

$testb = "SELECT  COUNT(DISTINCT e.CourseCode) AS cha
FROM  examresult e, course c 
WHERE 
e.CourseCode = c.CourseCode AND
 e.AYear > '2008'

AND c.Programme >=10050  AND c.Programme <= $code
AND c.prefix LIKE '%ED%'
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
AND c.Programme >10050 AND c.Programme <= $code
AND c.prefix LIKE '%ED%'
ORDER BY s.Name ASC";
 
    
    
   
    
    



}


else if($code == 10024 || $code == 10042)
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
   
   if($code == 10024 || $code == 10042 || $code == 100523)
   {
   
    $yr3 = 2007;
    $yr2 = 2009;
    
            
        $regcheck= "SELECT  RegNo
    FROM  `student` 
    WHERE  `regno` like 'kCN/DIPN/08/%' AND regno = '$reg'";
    $result_regcheck=mysql_query($regcheck);
       $regcheck2= mysql_num_rows($result_regcheck);
    while ($line = mysql_fetch_array($result_regcheck, MYSQL_ASSOC)) 
    {
    
       // $regcheck2= $line["RegNo"];
        //die($student_avg1);
    }

    
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
        if($regcheck2 ==1)
        {
            //$regcheck2 = '';
        $test3_avg = "SELECT  ROUND(AVG(`ExamScore`),0) as avarage
    FROM  `examresult` 
    WHERE  `AYear` = '2008'
    AND ExamCategory ='5'
    AND  `RegNo` =  '$reg' ORDER BY CourseCode ASC";
    $result3_avg=mysql_query($test3_avg);
    if($reg =='KCN/DipN/09/05')
  {
  
 // die($test3_avg);
  
  }
    
    
    }
    else
    {
    
        
        $test3_avg = "SELECT  ROUND(AVG(`ExamScore`),0) as avarage
    FROM  `examresult` 
    WHERE  `AYear` = '$yr2'
    AND ExamCategory ='5'
    AND  `RegNo` =  '$reg' ORDER BY CourseCode ASC";
    $result3_avg=mysql_query($test3_avg);
    }
    
    
    
    while ($line = mysql_fetch_array($result3_avg, MYSQL_ASSOC)) 
    {
    
        $student_avg2= $line["avarage"];
        //die($student_avg1);
        }
        
        
      
        
    if($regcheck2 ==1 && $code==10042)
    {
    
    //die('check ere');
    $yr3 = 2007;
    $yr2 = 2009;
        
        $test3 = "SELECT  `CourseCode` ,  `ExamScore` , AYear
    FROM  `examresult` 
    WHERE  `AYear` > '$yr3'
   AND ExamCategory ='5'
    AND  `RegNo` =  '$reg' ORDER BY  AYear,CourseCode ASC";
    $result3=mysql_query($test3);
    }
    else
    {
   
   $test3 = "SELECT  `CourseCode` ,  `ExamScore` , AYear
    FROM  `examresult` 
    WHERE  `AYear` >= $yr2
   AND ExamCategory ='5'
    AND  `RegNo` =  '$reg' ORDER BY  AYear,CourseCode ASC";
    $result3=mysql_query($test3);
    }
   
   
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
        
        
        
        
        
        
       
        
        
        $course1[$i] = $line["CourseCode"];
        
        if( $score[$i] < 50)
        {
        
        $counta +=1;
        
        }
        else if( $score[$i] == "")
        {
        
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
    
    if($pass_1 == $c)
    {
        
        $passfull= 'passed';
        
        
    
    }
    
    
    
    
    if($counta == 0 && $notassess == 0)
    {
    
    $countb +=1;
    
    }
    
    if($notassess >= 1)
    {
        $notassessb +=1;
    
    }
    
       // die($yr3.'heheheh');
        $x=0;
        $sum=0;
        
    for ($i=0; $i<$c; $i++)
    {
     $this->SetFont('','',9);
     
     
     //will check
    
     
        if($x <> $c)
       { 
        
         

      
            if ($cour[$i] == $course1[$i])
            {
            
            
            
            
                //if($score[$i] == "" || $score[$i] == " ")
                   // {
                    //    $score[$i] = 0;
                    //}  
                   //
                  // die($score[$i].'---'.$i);
                   if ($score[$i] < 50)
                   {
                   

                            if($AYear[$i] == 2010 && $AYear[$i-1] == 2009) 
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
                        
                         if($AYear[$i] == 2010 && $AYear[$i-1] == 2008) 
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
                else
                {
                
                        if($AYear[$i] == 2010 && $AYear[$i-1] == 2009) 
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
                        else if($AYear[$i] == 2010 && $AYear[$i-1] == 2008) 
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
                
                $sum = $sum + $score[$i];
                
            
            }
            else if ($cour[$i] <> $course1[$i])
            {
            
            
            
                $a = $i;
                for ($x=$i; $x<$c; $x++)
                {
                
                //die($cour[$x].'--'.$course1[$a].'--'.$score[$a].'---'.$i);

                
                    if ( $cour[$x] == $course1[$a])
                    {
                    //die($score[$a]."check val");
                  
                        if ($score[$a] < 50)
                         {
                          
                                if($AYear[$i] == 2010 && $AYear[$i-1] == 2009) 
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
                            else if($AYear[$i] == 2010 && $AYear[$i-1] == 2008) 
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
                          $this->Cell(6,6,number_format($score[$a]),1,0,'R',$fill);  
                             $rec +=1;      
                                          
                            //echo "<td><b>$score[$a]</td>";
                        }
                        else
                        {
                         
                        if($AYear[$i] == 2010 && $AYear[$i-1] == 2009) 
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
                        
                        else if($AYear[$i] == 2010 && $AYear[$i-1] == 2008) 
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
                    if($reg == 'kCN/DIPN/08/02'||$reg == 'KCN/DipN/07/12'||$reg == 'kcn/dipn/08/15'||$reg == 'kcn/dipn/08/12'||$reg == 'kCN/DipN/08/18')
     {
     
        if($cour[$x] == 'UBI 201'  )
        {
        //die('hehy2');
        $this->SetFillColor(57,127,145);
                            $this->SetTextColor(255);
                            $this->SetDrawColor(57,127,145);
                             $this->SetLineWidth(.3);
                             $this->SetFont('','B',9);
                             

                            $this->Cell(2,7,'',1,0,'R',1);   
                            
                             $this->SetFillColor(224,235,255);
                            $this->SetTextColor(0);
                             $this->SetFont('','',9);
                              //$AYear[$a - 1] = 2010;
                        
     }
      //
     
     
     }
                        
                     $this->Cell(6,6,number_format($score[$a]),1,0,'R',$fill);  
                          //   echo "<td>$score[$a]</td>";
                         }
                        
                         $sum = $sum + $score[$a];
                         $a +=1;
                     }
                     else
                     {
                     
                     //if($cour[$x] == $course1[$a+1]
                     
                       die($score[$i].'htr'.$reg.$c.$x.$cour[$x].$course1[$a]);
                     //$i = 10;
                     //die($AYear[$i]);
                     //die($i.'fd');
                            if($AYear[$i] == 2010 && $AYear[$i-1] == 2009) 
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
                        else if($AYear[$i] == 2010 && $AYear[$i-1] == 2008) 
                        {
                        
                        

                        
                            //die('inside');
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
                        
                                           $this->SetFont('','B');
                    
                      $this->Cell(6,6,'--',1,0,'R',$fill); 
                      
                         $rec = 100;


                      //echo "<td><b>--</td>";
                     
                     }
                
                }
                
                //</tr><tr>";
            
            }
            else
            {
             
             if($AYear[$i] == 2010 && $AYear[$i-1] == 2009)                       
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
                        else if($AYear[$i] == 2010 && $AYear[$i-1] == 2008)                        
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
                //echo "<td><b>--</td>";
            
            }
    //
       //will check
        }       
   
   
   
   
    
    }
    $avg = $sum/$c;
    
  //die($class."here");
   $this->SetFont('','B');
  if($code == 10024 || $code == 10042 || $code > 10050) 
  {
  
  
  
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
        
        if($rec == 0)
        {
        
        //die($cred. 'hweere');
        
            if($code == 10024 || $code == 10042)
            {
                    //die($cred.'here');
            
            
                if($credit == 'passed')
                {
                    $this->Cell(26,6,'CREDIT',1,0,'R',$fill);
                    $credit = 'fail';
                }
                else
                if($passfull=='passed')
                {
                
                 $this->Cell(26,6,'PASS',1,0,'R',$fill);
                 $passfull = 'fail';
                }
                else if($student_avg1 >=80 && $student_avg1 <= 100)
                {
                    $this->Cell(26,6,'DISTINCTION',1,0,'R',$fill);
                }
                else
                {
                    
                    // $this->Cell(30,6,'PASS',1,0,'R',$fill);
                }
                               

            }
            else
            {
                $this->Cell(26,6,'PP',1,0,'R',$fill);
            
            }
         }
         
         else if($rec >=100)
         {
         
            if($wh == 'wh')
            {
         
            $this->Cell(26,6,'WH',1,0,'R',$fill);
            }
            else if($wh == 'dif')
            {
            
            
               $this->Cell(26,6,'DEF',1,0,'R',$fill); 
            
            }
            else
            {
                $this->Cell(26,6,'Missing Grade',1,0,'R',$fill);
            
            }
         
         }
          else if($rec == 1)
         {
         
            $this->Cell(26,6,'REFF',1,0,'R',$fill); 
            $reff +=1; 
         
         }
         else if($rec == 2)
         {
         
            $this->Cell(30,6,'REFF',1,0,'R',$fill);
            $reff +=1;            
         
         }
         else if($rec >= 3)
         {
         
            
            
            if($wh == 'wh')
            {
         
            $this->Cell(30,6,'WH',1,0,'R',$fill);
            }
            else if($wh == 'dif')
            {
            
            
               $this->Cell(30,6,'DEF',1,0,'R',$fill); 
            
            }
            else
            {
             $this->Cell(30,6,'REPEAT',1,0,'R',$fill); 
            $rept +=1;
            }
         }
          else if($rec >= 5 && $code=1001)
         {
         
            $this->Cell(30,6,'FAIL AND WITHDRAW',1,0,'R',$fill); 
            $rept +=1;
         
         }
         else if($rec >= 4 && $code=10012)
         {
         
            $this->Cell(30,6,'FAIL AND WITHDRAW',1,0,'R',$fill); 
            $rept +=1;
         
         }
         else if($rec >= 5 && $code=10013)
         {
         
            $this->Cell(30,6,'FAIL AND WITHDRAW',1,0,'R',$fill); 
            $rept +=1;
         
         }
         else if($rec >= 3 && $code=1002)
         {
         
            $this->Cell(30,6,'FAIL AND WITHDRAW',1,0,'R',$fill); 
            $rept +=1;
         
         }
         
        $this->Ln();
    // echo "<td><td></tr><tr>";
     
      $this->SetFont('','');
     //reset array
     for ($r=0; $r<$c; $r++)
     {
     $score[$r] = 0;
     
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

$this->SetFont('','B');

$this->Cell(0,6,"SUMMARY OF RESULTS ",1,1,'L',1);
$this->Ln(0);

 $this->Cell(105,6,'PROGRAMME',1,0,'R',1); 
 $this->Cell(35,6,'NO. OF STUDENTS',1,0,'R',1); 
 $this->Cell(10,6,'DIST',1,0,'R',1); 
  $this->Cell(18,6,'CREDIT',1,0,'R',1); 
  $this->Cell(10,6,'PASS',1,0,'R',1); 
  $this->Cell(22,6,'COMP PASS',1,0,'R',1); 
    $this->Cell(10,6,'REF.',1,0,'R',1); 
    $this->Cell(15,6,'REPEAT',1,0,'R',1); 
    $this->Cell(28,6,'NOT ASSESSED',1,0,'R',1); 
    $this->Cell(22,6,'DEF EXAM',1,0,'R',1); 
    $this->Ln();
    
    //$this->SetFont('','B');
    $this->Cell(105,6,$pr,1,0,'R',0); 
 $this->Cell(35,6,number_format($sn),1,0,'R',0); 
 $this->Cell(10,6,number_format($g),1,0,'R',0); 
  $this->Cell(18,6,number_format($g),1,0,'R',0); 
  $this->Cell(10,6,number_format($countb),1,0,'R',0); 
  $this->Cell(22,6,number_format($g),1,0,'R',0); 
    $this->Cell(10,6,number_format($reff),1,0,'R',0); 
    $this->Cell(15,6,number_format($g),1,0,'R',0); 
    $this->Cell(28,6,number_format($notassessb),1,0,'R',0); 
    $this->Cell(22,6,number_format($d),1,0,'R',0); 
    //$this->Cell(array_sum($w),0,'','T');
}
}
function summary()
{

$diu2 = "
SELECT  count(RegNo) as num,Sex
        FROM student 
        WHERE        
    EntryYear = '2010'
        
    AND ProgrammeofStudy = '1001'
GROUP BY Sex
	";
    $resultdiu2=mysql_query($diu2);
    
   

$this->SetFont('','B');

$this->Cell(0,6,"SUMMARY OF RESULTS ",1,1,'L',1);
$this->Ln(0);

 $this->Cell(35,6,'',1,0,'R',1); 
 $this->Cell(10,6,'',1,0,'R',1); 
 $this->Cell(10,6,'PP',1,0,'R',1); 
 $this->Cell(10,6,'DF',1,0,'R',1); 
 $this->Cell(10,6,'REF',1,0,'R',1); 
  $this->Cell(10,6,'REP',1,0,'R',1);
 $this->Cell(10,6,'TR',1,0,'R',1);
  $this->Cell(10,6,'WH',1,0,'R',1);
   $this->Cell(10,6,'WM',1,0,'R',1);
    $this->Cell(10,6,'FW',1,0,'R',1);
     $this->Cell(10,6,'SUS',1,0,'R',1);
      $this->Cell(10,6,'DM',1,0,'R',1);
 $this->Ln(0);
  while ($line = mysql_fetch_array($resultdiu2, MYSQL_ASSOC)) 
    {
    
        $num= $line["num"];
        $sex = $line["Sex"];
        //die($student_avg1);
  
 if($sex =='F')
 {
 $this->Cell(35,6,'FEMALE',1,0,'R',1); 
 $this->Cell(10,6,number_format($num),1,0,'R',1); 
  $this->Cell(10,6,'PP',1,0,'R',1); 
 $this->Cell(10,6,'DF',1,0,'R',1); 
 $this->Cell(10,6,'REF',1,0,'R',1); 
  $this->Cell(10,6,'REP',1,0,'R',1);
 $this->Cell(10,6,'TR',1,0,'R',1);
  $this->Cell(10,6,'WH',1,0,'R',1);
   $this->Cell(10,6,'WM',1,0,'R',1);
    $this->Cell(10,6,'FW',1,0,'R',1);
     $this->Cell(10,6,'SUS',1,0,'R',1);
      $this->Cell(10,6,'DM',1,0,'R',1);
      }
      else
      {
 $this->Cell(10,6,'MALE',1,0,'R',1);
 $this->Cell(10,6,number_format($num),1,0,'R',1); 
 $this->Cell(10,6,'PP',1,0,'R',1); 
 $this->Cell(10,6,'DF',1,0,'R',1); 
 $this->Cell(10,6,'REF',1,0,'R',1); 
  $this->Cell(10,6,'REP',1,0,'R',1);
 $this->Cell(10,6,'TR',1,0,'R',1);
  $this->Cell(10,6,'WH',1,0,'R',1);
   $this->Cell(10,6,'WM',1,0,'R',1);
    $this->Cell(10,6,'FW',1,0,'R',1);
     $this->Cell(10,6,'SUS',1,0,'R',1);
      $this->Cell(10,6,'DM',1,0,'R',1);
 }
 
    $this->Ln();
    
    }
    
    
    
    //$this->Cell(array_sum($w),0,'','T');

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
$pdf->Output();
?>