<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('administratorMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Database Maintanance';
	$szSubSection = 'Update ExamNos';
	$szTitle = 'Update Student Registration Numbers';
	include('administratorheader.php');
	
#populate academic year combo box
mysql_select_db($database_zalongwa, $zalongwa);
$query_AYear = "SELECT AYear FROM academicyear ORDER BY AYear DESC";
$AYear = mysql_query($query_AYear, $zalongwa) or die(mysql_error());
$row_AYear = mysql_fetch_assoc($AYear);
?> 

<?php 
if (isset($_POST["add"])) {
$key = addslashes($_POST["Year"]);
# updating examresult regnos

#get all examno
$qregno = "select RegNo from student";
$dbregno = mysql_query($qregno);

while ($row_regno = mysql_fetch_assoc($dbregno)){
	$oldregno = $row_regno['RegNo'];
	$newregno = ereg_replace("[[:space:]]+", "",$oldregno);
	#update examresult table
	$qstd = "update student set RegNo='$newregno' where RegNo='$oldregno'";
	$dbstd =mysql_query($qstd);
	$qexamregister = "update examregister set RegNo='$newregno' where RegNo='$oldregno'";
	$dbexamregister =mysql_query($qexamregister);
	$qsecurity = "update security set RegNo='$newregno' where RegNo='$oldregno'";
	$dbsecurity =mysql_query($qsecurity);
	echo 'Candidate - '.$oldregno.' - updated to '.$newregno.'<br>';
   }
}
?>
<fieldset>
	<legend>Select Appropriate Entries</legend>
		<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" name="frmAyear" target="_self">
		<table width="200" border="1" cellpadding="0" cellspacing="0">
		  <tr>
			<th scope="row" nowrap><div align="right">Academic Year:</div>
			</th>
			<td><select name="Year" size="1">
			<option value="0">[Select Year]</option>
			<?php
				do {  
						?>
						<option value="<?php echo $row_AYear['AYear']?>"><?php echo $row_AYear['AYear']?></option>
						<?php
							} while ($row_AYear = mysql_fetch_assoc($AYear));
									$rows = mysql_num_rows($AYear);
									if($rows > 0) {
						mysql_data_seek($AYear, 0);
						$row_AYear = mysql_fetch_assoc($AYear);
  					}
               ?>
			
			</select></td>
		  </tr>
		  <tr>
			<th scope="row"><div align="right"></div></th>
			<td><input name="add" type="submit" value="Update"></td>
		  </tr>
		</table>
					
		</form>			
 </fieldset>