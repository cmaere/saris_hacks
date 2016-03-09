<script language="JavaScript">
function msg(course,examcat,examdate,exammarker,ayear)
{

alert('PLEASE CLICK UPDATE BEFORE SUBMITTING');
self.location='lecturerGradebookAdd.php?course=' + course +'&examcat=' + examcat +'&examdate=' + examdate +'&exammarker=' + exammarker +'&ayear=' + ayear +'&clicked=1';

}
</script>
<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
    //die(session_id());
	  
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Examination';
	$szSubSection = 'Grade Book';
	$szTitle = 'Examination Grade Book';
	include('lecturerheader.php');
    
    
    //cha modifications - declaring a function to check submission status

    
    function cha_substate($name, $ayear, $score, $code,$examcat)
    {
    
                
                
               //die("am here now");
                //cha modification
                $sql_lectsubstatus = "select substatus from submitresult where Lecturer_Name = '$name' and acYear = '$ayear' and courseCode = '$code' and category = '$examcat'";
                
                //die($sql_lectsubstatus);
				$qsubstatus=mysql_query($sql_lectsubstatus) or die('cha q Problem');
				$total_row = mysql_num_rows($qsubstatus);
                //die("cha===".$total_row);
				if($total_row > 0){
                ?>
                 <td>
                <?php echo $score; ?></td>
                <?php
                
                }
                else
                {
                ?>
                    <td>
                <input name='cwk[]' type='text' id='cwk[]' value='<?php echo $score; ?>' size='3'></td>
                
                <?php
                
                }
                
                

    
    
    
    
    
    }
    
    
    //end modification

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


//cha modification creating exam submition to Head Of Department or Deand Of Faculty then locking the results when submitted 
if ((isset($_POST["subvalidy"])) ) 
{

        $query_AYear = "SELECT AYear, Semister_status FROM academicyear WHERE Status = 1";
        $result_AYear=mysql_query($query_AYear);
        while ($line = mysql_fetch_array($result_AYear, MYSQL_ASSOC)) 
                    {
                        $ayear= $line["AYear"];  
                        $semister = $line["Semister_status"];
                    }         

        $ayear = $_POST["acYear"];
        $lectname = $_POST["Lecturer_Name"];
        $course = $_POST["courseCode"];
        $dept = $_POST["Dept"];
        $cat = $_POST["category"];

        $subsql= "INSERT INTO submitresult(acYear,Lecturer_Name,substatus,courseCode,Dept,category,semister) VALUES('$ayear','$lectname','1', '$course','$dept','$cat', '$semister')";
        //die($subsql);
            mysql_query($subsql);
            
            echo "<br>Database updated successfully";
			#open data entry form again
	 	  echo "<meta http-equiv = 'refresh' content ='0; 
    url = lecturerGradebookAdd.php?course=".$course."&examcat=".$cat."&examdate=".$examdate."&exammarker=".$exammarker."&ayear=".$ayear."&clicked=1'>";      
                exit;
	exit;
            
            

}
else if ((isset($_POST["subvalidn"])) ) 
{
        echo '<meta http-equiv = "refresh" content ="0; 
				 url = lecturerGradebookAdd.php?r=1">';
                 exit;
}
else
if ((isset($_POST["SUB_Results"])) ) 
{


$queryses = "SELECT sessionid,update1 from session";


$resultb=mysql_query($queryses);
while ($line = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
{
                                          
    
    $session_db= $line["sessionid"];
   
    
    
}


$ayear=addslashes($_POST['ayear']);
$lectname=addslashes($_POST['lectname']);
$course=addslashes($_POST['coursecode']);
$cat=addslashes($_POST['examcat']);


$key=addslashes($_POST['coursecode']);
	//$ayear=addslashes($_POST['ayear']);
	$RegNo = $_POST['RegNo'];
	$cwk = $_POST['cwk'];
	$examcat = addslashes($_POST['examcat']);
	$examdate = addslashes($_POST['examdate']);
	$exammarker = addslashes($_POST['exammarker']);
	$remark = $_POST['sitting'];
	//$core=$_POST['core'];
	$comment = $_POST['comment'];
	$max = sizeof($RegNo);
	$_SESSION['max']=$max;
    
    //die($session_db ."  and  ".session_id());

if ($session_db != session_id())
{

echo "<script language='JavaScript'> msg('".$course."','".$examcat."','".$examdate."','".$exammarker."','".$ayear."'); </script>";
exit;
}





 $sql_dept = "select dept from security where FullName = '$lectname'";
                
				$qdept=mysql_query($sql_dept) or die('cha q Problem');
				$row_dept = mysql_fetch_assoc($qdept);
                
                    $dept = $row_dept["dept"];
                    
    //cha edit update results before submitting
    
    for($c = 0; $c < $max; $c++) {
	$score1 = $cwk[$c];
	$score2 = floatval($cwk[$c]);
    
   $updatesql = "UPDATE examresult SET AYear ='$ayear', Marker = '$exammarker', CourseCode = '$key', ExamCategory = '$examcat', ExamDate = '$examdate', ExamSitting = '$remark[$c]', Recorder = '$username', RecordDate = '$curdate', RegNo = '$RegNo[$c]', ExamScore = '$cwk[$c]', Status = '1', Comment = '$comment[$c]'
                    WHERE RegNo = '$RegNo[$c]' AND AYear ='$ayear' AND CourseCode = '$key' AND ExamCategory = '$examcat'";     
     mysql_query($updatesql);
     
     
     
     
				}//close for loop
                
                
                
echo "Submitting mean that you are no longer going to edit the results, you give all the powers to your HOD<br><font color='red'>ARE YOU REALLY SURE YOU WANT TO SUBMIT TO HOD?</font>
<form action='lecturerGradebookAdd.php' method='POST'>

<input type='hidden' name='acYear' value='$ayear'>
<input type='hidden' name='Lecturer_Name' value='$lectname'>
<input type='hidden' name='substatus' value='1'>
<input type='hidden' name='courseCode' value='$course'>
<input type='hidden' name='Dept' value='$dept'>
<input type='hidden' name='category' value='$cat'>
<input type='submit' name='subvalidy' value='YES'><input type='submit' name='subvalidn' value='NO'>
</form>
";
     
    
    
   exit; 
    



}
else
//end cha modification
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
//save contents to database

$sessionid = session_id();
$sqlsession = "UPDATE session SET sessionid='$sessionid',update1=1";
//die($sqlsession);
mysql_query($sqlsession);

	$key=addslashes($_POST['coursecode']);
	$ayear=addslashes($_POST['ayear']);
	$RegNo = $_POST['RegNo'];
	$cwk = $_POST['cwk'];
	$examcat = addslashes($_POST['examcat']);
	$examdate = addslashes($_POST['examdate']);
	$exammarker = addslashes($_POST['exammarker']);
	$remark = $_POST['sitting'];
	//$core=$_POST['core'];
	$comment = $_POST['comment'];
	$max = sizeof($RegNo);
	$_SESSION['max']=$max;
	
	#start for loop to treat each candidate
	for($c = 0; $c < $max; $c++) 
    {
        $score1 = $cwk[$c];
        $score2 = floatval($cwk[$c]);
    
            //cha edits 
            
            //UPDATE examresult set AYear = '$ayear', Marker = '$exammarker', CourseCode = '$key', ExamCategory = '$examcat', ExamDate =, ExamSitting, Recorder, RecordDate, RegNo, ExamScore, Status, Comment
            $curdate = date(d.'-'.m.'-'.Y);
    
                $presql = "select RegNo from examresult WHERE RegNo = '$RegNo[$c]' AND AYear ='$ayear' AND CourseCode = '$key' AND ExamCategory = '$examcat'";
                //die($presql);
                $result_presql=mysql_query($presql);
                $presqlrows= mysql_num_rows($result_presql);
                
                if($presqlrows == 0)
                {
                
                    $updateSQL = "INSERT INTO examresult(AYear, Marker, CourseCode, ExamCategory, ExamDate, ExamSitting, Recorder, RecordDate, RegNo, ExamScore, Status, Comment)
						 VALUES ('$ayear', '$exammarker', '$key', '$examcat', '$examdate', '$remark[$c]', '$username', now(), '$RegNo[$c]', '$cwk[$c]', '1', '$comment[$c]')";
                //die($updateSQL);
                    mysql_query($updateSQL);
                }
                else
                {
                
    
                    $updateSQL = "UPDATE examresult SET AYear ='$ayear', Marker = '$exammarker', CourseCode = '$key', ExamCategory = '$examcat', ExamDate = '$examdate', ExamSitting = '$remark[$c]', Recorder = '$username', RecordDate = '$curdate', RegNo = '$RegNo[$c]', ExamScore = '$cwk[$c]', Status = '1', Comment = '$comment[$c]'
                    WHERE RegNo = '$RegNo[$c]' AND AYear ='$ayear' AND CourseCode = '$key' AND ExamCategory = '$examcat'";
                        //to insert score validations later in future
                        
                  //      die($updateSQL);
                        mysql_query($updateSQL);
                 }



        }//close for loop
				#session error
				$_SESSION['err']=$err;
                
                
          echo "<br>Database updated successfully";
			#open data entry form again
	 	   echo "<meta http-equiv = 'refresh' content ='0; 
    url = lecturerGradebookAdd.php?course=".$key."&examcat=".$examcat."&examdate=".$examdate."&exammarker=".$exammarker."&ayear=".$ayear."&clicked=1'>";      
                exit;
			
}

else

if((isset($_POST["view"])) && ($_POST["view"] == "Edit Records") || $_GET['clicked'] == 1)
{
        if($_GET['clicked'] == 1)
        {
            $key=addslashes($_GET['course']);
            $ayear=addslashes($_GET['ayear']);
            $examcat=addslashes($_GET['examcat']);
            $exammarker=addslashes($_GET['exammarker']);
            $examdate=addslashes($_GET['examdate']);

        
        }
        else
        {
            $key=addslashes($_POST['course']);
            $ayear=addslashes($_POST['ayear']);
            $examcat=addslashes($_POST['examcat']);
            $exammarker=addslashes($_POST['exammarker']);
            $examdate=addslashes($_POST['examdate']);
        }
        
 
if($examcat ==0)
{

    echo "<font color='red'>MAKE SURE EXAM CATEGORY IS SELECTED</font><br> <a href='lecturerGradebook.php?coursecode=".$key."&examdate=".$examdate."'>RETURN</a>";
exit;


}

$sqlcheck = "select substatus from submitresult where courseCode ='$key' and acYear=$ayear and category='$examcat'";
$result_check=mysql_query($sqlcheck);
$sqlcheckrows= mysql_num_rows($result_check);


$query_addexam_edit = "
                SELECT DISTINCT student.Name,
								course.CourseCode,
								course.CourseName, 
								examresult.ExamSitting,
								examresult.CourseCode,
								examresult.ExamScore,
								examresult.ExamCategory,
								examresult.ExamDate,
								examresult.Status,
								examresult.RegNo,
								examresult.Comment,
								examresult.Checked,
								programme.ProgrammeName
			   FROM programme, course
					   INNER JOIN examresult ON (course.CourseCode = examresult.CourseCode)
					   INNER JOIN student ON (examresult.RegNo = student.RegNo)
							 WHERE ( 
									  (examresult.CourseCode='$key') 
									 AND  
									  (examresult.AYear='$ayear') 
									 AND 
									  (examresult.ExamCategory = '$examcat')
									  AND 
									  (student.ProgrammeofStudy = programme.ProgrammeCode)
								   )
							ORDER BY student.Name, programme.ProgrammeName, examresult.RegNo ASC";

$query_addexam_add = "
                SELECT DISTINCT student.Name,
				                course.CourseCode,
								course.CourseName, 
								examregister.CourseCode,
								examregister.RegNo,
								examregister.Checked,
								programme.ProgrammeName
			   FROM programme, course
					   INNER JOIN examregister ON (course.CourseCode = examregister.CourseCode)
					   INNER JOIN student ON (examregister.RegNo = student.RegNo)
							 WHERE ( 
									  (examregister.CourseCode='$key') 
									 AND  
									  (examregister.AYear='$ayear') 
									  AND 
									  (student.ProgrammeofStudy = programme.ProgrammeCode)
								   )
							ORDER BY student.Name, programme.ProgrammeName, examregister.RegNo ASC";
}		
else{		
		$key=addslashes($_POST['course']);
		$ayear=addslashes($_POST['ayear']);
		$examcat=addslashes($_POST['examcat']);
		$exammarker=addslashes($_POST['exammarker']);
		$examdate=addslashes($_POST['examdate']);
		$r=addslashes($_GET['r']);
		
        //die("here ");
		#implements session, so that the grade book comes back
		if ($r==1){
		  $key=$_SESSION['key'];
          
		  $ayear=$_SESSION['ayear'];
		  $examcat=$_SESSION['examcat'];
		  $examdate=$_SESSION['examdate'] ;
		  $_POST["view"] = 'Edit Records';
          //die($ayear);
		  $query_addexam_add = "
                SELECT DISTINCT student.Name,
				                course.CourseCode,
								course.CourseName, 
								examregister.CourseCode,
								examregister.RegNo,
								examregister.Checked,
								programme.ProgrammeName
			   FROM programme, course
					   INNER JOIN examregister ON (course.CourseCode = examregister.CourseCode)
					   INNER JOIN student ON (examregister.RegNo = student.RegNo)
							 WHERE ( 
									  (examregister.CourseCode='$key') 
									 AND  
									  (examregister.AYear='$ayear') 
									  AND 
									  (student.ProgrammeofStudy = programme.ProgrammeCode)
								   )
							ORDER BY student.Name, programme.ProgrammeName, examregister.RegNo ASC";
            $query_addexam_edit = "
                SELECT DISTINCT student.Name,
								course.CourseCode,
								course.CourseName, 
								examresult.ExamSitting,
								examresult.CourseCode,
								examresult.ExamScore,
								examresult.ExamCategory,
								examresult.ExamDate,
								examresult.Status,
								examresult.RegNo,
								examresult.Comment,
								examresult.Checked,
								programme.ProgrammeName
			   FROM programme, course
					   INNER JOIN examresult ON (course.CourseCode = examresult.CourseCode)
					   INNER JOIN student ON (examresult.RegNo = student.RegNo)
							 WHERE ( 
									  (examresult.CourseCode='$key') 
									 AND  
									  (examresult.AYear='$ayear') 
									 AND 
									  (examresult.ExamCategory = '$examcat')
									  AND 
									  (student.ProgrammeofStudy = programme.ProgrammeCode)
								   )
							ORDER BY student.Name, programme.ProgrammeName, examresult.RegNo ASC";
		  #print out errors from last gradebook posting
		  if($_SESSION['err']==1){
		  $i=0;
			  while ($i<$_SESSION['max']){
				  ?>
				  <span class="style1">
				  <?php 
				  echo $_SESSION['err'.$i];
				  echo '</span>';
				  #update session to null
				  $_SESSION['err'.$i]='';
				  $i=$i+1;
			  }
		  }
		}else{
			#put data in sessions
			$_SESSION['key'] = $key;
			$_SESSION['ayear'] = $ayear;
			$_SESSION['examcat'] = $examcat;
			$_SESSION['examdate'] = $examdate;
		}
		if(strlen($examdate)<4){
			echo '<b>Warning:-</b> You must specify Exam Date <br> Click on Pick Date command to get calendar <br> if you proceed the system will use this date: ';
			$today = date("Y-m-d");
			echo $today;
			$examdate = $today;
		}
}

$addexam = mysql_query($query_addexam_add, $zalongwa) or die('Problem:'.mysql_error());	
//$addexam = mysql_query($query_addexam_add, $zalongwa) or die('Problem: Check the Add Query!3');
$row_addexam = mysql_fetch_array($addexam);
#get course code and course title
$qcourse = "SELECT CourseCode, CourseName from course WHERE CourseCode = '$key'";
$dbcourse = mysql_query($qcourse);
$row_course = mysql_fetch_assoc($dbcourse);
$coursecode = $row_course['CourseCode'];
$coursename = $row_course['CourseName'];
#display form for updating records


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title><?php echo $key ?></title>
	<style type="text/css">
			body{font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px}
			h1, h2{font-size:20px;}
			.style1 {color: #990000}
	</style>
</head>
<body>
	<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1">

                   <span class="style71"> <span class="style67">EXAMINATION RESULTS  BLACKSHEET FOR, <?php echo $ayear ?></span><br>
 <hr>
            <input name="coursecode" type="hidden" id="coursecode" value="<?php echo $coursecode //$row_addexam['CourseCode']; ?>">
			<input name="exammarker" type="hidden" id="exammarker" value="<?php echo $exammarker; ?>">
			<input name="examcat" type="hidden" id="examcat" value="<?php echo $examcat; ?>">
			<input name="examdate" type="hidden" id="examdate" value="<?php echo $examdate ?>">
			<input name="ayear" type="hidden" id="ayear" value="<?php echo $ayear ?>">
            <input name="lectname" type="hidden" id="lectname" value="<?php echo $name ?>">
			<?php
				#display Exam Category
					$qcat="select Id,Description from examcategory where Id='$examcat'";
					$dbcat=mysql_query($qcat);
					$row_cat=mysql_fetch_array($dbcat);
				#display Exam Marker
					$qmaker="select * from exammarker where Id='$exammarker'";
					$dbmarker=mysql_query($qmaker);
					$row_marker=mysql_fetch_array($dbmarker);
				?>
            <?php // echo "<b> Course: </b>".$row_addexam['CourseCode']; </span>: ?><?php //echo $row_addexam['CourseName']."<br>"; ?>
            <?php echo "<b> Course: </b>".$coursecode; ?></span>: <?php echo $coursename."<br>"; ?>
			<?php echo "<b> Category: </b>".$row_cat['Description']."<br>"; ?>
			<?php echo "<b> Exam Date: </b>".$examdate."<br>"; ?>
			<?php echo "<b> Exam Marker: </b>".$row_marker['Name']."<br>"; ?>
						
            <p>  
			
			<?php 
			if ($privilege == '2') {
			?>   <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
              <tr>
                <td width="4%"><strong>S/No</strong></td>
				<td width="20%" nowrap><strong>Degree Course</strong></td>
                <td width="12%"><strong>Name</strong></td>
                <td width="15%"><strong>RegNo</strong></td>
                <td width="4%"><strong>Score</strong></td>
                <td width="6%"><strong>Sitting </strong></td>
				<td width="4%"><strong>Drop</strong></td>
              </tr>
              <?php $i=1;
             
			  $addexam_add = mysql_query($query_addexam_add, $zalongwa) or die('Problem: Check the Add Query22!');
			  while ($row_addexam = mysql_fetch_assoc($addexam_add)){ 
			    $currentreg = $row_addexam['RegNo'];
			    $currentcourse = $row_addexam['CourseCode'];

			  //check for duplicates
				$qduplicate="SELECT RegNo FROM examresult WHERE RegNo='$currentreg' AND ExamCategory='$examcat' AND CourseCode='$currentcourse' AND AYear='$ayear'";
				$dbduplicate=mysql_query($qduplicate) or die('Problem');
				$total_row = mysql_num_rows($dbduplicate);
				if($total_row < 1){
			  ?>
              <tr >
                <td align="left" valign="middle"><div align="left"> <?php echo $i; ?> </div></td>
				<td align="left" valign="middle" nowrap><?php echo $row_addexam['ProgrammeName']; ?></td>
                <td align="left" valign="middle" nowrap><?php echo $row_addexam['Name']; ?></td>
                <td align="left" valign="middle">
				<input name="RegNo[]" type="hidden" id="RegNo[]" value="<?php echo $row_addexam['RegNo']; ?>">
				<input name="sitting[]" type="hidden" id="sitting[]" value="1">
                <?php $regno=$row_addexam['RegNo']; echo $row_addexam['RegNo']; ?></td>
                <?php
                $score = $row_addexam['ExamScore'];
                
            cha_substate($name, $ayear, $score,$currentcourse,$examcat );
            ?>

                <td><select name="comment[]" id="comment[]">
                  <?php
				#populate stitting combo box
					$sit=$row_addexam['Comment'];
					$qsitting="select Id,Description from sitting where Id='$sit'";
					$dbsitting=mysql_query($qsitting);
					$row_sitting=mysql_fetch_array($dbsitting);
					if($sit==''){
				?>
				<option value="1">First</option>
				<?php }else{ ?>
                  <option value="<?php echo $row_sitting['Id'] ?>"><?php echo $row_sitting['Description'] ?></option>
				  <?php } ?>
                  <option value="1">First</option>
				  <option value="4">Repeater</option>
                  <option value="2">Supp</option>
				  <option value="3">Special</option>
                </select></td>
                
                <td><?php print "<a href=\"lecturerexalresultdelete.php?RegNo=$regno&ayear=$ayear&key=$key\">Drop</a>";?></td>
              </tr>
              <?php $i=$i+1;
			    }
			  }  #ends while add loops
			  #starts edit row display
			  $addexam = mysql_query($query_addexam_edit, $zalongwa) or die('Problem: Check the Add Query34!');
			  while ($row_addexam = mysql_fetch_assoc($addexam)){ ?>
			  
              <tr >
                <td align="left" valign="middle"><div align="left"> <?php echo $i; ?> </div></td>
				<td align="left" valign="middle" nowrap><?php echo $row_addexam['ProgrammeName']; ?></td>
                <td align="left" valign="middle" nowrap><?php echo $row_addexam['Name']; ?></td>
                <td align="left" valign="middle">
				<input name="RegNo[]" type="hidden" id="RegNo[]" value="<?php echo $row_addexam['RegNo']; ?>">
				<input name="sitting[]" type="hidden" id="sitting[]" value="1">
                <?php $regno=$row_addexam['RegNo']; echo $row_addexam['RegNo']; ?></td>
                <td>
                <?php
                $score = $row_addexam['ExamScore'];

                cha_substate($name, $ayear, $score,$currentcourse,$examcat);
                
                ?>
                <td><select name="comment[]" id="comment[]">
                  <?php
				#populate stitting combo box
					$sit=$row_addexam['Comment'];
					$qsitting="select Id,Description from sitting where Id='$sit'";
					$dbsitting=mysql_query($qsitting);
					$row_sitting=mysql_fetch_array($dbsitting);
					if($sit==''){
				?>
				<option value="1">First</option>
				<?php }else{ ?>
                  <option value="<?php echo $row_sitting['Id'] ?>"><?php echo $row_sitting['Description'] ?></option>
				  <?php } ?>
                  <option value="1">First</option>
				  <option value="4">Repeater</option>
                  <option value="2">Supp</option>
				  <option value="3">Special</option>
                </select></td>
                <td><?php print "<a href=\"lecturerexalresultdelete.php?RegNo=$regno&ayear=$ayear&key=$key\">Drop</a>";?></td>
              </tr>
              <?php $i=$i+1;
			   }  #ends while edit row
			   ?>
			  
            </table>
		
		<?php }else{?>
			
            <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" >
              <tr>
                <td width="4%"><strong>S/No</strong></td>
				<td width="30%" nowrap><strong>Degree Course</strong></td>
                <td width="13%"><strong>Name</strong></td>
                <td width="16%"><strong>RegNo</strong></td>
                <td width="4%"><strong>Score</strong></td>
                <td width="6%"><strong>Sitting </strong></td>
              </tr>
              <?php $i=1;
			  $addexam_add = mysql_query($query_addexam_add, $zalongwa) or die('Problem: Check the Add Query44!');
			  while ($row_addexam = mysql_fetch_assoc($addexam_add)){ 
			    $currentreg = $row_addexam['RegNo'];
				$checked = $row_addexam['Checked'];
			    $currentcourse = $row_addexam['CourseCode'];

			  //check for duplicates
				$qduplicate="SELECT RegNo FROM examresult WHERE RegNo='$currentreg' AND ExamCategory='$examcat' AND CourseCode='$currentcourse' AND AYear='$ayear'";
				$dbduplicate=mysql_query($qduplicate) or die('Problem');
				$total_row = mysql_num_rows($dbduplicate);
				if(($total_row < 1) && ($checked==0)){
			  ?>
              <tr>
                <td align="left" valign="middle"><div align="left"> <?php echo $i; ?> </div></td>
				<td align="left" valign="middle" nowrap><?php echo $row_addexam['ProgrammeName']; ?></td>
                <td align="left" valign="middle" nowrap><?php echo $row_addexam['Name']; ?></td>
                <td align="left" valign="middle"><input name="RegNo[]" type="hidden" id="RegNo[]" value="<?php echo $row_addexam['RegNo']; ?>">
                <?php $regno=$row_addexam['RegNo']; echo $row_addexam['RegNo']; ?></td>
                
                <?php
                $score = $row_addexam['ExamScore'];

               cha_substate($name, $ayear, $score,$currentcourse,$examcat );
                ?>
                
               
                
                
				<input name="sitting[]" type="hidden" id="sitting[]" value="1">
                <td><select name="comment[]" id="comment[]" >
                  <?php
				#populate stitting combo box
					$sit=$row_addexam['Comment'];
					$qsitting="select Id,Description from sitting where Id='$sit'";
					$dbsitting=mysql_query($qsitting);
					$row_sitting=mysql_fetch_array($dbsitting);
					if($sit==''){
				?>
				<option value="1">First</option>
				<?php }else{ ?>
                  <option value="<?php echo $row_sitting['Id'] ?>"><?php echo $row_sitting['Description'] ?></option>
				  <?php } ?>
                  <option value="1">First</option>
				  <option value="4">Repeater</option>
                  <option value="2">Supp</option>
				  <option value="3">Special</option>
                </select></td>
              </tr>
              <?php $i=$i+1;
			   }
			  } #ends while add row exam
			  $addexam = mysql_query($query_addexam_edit, $zalongwa) or die('Problem: Check the Edit Query55!');
			  while ($row_addexam = mysql_fetch_assoc($addexam)) { 
			  $checked = $row_addexam['Checked'];
			  if ($checked==0){
			  ?>
              <tr>
                <td align="left" valign="middle"><div align="left"> <?php echo $i; ?> </div></td>
				<td align="left" valign="middle" nowrap><?php echo $row_addexam['ProgrammeName']; ?></td>
                <td align="left" valign="middle" nowrap><?php echo $row_addexam['Name']; ?></td>
                <td align="left" valign="middle"><input name="RegNo[]" type="hidden" id="RegNo[]" value="<?php echo $row_addexam['RegNo']; ?>">
                <?php $regno=$row_addexam['RegNo']; echo $row_addexam['RegNo']; ?></td>
                <?php
                $score = $row_addexam['ExamScore'];

                    cha_substate($name, $ayear, $score,$currentcourse,$examcat);
                ?>
				<input name="sitting[]" type="hidden" id="sitting[]" value="1">
                <td><select name="comment[]" id="comment[]">
                  <?php
				#populate stitting combo box
					$sit=$row_addexam['Comment'];
					$qsitting="select Id,Description from sitting where Id='$sit'";
					$dbsitting=mysql_query($qsitting);
					$row_sitting=mysql_fetch_array($dbsitting);
					if($sit==''){
				?>
				<option value="1">First</option>
				<?php }else{ ?>
                  <option value="<?php echo $row_sitting['Id'] ?>"><?php echo $row_sitting['Description'] ?></option>
				  <?php } ?>
                  <option value="1">First</option>
				  <option value="4">Repeater</option>
                  <option value="2">Supp</option>
				  <option value="3">Special</option>
                </select></td>
              </tr>
              <?php $i=$i+1;
			    } //ends checked ==0
			  }
			  ?>
    
            <?php } ?>
            <tr></tr><tr>
            <?php
            if(!$sqlcheckrows == 1)
            {
            
            ?>
            <td>
            <p>
              <input name="cmdEdit" type="submit" id="cmdEdit" value="Update Records">
              <input type="hidden" name="MM_update" value="form1">
              
            </p><td><td  width ='100%'>
            <p>
            <input name="SUB_Results" type="submit" id="SUB_Results" value="Submit Results to HOD >>">
            <?php } ?>
            
            </p>
            </table> 
</form>
<div id="timestamp"></div> 
<?php
// } 
include('../footer/footer.php');
?>