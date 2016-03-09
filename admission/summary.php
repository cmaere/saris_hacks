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
	$szTitle = 'Admission Record Filtering';
	include('admissionheader.php');
include('styles.inc');

echo "
<form action='$_SERVER[PHP_SELF]' method='POST'>
<table>
<tr>
<td class='formfield'>Academic Year:</td>
<td>";
?>
<select name="ayear" id="select" class="vform" <?php echo $state4;?>>
<?php
echo"<option value=''>[Select Academic Year]</option>";
$nm=mysql_query("SELECT AYear FROM academicyear where AYear!='$ayear' ORDER BY AYear DESC");
while($show = mysql_fetch_array($nm) )
{  										 
echo"<option  value='$show[AYear]'>$show[AYear]</option>";      
}
?>					                                        						 
</select>
</td>
<td>
</td>
</tr>
<tr>
<td class='formfield'>Department/Faculty:</td>
<td>
<select name="faculty" id="select" class="vform" <?php echo $state4;?>>
<?php
echo"<option value=''>[Select Department]</option>";
echo"<option  value='all'>All departments</option>";  
$nm=mysql_query("SELECT * FROM faculty ORDER BY FacultyName DESC");
while($show = mysql_fetch_array($nm) )
{  										 
echo"<option  value='$show[FacultyID]'>$show[FacultyName]</option>";
}
echo"<option  value='all'>All departments</option>";   
?>							                                        				 
</select>
</td>
<td>
</td>
</tr>
<tr><td></td><td>
<input type='submit' name='Go' value='Go'>
</td>
</tr>
</table>
</form>


<li>
<a href='graph1.php'>Graph</a></li>
<li>
<a href='BookPanel.php'>Nominall Roll</a>
</li>
<hr>
<?php
if(isset($_POST['Go']))
{
$faculty=$_POST['faculty'];
$ayear=$_POST['ayear'];

if($faculty)
{
if($faculty=="all")
{
$ct="select * from faculty";
$dept="ALL DEPARTMENTS";
$male_all=mysql_query("SELECT * FROM student WHERE Sex='M' and EntryYear='$ayear'");
$male_total=mysql_num_rows($male_all);
$female_all=mysql_query("SELECT * FROM student WHERE  Sex='F' and EntryYear='$ayear'");
$female_total=mysql_num_rows($female_all);
}else
{
$ct="select * from faculty  where FacultyID='$faculty'";
$dp=mysql_query("select * from faculty where FacultyID='$faculty'");
$d=mysql_fetch_array($dp);
$facultyName=$d['FacultyName'];
$dept=strtoupper($d['FacultyName']);
$male_all=mysql_query("SELECT * FROM student WHERE Faculty='$facultyName' and Sex='M' and EntryYear='$ayear'");
$male_total=mysql_num_rows($male_all);
$female_all=mysql_query("SELECT * FROM student WHERE Faculty='$facultyName' and Sex='F' and EntryYear='$ayear'");
$female_total=mysql_num_rows($female_all);
}

$jumla=$female_total+$male_total;
$male_pecentage=($male_total/$jumla)*100;
$female_pecentage=($female_total/$jumla)*100;
}
else
{
$ct="select * from faculty";

}
$query=mysql_query($ct)or die(mysql_error());
$pg=1;

echo "Male: $male_pecentage %<br>";
echo "Female: $female_pecentage %<br>";
echo"<table width='1000'><tr>";
echo"<th >ADMISSION SUMMARY FOR ACADEMIC YEAR $ayear UNDER $dept.</th></tr></table>";
echo"<table  border='1' cellspacing='0' cellpadding='0' width='950' class='dtable'>";
echo"<tr bgcolor='#ccccff'>
<th>S/N</th>
<th>Department</th>
<th colspan='2'>Programe</th>
<th>Male</th>
<th>Female</th>
<th>Total</th>
</tr>";
while($r=mysql_fetch_array($query))
{
$cat=mysql_query("SELECT  * FROM programme WHERE Faculty='$r[FacultyName]'");
$num=mysql_num_rows($cat);
$students=mysql_query("SELECT * FROM student WHERE ProgrammeofStudy='$r[Title]' and EntryYear='$ayear'") or die(mysql_error());
$M=0;
$F=0;
while($count=mysql_fetch_array($students))
{
if($count['Sex']=='M')
{
$M++;
}else
{
$F++;
}
}
echo "<tr>
<td rowspan='$num'>$pg</td>
<td rowspan='$num'>&nbsp;$r[FacultyName]</td>";
$g=mysql_query("SELECT  * FROM programme where Faculty='$r[FacultyName]'");
$total=mysql_num_rows($g);
if($total>0)
{
while($gl=mysql_fetch_array($g))
{
//Count All students
$student_no=mysql_num_rows($students);
//Count MALE STUDENTS
$male_students=mysql_query("SELECT * FROM student WHERE ProgrammeofStudy='$gl[ProgrammeCode]' and Sex='M' and EntryYear='$ayear'");
$male_student_no=mysql_num_rows($male_students);
//Count FEMALE STUDENTS
$female_students=mysql_query("SELECT * FROM student WHERE ProgrammeofStudy='$gl[ProgrammeCode]' and Sex='F' and EntryYear='$ayear'");
$female_student_no=mysql_num_rows($female_students);
$wote=$female_student_no+$male_student_no;
echo"<td>&nbsp;$gl[ProgrammeName]</td>
<td>&nbsp;$gl[Title]</td>
<td>&nbsp;$male_student_no</td>
<td>&nbsp;$female_student_no</td>";
echo "<td>&nbsp;$wote</td>";
echo"</tr>";
}
}else
{
echo"<td>&nbsp;$gl[ProgrammeName]</td>
<td>&nbsp;$gl[Title]</td>
<td>&nbsp;$male_student_no</td>
<td>&nbsp;$female_student_no</td>";
echo "<td>&nbsp;$wote</td>";
echo"</tr>";
}
$pg++;
}
echo"</table>";
}
else
{
}
?>
