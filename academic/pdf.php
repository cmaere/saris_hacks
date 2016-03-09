<?php


#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');


$class=$_GET['class'];
$code=$_GET['code'];
//$class=$row_coursecode['prefix'];
//die($class);

include('pdf/tutorial/faculty_assesspdf.php');

 
			

?>