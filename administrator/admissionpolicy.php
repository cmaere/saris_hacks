<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
include('administratorMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Policy Setup';
	$szTitle = 'Policy Setup';
	$szSubSection = 'Policy Setup';
	include('administratorheader.php');
	
?>
<br>
<?php require_once('../Connections/zalongwa.php');

$sql = "SELECT FullName, Email, Position, UserName, Registered FROM security WHERE UserName = '$username'";
$query = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
echo "<table border='1'>";
echo "<tr><td> Name </td><td> Login ID </td><td> Status </td><td> E-Post </td><td> Registered </td></tr>";
while($result = mysql_fetch_array($query)) {
		$Name = stripslashes($result["FullName"]);
		$username = stripslashes($result["UserName"]);
		$position = stripslashes($result["Position"]);
		$email = stripslashes($result["Email"]);
		$registered = stripslashes($result["Registered"]);
			echo "<tr><td>$Name</td>";
			echo "<td>$username</td>";
			echo "<td>$position</td>";
			echo "<td>$email</td>";
			echo "<td>$registered</td></tr>";
		}
echo "</table>";

	# include the footer
	include("../footer/footer.php");
?>