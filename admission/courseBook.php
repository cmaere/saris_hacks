<?php
#get connected to the database and verfy current session
require_once('../Connections/sessioncontrol.php');
require_once('../Connections/zalongwa.php');
	# initialise globals
	include('admissionMenu.php');
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Admission Process';
	$szSubSection = 'Course Books';
	$szTitle = 'Select Department to view registered Courses';
	include('admissionheader.php');
include('styles.inc');

echo "
<form action='courseBookList.php' method='POST'>
<table>
<tr>
<td class='formfield'>Department/Faculty:</td>
<td>";
?>
<select name="faculty" id="select"  <?php echo $state4;?>>
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
