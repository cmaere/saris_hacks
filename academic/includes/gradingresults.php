<?php
	#query Coursework
	$qtest2 = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$key' AND ExamCategory=4 AND AYear='$ayear'";
	$dbtest2=mysql_query($qtest2);
	$total_test2 = mysql_num_rows($dbtest2);
	$row_test2=mysql_fetch_array($dbtest2);
	$value_test2score=$row_test2['ExamScore'];
	if(($total_test2>0)&&($value_test2score<>'')){
		$test2date=$row_test2['ExamDate'];
		$test2score=$value_test2score;
	}else{
		//$remarks = "Inc";
		$test2score='';
	}

	#query Annual Exam
	$qae = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$key' AND ExamCategory=5 AND AYear='$ayear'";
	$dbae=mysql_query($qae);
	$total_ae = mysql_num_rows($dbae);
	$row_ae=mysql_fetch_array($dbae);
	$value_aescore=$row_ae['ExamScore'];
	if(($total_ae>0)&&($value_aescore<>'')){
		$aedate=$row_ae['ExamDate'];
		$aescore=$value_aescore;
	}else{
		$remarks = "Inc";
		$aescore='';
	}
	
	#query Special Exam
	$qsp = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$key' AND ExamCategory=7 AND AYear='$ayear'";
	$dbsp=mysql_query($qsp);
	$total_sp = mysql_num_rows($dbsp);
	$row_sp=mysql_fetch_array($dbsp);
	$value_spscore=$row_sp['ExamScore'];
	if(($total_sp>0)&&($value_spscore<>'')){
		$spdate=$row_sp['ExamDate'];
		$spscore=$value_spscore;
		$remarks = "sp";
	}else{
		$spscore='';
	}
								
	#query Supplimentatary Exam
	$qsup = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$key' AND ExamCategory=6 AND AYear='$ayear'";
	$dbsup=mysql_query($qsup);
	$row_sup=mysql_fetch_array($dbsup);
	$row_sup_total=mysql_num_rows($dbsup);
	$supdate=$row_sup['ExamDate'];
	$supscore=$row_sup['ExamScore'];
	if(($row_sup_total>0)&&($supscore<>'')){
		$remarks = '';
		#empty coursework
		$test2score ='n/a';
	}
	
	#query Project Exam
	$qpro = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$key' AND ExamCategory=8 AND AYear='$ayear'";
	$dbpro=mysql_query($qpro);
	$row_pro=mysql_fetch_array($dbpro);
	$row_pro_total=mysql_num_rows($dbpro);
	$prodate=$row_pro['ExamDate'];
	$proscore=$row_pro['ExamScore'];
	if(($row_pro_total>0)&&($proscore<>'')){
		$remarks = '';
		#empty coursework
		$test2score ='n/a';
	}
	
	#query Teaching Practice Exam
	$qtp = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$key' AND ExamCategory=9 AND AYear='$ayear'";
	$dbtp=mysql_query($qtp);
	$row_tp=mysql_fetch_array($dbtp);
	$row_tp_total=mysql_num_rows($dbtp);
	$tpdate=$row_tp['ExamDate'];
	$tpscore=$row_tp['ExamScore'];
	if(($row_tp_total>0)&&($tpscore<>'')){
		$remarks = '';
		#empty coursework
		$test2score ='n/a';
	}
	
	#query Practical Training Exam
	$qpt = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$key' AND ExamCategory=10 AND AYear='$ayear'";
	$dbpt=mysql_query($qpt);
	$row_pt=mysql_fetch_array($dbpt);
	$row_pt_total=mysql_num_rows($dbpt);
	$ptdate=$row_pt['ExamDate'];
	$ptscore=$row_pt['ExamScore'];
	if(($row_pt_total>0)&&($ptscore<>'')){
		$remarks = '';
		#empty coursework
		$test2score ='n/a';
	}

	#get total marks
	if (($row_sup_total>0)&&($supscore<>'')){
		$tmarks = $supscore;
		if($tmarks>=40){
			$gradesupp='C';
			$remark = 'PASS';
		}
	}elseif(($row_pro_total>0)&&($proscore<>'')){
		$tmarks = $proscore;
	}elseif(($row_tp_total>0)&&($tpscore<>'')){
		$tmarks = $tpscore;
	}elseif(($row_pt_total>0)&&($ptscore<>'')){
		$tmarks = $ptscore;
	}elseif(($total_sp>0)&&($spscore<>'')){
		$tmarks = $test2score + $spscore;
	}else{
		$tmarks = $test2score + $aescore;
	}

#grade marks
$marks = round($tmarks);
	
	if($remarks =='Inc'){
	$grade='I';
	$remark = 'Inc.';
	}elseif($marks ==-2){
					$grade='PASS';
					$remark = 'PASS';
	}else{
		#-----------
		if(($test2score<16)&&($test2score<>'n/a')){
			$remark = 'C/Repeat';
			$grade='*';
		}elseif (($aescore<24)&&($marks>=50)&&($aescore<>'')){
				$remark = 'Fail Exam';
				$grade='#';
		}elseif (($test2score>=16)&&($marks<50)){
				$remark = 'Fail Exam';
				if($marks>=45){
					$grade='D';
				}else{
					$grade='E';
				}
		}elseif ($marks<>0){
				if($marks>=75){
					$grade='A';
					$remark = 'PASS';
				}elseif($marks>=70){
					$grade='B+';
					$remark = 'PASS';
				}elseif($marks>=60){
					$grade='B';
					$remark = 'PASS';
				}elseif($marks>=50){
					$grade='C';
					$remark = 'PASS';
		}elseif($marks>=45){
			$grade='D';
			$remark = 'FAIL';
		}else{
			$grade='E';
			$remark = 'FAIL';
		}
	}
}

#manage supplimentary exams
if ($gradesupp=='C'){
	$grade='C';
	$remark='PASS';
	#empty gradesupp
	$gradesupp='';
}

?>