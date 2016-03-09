<?php
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('admissionMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Help';
	$szSubSection = 'User Manual';
	$szTitle = 'Admission Module User Guide';
	include("admissionheader.php");
?>
<h1><a name="_Toc125001733">Module 3: Admission Module </a></h1>
<p>The module has six (6) menus namely: Profile, Policy Setup, Admission Process, Communication, Security, and Sign Out. </p>
<h2><a name="_Toc125001734">Admission Process Menu </a></h2>
<p>This menu offers functionalities for maintaining student nominal rolls. With this menu, a student can be registered in the database through the use of Registration Form command.</p>
<p><strong>Nominal Roll: </strong> enables listing of all students admitted in the selected academic year. </p>
<p><strong>To Add New User: </strong> Select Registration Form, Complete the form, click AddNew To get Calendar, click Choose Date command</p>
<p><strong>To Edit Student Record: </strong> Search the candidate, then click the S/No against the name </p>
<p>To get Calendar, click Choose Date command </p>
<p><strong>To Delete Student Record: </strong> Search Candidate, Update Record, then Click Delete</p>
<p><strong>Alternative Reports: </strong>To print a list of all students for the selected academic year, check the “Print All Records” checkbox </p>
<p><a name="_Toc125001735">Admission Status: </a></p>
<p>This create statistics for the number of students admitted in that particular year. </p>

</fieldset>