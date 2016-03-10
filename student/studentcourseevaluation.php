<?php

	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	# include the header
	/*include('studentMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Course Evaluation';
	$szTitle = 'Course Evaluation';
	$szSubSection = 'Course Evaluation';
	include("studentheader.php");
	global $AYear, $RegNo, $semester, $programme,$coursecode; */
?>
<?php

$fees = $_GET['fees'];
$minimumfee = $_GET['minimumfee'];
$balance = $_GET['balance'];
$semester = $_GET['semester'];
$sponsor = $_GET['sponsor'];
if($fees < $minimumfee && $sponsor == 1 && $semester == "Semester II") 
 {
	   
	//echo "<font color='#FF0000'><b>YOU CAN NOT REGISTER FOR SECOND SEMESTER, YOU HAVE TO SETTLE YOU BALANCE AT ACCOUNTS OFFICE. YOUR BALANCE IS $balance </font>";   
	   
 }
 else
  { 
		if ((isset($_POST["add"]))&&($_POST["programme"]=='0') )
	    {
		  $msg="Please Pick a course";
		  
	    } 
	

if ((isset($_POST["add"]))&&($_POST["programme"]!='0')  )	
{    
  $year = addslashes($_POST["year"]);
  $programme = addslashes($_POST["programme"]);
  $semester = addslashes($_POST["semester"]);
  $coursecode = ($_POST["coursecode"]);
    /*echo $RegNo;
	echo $coursecode;
	echo $AYear;
    echo $semester;
	echo $programme; */
	//$year = addslashes($_POST["year"]);
    //$programme = addslashes($_POST["programme"]);
	include("courseEvaluationform.php");
}
	
if (isset($_POST['save']))	
{

$year = addslashes($_POST["year"]);
$programme = addslashes($_POST["programme"]);	

$qprogcourses = "SELECT courseevalution.ayear, courseevalution.coursecode, courseevalution.unitname, courseevalution.lectureID, courseevalution.name, course.CourseName as coursename from courseevalution INNER JOIN course ON courseevalution.coursecode=course.CourseCode WHERE course.programme ='$programme' and courseevalution.status='1' ORDER BY  CourseCode ASC ";
$courseslist = mysql_query($qprogcourses, $zalongwa) or die(mysql_error());
$row_courseslist = mysql_fetch_assoc($courseslist);
//echo "sucess".$RegNo;
?>
<table border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td><strong>Year</strong></td>
	<td><strong>Course</strong></td>
	<td><strong>Course Name</strong></td>
    <td><strong>Unit name</strong></td>
	<td><strong>Lecturer</strong></td>
	<td><strong>Evaluate</strong></td>
	  </tr>
	  
  <?php do { ?>
            <tr>
                <td nowrap><?php $ayear= $row_courseslist['ayear']; echo $row_courseslist['ayear']; ?></td>
				<td nowrap><?php $courseCode=$row_courseslist['coursecode']; echo $row_courseslist['coursecode']; ?></td>
				<td nowrap><?php $coursename=$row_courseslist['coursename']; echo $coursename; ?></td>
				<td nowrap><?php $unitname=$row_courseslist['unitname']; echo $unitname; ?></td>
				<td nowrap><?php echo $name= $row_courseslist['name']; $lid = $row_courselist['lecturerID']; ?></td>
			  <td nowrap><?php  echo "<a href=\"courseEvaluationform.php?new=1&ayear=$ayear&courseCode=$courseCode&RegNo=$RegNo&unitname=$unitname&name=$name&lid=$lid&programme=$programme\"> Click to fill evaluation form </a>"; ?></
			</tr>
            <?php } while ($row_courseslist = mysql_fetch_assoc($courseslist)); ?>
			<tr><td colspan="6" valign="center" ><a href="studentcourseevaluation.php"> Cant find correct modules: BACK </a> </td> </tr>
</table>
  <?php      
      }
      else
	  {	
	
?>

<fieldset>
	<legend>Step 1: Select Course to evaluate</legend>
		<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" name="frmAyear" target="_self">
		<table width="200" border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
		<tr ><td colspan="2" > <font size="3" color="red"> <?php echo $msg; ?> </font></td></tr>
		<tr><td colspan="2"> <b> <?php  ?></b></td></tr>
		  <tr>
			<th scope="row" nowrap><div align="right"> Course: </div>
			</th>
			<td>
            <input name="regno" type="hidden" value="<?php echo $RegNo; ?>"><input name="username" type="hidden" value="<?php echo $username; ?>"><select name="coursecode" size="1">
			<option value="0">[Click here to pick course]</option>
<?php
           //PICKING CURRENTACADEMIC YEAR AND  SEMESTER
		   
           $query_ayear = "SELECT AYear, Semister_status FROM academicyear WHERE Status='1'";
		   
		   $resultAyear=mysql_query($query_ayear); 
            while ($line = mysql_fetch_array($resultAyear, MYSQL_ASSOC)) 
                {
                    $AYear = $line["AYear"];
                    $semester = $line["Semister_status"];
				}
             
			 //PICKING CURRENT PROGRAMME AND PROGRAMME COURSES  
												
			$query_regcourses= " SELECT DISTINCT examregister.CourseCode, course.programme
          FROM examregister
          INNER JOIN course ON ( examregister.CourseCode = course.CourseCode )
          WHERE (examregister.RegNo = '$RegNo'
          AND examregister.semester = 'semester I'
          AND examregister.AYear = '$AYear')";
		    
			$result_regcourses=mysql_query($query_regcourses); 
            while ($line2 = mysql_fetch_array($result_regcourses, MYSQL_ASSOC)) 
                {
                    //$firstcourse = $line2["CourseCode"];
					$programme = $line2["programme"];
					
                }
               
			    		   
				$query_coursecode = "SELECT DISTINCT CourseCode, CourseName FROM course WHERE  YearOffered LIKE '$semester' AND Programme= '$programme' ORDER BY coursecode ASC";
                $resultb=mysql_query($query_coursecode);
                while ($coursedetail = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
                {
                    $coursecode = $coursedetail["CourseCode"];
                    $coursename = $coursedetail["CourseName"];
                   
                  ?>
                 
				 <option value="<?php echo $coursecode; ?>"><?php echo $coursename;?></option>
    <?php
                                  
                }
         
     ?>
			</select> <input name="coursename" type="hidden" value="<?php echo $coursename; ?>"></td>
			</select> <input name="programme" type="hidden" value="<?php echo $programme; ?>"></td>
			</select> <input name="semester" type="hidden" value="<?php echo $semester; ?>"></td>
			</select> <input name="year" type="hidden" value="<?php echo $AYear; ?>"></td>

  
			
		   
		   
		   </td>
		  
		  </tr>

           <tr>
			<th scope="row">  	&nbsp; 	&nbsp;</th>
			<td> 	&nbsp;  	&nbsp;</td>
		  </tr>
		  <tr>
			<th scope="row"><div align="right"></div></th>
			<td><input name="add" type="submit" value="Next"> </td>
		  </tr>
		</table>
					
		</form>			
 </fieldset>
 
 <?php } 
 
 
 }
   
 
 
 ?>