<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
include('studentMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Accommodation';
	$szTitle = 'ACCOMMODATION';
	$szSubSection = 'Check Room Allocated';
	//$additionalStyleSheet = './general.css';
	include("studentheader.php");
	
?>
<br> PAGE UNDER CONSTRUCTION
<br> 
<?php

	# include the footer
	include("../footer/footer.php");
?>