<?php require_once('../Connections/zalongwa.php'); ?>
<?php
@$key=$_GET['content'];
session_start();
header("Cache-control: private"); // IE 6 Fix. 
@$auth_level = $_SESSION['auth_level'];
@$RegNo = $_SESSION['RegNo'];
@$username = $_SESSION['username'];

if(!$auth_level){
	echo ("Session Expired, <a href=\"ReLogin.php\"> Click Here<a> to Re-Login");
	echo '<meta http-equiv = "refresh" content ="0; 
						url = ReLogin.php">';
					exit;
}
$browser  =  $_SERVER["HTTP_USER_AGENT"];   
$ip  =  $_SERVER["REMOTE_ADDR"];   

require_once('../Connections/zalongwa.php'); 

$sql="INSERT INTO stats(ip,browser,received,page) VALUES('$ip','$browser',now(),'$username')";   
//$sqldel = "delete from stats where (YEAR(CURRENT_DATE)-YEAR(received))- (RIGHT(CURRENT_DATE,5)<RIGHT(received,5))>1";
$result = mysql_query($sql) or die("Siwezi kuingiza data.<br>" . mysql_error());

?> 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang=en-US>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="keywords" content="ZALONGWA, zalongwa, Student Information System (SIS), accommodation information system, database system, examination database system, student records system, database system">
<meta name="description" content="School Management Information System, Database System, Juma Lungo Lungo Lungo Lungo, Database System , Student Information System (SIS), Accommodation Record Keeping, Examination Results System, student Normninal Roll Database">
<meta name="rating" content="General">
<meta name="Generator" content="Macromedia Dreamweaver  UtraDev 4.1">
<meta name="authors" content="Juma Hemed Lungo">
<meta name="robots" content="all">

<meta http-equiv="Content-Language" content="pt">
<meta name="VI60_defaultClientScript" content="JavaScript">

<title>OUT Student Information System</title>
<link rel="stylesheet" type="text/css" href="/master.css">

<style type="text/css">
<!--
.style24 {font-size: 12px}
.style29 {font-size: 12px; font-family: Verdana, Arial, Helvetica, sans-serif; }
a:link {
	text-decoration: none;
	color: #000099;
}
a:visited {
	text-decoration: none;
	color: #000099;
}
a:hover {
	text-decoration: underline;
	color: #CC0000;
}
a:active {
	text-decoration: none;
	color: #CC0000;
}
.style42 {color: #000000}
.style66 {font-size: 18}
.style67 {font-size: 24px}
.style68 {font-size: 18px}
.style71 {font-size: 16px}
-->
</style>
</head>

<body bgcolor="#FFFFCC">
<div align="center">
  <center>
    <tr> 
      <td width="100%" height="48"></td>
    </tr>
  </center>
</div>
<div align="center">
  <center>
    <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
      
      <tr bgcolor="#FFFFCC" class="normaltext">
        <td height="28" colspan="3" align="center" valign="middle" nowrap>
        <div align="left" class="style24"><span class="style29"><span class="style42"><font face="Verdana"><b><a href="/academic/lecturerindex.php">Home</a></b></font></span> </span> </div></td>
        <td colspan="2" align="center" valign="middle" bgcolor="#FFFFCC">
          <form action="/academic/lecturerexamofficersearch.php" method="get" class="style24">
            <div align="right"><span class="style42"><font face="Verdana"><b>Search</b></font></span> <font color="006699" face="Verdana"><b>
              <input type="text" name="content" size="15">
              </b></font><font color="#FFFF00" face="Verdana"><b>
              <input type="submit" value="GO" name="go">
            </b></font> </div>
        </form></td>
      </tr>
      <tr>
        <td width="120" rowspan="4" valign="top"></td>
        <td width="36" height="14"></td>
        <td colspan="3" valign="top">
          <div align="left"></div></td>
      </tr>
      <tr>
        <td height="112"></td>
        <td colspan="3" align="left" valign="top"><div align="left">
            <p align="center"><span class="style66"><span class="style67">UNIVERSITY OF DAR ES SALAAM</span><br>
                <span class="style68">DEPARTMENT OF COMPUTER SCIENCE</span></span><br>
		    <span class="style68">COURSE RECORD SHEET</span></span></p>
            <p align="left">&nbsp;</p>
            </div></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td height="88"></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td width="756"></td>
        <td width="18"></td>
        <td width="1671"></td>
      </tr>
      <tr>
        <td><img height="1" width="120" src="/images/spacer.gif"></td>
        <td><img height="1" width="5" src="/images/spacer.gif"></td>
        <td colspan="3"><div align="center">
	<?php	
		$query_examresult = "
SELECT student.Name,
	   student.ProgrammeofStudy,
       examresult.RegNo,
       examresult.ExamNo,
       examresult.CourseCode,
       course.Units,
       examresult.Grade,
       examresult.AYear,
       examresult.Remarks,
	   examresult.SemesterID,
       examresult.Status
FROM examresult
   INNER JOIN course ON (examresult.CourseCode = course.CourseCode)
   INNER JOIN student ON (examresult.RegNo = student.RegNo)
WHERE examresult.RegNo LIKE '%$key%' OR student.Name LIKE '%$key%'";
$result = mysql_query($query_examresult) or die("Mwanafunzi huyu hana matokeo".  mysql_error()); 
			$query = @mysql_query($query_examresult) or die("Cannot query the database.<br>" . mysql_error());
			$row_result = mysql_fetch_array($result);
			$name = $row_result['Name'];
			$degree = $row_result['ProgrammeofStudy'];
						
			if (mysql_num_rows($query) > 0){
			
					$totalunit=0;
					$unittaken=0;
					$sgp=0;
					$totalsgp=0;
					$gpa=0;
			echo  "$name - $RegNo - $degree";
			echo "<table border='1'>";
			echo "<tr><td> S/No </td><td> Name </td><td> RegNo </td><td> ExamNo </td><td>Year </td><td> Course</td><td> Unit </td><td> Grade </td><td> Remarks</td><td> Status</td></tr>";
			$i=1;
				while($result = mysql_fetch_array($query)) {
					
					
					$unit = $result['Units'];
					$totalunit=$totalunit+$unit;
					$regno = $result['RegNo'];
					$examno = $result['ExamNo'];
					$ayear = $result['AYear'];
					$name = $result['Name'];
					$remarks = $result['Remarks'];
					$status = $result['Status'];
					$grade = $result['Grade'];
					
					
						if ($grade=='A'){
							$point=5;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B+'){
							$point=4;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B'){
							$point=3;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='C'){
							$point=2;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='D'){
							$point=1;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='E'){
							$point=0;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}else
							$sgp='';
							
					$coursecode = $result['CourseCode'];
					//$totalRows_result = mysql_num_rows($result);
					
								echo "<tr><td>$i</td>";
								echo "<td>$name</td>";
								echo "<td>$regno</td>";
								echo "<td>$examno</td>";
								echo "<td>$ayear</td>";
								echo "<td>$coursecode</td>";
								echo "<td>$unit</td>";
								echo "<td>$grade</td>";
								echo "<td>$remarks</td>";
								echo "<td>$status</td></tr>";
								$i=$i+1;
						}
						echo "</table>";
						echo "Total Units = ". $totalunit.";   Units Completed = ".$unittaken.  ";   Total Points = ".$totalsgp. ";   GPA = ".substr($totalsgp/$unittaken, 0,3)."<br><hr>";
					}else{ 
					if(!@$reg[$c]){}else{
					echo "$c". ".Sorry, No Records Found for '$reg[$c]'<br><hr>";
							}
						}
					//mysql_close($zalongwa);
					//mysql_free_result($result);
					
	
			
?>
		<img height="8" width="10" src="/images/spacer.gif"></div></td>
      </tr>
    </table>
  </center> 
</div>
<div align="center">
  <center>
  </center>
</div>

</body>

</html>
<?php
mysql_close($zalongwa);
?>
