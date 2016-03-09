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
	$szTitle = 'User Visit Information';
	include('administratorheader.php');
?>  
         <form action="adminmanageuser.php" method="get" class="style24">
            <div align="right"><span class="style42"><font face="Verdana"><b>Search</b></font></span> 
              <font color="006699" face="Verdana"><b> 
              <input type="text" name="content" size="15">
              </b></font><font color="#FFFF00" face="Verdana"><b> 
              <input type="submit" value="GO" name="go">
            </b></font>            </div>
        </form>       <?php
require_once('../Connections/zalongwa.php'); 
$username=addslashes($_GET['login']);
$sql = "SELECT stats.ip,
       stats.browser,
       stats.received,
       stats.page
FROM stats WHERE stats.page LIKE '$username%' ORDER BY stats.received DESC";

//(((roomapplication.Hall)='$hall') And
$result = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
$query = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

$all_query = mysql_query($query);
$totalRows_query = mysql_num_rows($query);
/* Printing Results in html */
if (mysql_num_rows($query) > 0){
echo "Frequencies of User \"$username\" in Visiting the Website";
echo "<table border='1'>";
echo "<tr><td> S/No </td><td> IP Address</td><td> Browser </td><td> Date of Visit </td></tr>";
$i=1;
while($result = mysql_fetch_array($query)) {
		$ip = stripslashes($result["ip"]);
		$browser = stripslashes($result["browser"]);
		$received = stripslashes($result["received"]);
		
			echo "<tr><td>$i</td>";
			echo "<td>$ip</td>";
			echo "<td>$browser</td>";
			echo "<td>$received</td></tr>";
		$i=$i+1;
		}
echo "</table>";
}else{
echo "User \"$username\" has never visited the Website <br>";
}
mysql_close($zalongwa);
?>
  <?php

	# include the footer
	include('../footer/footer.php');
?>