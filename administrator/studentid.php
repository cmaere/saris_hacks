<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('administratorMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Database Maintanance';
	$szSubSection = 'Index Student';
	$szTitle = 'Create Student Index';
	include('administratorheader.php');

$getmax = "select MAX(Id) as idmax from student";
$dbmax = mysql_query($getmax);
$row_max = mysql_fetch_assoc($dbmax);
$max = $row_max['idmax']+1;
echo $max.' -> is the max index value <br>';

#get all examno
$qexamno = "select RegNo from student";
$dbexamno = mysql_query($qexamno);
$id = 1;
$row_examno = mysql_fetch_assoc($dbexamno);
while ($row_examno = mysql_fetch_assoc($dbexamno)){
	$regno = trim($row_examno['RegNo']);
	
	#update student table
	$qstd = "update student set Id='$id' where RegNo='$regno'";
	$dbstd =mysql_query($qstd);
	
	echo $regno.' -> index updated  to -> '.$id. '<br>';
	
	#update index
	$id=$id+1;
}
?>