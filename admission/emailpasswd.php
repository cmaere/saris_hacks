 <script type="text/javascript">
function showIcon() {
window.setTimeout('showProgress()', 0);
}
function showProgress() {
document.getElementById('progressImg').style.display = 'inline';
}
</script>  
<div class="progress" id="progressImg">&nbsp;
<img src="progress.gif" alt="Uploading" />
</div>  
<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('admissionMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Admission Process';
	$szSubSection = 'Reset Email Passwd';
	$szTitle = 'Email Password Change';
	include('admissionheader.php');

#Store Login History	
$browser  =  $_SERVER["HTTP_USER_AGENT"];   
$ip  =  $_SERVER["REMOTE_ADDR"];   
//$username = $username." "."Visited ".$szTitle;
$sql="INSERT INTO stats(ip,browser,received,page) VALUES('$ip','$browser',now(),'$username')";   
$result = mysql_query($sql) or die("Siwezi kuingiza data.<br>" . mysql_error());
	
?>
		<table border="1">

<form action="emailpasswd.php" method="post">
<tr><td>
Email address
<td> <input type="text" name="email" value="">
<td> <input type="submit" name="search" value="Search Email" onclick="showIcon();">
</tr>
</form>
</table>

<?php
if(isset($_POST['search']))
{

$email = $_POST['email'];
$command = "python gam/gam.py info user ".$email;
exec($command, $outputArray);

echo $outputArray[0];
$trimEmail = substr($outputArray[0], 6);
?>
<br />
<br />
<form action="emailpasswd.php" method="post">
New Password <input type="text" name="pass" value="" /> <input type="submit" name="reset" value="Reset Password" />
<input type="hidden" name="email2" value="<?php echo $trimEmail; ?>" />
</form>
<?php


}
else if(isset($_POST['reset']))
{
  $pass = $_POST['pass'];
  $email2 = $_POST['email2'];
  $command = "python gam/gam.py update user $email2 password '$pass'";
 // echo $command;
 exec($command, $outputArray2);
 print_r($outputArray2);
  
  
	
	
}


?>


<?php

	# include the footer
	include('../footer/footer.php');
?>








