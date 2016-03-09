<?php
#get connected to the database and verfy current session
	
$username = $_GET['user'];
$semester = $_GET['semester'];
$year = $_GET['year'];
$accpacid = $_GET['accpacid'];
$module = $_GET['module'];
$mtumiaji = $_GET['mtumiaji'];
$RegNo = $_GET['regno'];
$privilege = $_GET['privilege'];
$userFaculty = $_GET['userFaculty'];
$loginName = $_GET['loginName'];
$id = $_GET['id'];
$fees = $_GET['fees'];
$minimumfee = $_GET['minimumfee'];
$balance = $_GET['balance'];
$semester = $_GET['semester'];
$sponsor = $_GET['sponsor'];


//die('here'.$id);

require_once('Connections/sessioncontrol.php');
require_once('Connections/zalongwa.php');

	 	   //echo "<meta http-equiv = 'refresh' content ='0; 
			//	 url = student/studentindex.php?accpac=1>";
             echo '<meta http-equiv = "refresh" content ="0; 
						url = student/studentindex.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'&accpac='.$id.'&year='.$year.'">';
					//exit;
					


?>