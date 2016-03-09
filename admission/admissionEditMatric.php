<?php 
session_start();
header("Cache-control: private"); // IE 6 Fix. 
@$auth_level = $_SESSION['auth_level'];
@$RegNo = $_SESSION['RegNo'];
@$username = $_SESSION['username'];

/**
if(!$username){
	echo ("Session Expired, <a href=\"ReLogin.php\"> Click Here<a> to Re-Login");
	
	echo '<meta http-equiv = "refresh" content ="0; 
	url = ReLogin.php">';
	exit;
} */
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
<link rel="stylesheet" type="text/css" href="master.css">

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
.style61 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style29 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.style42 {
	color: #000000;
	font-weight: bold;
}
.style64 {font-weight: bold; font-size: 12px;}
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
        <td height="69" colspan="7" align="center" valign="middle"> <img src="images/Nkurumah.gif" width="724" height="69" align="left"></td>
      </tr>
      <tr>
        <td width="55" rowspan="5" valign="top">
            <table width="82%" height="61%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFCC" bgcolor="#99CCCC">
            <tr class="style35">
              <td width="24%" height="0" nowrap><div align="center" class="style47"><img src="images/bd21312_.gif" alt="Your Profile" width="15" height="15"></div></td>
              <td colspan="3" nowrap><div align="left"></div>
                  <div align="left" class="style58"><?php print "<a href=\"admissionprofile.php?username=$username\">My Profile</a>";?><span class="style59"> <font face="Verdana">&nbsp;</font> </span></div></td>
            
            <tr class="style35">
              <td height="20" align="right" valign="middle" nowrap><div align="center" class="style47"><img src="images/bd21312_.gif" alt="Room Application" width="15" height="15"></div></td>
              <td colspan="3" align="left" valign="middle" nowrap class="style35"><span class="style58"><span class="style24"><?php print "<a href=\"admissionpolicy.php?username=$username\">Admission Process</a>";?></span></span></td>
            </tr>
            <tr class="style35">
              <td height="10" align="right" valign="middle" nowrap><div align="center" class="style47"></div></td>
              <td width="18%" align="left" valign="middle" nowrap class="style35"><span class="style60"><span class="style24"><span class="style34"> </span></span></span></td>
              <td colspan="2" align="left" valign="middle" nowrap class="style35"><span class="style47"><img height=15 alt=Room allocation 
                  hspace=4 src="images/bd21312_.gif" width=15 
                  vspace=5 border=0></span><span class="style60"><span class="style24"><span class="style34"><?php print "<a href=\"admissionpolicy.php?username=$username\">Admission Policy</a>";?></span></span></span></td>
            </tr>
			<tr class="style35">
              <td height="10" align="right" valign="middle" nowrap>&nbsp;</td>
              <td align="left" valign="middle" nowrap class="style35"><span class="style60"><span class="style24"></span></span></td>
              <td colspan="2" align="left" valign="middle" nowrap class="style35"><span class="style47"><img height=15 alt=Room allocation 
                  hspace=4 src="images/bd21312_.gif" width=15 
                  vspace=5 border=0></span><span class="style60"><span class="style24"><span class="style34"><?php print "<a href=\"admissionApplicationForm.php?username=$username\">Application Form</a>";?></span></span></span></td>
            </tr>
			<tr class="style35">
              <td height="10" align="right" valign="middle" nowrap>&nbsp;</td>
              <td align="left" valign="middle" nowrap class="style35"><span class="style60"><span class="style24"></span></span></td>
              <td colspan="2" align="left" valign="middle" nowrap class="style35"><span class="style47"><img height=15 alt=Room allocation 
                  hspace=4 src="images/bd21312_.gif" width=15 
                  vspace=5 border=0></span><span class="style60"><span class="style24"><span class="style34"><?php print "<a href=\"admissionApplicationList.php?username=$username\">Application List</a>";?></span></span></span></td>
            </tr>
			<tr class="style35">
              <td height="10" align="right" valign="middle" nowrap>&nbsp;</td>
              <td align="left" valign="middle" nowrap class="style35"><span class="style60"><span class="style24"></span></span></td>
              <td colspan="2" align="left" valign="middle" nowrap class="style35"><span class="style47"><img height=15 alt=Room allocation 
                  hspace=4 src="images/bd21312_.gif" width=15 
                  vspace=5 border=0></span><span class="style60"><span class="style24"><span class="style34"><?php print "<a href=\"admissionRecommendedList.php?username=$username\">Recommended List</a>";?></span></span></span></td>
            </tr>
			<tr class="style35">
              <td height="10" align="right" valign="middle" nowrap>&nbsp;</td>
              <td align="left" valign="middle" nowrap class="style35"><span class="style60"><span class="style24"></span></span></td>
              <td colspan="2" align="left" valign="middle" nowrap class="style35"><span class="style47"><img height=15 alt=Room allocation 
                  hspace=4 src="images/bd21312_.gif" width=15 
                  vspace=5 border=0></span><span class="style60"><span class="style24"><span class="style34"><?php print "<a href=\"admissionSearchApplication.php?username=$username\">Search Application</a>";?></span></span></span></td>
            </tr>
			<tr class="style35">
              <td height="10" align="right" valign="middle" nowrap><span class="style47"><img height=15 alt=Room allocation 
                  hspace=4 src="images/bd21312_.gif" width=15 
                  vspace=5 border=0></span></td>
              <td colspan="3" align="left" valign="middle" nowrap class="style35"><span class="style60"><span class="style24"><span class="style34"><?php print "<a href=\"admissionRegistrationForm.php?username=$username\">Nominal Roll</a>";?></span></span></span><span class="style60"><span class="style24"></span></span></td>
            </tr>
			<tr class="style35">
              <td height="10" align="right" valign="middle" nowrap>&nbsp;</td>
              <td align="left" valign="middle" nowrap class="style35"><span class="style60"><span class="style24"></span></span></td>
              <td colspan="2" align="left" valign="middle" nowrap class="style35"><span class="style47"><img height=15 alt=Room allocation 
                  hspace=4 src="images/bd21312_.gif" width=15 
                  vspace=5 border=0></span><span class="style60"><span class="style24"><span class="style34"><?php print "<a href=\"admissionRegistrationForm.php?username=$username\">Registration Form</a>";?></span></span></span></td>
            </tr>
			<tr class="style35">
              <td height="10" align="right" valign="middle" nowrap>&nbsp;</td>
              <td align="left" valign="middle" nowrap class="style35"><span class="style60"><span class="style24"></span></span></td>
              <td colspan="2" align="left" valign="middle" nowrap class="style35"><span class="style47"><img height=15 alt=Room allocation 
                  hspace=4 src="images/bd21312_.gif" width=15 
                  vspace=5 border=0></span><span class="style60"><span class="style24"><span class="style34"><?php print "<a href=\"admissionNominalRoll.php?username=$username\">Nominal Roll</a>";?></span></span></span></td>
            </tr>
			<tr class="style35">
              <td height="10" align="right" valign="middle" nowrap>&nbsp;</td>
              <td align="left" valign="middle" nowrap class="style35"><span class="style60"><span class="style24"></span></span></td>
              <td colspan="2" align="left" valign="middle" nowrap class="style35"><span class="style47"><img height=15 alt=Room allocation 
                  hspace=4 src="images/bd21312_.gif" width=15 
                  vspace=5 border=0></span><span class="style60"><span class="style24"><span class="style34"><?php print "<a href=\"admissionStudentStatistics.php?username=$username\">Student Statistics</a>";?></span></span></span></td>
            </tr>
			<tr class="style35">
              <td height="10" align="right" valign="middle" nowrap>&nbsp;</td>
              <td align="left" valign="middle" nowrap class="style35"><span class="style60"><span class="style24"></span></span></td>
              <td colspan="2" align="left" valign="middle" nowrap class="style35"><span class="style47"><img height=15 alt=Room allocation 
                  hspace=4 src="images/bd21312_.gif" width=15 
                  vspace=5 border=0></span><span class="style60"><span class="style24"><span class="style34"><?php print "<a href=\"lecturernorminalroll.php?username=$username\">Search Student</a>";?></span></span></span></td>
            </tr>
			
              <td height="20" colspan="2" align="right" valign="middle" nowrap><div align="center" class="style47"><strong><img height=15 alt=Suggestion box 
                  hspace=4 src="images/bd21312_.gif" width=15 
                  vspace=5 border=0></strong></div></td>
              <td height="20" colspan="2" align="right" valign="middle" nowrap class="style35"><div align="left" class="style24"><?php print "<a href=\"admissionSuggestionBox.php?username=$username\">Suggestion Box</a>";?> </div></td>
            </tr>
			 <tr class="style35">
            <td height="20" colspan="2" align="right" valign="middle" nowrap><div align="center" class="style47"><strong><img height=15 alt=Suggestion Box 
                  hspace=4 src="images/bd21312_.gif" width=15 
                  vspace=5 border=0></strong></div></td>
            <td height="20" colspan="3" align="right" valign="middle" nowrap class="style35"><div align="left" class="style24"><?php print "<a href=\"admissionCheckMessage.php?username=$username\">Check Message</a>";?> </div></td>
            </tr>
            <tr class="style35">
              <td height="20" colspan="2" align="right" valign="middle" nowrap><div align="center" class="style47"><strong><img height=15 alt=Change password network 
                  hspace=4 src="images/bd21312_.gif" width=15 
                  vspace=5 border=0></strong></div></td>
              <td width="24%" height="20" align="right" valign="middle" nowrap class="style35"><div align="left" class="style24">
                  <div align="left" class="style34"><?php print "<a href=\"changepassword.php?username=$username\">Change Password</a>";?></div>
              </div></td>
            </tr>
            <tr class="style35">
              <td height="2" colspan="2" nowrap><div align="center"><span class="style55"><span class="style47"><img src="images/bd21312_.gif" alt="Sign Out" width="15" height="15"> </span></span></div></td>
              <td colspan="2" nowrap class="style35"><div align="left" class="style24"><?php print "<a href=\"signout.php?username=$username\">Sign Out</a>";?></div></td>
            </tr>
        </table></td>
        <td width="672" rowspan="2" align="left" valign="top">
	<?php 
	// include the Globals (admin version)
	//include($_SERVER['DOCUMENT_ROOT'].'/admin/config.inc.php');
	require_once('Connections/zalongwa.php'); 
	// include Staff Module
	//include('../config.inc.php');
	include('lib/staff_functions.php');
	include('lib/gt_functions.php');
	//include('../../rtf_form/lib/rtf_functions.php');

	# security
	//auth_checkgroup_and_exit('Admissions Admin');
	
	# include the header
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Public Content';
	$szTitle = 'Matriculation Information';
	$szSubSection = 'Admissions Manager';
	$additionalStyleSheet = '../general.css';
	//include("$szRootPath/lookfeel/header_admin.php");
	
	$intApplicantID = $_GET["intApplicantID"];
	$intMatricID = $_GET['intMatricID'];
	
	$arrInfos = gt_get_row('matric_info','WHERE intApplicantID='.$_GET['intApplicantID']);
	
	# does this information need to be updated?
	if (isset($_POST['action']) && ($_POST['action'] == "update"))
	{
		gt_debug($_POST,"POST variables");
		
		$arrMatric = array();
		$arrMatric = $_POST;
		
		gt_debug($arrMatric,"Matriculation to be updated by POST");
		
		// update the item in the database, and update our item array
		$arrMatric = gt_add_update_row($arrMatric,'matric_info','WHERE intMatricID='.$_GET['intMatricID']);

		gt_debug($arrMatric,"Updated Matriculation Information");
		
		// update intResultID variable
		$intMatricID = $arrMatric['intMatricID'];
	}

	if($intMatricID == 0)
	{
		// create completely new result
		// build blank array
		$arrMatric = array();

		// update the intApplicantID

		$arrMatric['intMatricID']=0;
		
		$arrMatric['intApplicantID'] = '';	
		$arrMatric['intCentreID'] = '';
		$arrMatric['intSubject1'] = '';
		$arrMatric['intSubject2'] = '';
		$arrMatric['intSubject3'] = '';
	}
	else
	{
		// get the matriculation information out of the database
		$arrMatric = gt_get_row('matric_info', 'WHERE intMatricID='.$intMatricID);
	}
	
?>

<!--	form validator	-->
<script language="JavaScript" src="../../news/admin/gen_validatorv2.js" type="text/javascript"></script>

To update the details of this applicant, fill in the boxes below with the correct information and press the update button.<br>
<br>
<?php if (isset($_POST['action']) && ($_POST['action'] == "update"))
{
		echo "<div style=\"padding:5px;border:1px solid #6666CC\">";
		echo "<b><center>Applicant Information updated</center></b>";
		echo "</div><br>";
}

?>		
<table cellspacing="1" cellpadding="6" border="0" width=100% bgcolor="#C7D6E6">

    <form name="staff" method="post" action="#">
		<input name="intApplicantID" id="intApplicantID" type="Hidden" value="<?php echo $_GET['intApplicantID']?>">
			<tr>
				<td align=right>Field</td>
				<td>Value</td>
			</tr>
  
              <TR bgcolor="#ffffff">
                <TD align=right valign=top>Examination Centre</TD>
                <TD>
                  <select name="intCentreID" id="intCentreID" class="vform">
				  	<option value="0">[Select Exam Centre]</option>
					<?php $arrCentres = gt_get_rows('matric_centre','intCentreID',0,0,'');
		
						foreach($arrCentres as $arrCentre)
						{
					?>
							<option value="<?php echo $arrCentre['intCentreID']?>" <?php if ($arrInfos['intCentreID']==$arrCentre['intCentreID']){ echo "selected";}?>><?php echo $arrCentre['szCentre']?></option>
					<?php }
					?>
				  </select>
                </TD>
              </TR>
 
              <TR bgcolor="#ffffff">
                <TD align=right valign=top>First Subject</TD>
                  <TD>
					<select name="intSubject1" id="intSubject1" class="vform">
					  	<option value="0">[Select Subject 1]</option>
						<?php $arrSubjects = gt_get_rows('acsee_subjects','intSubjectID',0,0,'');
			
							foreach($arrSubjects as $arrSubject)
							{
						?>
								<option value="<?php echo $arrSubject['intSubjectID']?>" <?php if ($arrInfos['intSubject1']==$arrSubject['intSubjectID']){ echo "selected";}?>><?php echo $arrSubject['szSubject']?></option>
						<?php }
						?>
					  </select>
                </TD>
              </TR>
			  
			  
			  <TR bgcolor="#ffffff">
			  	<TD align=right valign=top>Second Subject</TD>
                <TD>
					<select name="intSubject2" id="intSubject2" class="vform">
					  	<option value="0">[Select Subject 2]</option>
						<?php foreach($arrSubjects as $arrSubject)
							{
						?>
								<option value="<?php echo $arrSubject['intSubjectID']?>" <?php if ($arrInfos['intSubject2']==$arrSubject['intSubjectID']){ echo "selected";}?>><?php echo $arrSubject['szSubject']?></option>
						<?php }
						?>
					  </select>
                </TD>
              </TR>
			  
			  
			  <TR bgcolor="#ffffff">
                <TD align=right valign=top>Third Subject</TD>
                  <TD>
					<select name="intSubject3" id="intSubject3" class="vform">
					  	<option value="0">[Select Subject 3]</option>
						<?php foreach($arrSubjects as $arrSubject)
							{
						?>
								<option value="<?php echo $arrSubject['intSubjectID']?>" <?php if ($arrInfos['intSubject3']==$arrSubject['intSubjectID']){ echo "selected";}?>><?php echo $arrSubject['szSubject']?></option>
						<?php }
						?>
					  </select>
                </TD>
              </TR>
			  
			  
			  <tr>
				<td>&nbsp;</td>
				<td><input  class="vform" type="submit" name="action" value="update" onClick="return formValidator()"></td>
			  </tr>
    <!--</form>-->
	</table>
	
<br>

<table cellspacing="2" cellpadding="2" border="0">
<tr>
    <td><a href="index.php"><img src="images/back.gif" width="15" height="15" alt="" border="0"></a></td>
    <td><b><a href="edit_item.php?intApplicantID=<?php echo $_GET['intApplicantID']?>">Back to applicant's matriculation information.</a></b></td>
</tr>
</table>



<!-- A Separate Layer for the Calendar -->
<script language="JavaScript" src="<?php echo $szRootURL ?>/modules/datepicker/Calendar1-901.js" type="text/javascript"></script>

<!--	form validation scripts	-->
<script language="JavaScript" type="text/javascript">
	var validator  = new Validator("staff");
	
	validator.addValidation("intCentreID","dontselect=0","The Exam Centre must be indicated.");	
	validator.addValidation("intSubject1","dontselect=0","Please indicate subject 1.");
	validator.addValidation("intSubject2","dontselect=0","Please indicate subject 2.");
	validator.addValidation("intSubject3","dontselect=0","Please indicate subject 3.");
		
</script>	
<?php 
	# include the footer
	//include("$szRootPath/lookfeel/footer.php");
?>
        <td bgcolor="#99CCCC">&nbsp;</td>
      </tr>
      <tr>
        <td width="36" bgcolor="#99CCCC">&nbsp;</td>
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
