<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('administratorMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Database Maintanance';
	$szSubSection = 'Unknown Students';
	$szTitle = 'Unknown Candidates';
	include('administratorheader.php');
	?>
<?php
$browser  =  $_SERVER["HTTP_USER_AGENT"];   
$ip  =  $_SERVER["REMOTE_ADDR"];   
$today = date("F j, Y, g:i a");
$username = addslashes($_GET['username']);

$sql="INSERT INTO stats(ip,browser,received,page) VALUES('$ip','$browser',now(),'Data Backup')";   
$result = mysql_query($sql);// or die("Siwezi kuingiza data.<br>" . mysql_error());

//get all RegNo in Exam Results
$qexamregno = "Select DISTINCT RegNo, ExamNo, CourseCode, Grade, AYear FROM examresult ORDER BY CourseCode ASC";
$dbexamregno= mysql_query($qexamregno);
$i=1;
?>
<table width="200" border="1" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC">
  <tr>
    <th scope="col">S/No</th>
    <th scope="col">RegNo</th>
	 <th scope="col">ExamNo</th>
    <th nowrap scope="col">Course Code </th>
    <th scope="col">Grade</th>
	<th scope="col">Year</th>
  </tr>
  
<?php
while ($row_examregno = mysql_fetch_array($dbexamregno)){
$examregno = $row_examregno['RegNo'];
$coursecode = $row_examregno['CourseCode'];
$grade = $row_examregno['Grade'];
$year = $row_examregno['AYear'];
$examno = $row_examregno['ExamNo'];

//query student regno
	$qstudentregno = "SELECT RegNo FROM student where RegNo = '$examregno'";
	$dbstudentregno = mysql_query($qstudentregno);
	$totalresult = mysql_num_rows($dbstudentregno);
	if ($totalresult<1){
	?>
	<tr>
    <td><?php echo $i; ?></td>
    <td><?php echo $examregno; ?></td>
	<td><?php echo $examno; ?></td>
    <td><?php echo $coursecode; ?></td>
    <td><?php echo $grade;?></td>
	<td><?php echo $year;$i=$i+1; ?></td>
  </tr>
<?php
	}
}?> </table> <?php	