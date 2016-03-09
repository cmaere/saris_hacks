<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('admissionMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Admission Process';
	$szSubSection = 'Exams Result';
	$szTitle = 'Examination Result';
	include('admissionheader.php');

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


//Print Room Allocation Report
if (isset($_POST['search']) && ($_POST['search'] == "Search")) {
#get post variables
$key = $_POST['key'];
			
$query_examresult = "
	   SELECT student.Name,
	   student.ProgrammeofStudy,
       examresult.RegNo,
       examresult.ExamNo,
       examresult.CourseCode,
       course.Units,
       examresult.Grade,
       examresult.AYear,
       examresult.Remarks,
	   examresult.SemesterID,
       examresult.Status
FROM examresult
   INNER JOIN course ON (examresult.CourseCode = course.CourseCode)
   INNER JOIN student ON (examresult.RegNo = student.RegNo)
WHERE examresult.RegNo LIKE '%$key%' OR student.Name LIKE '%$key%'";
$result = mysql_query($query_examresult) or die("Mwanafunzi huyu hana matokeo".  mysql_error()); 
			$query = @mysql_query($query_examresult) or die("Cannot query the database.<br>" . mysql_error());
			$row_result = mysql_fetch_array($result);
			$name = $row_result['Name'];
			$degree = $row_result['ProgrammeofStudy'];
			$regno = $row_result['RegNo'];
						
			if (mysql_num_rows($query) > 0){
			
					$totalunit=0;
					$unittaken=0;
					$sgp=0;
					$totalsgp=0;
					$gpa=0;
			echo  "$name - $regno - $degree";
			echo "<table border='1'>";
			echo "<tr><td> S/No </td><td> Name </td><td> RegNo </td><td> ExamNo </td><td>Year </td><td> Course</td><td> Unit </td><td> Grade </td><td> Remarks</td><td> Status</td></tr>";
			$i=1;
				while($result = mysql_fetch_array($query)) {
					
					
					$unit = $result['Units'];
					$totalunit=$totalunit+$unit;
					$regno = $result['RegNo'];
					$examno = $result['ExamNo'];
					$ayear = $result['AYear'];
					$name = $result['Name'];
					$remarks = $result['Remarks'];
					$status = $result['Status'];
					$grade = $result['Grade'];
					
					
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
								echo "<td>$name</td>";
								echo "<td>$regno</td>";
								echo "<td>$examno</td>";
								echo "<td>$ayear</td>";
								echo "<td>$coursecode</td>";
								echo "<td>$unit</td>";
								echo "<td>$grade</td>";
								echo "<td>$remarks</td>";
								echo "<td>$status</td></tr>";
								$i=$i+1;
						}
						echo "</table>";
						echo "Total Units = ". $totalunit.";   Units Completed = ".$unittaken.  ";   Total Points = ".$totalsgp. ";   GPA = ".substr($totalsgp/$unittaken, 0,3)."<br><hr>";
					}else{ 
					if(!@$reg[$c]){}else{
					echo "$c". ".Sorry, No Records Found for '$reg[$c]'<br><hr>";
							}
						}
					
mysql_close($zalongwa);

}else{

?>

<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" name="studentRoomApplication" id="studentRoomApplication">
            <table width="284" border="0">
        <tr>
          <td colspan="2" nowrap><div align="center"></div>
          </td>
        </tr>
        <tr>
          <td width="111" nowrap><div align="right"><span class="style67">Name or RegNo:</span></div></td>
          <td width="157" bordercolor="#ECE9D8" bgcolor="#CCCCCC"><span class="style67">
          <input name="key" type="text" id="key" size="40" maxlength="40">
          </span></td>
        </tr>
        <tr>
          <td nowrap><div align="right">Click Search: </div></td>
          <td bgcolor="#CCCCCC"><input type="submit" name="search" value="Search"></td>
        </tr>
      </table>
                    </form>
<?php
}
include('../footer/footer.php');
?>
