<?php 
   #POST connected to the database and verfy current session
    require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	# initialise globals
	include('admissionMenu.php');
	# include the header
	global $szSection, $szSubSection;
        $szSection = 'Admission Process';
	$szSubSection = 'Statistics';
	$szTitle = 'Graph of Students Against Department';
	include('admissionheader.php');
include('styles.inc');
?>
<img src="graph.php" />

<?php
$sql="SELECT * FROM student,faculty where faculty.FacultyName=student.Faculty GROUP BY student.Faculty";
$data=mysql_query($sql);
echo"<table class='dtable'>";
$k=1;
echo"<tr><th>Department Code</th><th>Description</th></tr>";
while($d=mysql_fetch_array($data))
{
if($k%2==0)
{
$bg="#cfcfc";
}else
{
$bg="lime";
}
echo "<tr bgcolor='$bg'>";
echo"<th>$d[FacultyID]</th><td>$d[FacultyName]</td>";
echo"</tr>";
$k++;
}
echo"</table>";

?>

