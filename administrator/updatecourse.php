<?php
require_once('../Connections/zalongwa.php'); 

#update course
$qcourse = "SELECT ProgrammeCode, Faculty FROM programme";
$dbcourse = mysql_query($qcourse);
while ($row_course = mysql_fetch_assoc($dbcourse))
{
	$dept = $row_course['Faculty'];
	$progcode = $row_course['ProgrammeCode'];

	#update course
	$qcprog = "UPDATE course SET Programme = '$progcode' WHERE Department = '$dept'";
	$dbcprog = mysql_query($qcprog);
}
echo 'Mission Completed !!';
?>