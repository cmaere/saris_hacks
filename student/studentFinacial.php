<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
include('studentMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Financial Records';
	$szTitle = 'Student Finacial Records';
	$szSubSection = '';
	include("studentheader.php");
	
?>
Please Use Your "Financial Records Menu" to Find Your Payments Reports.
<?php

	# include the footer
	include("../footer/footer.php");
?>