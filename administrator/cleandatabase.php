<?php 
#get connected to the database and verfy current session
require_once('../Connections/sessioncontrol.php');
require_once('../Connections/zalongwa.php');

# initialise globals
include('administratorMenu.php');

# include the header
global $szSection, $szSubSection;
$szSection = 'Database Maintenance';
$szSubSection = 'Clean Database';
$szTitle = 'Clean Database: Detects all Duplicated Records';
include('administratorheader.php');

mysql_select_db($database_zalongwa, $zalongwa);
$query_AcademicYear = "SELECT AYear FROM academicyear ORDER BY AYear DESC";
$AcademicYear = mysql_query($query_AcademicYear, $zalongwa) or die(mysql_error());
$row_AcademicYear = mysql_fetch_assoc($AcademicYear);
$totalRows_AcademicYear = mysql_num_rows($AcademicYear);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($_POST['search']) && ($_POST['search'] == "Search Results")) {
#get post variables
$year = trim(addslashes($_POST['ayear']));
$decision = $_POST['checkbox'];
echo 'Processing Duplicate Records for Academic Year: '. $year;
if($decision=='on'){
	echo '<br> Delete Option is On';
}else{
	echo '<br> Delete Option is Off';
}
	#get all student
	$qregno = "select RegNo from student order by regno";
	$dbregno = mysql_query($qregno);
	while ($row_regno = mysql_fetch_assoc($dbregno)){
	$regno = trim($row_regno['RegNo']);
			
		#get all ayear
		$qayear = "select distinct ayear from academicyear where ayear='$year' order by ayear";
		$dbayear = mysql_query($qayear);
		while ($row_ayear=mysql_fetch_assoc($dbayear)){
		$ayear = $row_ayear['ayear'];
	
			#get all courses of this regno
			$qcourse = "select distinct coursecode from examresult where regno='$regno' and ayear='$ayear' order by coursecode";
			$dbcourse = mysql_query($qcourse) or die('course query has problem <br>');
			while ($row_course=mysql_fetch_assoc($dbcourse)){
				$coursecode = trim($row_course['coursecode']);
				
				#get all examcategory for this courses
				$qexamcate = "select distinct examcategory from examresult where regno='$regno' and coursecode = '$coursecode' and ayear='$ayear' order by examcategory";
				$dbexamcate = mysql_query($qexamcate);
				while ($row_examcate = mysql_fetch_assoc($dbexamcate)){
					$examcate = $row_examcate['examcategory'];
					
					#get records for this examcategory
					$qexam = "select examscore, examsitting from examresult where regno='$regno' and coursecode='$coursecode' and examcategory='$examcate' and ayear='$ayear' order by examsitting";
					$dbexam=mysql_query($qexam);
					$row_count = mysql_num_rows($dbexam);
					if ($row_count>1){
						echo '<hr> Duplicate Found for Candidate '.$regno.' on Course '.$coursecode.'<br>';
						$flag =0;
						while ($row_exam = mysql_fetch_assoc($dbexam)){
								$flag=$flag+1;
									if ($flag==1){
										$score1 = $row_exam['examscore'];
										$sit1 = $row_exam['examsitting'];
									echo 'Examcategory = '.$examcate. '; ExamScore = '. $score1.'; ExamSitting = '.$sit1.'<br>';
									}else{
										$score2 = $row_exam['examscore'];
										$sit2 = $row_exam['examsitting'];
									echo 'Examcategory = '.$examcate. '; ExamScore = '. $score2.'; ExamSitting = '.$sit2.'<br>';
									}
							}
								#take action
								if ($score1<=$score2){
									$qupdate = "update examresult set comment='deleteme' where regno='$regno' and coursecode='$coursecode' and examcategory='$examcate' and ayear='$ayear' and examsitting='$sit1'";
									if ($decision=='on'){
										$dbupdate=mysql_query($qupdate);
									}
									echo 'Dear, I delete the one with sitting value = '.$sit1;
								}else{
									$qupdate = "update examresult set comment='deleteme' where regno='$regno' and coursecode='$coursecode' and examcategory='$examcate' and ayear='$ayear' and examsitting='$sit2'";
									if ($decision=='on'){
										$dbupdate=mysql_query($qupdate);
									}
									echo 'Dear, I delete the one with sitting value = '.$sit2;
						}
					}
				}
			}
		}
	 }
	if ($decision=='on'){
		$qdeletedup = "delete from examresult where comment='deleteme'";
		$dbdeletedup = mysql_query($qdeletedup)or die ('nimeshindwa kuzifuta baba');
		echo '<hr> All Duplicates Deleted';
		
		$qupdatesitting = "update examresult set examsitting='1' where ayear='$ayear'";
		$dbsitting = mysql_query($qupdatesitting) or die ('<br> .... there are still exist some duplicates, please recheck again');
	}

}else{

?>

       <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" name="housingvacantRoom" id="housingvacantRoom">
            <fieldset bgcolor="#CCCCCC">
				<legend>Search Duplicates </legend>
			    <table width="255" border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
        <tr>
          <td nowrap><div align="right">Delete Duplicate:</div></td>
          <td width="132" bordercolor="#ECE9D8" bgcolor="#CCCCCC"><span class="style67">
            <input type="checkbox" name="checkbox" value="on">
          </span></td>
        </tr>
        <tr>
          <td nowrap><div align="right">Academic Year: </div></td>
          <td bgcolor="#CCCCCC"><select name="ayear" id="select2">
		  <option value="0">SelectAcademicYear</option>
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
        </tr>

        <tr>
          <td colspan="2" nowrap>
            <div align="center">
              <input type="submit" name="search" value="Search Results">
              </div></td></tr>
      </table>
	  </fieldset>
                    <input type="hidden" name="MM_search" value="room">
</form>
<?php
}
?>