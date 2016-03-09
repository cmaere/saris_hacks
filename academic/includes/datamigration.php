<?php
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
echo 'Data Migrated Successfull';
?>