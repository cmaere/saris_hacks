<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
include('admissionMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Security';
	$szTitle = 'Security';
	$szSubSection = 'Security';
	//$additionalStyleSheet = './general.css';
	include("admissionheader.php");
	
?>
<br> Please Use "Change Passowrd" to change your Password 
<br> Use "Login History" to know your Login History.
<?php

	# include the footer
	include("../footer/footer.php");
?>