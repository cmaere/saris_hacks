<?php

//require_once('Connections/sessioncontrol.php');
require_once('Connections/zalongwa.php');


$sql = "SELECT Name, RegNo
FROM  student
WHERE 
EntryYear = '2010'
";
$conn=odbc_connect('cha','','');
if (!$conn)
{exit("Connection Failed: " . $conn);}
$resultb=mysql_query($sql);
while ($line = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    $name= $line["Name"];
    $regno= $line["RegNo"];
     
$sql="INSERT INTO student(name,regno) VALUES('$name','$regno')";
$rs=odbc_exec($conn,$sql);
    
}

echo "imported";



//$class = 20;

 

odbc_close($conn);
?>