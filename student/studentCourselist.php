<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
include('studentMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Academic Records';
	$szTitle = 'Exam Registration: Please Pick a Course';
	$szSubSection = 'Course Roster';
	include("studentheader.php");
    
    //die($RegNo);
?>
<?php
//CHARLIE MAERE CODE........
// THIS IS THE REGISTRATION FORM, WHERE STUDENTS REGISTER WITHOUT CHOOSING COURSES
$fees = $_GET['fees'];
$minimumfee = $_GET['minimumfee'];
$balance = $_GET['balance'];
$semester = $_GET['semester'];
$sponsor = $_GET['sponsor'];

//registration
if(isset($_POST['reg']))
{


    $regno = $_POST['regno'];
    $username1 = $_POST['username'];
    $progcode = $_POST['prog'];
    //$prefix = $_POST['prefix'];
    $semister = $_POST['sem'];
    //query current Year
    $qyear = "SELECT AYear from academicyear WHERE Status = 1";
    $dbyear = mysql_query($qyear);
    $row_year = mysql_fetch_assoc($dbyear);
    $currentYear = $row_year['AYear'];
        
    if($progcode == "ED" || $progcode == "COM" || $progcode == "MGT" || $progcode == "PEAD")
    {
    
        $prefix = $progcode;
        $qprogname = "SELECT ProgrammeName FROM program_year WHERE ProgrammeCode = '10052' AND prefix = '$prefix'";
        $dbprogname = mysql_query($qprogname);
        $row_progname  = mysql_fetch_assoc($dbprogname);
        $progname= $row_progname['ProgrammeName'];
        
        //die($progname);
        
        //QUERY TO CHECK IF ALREADY REGISTERED
        $qregistered = "SELECT  CourseCode FROM examregister WHERE RegNo = '$regno' AND Semester = '$semister' AND AYear = '$currentYear'";
        $dbregistered= mysql_query($qregistered);
        $regcheck = mysql_num_rows($dbregistered);


        if($regcheck == 0)
        {
                $query_coursecode = "SELECT P.ProgrammeName, C.CourseCode, C.CourseName ,C.YearOffered FROM program_year P, course C
                                WHERE
                                P.ProgrammeCode = C.Programme
                                AND P.ProgrammeCode = '10052' and C.YearOffered = '$semister' AND P.prefix like '%$prefix%' and C.prefix like '%$prefix%'
                                ORDER BY P.ProgrammeCode, C.YearOffered";
                                
                               // die($query_coursecode);
                    $resultb=mysql_query($query_coursecode);
                    while ($line = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
                    {
                        $coursecode = $line["CourseCode"];
                        //$progcode = $line["ProgrammeCode"];
                        $insertSQL = "INSERT INTO examregister (AYear, Semester, RegNo, CourseCode, Recorder, Checked) 
                                                            VALUES ('$currentYear', '$semister', '$regno', '$coursecode', '$username1', '0')";
                                                            
                                                           // die( $insertSQL);
                                                            
                        mysql_query($insertSQL);
                        
                        
                        
                        
                    }
                    
                    echo "<font color='green'>YOU HAVE SUCCESSFULLY REGISTERED ALL COURSES FOR $currentYear  $progname IN $semister</font><br><br><font color='blue'>PLEASE CHECK IN \"Exam Registered\" TO SEE COURSES YOU HAVE REGISTERED</font>";
       
        
        
        }
        else
        {
        
        echo "YOU HAVE ALREADY REGISTERED FOR $currentYear  $progname IN $semister<br><br><font color='blue'>PLEASE CHECK IN \"Exam Registered\" TO SEE COURSES YOU HAVE REGISTERED</font>";
        
        
        }
    
    
    }
    else
    {
        $qprogname = "SELECT ProgrammeName FROM program_year WHERE ProgrammeCode = '$progcode'";
        $dbprogname = mysql_query($qprogname);
        $row_progname  = mysql_fetch_assoc($dbprogname);
        $progname= $row_progname['ProgrammeName'];
        
       
        
        //QUERY TO CHECK IF ALREADY REGISTERED
        $qregistered = "SELECT  CourseCode FROM examregister WHERE RegNo = '$regno' AND Semester = '$semister' AND AYear = '$currentYear'";
        $dbregistered= mysql_query($qregistered);
        $regcheck = mysql_num_rows($dbregistered);


        if($regcheck == 0)
        {
                $query_coursecode = "SELECT P.ProgrammeName, C.CourseCode, C.CourseName ,C.YearOffered FROM program_year P, course C
                                    WHERE
                                    P.ProgrammeCode = C.Programme
                                    AND P.ProgrammeCode = '$progcode' AND C.YearOffered = '$semister'
                                    ORDER BY P.ProgrammeCode, C.YearOffered";
                    $resultb=mysql_query($query_coursecode);
                    while ($line = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
                    {
                        $coursecode = $line["CourseCode"];
                        //$progcode = $line["ProgrammeCode"];
                        $insertSQL = "INSERT INTO examregister (AYear, Semester, RegNo, CourseCode, Recorder, Checked) 
                                                            VALUES ('$currentYear', '$semister', '$regno', '$coursecode', '$username1', '0')";
                                                            

                                                            
                        mysql_query($insertSQL);
                        
                        
                        
                        
                    }
                    
                    echo "<font color='green'>YOU HAVE SUCCESSFULLY REGISTERED ALL COURSES FOR $currentYear  $progname IN $semister</font><br><br><font color='blue'>PLEASE CHECK IN \"Exam Registered\" TO SEE COURSES YOU HAVE REGISTERED</font>";
       
        
        
        }
        else
        {
        
        echo "YOU HAVE ALREADY REGISTERED FOR $currentYear  $progname IN $semister<br><br><font color='blue'>PLEASE CHECK IN \"Exam Registered\" TO SEE COURSES YOU HAVE REGISTERED</font>";
        
        
        }
    
    
    
    }


}
else if($fees < $minimumfee && $sponsor == 1 && $semester == "Semester II") 
 {
	   
	//echo "<font color='#FF0000'><b>YOU CAN NOT REGISTER FOR SECOND SEMESTER, YOU HAVE TO SETTLE YOU BALANCE AT ACCOUNTS OFFICE. YOUR BALANCE IS $balance </font>";   
	   
 }
 else
   {







?>
<form action="studentCourselist.php" method="post" enctype="multipart/form-data" name="frmCourse" target="_self">
		<table width="200" border="1" cellpadding="0" cellspacing="0">
        
		  <tr>
			<th scope="row" nowrap><div align="right">Select Your Programe:</div>
            			
			</th>
			<td><input name="regno" type="hidden" value="<?php echo $RegNo; ?>"><input name="username" type="hidden" value="<?php echo $username; ?>"><select name="prog" size="1">
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
		  </tr>
        <tr>
			<th scope="row" nowrap><div align="right">Select Semister:</div>
            			
			</th>
			<td><select name="sem" size="1">
			<option value="0">[Select Semister]</option>
            <option value="Semester I">Semester I</option>
             <option value="Semester II">Semester II</option>

			</select></td>
		  </tr>
		  
		  
		  <tr>
			<th scope="row"><div align="right">Register:</div></th>
			<td><input name="reg" type="submit" value="Register"></td>
		  </tr>
		</table>
					
		</form>			

<?php
}

/*
$currentPage = $_SERVER["PHP_SELF"];
$maxRows_courselist = 13;
$pageNum_courselist = 0;
if (isset($_GET['pageNum_courselist'])) {
  $pageNum_courselist = $_GET['pageNum_courselist'];
}
$startRow_courselist = $pageNum_courselist * $maxRows_courselist;
mysql_select_db($database_zalongwa, $zalongwa);
if (isset($_GET['course'])) {
  $key=$_GET['course'];
  $query_courselist = "SELECT CourseCode, CourseName, Units FROM course WHERE CourseCode Like '%$key%' ORDER BY CourseCode";
}else{
$query_courselist = "SELECT CourseCode, CourseName, Units FROM course ORDER BY CourseCode";
}

$query_limit_courselist = sprintf("%s LIMIT %d, %d", $query_courselist, $startRow_courselist, $maxRows_courselist);
$courselist = mysql_query($query_limit_courselist, $zalongwa) or die(mysql_error());
$row_courselist = mysql_fetch_assoc($courselist);

if (isset($_GET['totalRows_courselist'])) {
  $totalRows_courselist = $_GET['totalRows_courselist'];
} else {
  $all_courselist = mysql_query($query_courselist);
  $totalRows_courselist = mysql_num_rows($all_courselist);
}
$totalPages_courselist = ceil($totalRows_courselist/$maxRows_courselist)-1;

$queryString_courselist = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_courselist") == false && 
        stristr($param, "totalRows_courselist") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_courselist = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_courselist = sprintf("&totalRows_courselist=%d%s", $totalRows_courselist, $queryString_courselist);
 
?>
<table width="720" border="1" cellpadding="0" cellspacing="0">
            <tr>
			<td width="25">Pick </td>
              <td width="60">Course Code </td>
			  <td width="40">Units</td>
              <td width="444" nowrap>Course Description </td>
            </tr>
            <?php do { ?>
            <tr>
                <td><?php $CourseCode = $row_courselist['CourseCode']; echo "<a href=\"studentcourseregister.php?CourseCode=$CourseCode\"> Pick </a>"; ?></td>
				<td nowrap><?php echo $row_courselist['CourseCode']; ?></td>
                <td><?php echo $row_courselist['Units']; ?></td>
                <td><?php echo $row_courselist['CourseName']; ?></td>
            </tr>
            <?php } while ($row_courselist = mysql_fetch_assoc($courselist)); ?>
</table>
		    <p><a href="<?php printf("%s?pageNum_courselist=%d%s", $currentPage, max(0, $pageNum_courselist - 1), $queryString_courselist); ?>">Previous Page</a> <span class="style66"><span class="style1">....</span><span class="style34">Record: <span class="style67"><span class="style34"><?php echo min($startRow_courselist + $maxRows_courselist, $totalRows_courselist) ?></span></span> of <?php echo $totalRows_courselist ?> </span><span class="style1">......</span></span><a href="<?php printf("%s?pageNum_courselist=%d%s", $currentPage, min($totalPages_courselist, $pageNum_courselist + 1), $queryString_courselist); ?>">Next Page</a></p>
		    <form name="form1" method="get" action="studentCourselist.php">
              Search by Course Code
              <input name="course" type="text" id="course" maxlength="50">
              <input type="submit" name="Submit" value="Search">
            </form>
		   
<?php
mysql_free_result($courselist);

*/
?>
