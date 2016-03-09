<?php

	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	# include the header
	include('studentMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'E-Voting';
	$szTitle = 'Election Voting';
	$szSubSection = 'Election Voting';
	include("studentheader.php");
?>
<?php

$fees = $_GET['fees'];
$minimumfee = $_GET['minimumfee'];
$balance = $_GET['balance'];
$semester = $_GET['semester'];
$sponsor = $_GET['sponsor'];
if($fees < $minimumfee && $sponsor == 1 && $semester == "Semester II") 
 {
	   
	//echo "<font color='#FF0000'><b>YOU CAN NOT REGISTER FOR SECOND SEMESTER, YOU HAVE TO SETTLE YOU BALANCE AT ACCOUNTS OFFICE. YOUR BALANCE IS $balance </font>";   
	   
 }
 else
   {


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
if (isset($_POST["save"])) {
$key = addslashes($_POST["Year"]);
$post = addslashes($_POST["cmbPost"]);
$name = addslashes($_POST["Candidate"]);
#get student faculty
$qfac = "SELECT Faculty from student where RegNo='$RegNo'";
$dbfac = mysql_query($qfac);
$row_fac = mysql_fetch_assoc($dbfac);
$stdfac = $row_fac['Faculty'];

$qcandfac = "SELECT Faculty FROM electioncandidate where id = '$name'";
$dbcandfac = mysql_query($qcandfac);
@$row_candfac = mysql_fetch_assoc($dbcandfac);
$candfac = $row_candfac['Faculty'];
#insert vote
if ($name==0){
	echo 'Invalid Vote Entries, Please select appropriate values';
	exit;
	}elseif($candfac == $stdfac) {
		$qins = "INSERT INTO electionvotes VALUES('$RegNo','$name','$key','$post')";
		$dbins = mysql_query($qins) or die('Rejected, ZALONGWA Knows that you are attempting to vote once more!');
	}elseif($candfac == '[All Faculties]') {
		$qins = "INSERT INTO electionvotes VALUES('$RegNo','$name','$key','$post')";
		$dbins = mysql_query($qins) or die('Rejected, ZALONGWA Knows that you are attempting to vote once more!');
	}else{
	echo 'Rejected, The Candidate You are Voting for Doesnot Belong to your Faculty';
	}
}

if (isset($_POST["add"])) {
$key = addslashes($_POST["Year"]);
$post = addslashes($_POST["cmbPost"]);
#chek if has already voted for this post
$qpost = "SELECT RegNo from electionvotes where RegNo = '$RegNo' AND Post = '$post' AND Period = '$key'";
$dbpost = mysql_query($qpost);
$total_rows = mysql_num_rows($dbpost);
#check expired election
$qedate = "SELECT EndDate FROM electionpost where Post = '$post' AND Period = '$key'";
$dbedate = mysql_query($qedate);
$row_edate = mysql_fetch_assoc($dbedate);
$enddate = $row_edate['EndDate'];
$today = date("Y-m-d H:i:s");
if($today>$enddate){
echo 'Election Days Expired! <br>';
echo 'Here are the Election Results';
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
//}
exit;
}elseif($total_rows>0){
echo 'You have already Voted for this post -'.$post;
exit;
}

#get all candidates
$qcandidate = "select * from electioncandidate WHERE Period = '$key' AND Post = '$post' ORDER BY Post";
$dbcandidate = mysql_query($qcandidate);
$totalrec = mysql_num_rows($dbcandidate);
if ($totalrec>0){
$row_candidate = mysql_fetch_assoc($dbcandidate);
?>
<fieldset>
	<legend>Step 2: Select Candidate</legend>
		<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" name="frmAyear" target="_self">
		<input name="Year" type="hidden" value="<?php echo $key?>"><?php echo $key?><br>
		<input name="cmbPost" type="hidden" value="<?php echo $post?>"><?php echo $post?>
		<table width="200" border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
		  <tr>
			<th scope="row" nowrap><div align="right"> Candidate:</div>
			</th>
			<td><select name="Candidate" size="1">
			<option value="0">[Select Candidate]</option>
			<?php
				do {  
						?>
						<option value="<?php echo $row_candidate['id']?>"><?php echo $row_candidate['Name']?></option>
						<?php
							} while ($row_candidate = mysql_fetch_assoc($dbcandidate));
									$rows = mysql_num_rows($dbcandidate);
									if($rows > 0) {
						mysql_data_seek($dbcandidate, 0);
						$row_candidate = mysql_fetch_assoc($dbcandidate);
  					}
               ?>
			
			</select></td>
		  </tr>
		  <tr>
			<th scope="row"><div align="right"></div></th>
			<td><input name="save" type="submit" value="Submit"></td>
		  </tr>
		</table>
					
		</form>			
 </fieldset>
 <?php
	 }else{
	 echo 'No election for this year - '.$key;
	 }
	 ?></table><?php
}else{
?>
<fieldset>
	<legend>Step 1: Select Period and Post</legend>
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
			<td><input name="add" type="submit" value="Next"></td>
		  </tr>
		</table>
					
		</form>			
 </fieldset>
 <?php } 
 
 
 }
 
 //
 
  //echo "Click <a href='../../saris/student/student_votes/2014_Student_Elections.xls'> HERE </a> To view Elections results<br>";
  //echo "Click <a href='../../saris/student/student_votes/2014_Student_Elections.jpg'> HERE </a> To view Elections results on <b> Mobile phones</b><br>";
 
 ?>