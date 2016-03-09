<?php
require_once('../Connections/sessioncontrol.php');
# include the header
include('lecturerMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Examination';
	$szTitle = 'Examination';
	$szSubSection = '';
	//$additionalStyleSheet = './general.css';
	include("lecturerheader.php");
	
	$query_AcademicYear = "SELECT AYear FROM academicyear ORDER BY AYear DESC";
$AcademicYear = mysql_query($query_AcademicYear, $zalongwa) or die(mysql_error());
	
?>
<br> 
<table>
<tr><td>Class:</td></tr>
<td nowrap><div align="right">STUDENT COHORT: </div></td>
          <td colspan="4" ><select name="cohot" id="select2">
		  <option value="0">--------------------------------</option>
            <?php
do {  
?>
            <option value="<?php echo $row_AcademicYear['AYear']?>"><?php echo $row_AcademicYear['AYear']?></option>
            <?php
} while ($row_AcademicYear = mysql_fetch_assoc($AcademicYear));
  $rows = mysql_num_rows($AcademicYear);
  if($rows > 0) {
      mysql_data_seek($AcademicYear, 0);
	  $row_AcademicYear = mysql_fetch_assoc($AcademicYear);
  }
?>
          </select></td>
</table>
<?php

	# include the footer
	include("../footer/footer.php");
?>