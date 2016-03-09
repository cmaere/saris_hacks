<?php
/*
#convert data
$qresults = "SELECT * FROM examresult";
$dbresult = mysql_query($qresults);
while($row_reults = mysql_fetch_assoc($dbresult)){
	$regno = $row_reults['RegNo'];
	$examno = $row_reults['ExamNo'];
	$coursecode = $row_reults['CourseCode'];
	$coursework = $row_reults['Coursework'];
	$exam = $row_reults['Exam'];
	$ayear = $row_reults['AYear'];
	$checked = $row_reults['checked'];
	$user = $row_reults['user'];
	$semester = $row_reults['SemesterID'];
	$count = $row_reults['Count'];
	$status = $row_reults['Status'];
	$comment = $row_reults['Comment'];
	$recorddate = $row_reults['RecordDate'];
	
	#insert Exam Marks into SUZA table
	$qinsertexam = "INSERT INTO examresultsuza SET 
							AYear = '$ayear',
							Semester ='$semester',
							Marker = '',
							CourseCode = '$coursecode',
							ExamCategory = '5',
							ExamDate = '',
							ExamSitting = '1',
							Recorder = '$user',
							RecordDate = '$recorddate',
							RegNo = '$regno',
							ExamScore = '$exam',
							Checked = '$checked',
							Status = '$status',
							Count = '$count',
							Comment = '$comment'
							";
	$dbinsertexam = mysql_query($qinsertexam);
	
	#insert Coursework Marks into SUZA table
	$qinsertexam = "INSERT INTO examresultsuza SET 
							AYear = '$ayear',
							Semester ='$semester',
							Marker = '',
							CourseCode = '$coursecode',
							ExamCategory = '4',
							ExamDate = '',
							ExamSitting = '1',
							Recorder = '$user',
							RecordDate = '$recorddate',
							RegNo = '$regno',
							ExamScore = '$coursework',
							Checked = '$checked',
							Status = '$status',
							Count = '$count',
							Comment = '$comment'
							";
	$dbinsertexam = mysql_query($qinsertexam);
}
*/
#copy dus data
$qdusstudent = "select * from student";
$dbdusstudent = mysql_query($qdusstudent);
While ($row_dusstudent = mysql_fetch_assoc($dbdusstudent))
{
	$regno = $row_dusstudent['RegNo'];
	$name = $row_dusstudent['Name'];
	$sex = $row_dusstudent['Sex'];
	$manner = $row_dusstudent['MannerofEntry'];
	$marital = $row_dusstudent['MaritalStatus'];
	$ayear = $row_dusstudent['EntryYear'];
	$prog = $row_dusstudent['ProgrammeofStudy'];
	echo $regno;
	
	#find programmename
	$qdegree = "select * from programme where ProgrammeID ='$prog'";
	$dbdegree = mysql_query($qdegree);
	$row_degree = mysql_fetch_assoc($dbdegree);
	$degree = $row_degree ['ProgrammeName'];
	/*
	#insert student record
	$qinsertstd = "INSERT INTO allstudent SET 
					Name = '$name',
					Sex = '$sex',
					RegNo = '$regno',
					MannerofEntry = '$manner',
					MaritalStatus = '$marital',
					Programme = '$degree',
					EntryYear = '$ayear'
					";
	$dbinsertstd =mysql_query($qinsertstd);
	*/
	#query results
	$qresult = "select * from examresult where regno ='$regno'";
	$dbresult = mysql_query($qresult) or die(mysql_error());
	$dbcount = mysql_query($qresult) or die(mysql_error());
	$count = mysql_num_rows($dbcount) or die(mysql_error());
	if ($count > 0)
	{
		#insert student record
		$qinsertstd = "INSERT INTO allstudents SET 
						Name = '$name',
						RegNo = '$regno',
						Sex = '$sex',
						MannerofEntry = '$manner',
						MaritalStatus = '$marital',
						Programme = '$degree',
						EntryYear = '$ayear'
						";
		$dbinsertstd =mysql_query($qinsertstd);
		
		#insert results
		while ($row_result = mysql_fetch_assoc($dbresult))
		{
			$coursecode = $row_result ['CourseCode'];
			$examno = $row_result ['ExamNo'];
			$grade = $row_result ['Grade'];
			$year = $row_result ['AYear'];
			$status = $row_result ['Status'];
			
			#insert examrecode record
			$qinsertexam = "INSERT INTO allexamresults SET 
							RegNo = '$regno',
							ExamNo = '$examno',
							CourseCode = 'coursecode',
							Grade= '$grade',
							Status = '$marital',
							AYear = '$year'
							";
			$dbinsertexam =mysql_query($qinsertstd);
		}
	}
}
						
		
echo 'Data Migrated Successfull';
?>