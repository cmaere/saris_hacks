<?php
	#reset gpa calculation values
	$point = '';
	$grade = '';
	$remark = '';
	
	#query Coursework
	$qtest2 = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$RegNo' AND ExamCategory=4";
	$dbtest2=mysql_query($qtest2);
	$total_test2 = mysql_num_rows($dbtest2);
	$row_test2=mysql_fetch_array($dbtest2);
	$value_test2score=$row_test2['ExamScore'];
	if(($total_test2>0)&&($value_test2score<>'')){
		$test2date=$row_test2['ExamDate'];
		$test2score=number_format($value_test2score,1);
	}else{
		$test2score='';
	}
	
	#query Annual Exam
	$qae = "SELECT ExamCategory, Examdate, ExamScore, AYear FROM examresult WHERE CourseCode='$course' AND RegNo='$RegNo' AND ExamCategory=5";
	$dbae=mysql_query($qae);
	$total_ae = mysql_num_rows($dbae);
	$row_ae=mysql_fetch_array($dbae);
	$value_aescore=$row_ae['ExamScore'];
	if(($total_ae>0)&&($value_aescore<>'')){
		$aedate=$row_ae['ExamDate'];
		$aescore=number_format($value_aescore,1);
	}else{
		$remarks = "Inc";
		$aescore='';
	}
	
	#query Special Exam
	$qsp = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$RegNo' AND ExamCategory=7";
	$dbsp=mysql_query($qsp);
	$total_sp = mysql_num_rows($dbsp);
	$row_sp=mysql_fetch_array($dbsp);
	$value_spscore=$row_sp['ExamScore'];
	if(($total_sp>0)&&($value_spscore<>'')){
		$spdate=$row_sp['ExamDate'];
		$spscore=number_format($value_spscor,1);
		$remarks = "sp";
		$aescore = $spscore;
	}else{
		$spscore='';
	}
	
	#query Supplimentatary Exam
	$qsup = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$RegNo' AND ExamCategory=6";
	$dbsup=mysql_query($qsup);
	$row_sup=mysql_fetch_array($dbsup);
	$row_sup_total=mysql_num_rows($dbsup);
	$supdate=$row_sup['ExamDate'];
	$supscore=$row_sup['ExamScore'];
	if(($row_sup_total>0)&&($supscore<>'')){
		$remarks = '';
		$aescore = number_format($supscore,1);
		#empty coursework
		$test2score ='n/a';
	}
	
	#query Project Exam
	$qpro = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$RegNo' AND ExamCategory=8";
	$dbpro=mysql_query($qpro);
	$row_pro=mysql_fetch_array($dbpro);
	$row_pro_total=mysql_num_rows($dbpro);
	$prodate=$row_pro['ExamDate'];
	$proscore=$row_pro['ExamScore'];
	if(($row_pro_total>0)&&($proscore<>'')){
		$remarks = '';
		$aescore = number_format($proscore,1);
		#empty coursework
		$test2score ='n/a';
	}
	
	#query Traching Practice Exam
	$qtp = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$RegNo' AND ExamCategory=9";
	$dbtp=mysql_query($qtp);
	$row_tp=mysql_fetch_array($dbtp);
	$row_tp_total=mysql_num_rows($dbtp);
	$tpdate=$row_tp['ExamDate'];
	$tpscore=$row_tp['ExamScore'];
	if(($row_tp_total>0)&&($tpscore<>'')){
		$remarks = '';
		$aescore = number_format($tpscore,1);
		#empty coursework
		$test2score ='n/a';
	}
	
	#query Practical Training Exam
	$qpt = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$RegNo' AND ExamCategory=10";
	$dbpt=mysql_query($qpt);
	$row_pt=mysql_fetch_array($dbpt);
	$row_pt_total=mysql_num_rows($dbpt);
	$ptdate=$row_pt['ExamDate'];
	$ptscore=$row_pt['ExamScore'];
	if(($row_pt_total>0)&&($ptscore<>'')){
		$remarks = '';
		$aescore = number_format($ptscore,1);
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
	
	#round marks
	//$marks = round($tmarks);
	$marks = number_format($tmarks,1);
	
	/*
	#get points corresponding to this marks
	$qpoints = "SELECT grade, point, remark FROM gradescale WHERE marks = '$marks'";
	$dbpoints = mysql_query($qpoints);
	$row_points = mysql_fetch_assoc($dbpoints);
	$point = $row_points['point'];
	$grade = $row_points['grade'];
	$remark = $row_points['remark'];
	*/
	
	#grade marks
	if($remarks =='Inc'){
	$grade='I';
	$remark = 'Inc.';
	$point=0;
	$sgp=$point*$unit;
	}else{
		if($marks>=69.5){
			$grade='A';
			$remark = 'PASS';
			$margin = 70-$marks;
			$point=5;
			$sgp=$point*$unit;
			$totalsgp=$totalsgp+$sgp;
			$unittaken=$unittaken+$unit;
			if (($margin<=0.5)&&($margin>0)){
				$marks=70;
			}
		}elseif($marks>=59.5){
			$grade='B+';
			$remark = 'PASS';
			$margin = 60-$marks;
			$point=4;
			$sgp=$point*$unit;
			$totalsgp=$totalsgp+$sgp;
			$unittaken=$unittaken+$unit;
			if (($margin<=0.5)&&($margin>0)){
				$marks=60;
			}
		}elseif($marks>=49.5){
			$grade='B';
			$remark = 'PASS';
			$margin = 50-$marks;
			$point=3;
			$sgp=$point*$unit;
			$totalsgp=$totalsgp+$sgp;
			$unittaken=$unittaken+$unit;
			if (($margin<=0.5)&&($margin>0)){
				$marks=50;
			}
		}elseif($marks>=39.5){
			$grade='C';
			$remark = 'PASS';
			$margin = 40-$marks;
			$point=2;
			$sgp=$point*$unit;
			$totalsgp=$totalsgp+$sgp;
			$unittaken=$unittaken+$unit;
			if (($margin<=0.5)&&($margin>0)){
				$marks=40;
			}
		}elseif($marks>=34.5){

			$grade='D';
			$remark = 'SUPP';
			$margin = 35-$marks;
			$point=1;
			$sgp=$point*$unit;
			$totalsgp=$totalsgp+$sgp;
			$unittaken=$unittaken+$unit;
			if (($margin<=0.5)&&($margin>0)){
				$marks=35;
			}
		}else{
			$grade='E';
			$remark = 'SUPP';
			$point=0;
			$sgp=$point*$unit;
			$totalsgp=$totalsgp+$sgp;
			$unittaken=$unittaken+$unit;
		}
	}
	
	#check if ommited
	$qcount = "SELECT DISTINCT Count FROM examresult WHERE CourseCode='$course' AND RegNo='$RegNo'";
	$dbcount=mysql_query($qcount);
	$row_count=mysql_fetch_array($dbcount);
	$count =$row_count['Count'];
	if ($count==1){
	$sgp =0;
	$unit=0;
	$coursename ='*'.$coursename;
	}
	
	#compute gpa values
	$sgp=$point*$unit;
	$totalsgp=$totalsgp+$sgp;
	$unittaken=$unittaken+$unit;
	
	
	 if(($test2score<16)&&($test2score<>'n/a')){
		//$grade='E*';
		//$remark = 'C/Repeat';
		//$egrade='*';
	}elseif($remarks =='Inc'){
		$grade='I';
		$remark = 'Inc.';
		$igrade='I';
	}elseif($marks ==-2){
		$grade='PASS';
		$remark = 'PASS';
	}else{
	/*
		if(($test2score<16)&&($test2score<>'n/a')){
				$remark = 'Fail CWK';
				$grade='E';
				$fcwk='*';
		}elseif (($aescore<20)&&($marks>=50)&&($aescore<>'')){
					$remark = 'Fail Exam';
					$grade='#';
					$fexm='#';
		}elseif (($test2score>=16)&&($marks<50)){
					$remark = 'Fail Exam';
					$supp='!';
		}
	*/
   }

#manage supplimentary exams
	if ($gradesupp=='C'){
		$unittaken=$unittaken-$unit;
		$totalsgp=$totalsgp-$sgp;
		$grade='C'; // put the fixed value of a supplimentary grade
		$point=2; // put the fixed value for SUPP point whic is equivalent to 50 marks
		$sgp=$point*$unit;
		$totalsgp=$totalsgp+$sgp;
		$unittaken=$unittaken+$unit;
		#empty gradesupp
		$gradesupp='';
	}

#format sgp and totalsgp
$sgp = number_format($sgp,1,'.',',');
$totalsgp = number_format($totalsgp,1,'.',',');

#get course semester
$qsem = "SELECT YearOffered FROM course WHERE CourseCode = '$course'";
$dbsem = mysql_query($qsem);
$row_sem = mysql_fetch_assoc($dbsem);
$semname = $row_sem['YearOffered'];
#get semester ID
$qsemid = "SELECT Id FROM terms WHERE Semester = '$semname'";
$dbsemid = mysql_query($qsemid );
$row_semid = mysql_fetch_assoc($dbsemid);
$semid = $row_semid['Id'];

#format printing numbers

?>