<script language="JavaScript">

function doquery()
{
 var coursecode = document.frmCourse.course.value

    self.location='lecturerGradebook.php?coursecode=' + coursecode;

}

function category(course)
{

var cat = document.frmCourse.examcat.value
var examdate = document.frmCourse.examdate.value
self.location='lecturerGradebook.php?coursecode=' + course +'&cat=' + cat +'&examdate=' + examdate;
}

function msgbox(msg1,msg2)
{

alert('This is '+ msg1 +' Course please select on '+ msg2 +' to proceed');

}
function normalcat(catid,catedisplay,coursecode,examdate)
{
    self.location='lecturerGradebook.php?catb=' + catid +'&catedisplay=' + catedisplay +'&coursecode=' + coursecode +'&examdate=' + examdate;

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
	$szSubSection = 'Grade Book';
	$szTitle = 'Examination GradeBook';
	include('lecturerheader.php');

#save user statistics
$browser  = $_SERVER["HTTP_USER_AGENT"];   
$ip  =  $_SERVER["REMOTE_ADDR"];   
$sql="INSERT INTO stats(ip,browser,received,page) VALUES('$ip','$browser',now(),'$username')";   
$result = mysql_query($sql) or die("Siwezi kuingiza data.<br>" . mysql_error());

#Set academic year 
mysql_select_db($database_zalongwa, $zalongwa);
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
							$query_dept = "SELECT department.DeptName
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
// end check faculty

//Catergory validations
$cat = $_GET['cat'];
$coursecatvalid = $_GET['coursecode'];
$examdatevalid = $_GET['examdate'];
if($cat  <> '')
{
    
    
        $catvalid_sql = "SELECT assessment_status FROM examdate WHERE CourseCode = '$coursecatvalid'";
                                               //die($coursecatvalid_sql);
                                               $result_catvalid = mysql_query($catvalid_sql);
                                                while ($line = mysql_fetch_array($result_catvalid, MYSQL_ASSOC)) 
                                                {
                                                    $assessment_status= $line["assessment_status"];  
                                                    
                                                }
                                                
       // contious course = 4
       // end of year course = 5
       if($assessment_status == 4 )
       {
       
            if($cat <> $assessment_status)
            {
                //die('here');
              
              echo "<script language='JavaScript'> msgbox('a continous', 'Countinous Assessment'); </script>";
            
            }
            else
            {
            
                if($fac==1)
                {
                    $query_examcategory = "SELECT Id,Description FROM examcategory WHERE Id = '$cat' ";
                }
                else
                {
                    $query_examcategory = "SELECT Id,Description FROM examcategory WHERE Id = '$cat'";
                }
                
                                               $result_catnormal = mysql_query($query_examcategory);
                                                while ($line = mysql_fetch_array($result_catnormal, MYSQL_ASSOC)) 
                                                {
                                                    $catedisplay =  $line["Description"]; 
                                                    $catid       =  $line["Id"];                                                   
                                                    
                                                }
                                                
                //die('here');
                echo "<script language='JavaScript'> normalcat('$catid','$catedisplay','$coursecatvalid','$examdatevalid'); </script>";
            
            }
       
       
       }
       else if($assessment_status == 5 )
       {
            
            if($cat <> $assessment_status)
            {
                //die('here');
              
              echo "<script language='JavaScript'> msgbox('an End of Year', 'End of Year Assessment'); </script>";
            
            }
             else
            {
            
                if($fac==1)
                {
                    $query_examcategory = "SELECT Id,Description FROM examcategory WHERE Id = '$cat' ";
                }
                else
                {
                    $query_examcategory = "SELECT Id,Description FROM examcategory WHERE Id = '$cat'";
                }
                
                                               $result_catnormal = mysql_query($query_examcategory);
                                                while ($line = mysql_fetch_array($result_catnormal, MYSQL_ASSOC)) 
                                                {
                                                    $catedisplay =  $line["Description"]; 
                                                    $catid       =  $line["Id"];                                                   
                                                    
                                                }
                                                
                //die('here');
                echo "<script language='JavaScript'> normalcat('$catid','$catedisplay','$coursecatvalid','$examdatevalid'); </script>";
            
            }
       
       }




}
//end catergory validations

// Process of exam grade form entry intro
  

   

    if($fac==1){
    $query_examcategory = "SELECT Id,Description FROM examcategory WHERE (Id > 2) ORDER BY Id";
    }else{
    $query_examcategory = "SELECT Id,Description FROM examcategory WHERE (Id < 11) ORDER BY Id";
    }
    $examcategory = mysql_query($query_examcategory, $zalongwa) or die(mysql_error());
    $row_examcategory = mysql_fetch_assoc($examcategory);
    $totalRows_examcategory = mysql_num_rows($examcategory);

   
   
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
            SELECT DISTINCT CourseCode
            FROM course 
            WHERE 
             (Faculty = '$fac') ORDER BY CourseCode ASC";
    }


    $coursecode = mysql_query($query_coursecode, $zalongwa) or die(mysql_error());

    ?>
     <fieldset>
        <legend>Select Appropriate Entries</legend>
            <form action="lecturerGradebookAdd.php" method="post" enctype="multipart/form-data" name="frmCourse" target="_self">
            <table width="200" border="1" cellpadding="0" cellspacing="0">
              <tr>
                <th scope="row" nowrap><div align="right">Course Code:</div>
                <input name="ayear" type="hidden" value="<?php echo $ayear ?>">
                </th>
                <td><select name="course" size="1" onchange="doquery()">
                <?php
                $coursecodeb = $_GET["coursecode"];
                if($coursecodeb == '')
                {
                
                    echo "<option value='0'>[Select Course Code]</option>";
                
                }
                else
                {
                    echo "<option value='$coursecodeb'>$coursecodeb</option>";
                }
                
               
                    do {  
                            ?>
                            <option value="<?php echo $row_coursecode['CourseCode']?>"><?php echo $row_coursecode['CourseCode']?></option>
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
                <th scope="row" nowrap><div align="right">Exam Category:</div></th>
                <td><select name="examcat" size="1" onchange="category('<?php echo $coursecodeb; ?>')">
                <?php
                $categorydisp = $_GET['catedisplay'];
                $catvalid = $_GET['catb'];
                
                if($categorydisp =='')
                {
                    echo"<option value='0'>[Select Examcategory]</option>";
                
                }
                else
                {
                
                    echo"<option value='$catvalid'>$categorydisp </option>";
                
                }
                
                            
                    do {  
                            ?>
                            <option value="<?php echo $row_examcategory['Id']?>"><?php echo $row_examcategory['Description']?></option>
                            <?php
                                } while ($row_examcategory = mysql_fetch_assoc($examcategory));
                                        $rows = mysql_num_rows($examcategory);
                                        if($rows > 0) {
                            mysql_data_seek($examcategory, 0);
                            $row_examcategory = mysql_fetch_assoc($examcategoryr);
                        }
                   ?>
                </select></td>
              </tr>
              
                <th scope="row" nowrap><div align="right">Exam Date:</div></th>
                <td>
                <!-- A Separate Layer for the Calendar -->
                        <script language="JavaScript" src="datepicker/Calendar1-901.js" type="text/javascript"></script>
                         <table border="0">
                                        <tr>
                                        <?php
                                        // getting exam date for the course
                                        
                                        $examdate_sql = "SELECT examdate FROM examdate WHERE CourseCode = '$coursecodeb' AND Semister = '$semister'";
                                       
                                       $result_examdate=mysql_query($examdate_sql);
                                        while ($line = mysql_fetch_array($result_examdate, MYSQL_ASSOC)) 
    		                            {
                                            $examdate= $line["examdate"];  
                                            
                                        }                
                                        
                                        ?>
                                            <td><input type="text" name='examdate' value = '<?php echo $examdate; ?>'</td>
                                            <td><input type="button" class="button" name="rpDate_button" value="Change Date" onClick="show_calendar('frmCourse.examdate', '','','YYYY-MM-DD', 'POPUP','AllowWeekends=Yes;Nav=No;SmartNav=Yes;PopupX=325;PopupY=325;')"></td>
                                        </tr>
                          </table>
                </td>
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



include('../footer/footer.php');
?>