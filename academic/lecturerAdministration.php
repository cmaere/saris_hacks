<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
include('lecturerMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Administration';
	$szTitle = 'Administration';
	$szSubSection = '';
	//$additionalStyleSheet = './general.css';
	include("lecturerheader.php");
	
?>
<br>
Welcome, Please Use the Menus  to Navigate the System
</fieldset>
<?php

	# include the footer
	include("../footer/footer.php");
?>