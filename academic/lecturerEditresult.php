<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Examination';
	$szSubSection = 'Search';
	$szTitle = 'Examination Result';
	include('lecturerheader.php');
	
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	$ExamNo = $_POST['ExamNo'];
	$coursecode = $_POST['coursecode'];
	$RegNo = $_POST['RegNo'];
	$cwk = $_POST['cwk'];
	$exam = $_POST['exam'];
	$total = $_POST['total'];
	$grade = $_POST['grade'];
	$remark = $_POST['remark'];
	@$checkbox = $_POST['checked'];
	@$core=$_POST['core'];
	$comment = $_POST['comment'];
		
	for($c = 0; $c < sizeof($ExamNo); $c++) {
			//check if student is blocked
			if (@$checkbox[$c]=='on'){
				$checked[$c] = 1;
			}else{
				$checked[$c] = 0;
				}
			
			//claculate Total Marks
			$total[$c] = $cwk[$c] + $exam[$c];
			
			if($exam[$c]==''){
				$remark[$c]='I';
			}elseif ($cwk[$c]==''){
				$remark[$c]='I';
			}else{
			if ($total[$c] >= 70){
				$grade[$c] = 'A';
				$remark[$c]= 'Pass';
				}
			elseif ($total[$c] >= 60){
				$grade[$c] = 'B+';
				$remark[$c]= 'Pass';
				}
			elseif ($total[$c] >= 50){
				$grade[$c] = 'B';
				$remark[$c]= 'Pass';
				}
			elseif ($total[$c] >= 40){
				$grade[$c] = 'C';
				$remark[$c]= 'Pass';
				}
			elseif ($total[$c] >= 35){
				$grade[$c] = 'D';
				$remark[$c]= 'Fail';
				}
			else{
				$grade[$c] = 'E';
				$remark[$c]= 'Fail';
				}
			}
			$updateSQL = "UPDATE examresult SET ExamNo='$ExamNo[$c]', Coursework = '$cwk[$c]', Exam = '$exam[$c]', 
			Total ='$total[$c]', Grade = '$grade[$c]', Remarks = '$remark[$c]', Status = '$core[$c]', Comment = '$comment[$c]' 
			WHERE RegNo='$RegNo[$c]' AND CourseCode='$coursecode'";
            //mysql_select_db($database_zalongwa, $zalongwa);
  			$Result1 = mysql_query($updateSQL) or die(mysql_error()); 
			}
			
}	
mysql_select_db($database_zalongwa, $zalongwa);
if (isset($_GET['Candidate'])) {
  $cand=$_GET['Candidate'];
  $query_addexam = "SELECT student.Name,        course.CourseCode,        course.CourseName,        examresult.RegNo,       
 examresult.ExamNo,        examresult.CourseCode,        examresult.Coursework,        examresult.Exam,       
  examresult.Total,        examresult.Grade,        examresult.Remarks,        examresult.AYear,        
  examresult.checked,        examresult.user,        examresult.SemesterID, examresult.Status, examresult.Comment  
  FROM examresult    INNER JOIN course ON (examresult.CourseCode = course.CourseCode)    
  INNER JOIN student ON (examresult.RegNo = student.RegNo) 
  WHERE (student.Name Like '%$cand%') OR  (examresult.RegNo Like '%$cand%') ORDER BY course.CourseCode ASC";
}else{
$query_addexam = "SELECT student.Name,        course.CourseCode,        course.CourseName,        examresult.RegNo,       
 examresult.ExamNo,        examresult.CourseCode,        examresult.Coursework,        examresult.Exam,       
  examresult.Total,        examresult.Grade,        examresult.Remarks,        examresult.AYear,        
  examresult.checked,        examresult.user,        examresult.SemesterID, examresult.Status, examresult.Comment  
  FROM examresult    INNER JOIN course ON (examresult.CourseCode = course.CourseCode)    
  INNER JOIN student ON (examresult.RegNo = student.RegNo) 
  WHERE (examresult.CourseCode='$key') AND  (examresult.AYear='$ayear') AND (examresult.SemesterID = '$sem')ORDER BY examresult.RegNo ASC";
}
$addexam = mysql_query($query_addexam, $zalongwa) or die(mysql_error());
$row_addexam = mysql_fetch_assoc($addexam);
$totalRows_addexam = mysql_num_rows($addexam);
 
  ?>
 <form name="form1" method="GET" action="lecturerGradebookAdd.php">
              Search Candidate:
                <input name="Candidate" type="text" id="Candidate" maxlength="50">
              <input type="submit" name="Submit" value="Search">
       </form>
	   
  <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1">
            <p align="center"><span class="style64">UNIVERSITY OF DAR ES SALAAM</span><br>
                <span class="style71"> <span class="style67">EXAMINATION RESULTS FOR <?php echo $row_addexam['SemesterID']; ?> , <?php echo $row_addexam['AYear']; ?></span><br>
              </span></p>
            <p align="center"><span class="style71">
            <input name="coursecode" type="hidden" id="coursecode" value="<?php echo $row_addexam['CourseCode']; ?>">
            <?php echo $row_addexam['CourseCode']; ?></span>: <?php echo $row_addexam['CourseName']; ?></p>
            <p>  
	 
			
			<?php 
			if ($privilege == '2') {
			?>   <table width="100%" border="1" align="center" bordercolor="#CCCCCC">
              <tr bgcolor="#CCCCCC">
                <td><strong>S/No</strong></td>
                <td><strong>CourseCode</strong></td>
                <td><strong>Name</strong></td>
                <td><strong>RegNo</strong></td>
                <td><strong>ExamNo</strong></td>
                <td><strong>Coursework</strong></td>
                <td><strong>Exam</strong></td>
                <td><strong>Total</strong></td>
                <td><strong>Grade</strong></td>
                <td><strong>Remarks</strong></td>
				  <td><strong>Core/Option</strong></td>
				  <td><strong>Comments</strong></td>
				  <td><strong>Drop</strong></td>
              </tr>
              <?php $i=1;do { ?>
              <tr bgcolor="#CCCCCC">
                <td align="left" valign="middle"><div align="left"> <?php echo $i; ?> </div></td>
                <td align="left" valign="middle" nowrap><?php echo $row_addexam['CourseCode']; ?></td>
                <td align="left" valign="middle" nowrap><?php echo $row_addexam['Name']; ?></td>
                <td align="left" valign="middle"><input name="RegNo[]" type="hidden" id="RegNo[]" value="<?php echo $row_addexam['RegNo']; ?>">
                <?php $regno=$row_addexam['RegNo']; echo $row_addexam['RegNo']; ?></td>
                <td>
                <input name="ExamNo[]" type="text" id="ExamNo[]" value="<?php echo $row_addexam['ExamNo']; ?>" size="10"></td>
                <td>
                <input name="cwk[]" type="text" id="cwk[]" value="<?php echo $row_addexam['Coursework']; ?>" size="10"></td>
                <td>
                <input name="exam[]" type="text" id="exam[]" value="<?php echo $row_addexam['Exam']; ?>" size="4"></td>
                <td><?php echo $row_addexam['Total']; ?>
                <input name="total[]" type="hidden" id="total[]"></td>
                <td><?php echo $row_addexam['Grade']; ?>
                <input name="grade[]" type="hidden" id="grade[]"></td>
               <td><?php echo $row_addexam['Remarks']; ?>
                <input name="remark[]" type="hidden" id="remark[]"></td>
               <td><select name="core[]" id="core[]">
				  <option value="Core" >Core Course</option>
                  <option value="Option">Option Course</option>
                </select></td>
			    <td><input name="comment[]" type="text" id="comment[]" value="<?php echo $row_addexam['Comment']; ?>"></td>
                <td><?php print "<a href=\"lecturerexalresultdelete.php?RegNo=$regno&ayear=$ayear&key=$key\">Drop</a>";?></td>
              </tr>
              <?php $i=$i+1;} while ($row_addexam = mysql_fetch_assoc($addexam)); ?>
            </table>
		
		<?php }else{?>
			
            <table width="100%" border="1" align="center">
              <tr bgcolor="#CCCCCC">
                <td><strong>S/No</strong></td>
                <td><strong>Name</strong></td>
                <td><strong>RegNo</strong></td>
                <td><strong>ExamNo</strong></td>
                <td><strong>Coursework</strong></td>
                <td><strong>Exam</strong></td>
                <td><strong>Total</strong></td>
                <td><strong>Grade</strong></td>
                <td><strong>Remarks</strong></td>
				 <td><strong>Comments</strong></td>
				
              </tr>
              <?php $i=1;do { ?>
              <tr bgcolor="#CCCCCC">
                <td align="left" valign="middle"><div align="left"> <?php echo $i; ?> </div></td>
                <td align="left" valign="middle" nowrap><?php echo $row_addexam['Name']; ?></td>
                <td align="left" valign="middle"><input name="RegNo[]" type="hidden" id="RegNo[]" value="<?php echo $row_addexam['RegNo']; ?>">
                    <?php $regno=$row_addexam['RegNo']; echo $row_addexam['RegNo']; ?></td>
                <td>                <input name="ExamNo[]" type="text" id="ExamNo[]" value="<?php echo $row_addexam['ExamNo']; ?>" size="10"></td>
                <td>
                  <input name="cwk[]" type="text" id="cwk[]" value="<?php echo $row_addexam['Coursework']; ?>" size="10"></td>
                <td>
                  <input name="exam[]" type="text" id="exam[]" value="<?php echo $row_addexam['Exam']; ?>" size="4"></td>
                <td><?php echo $row_addexam['Total']; ?>
                    <input name="total[]" type="hidden" id="total[]"></td>
                <td><?php echo $row_addexam['Grade']; ?>
                    <input name="grade[]" type="hidden" id="grade[]"></td>
                 <td><?php echo $row_addexam['Remarks']; ?>
                    <input name="remark[]" type="hidden" id="remark[]"></td>
				 <td><input name="comment[]" type="text" id="comment[]" value="<?php echo $row_addexam['Comment']; ?>"></td>
   
              </tr>
              <?php $i=$i+1;} while ($row_addexam = mysql_fetch_assoc($addexam)); ?>
    </table> 
            <?php } ?>
            <p>
              <input type="submit" name="Submit" value="Update Records">
              <input type="hidden" name="MM_update" value="form1">
            </p>
</form>
