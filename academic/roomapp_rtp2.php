<?php

 require_once('../Connections/zalongwa.php');
 $year = $_GET["year"];
 $program = $_GET["program"];

 $sql1 =  "SELECT r.date, r.name, r.regno, s.DBirth, r.gender, r.mate FROM roomapp r , student s
 where r.regno = s.RegNo and r.degree = $program
order by  r.degree,r.gender, r.date asc  ";
//die($sql1);
echo"<table><tr><td><b>ROOM APPLICATION LIST FOR $program</tr><tr><td>";
echo "<table border=1>";

echo "<tr><td><b>Date Applied<td><b>Name<td><b>Regno<td><b>DBirth<td><b>Gender<td><b>Room Mate</tr>";
            $result1 = mysql_query($sql1);
            while($row = mysql_fetch_array($result1, MYSQL_ASSOC))
            {
                $date = $row['date'];
                 //$row['date'];
                 $name= $row['name'];
                 $regno =$row['regno'];
                 $gender = $row['gender'];
                 $age = $row['DBirth'];
                 $met = $row['mate'];
                 //$letter = $row['letter'];
                 echo "<tr><td>$date<td>$name<td>$regno<td>$age<td>$gender<td>$prog<td>$met</tr>";
                 
                 
                 
                 
            
            }


echo"</tr></table></tr><tr><td><i>report generated on ".date ( "Y-m-d H:t:s " )."</table>";
?>