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
	$arrStructure[$i] = array( 'name1' => 'Help', 'name2' => 'Usalama', 'url' => 'lecturerUserManual.php', 'image' => '',  'width' => '', 'height' => '');
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'User Manual', 'name2' => 'Usaidizi', 'url' => 'lecturerUserManual.php', 'width' => '', 'height' => '');
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
			$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Combination', 'name2' => 'Somo', 'url' => 'admissionCombination.php', 'width' => '', 'height' => '');
			$j++;
			$i++;
	}
	// Administration Menus
	$arrStructure[$i] = array( 'name1' => 'Administration', 'name2' => 'Utawala', 'url' => 'lecturerAdministration.php', 'image' => '',  'width' => '2', 'height' => '3');
	
	$arrStructure[$i]['subsections'] = array(); $j=1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Student Register', 'name2' => 'Fomu ya Maombi', 'url' => 'lecturerStudentRegister.php', 'width' => '', 'height' => '');
	$j++;
	if ($privilege==2){
		$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Student Remarks', 'name2' => 'Fomu ya Maombi', 'url' => 'lecturerStudentRemarker.php', 'width' => '', 'height' => '');
		$j++;
		$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Exam Marker', 'name2' => 'Orodha ya Maombi', 'url' => 'lecturerExammaker.php', 'width' => '', 'height' => '');
		$j++;
		$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Publish Exam', 'name2' => 'Orodha ya Maombi', 'url' => 'lecturerexamofficerpublishresults.php', 'width' => '', 'height' => '');
		$j++;
		$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Change Semester', 'name2' => 'Orodha ya Maombi', 'url' => 'lecturerexamofficerchangesemester.php', 'width' => '', 'height' => '');
		$j++;
		$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Course Allocation', 'name2' => 'Orodha ya Maombi', 'url' => 'lecturerCourseAllocation.php', 'width' => '', 'height' => '');
		$j++;	
         $arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Room Application', 'name2' => 'Optional Courses', 'url' => 'roomapp_rtp.php', 'width' => '', 'height' => '');
	    $j++;
	}
     $sql_deptb = "select AR from faculty where AR = '$name'";
                
				$qdept=mysql_query($sql_deptb) or die('cha q Problem');
				$total_row = mysql_num_rows($qdept);
                //die("cha===".$total_row);
				if($total_row > 0)
                {
                      
    $arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Age Statistics', 'name1' => 'Optional Courses', 'url' => 'age_stat.php', 'width' => '', 'height' => '');
	    $j++;
    
                }

	//$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Class Size', 'name2' => 'Fomu ya Maombi', 'url' => 'lecturerClasssize.php', 'width' => '', 'height' => '');
	//$j++;

	$i++;
	
	// Examination Menu
	$arrStructure[$i] = array( 'name1' => 'Examination', 'name2' => 'Usaili Chuoni', 'url' => 'lecturerResults.php', 'image' => '',  'width' => '', 'height' => '');
	
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Search', 'name2' => 'Tafuta Mwanafunzi', 'url' => 'admissionExamResult.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Grade Book', 'name2' => 'Kitabu cha matokeo', 'url' => 'lecturerGradebook.php', 'width' => '', 'height' => '');
	$j++;
    
    
    
    //cha addition
    
     $sql_dept = "select DeptHead from department where DeptHead = '$name'";
                
				$qdept=mysql_query($sql_dept) or die('cha q Problem');
				$total_row = mysql_num_rows($qdept);
                //die("cha===".$total_row);
				if($total_row > 0)
                {
                      
    
                    $arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Departmental Exam Assessment', 'name2' => 'Departmental Exam Assessment', 'url' => 'HODassessment.php', 'width' => '', 'height' => '');
	                $j++;
    
                }
                
                
        $sql_dept = "select DOF from faculty where DOF = '$name'";
                
				$qdept=mysql_query($sql_dept) or die('cha q Problem');
				$total_row = mysql_num_rows($qdept);
                //die("cha===".$total_row);
				if($total_row > 0)
                {
                      
    
                    $arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Faculty Exam Assessment', 'name2' => 'Faculty Exam Assessment', 'url' => 'DOFassessment2.php', 'width' => '', 'height' => '');
	                $j++;
                    
    
                }
                
                
                   $sql_deptc = "select DOF from faculty where MSC = '$name'";
                
				$qdept=mysql_query($sql_deptc) or die('cha q Problem');
				$total_row = mysql_num_rows($qdept);
                //die("cha===".$total_row);
				if($total_row > 0)
                {
                      
    
                    $arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Faculty Exam Assessment', 'name2' => 'Faculty Exam Assessment', 'url' => 'DOFassessment.php', 'width' => '', 'height' => '');
	                $j++;
                    
    
                }
                
                
                $sql_deptb = "select AR from faculty where AR = '$name'";
                
				$qdept=mysql_query($sql_deptb) or die('cha q Problem');
				$total_row = mysql_num_rows($qdept);
                //die("cha===".$total_row);
				if($total_row > 0)
                {
                      
    
                    $arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Faculty Exam Assessment', 'name2' => 'Faculty Exam Assessment', 'url' => 'DOFassessment2.php', 'width' => '', 'height' => '');
	                $j++;
                     $arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Departmental Exam Assessment', 'name2' => 'Departmental Exam Assessment', 'url' => 'HODassessment.php', 'width' => '', 'height' => '');
	                $j++;
    
                }
    
    //end addition
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Excel Import', 'name2' => 'Kitabu cha matokeo', 'url' => 'zalongwaimport.php', 'width' => '', 'height' => '');
	$j++;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Course Result', 'name2' => 'Ada ya Chuo', 'url' => 'lecturerCourseresult.php', 'width' => '', 'height' => '');
	$j++;
	if ($privilege==2){ 
		$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Annual Report', 'name2' => 'GPA ya Idara', 'url' => 'lecturerAnnualreport.php', 'width' => '', 'height' => '');
		$j++;
		$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Semester Report', 'name2' => 'GPA ya Idara', 'url' => 'lecturerSemesterreport.php', 'width' => '', 'height' => '');
		$j++;
		$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Cand. Transcript', 'name2' => 'Ada ya Chuo', 'url' => 'lecturerTranscript.php', 'width' => '', 'height' => '');
		$j++;
		$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Cand. Statement', 'name2' => 'Ada ya Chuo', 'url' => 'lecturerProgress.php', 'width' => '', 'height' => '');
		$j++;
		$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Cumulative Points', 'name2' => 'Jumla ya Points', 'url' => 'lecturerCumulativepoint.php', 'width' => '', 'height' => '');
		$j++;
	    $arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Elective Courses', 'name2' => 'Optional Courses', 'url' => 'lecturerCleangpa.php', 'width' => '', 'height' => '');
	    $j++;
        $arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Exam Time Table', 'name2' => 'Optional Courses', 'url' => 'examtimetable.php', 'width' => '', 'height' => '');
	    $j++;
	       $arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Exam Numbers', 'name2' => 'Optional Courses', 'url' => 'examnumber.php', 'width' => '', 'height' => '');
	    $j++;
       
	}
	$i++;
	
	// E-Learning
	$arrStructure[$i] = array( 'name1' => 'E-Learning', 'name2' => 'Mawasiliano', 'url' => 'lecturerElearning.php', 'image' => '',  'width' => '', 'height' => '');
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Lecture Note', 'name2' => 'Shahada', 'url' => 'lecturercourseregisterednotes.php', 'width' => '', 'height' => '');
	$j++;
	$i++;
	
	// Communication
	$arrStructure[$i] = array( 'name1' => 'Communication', 'name2' => 'Mawasiliano', 'url' => 'admissionComm.php', 'image' => '',  'width' => '', 'height' => '');
	$arrStructure[$i]['subsections'] = array(); $j = 1;
	//$arrStructure[$i]['subsections'][$j] = array( 'name1' => 'Suggestion Box', 'name2' => 'Sanduku la Maoni', 'url' => 'admissionSuggestionBox.php', 'width' => '', 'height' => '');
	//$j++;
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