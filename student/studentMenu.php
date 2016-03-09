<?php
	# start the session
	session_start();
	
	# include the global settings
	$accpac2 = $_SESSION['accpac'];
    //die($accpac2);
	require_once('../Connections/zalongwa.php'); 
	global $blnPrivateArea,$szHeaderPath,$szFooterPath,$szRootPath;
	$blnPrivateArea = false;
	$szHeaderPath = 'header.php';
	$szFooterPath = 'footer.php';
	
	 $fees = $_GET['fees'];
			$minimumfee = $_GET['minimumfee'];
			$balance = $_GET['balance'];
			$semester = $_GET['semester'];
			$sponsor = $_GET['sponsor'];

	# define Top level Navigation Array if not defined already
	
	$arrStructure = array();$i=1;
		
	//Help
	$arrStructure[$i] = array( 'name1' => 'Help', 'name2' => 'Usalama', 'url' => 'studentUserManual.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'image' => '',  'width' => '', 'height' => '');
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'User Manual', 'name2' => 'Usaidizi', 'url' => 'studentUserManual.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
	$j++;
	$i++;
	
	// Profile
	$arrStructure[$i] = array( 'name1' => 'Profile', 'name2' => 'Profile', 'url' => 'admissionprofile.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '20', 'height' => '50');
	$i++;
	
		// Academic records
	$arrStructure[$i] = array( 'name1' => 'Academic Records', 'name2' => 'Taaluma', 'url' => 'studentAcademic.php?accpac='.$accpac.'&sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'image' => '',  'width' => '2', 'height' => '3');
	$arrStructure[$i]['subsections'] = array(); $j=1;
    
    $sqltype = "SELECT weaver FROM security WHERE UserName = '$username'";
		$result = mysql_query($sqltype);
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
   		{
       
			$weaver = $row["username"];
        }
    
    if($accpac2 == 0)
    {
      //die('here2'.$accpac);
	 
			
        $arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Course Roster', 'name2' => 'Kitivo', 'url' => 'studentCourselist.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
        $j++;
        $arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Exam Registered', 'name2' => 'Kitivo', 'url' => 'studentAcademic.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
        $j++;
    }
   
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Exam Result', 'name2' => 'Kitivo', 'url' => 'studentexamresult.php?accpac='.$accpac.'&sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
	$j++;
	$i++;
	
	// Course Evaluation
	$arrStructure[$i] = array( 'name1' => 'Course Evaluation', 'name2' => 'Mawasiliano2', 'url' => 'studentcourseevaluation.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'image' => '',  'width' => '', 'height' => '');
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Course Evaluation', 'name2' => 'Shahada', 'url' => 'studentcourseevaluation.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
	$j++;
	$i++;
     
	// Ficancial records
	$arrStructure[$i] = array( 'name1' => 'Financial Records', 'name2' => 'Malipo', 'url' => 'studentFinacial.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'image' => '',  'width' => '', 'height' => '');
	
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Tuition Fee', 'name2' => 'Orodha ya Wanafunzi', 'url' => 'studentroomrent.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Caution Fee', 'name2' => 'Statistics', 'url' => 'studentcautionfee.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Penalty Charge', 'name2' => 'Formu ya Kuandikishwa', 'url' => 'studentpenaltcharges.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Transactions', 'name2' => 'Tafuta Mwanafunzi', 'url' => 'studentTuitionfee.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
	$j++;
	$i++;
	
    // E-Learning
	$arrStructure[$i] = array( 'name1' => 'Accommodation', 'name2' => 'Mawasiliano', 'url' => 'studentaccommodation.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'image' => '',  'width' => '', 'height' => '');
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Apply for a Room', 'name2' => 'Shahada', 'url' => 'roomapplication.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Check Room Allocated', 'name2' => 'Shahada', 'url' => 'roomawards.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
	$j++;
	$i++;
    
	// E-Learning
	$arrStructure[$i] = array( 'name1' => 'E-Learning', 'name2' => 'Mawasiliano', 'url' => 'studentElearning.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'image' => '',  'width' => '', 'height' => '');
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Lecture Note', 'name2' => 'Shahada', 'url' => 'studentcourseregisterednotes.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'e-Library', 'name2' => 'Shahada', 'url' => 'studentelibrary.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
	$j++;
	$i++;
    
	// E-voting
	$arrStructure[$i] = array( 'name1' => 'E-Voting', 'name2' => 'Mawasiliano', 'url' => 'admissionVoting.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'image' => '',  'width' => '', 'height' => '');
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Election Voting', 'name2' => 'Shahada', 'url' => 'admissionVoting.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
	$j++;
	$i++;
	
	// Communication
	$arrStructure[$i] = array( 'name1' => 'Communication', 'name2' => 'Mawasiliano', 'url' => 'admissionComm.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'image' => '',  'width' => '', 'height' => '');
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Suggestion Box', 'name2' => 'Sanduku la Maoni', 'url' => 'admissionSuggestionBox.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Check Message', 'name2' => 'Pata Habari', 'url' => 'admissionCheckMessage.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'News & Events', 'name2' => 'Pata Habari', 'url' => 'studentNews.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
	$j++;
	$i++;
	
	//Security
	$arrStructure[$i] = array( 'name1' => 'Security', 'name2' => 'Usalama', 'url' => 'admissionSecurity.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'image' => '',  'width' => '', 'height' => '');
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Change Password', 'name2' => 'Badili Password', 'url' => 'admissionChangepassword.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Login History', 'name2' => 'Historia ya Kulogin', 'url' => 'admissionLoginHistory.php?sponsor='.$sponsor.'&semester='.$semester.'&fees='.$fees.'&balance='.$balance.'&minimumfee='.$minimumfee.'', 'width' => '', 'height' => '');
	$j++;
	$i++;
	$arrStructure[$i] = array( 'name1' => 'Sign Out', 'name2' => 'Funga Program', 'url' => '../signout.php', 'image' => '',  'width' => '', 'height' => '');
    $i++;
?>