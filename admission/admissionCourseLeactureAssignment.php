<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa_newphp.php');
	
	# initialise globals
	include('admissionMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Profile';
	$szSubSection = 'Profile';
	$szTitle = 'Course Lecturer Assignment';
	include('admissionheader.php');

	function lecturerCheck($lecturer,$zalongwa)
	{
		$sql2 = "SELECT LectId FROM LecturerCourse WHERE LectId = '$lecturer";
           
           $list=mysqli_query($zalongwa,$sql2);
           $check = mysqli_num_rows($list);
		   
		   return($check);
		
	}
	
?>




<form id="form1" name="form1" method="post" action="<? $_SERVER['PHP_SELF']?>">
        Lecturer: <br>
        <select id="lecturer" name="lecturer" onchange="run()">  
           <option value="">--- Select ---</option>
           <?php
		
		$sql2 = "SELECT UPPER(FullName) as FullName,RegNo FROM security WHERE Position = 'Lecturer' ORDER BY FullName ASC";
           
           $list=mysqli_query($zalongwa,$sql2);
           while($row_list=mysqli_fetch_assoc($list)){
           ?>
           <option value="<?php echo $row_list['RegNo']; ?>">
			   
              <?php echo  $row_list['FullName']; ?>
			 
           </option>
           <?php
           }
           ?>
        </select><br><br>
        Courses:  <br>
        <select name='courses'>
            <option value="">--- Select ---</option>
            <?php
			
			$sql3 = "SELECT CourseName,CourseCode FROM course ORDER BY CourseCode ASC";
            
            $list=mysqli_query($zalongwa,$sql3);
            while($row_list=mysqli_fetch_assoc($list)){
            ?>
            <option value="<?php echo $row_list['CourseCode']; ?>">
               <?php echo $row_list['CourseName']." (".$row_list['CourseCode'].")"; ?>
            </option>
            <?php
            }
            ?>
        </select>
		<br><br>
		        Course Subsection (<i>Optional</i>): <br>
		
		<input type="text" name="option" value=""\> <br>
		<input type="submit" name="submit" value="Submit"\>
    </form> 
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <!--[ I'M GOING TO INCLUDE THE SCRIPT PART DOWN BELOW ]-->




		<br>
<br>
<?php

if(isset($_POST["submit"]))
{
	$lecturer = $_POST["lecturer"];
	$course = $_POST["courses"];
	$option = $_POST["option"];
	
	$lecturercheck = lecturerCheck();
	if($lecturercheck == 0)
	{
		$sqlins = "INSERT INTO LecturerCourse(CourseCode,LectId,CourseOptions) VALUES('$course','$lecturer','$option')";
		mysqli_query($zalongwa,$sqlins) OR die(mysqli_error());
		
	}
	else
	{
		$sqlup = "UPDATE LecturerCourse SET CourseCode='$course',CourseOptions='$options' WHERE LectId = '$lecturer'";
		mysqli_query($zalongwa,$sqlup) OR die(mysqli_error());
		
	}
	
	echo "Data Successfully Updated";
	
	
}

	# include the footer
	include('../footer/footer.php');
?>