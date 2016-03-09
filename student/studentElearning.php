<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
include('studentMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'E-Learning';
	$szTitle = 'Lecturer Notes and Assignments';
	$szSubSection = '';
	//$additionalStyleSheet = './general.css';
	include("studentheader.php");
	
?>
<br> Please Use Your "E-Learning Menu" to Operate the System 
<br> 
<?php

	# include the footer
	include("../footer/footer.php");
?>