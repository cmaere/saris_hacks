<?php
echo "<B> KAMUZU COLLEGE OF NURSING<BR><BR>AGE STATISTICS FOR GENERIC STUDENTS";
 require_once('../Connections/zalongwa.php');
 $prog = 'kcn/bscn/11%';
 $query_coursecode = "select (left(curdate(),4) - right(DBirth,4)) as age from student where RegNo like '$prog'  and Sex = 'M'";

//die($query_coursecode );
$resultb=mysql_query($query_coursecode);
while ($line = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    //$class= $line["prefix"];
    $age= $line["age"];
    
    if ($age < 18)
    {
      $a +=1;
     
    }
    else  if ($age  == 18)  
    {
        $a1 +=1;
      
    } 
    else  if ($age  == 19)  
    {
        $b +=1;
      
    } 
    else if ($age  == 20)  
    {
        $c +=1;
    }
    else if ($age  == 21)  
    {
        $d +=1;
    }
    else if ($age  == 22)  
    {
        $e +=1;
    }
    else if ($age  == 23)  
    {
        $f+=1;
    }
    else if ($age  == 24)  
    {
        $g +=1;
    }
   
    else if ($age  >= 25)  
    {
        $h +=1;
    }
        
    
  
  }
  
  $query_coursecodef = "select (left(curdate(),4) - right(DBirth,4)) as age from student where RegNo like '$prog'  and Sex = 'F'";

//die($query_coursecode );
$resultbf=mysql_query($query_coursecodef);
while ($line = mysql_fetch_array($resultbf, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    //$class= $line["prefix"];
    $agef= $line["age"];
    
    if ($age < 18)
    {
      $af +=1;
     
    }
    else  if ($agef  == 18)  
    {
        $af1 +=1;
      
    } 
    else  if ($agef  == 19)  
    {
        $bf +=1;
      
    } 
    else if ($agef  == 20)  
    {
        $cf +=1;
    }
    else if ($agef  == 21)  
    {
        $df +=1;
    }
    else if ($agef  == 22)  
    {
        $ef +=1;
    }
    else if ($agef  == 23)  
    {
        $ff+=1;
    }
    else if ($agef  == 24)  
    {
        $gf +=1;
    }
   
    else if ($agef  >= 25)  
    {
        $hf +=1;
    }
        
    
  
  }
  
  //year 2 
  
  $prog2 = 'kcn/bscn/10%';
 $query_coursecode2 = "select (left(curdate(),4) - right(DBirth,4)) as age from student where RegNo like '$prog2'  and Sex = 'M'";

//die($query_coursecode );
$resultb2=mysql_query($query_coursecode2);
while ($line2 = mysql_fetch_array($resultb2, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    //$class= $line["prefix"];
    $age2= $line2["age"];
    
    if ($age2 < 18)
    {
      $a2 +=1;
     
    }
    else  if ($age2  == 18)  
    {
        $a12 +=1;
      
    } 
    else  if ($age2  == 19)  
    {
        $b2 +=1;
      
    } 
    else if ($age2  == 20)  
    {
        $c2 +=1;
    }
    else if ($age2  == 21)  
    {
        $d2 +=1;
    }
    else if ($age2  == 22)  
    {
        $e2 +=1;
    }
    else if ($age2  == 23)  
    {
        $f2+=1;
    }
    else if ($age2  == 24)  
    {
        $g2 +=1;
    }
   
    else if ($age2  >= 25)  
    {
        $h2 +=1;
    }
        
    
  
  }
  
  $query_coursecodef2 = "select (left(curdate(),4) - right(DBirth,4)) as age from student where RegNo like '$prog2'  and Sex = 'F'";

//die($query_coursecode );
$resultbf2=mysql_query($query_coursecodef2);
while ($line2 = mysql_fetch_array($resultbf2, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    //$class= $line["prefix"];
    $agef2= $line2["age"];
    
    if ($agef2 < 18)
    {
      $af2 +=1;
     
    }
    else  if ($agef2  == 18)  
    {
        $af12 +=1;
      
    } 
    else  if ($agef2  == 19)  
    {
        $bf2 +=1;
      
    } 
    else if ($agef2  == 20)  
    {
        $cf2 +=1;
    }
    else if ($agef2  == 21)  
    {
        $df2 +=1;
    }
    else if ($agef2  == 22)  
    {
        $ef2 +=1;
    }
    else if ($agef2  == 23)  
    {
        $ff2+=1;
    }
    else if ($agef2  == 24)  
    {
        $gf2 +=1;
    }
   
    else if ($agef2  >= 25)  
    {
        $hf2 +=1;
    }
        
    
  
  }
  
  
   $prog3 = 'kcn/bscn/09%';
 $query_coursecode3 = "select (left(curdate(),4) - right(DBirth,4)) as age from student where RegNo like '$prog3'  and Sex = 'M'";

//die($query_coursecode );
$resultb3=mysql_query($query_coursecode3);
while ($line3 = mysql_fetch_array($resultb3, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    //$class= $line["prefix"];
    $age3 = $line3["age"];
    
    if ($age3 < 18)
    {
      $a3 +=1;
     
    }
    else  if ($age3  == 18)  
    {
        $a13 +=1;
      
    } 
    else  if ($age3  == 19)  
    {
        $b3 +=1;
      
    } 
    else if ($age3  == 20)  
    {
        $c3 +=1;
    }
    else if ($age3  == 21)  
    {
        $d3 +=1;
    }
    else if ($age3  == 22)  
    {
        $e3 +=1;
    }
    else if ($age3  == 23)  
    {
        $f3 +=1;
    }
    else if ($age3  == 24)  
    {
        $g3 +=1;
    }
   
    else if ($age3  >= 25)  
    {
        $h3 +=1;
    }
        
    
  
  }
  
  $query_coursecodef3 = "select (left(curdate(),4) - right(DBirth,4)) as age from student where RegNo like '$prog3'  and Sex = 'F'";

//die($query_coursecode );
$resultbf3=mysql_query($query_coursecodef3);
while ($line3 = mysql_fetch_array($resultbf3, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    //$class= $line["prefix"];
    $agef3= $line3["age"];
    
    if ($agef3 < 18)
    {
      $af3 +=1;
     
    }
    else  if ($agef3  == 18)  
    {
        $af13 +=1;
      
    } 
    else  if ($agef3  == 19)  
    {
        $bf3 +=1;
      
    } 
    else if ($agef3  == 20)  
    {
        $cf3 +=1;
    }
    else if ($agef3  == 21)  
    {
        $df3 +=1;
    }
    else if ($agef3  == 22)  
    {
        $ef3 +=1;
    }
    else if ($agef3  == 23)  
    {
        $ff3+=1;
    }
    else if ($agef3  == 24)  
    {
        $gf3 +=1;
    }
   
    else if ($agef3  >= 25)  
    {
        $hf3 +=1;
    }
        
    
  
  }
  
    $prog4 = 'kcn/bscn/08%';
 $query_coursecode4 = "select (left(curdate(),4) - right(DBirth,4)) as age from student where RegNo like '$prog4'  and Sex = 'M'";

//die($query_coursecode );
$resultb4=mysql_query($query_coursecode4);
while ($line4 = mysql_fetch_array($resultb4, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    //$class= $line["prefix"];
    $age4 = $line4["age"];
    
    if ($age4 < 18)
    {
      $a4 +=1;
     
    }
    else  if ($age4  == 18)  
    {
        $a14 +=1;
      
    } 
    else  if ($age4  == 19)  
    {
        $b4 +=1;
      
    } 
    else if ($age4  == 20)  
    {
        $c4 +=1;
    }
    else if ($age4  == 21)  
    {
        $d4 +=1;
    }
    else if ($age4  == 22)  
    {
        $e4 +=1;
    }
    else if ($age4  == 23)  
    {
        $f4 +=1;
    }
    else if ($age4  == 24)  
    {
        $g4 +=1;
    }
   
    else if ($age4  >= 25)  
    {
        $h4 +=1;
    }
        
    
  
  }
  
  $query_coursecodef4 = "select (left(curdate(),4) - right(DBirth,4)) as age from student where RegNo like '$prog4'  and Sex = 'F'";

//die($query_coursecode );
$resultbf4=mysql_query($query_coursecodef4);
while ($line4 = mysql_fetch_array($resultbf4, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    //$class= $line["prefix"];
    $agef4 = $line4["age"];
    
    if ($agef4 < 18)
    {
      $af4 +=1;
     
    }
    else  if ($agef4  == 18)  
    {
        $af14 +=1;
      
    } 
    else  if ($agef4  == 19)  
    {
        $bf4 +=1;
      
    } 
    else if ($agef4  == 20)  
    {
        $cf4 +=1;
    }
    else if ($agef4  == 21)  
    {
        $df4 +=1;
    }
    else if ($agef4  == 22)  
    {
        $ef4 +=1;
    }
    else if ($agef4  == 23)  
    {
        $ff4 +=1;
    }
    else if ($agef4  == 24)  
    {
        $gf4 +=1;
    }
   
    else if ($agef4  >= 25)  
    {
        $hf4 +=1;
    }
        
    
  
  }
  
  echo "<style type='text/css'>
  td {
  padding:7px;
  width: 50;
  }
  
  
  </style>";
  
  
  echo " <table border=1><tr><td>Age<td>Year 1<td> Year 2 <td> Year 3 <td> Year 4 </tr>";
    echo "<tr><td><td>
    <table border=1><tr><td>M<td>F</tr></table> <td><table border=1><tr><td>M<td>F</tr></table><td><table border=1><tr><td>M<td>F</tr></table><td><table border=1><tr><td>M<td>F</tr></table></tr>
    <tr><td><18 <td>
    <table border=1><tr><td>$a<td>$af</tr></table> <td> <table border=1><tr><td>$a2<td>$af2</tr></table><td> <table border=1><tr><td>$a3<td>$af3</tr></table><td> <table border=1><tr><td>$a4<td>$af4</tr></table></tr>
    <tr><td>18 <td>
    <table border=1><tr><td>$a1<td>$af1</tr></table> <td> <table border=1><tr><td>$a12<td>$af12</tr></table><td> <table border=1><tr><td>$a13<td>$af13</tr></table><td> <table border=1><tr><td>$a14<td>$af14</tr></table></tr>
    <tr><td>19 <td>
    <table border=1><tr><td>$b<td>$bf</tr></table> <td> <table border=1><tr><td>$b2<td>$bf2</tr></table><td> <table border=1><tr><td>$b3<td>$bf3</tr></table><td> <table border=1><tr><td>$b4<td>$bf4</tr></table></tr>
    <tr><td>20 <td>
    <table border=1><tr><td>$c<td>$cf</tr></table> <td> <table border=1><tr><td>$c2<td>$cf2</tr></table><td> <table border=1><tr><td>$c3<td>$cf3</tr></table><td> <table border=1><tr><td>$c4<td>$cf4</tr></table></tr>
    <tr><td>21 <td>
    <table border=1><tr><td>$d<td>$df</tr></table> <td> <table border=1><tr><td>$d2<td>$df2</tr></table><td> <table border=1><tr><td>$d3<td>$df3</tr></table><td> <table border=1><tr><td>$d4<td>$df4</tr></table></tr>
    <tr><td>22 <td>
    <table border=1><tr><td>$e<td>$ef</tr></table> <td> <table border=1><tr><td>$e2<td>$ef2</tr></table><td> <table border=1><tr><td>$e3<td>$ef3</tr></table><td> <table border=1><tr><td>$e4<td>$ef4</tr></table></tr>
    <tr><td>23 <td>
    <table border=1><tr><td>$f<td>$ff</tr></table> <td> <table border=1><tr><td>$f2<td>$ff2</tr></table><td> <table border=1><tr><td>$f3<td>$ff3</tr></table><td> <table border=1><tr><td>$f4<td>$ff4</tr></table></tr>
    <tr><td>24 <td>
    <table border=1><tr><td>$g<td>$gf</tr></table> <td> <table border=1><tr><td>$g2<td>$gf2</tr></table><td> <table border=1><tr><td>$g3<td>$gf3</tr></table><td> <table border=1><tr><td>$g4<td>$gf4</tr></table></tr>
    <tr><td>>=25 <td>
    <table border=1><tr><td>$h<td>$hf</tr></table> <td> <table border=1><tr><td>$h2<td>$hf2</tr></table><td> <table border=1><tr><td>$h3<td>$hf3</tr></table><td> <table border=1><tr><td>$h4<td>$hf4</tr></table></tr>
    ";
    


?>