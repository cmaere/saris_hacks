<?php 
$accpac = $_GET['accpac'];
require_once('../Connections/sessioncontrol.php');
# include the header  
$_SESSION['accpac'] = $accpac; 
//die('here'.$accpac);
include('studentMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Profile';
	$szTitle = 'Profile';
	$szSubSection = 'Profile';
  
	include("studentheader.php");
   
	
?>
<br>
<?php require_once('../Connections/zalongwa.php');

$sql = "SELECT FullName, Email, Position, UserName, LastLogin FROM security WHERE UserName = '$username'";
$query = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
echo "<table border='1'>";
if($accpac == 1)
{

    echo "<tr><td><font color='red'><h1>PLEASE PAY TO ACCOUNTS OFFICE FOR YOU TO REGISTER</h1></font></tr>";

}
echo "<tr><td> Name </td><td> Login ID </td><td> Status </td><td> E-Post </td><td> Last Login </td></tr>";
while($result = mysql_fetch_array($query)) {
		$Name = stripslashes($result["FullName"]);
		$username = stripslashes($result["UserName"]);
		$position = stripslashes($result["Position"]);
		$email = stripslashes($result["Email"]);
		$registered = stripslashes($result["LastLogin"]);
			echo "<tr><td>$Name</td>";
			echo "<td>$username</td>";
			echo "<td>$position</td>";
			echo "<td>$email</td>";
			echo "<td>$registered</td></tr>";
		}
echo "</table>";
#Store Login History	
$browser  =  $_SERVER["HTTP_USER_AGENT"];   
$ip  =  $_SERVER["REMOTE_ADDR"];
$jina = $username." - Visited the Student Page";   
//$username = $username." "."Visited ".$szTitle;
$sql="INSERT INTO stats(ip,browser,received,page) VALUES('$ip','$browser',now(),'$jina')";   
$result = mysql_query($sql) or die("Siwezi kuingiza data.<br>" . mysql_error());
	# include the footer
	include("../footer/footer.php");
?>