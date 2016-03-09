<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('admissionMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Profile';
	$szSubSection = 'Profile';
	$szTitle = 'Admission Module';
	include('admissionheader.php');

#Store Login History	
$browser  =  $_SERVER["HTTP_USER_AGENT"];   
$ip  =  $_SERVER["REMOTE_ADDR"];   
//$username = $username." "."Visited ".$szTitle;
$sql="INSERT INTO stats(ip,browser,received,page) VALUES('$ip','$browser',now(),'$username')";   
$result = mysql_query($sql) or die("Siwezi kuingiza data.<br>" . mysql_error());
	
?>
		Welcome to the Admission Office Module.<br>
<br>
<?php

	# include the footer
	include('../footer/footer.php');
?>