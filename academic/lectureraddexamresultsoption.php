<?php require_once('../Connections/zalongwa.php'); ?>
<?php
session_start();
header("Cache-control: private"); // IE 6 Fix. 
@$auth_level = $_SESSION['auth_level'];
@$RegNo = $_SESSION['RegNo'];
@$username = $_SESSION['username'];
@$key=$_GET['course'];
@$ayear=$_GET['ayear'];
@$sem=$_GET['sem'];
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	$ExamNo = $_POST['ExamNo'];
	$coursecode = $_POST['coursecode'];
	$RegNo = $_POST['RegNo'];
	$cwk = $_POST['cwk'];
	$exam = $_POST['exam'];
	$total = $_POST['total'];
	$grade = $_POST['grade'];
	$remark = $_POST['remark'];
	$comment = $_POST['comment'];
 		for($c = 0; $c < sizeof($RegNo); $c++) {
			$total[$c] = $cwk[$c] + $exam[$c];
			if($exam[$c]==''){
				$remark[$c]='I';
			}elseif ($cwk[$c]==''){
				$remark[$c]='I';
			}else{
			if ($total[$c] >= 70){
				$grade[$c] = 'A';
				$remark[$c]= 'Pass';
				}
			elseif ($total[$c] >= 60){
				$grade[$c] = 'B+';
				$remark[$c]= 'Pass';
				}
			elseif ($total[$c] >= 50){
				$grade[$c] = 'B';
				$remark[$c]= 'Pass';
				}
			elseif ($total[$c] >= 40){
				$grade[$c] = 'C';
				$remark[$c]= 'Pass';
				}
			elseif ($total[$c] >= 35){
				$grade[$c] = 'D';
				$remark[$c]= 'Fail';
				}
			else{
				$grade[$c] = 'E';
				$remark[$c]= 'Fail';
				}
			}
			$updateSQL = "UPDATE examresult SET ExamNo='$ExamNo[$c]', Coursework = '$cwk[$c]', Exam = '$exam[$c]', 
			Total ='$total[$c]', Grade = '$grade[$c]', Remarks = '$remark[$c]', Comment = '$comment[$c]'  
			WHERE RegNo='$RegNo[$c]' AND CourseCode='$coursecode' AND checked =0";
            //mysql_select_db($database_zalongwa, $zalongwa);
  			$Result1 = mysql_query($updateSQL) or die(mysql_error()); 
			}
}	
//mysql_select_db($database_zalongwa, $zalongwa);
$query_addexam = "SELECT student.Name,        course.CourseCode,        course.CourseName,        examresult.RegNo,  
      examresult.ExamNo,        examresult.CourseCode,        examresult.Coursework,        examresult.Exam,        
	  examresult.Total,        examresult.Grade,        examresult.Remarks,        examresult.AYear,      
	   examresult.checked,        examresult.user,        examresult.SemesterID, examresult.Comment
	   FROM examresult    INNER JOIN course ON (examresult.CourseCode = course.CourseCode)    
	   INNER JOIN student ON (examresult.RegNo = student.RegNo) 
	   WHERE (examresult.CourseCode='$key') AND  (examresult.AYear ='$ayear') AND (examresult.SemesterID = '$sem') ORDER BY examresult.RegNo ASC";
$addexam = mysql_query($query_addexam, $zalongwa) or die(mysql_error());
$row_addexam = mysql_fetch_assoc($addexam);
$totalRows_addexam = mysql_num_rows($addexam);
 
if(!$auth_level){
	echo ("Session Expired, <a href=\"ReLogin.php\"> Click Here<a> to Re-Login");
	echo '<meta http-equiv = "refresh" content ="0; 
						url = ReLogin.php">';
					exit;
}

$browser  =  $_SERVER["HTTP_USER_AGENT"];   
$ip  =  $_SERVER["REMOTE_ADDR"];   

require_once('../Connections/zalongwa.php'); 

$sql="INSERT INTO stats(ip,browser,received,page) VALUES('$ip','$browser',now(),'$username')";   
//$sqldel = "delete from stats where (YEAR(CURRENT_DATE)-YEAR(received))- (RIGHT(CURRENT_DATE,5)<RIGHT(received,5))>1";
$result = mysql_query($sql) or die("Siwezi kuingiza data.<br>" . mysql_error());
mysql_close($zalongwa);
?> 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang=en-US>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="keywords" content="ZALONGWA, zalongwa, Student Information System (SIS), accommodation information system, database system, examination database system, student records system, database system">
<meta name="description" content="School Management Information System, Database System, Juma Lungo Lungo Lungo Lungo, Database System , Student Information System (SIS), Accommodation Record Keeping, Examination Results System, student Normninal Roll Database">
<meta name="rating" content="General">
<meta name="Generator" content="Macromedia Dreamweaver  UtraDev 4.1">
<meta name="authors" content="Juma Hemed Lungo">
<meta name="robots" content="all">

<meta http-equiv="Content-Language" content="pt">
<meta name="VI60_defaultClientScript" content="JavaScript">

<title>OUT Student Information System</title>
<link rel="stylesheet" type="text/css" href="/master.css">

<style type="text/css">
<!--
.style24 {font-size: 12px}
.style25 {
	color: #3366CC;
	font-weight: bold;
}
.style26 {color: #FFFF00}
.style29 {font-size: 12px; font-family: Verdana, Arial, Helvetica, sans-serif; }
.style34 {color: #990000}
.style35 {
	color: #993300;
	font-size: 11px;
}
a:link {
	text-decoration: none;
	color: #000099;
}
a:visited {
	text-decoration: none;
	color: #000099;
}
a:hover {
	text-decoration: underline;
	color: #CC0000;
}
a:active {
	text-decoration: none;
	color: #CC0000;
}
.style42 {color: #000000}
.style47 {font-size: small}
.style55 {font-size: 10px}
.style58 {color: #993300; font-size: 12px; }
.style59 {color: #993300}
.style60 {font-size: 11px; color: #990000;}
.style64 {font-size: 24px}
.style65 {font-size: 18px}
.style67 {font-size: 16}
-->
</style>
</head>

<body bgcolor="#FFFFCC">
<div align="center">
  <center>
    <tr> 
      <td width="100%" height="48"></td>
    </tr>
  </center>
</div>
<div align="center">
  <center>
  </center>
</div>
<div align="center">
  <center>
    <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
      <tr bgcolor="#99CCCC">
        <td height="69" colspan="4" align="center" valign="middle">
        <div align="left"><img src="/images/Nkurumah.gif" width="735" height="69" align="absmiddle"></div></td>
      </tr>
      <tr bgcolor="#FFFFCC" class="normaltext">
        <td height="28" colspan="3" align="center" valign="middle" nowrap>
          <div align="left" class="style24"><span class="style29"><font face="Verdana" color="#FFFF00"><b><font color="006699"><a href="/index.html">Welcome</a> </font></b></font><font face="Verdana"><b><span class="style42">--&gt;</span></b></font></span><font face="Verdana"><b> User Menu</b></font><span class="style29"><font face="Verdana"> </font> </span> </div>
          <form action="/academic/lecturerexamofficerpublishresults.php" method="get" class="style24">
            <div align="right"><span class="style42"><font face="Verdana"><b>Search</b></font></span> <font color="006699" face="Verdana"><b>
              <input type="text" name="content" size="15">
              </b></font><font color="#FFFF00" face="Verdana"><b>
              <input type="submit" value="GO" name="go">
            </b></font> </div>
          </form></td>
      </tr>
      <tr>
        <td height="236" colspan="2" valign="top"><table width="82%" height="61%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFCC" bgcolor="#99CCCC">
            <tr class="style35">
              <td width="27%" height="0" nowrap><div align="center" class="style47"><img src="/images/bd21312_.gif" alt="Your Profile" width="15" height="15"></div></td>
              <td colspan="3" nowrap><div align="left"></div>
                  <div align="left" class="style58"><?php print "<a href=\"lecturerprofile.php?username=$username\">Your Profile</a>";?><span class="style59"> <font face="Verdana">&nbsp;</font> </span></div></td>
            
            <tr class="style35">
              <td height="20" align="right" valign="middle" nowrap><div align="center" class="style47"><img src="/images/bd21312_.gif" alt="Room Application" width="15" height="15"></div></td>
              <td colspan="3" align="left" valign="middle" nowrap class="style35"><span class="style58"><span class="style24"><?php print "<a href=\"lecturercourseregisteredlist.php?username=$username\">Course Register</a>";?></span></span></td>
            </tr>
            <tr class="style35">
              <td height="10" align="right" valign="middle" nowrap><div align="center" class="style47"><img height=15 alt=Room allocation 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></div></td>
              <td colspan="3" align="left" valign="middle" nowrap class="style35"><span class="style60"><span class="style24"><span class="style34"><?php print "<a href=\"lecturercourseregisterednotes.php?username=$username\">Lecture Notes</a>";?> </span></span></span></td>
            </tr>
			<?php if ($auth_level=='editor'){?>
            <tr class="style35">
              <td height="10" align="right" valign="middle" nowrap><span class="style47"><img height=15 alt=Room allocation 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></span></td>
              <td colspan="3" align="left" valign="middle" nowrap class="style35"><span class="style60"><span class="style24"><span class="style34"><?php print "<a href=\"lecturernorminalroll.php?username=$username\">Student Nominal Roll</a>";?></span></span></span></td>
            </tr>
            <?php }else{}?>
            <tr class="style35">
              <td height="20" colspan="2" align="right" valign="middle" nowrap><div align="center" class="style47">
                  <div align="center"><strong><img height=15 alt=Rent fees 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></strong></div>
              </div></td>
              <td height="20" colspan="2" align="right" valign="middle" nowrap class="style35"><div align="left" class="style24"><span class="style34"><?php print "<a href=\"lecturerexamofficergradebook.php?username=$username\">Exam Results</a>";?></span></div></td>
            </tr>
            <tr class="style35">
              <td height="20" colspan="2" align="right" valign="middle" nowrap><div align="center" class="style47"></div></td>
              <td height="20" colspan="2" align="left" valign="middle" nowrap class="style35"><div align="left" class="style24"><span class="style47"><strong><img height=15 alt=Rent fees 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></strong></span><span class="style24"><span class="style34"><?php print "<a href=\"lecturerexamofficergradebook.php?username=$username\">Grade Book</a>";?></span></span></div>                </td>
            </tr>
			<?php //if ($auth_level=='editor'){?>
            <tr class="style35">
              <td height="20" colspan="2" align="right" valign="middle" nowrap><div align="center" class="style47"></div></td>
              <td height="20" colspan="2" align="left" valign="middle" nowrap class="style35"><div align="left" class="style24"><span class="style47"><strong><img height=15 alt=Rent fees 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></strong></span><span class="style24"><span class="style34"><?php print "<a href=\"lecturerexamofficercourseregistartion.php?username=$username\">Course Registration</a>";?></span></span></div>                </td>
            </tr>
			<?php //}else{}?>
            <tr class="style35">
              <td height="20" colspan="2" align="right" valign="middle" nowrap><div align="center" class="style47"></div></td>
              <td height="20" colspan="2" align="left" valign="middle" nowrap class="style35"><div align="left" class="style24"><span class="style47"><strong><img height=15 alt=Rent fees 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></strong></span><span class="style24"><span class="style34"><?php print "<a href=\"lecturerstudentregistration.php?username=$username\">Student Registration</a>";?></span></span></div>                </td>
            </tr>
			<?php if ($auth_level=='editor'){?>
            <tr class="style35">
              <td height="20" colspan="2" align="right" valign="middle" nowrap><div align="center" class="style47"></div></td>
              <td height="20" colspan="2" align="left" valign="top" nowrap class="style35"><div align="left" class="style24"><span class="style47"><strong><img height=15 alt=Rent fees 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></strong></span><span class="style24"><span class="style34"><?php print "<a href=\"lecturercourseallocation.php?username=$username\">Course Allocation</a>";?></span></span></div>                </td>
            </tr>
			<?php }else{}?>
			<?php if ($auth_level=='editor'){?>
			<tr class="style35">
              <td height="20" colspan="2" align="right" valign="middle" nowrap><div align="center" class="style47"></div></td>
              <td height="20" colspan="2" align="left" valign="top" nowrap class="style35"><div align="left" class="style24"><span class="style47"><strong><img height=15 alt=Rent fees 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></strong></span><span class="style24"><span class="style34"><?php print "<a href=\"lecturerprocesstranscript.php?username=$username\">Student Transcripts</a>";?></span></span></div>                </td>
            </tr>
			<?php }else{}?>
            <tr class="style35">
              <td height="20" colspan="2" align="right" valign="middle" nowrap><div align="center" class="style47"><strong><img height=15 alt=Suggestion box 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></strong></div></td>
              <td height="20" colspan="2" align="right" valign="middle" nowrap class="style35"><div align="left" class="style24"><?php print "<a href=\"lecturersuggestionbox.php?username=$username\">Suggestion Box</a>";?> </div></td>
            </tr>
			 <tr class="style35">
            <td height="20" colspan="2" align="right" valign="middle" nowrap><div align="center" class="style47"><strong><img height=15 alt=Suggestion Box 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></strong></div></td>
            <td height="20" colspan="3" align="right" valign="middle" nowrap class="style35"><div align="left" class="style24"><?php print "<a href=\"lecturercheckmessage.php?username=$username\">Check Message</a>";?> </div></td>
            </tr>
            <tr class="style35">
              <td height="20" colspan="2" align="right" valign="middle" nowrap><div align="center" class="style47"><strong><img height=15 alt=Change password network 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></strong></div></td>
              <td width="34%" height="20" align="right" valign="middle" nowrap class="style35"><div align="left" class="style24">
                  <div align="left" class="style34"><?php print "<a href=\"changepassword.php?username=$username\">Change Password</a>";?></div>
              </div></td>
            </tr>
            <tr class="style35">
              <td height="2" colspan="2" nowrap><div align="center"><span class="style55"><span class="style47"><img src="/images/bd21312_.gif" alt="Sign Out" width="15" height="15"> </span></span></div></td>
              <td colspan="2" nowrap class="style35"><div align="left" class="style24"><?php print "<a href=\"signout.php?username=$username\">Sign Out</a>";?></div></td>
            </tr>
        </table>
            <div align="center"></div></td>
        <td width="874" height="236" valign="top"><form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1">
            <p align="center"><span class="style64">UNIVERSITY OF DAR ES SALAAM</span><br>
                <span class="style68 style65">DEPARTMENT OF COMPUTER SCIENCE</span><span class="style65"></span><br>
                <span class="style71"> <span class="style67">EXAMINATION RESULTS FOR <?php echo $row_addexam['SemesterID']; ?> , <?php echo $row_addexam['AYear']; ?></span><br>
                <input name="coursecode" type="hidden" id="coursecode" value="<?php echo $row_addexam['CourseCode']; ?>">
                <?php echo $row_addexam['CourseCode']; ?></span>: <?php echo $row_addexam['CourseName']; ?></p>
            <p>    
            <table width="100%" border="1" align="center">
              <tr>
                <td>S/No</td>
                <td>Name</td>
                <td>RegNo</td>
                <td>ExamNo</td>
                <td>Coursework</td>
                <td>Exam</td>
                <td>Total</td>
                <td>Grade</td>
                <td>Remarks</td>
                <td>Comments</td>
              </tr>
              <?php $i=1;do { ?>
              <tr>
                <td align="left" valign="middle"><div align="left"> <?php echo $i; ?> </div></td>
                <td align="left" valign="middle" nowrap><?php echo $row_addexam['Name']; ?></td>
                <td align="left" valign="middle"><input name="RegNo[]" type="hidden" id="RegNo[]" value="<?php echo $row_addexam['RegNo']; ?>">
                    <?php echo $row_addexam['RegNo']; ?></td>
                <td>
                  <input name="ExamNo[]" type="text" id="ExamNo[]" value="<?php echo $row_addexam['ExamNo']; ?>"></td>
                <td>
                  <input name="cwk[]" type="text" id="cwk[]" value="<?php echo $row_addexam['Coursework']; ?>" size="10"></td>
                <td>
                  <input name="exam[]" type="text" id="exam[]" value="<?php echo $row_addexam['Exam']; ?>" size="5"></td>
                <td><?php echo $row_addexam['Total']; ?>
                    <input name="total[]" type="hidden" id="total[]"></td>
                <td><?php echo $row_addexam['Grade']; ?>
                    <input name="grade[]" type="hidden" id="grade[]"></td>
                <td><?php echo $row_addexam['Remarks']; ?>
                    <input name="remark[]" type="hidden" id="remark[]"></td>
                <td><input name="comment[]" type="text" id="comment[]" value="<?php echo $row_addexam['Comment']; ?>"></td>
              </tr>
              <?php $i=$i+1;} while ($row_addexam = mysql_fetch_assoc($addexam)); ?>
            </table>
            <p></p>
            <p>
              <input type="submit" name="Submit" value="Update Records">
              <input type="hidden" name="MM_update" value="form1">
            </p>
        </form></td>
      </tr>
      <tr>
        <td width="87" height="2"></td>
      </tr>
    </table>
  </center>
</div>

</body>

</html>
<?php
mysql_free_result($addexam);
?>
