<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
    //die($name);
	  
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Examination';
	$szSubSection = 'Faculty Exam Assessment';
	$szTitle = 'Faculty Exam Assessment';
	include('lecturerheader.php');
    
    
    //cha modifications - declaring a function to check submission status

    //die("here");
    function cha_substate($name, $ayear, $score, $code)
    {
    
                
                
               //die("am here now");
                //cha modification
                $sql_lectsubstatus = "select substatus from submitresult_faculty where Lecturer_Name = '$name' and acYear = '$ayear' and courseCode = '$code'";
                
                //die($sql_lectsubstatus);
				$qsubstatus=mysql_query($sql_lectsubstatus) or die('cha q Problem');
				$total_row = mysql_num_rows($qsubstatus);
                //die("cha===".$total_row);
                
                // die($score);
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


if ((isset($_POST["SUB_Results"])) ) 
{

$ayear=addslashes($_POST['ayear']);
$lectname=addslashes($_POST['lectname']);
$course=addslashes($_POST['coursecode']);
$hod=addslashes($_POST['hod']);
$cat=addslashes($_POST['examcat']);



 $sql_dept = "select dept from security where FullName = '$lectname'";
                
				$qdept=mysql_query($sql_dept) or die('cha q Problem');
				$row_dept = mysql_fetch_assoc($qdept);
                
                    $dept = $row_dept["dept"];
                

    $subsql= "INSERT INTO submitresult_faculty(acYear,Lecturer_Name,HODapprove,substatus,courseCode,Dept,category) VALUES('$ayear','$lectname','$hod','1', '$course','$dept','$cat')";
    mysql_query($subsql);
    
    



}


if (isset($_POST["cmdEdit"]))
{
//save contents to database
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
	for($c = 0; $c < $max; $c++) {
	$score1= $cwk[$c];
    
	$score2 = floatval($cwk[$c]);
    //die($score1."here".$RegNo[$c].$score2);
				$updateSQL = "UPDATE examresult SET ExamScore = '$cwk[$c]'  WHERE AYear = '$ayear' AND CourseCode = '$key' AND RegNo = '$RegNo[$c]'";
						 
					mysql_select_db($database_zalongwa, $zalongwa);
                    
                    //die($score2);
			
										if ($score1 >100){
										   $_SESSION['err'.$c] =  $score1.' is invalid exam score entry to cha: '.$RegNo[$c].'<br>';
										   $err = 1;
					                       }else{
					                       mysql_query($updateSQL);
					                     }
                                         
                                         
                                         
                    }
                    
                    $_SESSION['err']=$err;
			echo "<br>Database updated successfully";
            //      die($key."am here");
			#open data entry form again
	 	  echo "<meta http-equiv='refresh' content='0;URL=deptassess.php?r=1&code=$key'>";	   
                    
                    }







//end cha modification
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
//save contents to database
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
	for($c = 0; $c < $max; $c++) {
	$score1= $cwk[$c];
    
	$score2 = floatval($cwk[$c]);
    //die($score1."here".$RegNo[$c].$score2);
				$updateSQL = "REPLACE INTO examresult(AYear, Marker, CourseCode, ExamCategory, ExamDate, ExamSitting, Recorder, RecordDate, RegNo, ExamScore, Status, Comment)
						 VALUES ('$ayear', '$exammarker', '$key', '$examcat', '$examdate', '$remark[$c]', '$username', now(), '$RegNo[$c]', '$cwk[$c]', '1', '$comment[$c]')";
					mysql_select_db($database_zalongwa, $zalongwa);
                    
                    //die($score2);
				switch ($examcat) {
									case 4:
										if (("$score2"!="$score1" && "$score1" <> "") || $score1>100){
										   $_SESSION['err'.$c] =  $score1.' is invalid exam score entry to cha: '.$RegNo[$c].'<br>';
										   $err = 1;
					                       }else{
					                       $Result1 = mysql_query($updateSQL);
					                     }
										break;
									case 5:
										if (("$score2"!="$score1" && "$score1" <> "") || $score1>100){
										  $_SESSION['err'.$c] =  $score1.' is invalid exam score entry to: '.$RegNo[$c].'<br>';
										   $err = 1;
					                       }else{
					                       $Result1 = mysql_query($updateSQL);
					                     }
										break;
									case 6:
										if (("$score2"!="$score1" && "$score1" <> "") || $score1>100){
										  $_SESSION['err'.$c] = $score1.' is invalid exam score entry to: '.$RegNo[$c].'<br>';
										   $err = 1;
					                       }else{
					                       $Result1 = mysql_query($updateSQL);
					                     }
										break;
									case 7:
										if (("$score2"!="$score1" && "$score1" <> "") || $score1>100){
										   $_SESSION['err'.$c] =  $score1.' is invalid exam score entry to: '.$RegNo[$c].'<br>';
										   $err = 1;
					                       }else{
					                       $Result1 = mysql_query($updateSQL);
					                     }
										break;
									case 8:
										if (("$score2"!="$score1" && "$score1" <> "") || $score1>100){
										   $_SESSION['err'.$c] =  $score1.' is invalid exam score entry to: '.$RegNo[$c].'<br>';
										   $err = 1;
					                       }else{
					                       $Result1 = mysql_query($updateSQL);
					                     }
										break;
									case 9:
										if (("$score2"!="$score1" && "$score1" <> "") || $score1>100){
										   $_SESSION['err'.$c] =  $score1.' is invalid exam score entry to: '.$RegNo[$c].'<br>';
										   $err = 1;
					                       }else{
					                       $Result1 = mysql_query($updateSQL);
					                     }
										break;
									case 10:
										if (("$score2"!="$score1" && "$score1" <> "") || $score1>100){
										   $_SESSION['err'.$c] =  $score1.' is invalid exam score entry to: '.$RegNo[$c].'<br>';
										   $err = 1;
					                       }else{
					                       $Result1 = mysql_query($updateSQL);
					                     }
										break;
									default:
									        $_SESSION['err'.$c] =  $score1.' is invalid exam score entry to: '.$RegNo[$c].'<br>';
										   $err = 1;
								}
				}//close for loop
				#session error
				$_SESSION['err']=$err;
			echo "<br>Database updated successfully";
			#open data entry form again
	 	   echo '<meta http-equiv = "refresh" content ="0; 
				 url = deptassess.php?r=1">';
	exit;
}else{		
		$key=addslashes($_POST['course']);
		$ayear=addslashes($_POST['ayear']);
		$examcat=addslashes($_POST['examcat']);
        //die($examcat);
		$exammarker=addslashes($_POST['exammarker']);
		$examdate=addslashes($_POST['examdate']);
		$r=addslashes($_GET['r']);
        
        
        //die($r);
		
		#implements session, so that the grade book comes back
		if ($r==1){
		  $key=addslashes($_GET['code']);
          //die($key."here o");
		  $ayear=$_SESSION['ayear'];
		  $examcat=$_SESSION['examcat'];
		  $examdate=$_SESSION['examdate'] ;
		  $_POST["view"] = 'Edit Records';
		  
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
        
        //cha modification
        $sql_ex = "select ExamDate from examresult where CourseCode = '$key' and AYear = '$ayear'";
                
				$qex=mysql_query($sql_ex) or die('cha q Problem');
				$row_ex = mysql_fetch_assoc($qex);
                
                    $examdate = $row_ex["ExamDate"];
        
		//if(strlen($examdate)<4){
			//echo '<b>Warning:-</b> You must specify Exam Date <br> Click on Pick Date command to get calendar <br> if you proceed the system will use this date: ';
			//$today = date("Y-m-d");
			//echo $today;
			//$examdate = $today;
		//}
}




if((isset($_POST["view"])) && ($_POST["view"] == "View Result Report") || ($r==1)) {

$p = $_POST['course'];


$query_coursecode = "
		SELECT DISTINCT prefix,ProgrammeCode,ProgrammeYear
		FROM program_year
	
		 WHERE ProgrammeName = '$p'";


$resultb=mysql_query($query_coursecode);
while ($line = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    $class= $line["prefix"];
    $code= $line["ProgrammeCode"];
    $year=$line["ProgrammeYear"];
    
    
}
//$class=$row_coursecode['prefix'];
//die($class);
include('testr.php');

 
}		

if((isset($_POST["pdf"])) && ($_POST["pdf"] == "PDF Result Report")) {
//die("here");
$p = $_POST['course'];
//die($p);

$query_coursecode = "
		SELECT DISTINCT prefix,ProgrammeCode
		FROM program_year
	
		 WHERE ProgrammeName = '$p'";


$resultb=mysql_query($query_coursecode);
while ($line = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
{
                                          
    //$reg= $line["RegNo"];
    $class= $line["prefix"];
    $code= $line["ProgrammeCode"];
    //die($class);
    
    
}
//$class=$row_coursecode['prefix'];
echo "<meta http-equiv='refresh' content='0;URL=pdf.php?&class=$class&code=$code'>";
 
}			



$addexam = mysql_query($query_addexam_add, $zalongwa) or die('');

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
            <input name="hod" type="hidden" id="hod" value="<?php echo $name; ?>">
            
			<?php
            
            
            $qcat2="select category from submitresult where acYear='$ayear' and courseCode='$coursecode'";
					$dbcat2=mysql_query($qcat2);
					$row_cat2=mysql_fetch_array($dbcat2);
                    $cat_f = $row_cat2['category'];
            
				#display Exam Category
					$qcat="select Id,Description from examcategory where Id='$cat_f'";
					$dbcat=mysql_query($qcat);
					$row_cat=mysql_fetch_array($dbcat);
				#display Exam Marker
					$qmaker="select Lecturer_Name from submitresult where courseCode='$coursecode'";
					$dbmarker=mysql_query($qmaker);
					$row_marker=mysql_fetch_array($dbmarker);
				?>
                
                <input name="lectname" type="hidden" id="lectname" value="<?php echo $row_marker['Lecturer_Name']; ?>">
                
            <?php // echo "<b> Course: </b>".$row_addexam['CourseCode']; </span>: ?><?php //echo $row_addexam['CourseName']."<br>"; ?>
            <?php echo "<b> Course: </b>".$coursecode; ?></span>: <?php echo $coursename."<br>"; ?>
			<?php echo "<b> Category: </b>".$row_cat['Description']."<br>"; ?>
			<?php echo "<b> Exam Date: </b>".$examdate."<br>"; ?>
			<?php echo "<b> Exam Marker: </b>".$row_marker['Lecturer_Name']."<br>"; ?>
						
            <p>  
			
			<?php 
            $lect_assesname = $row_marker['Lecturer_Name'];
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
			  $addexam_add = mysql_query($query_addexam_add, $zalongwa) or die('Problem: Check the Add Queryc!');
			  while ($row_addexam = mysql_fetch_assoc($addexam_add)){ 
			    $currentreg = $row_addexam['RegNo'];
			    $currentcourse = $row_addexam['CourseCode'];

			  //check for duplicates
				$qduplicate="SELECT RegNo FROM examresult WHERE RegNo='$currentreg' AND ExamCategory='$examcat' AND CourseCode='$currentcourse'";
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
                //die("cha".$score);
            cha_substate($lect_assesname, $ayear, $score,$currentcourse );
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
			  $addexam = mysql_query($query_addexam_edit, $zalongwa) or die('Problem: Check the Add Queryw!');
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
                     //die("cha".$score);
                cha_substate($name, $ayear, $score,$currentcourse );
                
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
			
            <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
              <tr >
                <td width="4%"><strong>S/No</strong></td>
				<td width="20%" nowrap><strong>Degree Course</strong></td>
                <td width="13%"><strong>Name</strong></td>
                <td width="16%"><strong>RegNo</strong></td>
                <td width="4%"><strong>Score</strong></td>
                <td width="6%"><strong>Sitting </strong></td>
              </tr>
              <?php $i=1;
			  $addexam_add = mysql_query($query_addexam_edit, $zalongwa) or die('Problem: Check the Add Query2!');
			  while ($row_addexam = mysql_fetch_assoc($addexam_add)){ 
			    $currentreg = $row_addexam['RegNo'];
				$checked = $row_addexam['Checked'];
			    $currentcourse = $row_addexam['CourseCode'];

			  //check for duplicates
				$qduplicate="SELECT RegNo FROM examresult WHERE RegNo='$currentreg' AND ExamCategory='$examcat' AND CourseCode='$currentcourse'";
				$dbduplicate=mysql_query($qduplicate) or die('Problem');
				$total_row = mysql_num_rows($dbduplicate);
				if(($total_row < 1) && ($checked==0)){
			  ?>
              <tr >
                <td align="left" valign="middle"><div align="left"> <?php echo $i; ?> </div></td>
				<td align="left" valign="middle" nowrap><?php echo $row_addexam['ProgrammeName']; ?></td>
                <td align="left" valign="middle" nowrap><?php echo $row_addexam['Name']; ?></td>
                <td align="left" valign="middle"><input name="RegNo[]" type="hidden" id="RegNo[]" value="<?php echo $row_addexam['RegNo']; ?>">
                <?php $regno=$row_addexam['RegNo']; echo $row_addexam['RegNo']; ?></td>
                
                <?php
                $score = $row_addexam['ExamScore'];
                 //die("cha".$score);

               cha_substate($lect_assesname, $ayear, $score,$currentcourse );
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
			   }
			  } #ends while add row exam
			  $addexam = mysql_query($query_addexam_edit, $zalongwa) or die('Problem: Check the Add Queryb!');
			  while ($row_addexam = mysql_fetch_assoc($addexam)) { 
			  $checked = $row_addexam['Checked'];
			  if ($checked==0){
			  ?>
              <tr >
                <td align="left" valign="middle"><div align="left"> <?php echo $i; ?> </div></td>
				<td align="left" valign="middle" nowrap><?php echo $row_addexam['ProgrammeName']; ?></td>
                <td align="left" valign="middle" nowrap><?php echo $row_addexam['Name']; ?></td>
                <td align="left" valign="middle"><input name="RegNo[]" type="hidden" id="RegNo[]" value="<?php echo $row_addexam['RegNo']; ?>">
                <?php $regno=$row_addexam['RegNo']; echo $row_addexam['RegNo']; ?></td>
                <?php
                $score = $row_addexam['ExamScore'];
                //die($score);

                    cha_substate($lect_assesname, $ayear, $score, $currentcourse );
                   
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
    </table> 
            <?php } ?>
            <p>
              <input name="cmdEdit" type="submit" id="cmdEdit" value="Update Records">
              
              
            </p>
            <p>
            
            <input name="SUB_Results" type="submit" id="SUB_Results" value="Submit Exam Results to DOF >>">
          
            
            </p>
</form>
<div id="timestamp"></div> 
<?php
// } 
include('../footer/footer.php');
?>