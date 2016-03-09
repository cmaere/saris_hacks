<?php 
include('../Connections/sessioncontrol.php');
# include the header
include('studentMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Profile';
	$szTitle = 'User Profile';
	$szSubSection = 'Policy Setup';
	include("studentheader.php");
?>
<?php
mysql_select_db($database_zalongwa, $zalongwa);
$query_Recordset1 = "SELECT ProgrammeName FROM programme ORDER BY ProgrammeName ASC";
$Recordset1 = mysql_query($query_Recordset1, $zalongwa) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_zalongwa, $zalongwa);
$query_Recordset2 = "SELECT sex FROM sex ORDER BY sex ASC";
$Recordset2 = mysql_query($query_Recordset2, $zalongwa) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_zalongwa, $zalongwa);
$query_Recordset3 = "SELECT FacultyName FROM faculty ORDER BY FacultyName ASC";
$Recordset3 = mysql_query($query_Recordset3, $zalongwa) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

if (isset($_POST['action']) && ($_POST['action'] == 'Update Profile')){
//$sex = addslashes($_POST['sex']);
//$status = addslashes($_POST['textStatus']);
$email = addslashes($_POST['textEmail']);
//$degree = addslashes($_POST['degree']);
//$faculty = addslashes($_POST['faculty']);
$address = addslashes($_POST['textaddress']);
#check if use has submitted valid email address
function check_email_address($email) {
  // First, we check that there's one @ symbol, and that the lengths are right
  if (!ereg("[^@]{1,64}@[^@]{1,255}", $email)) {
    // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
    return false;
  }
  // Split it into sections to make life easier
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
     if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
      return false;
    }
  }  
  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
        return false; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
        return false;
      }
    }
  }
  return true;
}

if($email<>''){
			if (check_email_address($email)) {
			  //echo $email . ' is a valid email address.';
				} else {
			  echo $email . ' is not a valid email address, 
			   <br>Please Click the  Back Button to Return Back <br>
			   ZALONGWA DATABASE SYSTEM Administrator.';
			  exit;
			}
}


if(!empty($RegNo)) {

//$sql = "UPDATE student SET Sex='$sex', Address='$address', ProgrammeofStudy= '$degree', Faculty= '$faculty' WHERE RegNo = '$RegNo'";
$sql = "UPDATE student SET Address='$address' WHERE RegNo = '$RegNo'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
//$sql = "UPDATE security SET Position='$status', Email='$email' WHERE RegNo = '$RegNo'";
$sql = "UPDATE security SET Email='$email' WHERE RegNo = '$RegNo'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
//echo "Database Updated.";
echo '<meta http-equiv = "refresh" content ="0; 
	url = admissionprofile.php">';
} else {
echo '<meta http-equiv = "refresh" content ="0; 
	url = admissionprofile.php?RegNo=$RegNo">';
}
mysql_close($zalongwa);
}
?>
<?php
if (isset($_GET['RegNo'])){
$new=2;
$sql = "SELECT student.Name, 
student.RegNo, student.Sex, 
student.Address, 
student.Faculty, 
student.ProgrammeofStudy,
security.UserName, 
security.Position, 
security.Email, 
security.Registered
FROM security INNER JOIN student ON security.RegNo = student.RegNo
 WHERE security.RegNo = '$RegNo'";
$query = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
		$Name = stripslashes($result["Name"]);
		$RegNo = stripslashes($result["RegNo"]);
		$sex = stripslashes($result["Sex"]);
		$faculty = stripslashes($result["Faculty"]);
		$degree = stripslashes($result["ProgrammeofStudy"]);
		//get degree name
			$qdegree = "Select Title from programme where ProgrammeCode = '$degree'";
			$dbdegree = mysql_query($qdegree);
			$row_degree = mysql_fetch_array($dbdegree);
			$programme = $row_degree['Title'];
		$username = stripslashes($result["UserName"]);
		$position = stripslashes($result["Position"]);
		$email = stripslashes($result["Email"]);
		$address = stripslashes($result["Address"]);
		$registered = stripslashes($result["Registered"]);
			
}
?>
    <form name="form1" method="post" action="#">
      <table width="200" border="1">
  <tr>
  	<td></td>
    <td>Name</td>
    <td><?php echo $Name; ?> </td>
  </tr>
  <tr>
    <td></td>
	<td>RegNo</td>
    <td><?php echo $RegNo; ?> </td>
 
   <tr>
    <td></td>
	<td>Login ID</td>
    <td><?php echo $username; ?> </td>
  </tr>
   <tr>
    <td></td>
	<td>Address/Tel</td>
    <td><textarea name="textaddress" rows="4" id="textaddress"><?php echo $address; ?> </textarea></td>
  </tr>
  <tr>
    <td></td>
	<td>E-Post</td>
    <td><input name="textEmail" type="text" id="textEmail" value="<?php echo $email; ?>"></td>
  </tr>
  <tr>
    <td></td>
	<td>Registered</td>
    <td><?php echo $registered; ?></td>
	</tr>
</table>
<input name="action" type="submit" id="action" value="Update Profile">
    </form>
<br>
<?php
}
$new=1;
if ($new==1){
$sql = "SELECT student.Name, 
			   student.RegNo, 
			   student.Sex, 
			   student.Address, 
			   student.Faculty, 
			   student.Nationality, 
			   student.EntryYear, 
			   student.ProgrammeofStudy, 
			   security.UserName, 
			   security.Position, 
			   security.Email, 
			   security.Registered
	  FROM security INNER JOIN student ON security.RegNo = student.RegNo
 	  WHERE security.RegNo = '$RegNo'";
$query = @mysql_query($sql) or die("Huduma Hii Kwa Sasa Haipo.<br>");
echo "<table border='1'>";
echo "<tr><td> Name </td><td> RegNo </td><td> Sex </td><td> Nationality </td><td> Faculty </td><td> Degree </td><td> Login ID </td><td> Status </td><td> Address </td><td> E-Post </td><td> Registered </td></tr>";
while($result = mysql_fetch_array($query)) {
		$Name = stripslashes($result["Name"]);
		$RegNo = stripslashes($result["RegNo"]);
		$sex = stripslashes($result["Sex"]);
		$faculty = stripslashes($result["Faculty"]);
		$degree = stripslashes($result["ProgrammeofStudy"]);
		//get degree name
			$qdegree = "Select Title from programme where ProgrammeCode = '$degree'";
			$dbdegree = mysql_query($qdegree);
			$row_degree = mysql_fetch_array($dbdegree);
			$programme = $row_degree['Title'];
		$username = stripslashes($result["UserName"]);
		$position = stripslashes($result["Position"]);
		$email = stripslashes($result["Email"]);
		$registered = stripslashes($result["EntryYear"]);
		$address = stripslashes($result["Address"]);
		$nationality = stripslashes($result["Nationality"]);
			echo "<tr><td>$Name</td>";
			echo "<td>$RegNo</td>";
			echo "<td>$sex</td>";
			echo "<td>$nationality</td>";
			echo "<td>$faculty</td>";
			echo "<td>$programme</td>";
			echo "<td>$username</td>";
			echo "<td>$position</td>";
			echo "<td>$address</td>";
			echo "<td>$email</td>";
			echo "<td>$registered</td></tr>";
		}
echo "</table>";

 print "<a href=\"admissionprofile.php?RegNo=$RegNo\">Edit Your Contacts</a>";
 mysql_free_result($query);
 mysql_close($zalongwa);
 }
	# include the footer
	include("../footer/footer.php");
?>