<?php
	# start the session
	session_start();
	
	# include the global settings
	
	require_once('../Connections/zalongwa.php'); 

	# define Top level Navigation Array if not defined already
	
	$arrStructure = array();$i=1;
		
	//Help
	$arrStructure[$i] = array( 'name1' => 'Help', 'name2' => 'Usalama', 'url' => 'administratorUserManual.php', 'image' => '',  'width' => '', 'height' => '');
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'User Manual', 'name2' => 'Usaidizi', 'url' => 'administratorUserManual.php', 'width' => '', 'height' => '');
	$j++;
	$i++;

	// Profile
	$arrStructure[$i] = array( 'name1' => 'Profile', 'name2' => 'Profile', 'url' => 'administratorprofile.php', 'width' => '20', 'height' => '50');
	$i++;
	//if ($privilege==2){
			$arrStructure[$i] = array( 'name1' => 'Policy Setup', 'name2' => 'Plicy Setup', 'url' => 'admissionpolicy.php', 'image' => '',  'width' => '2', 'height' => '3');
			$arrStructure[$i]['subsections'] = array(); $j=1;
			$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Academic Year', 'name2' => 'Mwaka', 'url' => 'admissionAYear.php', 'width' => '', 'height' => '');
			$j++;
			//$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Encode Password', 'name2' => 'Mwaka', 'url' => 'administratorConvertpassword.php', 'width' => '', 'height' => '');
			//$j++;

			$i++;
	//}
	// Manage Users
	#check if user is a manager
	//if ($privilege==2){
			$arrStructure[$i] = array( 'name1' => 'Manage Users', 'name2' => 'Angalia Watumiaji', 'url' => 'administratoradduser.php', 'image' => '',  'width' => '2', 'height' => '3');
			$arrStructure[$i]['subsections'] = array(); $j=1;
			$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'User List', 'name2' => 'Ongeza Mtumiaji', 'url' => 'adminmanageuser.php', 'width' => '', 'height' => '');
			$j++;
			$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Web Statistics', 'name2' => 'Takwimu', 'url' => 'administratorWebStatistics.php', 'width' => '', 'height' => '');
			$j++;
			$i++;
	//}
	// Database Maintenance
	$arrStructure[$i] = array( 'name1' => 'Database Maintenance', 'name2' => 'Maombi ya Kuingia', 'url' => 'administratoradhocqueries.php', 'image' => '',  'width' => '2', 'height' => '3');
	
	$arrStructure[$i]['subsections'] = array(); $j=1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Import Data', 'name2' => 'Fomu ya Maombi', 'url' => '../install/zalongwainstall.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Clean Database', 'name2' => 'Fomu ya Maombi', 'url' => 'cleandatabase.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Unknown Students', 'name2' => 'Fomu ya Maombi', 'url' => 'administratorUnknownregno.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Index Student', 'name2' => 'Orodha ya Maombi', 'url' => 'studentid.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Query Database', 'name2' => 'Fomu ya Maombi', 'url' => 'administratoradhocqueries.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Check Connections', 'name2' => 'Orodha ya Maombi', 'url' => 'administratorcheckconnections.php', 'width' => '', 'height' => '');
	$j++;
	$i++;
	
		// Communication
	$arrStructure[$i] = array( 'name1' => 'Communication', 'name2' => 'Mawasiliano', 'url' => 'admissionCheckMessage.php', 'image' => '',  'width' => '', 'height' => '');
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Check Message', 'name2' => 'Pata Habari', 'url' => 'admissionCheckMessage.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'News & Events', 'name2' => 'Pata Habari', 'url' => 'studentNews.php', 'width' => '', 'height' => '');
	$j++;
	$i++;
	
	//Security
	$arrStructure[$i] = array( 'name1' => 'Security', 'name2' => 'Usalama', 'url' => 'admissionSecurity.php', 'image' => '',  'width' => '', 'height' => '');
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Change Password', 'name2' => 'Badili Password', 'url' => 'admissionChangepassword.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Login History', 'name2' => 'Historia ya Kulogin', 'url' => 'admissionLoginHistory.php', 'width' => '', 'height' => '');
	$j++;
	$i++;
	$arrStructure[$i] = array( 'name1' => 'Sign Out', 'name2' => 'Funga Program', 'url' => '../signout.php', 'image' => '',  'width' => '', 'height' => '');
    $i++;
?>