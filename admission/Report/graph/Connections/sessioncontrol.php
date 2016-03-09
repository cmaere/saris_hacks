<?php
session_start();
session_cache_limiter('nocache');

@$privilege  = $_SESSION['privilege'];
@$RegNo = $_SESSION['RegNo'];
@$username = $_SESSION['username'];
@$name = $_SESSION['loginName'];
$userFaculty = $_SESSION['userFaculty']; 


if(!$username){
	echo ("Session Expired, <a href=\"../index.html\"> Click Here<a> to Re-Login");
	echo '<meta http-equiv = "refresh" content ="0; 
	url = ../index.php">';
	exit;
}		
?>
