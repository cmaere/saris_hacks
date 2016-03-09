<?php


require_once('Connections/zalongwa.php');
$year = 2012;
$yr = 12;

$sql1 = "SELECT RegNo, Name FROM `student` WHERE `EntryYear` = $year and `ProgrammeofStudy` = 1001 and RegNo like '%/$yr/%'";
 $result = mysql_query($sql1);
 while($row = mysql_fetch_array($result, MYSQL_ASSOC))
   {
        $reg = $row['RegNo'];
		$name = explode(",",$row['Name']);
		$name2 =$name[1];
		
		$lastname =  explode(" ",$name2);
		$email = strtolower($lastname[1]).$year.strtolower($name[0])."@kcn.unima.mw";
		//echo "$email<br>";
		
		$sql = "UPDATE student SET Email = '$email' where RegNo='$reg'";
		 mysql_query($sql);
		echo "$email is done<br>";
   }



  //  $result = mysql_query($sql);


?>