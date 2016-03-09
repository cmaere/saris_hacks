<?php
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	# include the header
	include('admissionMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'E-Voting System';
	$szTitle = 'Election Results';
	$szSubSection = 'Election Results';
	include("admissionheader.php");
?>
<?php
#populate academic year combo box
mysql_select_db($database_zalongwa, $zalongwa);
$query_AYear = "SELECT AYear FROM academicyear ORDER BY AYear DESC";
$AYear = mysql_query($query_AYear, $zalongwa) or die(mysql_error());
$row_AYear = mysql_fetch_assoc($AYear);

mysql_select_db($database_zalongwa, $zalongwa);
$query_post = "SELECT * FROM electionpost ORDER BY Post ASC";
$post = mysql_query($query_post, $zalongwa) or die(mysql_error());
$row_post = mysql_fetch_assoc($post);
$totalRows_post = mysql_num_rows($post);
?> 

<?php 
if (isset($_POST["add"])) {
$key = addslashes($_POST["Year"]);
$post = addslashes($_POST["cmbPost"]);
# updating examresult regnos

#get all candidates
$qexamno = "select * from electioncandidate WHERE Period = '$key' AND Post = '$post' ORDER BY Post";
$dbexamno = mysql_query($qexamno);
$totalrec = mysql_num_rows($dbexamno);
if ($totalrec>0){
?>
<table border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td><strong>Period</strong></td>
	<td><strong>Candidate</strong></td>
    <td><strong>Post</strong></td>
	<td><strong>Faculty</strong></td>
	<td><strong>Institution</strong></td>
	<td><strong>Votes</strong></td>
  </tr>

<?php
		while ($row_examno = mysql_fetch_assoc($dbexamno)){
			$candidateid = trim($row_examno['id']);
			$period = trim($row_examno['Period']);
			$name = trim($row_examno['Name']);
			$post= trim($row_examno['Post']);
			$faculty = trim($row_examno['Faculty']);
			$inst= trim($row_examno['Institution']);
			#count votes
			$qvote = "select * from electionvotes where CandidateID='$candidateid'";
			$dbvote =mysql_query($qvote);
			$vote = mysql_num_rows($dbvote);
			?>
	<tr>
    <td norwap> <?php echo $period?></td>
	<td norwap> <?php echo $name?></td>
    <td norwap><?php echo $post?></td>
	<td norwap><?php echo $faculty?></td>
	<td norwap><?php echo $inst?></td>
	<td norwap><?php echo $vote?></td>
  </tr>

			<?php
		   }
	 }else{
	 echo 'No electionresults for this year - '.$key;
	 }
	 ?></table><?php
}else{
?>
<fieldset>
	<legend>Select Appropriate Entries</legend>
		<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" name="frmAyear" target="_self">
		<table width="200" border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
		  <tr>
			<th scope="row" nowrap><div align="right"> Year:</div>
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
		  <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Post:</div></th>
      <td><select name="cmbPost" id="cmbPost" title="<?php echo $row_post['Post']; ?>">
        <?php
do {  
?>
        <option value="<?php echo $row_post['Post']?>"><?php echo $row_post['Post']?></option>
        <?php
} while ($row_post = mysql_fetch_assoc($post));
  $rows = mysql_num_rows($post);
  if($rows > 0) {
      mysql_data_seek($post, 0);
	  $row_post = mysql_fetch_assoc($post);
  }
?>
      </select></td>
    </tr>

		  <tr>
			<th scope="row"><div align="right"></div></th>
			<td><input name="add" type="submit" value="Search"></td>
		  </tr>
		</table>
					
		</form>			
 </fieldset>
 <?php } ?>