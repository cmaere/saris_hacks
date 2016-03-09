<?php 
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	require_once('../Connections/gt_functions.php');
	# include the header
	include('admissionMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Application Process';
	$szTitle = 'Application Process';
	$szSubSection = 'Policy Setup';
	include("admissionheader.php");
	
?>

<table cellspacing="1" cellpadding="4" border="0" width=100% >
	<tr>
		<td class="smalltext">&nbsp;</td>
		<td align=right valign=bottom>
			<input type="button" name="asd" value="add new" class="vform" onClick="window.location.href='admissionNewapplication.php'">
		</td>
	</tr>
</table>

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



if (isset($_POST['search']) && ($_POST['search'] == "Search")) {
#get post variables
$key = $_POST['key'];
			
require_once('../Connections/zalongwa.php'); 
$sql = "SELECT applicant.intApplicantID,
       applicant.szSurname,
       applicant.szFirstname,
       applicant.szMiddlename,
       academicyear.AYear,
       programme.ProgrammeName,
       degree_choice.intChoiceNo,
       acsee_subjects.szSubject,
       acsee_grades.szGrade,
       sex.sex
FROM sex
   INNER JOIN applicant ON (sex.sexid = applicant.intSexID)
   INNER JOIN acsee_results ON (applicant.intApplicantID = acsee_results.intApplicantID)
   INNER JOIN acsee_grades ON (acsee_results.intGradeID = acsee_grades.intGradeID)
   INNER JOIN acsee_subjects ON (acsee_results.intSubjectID = acsee_subjects.intSubjectID)
   INNER JOIN academicyear ON (applicant.intYearID = academicyear.intYearID)
   INNER JOIN degree_choice ON (applicant.intApplicantID = degree_choice.intApplicantID)
   INNER JOIN programme ON (degree_choice.intDegreeID = programme.ProgrammeID)
WHERE (applicant.szSurname LIKE '%$key%') OR (applicant.intApplicantID LIKE '%$key%') ORDER BY applicant.intApplicantID";

$result = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
$query = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

$all_query = mysql_query($query);
$totalRows_query = mysql_num_rows($query);
/* Printing Results in html */
if (mysql_num_rows($query) > 0){
echo "<p>Total Records Found: $totalRows_query </p>";
echo "<table border='1'>";
echo "<tr><td> S/No </td><td> Name </td><td> RegNo </td><td> Sex </td><td> Degree </td><td> Faculty </td><td> Sponsor </td><td> Registered </td><td> Hostel </td><td> Room No.: </td><td> Academic Year </td></tr>";
$i=1;
while($result = mysql_fetch_array($query)) {
		$id = stripslashes($result["intApplicantID"]);
		$year = stripslashes($result["AYear"]);
		$Name = stripslashes($result["szSurname"]);
		$RegNo = stripslashes($result["intApplicantID"]);
		$sex = stripslashes($result["sex"]);
		$degree = stripslashes($result["ProgrammeName"]);
		$faculty = stripslashes($result["szSubject"]);
		$sponsor = stripslashes($result["szGrade"]);
		$entryyear = stripslashes($result["intChoiceNo"]);
		//$hall = stripslashes($result["HName"]);
		//$citeria = stripslashes($result["RNumber"]);
			echo "<tr><td><a href=\"admissionRegistrationForm.php?id=$id&RegNo=$RegNo\">$i</a></td>";
			echo "<td>$Name</td>";
			echo "<td>$RegNo</td>";
			echo "<td>$sex</td>";
			echo "<td>$degree</td>";
			echo "<td>$faculty</td>";
			echo "<td>$sponsor</td>";
			echo "<td>$entryyear</td>";
			echo "<td>$hall</td>";
			echo "<td>$citeria</td>";
			echo "<td>$year</td></tr>";
		$i=$i+1;
		}
echo "</table>";
}else{
$key= stripslashes($key);
echo "Sorry, No Records Found <br>";
echo "That Match With Your Searck Key \"$key \" ";
}
mysql_close($zalongwa);

}else{

?>

<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" name="studentRoomApplication" id="studentRoomApplication">
            <table width="701" border="0">
        <tr>
          <td colspan="2" nowrap><div align="center"></div>
          </td>
        </tr>
        <tr>
          <td width="235" nowrap><div align="right"><span class="style67"><strong>Name or Application No.:</strong></span></div></td>
          <td width="456" bordercolor="#ECE9D8" bgcolor="#CCCCCC"><span class="style67">
          <input name="key" type="text" id="key" size="40" maxlength="40">
          </span></td>
        </tr>
        <tr>
          <td nowrap><div align="right">Click Search: </div></td>
          <td bgcolor="#CCCCCC"><input type="submit" name="search" value="Search"></td>
        </tr>
      </table>
</form>
<?php
}
include('../footer/footer.php');
?>


<br>
<Script Language="JavaScript">
function formValidator(){
	if (document.staff_by_gender.intSexID.value == ""){
		alert("Please enter the gender!");
		document.staff_by_gender.intSexID.focus();
		return false
	}
	return true;
}
</Script>
<?php 

	if (isset($_GET['go']) && ($_GET['go'] == "Go")){
		
		/*
		$selectSQL = "	SELECT a.intApplicantID as intID, a.szSurname, a.szFirstname, SUM(g.intPoints) as intPoints
						FROM applicant as a, acsee_results as r, acsee_grades as g
						WHERE (a.intApplicantID = r.intApplicantID) AND (r.intGradeID = g.intGradeID) ";
		*/
		
		$selectSQL = "	SELECT a.intApplicantID as intID, a.szSurname, a.szFirstname
						FROM applicant as a, acsee_results as r
						WHERE (a.intApplicantID = r.intApplicantID)";
									
		if($_GET['how'] == 'contain')	$szHow = '%'.$_GET['str'].'%';
		if($_GET['how'] == 'begin')		$szHow = $_GET['str'].'%';
		if($_GET['how'] == 'is')		$szHow = $_GET['str'];
		if($_GET['how'] == 'end')		$szHow = '%'.$_GET['str'];
	
		//get applicants that match the given criteria
		$selectSQL = $selectSQL." AND (a.".$_GET['field']." like '".$szHow."')";

		$selectSQL = $selectSQL." GROUP BY a.intApplicantID";

		//$query_string=$_GET['field']."='".$szHow."'&go=Go";
		//$query_string=$_GET['field']."='".$szHow."'&go=Go";
		$query_string="field=$_GET[field]&how=$_GET[how]&str=$szHow&go=Go";
		
		if (isset($_GET['szOrderBy'])){
			$szOrderBy=$_GET['szOrderBy'];
		} else {
			$szOrderBy="intID";
		}
		
		report_generator($selectSQL,$_GET['page'],$query_string,$szOrderBy);
	}
	# include the footer
	include("../footer/footer.php");
?>