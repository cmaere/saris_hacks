<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
include('admissionMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Communication';
	$szTitle = 'Communication';
	$szSubSection = 'User Communication';
	//$additionalStyleSheet = './general.css';
	include("admissionheader.php");
	
?>
<br> Please Use Your "Suggestion Box" to Send any Comments on the 
<br> Design and Missing Information to the System Engineer. 
<br> Use "Check Message" to get Messages from Other Users.
<?php

	# include the footer
	include("../footer/footer.php");
?>