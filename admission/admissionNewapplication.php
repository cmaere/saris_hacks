<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
include('admissionMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Application Process';
	$szTitle = 'Register New Application';
	$szSubSection = 'Application Form';
	//$additionalStyleSheet = './general.css';
	include("admissionheader.php");
	
?>
<?php 
	
	if (isset($_POST['add']) && ($_POST['add'] == "Add New")){
		$intApplicantID=$_POST['aregno'];
		
		
		#check if RegNo Exist
			$sql = "SELECT intApplicantID FROM applicant WHERE intApplicantID = '$intApplicantID'";
			$result = mysql_query($sql) or die("kuna tatizo");
			$intApplicantIDFound = mysql_num_rows($result);
			if ($intApplicantIDFound) {
					echo "ZALONGWA Database System Imegundua Kuwa,<br> Registration Number Hii Ina Mtu Tayari";
					echo "<br> Tafadhari Chagua Nyingine!<hr><br>";
					
					}else{
					
								$sql="INSERT INTO applicant (intApplicantID) VALUES('$intApplicantID')";
								$result = mysql_query($sql) or die("ZALONGWA Database System Imegundua Kuwa,<br> Registration Number Hii Inamtu Tayari!" . mysql_error());
							    echo '<meta http-equiv = "refresh" content ="0; 
								url = admissionApplicationForm.php?intApplicantID='.$intApplicantID.'">';
								exit;
						}
							
   }
?>
<form name="form1" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
  <table width="200" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
      <th nowrap scope="row">Application RegNo: </th>
      <td><input name="aregno" type="text" id="aregno" size="30"></td>
      <td><input name="add" type="submit" id="add" value="Add New"></td>
    </tr>
  </table>
</form>
<?php
	# include the footer
	include("../footer/footer.php");
?>
