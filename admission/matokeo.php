<?php
#get connected to the database and verfy current session
require_once('../Connections/sessioncontrol.php');
require_once('../Connections/zalongwa.php');
# initialise globals
include('admissionMenu.php');
# include the header
global $szSection, $szSubSection;
$szSection = 'Admission Process';
$szSubSection = 'Registration Form';
$szTitle = 'Form Four NECTA Result Registration Form';
include('admissionheader.php');
//End of Connections


//Responding to user action
if(isset($_POST['data']))
{
$Grade=$_POST[Eq];
$regno=$_POST['regno'];
$IndexNo=$_POST['IndexNo'];
$SubjectID=$_POST['SubjectID'];

$count=count($Grade);
echo $count."HEREE";
if($count>0)
{
for($c=0;$c<$count;$c++)
{
$plug="INSERT INTO olevel_results(SubjectID,Grade,IndexNo,regno) values('$SubjectID','$Grade','$IndexNo','$regno')";
$plug_matokeo=mysql_query($plug);
}
}else
{
echo"Cant deal with empty records";
}
}
///************End of Receiving Result

//form of accepting results start here
$student="select * from olevel_subjects";
$masomo=mysql_query($student);
echo"<form name='matokeo' action='$_SERVER[PHP_SELF]' method='POST'>";
echo"<table cellspacing='0' cellpadding='0' border='1'>";
echo"<tr>";
echo"<th>S/N</th>";
echo"<th>Subject</th>";
echo"<th>Subject Name</th>";
echo"<th>Grade</th>";
echo"</tr>";
while($r=mysql_fetch_array($masomo))
{
echo"<tr>";
echo"<td>$k</td>";
echo"<td>$r[SubjectID]</td>";
echo"<td>$r[SubjectName]</td>";
echo"<td>";
echo"<input type='hidden' name='SubjectID' value='$r[SubjectID]'>";
echo"<input type='text' name='Eq[]' value='$r[Grade]'>";
echo"</td>";
echo"</tr>";
$k++;
}
echo"</table>";
echo"<table>";
echo"<tr>";
echo"<td>";
echo"Registration Number:&nbsp;";
echo"<input type='text' name='regno' value='$regno'>";
echo"</td>";
echo"<td>";
echo"Form Index No:&nbsp;";
echo"<input type='text' name='IndexNo' value='$IndexNo'>";
echo"</td>";
echo"<td>";
echo"<input type='reset' name='clear' value='Clear'>";
echo"</td>";
echo"<td>";
echo"<input type='submit' name='data' value='Save'>";
echo"</td>";
echo"</tr>";
echo"</table>";
echo"</form>";
?>
