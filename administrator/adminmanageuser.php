<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('administratorMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Manage Users';
	$szSubSection = '';
	$szTitle = 'List of Database Users';
	include('administratorheader.php')
?>
<form action="adminmanageuser.php" method="get" class="style24">
            <div align="right"><span class="style67"><font face="Verdana"><b>Search</b></font></span> 
              <font color="006699" face="Verdana"><b> 
              <input type="text" name="content" size="15">
              </b></font><font color="#FFFF00" face="Verdana"><b> 
              <input type="submit" value="GO" name="go">
              </b></font>            </div>
        </form>
        <?php
require_once('../Connections/zalongwa.php'); 
if (isset($_GET["content"])){
$key=addslashes($_GET['content']);
$sql = "SELECT FullName, RegNo, UserName, Password, Position, AuthLevel, LastLogin
FROM security 
WHERE FullName LIKE '%$key%' OR UserName LIKE '%$key%' OR RegNo LIKE '%$key%' ORDER BY FullName";
$result = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
$query = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
}else{
$sql = "SELECT FullName, RegNo, UserName, Password, Position, AuthLevel, LastLogin
FROM security ORDER BY FullName";
//(((roomapplication.Hall)='$hall') And
$result = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
$query = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
}
$all_query = mysql_query($query);
$totalRows_query = mysql_num_rows($query);
/* Printing Results in html */
if (mysql_num_rows($query) > 0){
echo "<p>Total Records Found: $totalRows_query </p>";
echo "<table border='1'>";
echo "<tr><td> S/No </td><td> Name </td><td> RegNo </td><td> UserName </td><td> Position </td><td> Last Login</td><td> Edit</td><td>Delete</td></tr>";
$i=1;
while($result = mysql_fetch_array($query)) {
		$login = stripslashes($result["UserName"]);
		$Name = stripslashes($result["FullName"]);
		$RegNo = stripslashes($result["RegNo"]);
		$pwd = stripslashes($result["Password"]);
		$position = stripslashes($result["Position"]);
		$last = stripslashes($result["LastLogin"]);
			echo "<tr><td>$i</td>";
			echo "<td><a href=\"administratoruservisitdetails.php?login=$login\">$Name</a></td>";
			echo "<td>$RegNo</td>";
			echo "<td>$login</td>";
			echo "<td>$position</td>";
			echo "<td>$last</td>";
			echo "<td><a href=\"adminmanageuseredit.php?login=$login\">Edit</a></td>";
			echo "<td><a href=\"adminmanageuserdelete.php?login=$login\">Delete</a></td></tr>";
		$i=$i+1;
		}
echo "</table>";
}else{
$key= stripslashes($key);
echo "Sorry, No Records Found <br>";
echo "That Match With Your Searck Key \"$key \" ";
}
mysql_close($zalongwa);
?>
		 <?php

	# include the footer
	include('../footer/footer.php');
?>