<?php
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('admissionMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Admission Process';
	$szSubSection = 'Registration Form';
	$szTitle = 'Student Registration Form';
	include('admissionheader.php');
	
	?>
	<?php
	#get record id
	$id = $_GET['id'];
	#delete candidate
	$qdelete = "DELETE FROM student WHERE Id = '$id'";
	$dbdelete = mysql_query($qdelete);
	
	#delete examresults
	$qdeleteresult = "DELETE FROM examresult WHERE RegNo='$regno'";
	mysql_query($qdeleteresult);
	
	#print message
	if($dbdelete){
		echo $regno." - this candidate is deleted from the database!";
	}else{
		echo "Cannot delete this candidate - ".$regno;
	}