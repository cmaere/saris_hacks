<?php require_once('../Connections/zalongwa.php'); ?>
<?php
mysql_select_db($database_zalongwa, $zalongwa);
$query_courselist = "SELECT CourseCode, CourseName FROM course ORDER BY CourseCode ASC";
$courselist = mysql_query($query_courselist, $zalongwa) or die(mysql_error());
$row_courselist = mysql_fetch_assoc($courselist);
$totalRows_courselist = mysql_num_rows($courselist);
 
session_start();
header("Cache-control: private"); // IE 6 Fix. 
@$auth_level = $_SESSION['auth_level'];
@$RegNo = $_SESSION['RegNo'];
@$username = $_SESSION['username'];

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
$course = $_POST['course'];
$ayear = $_POST['ayear'];
for($i = 0; $i < sizeof($course); $i++) {
/*echo $course[$i].",";
echo $ayear.","; 

tr>
              <td>Computer IP</td>
              <td><div align="center">Web Browser</div></td>
            </tr>
            <?php do { ?>
            <tr>
              <td><?php echo $row_stats['ip']; ?></td>
              <td><?php echo $row_stats['browser']; ?></td>
            </tr>
            <?php } while ($row_stats = mysql_fetch_assoc($stats)); ?>
</table>
*/
	$updateSQL = "SELECT student.Name,
						   examresult.RegNo,
						   examresult.CourseCode,
						   examresult.Grade,
						   examresult.Unit,
						   examresult.AYear,
						   examresult.Remarks,
						   examresult.Status
				FROM examresult INNER JOIN student ON (examresult.RegNo = student.RegNo) 
				WHERE examresult.AYear='$ayear' AND CourseCode = '$course[$i]'";
            mysql_select_db($database_zalongwa, $zalongwa);
  			$result = mysql_query($updateSQL, $zalongwa) or die(mysql_error()); 
			$row_result = mysql_fetch_array($result);
			
			$unit = $row_result['Unit'];
			
			$totalRows_result = mysql_num_rows($result);
			?>
			<table border="1">
			<tr><td></td><td></td><td nowrap>Course Taken: </td>
			 <?php for($i = 0; $i < sizeof($course); $i++) { ?>
			 <td nowrap><?php echo $course[$i]; }?></td>
			   <td nowrap>Total Units</td>
			   <td nowrap>SGP</td>
			   <td nowrap>GPA</td>
			   </tr>
			 
			 <tr><td></td><td></td><td nowrap>Course Units </td>
			 <?php $totalunit = 0; for($i = 0; $i < sizeof($course); $i++) { ?>
			 <td nowrap><?php $totalunit = $totalunit + @$row_result['Unit']; echo @$row_result['Unit']; }?></td>
			   <td nowrap><?php echo $totalunit ; ?></td>
			   <td nowrap>SGP</td>
			   <td nowrap>GPA</td>
			   </tr>
			  
			  <tr>
			 <?php $j=1; $unit=0; do { ?>
			 <td><?php echo $j; $j=$j+1; ?></td><td nowrap><?php echo @$row_result['Name']; ?></td>
			 <td nowrap><?php $regno = $row_result['RegNo']; echo @$row_result['RegNo']; ?></td>
			  <?php $totalunit = 0; for($i = 0; $i < sizeof($course); $i++) { ?>
			 <td nowrap><?php $grade = $row_result['Grade']; echo $grade; }?></td>
			   <td nowrap><?php echo $unit; ?></td>
			   <td nowrap>SGP</td>
			   <td nowrap>GPA</td></tr><tr></tr>
			   <?php } while ($row_result = @mysql_fetch_assoc($result)); ?>
			  </tr>
						
          <?php
			}
}	
if(!$auth_level){
	echo ("Session Expired, <a href=\"ReLogin.php\"> Click Here to Re-Login");
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
.style42 {color: #000000}
.style47 {font-size: small}
.style55 {font-size: 10px}
.style58 {color: #993300; font-size: 12px; }
.style59 {color: #993300}
.style60 {font-size: 11px; color: #990000;}
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
    <table width="100%" height="53%" border="0" cellpadding="0" cellspacing="0">
      <tr bgcolor="#99CCCC"> 
        <td height="69" colspan="5" align="center" valign="middle"> 
        <div align="left"><img src="/images/Nkurumah.gif" width="735" height="69" align="absmiddle"></div></td>
      </tr>
      <tr bgcolor="#FFFFCC" class="normaltext"> 
        <td height="28" colspan="3" align="center" valign="middle" nowrap> 
          <div align="left" class="style24"><span class="style29"><font face="Verdana" color="#FFFF00"><b><font color="006699"><a href="/index.html">Welcome</a> </font></b></font><font face="Verdana"><b><span class="style42">--&gt;</span></b></font></span><span class="style24"><font face="Verdana"><b> User Menu</b></font></span><span class="style29"><font face="Verdana">            
        </font>             </span>                     </div>        </td>
        <td valign="middle" align="center" colspan="2"> 
          <form action="/academic/lecturerexamofficerpublishresults.php" method="get" class="style24">
            <div align="right"><span class="style42"><font face="Verdana"><b>Search</b></font></span> 
              <font color="006699" face="Verdana"><b> 
              <input type="text" name="content" size="15">
              </b></font><font color="#FFFF00" face="Verdana"><b> 
              <input type="submit" value="GO" name="go">
              </b></font> 
              
            </div>
        </form>        </td>
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
              <td height="20" align="right" valign="middle" nowrap><div align="center" class="style47"><img height=15 alt=Room allocation 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></div></td>
              <td colspan="3" align="left" valign="middle" nowrap class="style35"><span class="style60"><span class="style24"><span class="style34"><?php print "<a href=\"lecturercourseregisterednotes.php?username=$username\">Lecture Notes</a>";?> </span></span></span></td>
            </tr>
            
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
            <tr class="style35">
              <td height="20" colspan="2" align="right" valign="middle" nowrap><div align="center" class="style47"></div></td>
              <td height="20" colspan="2" align="left" valign="middle" nowrap class="style35"><div align="left" class="style24"><span class="style47"><strong><img height=15 alt=Rent fees 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></strong></span><span class="style24"><span class="style34"><?php print "<a href=\"lecturerexamofficercourseregistartion.php?username=$username\">Course Registration</a>";?></span></span></div>                </td>
            </tr>
            <tr class="style35">
              <td height="20" colspan="2" align="right" valign="middle" nowrap><div align="center" class="style47"></div></td>
              <td height="20" colspan="2" align="left" valign="middle" nowrap class="style35"><div align="left" class="style24"><span class="style47"><strong><img height=15 alt=Rent fees 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></strong></span><span class="style24"><span class="style34"><?php print "<a href=\"lecturerstudentregistration.php?username=$username\">Student Registration</a>";?></span></span></div>                </td>
            </tr>
            <tr class="style35">
              <td height="20" colspan="2" align="right" valign="middle" nowrap><div align="center" class="style47"></div></td>
              <td height="20" colspan="2" align="left" valign="top" nowrap class="style35"><div align="left" class="style24"><span class="style47"><strong><img height=15 alt=Rent fees 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></strong></span><span class="style24"><span class="style34"><?php print "<a href=\"lecturercourseallocation.php?username=$username\">Course Allocation</a>";?></span></span></div>                </td>
            </tr>
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
        </td>
        <td width="5" height="14"></td>
        <td colspan="3" valign="top">
          <div align="center">Select The Courses You Want To Compile </div></td>
      </tr>
      <tr> 
        <td height="87"></td>
        <td colspan="3"><div align="left">
          <form name="form1" method="post" action="/academic/lecturerselectcourse.php">
            <div align="center">
              <select name="ayear" id="ayear">
                <option value="2003/2004">2003/2004</option>
                <option value="2002/2004">2002/2003</option>
              </select>
              <select name="course[]" size="20" multiple id="course[]">
                  <?php
do {  
?>
                  <option value="<?php echo $row_courselist['CourseCode']?>"><?php echo $row_courselist['CourseCode']?></option>
                  <?php
} while ($row_courselist = mysql_fetch_assoc($courselist));
  $rows = mysql_num_rows($courselist);
  if($rows > 0) {
      mysql_data_seek($courselist, 0);
	  $row_courselist = mysql_fetch_assoc($courselist);
  }
?>
              </select>
              <input type="submit" name="Submit" value="Print Results"> 
              <input name="MM_update" type="hidden" id="MM_update" value="form1">       
              </div>
          </form>
          <p>&nbsp;</p>
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
        <td width="359"></td>
        <td width="9"></td>
        <td width="406"></td>
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
mysql_free_result($courselist);
?>
