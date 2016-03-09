<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Examination';
	$szSubSection = 'Certificate';
	$szTitle = 'Student Certificate';
	include('lecturerheader.php');
$editFormAction = $_SERVER['PHP_SELF'];

mysql_select_db($database_zalongwa, $zalongwa);
$query_studentlist = "SELECT RegNo, Name, ProgrammeofStudy FROM student ORDER BY ProgrammeofStudy  ASC";
$studentlist = mysql_query($query_studentlist, $zalongwa) or die(mysql_error());
$row_studentlist = mysql_fetch_assoc($studentlist);
$totalRows_studentlist = mysql_num_rows($studentlist);

mysql_select_db($database_zalongwa, $zalongwa);
$query_degree = "SELECT ProgrammeName FROM programme ORDER BY ProgrammeName ASC";
$degree = mysql_query($query_degree, $zalongwa) or die(mysql_error());
$row_degree = mysql_fetch_assoc($degree);
$totalRows_degree = mysql_num_rows($degree);

mysql_select_db($database_zalongwa, $zalongwa);
$query_ayear = "SELECT AYear FROM academicyear ORDER BY AYear DESC";
$ayear = mysql_query($query_ayear, $zalongwa) or die(mysql_error());
$row_ayear = mysql_fetch_assoc($ayear);
$totalRows_ayear = mysql_num_rows($ayear);

mysql_select_db($database_zalongwa, $zalongwa);
$query_dept = "SELECT Faculty, DeptName FROM department ORDER BY DeptName, Faculty ASC";
$dept = mysql_query($query_dept, $zalongwa) or die(mysql_error());
$row_dept = mysql_fetch_assoc($dept);
$totalRows_dept = mysql_num_rows($dept);
?>
<?php
			
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

?>
<h2 align="center">UNIVERSITY OF DAR ES SALAAM<br>
					  
Examination Results Trascripts <br>
</h2>
<?php 
//$reg = $_POST['regno'];
@$checkdegree = $_POST['checkdegree'];
@$checkyear = $_POST['checkyear'];
@$checkdept = $_POST['checkdept'];

$c=0;

if (($checkdegree=='on') && ($checkyear == 'on') && ($checkdept == 'on')){
$reg = $_POST['regno'];
$deg=$_POST['degree'];
$year = $_POST['ayear'];
$dept = $_POST['dept'];

	for(;;){
		if($c > sizeof($reg)){
				break;
			}
			@$updateSQL = "SELECT student.Name,
										   student.ProgrammeofStudy,
										   examresult.RegNo,
										   examresult.ExamNo,
										   examresult.CourseCode,
										   course.Units,
										   examresult.Grade,
										   examresult.AYear,
										   examresult.Remarks,
										   examresult.Status,
										   examresult.SemesterID,
										   examresult.Comment,
										   course.Department
							FROM course
							   INNER JOIN examresult ON (course.CourseCode = examresult.CourseCode)
							   INNER JOIN student ON (examresult.RegNo = student.RegNo)
							WHERE 
										   (
											  (student.ProgrammeofStudy = '$deg')
										   and 
											  (examresult.RegNo = '$reg[$c]')
										   and 
											  (course.Department= '$dept')
										   and 
											  (examresult.AYear = '$year')
										   )";
           	$result = mysql_query($updateSQL) or die("Mwanafunzi huyu hana matokeo"); 
			$query = @mysql_query($updateSQL) or die("Cannot query the database.<br>" . mysql_error());
			$row_result = mysql_fetch_array($result);
			$name = $row_result['Name'];
			$degree = $row_result['ProgrammeofStudy'];
			
			if (mysql_num_rows($query) > 0){
			
					$totalunit=0;
					$unittaken=0;
					$sgp=0;
					$totalsgp=0;
					$gpa=0;
					
			echo "$c". ".$name  $reg[$c] '$degree'";
			echo "<table border='1'>";
			echo "<tr><td> S/No </td><td>Year </td><td> Course</td><td> Unit </td><td> Grade </td><td> Remarks</td><td> Core/Option</td></tr>";
			$i=1;
				while($result = mysql_fetch_array($query)) {
					
					
					$unit = $result['Units'];
					$totalunit=$totalunit+$unit;
					
					$ayear = $result['AYear'];
					$remarks = $result['Remarks'];
					$status = $result['Status'];
					$grade = $result['Grade'];
					$comment = $result['Comment'];
					//$semester = $result['SemesterID'];
					
					
					if ($grade=='A'){
							$point=5;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B+'){
							$point=4;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B'){
							$point=3;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='C'){
							$point=2;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='D'){
							$point=1;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='E'){
							$point=0;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}else
							$sgp='';
							
					$coursecode = $result['CourseCode'];
					//$totalRows_result = mysql_num_rows($result);
					
								echo "<tr><td>$i</td>";
								echo "<td>$ayear</td>";
								echo "<td>$coursecode</td>";
								echo "<td>$unit</td>";
								echo "<td>$grade</td>";
								echo "<td>$remarks</td>";
								echo "<td>$status</td></tr>";
							$i=$i+1;
						}
						echo "</table>";
						echo "Total Units =". $totalunit.";   Units Completed = ".$unittaken.  ";   Total Points = ".$totalsgp. ";   GPA = ".@substr($totalsgp/$unittaken, 0,3)."<br><hr>";
					}else{ 
					if(!@$reg[$c]){}
						}
					//mysql_close($zalongwa);
					//mysql_free_result($result);
					$c++;
			 }
		
}elseif (($checkyear == 'on') && ($checkdept == 'on')){	
$reg = $_POST['regno'];
$year = $_POST['ayear'];
$dept = $_POST['dept'];
	for(;;){
		if($c > sizeof($reg)){
				break;
			}
			@$updateSQL = "SELECT student.Name,
										   student.ProgrammeofStudy,
										   examresult.RegNo,
										   examresult.ExamNo,
										   examresult.CourseCode,
										   course.Units,
										   examresult.Grade,
										   examresult.AYear,
										   examresult.Remarks,
										   examresult.Status,
										   examresult.SemesterID,
										   examresult.Comment,
										   course.Department
							FROM course
							   INNER JOIN examresult ON (course.CourseCode = examresult.CourseCode)
							   INNER JOIN student ON (examresult.RegNo = student.RegNo)
							WHERE 
										   (
											  (examresult.RegNo = '$reg[$c]')
										   and 
											  (course.Department= '$dept')
										   and 
											  (examresult.AYear = '$year')
										   )";
           	$result = mysql_query($updateSQL) or die("Mwanafunzi huyu hana matokeo"); 
			$query = @mysql_query($updateSQL) or die("Cannot query the database.<br>" . mysql_error());
			$row_result = mysql_fetch_array($result);
			$name = $row_result['Name'];
			$degree = $row_result['ProgrammeofStudy'];
			
			if (mysql_num_rows($query) > 0){
			
					$totalunit=0;
					$unittaken=0;
					$sgp=0;
					$totalsgp=0;
					$gpa=0;
					
			echo "$c". ".$name  $reg[$c] '$degree'";
			echo "<table border='1'>";
			echo "<tr><td> S/No </td><td>Year </td><td> Course</td><td> Unit </td><td> Grade </td><td> Remarks</td><td> Core/Option</td></tr>";
			$i=1;
				while($result = mysql_fetch_array($query)) {
					
					
					$unit = $result['Units'];
					$totalunit=$totalunit+$unit;
					
					$ayear = $result['AYear'];
					$remarks = $result['Remarks'];
					$status = $result['Status'];
					$grade = $result['Grade'];
					$comment = $result['Comment'];
					//$semester = $result['SemesterID'];
					
					
					if ($grade=='A'){
							$point=5;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B+'){
							$point=4;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B'){
							$point=3;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='C'){
							$point=2;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='D'){
							$point=1;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='E'){
							$point=0;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}else
							$sgp='';
							
					$coursecode = $result['CourseCode'];
					//$totalRows_result = mysql_num_rows($result);
					
								echo "<tr><td>$i</td>";
								echo "<td>$ayear</td>";
								
								echo "<td>$coursecode</td>";
								echo "<td>$unit</td>";
								echo "<td>$grade</td>";
								echo "<td>$remarks</td>";
								echo "<td>$status</td></tr>";
							$i=$i+1;
						}
						echo "</table>";
						echo "Total Units =". $totalunit.";   Units Completed = ".$unittaken.  ";   Total Points = ".$totalsgp. ";   GPA = ".@substr($totalsgp/$unittaken, 0,3)."<br><hr>";
					}else{ 
					if(!@$reg[$c]){}
						}
					//mysql_close($zalongwa);
					//mysql_free_result($result);
					$c++;
			 }
}elseif ($checkdegree=='on'){
$reg = $_POST['regno'];
$deg=$_POST['degree'];

for(;;){
		if($c > sizeof($reg)){
				break;
			}
			@$updateSQL = "SELECT student.Name,
			student.ProgrammeofStudy,
       examresult.RegNo,
       examresult.ExamNo,
       examresult.CourseCode,
       course.Units,
       examresult.Grade,
       examresult.AYear,
       examresult.Remarks,
       examresult.Status,
	   examresult.SemesterID,
	   examresult.Comment
FROM examresult
   INNER JOIN course ON (examresult.CourseCode = course.CourseCode)
   INNER JOIN student ON (examresult.RegNo = student.RegNo)
				WHERE  student.ProgrammeofStudy = '$deg' AND examresult.RegNo = '$reg[$c]' ";
           	$result = mysql_query($updateSQL) or die("Mwanafunzi huyu hana matokeo"); 
			$query = @mysql_query($updateSQL) or die("Cannot query the database.<br>" . mysql_error());
			$row_result = mysql_fetch_array($result);
			$name = $row_result['Name'];
			$degree = $row_result['ProgrammeofStudy'];
			
			if (mysql_num_rows($query) > 0){
			
					$totalunit=0;
					$unittaken=0;
					$sgp=0;
					$totalsgp=0;
					$gpa=0;
					
			echo "$c". ".$name  $reg[$c] '$degree'";
			echo "<table border='1'>";
			echo "<tr><td> S/No </td><td>Year </td><td> Course</td><td> Unit </td><td> Grade </td><td> Remarks</td><td> Core/Option</td></tr>";
			$i=1;
				while($result = mysql_fetch_array($query)) {
					
					
					$unit = $result['Units'];
					$totalunit=$totalunit+$unit;
					
					$ayear = $result['AYear'];
					$remarks = $result['Remarks'];
					$status = $result['Status'];
					$grade = $result['Grade'];
					$comment = $result['Comment'];
					
					
					
					if ($grade=='A'){
							$point=5;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B+'){
							$point=4;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B'){
							$point=3;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='C'){
							$point=2;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='D'){
							$point=1;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='E'){
							$point=0;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}else
							$sgp='';
							
					$coursecode = $result['CourseCode'];
					//$totalRows_result = mysql_num_rows($result);
					
								echo "<tr><td>$i</td>";
								echo "<td>$ayear</td>";
								
								echo "<td>$coursecode</td>";
								echo "<td>$unit</td>";
								echo "<td>$grade</td>";
								echo "<td>$remarks</td>";
								echo "<td>$status</td></tr>";
							$i=$i+1;
						}
						echo "</table>";
						echo "Total Units =". $totalunit.";   Units Completed = ".$unittaken.  ";   Total Points = ".$totalsgp. ";   GPA = ".@substr($totalsgp/$unittaken, 0,3)."<br><hr>";
					}else{ 
					if(!@$reg[$c]){}
						}
					//mysql_close($zalongwa);
					//mysql_free_result($result);
					$c++;
			 }
		
}else{
$reg = $_POST['regno'];

for(;;){
		if($c > sizeof($reg)){
				break;
			}
			@$updateSQL = "SELECT student.Name,
			student.ProgrammeofStudy,
       examresult.RegNo,
       examresult.ExamNo,
       examresult.CourseCode,
       course.Units,
       examresult.Grade,
       examresult.AYear,
       examresult.Remarks,
       examresult.Status,
	   examresult.SemesterID,
	   examresult.Comment
FROM examresult
   INNER JOIN course ON (examresult.CourseCode = course.CourseCode)
   INNER JOIN student ON (examresult.RegNo = student.RegNo)
				WHERE  examresult.RegNo = '$reg[$c]' ";
           	$result = mysql_query($updateSQL) or die("Mwanafunzi huyu hana matokeo"); 
			$query = @mysql_query($updateSQL) or die("Cannot query the database.<br>" . mysql_error());
			$row_result = mysql_fetch_array($result);
			$name = $row_result['Name'];
			$degree = $row_result['ProgrammeofStudy'];
			
			if (mysql_num_rows($query) > 0){
			
					$totalunit=0;
					$unittaken=0;
					$sgp=0;
					$totalsgp=0;
					$gpa=0;
					
			echo "$c". ".$name  $reg[$c] '$degree'";
			echo "<table border='1'>";
			echo "<tr><td> S/No </td><td>Year </td><td> Course</td><td> Unit </td><td> Grade </td><td> Remarks</td><td> Core/Option</td></tr>";
			$i=1;
				while($result = mysql_fetch_array($query)) {
					
					
					$unit = $result['Units'];
					$totalunit=$totalunit+$unit;
					
					$ayear = $result['AYear'];
					$remarks = $result['Remarks'];
					$status = $result['Status'];
					$grade = $result['Grade'];
					$comment = $result['Comment'];
										
					
					if ($grade=='A'){
							$point=5;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B+'){
							$point=4;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='B'){
							$point=3;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='C'){
							$point=2;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='D'){
							$point=1;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}elseif($grade=='E'){
							$point=0;
							$sgp=$point*$unit;
							$totalsgp=$totalsgp+$sgp;
							$unittaken=$unittaken+$unit;
						}else
							$sgp='';
							
					$coursecode = $result['CourseCode'];
					//$totalRows_result = mysql_num_rows($result);
					
								echo "<tr><td>$i</td>";
								echo "<td>$ayear</td>";
								echo "<td>$coursecode</td>";
								echo "<td>$unit</td>";
								echo "<td>$grade</td>";
								echo "<td>$remarks</td>";
								echo "<td>$status</td></tr>";
							$i=$i+1;
						}
						echo "</table>";
						echo "Total Units =". $totalunit.";   Units Completed = ".$unittaken.  ";   Total Points = ".$totalsgp. ";   GPA = ".@substr($totalsgp/$unittaken, 0,3)."<br><hr>";
					}else{ 
					if(!@$reg[$c]){}
						}
					//mysql_close($zalongwa);
					//mysql_free_result($result);
					$c++;
			 }
		}			
}else{
?>

<form name="form1" method="post" action="<?php echo $editFormAction ?>">
            <div align="center">
			<table width="200" border="0">
                <tr>
                  <td colspan="3"><span class="style61">if you want to filter the results by  criteria <span class="style34">Tick the corresponding check box first</span> the select appropriately </span></td>
                </tr>
                <tr>
                  <td nowrap><input name="checkdegree" type="checkbox" id="checkdegree" value="on"></td>
                  <td nowrap><div align="left">Degree Programme:</div></td>
                  <td>
                      <div align="left">
                        <select name="degree" id="degree">
                          <?php
do {  
?>
                          <option value="<?php echo $row_degree['ProgrammeName']?>"><?php echo $row_degree['ProgrammeName']?></option>
                          <?php
} while ($row_degree = mysql_fetch_assoc($degree));
  $rows = mysql_num_rows($degree);
  if($rows > 0) {
      mysql_data_seek($degree, 0);
	  $row_degree = mysql_fetch_assoc($degree);
  }
?>
                        </select>
                    </div></td></tr>
                <tr>
                  <td><input name="checkyear" type="checkbox" id="checkyear" value="on"></td>
                  <td nowrap><div align="left">Results of the Year: </div></td>
                  <td><div align="left">
                    <select name="ayear" id="ayear">
                        <?php
do {  
?>
                        <option value="<?php echo $row_ayear['AYear']?>"><?php echo $row_ayear['AYear']?></option>
                        <?php
} while ($row_ayear = mysql_fetch_assoc($ayear));
  $rows = mysql_num_rows($ayear);
  if($rows > 0) {
      mysql_data_seek($ayear, 0);
	  $row_ayear = mysql_fetch_assoc($ayear);
  }
?>
                    </select>
                  </div></td>
                </tr>
                <tr>
                  <td><input name="checkdept" type="checkbox" id="checkdept" value="on"></td>
                  <td nowrap><div align="left">From Department: </div></td>
                  <td><div align="left">
                    <select name="dept" id="dept">
                        <?php
do {  
?>
                        <option value="<?php echo $row_dept['DeptName']?>"><?php echo $row_dept['DeptName']?></option>
                        <?php
} while ($row_dept = mysql_fetch_assoc($dept));
  $rows = mysql_num_rows($dept);
  if($rows > 0) {
      mysql_data_seek($dept, 0);
	  $row_dept = mysql_fetch_assoc($dept);
  }
?>
                    </select>
                  </div></td>
                </tr>
                <tr>
                  <td colspan="3"><div align="center"></div></td>
                </tr>
              </table>
              <select name="regno[]" size="15" multiple id="regno[]">
                <?php
do {  
?>
                <option value="<?php echo $row_studentlist['RegNo']?>"><?php echo $row_studentlist['RegNo'].":  ".$row_studentlist['Name']?></option>
                <?php
} while ($row_studentlist = mysql_fetch_assoc($studentlist));
  $rows = mysql_num_rows($studentlist);
  if($rows > 0) {
      mysql_data_seek($studentlist, 0);
	  $row_studentlist = mysql_fetch_assoc($studentlist);
  }
?>
              </select>
              
              <input name="action" type="submit" id="action" value="Print Results"> 
              <input name="MM_update" type="hidden" id="MM_update" value="form1">       
  </div>
</form>
<?php
}
include('../footer/footer.php');
?>