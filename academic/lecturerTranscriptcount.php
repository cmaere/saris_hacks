<?php
#get connected to the database and verfy current session
require_once('../Connections/sessioncontrol.php');
require_once('../Connections/zalongwa.php');
# initialise globals
require_once('lecturerMenu.php');

# include the header
global $szSection, $szSubSection;
$szSection = 'Examination';
$szSubSection = 'Cand. Transcript';
$szTitle = 'Transcript of Examination Results';
require_once('lecturerheader.php');
# today date
$today = date("Y-m-d");

if (isset($_POST['PreView']) && ($_POST['PreView'] == "PreView")) {
	#get post variables
	$rawkey = addslashes(trim($_POST['datevalue']));
	$key = ereg_replace("[[:space:]]+", "",$rawkey);
	$sn=1;
	?>
  <table border="1" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr bgcolor="#CCCCCC">
    <td colspan="6" nowrap><strong>Trancript From <?php echo $key; ?> To <?php echo $today; ?> (Sorted by RegNo)</strong></td>
  </tr>	
  <tr bgcolor="#CCCCCC">
    <td nowrap><strong>S/No</strong></td>
	<td nowrap><strong>RegNo</strong></td>	
	<td nowrap><strong>Sex</strong></td>
	<td nowrap><strong>Name</strong></td>	
	<td nowrap><strong>Programme</strong></td>		
	<td nowrap><strong>Printed Date</strong></td>
  </tr>	
	<?php
	#get transcript list
	$qcand = "SELECT DISTINCT RegNo FROM transcriptcount WHERE received >='$key%' ORDER BY RegNo";
	$dbcand = mysql_query($qcand);
	while($result = mysql_fetch_array($dbcand)) {
		$candregno=$result['RegNo'];
		#find printdate
		$qpdate = "SELECT received FROM transcriptcount WHERE RegNo = '$candregno' ORDER BY received DESC LIMIT 1";
		$dbpdate = mysql_query($qpdate) or  die("something is wrong");
		$result_pdate = mysql_fetch_array($dbpdate);
		$printdate=$result_pdate['received'];
		#query name and date printed
		$qcandname = "SELECT Name, sex, ProgrammeofStudy from student WHERE RegNo='$candregno'";
		$dbcandname = mysql_query($qcandname);
		$row_candname = mysql_fetch_assoc($dbcandname);
		$candname = $row_candname['Name'];
		$candsex = $row_candname['sex'];
		$degree = $row_candname['ProgrammeofStudy'];
		//get degree name
		$qdegree = "Select Title from programme where ProgrammeCode = '$degree'";
		$dbdegree = mysql_query($qdegree);
		$row_degree = mysql_fetch_array($dbdegree);
		$programme = $row_degree['Title'];
		#print results
		?>
          <tr>
            <td nowrap><?php echo $sn?></td>
            <td nowrap><?php echo strtoupper($candregno)?></td>	
            <td nowrap><?php echo $candsex?></td>
            <td nowrap><?php echo $candname?></td>
            <td nowrap><?php echo $programme?></td>
            <td nowrap><?php echo substr($printdate,0,10)?></td>			
          </tr>	
		<?php
        $sn=$sn+1;
	}
	echo '</table>';
}else{
?>

 <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="save" target="_self">
    <strong>Transcript Counter Report
    </strong>   
    <table  border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td nowrap><strong>From Date :</strong></td>
	<!-- A Separate Layer for the Calendar -->
	<script language="JavaScript" src="datepicker/Calendar1-901.js" type="text/javascript"></script>    
	<td><input name="datevalue" type="text" id="datevalue" value=<?php echo $today; ?> maxlength="20"></td>
	<td><input type="button" class="button" name="dtDate_button" value="Calendar" onClick="show_calendar('save.datevalue', '','','YYYY-MM-DD', 'POPUP','AllowWeekends=Yes;Nav=No;SmartNav=Yes;PopupX=300;PopupY=300;')"></td>
  </tr>
  <tr>
    <td></td>
    <td><div align="center">
      <input name="PreView" type="submit" value="PreView">
    </div></td>
  </tr>
</table>
</form>
<?php } ?>