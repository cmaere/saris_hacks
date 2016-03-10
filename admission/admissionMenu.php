<?php
	# start the session
	session_start();
	
	# include the global settings
	
require_once('../Connections/zalongwa.php'); 
	global $blnPrivateArea,$szHeaderPath,$szFooterPath,$szRootPath;
	$blnPrivateArea = false;
	$szHeaderPath = 'header.php';
	$szFooterPath = 'footer.php';

	# define Top level Navigation Array if not defined already
	
	$arrStructure = array();$i=1;
		
	//Help
	$arrStructure[$i] = array( 'name1' => 'Help', 'name2' => 'Usalama', 'url' => 'admissionUserManual.php', 'image' => '',  'width' => '', 'height' => '');
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'User Manual', 'name2' => 'Usaidizi', 'url' => 'admissionUserManual.php', 'width' => '', 'height' => '');
	$j++;
	$i++;
	// Profile
	$arrStructure[$i] = array( 'name1' => 'Profile', 'name2' => 'Profile', 'url' => 'admissionprofile.php', 'width' => '20', 'height' => '50');
	$i++;
	
	// Database Lookup Tables Setup
	#check if user is a manager
	if ($privilege==2){
			$arrStructure[$i] = array( 'name1' => 'Policy Setup', 'name2' => 'Plicy Setup', 'url' => 'admissionpolicy.php', 'image' => '',  'width' => '2', 'height' => '3');
			$arrStructure[$i]['subsections'] = array(); $j=1;
			$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Institution', 'name2' => 'Chuo', 'url' => 'admissionInst.php', 'width' => '', 'height' => '');
			$j++;
			$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Faculty', 'name2' => 'Kitivo', 'url' => 'admissionFaculty.php', 'width' => '', 'height' => '');
			$j++;
			$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Department', 'name2' => 'Kitivo', 'url' => 'admissionDepartment.php', 'width' => '', 'height' => '');
			$j++;
			$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Programme', 'name2' => 'Shahada', 'url' => 'admissionProgramme.php', 'width' => '', 'height' => '');
			$j++;
			$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Subject', 'name2' => 'Somo', 'url' => 'admissionSubject.php', 'width' => '', 'height' => '');
			$j++;

			//$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'CutoffPoint', 'name2' => 'Alama za Mwisho', 'url' => 'admissionCutoffpoint.php', 'width' => '', 'height' => '');
			//$j++;
			$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Sponsor', 'name2' => 'Mfadhiri', 'url' => 'aformSponsor.php', 'width' => '', 'height' => '');
			$j++;
			$i++;
	}
	// Application Process
	/*
	$arrStructure[$i] = array( 'name1' => 'Application Process', 'name2' => 'Maombi ya Kuingia', 'url' => 'admissionProcess.php', 'image' => '',  'width' => '2', 'height' => '3');
	
	$arrStructure[$i]['subsections'] = array(); $j=1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Application Form', 'name2' => 'Fomu ya Maombi', 'url' => 'admissionNewapplication.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Application List', 'name2' => 'Orodha ya Maombi', 'url' => 'admissionApplicationList.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Recommended List', 'name2' => 'Waliopendekezwa', 'url' => 'admissionRecommendedList.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Search Application', 'name2' => 'Tafuta Muombaji', 'url' => 'admissionSearchApplication.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Application Status', 'name2' => 'Statistikis', 'url' => 'admissionApplicationStatistics.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Matriculation', 'name2' => 'Matriculation', 'url' => 'admissionMatriculation.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Application Fee', 'name2' => 'Ada ya Maombi', 'url' => 'admissionApplicationFee.php', 'width' => '', 'height' => '');
	$j++;
	$i++;
	*/
	// Admission Process
	$arrStructure[$i] = array( 'name1' => 'Admission Process', 'name2' => 'Usaili Chuoni', 'url' => 'admissionstart.php', 'image' => '',  'width' => '', 'height' => '');
	
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	if ($privilege==2){
		$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Registration Form', 'name2' => 'Formu ya Kuandikishwa', 'url' => 'admissionRegistrationForm.php', 'width' => '', 'height' => '');
		$j++;
	}
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Nominal Roll', 'name2' => 'Orodha ya Wanafunzi', 'url' => 'admissionNominalRoll.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Reset Email Passwd', 'name2' => 'email', 'url' => 'emailpasswd.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Admission Status', 'name2' => 'Statistics', 'url' => 'admissionRegisteredStatistics.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Search Student', 'name2' => 'Tafuta Mwanafunzi', 'url' => 'admissionSearchStudent.php', 'width' => '', 'height' => '');
        $j++;
        $arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Delete Students', 'name2' => 'Formu ya Kuandikishwa', 'url' => 'DeleteStudent.php', 'width' => '', 'height' => '');
	$j++;
        $arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Restore logs', 'name2' => 'Formu ya Kuandikishwa', 'url' => 'RestoreStudents.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Admission Books', 'name2' => 'Formu ya Kuandikishwa', 'url' => 'admissionBook.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Course Books', 'name2' => 'Formu ya Kuandikishwa', 'url' => 'courseBook.php', 'width' => '', 'height' => '');
	$j++;
	

	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Course Assignment', 'name2' => 'Formu ya Kuandikishwa', 'url' => 'admissionCourseLeactureAssignment.php', 'width' => '', 'height' => '');
	$j++;
	//$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Exams Result', 'name2' => 'Matokeo ya Mtihani', 'url' => 'admissionExamResult.php', 'width' => '', 'height' => '');
	//$j++;
	
	
	if ($privilege==2){
	/*
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Tuition Fee', 'name2' => 'Ada ya Chuo', 'url' => 'admissionTuitionFee.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Caution Fee', 'name2' => 'Fedha za Tahadhari', 'url' => 'admissionCautionFee.php', 'width' => '', 'height' => '');
	$j++;
	*/
	}
	$i++;
	// Voting System
	$arrStructure[$i] = array( 'name1' => 'E-Voting System', 'name2' => 'Mawasiliano', 'url' => 'admissionVoteresults.php', 'image' => '',  'width' => '', 'height' => '');
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Election Posts', 'name2' => 'Nafasi', 'url' => 'admissionElectionpost.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Set Candidates', 'name2' => 'Wagombea', 'url' => 'admissionSetcandidate.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Election Results', 'name2' => 'Matokeo', 'url' => 'admissionVoteresults.php', 'width' => '', 'height' => '');
	$j++;
	$i++;
	// Communication
	$arrStructure[$i] = array( 'name1' => 'Communication', 'name2' => 'Mawasiliano', 'url' => 'admissionComm.php', 'image' => '',  'width' => '', 'height' => '');
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
