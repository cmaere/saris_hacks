<?php

	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	# include the header
	include('studentMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Course Evaluation';
	$szTitle = 'Course Evaluation';
	$szSubSection = 'Course Evaluation';
	include("studentheader.php");
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


         /*@$new=$_GET['new'];
         if (@$new=='1')
		    {
	         require_once("courseEvaluationForm.php");
	         @$new=='';
	         exit;
            }
			*/
	

	    //if ((isset($_POST["add"]))&&(($_POST["programme"]=='0') || ($_POST["year"]=='0') ))
		if ((isset($_POST["add"]))&&($_POST["programme"]=='0') )
	    {
		  $msg="Please select your programme of study";
		  
	    } 
	
if (isset($_POST['save']))
{
	include("courseEvaluationform.php");
}
	
if ((isset($_POST["add"]))&&($_POST["programme"]!='0')  )	
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
	<legend>Step 1: Select your Program  of study</legend>
		<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" name="frmAyear" target="_self">
		<table width="200" border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
		<tr ><td colspan="2" > <font size="3" color="red"> <?php echo $msg; ?> </font></td></tr>
		<tr><td colspan="2"> <b> <?php  ?></b></td></tr>
		  <tr>
			<th scope="row" nowrap><div align="right"> Programme:</div>
			</th>
			<td>
            <input name="regno" type="hidden" value="<?php echo $RegNo; ?>"><input name="username" type="hidden" value="<?php echo $username; ?>"><select name="programme" size="1">
			<option value="0">[Select Programme]</option>
<?php
				$query_coursecode = "SELECT DISTINCT ProgrammeName,ProgrammeCode, prefix FROM program_year ORDER BY ProgrammeCode ASC";
                $resultb=mysql_query($query_coursecode);
                while ($line = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
                {
                    $progname = $line["ProgrammeName"];
                    $progcode = $line["ProgrammeCode"];
                    $prefix = $line["prefix"];
                    if($progcode == 10052)
                    {
                    ?>
                    
                    
        
						<option value="<?php echo $prefix ; ?>"><?php echo $progname;?></option>
<?php
                    }
                    else
                    {
                    ?>
                    
                    
        
						<option value="<?php echo $progcode ; ?>"><?php echo $progname;?></option>
<?php
                    }              
                }
         
?>
			</select><input name="progname" type="hidden" value="<?php echo $progname; ?>"></td>


			<!-- <select name="Year" size="1"> 
			<select name="programme" id="select3">
		    <option value="0">--------------------------------</option>
            <option value="1001">Bachelor of Science in Nursing and Midwifery</option>
            <option value="1005">Bachelor of Science in Nursing (Post Basic)</option>
            <option value="1003">University Certificate in Midwifery</option>
           </select> -->
		   
		   
		   </td>
		  </tr>
		 <!-- <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Year:</div></th>
      <td>
	    <select name="year" id="select3">
		    <option value="0">--------------------------------</option>
            <option value="1">Year 1</option>
            <option value="2">Year 2</option>
            <option value="3">Year 3</option>
			<option value="4">Year 4</option>
           </select>
	  
</td>
    </tr>-->
           <tr>
			<th scope="row">  	&nbsp; 	&nbsp;</th>
			<td> 	&nbsp;  	&nbsp;</td>
		  </tr>
		  <tr>
			<th scope="row"><div align="right"></div></th>
			<td><input name="add" type="submit" value="Next"></td>
		  </tr>
		</table>
					
		</form>			
 </fieldset>
 <?php } 
 
 
 }
   
 
 
 ?>