<?php require_once('../Connections/zalongwa.php'); ?>
<?php
session_start();
header("Cache-control: private"); // IE 6 Fix. 
@$auth_level = $_SESSION['auth_level'];
@$RegNo = $_SESSION['RegNo'];
@$username = $_SESSION['username'];
$CourseCode = $_GET['CourseCode'];

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO examresult (RegNo, CourseCode, AYear, SemesterID, user, checked) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['lecturer'], "text"),
                       GetSQLValueString($_POST['course'], "text"),
                       GetSQLValueString($_POST['Ayear'], "text"),
                       GetSQLValueString($_POST['semester'], "text"),
					   GetSQLValueString($_POST['user'], "text"),
					   GetSQLValueString($_POST['checked'], "text"));

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($insertSQL, $zalongwa) or die(mysql_error());
  echo '<meta http-equiv = "refresh" content ="0; 
						url = lecturercourseregisteredlist.php">';
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_ExamOfficerGradeBook = 20;
$pageNum_ExamOfficerGradeBook = 0;
if (isset($_GET['pageNum_ExamOfficerGradeBook'])) {
  $pageNum_ExamOfficerGradeBook = $_GET['pageNum_ExamOfficerGradeBook'];
}
$startRow_ExamOfficerGradeBook = $pageNum_ExamOfficerGradeBook * $maxRows_ExamOfficerGradeBook;

mysql_select_db($database_zalongwa, $zalongwa);
$query_ExamOfficerGradeBook = "SELECT CourseCode, AYear, SemesterID FROM examresult";
$query_limit_ExamOfficerGradeBook = sprintf("%s LIMIT %d, %d", $query_ExamOfficerGradeBook, $startRow_ExamOfficerGradeBook, $maxRows_ExamOfficerGradeBook);
$ExamOfficerGradeBook = mysql_query($query_limit_ExamOfficerGradeBook, $zalongwa) or die(mysql_error());
$row_ExamOfficerGradeBook = mysql_fetch_assoc($ExamOfficerGradeBook);

if (isset($_GET['totalRows_ExamOfficerGradeBook'])) {
  $totalRows_ExamOfficerGradeBook = $_GET['totalRows_ExamOfficerGradeBook'];
} else {
  $all_ExamOfficerGradeBook = mysql_query($query_ExamOfficerGradeBook);
  $totalRows_ExamOfficerGradeBook = mysql_num_rows($all_ExamOfficerGradeBook);
}
$totalPages_ExamOfficerGradeBook = ceil($totalRows_ExamOfficerGradeBook/$maxRows_ExamOfficerGradeBook)-1;

mysql_select_db($database_zalongwa, $zalongwa);
$query_Ayear = "SELECT AYear FROM academicyear ORDER BY AYear DESC";
$Ayear = mysql_query($query_Ayear, $zalongwa) or die(mysql_error());
$row_Ayear = mysql_fetch_assoc($Ayear);
$totalRows_Ayear = mysql_num_rows($Ayear);

mysql_select_db($database_zalongwa, $zalongwa);
$query_semester = "SELECT Description FROM terms ORDER BY Semester ASC";
$semester = mysql_query($query_semester, $zalongwa) or die(mysql_error());
$row_semester = mysql_fetch_assoc($semester);
$totalRows_semester = mysql_num_rows($semester);

mysql_select_db($database_zalongwa, $zalongwa);
$query_course = "SELECT CourseCode, CourseName FROM course ORDER BY CourseCode ASC";
$course = mysql_query($query_course, $zalongwa) or die(mysql_error());
$row_course = mysql_fetch_assoc($course);
$totalRows_course = mysql_num_rows($course);

mysql_select_db($database_zalongwa, $zalongwa);
$query_lecturer = "SELECT RegNo FROM student ORDER BY RegNo ASC";
$lecturer = mysql_query($query_lecturer, $zalongwa) or die(mysql_error());
$row_lecturer = mysql_fetch_assoc($lecturer);
$totalRows_lecturer = mysql_num_rows($lecturer);

$queryString_ExamOfficerGradeBook = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_ExamOfficerGradeBook") == false && 
        stristr($param, "totalRows_ExamOfficerGradeBook") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_ExamOfficerGradeBook = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_ExamOfficerGradeBook = sprintf("&totalRows_ExamOfficerGradeBook=%d%s", $totalRows_ExamOfficerGradeBook, $queryString_ExamOfficerGradeBook);
 
if(!$username){
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
.style47 {font-size: small}
.style55 {font-size: 10px}
.style58 {color: #993300; font-size: 12px; }
.style59 {color: #993300}
.style60 {font-size: 11px; color: #990000;}
.style65 {font-size: 18px}
.style66 {color: #FFFFCC}
.style67 {font-size: 12px; color: #FFFFCC; }
.style68 {color: #000000}
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
    <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
      <tr bgcolor="#99CCCC">
        <td height="69" colspan="5" align="center" valign="middle">
          <div align="left"><img src="/images/Nkurumah.gif" width="735" height="69" align="absmiddle"></div></td>
      </tr>
      <tr class="normaltext">
        <td height="28" colspan="3" align="center" valign="middle" nowrap>
        <div align="left" class="style24 style66"><span class="style29"><font face="Verdana"><b><a href="/index.html">Welcome</a> <span class="style68">--&gt;</span></b></font></span><span class="style68"><font face="Verdana"><b> User Menu</b></font></span></div></td>
        <td valign="middle" align="center" colspan="2">
          <form action="/academic/lecturerexamofficerpublishresults.php" method="get" class="style67">
            <div align="right"><font face="Verdana"><b>Search</b></font> <font face="Verdana"><b>
              <input type="text" name="content" size="15">
              <input type="submit" value="GO" name="go">
            </b></font> </div>
        </form></td>
      </tr>
      <tr>
        <td width="120" rowspan="4" valign="top"><table width="82%" height="61%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFCC" bgcolor="#99CCCC">
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
        </table></td>
        <td width="5" height="14"></td>
        <td colspan="3" valign="top">
          <div align="left" class="style65">Course  Registration </div></td>
      </tr>
      <tr>
        <td height="112"></td>
        <td colspan="3" align="left" valign="top"><div align="left">
            
            <p>&nbsp;</p>
            <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
              <table width="59%"  border="1">
                <tr>
                  <td width="17%" nowrap><div align="right">Academic Year: </div></td>
                  <td width="16%"><select name="Ayear" id="Ayear">
                    <?php
do {  
?>
                    <option value="<?php echo $row_Ayear['AYear']?>"><?php echo $row_Ayear['AYear']?></option>
                    <?php
} while ($row_Ayear = mysql_fetch_assoc($Ayear));
  $rows = mysql_num_rows($Ayear);
  if($rows > 0) {
      mysql_data_seek($Ayear, 0);
	  $row_Ayear = mysql_fetch_assoc($Ayear);
  }
?>
                  </select></td>
                  <td width="15%" nowrap><div align="right">Course Code: </div></td>
                  <td width="52%"><input name="course" type="hidden" id="course" value="<?php echo $CourseCode; ?>"><?php echo $CourseCode; ?></td>
                </tr>
                <tr>
                  <td nowrap><div align="right">Semester:</div></td>
                  <td><select name="semester" id="semester">
                    <?php
do {  
?>
                    <option value="<?php echo $row_semester['Description']?>"><?php echo $row_semester['Description']?></option>
                    <?php
} while ($row_semester = mysql_fetch_assoc($semester));
  $rows = mysql_num_rows($semester);
  if($rows > 0) {
      mysql_data_seek($semester, 0);
	  $row_semester = mysql_fetch_assoc($semester);
  }
?>
                  </select></td>
                  <td nowrap><div align="right">
                    <input name="user" type="hidden" id="user" value="<?php echo $username;?>">
                    <input name="checked" type="hidden" id="checked" value="0">
                    Your Username: </div></td>
                  <td><input name="lecturer" type="hidden" id="lecturer" value="<?php echo $username; ?>">
                  <?php echo $username; ?></td>
                </tr>
                <tr>
                  <td colspan="4"><div align="center">
                      <input type="submit" name="Submit" value="Save Records">
                  </div></td>
                </tr>
              </table>
                <input type="hidden" name="MM_insert" value="form1">
            </form>
        </div></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td height="88"></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td width="441"></td>
        <td width="10"></td>
        <td width="396"></td>
      </tr>
      <tr>
        <td><img height="1" width="120" src="/images/spacer.gif"></td>
        <td><img height="1" width="5" src="/images/spacer.gif"></td>
        <td><img height="1" width="168" src="/images/spacer.gif"></td>
        <td></td>
        <td><img height="1" width="10" src="/images/spacer.gif"></td>
      </tr>
    </table>
  </center>
</div>
<div align="center">
  <center>
  </center>
</div>

</body>

</html>
<?php
mysql_free_result($ExamOfficerGradeBook);

mysql_free_result($Ayear);

mysql_free_result($semester);

mysql_free_result($course);

mysql_free_result($lecturer);
?>
