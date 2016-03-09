<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Profile';
	$szSubSection = 'Profile';
	$szTitle = 'Academic Module';
	include('lecturerheader.php');

#Store Login History	
$browser  =  $_SERVER["HTTP_USER_AGENT"];   
$ip  =  $_SERVER["REMOTE_ADDR"];
$name = $username." - Visited the Academic Page";   
//$username = $username." "."Visited ".$szTitle;
$sql="INSERT INTO stats(ip,browser,received,page) VALUES('$ip','$browser',now(),'$name')";   
$result = mysql_query($sql) or die("Siwezi kuingiza data.<br>" . mysql_error());

	
?>
		Welcome to the Academic Module.<br>
<br>
<?php

	# include the footer
	include('../footer/footer.php');
?>