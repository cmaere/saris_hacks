<?php require_once('../Connections/zalongwa.php'); ?>
<?php
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "applicant")) {
  $updateSQL = sprintf("UPDATE applicant SET intYearID=%s, szSurname=%s, szFirstname=%s, szMiddlename=%s, intSexID=%s, dtDOB=%s, szBirthPlace=%s, intCountryID=%s, intReligionID=%s, intStatusID=%s, szMailingAddress=%s, szPhone=%s, szEmail=%s, intDisabilityID=%s WHERE intApplicantID=%s",
                       GetSQLValueString($_POST['intYearID'], "int"),
                       GetSQLValueString($_POST['szSurname'], "text"),
                       GetSQLValueString($_POST['szFirstname'], "text"),
                       GetSQLValueString($_POST['szMiddlename'], "text"),
                       GetSQLValueString($_POST['intSexID'], "int"),
                       GetSQLValueString($_POST['dtDOB'], "date"),
                       GetSQLValueString($_POST['szBirthPlace'], "text"),
                       GetSQLValueString($_POST['intCountryID'], "int"),
                       GetSQLValueString($_POST['intReligionID'], "int"),
                       GetSQLValueString($_POST['intStatusID'], "int"),
                       GetSQLValueString($_POST['szMailingAddress'], "text"),
                       GetSQLValueString($_POST['szPhone'], "text"),
                       GetSQLValueString($_POST['szEmail'], "text"),
                       GetSQLValueString($_POST['intDisabilityID'], "int"),
                       GetSQLValueString($_POST['intApplicantID'], "text"));

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($updateSQL, $zalongwa) or die(mysql_error());
}

$intApplicantID_applicant = "1";
if (isset($_GET['intApplicantID'])) {
  $intApplicantID_applicant = (get_magic_quotes_gpc()) ? $_GET['intApplicantID'] : addslashes($_GET['intApplicantID']);
}
mysql_select_db($database_zalongwa, $zalongwa);
$query_applicant = sprintf("SELECT * FROM applicant WHERE %s = '$%s'", $intApplicantID_applicant,$intApplicantID_applicant);
$applicant = mysql_query($query_applicant, $zalongwa) or die(mysql_error());
$row_applicant = mysql_fetch_assoc($applicant);
$totalRows_applicant = mysql_num_rows($applicant);
?>
<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
include('admissionMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Application Process';
	$szTitle = 'Application Details';
	$szSubSection = 'Application Form';
	
	include("admissionheader.php");
	include('../Connections/gt_functions.php');
	
	# set the Members Group to 1 if not supplied
	if (isset($_GET['intMemGroupID'])){$intMemGroupID = $_GET['intMemGroupID']; }else{ $intMemGroupID=1; }
	
	$intApplicantID = $_GET["intApplicantID"];
	$arrYears = gt_get_rows('academicyear','intYearID',0,0,'');
	$arrCseeResults = gt_get_rows('csee_results','intSubjectID',0,'intSubjectID','intApplicantID='.$_GET['intApplicantID']);
	$arrAcseeResults = gt_get_rows('acsee_results','intSubjectID',0,'intSubjectID','intApplicantID='.$_GET['intApplicantID']);
	$arrOtherResults = gt_get_rows('other_results','intSubjectID',0,'intSubjectID','intApplicantID='.$_GET['intApplicantID']);
	
	$arrCseeSubjects = gt_get_rows('csee_subjects','intSubjectID',0,'szSubject','');
	$arrAcseeSubjects = gt_get_rows('acsee_subjects','intSubjectID',0,'szSubject','');
	$arrOtherSubjects = gt_get_rows('csee_subjects','intSubjectID',0,'szSubject','');
	
	# does this information need to be updated?
	if (isset($_POST['action']) && ($_POST['action'] == "update"))
	{

		gt_debug($_POST,"POST variables");

		// build array from posted data
		$arrApplicant = array();
		$arrApplicant = $_POST;
		
		gt_debug($arrApplicant,"Applicant to be updated by POST");
		
		// update the item in the database, and update our item array
		$arrApplicant = gt_add_update_row($arrApplicant,'applicant','WHERE intApplicantID='.$intApplicantID);

		gt_debug($arrApplicant,"Updated Applicant");
		
		// update Personnel Number variable
		$intApplicantID = $arrApplicant['intApplicantID'];
	}

	if($intApplicantID == 0)
	{
		// create completely new staff	
		// build blank array
		$arrApplicant = array();
		// update the intApplicantID
		$arrApplicant['intApplicantID']=0;
	}
	else
	{
		// get the application Number
		$arrApplicant = gt_get_row('applicant', 'WHERE intApplicantID='.$intApplicantID);
	}
?>

<!--	form validator	-->
<script language="JavaScript" src="../../news/admin/gen_validatorv2.js" type="text/javascript"></script>
<style type="text/css">
<!--
.style1 {color: #CCCCCC}
.style2 {color: #000000}
.style3 {font-weight: bold}
-->
</style>


<br>
<?php if (isset($_POST['action']) && ($_POST['action'] == "update"))
{
		echo "<div style=\"padding:5px;border:1px solid #6666CC\">";
		echo "<b><center>Applicant Information Updated</center></b>";
		echo "</div><br>";
}
?>		





<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td colspan="2" align="right"><div align="center"><b><font size="+1">UNIVERSITY OF DAR ES SALAAM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></b></div></td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<b>APPLICATION FORM FOR ADMISSION TO UNDERGRADUATE DEGREE PROGRAMMES</b><br>
			To be filled in duplicate and sent to: The Director of Undergreaduate Studies<br>
			University of Dar es Salaam, P.O. Box 35091, Dar es Salaam, Tanzania
		</td>
	</tr>
</table>






<form action="<?php echo $editFormAction; ?>" method="POST" name="applicant" id="applicant">

<div class="smalltext" style="margin: 0px 0px 5px 0px;">&nbsp;
<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC" class="smalltext">
	<tr>
		<td><em>Academic year for which Admission is sought:
		<?php $arrCurrentYear  = gt_get_row('academicYear','WHERE Status=1'); ?></em></td>
		<td align="right"><?php echo $arrCurrentYear['AYear']?>
			<select name="intYearID" id="intYearID" class="vform">
				<option value="<?php echo $arrCurrentYear['intYearID']?>"><?php echo $arrCurrentYear['AYear']?></option>
			<?php if($arrYears)
				{
					foreach($arrYears as $arrYear)
					{
			?>
						<option value="<?php echo $arrYear['intYearID']?>" <?php if($arrYear['intYearID'] == $arrApplicant['intYearID']) echo 'selected'; ?>><?php echo $arrYear['AYear']?></option>
			<?php }
				}
			?>
			</select>
		</td>
	</tr>
		<tr>
		<td><em>Application Registration Number:</em></td>
		<td align="right"><?php echo $intApplicantID ?>
			
		</td>
	</tr
></table>

</div>



<div style="font-family: verdana, arial; font-size: 11px; font-weight: bold; padding: 5px 0px 0px 0px;">Matriculation Examinations Information.</div>

<?php $arrInfos = gt_get_row('matric_info','WHERE intApplicantID='.$intApplicantID);
	$arrChoice = gt_get_row('matric_centre','WHERE intCentreID='.$arrInfos['intCentreID']);
?>

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td>
	<div><em>Matriculation Examinations Centre</em>:	&nbsp; <b><?php echo $arrChoice['szCentre']?></b></div>
	</td>
	<td align="right" style="padding-top:5px; padding-bottom:5px;">
		<?php if(!$arrInfos) $intMatricID = 0;
			else $intMatricID = $arrInfos['intMatricID'];
		
			$url = "window.location.href='aformMatric.php?intApplicantID=".$_GET['intApplicantID']."&intMatricID=".$intMatricID."'";
			?>
		<input type="button" name="asd" value="Edit Matriculation Info" class="vform" onClick="<?php echo $url?>">
	</td>
</tr>
</table>

<table cellspacing="1" cellpadding="2" border="0" width="100%" bgcolor="#CCCCCC">			

<?php if ($arrInfos)
	{
?>
		<tr style="font-weight: bold;">
			<td align="center">Exam No:</td>
			<td>Subject 1</td>
			<td>Subject 2</td>
			<td>Subject 3</td>
		</tr>
		
		<tr bgcolor="#ffffff">
			<td align="center">1</td>
			<td>
				<?php $arrSubject = gt_get_row('acsee_subjects','WHERE intSubjectID='.$arrInfos['intSubject1']);
					echo $arrSubject['szSubject'];
				?>
			</td>
			
			<td>
				<?php $arrSubject = gt_get_row('acsee_subjects','WHERE intSubjectID='.$arrInfos['intSubject2']);
					echo $arrSubject['szSubject'];
				?>
			</td>
			
			<td>
				<?php $arrSubject = gt_get_row('acsee_subjects','WHERE intSubjectID='.$arrInfos['intSubject3']);
					echo $arrSubject['szSubject'];
				?>
			</td>
		</tr>

<?php }
	else 
	{
		echo "<tr class='adminRow2'><td colspan='4' class='adminContent' align='center' valign='top'>No subjects have been entered yet.</td></tr>";
	}
?>
</table>


<br>


<div style="font-family: verdana, arial; font-size: 11px; font-weight: bold; padding: 5px 0px 5px 0px;">Choice of Degree Programmes in Descending Order of Preference.</div>

<table cellspacing="1" cellpadding="4" border="0" width=100% bgcolor="#CCCCCC">				
	<tr style="font-weight: bold;">
		<td>Choice</td>
		<td>Institution</td>
		<td>Degree&nbsp;<i>(Degree Code)</i></td>
		<td align="center">Options</td>
	</tr>
	
	<tr>
		<td bgcolor="#ffffff">First Choice</td>
		<td bgcolor="#ffffff">
		
		<?php $first_choice = gt_get_row('degree_choice','WHERE (intApplicantID='.$_GET['intApplicantID'].') AND (intChoiceNo=1)');
			
			if($first_choice)
			{
				$arrChoice_one  = gt_get_row('programme','WHERE ProgrammeID='.$first_choice['intDegreeID']);
								
				echo $arrChoice_one['Faculty'];
			}
			else
			{
				echo 'First Choice not entered yet.';
			}
		?>
		
		</td>
		<td bgcolor="#ffffff"><?php echo $arrChoice_one['ProgrammeName']?></td>
		<td align="center" bgcolor="#ffffff"><a href="aformEditchoice.php?intApplicantID=<?php echo $_GET['intApplicantID']?>&intChoiceID=<?php echo $first_choice['intChoiceID']?>&intChoiceNo=1"><?if($first_choice){ echo 'Change';} else { echo 'Enter'; }?></a>&nbsp;&nbsp;<a href="Javascript:confirm_delete('aformDeletechoice.php?intApplicantID=<?php echo $_GET['intApplicantID']?>&intChoiceID=<?php echo $first_choice['intChoiceID']?>');">Delete</a></td>
	</tr>
	
	<tr>
		<td bgcolor="#ffffff">Second Choice</td>
		<td bgcolor="#ffffff">
		
		<?php $second_choice = gt_get_row('degree_choice','WHERE (intApplicantID='.$_GET['intApplicantID'].') AND (intChoiceNo=2)');
			
			if($second_choice)
			{
				$arrChoice_two  = gt_get_row('programme','WHERE ProgrammeID='.$second_choice['intDegreeID']);
	
				echo $arrChoice_two['Faculty'];	
			}
			else
			{
				echo 'Second Choice not entered yet.';
			}
		?>
		
		</td>
		<td bgcolor="#ffffff"><?php echo $arrChoice_two['ProgrammeName']?></td>
		<td align="center" bgcolor="#ffffff"><a href="aformEditchoice.php?intApplicantID=<?php echo $_GET['intApplicantID']?>&intChoiceID=<?php echo $second_choice['intChoiceID']?>&intChoiceNo=2"><?if($second_choice){ echo 'Change';} else { echo 'Enter'; }?></a>&nbsp;&nbsp;<?if($second_choice) {?><a href="Javascript:confirm_delete('aformDeletechoice.php?intApplicantID=<?php echo $_GET['intApplicantID']?>&intChoiceID=<?php echo $second_choice['intChoiceID']?>');">Delete</a><?php }?></td>
	</tr>
	
	<tr>
		<td bgcolor="#ffffff">Third Choice</td>
		<td bgcolor="#ffffff">
		
		<?php $third_choice = gt_get_row('degree_choice','WHERE (intApplicantID='.$_GET['intApplicantID'].') AND (intChoiceNo=3)');
			
			if($third_choice)
			{
				$arrChoice_three  = gt_get_row('programme','WHERE ProgrammeID='.$third_choice['intDegreeID']);
	
			echo $arrChoice_three['Faculty'];
			}
			else
			{
				echo 'Third Choice not entered yet.';
			}
		?>
		
		</td>
		<td bgcolor="#ffffff"><?php echo $arrChoice_three['ProgrammeName']?></td>
		<td align="center" bgcolor="#ffffff"><a href="aformEditchoice.php?intApplicantID=<?php echo $_GET['intApplicantID']?>&intChoiceID=<?php echo $third_choice['intChoiceID']?>&intChoiceNo=3"><?if($third_choice){ echo 'Change';} else { echo 'Enter'; }?></a>&nbsp;&nbsp;<?if($third_choice){?><a href="Javascript:confirm_delete('aformDeletechoice.php?intApplicantID=<?php echo $_GET['intApplicantID']?>&intChoiceID=<?php echo $third_choice['intChoiceID']?>');">Delete</a><?php }?></td>
	</tr>

</table>

<br>

<table cellspacing="1" cellpadding="6" border="0" width=100% bgcolor="#CCCCCC">
	<tr bgcolor="#C7D6E6" style="font-weight: bold;">
		<td bgcolor="#CCCCCC"><span class="style2">1.0 Personal Particulars</span></td>
		<td align="right" bgcolor="#CCCCCC"><span class="style1"><i>Applicant ID</i>:&nbsp;
	    <?php echo $arrApplicant['intApplicantID']?>
	    <input type="hidden" name="intApplicantID" id="intApplicantID" value="<?php echo $arrApplicant['intApplicantID']?>">
	  </span></td>
	</tr>
</table>

<div style="padding: 5px;"></div>


<table width="93%" border="0" cellpadding="0" cellspacing="1" bordercolor="#CCCCCC" bgcolor="#CCCCCC">
  <tr bgcolor="#FFFFFF">
    <th width="16%" nowrap scope="row"><div align="left">1.1 Surname: </div></th>
    <td colspan="4"><input name="szSurname" type="text" class="vform " id="szSurname" value="<?php echo $arrApplicant['szSurname']?>" size="50">      <strong> </strong></td>
    <td width="1%" bgcolor="#CCCCCC">&nbsp;</td>
  </tr>
  <tr>
    <th nowrap bgcolor="#FFFFFF" scope="row"><div align="left"><strong>First Name: </strong></div></th>
    <td colspan="2" bgcolor="#FFFFFF"><input name="szFirstname" type="text" class="vform " id="szFirstname"  value="<?php echo $arrApplicant['szFirstname']?>" size="50"></td>
    <td colspan="3" rowspan="6" nowrap bgcolor="#CCCCCC"><span class="vsmalltext"><strong>
    </strong></span><strong>
    </strong><span class="vsmalltext"><strong>
    </strong></span><strong>
    </strong></td>
  </tr>
  <tr>
    <th nowrap bgcolor="#FFFFFF" scope="row"><div align="left"><strong>Middle Names: </strong></div></th>
    <td colspan="2" bgcolor="#FFFFFF"><input name="szMiddlename" type="text" class="vform " id="szMiddlename" value="<?php echo $arrApplicant['szMiddlename']?>" size="50"></td>
    </tr>
  <tr>
    <th colspan="3" nowrap bgcolor="#FFFFFF" scope="row"><div align="left"><strong>1.2 Sex:
          <?php $arrSexes = gt_get_rows('sex','sexid',0,0,'');
			?>
          <select name="intSexID" class="vform style3">
            <option value="0" <?php if ($arrApplicant['sex'] == ""){ echo "selected"; } ?>>[Sex]</option>
            <?php foreach($arrSexes as $arrSex)
				{
			?>
            <option value="<?php echo $arrSex['sexid']?>" <?php if ($arrApplicant['sexid']==$arrSex['sexid']){ echo "selected";}?>>
            <?php echo $arrSex['sex']?>
            </option>
            <?php }
			?>
          </select>
</strong><strong>1.3 Date of Birth:</strong> <span class="vsmalltext"><strong>
      <input type="text" class="vform" size="15" name="dtDOB" value="<?php echo $arrApplicant['dtDOB']?>">
      <strong><strong>
      <input type="button" class="button" name="dtDOB_button" value="Choose Date" onClick="show_calendar('applicant.dtDOB', '','','YYYY-MM-DD', 'POPUP','AllowWeekends=Yes;Nav=No;SmartNav=Yes;PopupX=300;PopupY=300;')">
      </strong></strong> </strong></span><span class="vsmalltext"><strong><strong>
    </strong>
      </strong></span></div>      </th>
    </tr>
  <tr>
    <th nowrap bgcolor="#FFFFFF" scope="row"><div align="left">1.4 Place of Birth: </div></th>
    <td colspan="2" nowrap bgcolor="#FFFFFF"><input name="szBirthPlace" type="text" class="vform " id="szBirthPlace" value="<?php echo $arrApplicant['szBirthPlace']?>" size="20">      <strong>
    </strong></td>
    </tr>
  <tr>
    <th nowrap bgcolor="#FFFFFF" scope="row"><div align="left"><strong>1.5 Citizenship:
          <?php $arrCountries = gt_get_rows('country','intCountryID',0,0,'');
			?>
    </strong></div></th>
    <td colspan="2" bgcolor="#FFFFFF"><strong>
      <select name="intCountryID" id="intCountryID">
        <option value="0" <?php if ($arrApplicant['szCountry']==""){ echo "selected";}?>>[Select Country]</option>
        <?php foreach($arrCountries as $arrCountry)
				{
			?>
        <option value="<?php echo $arrCountry['intCountryID']?>" <?php if ($arrApplicant['intCountryID']==$arrCountry['intCountryID']){ echo "selected";}?>>
        <?php echo $arrCountry['szCountry']?>
        </option>
        <?php }
			?>
      </select>
    </strong></td>
    </tr>
  <tr>
    <th nowrap bgcolor="#FFFFFF" scope="row"><div align="left">1.6 Religion:</div></th>
    <td colspan="2" bgcolor="#FFFFFF"><select name="intReligionID" id="intReligionID">
      <?php $arrReligions = gt_get_rows('religion','ReligionID',0,0,'');
			?>
      <option value="" <?php if ($arrApplicant['Religion']==""){ echo "selected";}?>>[Religion]</option>
      <?php foreach($arrReligions as $arrReligion)
				{
			?>
      <option value="<?php echo $arrReligion['ReligionID']?>" <?php if ($arrApplicant['ReligionID']==$arrReligion['ReligionID']){ echo "selected";}?>>
      <?php echo $arrReligion['Religion']?>
      </option>
      <?php }
			?>
    </select></td>
    </tr>
  <tr>
    <th nowrap bgcolor="#FFFFFF" scope="row"><div align="left">1.7 Marital Status: 
      <?php $arrStatus = gt_get_rows('maritalstatus','intStatusID',0,0,'');
			?>
    </div></th>
    <td width="74%" bgcolor="#FFFFFF"><select id="intStatusID" name="intStatusID" class="vform">
      <option value="" <?php if ($arrApplicant['szStatus']==""){ echo "selected";}?>>[Marital Status]</option>
      <?php foreach($arrStatus as $arrStatum)
				{
			?>
      <option value="<?php echo $arrStatum['intStatusID']?>" <?php if ($arrApplicant['intStatusID']==$arrStatum['intStatusID']){ echo "selected";}?>>
      <?php echo $arrStatum['szStatus']?>
      </option>
      <?php }
			?>
    </select></td>
    <td colspan="4" bgcolor="#CCCCCC">&nbsp;</td>
    </tr>
  <tr>
    <th nowrap bgcolor="#FFFFFF" scope="row"><div align="left">1.8 Mailing Address: </div></th>
    <td bgcolor="#FFFFFF"><textarea name="szMailingAddress" class="vform" id="szMailingAddress" cols="30" rows="5"><?php echo $arrApplicant['szMailingAddress']?>
    </textarea></td>
    <td colspan="4" bgcolor="#CCCCCC">&nbsp;</td>
  </tr>
  <tr>
    <th nowrap bgcolor="#FFFFFF" scope="row"><div align="left">1.9 Telephone Number: </div></th>
    <td bgcolor="#FFFFFF"><input type="text" name="szPhone" size="15" class="vform" id="szPhone"  value="<?php echo $arrApplicant['szPhone']?>"></td>
    <td colspan="4" bgcolor="#CCCCCC">&nbsp;</td>
  </tr>
  <tr>
    <th nowrap bgcolor="#FFFFFF" scope="row"><div align="left">e-mail:</div></th>
    <td bgcolor="#FFFFFF"><input type="text" name="szEmail" size="30" class="vform" id="szEmail"  value="<?php echo $arrApplicant['szEmail']?>"></td>
    <td colspan="4" bgcolor="#CCCCCC">&nbsp;</td>
  </tr>
  <tr>
    <th nowrap bgcolor="#FFFFFF" scope="row"><div align="left">1.10 Disability: 
      <?php $arrDisabilities = gt_get_rows('disability','DisabilityID',0,0,'');
			?>
    </div></th>
    <td bgcolor="#FFFFFF"><select id="intDisabilityID" name="intDisabilityID" class="vform">
      <option value="0" <?php if ($arrApplicant['Disability']==""){ echo "selected";}?>>[Disability]</option>
      <?php foreach($arrDisabilities as $arrDisability)
				{
			?>
      <option value="<?php echo $arrDisability['DisabilityID']?>" <?php if ($arrApplicant['tDisabilityID']==$arrDisability['DisabilityID']){ echo "selected";}?>>
      <?php echo $arrDisability['Disability']?>
      </option>
      <?php }
			?>
    </select></td>
    <td colspan="4" bgcolor="#CCCCCC">&nbsp;</td>
  </tr>
</table>
<div align="center">
  <input  class="vform" type="submit" name="action" value="update">
  <br>
</div>
<input type="hidden" name="MM_update" value="applicant">
</form>



<table cellspacing="1" cellpadding="6" border="0" width=100% bgcolor="#CCCCCC">
	<tr bgcolor="#C7D6E6" style="font-weight: bold;">
		<td bgcolor="#CCCCCC">2.0 Education Background and Employment Record</td>
	</tr>
</table>

<div style="padding: 5px;"></div>

<table cellspacing="1" cellpadding="6" border="0" width=100% bgcolor="#CCCCCC">
	<tr bgcolor="#C7D6E6" style="font-weight: bold;">
		<td bgcolor="#CCCCCC">2.1 C.S.E.E./ National Form IV/ or Equivalent</td>
		<td align="right" bgcolor="#CCCCCC">
			<?php $url = "window.location.href='aformResult.php?intApplicantID=".$_GET['intApplicantID']."&intResultID=0&level=csee'";	?>
			<input type="button" name="asd" value="add new" class="vform" onClick="<?php echo $url?>">
	  </td>
	</tr>
</table>

<div style="padding: 5px;"></div>

<table cellspacing="1" cellpadding="4" border="0" width=100% bgcolor="#CCCCCC">			
	<tr style="font-weight: bold;">
		<td>Subject</td><td>Grade</td><td>Date</td><td>Index No.</td>
	</tr>
<?php if ($arrCseeResults)
	{
		foreach($arrCseeResults as $arrCseeResult)
		{
?>
	<tr bgcolor="#ffffff">
		<td>
			<?php $arrSubject = gt_get_row('csee_subjects','WHERE intSubjectID='.$arrCseeResult['intSubjectID']);	
				echo '<b><a href="aformResult.php?intApplicantID='.$_GET['intApplicantID'].'&intResultID='.$arrCseeResult['intResultID'].'&level=csee">'.$arrSubject['szSubject'].'</b></a>';
			?>
		</td>
		<td>
			<?php $arrGrade = gt_get_row('csee_grades','WHERE intGradeID='.$arrCseeResult['intGradeID']);
				echo $arrGrade['szGrade'];
			?>
		</td>
		<td><?php echo $arrCseeResult['dtDate']?></td>
		<td><?php echo $arrCseeResult['szIndexNo']?></td>
	</tr>		
<?php }
	}
	else
	{
?>
	<tr bgcolor="#ffffff"><td colspan="4">No results at present.</td></tr>
<?php }
?>

</table>

<div style="padding: 5px;"></div>

<table cellspacing="1" cellpadding="6" border="0" width=100% bgcolor="#CCCCCC">
	<tr bgcolor="#C7D6E6" style="font-weight: bold;">
		<td bgcolor="#CCCCCC">2.2 A.C.S.E.E./ National Form VI/ or Equivalent</td>
		<td align="right" bgcolor="#CCCCCC">
			<?php $url = "window.location.href='aformResult.php?intApplicantID=".$_GET['intApplicantID']."&intResultID=0&level=acsee'";	?>
			<input type="button" name="asd" value="add new" class="vform" onClick="<?php echo $url?>">
	  </td>
	</tr>
</table>

<div style="padding: 5px;"></div>

<table cellspacing="1" cellpadding="4" border="0" width=100% bgcolor="#CCCCCC">			
	<tr style="font-weight: bold;">
		<td>Subject</td><td>Grade</td><td>Date</td><td>Index No.</td>
	</tr>
<?php if ($arrAcseeResults)
	{
		foreach($arrAcseeResults as $arrAcseeResult)
		{
?>
	<tr bgcolor="#ffffff">
		<td>
			<?php $arrSubject = gt_get_row('acsee_subjects','WHERE intSubjectID='.$arrAcseeResult['intSubjectID']);	
				echo '<b><a href="aformResult.php?intApplicantID='.$_GET['intApplicantID'].'&intResultID='.$arrAcseeResult['intResultID'].'&level=acsee">'.$arrSubject['szSubject'].'</b></a>';
			?>
		</td>
		<td>
			<?php $arrGrade = gt_get_row('acsee_grades','WHERE intGradeID='.$arrAcseeResult['intGradeID']);
				echo $arrGrade['szGrade'];
			?>
		</td>
		<td><?php echo $arrAcseeResult['dtDate']?></td>
		<td><?php echo $arrAcseeResult['szIndexNo']?></td>
	</tr>		
<?php }
	}
	else
	{
?>
	<tr bgcolor="#ffffff"><td colspan="4">No results at present.</td></tr>
<?php }
?>

</table>


<div style="padding: 5px;"></div>

<table cellspacing="1" cellpadding="6" border="0" width=100% bgcolor="#CCCCCC">
	<tr bgcolor="#C7D6E6" style="font-weight: bold;">
		<td bgcolor="#CCCCCC">2.3 Qualifications other than A.C.S.E.E. </td>
		<td align="right" bgcolor="#CCCCCC">
			<?php $url = "window.location.href='aformOther.php?intApplicantID=".$_GET['intApplicantID']."&intResultID=0&level=other'";	?>
			<input type="button" name="asd" value="add new" class="vform" onClick="<?php echo $url?>">
	  </td>
	</tr>
</table>

<div style="padding: 5px;"></div>

<table cellspacing="1" cellpadding="4" border="0" width=100% bgcolor="#CCCCCC">			
	<tr style="font-weight: bold;">
		<td>Subject</td><td>Grade</td><td>Date</td><td>Index No.</td>
	</tr>
<?php if ($arrOtherResults)
	{
		foreach($arrOtherResults as $arrOtherResult)
		{
?>
	<tr bgcolor="#ffffff">
		<td>
			<?php $arrSubject = gt_get_row('other_subjects','WHERE intSubjectID='.$arrOtherResult['intSubjectID']);	
				echo '<b><a href="aformOther.php?intApplicantID=1&intResultID='.$arrOtherResult['intResultID'].'&level=csee">'.$arrSubject['szSubject'].'</b></a>';
			?>
		</td>
		<td>
			<?php $arrGrade = gt_get_row('other_grades','WHERE intGradeID='.$arrOtherResult['intGradeID']);
				echo $arrGrade['szGrade'];
			?>
		</td>
		<td><?php echo $arrOtherResult['dtDate']?></td>
		<td><?php echo $arrOtherResult['szIndexNo']?></td>
	</tr>		
<?php }
	}
	else
	{
?>
	<tr bgcolor="#ffffff"><td colspan="4">No results at present.</td></tr>
<?php }
?>

</table>

<div style="padding: 5px;"></div>

<table cellspacing="1" cellpadding="6" border="0" width=100% bgcolor="#CCCCCC">
	<tr bgcolor="#C7D6E6" style="font-weight: bold;">
		<td bgcolor="#CCCCCC">2.4 Post A-Level Education</td>
		<td align="right" bgcolor="#CCCCCC">
			<?php $url = 'window.location="aformPostresult.php?intApplicantID='.$intApplicantID.'"'; 
			?>
			<input  class="vform" type="button" name="action" value="Click Here" onClick=<?php echo $url?>>
	  </td>
	</tr>
</table>

<div style="padding: 5px;"></div>

<table cellspacing="1" cellpadding="4" border="0" width=100% bgcolor="#CCCCCC">			
	<tr style="font-weight: bold;">
		<td>S/N</td>
		<td>Institution Attended</td>
		<td>Status</td>
		<td>Qualification</td>
		<td align="center">Date Obtained</td>
		<td align="center">Options</td>
	</tr>
	
	<?php $arrPosts = gt_get_rows('post_a_education','intPostID',0,'intPostID','intApplicantID='.$_GET['intApplicantID']);
		
		if($arrPosts)
		{
			$i = 1;
			
			foreach($arrPosts as $arrPost)
			{
				$intPostID = $arrPost['intPostID'];
				
				echo '<tr bgcolor="#ffffff" style="border: 1px solid #C7D6E6;">';
					echo '<td bgcolor="#ffffff">'.$intPostID.'</td>';
					echo '<td bgcolor="#ffffff"><a href="aformPostresult.php?intApplicantID='.$_GET['intApplicantID'].'&intPostID='.$intPostID.'"><b>'.$arrPost['szInstitution'].'</b></a></td>';
		?>
						<td>
		<?php $arrStatus = gt_get_row('post_status','WHERE intStatusID='.$arrPost['intStatusID']);
					echo $arrStatus['szStatus'];
		?>					
						</td>
		<?php echo '<td>'.$arrPost['szQualification'].'</td>';
					echo '<td align="center">'.$arrPost['dtDate'].'</td>';
					
	?>
					<td align="center"><a href="Javascript:confirm_delete('aformDeletequalification..php?intApplicantID=<?php echo $_GET['intApplicantID']?>&intPostID=<?php echo $intPostID?>');"><img src="./images/delete.gif" alt="Delete this applicant?" width="15" height="15" border="0"></a></td>
	<?php echo '</tr>';
				
				$i++; if ($i > 2) $i = 1;
			}
		}
		else
		{
			echo '<tr><td colspan="6" align="center" class="adminRow2">No qualifications available at present.</td></tr>';
		}
	?>
	
</table>





<br>

<table cellspacing="1" cellpadding="6" border="0" width=100% bgcolor="#CCCCCC">
	<tr bgcolor="#C7D6E6" style="font-weight: bold;">
		<td bgcolor="#CCCCCC">2.5 Employment Record</td>
		<td align="right" bgcolor="#CCCCCC">
			<?php $url = "window.location.href='aformEmployment.php?intApplicantID=".$_GET['intApplicantID']."&intEmploymentID=0'";	?>
			<input type="button" name="asd" value="add new" class="vform" onClick="<?php echo $url?>">
	  </td>
	</tr>
</table>

<div style="padding: 5px;"></div>

<table cellspacing="1" cellpadding="4" border="0" width=100% bgcolor="#CCCCCC">			
	<tr>
		<td>S/N</td>
		<td>Name of Employer</td>
		<td>Post(s) Held</td>
		<td align="center">Start Date</td>
		<td align="center">End Date</td>
		<td align="center">Options</td>
	</tr>
	
	<?php $arrDetails = gt_get_rows('employment_details','intEmploymentID',0,'intEmploymentID','intApplicantID='.$_GET['intApplicantID']);
		
		if($arrDetails)
		{
			$i = 1;
			
			foreach($arrDetails as $arrDetail)
			{
				$intEmploymentID = $arrDetail['intEmploymentID'];
				
				echo '<tr bgcolor=#ffffff>';
					echo '<td>'.$intEmploymentID.'</td>';
					echo '<td><a href="aformEmployment.php?intApplicantID='.$_GET['intApplicantID'].'&intEmploymentID='.$intEmploymentID.'"><b>'.$arrDetail['szEmployer'].'</b></a></td>';
					echo '<td>'.$arrDetail['szPost'].'</td>';
					echo '<td align="center">'.$arrDetail['dtFrom'].'</td>';
					echo '<td align="center">'.$arrDetail['dtTo'].'</td>';
	?>
					<td align="center"><a href="Javascript:confirm_delete('delete_employment.php?intApplicantID=<?php echo $_GET['intApplicantID']?>&intEmploymentID=<?php echo $intEmploymentID?>');"><img src="./images/delete.gif" alt="Delete this applicant?" width="15" height="15" border="0"></a></td>
	<?php echo '</tr>';
				
				$i++; if ($i > 2) $i = 1;
			}
		}
		else
		{
			echo '<tr bgcolor="#ffffff"><td align="center" colspan="6">Employment Details for the applicant not available. </td></tr>';
		}
		
		
	?>
	
</table>

<br>

<table cellspacing="1" cellpadding="6" border="0" width=100% bgcolor="#CCCCCC">
	<tr bgcolor="#C7D6E6" style="font-weight: bold;">
		<td bgcolor="#CCCCCC">3.0 Application Fee and Sponsorship</td>
		<td align="right" bgcolor="#CCCCCC">
			<?php $url = "window.location.href='aformEditsponsor.php?intApplicantID=".$_GET['intApplicantID']."&intSponsorID=0'";	?>
			<input type="button" name="asd" value="add new" class="vform" onClick="<?php echo $url?>">
	  </td>
	</tr>
</table>

<div style="padding: 5px;"></div>

<table cellspacing="1" cellpadding="4" border="0" width=100% bgcolor="#CCCCCC">			
	<tr style="font-weight: bold;">
		<td>No.</td>
		<td>Name of Sponsor</td>
		<td>Relationship</td>
		<td align="center">Telephone No.</td>
		<td align="center">Options</td>
	</tr>
	
	<?php $arrDetails = gt_get_rows('sponsor_details','intSponsorID',0,'intSponsorID','intApplicantID='.$_GET['intApplicantID']);
		
		if($arrDetails)
		{
			$i = 1;
			
			foreach($arrDetails as $arrDetail)
			{
				$intSponsorID = $arrDetail['intSponsorID'];
				
				echo '<tr bgcolor=#ffffff>';
					echo '<td>'.$intSponsorID.'</td>';
					echo '<td><a href="edit_sponsor.php?intApplicantID='.$_GET['intApplicantID'].'&intSponsorID='.$intSponsorID.'"><b>'.$arrDetail['szSponsor'].'</b></a></td>';
					echo '<td>'.$arrDetail['szRelationship'].'</td>';
					echo '<td align="center">'.$arrDetail['szTelephone'].'</td>';
	?>
					<td align="center"><a href="Javascript:confirm_delete('delete_sponsor.php?intApplicantID=<?php echo $_GET['intApplicantID']?>&intSponsorID=<?php echo $intSponsorID?>');"><img src="./images/delete.gif" alt="Delete this applicant?" width="15" height="15" border="0"></a></td>
	<?php echo '</tr>';
				
				$i++; if ($i > 2) $i = 1;
			}
		}
		else
		{
			echo '<tr class="adminRow2"><td align="center" colspan="6">Sponsorship Details for the applicant not available. </td></tr>';
		}
		
		
	?>
	
</table>
<br>

<table cellspacing="2" cellpadding="2" border="0">
<tr>
    <td><a href="index.php"><img src="images/back.gif" width="15" height="15" alt="" border="0"></a></td>
    <td><b><a href="admissionProcess.php?field=szFirstname&how=contain&str=&go=Go">Go Back</a></b></td>
</tr>
</table>
<!-- A Separate Layer for the Calendar -->
<script language="JavaScript" src="datepicker/Calendar1-901.js" type="text/javascript"></script>


<?php 
	include("../footer/footer.php");
?>
<?php
mysql_free_result($applicant);
?>
