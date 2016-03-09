<script language="JavaScript">

function data()
{
 var program = document.frmDept.program.value
 
 var year = document.frmDept.year.value
 var semister = document.frmDept.semister.value
 if (program == "Bachelor of Science in Nursing and Midwifery (Generic) Yr4")
 {
	 if(semister == "Semester II")
	{
		 self.location='pdf/tutorial/dof_pdfb_year4.php?program=' + program +'&year=' + year +'&semister=' + semister +'&index=1';
	}
	else
	{
		self.location='pdf/tutorial/dof_pdfb.php?program=' + program +'&year=' + year +'&semister=' + semister +'&index=1';
 
	}
   
 }
 else if (program == "Bachelor of Science in Nursing (Post Basic) Yr2 COM" || program == "Bachelor of Science in Nursing (Post Basic) Yr2 EDU" || program == "Bachelor of Science in Nursing (Post Basic) Yr2 MGT" )
 {
	if(semister == "Semester II")
	{
		 self.location='pdf/tutorial/dof_pdfb_mature.php?program=' + program +'&year=' + year +'&semister=' + semister +'&index=1';
	}
	else
	{
    	self.location='pdf/tutorial/dof_pdfb_mature_sem1.php?program=' + program +'&year=' + year +'&semister=' + semister +'&index=1';
	}
 }
 else  if (program == "University Certificate in Midwifery")
 {
    self.location='pdf/tutorial/dof_pdfbucm.php?program=' + program +'&year=' + year +'&semister=' + semister +'&index=1';
 }
 else
 {
    self.location='pdf/tutorial/dof_pdfb.php?program=' + program +'&year=' + year +'&semister=' + semister +'&index=1';
 }

}


function data2()
{
 var ayear = document.frmDept2.ayear.value
 var semister = document.frmDept2.semister.value
 var status = document.frmDept2.status.value
     self.location='DOFassessment2.php?ayear=' + ayear +'&semister=' + semister +'&status=' + status +'&index=1';
 

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
	$szSubSection = 'Faculty Exam Assessment';
	$szTitle = 'Faculty Exam Assessment';
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
$query_AYear = "SELECT AYear, Semister_status FROM academicyear WHERE Status = 1";
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
$year = $row_AYear['AYear'];
$semister = $row_AYear['Semister_status'];
@$faculty = $row_userfac["Faculty"];

//die($semester.$ayear.$deptid.@$faculty);
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


$index = $_GET['index'];
 if( isset($_POST["hod"]))
{

if ($semister == "Semester II")
{
    $cat = 5;
}
else
{
    $cat = 4;
}

//die($status." mememe");
?>
<fieldset>
	<legend>Select Academic Year</legend>
		<form action="DOFassessment2.php" method="post" enctype="multipart/form-data" name="frmDept2" target="_self">
		<table width="200" border="1" cellpadding="0" cellspacing="0">
		  
		  
		  
		  <tr>
			<th scope="row"><div align="right"></div></th>
			<td>
            <select name="ayear"  onchange="data2()">
            <option value="">---select---</option>
            <?php
            $sql1 =  " SELECT `AYear`,Semister_status FROM `academicyear` ORDER BY `AYear` DESC
LIMIT 0,5
            ";


            $result1 = mysql_query($sql1);
            while($row = mysql_fetch_array($result1, MYSQL_ASSOC))
            {
                $ayear= $row['AYear'];
                $status = $row['Semister_status'];
                echo "<option value='$ayear'>$ayear</option>";
            }
            echo "</select>";
              echo "<input type='hidden' name='status' value='$status'>";
           
            echo "<input type='hidden' name='semister' value='$semister'>";
              
            ?>
 
            
            
            </td>
            
		  </tr>
		</table>
			</form>		
			
 </fieldset>
 <?php

}
else if( $index == 1)
{

$ayear = $_GET['ayear'];
$status = $_GET['status'];
$semister = $_GET['semister'];



if ($semister == "Semester II")
{
    $cat = 5;
}
else
{
    $cat = 4;
}
?>
<fieldset>
	<legend><?php echo"<a href='pdf/tutorial/dof_pdfb_main2b.php?program=$program&year=$ayear&semister=$semister&index=1'>";?>Print Full ASSESSMENT REPORT FOR  <?php echo $ayear; ?></a></legend>
    
    </fieldset>
<fieldset>
	<legend>Select Programme Year for <?php echo $ayear; ?></legend>
		<form action="DOFassessment2.php" method="post" enctype="multipart/form-data" name="frmDept" target="_self">
		<table width="200" border="1" cellpadding="0" cellspacing="0">
		  
		  
		  
		  <tr>
			<th scope="row"><div align="right"></div></th>
			<td>
            <select name="program"  onchange="data()">
            <option value="">---select---</option>
            <?php
            $sql1 =  "       select distinct e.programme  from examdate e, submitresult s 
INNER JOIN examdate ON (examdate.CourseCode = s.courseCode) 
where e.Ayear = $ayear and s.category = $cat
            order by e.programme asc
            ";


            $result1 = mysql_query($sql1);
            while($row = mysql_fetch_array($result1, MYSQL_ASSOC))
            {
                $program= $row['programme'];
                echo "<option value='$program'>$program</option>";
            }
            echo "</select>";
            
            echo "<input type='hidden' name='year' value='$ayear'>";
            echo "<input type='hidden' name='semister' value='$semister'>";
              
            ?>
 
            
            
            </td>
            
		  </tr>
		</table>
			</form>		
			
 </fieldset>
 <?php

}

else if(isset($_POST["enter"]))

{


    $semister = $_POST["semister"];
    

     
    if ($privilege ==3) {
    $query_coursecode = "
            SELECT DISTINCT submitresult.courseCode, submitresult.category,
                            course.CourseName
            FROM submitresult 
                INNER JOIN course ON (course.CourseCode = submitresult.courseCode)
            WHERE (submitresult.acYear = '$ayear') 
            
            AND submitresult.semister = '$semister'
            ORDER BY submitresult.courseCode ASC";

    $query_coursecode2 = "
            SELECT DISTINCT submitresult.courseCode, submitresult.category,
                            course.CourseName
            FROM submitresult 
                INNER JOIN course ON (course.CourseCode = submitresult.courseCode)
            WHERE (submitresult.acYear = '$ayear') 
             ORDER BY submitresult.courseCode ASC";

    }else{
    $query_coursecode = "
            SELECT DISTINCT CourseCode
            FROM course 
            WHERE 
             (Faculty = '$fac') ORDER BY CourseCode ASC";
    }


    $coursecode = mysql_query($query_coursecode, $zalongwa) or die(mysql_error());
    $coursecode2 = mysql_query($query_coursecode2, $zalongwa) or die(mysql_error());
    $row_coursecode2 = mysql_fetch_assoc($coursecode2);
    //die($row_coursecode2['category']);

    ?>
     <fieldset>
        <legend>Select Appropriate Entries</legend>
            <form action="deptassess.php" method="post" enctype="multipart/form-data" name="frmCourse" target="_self">
            <table width="200" border="1" cellpadding="0" cellspacing="0">
              <tr>
                <th scope="row" nowrap><div align="right">Course Code:</div>
                <input name="row1" type="hidden" value=" <?php echo $row_coursecode2['category'];?>">
                <input name="ayear" type="hidden" value="<?php echo $ayear ?>">
                <input name="examcat" type="hidden" value="<?php echo $row_coursecode2['category']; ?>">
                </th>
                <td><select name="course" size="1">
                <option value="0">[Select Course Code]</option>
                <?php
                    do {  
                    
                    
                            ?>
                            <option value="<?php echo $row_coursecode['courseCode']?>"><?php echo $row_coursecode['courseCode'].' - '.$row_coursecode['CourseName'];?></option>
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
                <th scope="row"><div align="right">Class Roster:</div></th>
                <td><input name="view" type="submit" value="Edit Records"></td>
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
}
else
{
?>
<fieldset>
	<legend>Select Appropriate Entries</legend>
		<form action="DOFassessment2.php" method="post" enctype="multipart/form-data" name="frmCourse" target="_self">
		<table width="200" border="1" cellpadding="0" cellspacing="0">
		  
		  
		  
		  <tr>
			<th scope="row"><div align="right"></div></th>
			<td><input name="hod" type="submit" value="Faculty Report"></td>
            <td><input name="enter" type="submit" value="Edit Course results"></td>
            <?php
            
            echo "<input type='hidden' name='semister' value='$semister'>";
            
            ?>
		  </tr>
		</table>
			</form>		
			
 </fieldset>
 <?php

}


include('../footer/footer.php');
?>
