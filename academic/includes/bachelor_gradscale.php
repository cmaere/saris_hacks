<?php
	#reset gpa calculation values
	$point = '';
	$grade = '';
	$remark = '';
	//charlie comments just for now we have disabled first semister result display just do deal with remark error
    /*
	#query Semester I Exam
	$qtest2 = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$RegNo' AND ExamCategory=4";
	$dbtest2=mysql_query($qtest2);
	$total_test2 = mysql_num_rows($dbtest2);
	$row_test2=mysql_fetch_array($dbtest2);
	$value_test2score=$row_test2['ExamScore'];
	//die('inside'.$value_test2score);
	if(($total_test2>0)&&($value_test2score<>'')){
		$test2date=$row_test2['ExamDate'];
		$test2score=number_format($value_test2score,1);
	}else{
		$test2score='';
		$remarks = "Inc";
	}
    
    */
	// over look exam category but check semester
//die('year'.$ayear);
$sql = "select Semister_status from academicyear where AYear = 2011";

    $result = mysql_query($sql);
    while($row = mysql_fetch_array($result, MYSQL_ASSOC))
    {
        $semcheck= $row['Semister_status'];
        
    }     
    if($semcheck =='Semester I' )
    {
           #query End of Year Exam
            $qae = "SELECT ExamCategory, Examdate, ExamScore, AYear FROM examresult WHERE CourseCode='$course' AND RegNo='$RegNo'";
	    //
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
    
    }
    else
    {
    
            #query End of Year Exam
		//die('here'.$currentyear);
            $qae = "SELECT ExamCategory, Examdate, ExamScore, AYear FROM examresult WHERE CourseCode='$course' AND RegNo='$RegNo' AND ExamCategory=5 AND AYear=$currentyear";
	    //die($qae);
            $dbae=mysql_query($qae);
            $total_ae = mysql_num_rows($dbae);
            $row_ae=mysql_fetch_array($dbae);
            $value_aescore=$row_ae['ExamScore'];
		//die( 'in here man'.$value_aescore);
            if(($total_ae>0)&&($value_aescore<>'')){
                $aedate=$row_ae['ExamDate'];
                $aescore=number_format($value_aescore,1);
		
		//die($aescore);
            }else{
                $remarks = "Inc";
                $aescore='';
            }
	
    }
	#query Special Exam
	$qsp = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$RegNo' AND ExamCategory=7";
	//die($qsp);
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
	
	//die('here2'.$aescore);
	
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
		$cascore ='n/a';
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
		$cascore ='n/a';
	}
	
	#query Practical Training Exam
	$qpt = "SELECT ExamCategory, Examdate, ExamScore FROM examresult WHERE CourseCode='$course' AND RegNo='$RegNo' AND ExamCategory=9";
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
		$cascore ='n/a';
	}
	
	#get total marks
	if (($row_sup_total>0)&&($supscore<>'')){
				$tmarks = $supscore;
				if($tmarks>=50){
					$gradesupp='C';
					$remark = 'PASS';
					$tmarks = 50;
				}
	}elseif(($row_pro_total>0)&&($proscore<>'')){
		$tmarks = $proscore;
	}elseif(($row_pt_total>0)&&($ptscore<>'')){
		$tmarks = $ptscore;
	}elseif(($total_sp>0)&&($spscore<>'')){
		$tmarks = $test2score + $spscore;
	}else{
		$tmarks = $test2score + $aescore;
	}
	
	#format total marks
	$marks = number_format($tmarks,1);
	
	#grade marks
	if($remarks =='Inc'){
	$grade='I';
	$remark = 'Inc.';
	$point=0;
	$sgp=$point*$unit;
	}else{
		if($marks>=74.5){
			$grade='A';
			$remark = 'PASS';
			$margin = 75-$marks;
			$point=5;
			$sgp=$point*$unit;
			$totalsgp=$totalsgp+$sgp;
			$unittaken=$unittaken+$unit;
			if (($margin<=0.5)&&($margin>0)){
				$marks=75;
			}
		}elseif($marks>=64.5){
			$grade='B';
			$remark = 'PASS';
			$margin = 65-$marks;
			$point=4;
			$sgp=$point*$unit;
			$totalsgp=$totalsgp+$sgp;
			$unittaken=$unittaken+$unit;
			if (($margin<=0.5)&&($margin>0)){
				$marks=65;
			}
		}elseif($marks>=49.5){
			$grade='C';
			$remark = 'PASS';
			$margin = 50-$marks;
			$point=2;
			$sgp=$point*$unit;
			$totalsgp=$totalsgp+$sgp;
			$unittaken=$unittaken+$unit;
			if (($margin<=0.5)&&($margin>0)){
				$marks=50;
			}
		}else{
			$grade='F';
			$remark = 'FAILURE';
			$supp='!';
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
		$unittaken=$unittaken-$unit;
		$totalsgp=$totalsgp-$sgp;
		$sgp =0;
		$unit=0;
		$cname ='*'.$cname;
	}
	
	
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
?>