<?php require_once('../Connections/zalongwa.php'); ?>
<?php
session_start();
header("Cache-control: private"); // IE 6 Fix. 
@$auth_level = $_SESSION['auth_level'];
@$RegNo = $_SESSION['RegNo'];
@$username = $_SESSION['username'];

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

mysql_select_db($database_zalongwa, $zalongwa);
$query_AcademicYear = "SELECT AYear FROM academicyear ORDER BY AYear DESC";
$AcademicYear = mysql_query($query_AcademicYear, $zalongwa) or die(mysql_error());
$row_AcademicYear = mysql_fetch_assoc($AcademicYear);
$totalRows_AcademicYear = mysql_num_rows($AcademicYear);

mysql_select_db($database_zalongwa, $zalongwa);
$query_course = "SELECT CourseCode, CourseName FROM course ORDER BY CourseCode ASC";
$course = mysql_query($query_course, $zalongwa) or die(mysql_error());
$row_course = mysql_fetch_assoc($course);
$totalRows_course = mysql_num_rows($course);

if(!$username){
	echo ("Session Expired, <a href=\"ReLogin.php\"> Click Here<a> to Re-Login");
	
	echo '<meta http-equiv = "refresh" content ="0; 
	url = ReLogin.php">';
	exit;
}
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
.style61 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style63 {font-size: 12px; color: #990000;}
.style65 {color: #990000; font-weight: bold; }
.style67 {color: #000000}
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
    <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#990000">
      <tr bgcolor="#99CCCC">
        <td height="69" colspan="7" align="center" valign="middle"> <img src="/images/Nkurumah.gif" width="724" height="69" align="left"></td>
      </tr>
      <tr>
        <td width="56" rowspan="5" valign="top">
            <table width="82%" height="61%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFCC" bgcolor="#99CCCC">
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
        <td><form action="/accommodation/housingroomallocationsearchreport.php" method="get" class="style24">
            <div align="right"><span class="style42"><font face="Verdana"><b>Search</b></font></span> 
              <font color="006699" face="Verdana"><b> 
              <input type="text" name="content" size="15">
              </b></font><font color="#FFFF00" face="Verdana"><b> 
              <input type="submit" value="GO" name="go">
            </b></font>            </div>
        </form></td>
        <td bgcolor="#99CCCC">&nbsp;</td>
      </tr>
      <tr>
        <td width="671" valign="top"><div align="left">	
          <form action="/academic/lectureraddexamresults.php" method="POST" name="studentRoomApplication" id="studentRoomApplication">
            <table width="284" border="0">
        <tr>
          <td colspan="2" nowrap><span class="style65">...PRINTING ROOM ALLOCATION REPORT </span> <hr></td>
          </tr>
        <tr>
          <td width="111" nowrap><div align="right"></div></td>
          <td width="157" bordercolor="#ECE9D8" bgcolor="#CCCCCC"><span class="style67">
          </span></td>
        </tr>
        <tr>
          <td nowrap><div align="right">Application Year: </div></td>
          <td bgcolor="#CCCCCC"><select name="AYear" id="select2">
            <?php
do {  
?>
            <option value="<?php echo $row_AcademicYear['AYear']?>"><?php echo $row_AcademicYear['AYear']?></option>
            <?php
} while ($row_AcademicYear = mysql_fetch_assoc($AcademicYear));
  $rows = mysql_num_rows($AcademicYear);
  if($rows > 0) {
      mysql_data_seek($AcademicYear, 0);
	  $row_AcademicYear = mysql_fetch_assoc($AcademicYear);
  }
?>
          </select></td>
        </tr>
        <tr>
          <td nowrap><div align="right"> Course Code:</div></td>
          <td bgcolor="#CCCCCC"><select name="Hall" id="select">
              <?php
do {  
?>  $rows = mysql_num_rows($course);

            <option value="<?php echo $row_course['CourseCode']?>"><?php echo $row_course['CourseCode']?></option>
              <?php
} while ($row_course = mysql_fetch_assoc($course));
  $rows = mysql_num_rows($course);
  if($rows > 0) {
      mysql_data_seek($course, 0);
	  $row_course = mysql_fetch_assoc($course);
  }
?>
          </select></td>
        </tr>
        <tr>
          <td nowrap><div align="right">Click Submit to Save: </div></td>
          <td bgcolor="#CCCCCC"><input type="submit" name="Submit" value="Submit"></td>
        </tr>
      </table>
                    <input type="hidden" name="MM_insert" value="housingRoomApplication">
          </form>
                    <hr>
        </div></td>
        <td width="35" bgcolor="#99CCCC">&nbsp;</td>
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
mysql_free_result($AcademicYear);

mysql_free_result($course);

?>
