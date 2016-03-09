<?php

require_once('Connections/zalongwa.php');
$sql="SELECT  barcode,lastname,firstname
FROM temp";

  $result = mysql_query($sql);

while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) 
{

    $barcode= $line["barcode"];
    $lastname= $line["lastname"];
    $firstname= $line["firstname"];
     
     $sql2 = "UPDATE student SET barcode = $barcode WHERE Name like '".$lastname.",".$firstname."'";
     //die($sql2);
     
    mysql_query($sql2);
    
}
echo "done22222222222222";

?>