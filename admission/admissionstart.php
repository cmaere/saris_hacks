<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('admissionMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Admission Process';
	$szSubSection = 'Search Student';
	$szTitle = 'Search Student Record';
	include('admissionheader.php');

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


//Print Room Allocation Report
if (isset($_POST['search']) && ($_POST['search'] == "Search")) {
#get post variables
$key = $_POST['key'];
			
require_once('../Connections/zalongwa.php'); 
$sql = "SELECT student.Id, student.Name, student.Sex, student.ProgrammeofStudy, student.Faculty, student.Sponsor, student.EntryYear, student.RegNo, student.Photo
FROM student
WHERE (student.Name LIKE '%$key%') OR (student.RegNo LIKE '%$key%') ORDER BY student.Name";

$result = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
$query = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

$all_query = mysql_query($query);
$totalRows_query = mysql_num_rows($query);
/* Printing Results in html */
if (mysql_num_rows($query) > 0){
echo "<p>Total Records Found: $totalRows_query </p>";
echo "<table border='1' cellpadding='0' cellspacing='0'>";
echo "<tr><td> S/No </td><td> Name </td><td> RegNo </td><td> Sex </td><td> Degree </td><td> Faculty </td><td> Sponsor </td><td> Registered </td><td> Photo </td></tr>";
$i=1;
while($result = mysql_fetch_array($query)) {
		$id = stripslashes($result["Id"]);
		$year = stripslashes($result["AYear"]);
		$Name = stripslashes($result["Name"]);
		$RegNo = stripslashes($result["RegNo"]);
		$sex = stripslashes($result["Sex"]);
		$degree = stripslashes($result["ProgrammeofStudy"]);
		$faculty = stripslashes($result["Faculty"]);
		$sponsor = stripslashes($result["Sponsor"]);
		$entryyear = stripslashes($result["EntryYear"]);
		$photo = stripslashes($result["Photo"]);
		$citeria = stripslashes($result["RNumber"]);
			//get degree name
			$qdegree = "Select Title from programme where ProgrammeCode = '$degree'";
			$dbdegree = mysql_query($qdegree);
			$row_degree = mysql_fetch_array($dbdegree);
			$programme = $row_degree['Title'];

			echo "<tr><td><a href=\"admissionRegistrationForm.php?id=$id&RegNo=$RegNo\">$i</a></td>";
			echo "<td>$Name</td>";
			echo "<td>$RegNo</td>";
			echo "<td>$sex</td>";
			echo "<td>$programme</td>";
			echo "<td>$faculty</td>";
			echo "<td>$sponsor</td>";
			echo "<td>$entryyear</td>";
			echo "<td><a href=\"studentphoto.php?id=$id&RegNo=$RegNo\">edit</a><img src=$photo></td>";
			echo "</tr>";
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
            <table width="284" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="2" nowrap><div align="center"></div>
          </td>
        </tr>
        <tr>
          <td nowrap>Name or RegNo:</td>
          <td bordercolor="#ECE9D8" bgcolor="#CCCCCC"><span class="style67">
          <input name="key" type="text" id="key" size="40" maxlength="40">
          </span></td>
        </tr>
        <tr>
          <td nowrap><div align="right"></div></td>
          <td bgcolor="#CCCCCC"><div align="center">
            <input type="submit" name="search" value="Search">
          </div></td>
        </tr>
  </table>
</form>
<?php
}
include('../footer/footer.php');
?>
