<script language="JavaScript">
function msg(course,examcat,examdate,exammarker,ayear)
{

alert('PLEASE CLICK UPDATE BEFORE SUBMITTING');
self.location='lecturerGradebookAdd.php?course=' + course +'&examcat=' + examcat +'&examdate=' + examdate +'&exammarker=' + exammarker +'&ayear=' + ayear +'&clicked=1';

}
</script>

<?php 

    
    //end modification
 
    function cha_substate($name, $ayear, $score, $code,$examcat)
    {
    
                
                
               //die("am here now");
                //cha modification
                $sql_lectsubstatus = "select substatus from submitresult where Lecturer_Name = '$name' and acYear = '$ayear' and courseCode = '$code' and category = '$examcat'";
                
                //die($sql_lectsubstatus);
				$qsubstatus=mysql_query($sql_lectsubstatus) or die('cha q Problem');
				$total_row = mysql_num_rows($qsubstatus);
                //die("cha===".$total_row);
				if($total_row > 0){
                ?>
                 <td>
                <?php echo $score; ?></td>
                <?php
                
                }
                else
                {
					
                ?>
                    <td>
                <input name='cwk[]' type='text' id='cwk[]' value='<?php echo $score; ?>' size='3'></td>
                
                <?php
                
                }
                
                

    
    
    
    
    
    }
    
    
    //end modification

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}



#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
    //die(session_id());
	  
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Examination';
	$szSubSection = 'Grade Book';
	$szTitle = 'Examination Grade Book';
	include('lecturerheader.php');
	
	
	if (isset($_POST['update_records'])) 
 {
	//save contents to database
	$sessionid = session_id();
	$sqlsession = "UPDATE session SET sessionid='$sessionid',update1=1";
	//die($sqlsession);
	mysql_query($sqlsession);
	
		$key=addslashes($_POST['coursecode']);
		$ayear=addslashes($_POST['ayear']);
		$RegNo = $_POST['RegNo'];
		$cwk = $_POST['cwk'];
		$examcat = addslashes($_POST['examcat']);
		$examdate = addslashes($_POST['examdate']);
		$exammarker = addslashes($_POST['exammarker']);
		$remark = $_POST['sitting'];
		//$core=$_POST['core'];
		$comment = $_POST['comment'];
		$max = sizeof($RegNo);
		$_SESSION['max']=$max;
		
		#start for loop to treat each candidate
		for($c = 0; $c < $max; $c++) 
		{
			$score1 = $cwk[$c];
			$score2 = floatval($cwk[$c]);
		
				//cha edits 
				
				//UPDATE examresult set AYear = '$ayear', Marker = '$exammarker', CourseCode = '$key', ExamCategory = '$examcat', ExamDate =, ExamSitting, Recorder, RecordDate, RegNo, ExamScore, Status, Comment
				$curdate = date(d.'-'.m.'-'.Y);
		
					$presql = "select RegNo from examresult WHERE RegNo = '$RegNo[$c]' AND AYear ='$ayear' AND CourseCode = '$key' AND ExamCategory = '$examcat'";
					//die($presql);
					$result_presql=mysql_query($presql);
					$presqlrows= mysql_num_rows($result_presql);
					
					if($presqlrows == 0)
					{
					
						$updateSQL = "INSERT INTO examresult(AYear, Marker, CourseCode, ExamCategory, ExamDate, ExamSitting, Recorder, RecordDate, RegNo, ExamScore, Status, Comment)
							 VALUES ('$ayear', '$exammarker', '$key', '$examcat', '$examdate', '$remark[$c]', '$username', now(), '$RegNo[$c]', '$cwk[$c]', '1', '$comment[$c]')";
					//die($updateSQL);
						mysql_query($updateSQL);
					}
					else
					{
					
		
						$updateSQL = "UPDATE examresult SET AYear ='$ayear', Marker = '$exammarker', CourseCode = '$key', ExamCategory = '$examcat', ExamDate = '$examdate', ExamSitting = '$remark[$c]', Recorder = '$username', RecordDate = '$curdate', RegNo = '$RegNo[$c]', ExamScore = '$cwk[$c]', Status = '1', Comment = '$comment[$c]'
						WHERE RegNo = '$RegNo[$c]' AND AYear ='$ayear' AND CourseCode = '$key' AND ExamCategory = '$examcat'";
							//to insert score validations later in future
							
					  //      die($updateSQL);
							mysql_query($updateSQL);
					 }
	
	
	
			}//close for loop
					#session error
					$_SESSION['err']=$err;
					
					
			  echo "<br>Database updated successfully";
				#open data entry form again
			   echo "<meta http-equiv = 'refresh' content ='0; 
		url = lecturerGradebookAdd.php?course=".$key."&examcat=".$examcat."&examdate=".$examdate."&exammarker=".$exammarker."&ayear=".$ayear."&clicked=1'>";      
					exit;
	
				
}
else if(isset($_POST['view']) && $_POST['view'] == "Edit Records" || $_GET['clicked'] == 1)
{
	
	
        if($_GET['clicked'] == 1)
        {
            $key=addslashes($_GET['course']);
            $ayear=addslashes($_GET['ayear']);
            $examcat=addslashes($_GET['examcat']);
            $exammarker=addslashes($_GET['exammarker']);
            $examdate=addslashes($_GET['examdate']);

        
        }
        else
        {
            $key=addslashes($_POST['course']);
            $ayear=addslashes($_POST['ayear']);
            $examcat=addslashes($_POST['examcat']);
            $exammarker=addslashes($_POST['exammarker']);
            $examdate=addslashes($_POST['examdate']);
        }
        
 
if($examcat ==0)
{

    echo "<font color='red'>MAKE SURE EXAM CATEGORY IS SELECTED</font><br> <a href='lecturerGradebook.php?coursecode=".$key."&examdate=".$examdate."'>RETURN</a>";
exit;


}

$sqlcheck = "select substatus from submitresult where courseCode ='$key' and acYear=$ayear and category='$examcat'";
$result_check=mysql_query($sqlcheck);
$sqlcheckrows= mysql_num_rows($result_check);


$query_addexam_edit = "
                SELECT DISTINCT student.Name,
								course.CourseCode,
								course.CourseName, 
								examresult.ExamSitting,
								examresult.CourseCode,
								examresult.ExamScore,
								examresult.ExamCategory,
								examresult.ExamDate,
								examresult.Status,
								examresult.RegNo,
								examresult.Comment,
								examresult.Checked,
								programme.ProgrammeName
			   FROM programme, course
					   INNER JOIN examresult ON (course.CourseCode = examresult.CourseCode)
					   INNER JOIN student ON (examresult.RegNo = student.RegNo)
							 WHERE ( 
									  (examresult.CourseCode='$key') 
									 AND  
									  (examresult.AYear='$ayear') 
									 AND 
									  (examresult.ExamCategory = '$examcat')
									  AND 
									  (student.ProgrammeofStudy = programme.ProgrammeCode)
								   )
							ORDER BY student.Name, programme.ProgrammeName, examresult.RegNo ASC";

$query_addexam_add = "
                SELECT DISTINCT student.Name,
				                course.CourseCode,
								course.CourseName, 
								examregister.CourseCode,
								examregister.RegNo,
								examregister.Checked,
								programme.ProgrammeName
			   FROM programme, course
					   INNER JOIN examregister ON (course.CourseCode = examregister.CourseCode)
					   INNER JOIN student ON (examregister.RegNo = student.RegNo)
							 WHERE ( 
									  (examregister.CourseCode='$key') 
									 AND  
									  (examregister.AYear='$ayear') 
									  AND 
									  (student.ProgrammeofStudy = programme.ProgrammeCode)
								   )
							ORDER BY student.Name, programme.ProgrammeName, examregister.RegNo ASC";
							
							$addexam = mysql_query($query_addexam_add, $zalongwa) or die('Problem: Check the Add Query!3cha');
							$row_addexam = mysql_fetch_array($addexam);
							#get course code and course title
							$qcourse = "SELECT CourseCode, CourseName from course WHERE CourseCode = '$key'";
							$dbcourse = mysql_query($qcourse);
							$row_course = mysql_fetch_assoc($dbcourse);
							$coursecode = $row_course['CourseCode'];
							$coursename = $row_course['CourseName'];
							#display form for updating records
}		
	



?>

<form action="lecturerGradebookAdd2014.php" method="post">
<span class="style71"> <span class="style67">EXAMINATION RESULTS  BLACKSHEET FOR, <?php echo $ayear ?></span><br>
 <input name="coursecode" type="hidden" id="coursecode" value="<?php echo $coursecode //$row_addexam['CourseCode']; ?>">
			<input name="exammarker" type="hidden" id="exammarker" value="<?php echo $exammarker; ?>">
			<input name="examcat" type="hidden" id="examcat" value="<?php echo $examcat; ?>">
			<input name="examdate" type="hidden" id="examdate" value="<?php echo $examdate ?>">
			<input name="ayear" type="hidden" id="ayear" value="<?php echo $ayear ?>">
            <input name="lectname" type="hidden" id="lectname" value="<?php echo $name ?>">
			<?php
				#display Exam Category
					$qcat="select Id,Description from examcategory where Id='$examcat'";
					$dbcat=mysql_query($qcat);
					$row_cat=mysql_fetch_array($dbcat);
				#display Exam Marker
					$qmaker="select * from exammarker where Id='$exammarker'";
					$dbmarker=mysql_query($qmaker);
					$row_marker=mysql_fetch_array($dbmarker);
				?>
            <?php // echo "<b> Course: </b>".$row_addexam['CourseCode']; </span>: ?><?php //echo $row_addexam['CourseName']."<br>"; ?>
            <?php echo "<b> Course: </b>".$coursecode; ?></span>: <?php echo $coursename."<br>"; ?>
			<?php echo "<b> Category: </b>".$row_cat['Description']."<br>"; ?>
			<?php echo "<b> Exam Date: </b>".$examdate."<br>"; ?>
			<?php echo "<b> Exam Marker: </b>".$row_marker['Name']."<br>"; ?>
            	
               <!--- edit form 2014 --->
               
                <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" >
              <tr>
                <td width="4%"><strong>S/No</strong></td>
				<td width="30%" nowrap><strong>Degree Course</strong></td>
                <td width="13%"><strong>Name</strong></td>
                <td width="16%"><strong>RegNo</strong></td>
                <td width="4%"><strong>Score</strong></td>
                <td width="6%"><strong>Sitting </strong></td>
              </tr>
              
    			
                <?php $i=1;
			  $addexam_add = mysql_query($query_addexam_add, $zalongwa) or die('Problem: Check the Add Query44!');
			  while ($row_addexam = mysql_fetch_assoc($addexam_add)){ 
			    $currentreg = $row_addexam['RegNo'];
				$checked = $row_addexam['Checked'];
			    $currentcourse = $row_addexam['CourseCode'];

			  //check for duplicates
				$qduplicate="SELECT RegNo FROM examresult WHERE RegNo='$currentreg' AND ExamCategory='$examcat' AND CourseCode='$currentcourse' AND AYear='$ayear'";
				$dbduplicate=mysql_query($qduplicate) or die('Problem');
				$total_row = mysql_num_rows($dbduplicate);
				if(($total_row < 1) && ($checked==0)){
			  ?>
              <tr>
                <td align="left" valign="middle"><div align="left"> <?php echo $i; ?> </div></td>
				<td align="left" valign="middle" nowrap><?php echo $row_addexam['ProgrammeName']; ?></td>
                <td align="left" valign="middle" nowrap><?php echo $row_addexam['Name']; ?></td>
                <td align="left" valign="middle"><input name="RegNo[]" type="hidden" id="RegNo[]" value="<?php echo $row_addexam['RegNo']; ?>">
                <?php $regno=$row_addexam['RegNo']; echo $row_addexam['RegNo']; ?></td>
                
                <?php
                $score = $row_addexam['ExamScore'];

               cha_substate($name, $ayear, $score,$currentcourse,$examcat );
                ?>
                <td></td>
               
               
				
                <td><select name="comment[]" id="comment[]" >
                  <?php
				#populate stitting combo box
					$sit=$row_addexam['Comment'];
					$qsitting="select Id,Description from sitting where Id='$sit'";
					$dbsitting=mysql_query($qsitting);
					$row_sitting=mysql_fetch_array($dbsitting);
					if($sit==''){
				?>
				<option value="1">First</option>
				<?php }else{ ?>
                  <option value="<?php echo $row_sitting['Id'] ?>"><?php echo $row_sitting['Description'] ?></option>
				  <?php } ?>
                  <option value="1">First</option>
				  <option value="4">Repeater</option>
                  <option value="2">Supp</option>
				  <option value="3">Special</option>
                </select></td>
              </tr>
              <?php $i=$i+1;
			   }
			  } #ends while add row exam
			  
			  ?>
                
            
            
               
               
               <!-- end edit form 2014 -->
			
           
						
<tr><td>        
						
<input type="submit" value="Update records" name="update_records">
<td>
<input type="submit" value="Submit Results to HOD >>" name="SUB_Results">
</tr>
 </table>
</form>