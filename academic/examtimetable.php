<script language="JavaScript">

function error()
{

alert('One of the Item is Blank, Please each box should be filled');

}

function error2(course)
{

alert('Course ' + course + ' already entered in the timetable');

}
</script>
<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Examination';
	$szSubSection = 'Exam Time Table';
	$szTitle = 'Exam Time Table';
	include('lecturerheader.php');

#save user statistics
$browser  = $_SERVER["HTTP_USER_AGENT"];   
$ip  =  $_SERVER["REMOTE_ADDR"];   
$sql="INSERT INTO stats(ip,browser,received,page) VALUES('$ip','$browser',now(),'$username')";   
$result = mysql_query($sql) or die("Siwezi kuingiza data.<br>" . mysql_error());

#Control Refreshing the page
#if not refreshed set refresh = 0
@$refresh = 0;
#------------
#populate academic year combo box
mysql_select_db($database_zalongwa, $zalongwa);
$query_AYear = "SELECT AYear FROM academicyear WHERE Status = 1";
$AYear = mysql_query($query_AYear, $zalongwa) or die(mysql_error());
$row_AYear = mysql_fetch_assoc($AYear);
$totalRows_AYear = mysql_num_rows($AYear);

//check if is a Departmental examination officer
$query_userdept = "SELECT Dept FROM security where UserName = '$username' AND Dept<>0";
$userdept = mysql_query($query_userdept, $zalongwa) or die(mysql_error());
$row_userdept = mysql_fetch_assoc($userdept);
$totalRows_userdept = mysql_num_rows($userdept);
mysql_select_db($database_zalongwa, $zalongwa);

//check if is Faculty examination officer
$query_userfac = "SELECT Faculty FROM security where UserName = '$username' AND Dept=0";
$userfac = mysql_query($query_userfac, $zalongwa) or die(mysql_error());
$row_userfac = mysql_fetch_assoc($userfac);
$totalRows_userfac = mysql_num_rows($userfac);
$fac = $row_userfac["Faculty"];
//academic year


$query_AYear = "SELECT AYear, Semister_status FROM academicyear WHERE Status = 1";
$AYear = mysql_query($query_AYear, $zalongwa) or die(mysql_error());
$row_AYear = mysql_fetch_assoc($AYear);
$totalRows_AYear = mysql_num_rows($AYear);
$result_AYear=mysql_query($query_AYear);
while ($line = mysql_fetch_array($result_AYear, MYSQL_ASSOC)) 
    		{
                $ayear= $line["AYear"];  
                $semister = $line["Semister_status"];
            }                



$query_coursecodeb = "
            SELECT DISTINCT course.CourseCode, 
                            examregister.AYear
            FROM examregister 
                INNER JOIN course ON (examregister.CourseCode = course.CourseCode)
            WHERE (examregister.AYear ='$ayear') 
              ORDER BY examregister.CourseCode ASC";
            
            
$coursecodeb= mysql_query($query_coursecodeb, $zalongwa) or die(mysql_error());

if($totalRows_userdept>0){
							$query_dept = "SELECT department.DeptName, department.DeptID
							FROM department
							INNER JOIN security ON (department.DeptID = security.Dept)
							WHERE 
							   (
								  (UserName = '$username')
							   )
							ORDER BY department.DeptName";
  }elseif($privilege == 2){
						$query_dept = "SELECT FacultyID, FacultyName FROM faculty 
										WHERE
											(
												(FacultyID = '$fac')
											)";
						}else{
								$query_dept = "SELECT DeptID, DeptName	FROM department 
								ORDER BY DeptName ASC";
								}
								
$dept = mysql_query($query_dept, $zalongwa) or die(mysql_error());
$row_dept = mysql_fetch_assoc($dept);
$totalRows_dept = mysql_num_rows($dept);
$deptid = $row_dept["DeptID"];


#process form submission
$editFormAction = $_SERVER['PHP_SELF'];



/// cha edits
#set refresh = 1
$refresh = 1;

#..............
@$ayear = $row_AYear['AYear'];
@$faculty = $row_userfac["Faculty"];


#populate CourseCode combo box
/*
if ($privilege ==3) {
$query_coursecode = "
		SELECT DISTINCT course.CourseCode, 
						examregister.AYear
		FROM examregister 
			INNER JOIN course ON (examregister.CourseCode = course.CourseCode)
		WHERE (examregister.AYear ='$ayear') 
		AND (examregister.RegNo='$username')  ORDER BY examregister.CourseCode ASC";
}else{
$query_coursecode = "
		SELECT DISTINCT course.CourseCode, 
						examregister.AYear
		FROM examregister 
			INNER JOIN course ON (examregister.CourseCode = course.CourseCode)
		WHERE (examregister.AYear ='$ayear') 
		AND (course.Faculty = '$faculty') ORDER BY examregister.CourseCode ASC";
}
*/


if ($privilege ==2) {

//die('am here4');
$query_coursecode = "
		SELECT DISTINCT ProgrammeName,ProgrammeCode,prefix,ProgrammeYear
		FROM program_year
	
		 ORDER BY ProgrammeCode,ProgrammeName ASC";

$query_coursecode2 = "
		SELECT DISTINCT submitresult.courseCode, submitresult.category,
						course.CourseName
		FROM submitresult 
			INNER JOIN course ON (course.CourseCode = submitresult.courseCode)
		WHERE (submitresult.acYear = '$ayear') 
		AND (submitresult.dept='$deptid')  ORDER BY submitresult.courseCode ASC";

}else{
$query_coursecode = "
		SELECT DISTINCT ProgrammeName,prefix,ProgrammeCode,ProgrammeYear
		FROM program_year
	
		 ORDER BY ProgrammeCode,ProgrammeName ASC";
}


$coursecode = mysql_query($query_coursecode, $zalongwa) or die(mysql_error());
$coursecode2 = mysql_query($query_coursecode2, $zalongwa) or die(mysql_error());
$row_coursecode2 = mysql_fetch_assoc($coursecode2);
//die($row_coursecode2['category']);


//form processing
if(isset($_POST["savetimetable"]))
{

$program = $_POST["programe"];
$course= $_POST["course"];
$semister= $_POST["sem"];
$category= $_POST["category"];
$examdate= $_POST["examdate"];
$ayear= $_POST["ayear"];



$sqlcheck = "select CourseCode from examdate where CourseCode = '$course' and Ayear = $ayear and Semister = '$semister'  ";
$result_check=mysql_query($sqlcheck);
$sqlcheckrows= mysql_num_rows($result_check);
//die($sqlcheck);
if($sqlcheckrows != 0)
{
//die('here');

echo "<script language='JavaScript'> error2('".$course."'); </script>";
 echo '<meta http-equiv = "refresh" content ="0; 
				 url = examtimetable.php">';
 
 exit;


}

if($program == '0' || $course == '0' || $semister == '0' || $category == '0' || $examdate == '')
{

 echo "<script language='JavaScript'> error(); </script>";
 echo '<meta http-equiv = "refresh" content ="0; 
				 url = examtimetable.php">';
 
 exit;


}

$sql = "INSERT INTO examdate(CourseCode,Ayear,Semister,examdate,assessment_status,programme) VALUES('$course','$ayear','$semister','$examdate','$category','$program')";
mysql_query($sql, $zalongwa) or die(mysql_error());
echo "Time Table Successfully Updated";
echo '<meta http-equiv = "refresh" content ="0; 
				 url = examtimetable.php">';
                 exit;

}



?>




 <fieldset>
	<legend>Select Appropriate Entries</legend>
		<form action="examtimetable.php" method="post" enctype="multipart/form-data" name="frmCourse" target="_self">
		<table width="200" border="1" cellpadding="0" cellspacing="0">
		  <tr>
			<th scope="row" nowrap><div align="right">Programme:</div>
			<input name="ayear" type="hidden" value="<?php echo $ayear ?>">
            <input name="examcat" type="hidden" value="<?php echo $row_coursecode['prefix']; ?>">
            <input name="code" type="hidden" value="<?php echo $row_coursecode['ProgrammeCode']; ?>">
             <input name="year" type="hidden" value="<?php echo $row_coursecode['ProgrammeYear']; ?>">
			</th>
			<td><select name="programe" size="1">
			<option value="0">[Select Programme]</option>
			<?php
				do {  
                
                
						?>
						<option value="<?php echo $row_coursecode['ProgrammeName']?>"><?php echo $row_coursecode['ProgrammeName'];?></option>
						<?php
							} while ($row_coursecode = mysql_fetch_assoc($coursecode));
									$rows = mysql_num_rows($coursecode);
									if($rows > 0) {
						mysql_data_seek($coursecode, 0);
						$row_coursecode = mysql_fetch_assoc($coursecode);
  					}
               ?>
			
			</select></td>
		  </tr>
	
		  <tr>
                <th scope="row" nowrap><div align="right">Course Code:</div>
                </th>
                <td><select name="course" size="1" >
                <?php
                
               
                
                    echo "<option value='0'>[Select Course Code]</option>";
                
               
                
               
                    do {  
                            ?>
                            <option value="<?php echo $row_coursecodeb['CourseCode']?>"><?php echo $row_coursecodeb['CourseCode']?></option>
                            <?php
                                } while ($row_coursecodeb = mysql_fetch_assoc($coursecodeb));
                                        $rows = mysql_num_rows($coursecodeb);
                                        if($rows > 0) {
                            mysql_data_seek($coursecodeb, 0);
                            $row_coursecodeb = mysql_fetch_assoc($coursecodeb);
                        }
                   ?>
                
                </select></td>
              </tr>
		  
           <tr>
                <th scope="row" nowrap><div align="right">Semister:</div>
                </th>
                <td><select name="sem" size="1" >
                <option value='0'>[Select Semester]</option>
                <option value='Semester I'>Semester I</option>
                <option value='Semester II'>Semester II</option>
                </select>
          
          <tr>
           <tr>
                <th scope="row" nowrap><div align="right">Assessment Status</div>
                </th>
                <td><select name="category" size="1" >
                <option value='0'>[Select Status]</option>
                <option value='4'>Continuous Assessment</option>
                <option value='5'>End of Year</option>
                </select>
          
          <tr>
                <th scope="row" nowrap><div align="right">Exam Date:</div></th>
                <td>
                <!-- A Separate Layer for the Calendar -->
                        <script language="JavaScript" src="datepicker/Calendar1-901.js" type="text/javascript"></script>
                         <table border="0">
                                        <tr>
                                         <td><input type="text" name='examdate' value = ''</td>
                                            <td><input type="button" class="button" name="rpDate_button" value="Change Date" onClick="show_calendar('frmCourse.examdate', '','','YYYY-MM-DD', 'POPUP','AllowWeekends=Yes;Nav=No;SmartNav=Yes;PopupX=325;PopupY=325;')"></td>
                                        </tr>
                          </table>
                </td>
              </tr>
          
		  <tr>
			<th scope="row"><div align="right">Save</div></th>
			<td><input name="savetimetable" type="submit" value="Save Into Time Table"></td>
		  </tr>
		</table>
			</form>		
			
 </fieldset>
<?php
//end of the form display


#display the form when refresh is zero
if ($refresh == 0) {
?> 



<?php
}

include('../footer/footer.php');
?>