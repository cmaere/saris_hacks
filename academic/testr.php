<?php
require_once('../Connections/sessioncontrol.php');
require_once('../Connections/zalongwa.php');
//$class = 20;
//die("stop");



if($class >70)
{
    if($class ==71 || $class ==72)
    {
    //echo "here1";
       
                $test = "SELECT  DISTINCT e.CourseCode, c.Programme
        FROM  examresult e, course c 
        WHERE 
        e.CourseCode = c.CourseCode AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
        AND  e.CourseCode LIKE CONVERT( _utf8 '%71%'
        USING latin1 )   OR e.CourseCode LIKE CONVERT( _utf8 '%72%'
        USING latin1 )
        COLLATE latin1_swedish_ci
        AND c.Programme = '$code'

        ORDER BY e.CourseCode ASC";

        $testb = "SELECT  COUNT(DISTINCT e.CourseCode) AS cha
        FROM  examresult e, course c 
        WHERE 
        e.CourseCode = c.CourseCode AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
       AND  e.CourseCode LIKE CONVERT( _utf8 '%71%'
        USING latin1 )   OR e.CourseCode LIKE CONVERT( _utf8 '%72%'
        USING latin1 )
        COLLATE latin1_swedish_ci
        AND c.Programme = '$code'
        ORDER BY e.CourseCode ASC";




        $test2 = "SELECT  DISTINCT e.RegNo, s.Name,s.Sex
        FROM  examresult e, course c , student s
        WHERE 
        e.CourseCode = c.CourseCode AND
        s.RegNo = e.RegNo AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
       AND  e.CourseCode LIKE CONVERT( _utf8 '%71%'
        USING latin1 )   
        AND c.Programme = '$code'
        ORDER BY s.Name ASC";       
    
    }
    else if($class ==73 || $class ==74)
    {
    
     $test = "SELECT  DISTINCT e.CourseCode, c.Programme
        FROM  examresult e, course c 
        WHERE 
        e.CourseCode = c.CourseCode AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
        AND  e.CourseCode LIKE CONVERT( _utf8 '%73%'
        USING latin1 )   OR e.CourseCode LIKE CONVERT( _utf8 '%74%'
        USING latin1 )
        COLLATE latin1_swedish_ci
        AND c.Programme = '$code'

        ORDER BY e.CourseCode ASC";

        $testb = "SELECT  COUNT(DISTINCT e.CourseCode) AS cha
        FROM  examresult e, course c 
        WHERE 
        e.CourseCode = c.CourseCode AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
       AND  e.CourseCode LIKE CONVERT( _utf8 '%73%'
        USING latin1 )   OR e.CourseCode LIKE CONVERT( _utf8 '%74%'
        USING latin1 )
        COLLATE latin1_swedish_ci
        AND c.Programme = '$code'
        ORDER BY e.CourseCode ASC";




        $test2 = "SELECT  DISTINCT e.RegNo, s.Name,s.Sex
        FROM  examresult e, course c , student s
        WHERE 
        e.CourseCode = c.CourseCode AND
        s.RegNo = e.RegNo AND
         e.AYear LIKE CONVERT( _utf8 '2010'
        USING latin1 ) 
        COLLATE latin1_swedish_ci
       AND  e.CourseCode LIKE CONVERT( _utf8 '%73%'
        USING latin1 )   
        AND c.Programme = '$code'
        ORDER BY s.Name ASC";       
    
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
AND  e.CourseCode LIKE CONVERT( _utf8 '%$class%'
USING latin1 )  
COLLATE latin1_swedish_ci
AND c.Programme = '$code'
AND ExamCategory = '$sem'
ORDER BY e.CourseCode ASC";

$testb = "SELECT  COUNT(DISTINCT e.CourseCode) AS cha
FROM  examresult e, course c 
WHERE 
e.CourseCode = c.CourseCode AND
 e.AYear LIKE CONVERT( _utf8 '2010'
USING latin1 ) 
COLLATE latin1_swedish_ci
AND  e.CourseCode LIKE CONVERT( _utf8 '%$class%'
USING latin1 )  
COLLATE latin1_swedish_ci
AND c.Programme = '$code'
AND ExamCategory = '$sem'
ORDER BY e.CourseCode ASC";




$test2 = "SELECT  DISTINCT e.RegNo, s.Name,s.Sex
FROM  examresult e, course c , student s
WHERE 
e.CourseCode = c.CourseCode AND
s.RegNo = e.RegNo AND
 e.AYear LIKE CONVERT( _utf8 '2010'
USING latin1 ) 
COLLATE latin1_swedish_ci
AND  e.CourseCode LIKE CONVERT( _utf8 '%$class%'
USING latin1 )  
COLLATE latin1_swedish_ci
AND c.Programme = '$code'

ORDER BY s.Name ASC";

}

//die($testb);
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

echo "<b>$p</b><br><table border=1><tr><td><b>SN</td><td width = '300'><b>REG NO.<td width = '400'><b>NAME OF STUDENT<td><b>Gender";
$result = mysql_query($test);
//die($test); 
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) 
{
     //echo "here yo";
     //die($c);                                    
    //$reg= $line["RegNo"];
    $course= $line["CourseCode"];
    $cour[$i] = $line["CourseCode"];
  
    
    $maxsql = "Select max(`ExamScore`) as max from examresult
where `AYear`='2010' and `CourseCode`='$course'";
    echo "<td><b>$course</td>";
    $resultmax=mysql_query($maxsql);
    while ($linemax = mysql_fetch_array($resultmax, MYSQL_ASSOC)) 
    {
    $max[$i] =  $linemax["max"];   
    
    }
     $minsql = "Select min(`ExamScore`) as min from examresult
where `AYear`='2010' and `CourseCode`='$course' and ExamScore !='' ";
    //echo "<td><b>$course</td>";
    $resultmin=mysql_query($minsql);
    while ($linemin = mysql_fetch_array($resultmin, MYSQL_ASSOC)) 
    {
    $min[$i] =  $linemin["min"];   
    
    }
    $avgsql = "Select avg(`ExamScore`) as avg from examresult
where `AYear`='2010' and `CourseCode`='$course'";
    //echo "<td><b>$course</td>";
    $resultavg=mysql_query($avgsql);
    while ($lineavg = mysql_fetch_array($resultavg, MYSQL_ASSOC)) 
    {
    $avgb[$i] =  $lineavg["avg"];   
    
    }
    $i++;
    
}
 //die($c);  
echo "<td><b>AVG<td><b>WRNGS<td><b>RECOMM</tr><tr>";
$result2=mysql_query($test2);
$sn = 1;
while ($line = mysql_fetch_array($result2, MYSQL_ASSOC)) 
{
                                          
    $reg= $line["RegNo"];
    
    $testc = "SELECT Name, Sex FROM student WHERE RegNo ='$reg'";
    $resultc=mysql_query($testc);
  
while ($line2 = mysql_fetch_array($resultc, MYSQL_ASSOC)) 
{
                                          
    $name1= $line2["Name"];
    $gend= $line2["Sex"];
    
    }
        if($code == 1005)
        {
            
        }
    //$exam= $line["ExamScore"];
    echo "<td>$sn</td><td>$reg</td><td>$name1<td>$gend";
    $test3 = "SELECT  `CourseCode` ,  `ExamScore` 
    FROM  `examresult` 
    WHERE  `AYear` = 2010
    AND  `RegNo` =  '$reg' ORDER BY CourseCode ASC";
    $result3=mysql_query($test3);
    $i=0;
    $counta = 0;
    while ($line = mysql_fetch_array($result3, MYSQL_ASSOC)) 
    {
    
        $score[$i]= $line["ExamScore"];
        
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
        
    for ($i=0; $i<$c; $i++)
    {
        if($x <> $c)
        {              
      
            if (  $cour[$i]== $course1[$i])
            {
                //if($score[$i] == "" || $score[$i] == " ")
                   // {
                    //    $score[$i] = 0;
                    //}  
                   //
                   if ($score[$i] < 50)
                   {
                echo "<td><b>$score[$i]</td>";
                }
                else
                {
                echo "<td>$score[$i]</td>";
                    
                }
                
                $sum = $sum + $score[$i];
                
            
            }
            else if ($cour[$i] <> $course1[$i])
            {
            
            if($i == 1)
                {                
                //die($cour[$i]."==".$score[$i]); 
                }              
            
                $a = $i;
                for ($x=$i; $x<$c; $x++)
                {
                
                    if ( $cour[$x] == $course1[$a])
                    {
                    //die($score[$a]."check val");
            
                        if ($score[$a] < 50)
                         {
                            echo "<td><b>$score[$a]</td>";
                        }
                        else
                        {
                        
                        if($score[$a] == 55)
                    {
                   // die($score[$a]."=ing=".$course1[$a]."ee".$cour[$x]);
                    }
                             echo "<td>$score[$a]</td>";
                         }
                        
                         $sum = $sum + $score[$a];
                         $a +=1;
                     }
                     else
                     {
                     
                      echo "<td><b>--</td>";
                     
                     }
                
                }
                
                //</tr><tr>";
            
            }
            else
            {
                echo "<td><b>--</td>";
            
            }
    //
       
        }       
   
    
    }
    $avg = $sum/$c;
    
  //die($class."here");
  if ($class != 60)
  {
        if($avg < 50)
        {
            echo "<td><b><font color='red'>".number_format($avg,1,'.',',')."</font></b></td>";
        }
        else
        {
                echo "<td><b>".number_format($avg,1,'.',',')."</b></td>";
                }
        }
     echo "<td><td></tr><tr>";
     
     
     //reset array
     for ($r=0; $r<$c; $r++)
     {
     $score[$r] = 0;
     
     }
     
     
     $sn +=1;
     
}

echo "<tr><td>&nbsp;</tr><tr><td><td><td><b>Highest Score:";
 for ($r=0; $r<$c; $r++)
     {
     echo "<td><b>$max[$r]</td>";
     
     }
     echo "</tr><tr><td><td><td><b>Lowest Score:";
     for ($r=0; $r<$c; $r++)
     {
     echo "<td><b>$min[$r]</td>";
     
     }
     echo "</tr><tr><td><td><td><b>Avarage Score:";
     for ($r=0; $r<$c; $r++)
     {
     echo "<td><b>".number_format($avgb[$r],1,'.',',')."</td>";
     
     }
     $sn -=1;
     

echo "<form action='DOFassessmentedit.php'></tr><tr><td><td><input type='submit' value='EDIT FACULTY RESULTS'></tr></table><br>";

echo "<B>SUMMARY OF RESULTS  <TABLE BORDER=1 ><TR><TD><B> PROGRAMME<TD> NO. OF STUDENTS<TD> DIST<TD>CREDIT<TD>PASS<TD>COMP PASS <TD> REF.<TD>REPEAT<TD>NOT ASSESSED<TD> DEF EXAM<TD>F/WD</TR>";
echo "<TR><TD><B>$p<TD>$sn<TD> <TD><TD>$countb<TD> <TD> <TD><TD>$notassessb<TD><TD></TR></TABLE>";
                                    
                                    
?>