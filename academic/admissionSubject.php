<?php require_once('../Connections/zalongwa.php'); 
require_once('../Connections/sessioncontrol.php');
# include the header
include('lecturerMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Policy Setup';
	$szTitle = 'Subject Information';
	$szSubSection = 'Subject';
	include("lecturerheader.php");
?>
<?php
$currentPage = $_SERVER["PHP_SELF"];
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmInst")) {
$rawcode = $_POST['txtCode'];
$rawprog = $_POST['cmbprog'];
$code = ereg_replace("[[:space:]]+", " ",$rawcode);
$prog = ereg_replace("[[:space:]]+", " ",$rawprog);

#check if coursecode exist
$sql ="SELECT course.CourseCode 			
	  FROM course WHERE (course.CourseCode  = '$code') AND course.Programme = '$prog'";
$result = mysql_query($sql);
$coursecodeFound = mysql_num_rows($result);
if ($coursecodeFound) {
          $coursefound   = mysql_result($result,0,'CourseCode');
			print " This Course Code: '".$coursefound."' Do Exists!!"; 
			exit;
}else{
	   				   $insertSQL = sprintf("INSERT INTO course (CourseCode, CourseName, YearOffered, Capacity, Units, Department, Faculty, Programme, StudyLevel, Category, Hours) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['txtCode'], "text"),
                       GetSQLValueString($_POST['txtTitle'], "text"),
					   GetSQLValueString($_POST['cmbSem'], "text"),
					   GetSQLValueString($_POST['txtCapacity'], "text"),
                       GetSQLValueString($_POST['txtUnit'], "text"),
                       GetSQLValueString($_POST['cmbFac'], "text"),
                       GetSQLValueString($_POST['cmbInst'], "text"),
                       GetSQLValueString($_POST['cmbprog'], "text"),
                       GetSQLValueString($_POST['cmbLevel'], "text"),
                       GetSQLValueString($_POST['cmbCategory'], "text"),
                       GetSQLValueString($_POST['txtHours'], "text"));

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($insertSQL, $zalongwa) or die('You What? '.mysql_error());
  }
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmInstEdit")) {
 					   $updateSQL = sprintf("UPDATE course SET CourseName=%s, YearOffered=%s, Units=%s, Department=%s, Faculty=%s, Programme=%s, StudyLevel=%s, Category=%s, Hours=%s, Capacity=%s WHERE Id=%s",
                       GetSQLValueString($_POST['txtTitle'], "text"),
					   GetSQLValueString($_POST['cmbSem'], "text"),
                       GetSQLValueString($_POST['txtUnit'], "text"),
                       GetSQLValueString($_POST['cmbFac'], "text"),
                       GetSQLValueString($_POST['cmbInst'], "text"),
                       GetSQLValueString($_POST['cmbprog'], "text"),
                       GetSQLValueString($_POST['cmbLevel'], "text"),
                       GetSQLValueString($_POST['cmbCategory'], "text"),
                       GetSQLValueString($_POST['txtHours'], "text"),
                       GetSQLValueString($_POST['txtCapacity'], "text"),
					    GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($updateSQL, $zalongwa) or die(mysql_error());
 }
 
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
//control the display table
@$new=2;

mysql_select_db($database_zalongwa, $zalongwa);
$query_prog = "SELECT ProgrammeCode, Title FROM programme ORDER BY Title ASC";
$prog = mysql_query($query_prog, $zalongwa) or die(mysql_error());
$row_prog = mysql_fetch_assoc($prog);
$totalRows_prog = mysql_num_rows($prog);
$progcode = $row_prog['ProgrammeCode'];

mysql_select_db($database_zalongwa, $zalongwa);
$query_campus = "SELECT FacultyID, FacultyName FROM faculty ORDER BY FacultyName ASC";
$campus = mysql_query($query_campus, $zalongwa) or die(mysql_error());
$row_campus = mysql_fetch_assoc($campus);
$totalRows_campus = mysql_num_rows($campus);
$facultyid = $row_campus['FacultyID'];

mysql_select_db($database_zalongwa, $zalongwa);
$query_faculty = "SELECT DeptName FROM department ORDER BY DeptName ASC";
$faculty = mysql_query($query_faculty, $zalongwa) or die(mysql_error());
$row_faculty = mysql_fetch_assoc($faculty);
$totalRows_faculty = mysql_num_rows($faculty);

mysql_select_db($database_zalongwa, $zalongwa);
$query_semester = "SELECT Semester FROM terms ORDER BY Semester ASC";
$semester = mysql_query($query_semester, $zalongwa) or die(mysql_error());
$row_semester = mysql_fetch_assoc($semester);
$totalRows_semester = mysql_num_rows($semester);

mysql_select_db($database_zalongwa, $zalongwa);
$query_studylevel = "SELECT * FROM programmelevel ORDER BY Code ASC";
$studylevel = mysql_query($query_studylevel, $zalongwa) or die(mysql_error());
$row_studylevel = mysql_fetch_assoc($studylevel);
$totalRows_studylevel = mysql_num_rows($studylevel);


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$maxRows_inst = 10;
$pageNum_inst = 0;
if (isset($_GET['pageNum_inst'])) {
  $pageNum_inst = $_GET['pageNum_inst'];
}
$startRow_inst = $pageNum_inst * $maxRows_inst;

mysql_select_db($database_zalongwa, $zalongwa);
if (isset($_GET['course'])) {
  $key=$_GET['course'];
  $query_inst = "SELECT * FROM course WHERE CourseCode Like '%$key%' ORDER BY CourseCode ASC";
}else{
$query_inst = "SELECT * FROM course ORDER BY CourseCode ASC";
}
//$query_inst = "SELECT * FROM course ORDER BY CourseCode ASC";
$query_limit_inst = sprintf("%s LIMIT %d, %d", $query_inst, $startRow_inst, $maxRows_inst);
$inst = mysql_query($query_limit_inst, $zalongwa) or die(mysql_error());
$row_inst = mysql_fetch_assoc($inst);

if (isset($_GET['totalRows_inst'])) {
  $totalRows_inst = $_GET['totalRows_inst'];
} else {
  $all_inst = mysql_query($query_inst);
  $totalRows_inst = mysql_num_rows($all_inst);
}
$totalPages_inst = ceil($totalRows_inst/$maxRows_inst)-1;

$queryString_inst = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_inst") == false && 
        stristr($param, "totalRows_inst") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_inst = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_inst = sprintf("&totalRows_inst=%d%s", $totalRows_inst, $queryString_inst);
 
?>
<style type="text/css">
<!--
.style1 {color: #000000}
.style2 {color: #FFFFFF}
-->
</style>


<p><?php echo "<a href=\"admissionSubject.php?new=1\">"?>Add New Subject (Course) </p>
<?php @$new=$_GET['new'];
echo "</a>";
if (@$new<>1){
?>
<form name="form1" method="get" action="admissionSubject.php">
              Search by Course Code
              <input name="course" type="text" id="course" maxlength="50">
              <input type="submit" name="Submit" value="Search">
</form>
	   
<table border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td><strong>Programme</strong></td>
	<td><strong>Course</strong></td>
	<td><strong>Description</strong></td>
	<td><strong>Credits</strong></td>
	<td><strong>SemesterOffered</strong></td>
  </tr>
  <?php do { ?>

  <tr><?php $id = $row_inst['Id'];?>
		<?php
		#get programme title
		$progcode = $row_inst['Programme'];
		$qprogtitle = "SELECT Title FROM programme WHERE ProgrammeCode = '$progcode'";
		$dbprogtitle = mysql_query($qprogtitle);
		$row_progtitle = mysql_fetch_assoc($dbprogtitle);
		$progtitle = $row_progtitle['Title'];
		?>
	  <td nowrap><?php $id = $row_inst['Id']; echo $progtitle; ?></td>
      <td nowrap><?php $name = $row_inst['CourseCode']; echo "<a href=\"admissionSubject.php?edit=$id\">$name</a>"?></td>
	  <td nowrap><?php echo $row_inst['CourseName'] ?></td>
	  <td><?php echo $row_inst['Units']; ?></td>
	  <td><?php echo $row_inst['YearOffered']; ?></td>
  </tr>
  <?php } while ($row_inst = mysql_fetch_assoc($inst)); ?>
</table>
<a href="<?php printf("%s?pageNum_inst=%d%s", $currentPage, max(0, $pageNum_inst - 1), $queryString_inst); ?>">Previous</a><span class="style1"><span class="style2">......</span><?php echo min($startRow_inst + $maxRows_inst, $totalRows_inst) ?>/<?php echo $totalRows_inst ?> <span class="style1"></span><span class="style2">..........</span></span><a href="<?php printf("%s?pageNum_inst=%d%s", $currentPage, min($totalPages_inst, $pageNum_inst + 1), $queryString_inst); ?>">Next</a><br>
    			
<?php }else{?>
<form action="<?php echo $editFormAction; ?>" method="POST" name="frmInst" id="frmInst">
  <table width="200" border="1" cellpadding="0" cellspacing="0" bordercolor="#006600">
    <tr bgcolor="#CCCCCC">
      <th width="120" scope="row"><div align="right">Programme:</div></th>
<td colspan="3"><select name="cmbprog" id="cmbprog" title="<?php echo $row_prog['ProgrammeCode']; ?>">
  <?php
do {  
?>
  <option value="<?php echo $row_prog['ProgrammeCode']?>"><?php echo $row_prog['Title']?></option>
  <?php
} while ($row_prog = mysql_fetch_assoc($prog));
  $rows = mysql_num_rows($prog);
  if($rows > 0) {
      mysql_data_seek($prog, 0);
	  $row_prog = mysql_fetch_assoc($prog);
  }
?>
      </select></td>
    </tr>
 <tr bgcolor="#CCCCCC">
      <th scope="row"><div align="right">Faculty:</div></th>
<td colspan="3"><select name="cmbInst" id="cmbInst" title="<?php echo $row_campus['FacultyID']; ?>">
  <?php
do {  
?>
  <option value="<?php echo $row_campus['FacultyID']?>"><?php echo $row_campus['FacultyName']?></option>
  <?php
} while ($row_campus = mysql_fetch_assoc($campus));
  $rows = mysql_num_rows($campus);
  if($rows > 0) {
      mysql_data_seek($campus, 0);
	  $row_campus = mysql_fetch_assoc($campus);
  }
?>
      </select></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th scope="row"><div align="right">Department:</div></th>
      <td colspan="3"><select name="cmbFac" id="cmbFac" title="<?php echo $row_faculty['DeptName']; ?>">
        <?php
do {  
?>
        <option value="<?php echo $row_faculty['DeptName']?>"><?php echo $row_faculty['DeptName']?></option>
        <?php
} while ($row_faculty = mysql_fetch_assoc($faculty));
  $rows = mysql_num_rows($faculty);
  if($rows > 0) {
      mysql_data_seek($faculty, 0);
	  $row_faculty = mysql_fetch_assoc($faculty);
  }
?>
      </select></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Course Code:</div></th>
      <td colspan="3"><input name="txtCode" type="text" id="txtCode" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Course Title:</div></th>
      <td colspan="3"><input name="txtTitle" type="text" id="txtTitle" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Credits:</div></th>
      <td colspan="3" nowrap><input name="txtUnit" type="text" id="txtUnit" size="6">
      <b>Capacity:</b>
      <input name="txtCapacity" type="text" id="txtCapacity" size="10">	  </td>
    </tr>
	<tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Exam Regulation:</div></th>
      <td colspan="3"><select name="cmbLevel" id="cmbLevel" title="<?php echo $row_studylevel['Code']; ?>">
        <?php
do {  
?>
        <option value="<?php echo $row_studylevel['Code']?>"><?php echo $row_studylevel['StudyLevel']?></option>
        <?php
} while ($row_studylevel = mysql_fetch_assoc($studylevel));
  $rows = mysql_num_rows($studylevel);
  if($rows > 0) {
      mysql_data_seek($studylevel, 0);
	  $row_studylevel = mysql_fetch_assoc($studylevel);
  }
?>
      </select></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th scope="row"><div align="right">Semester:</div></th>
      <td colspan="3"><select name="cmbSem" id="cmbSem" title="<?php echo $row_semester['Semester']; ?>">
        <?php
do {  
?>
        <option value="<?php echo $row_semester['Semester']?>"><?php echo $row_semester['Semester'];?></option>
        <?php
} while ($row_semester = mysql_fetch_assoc($semester));
  $rows = mysql_num_rows($semester);
  if($rows > 0) {
      mysql_data_seek($semester, 0);
	  $row_semester = mysql_fetch_assoc($semester);
  }
?>
      </select></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th scope="row"><div align="right">Category:</div></th>
      <td width="114">
        
        <div align="left">
            <select name="cmbCategory" id="cmbCategory">
              <option value="0">Select Category</option>
              <option value="1">Theoretical</option>
              <option value="2">Clinical</option>
            </select>
        </div></td>
      <td width="46"><div align="right"><strong>Hours:</strong></div></td>
      <td width="76">
        <div align="left">
          <input name="txtHours" type="text" id="txtHours" size="6" />
        </div></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th scope="row">&nbsp;</th>
      <td colspan="3"><div align="center">
        <input type="submit" name="Submit" value="Add Record">
      </div></td>
    </tr>
  </table>
    <input type="hidden" name="MM_insert" value="frmInst">
</form>
<?php } 
if (isset($_GET['edit'])){
#get post variables
$key = $_GET['edit'];

mysql_select_db($database_zalongwa, $zalongwa);
$query_instEdit = "SELECT * FROM course WHERE Id ='$key'";
$instEdit = mysql_query($query_instEdit, $zalongwa) or die(mysql_error());
$row_instEdit = mysql_fetch_assoc($instEdit);
$totalRows_instEdit = mysql_num_rows($instEdit);

$queryString_inst = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_inst") == false && 
        stristr($param, "totalRows_inst") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_inst = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_inst = sprintf("&totalRows_inst=%d%s", $totalRows_inst, $queryString_inst);

?>
<form action="<?php echo $editFormAction; ?>" method="POST" name="frmInstEdit" id="frmInstEdit">
 <table width="200" border="1" cellpadding="0" cellspacing="0" bordercolor="#006600">
    <tr bgcolor="#CCCCCC">
      <th scope="row"><div align="right">Programme:</div></th>
<?php
#get programme title
$progcode = $row_instEdit['Programme'];
$qprogtitle = "SELECT Title FROM programme WHERE ProgrammeCode = '$progcode'";
$dbprogtitle = mysql_query($qprogtitle);
$row_progtitle = mysql_fetch_assoc($dbprogtitle);
$progtitle = $row_progtitle['Title'];
?>
<td><select name="cmbprog" id="cmbprog" title="<?php echo $row_prog['ProgrammeCode']; ?>">
<option value="<?php echo $row_instEdit['Programme']?>"><?php echo $progtitle ?></option>
  <?php
do {  
?>
<option value="<?php echo $row_prog['ProgrammeCode']?>"><?php echo $row_prog['Title']?></option>
  <?php
} while ($row_prog = mysql_fetch_assoc($prog));
  $rows = mysql_num_rows($prog);
  if($rows > 0) {
      mysql_data_seek($prog, 0);
	  $row_prog = mysql_fetch_assoc($prog);
  }
?>
      </select></td>
    </tr>
<tr bgcolor="#CCCCCC">
      <th scope="row"><div align="right">Faculty:</div></th>
<?php
#get faculty name
$facultyid = $row_instEdit['Faculty'];
$qfacultytitle = "SELECT FacultyName FROM faculty WHERE FacultyID = '$facultyid'";
$dbfacultytitle = mysql_query($qfacultytitle);
$row_facultytitle = mysql_fetch_assoc($dbfacultytitle);
$facultytitle = $row_facultytitle['FacultyName'];
?>
<td><select name="cmbInst" id="cmbInst" title="<?php echo $row_campus['FacultyID']; ?>">
<option value="<?php echo $row_instEdit['Faculty']?>"><?php echo $facultytitle?></option>
  <?php
do {  
?>
<option value="<?php echo $row_campus['FacultyID']?>"><?php echo $row_campus['FacultyName']?></option>
  <?php
} while ($row_campus = mysql_fetch_assoc($campus));
  $rows = mysql_num_rows($campus);
  if($rows > 0) {
      mysql_data_seek($campus, 0);
	  $row_campus = mysql_fetch_assoc($campus);
  }
?>
      </select></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th scope="row"><div align="right">Department:</div></th>
      <td><select name="cmbFac" id="cmbFac" title="<?php echo $row_faculty['DeptName']; ?>">
	  <option value="<?php echo $row_instEdit['Department']?>"><?php echo $row_instEdit['Department']?></option>
        <?php
do {  
?>
        <option value="<?php echo $row_faculty['DeptName']?>"><?php echo $row_faculty['DeptName']?></option>
        <?php
} while ($row_faculty = mysql_fetch_assoc($faculty));
  $rows = mysql_num_rows($faculty);
  if($rows > 0) {
      mysql_data_seek($faculty, 0);
	  $row_faculty = mysql_fetch_assoc($faculty);
  }
?>
      </select></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Course Code:</div></th>
      <td><?php echo $row_instEdit['CourseCode']; ?></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Course Title:</div></th>
      <td><input name="txtTitle" type="text" id="txtTitle" value="<?php echo $row_instEdit['CourseName']; ?>" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Credits:</div></th>
      <td><input name="txtUnit" type="text" id="txtUnit" value="<?php echo $row_instEdit['Units']; ?>" size="6">
      <b>Capacity:</b>
	  <input name="txtCapacity" type="text" id="txtCapacity" value="<?php echo $row_instEdit['Capacity']; ?>"size="10">
</td>
    </tr>
	<tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Exam Regulation:</div></th>
		<?php
		#get programme title
		$studycode = $row_instEdit['StudyLevel'];
		$qstudytitle = "SELECT StudyLevel FROM programmelevel WHERE Code = '$studycode'";
		$dbstudytitle = mysql_query($qstudytitle);
		$row_studytitle = mysql_fetch_assoc($dbstudytitle);
		$studytitle = $row_studytitle['StudyLevel'];
		?>
      <td><select name="cmbLevel" id="cmbLevel" title="<?php echo $row_studylevel['StudyLevel']; ?>">
	  <option value="<?php echo $row_instEdit['StudyLevel']?>"><?php echo $studytitle?></option>
        <?php
do {  
?>
        <option value="<?php echo $row_studylevel['Code']?>"><?php echo $row_studylevel['StudyLevel']?></option>
        <?php
} while ($row_studylevel = mysql_fetch_assoc($studylevel));
  $rows = mysql_num_rows($studylevel);
  if($rows > 0) {
      mysql_data_seek($studylevel, 0);
	  $row_studylevel = mysql_fetch_assoc($studylevel);
  }
?>
      </select></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th scope="row"><div align="right">Semester:</div></th>
      <td>
	  <select name="cmbSem" id="cmbSem" title="<?php echo $row_semester['Semester']; ?>">
	  <option value="<?php echo $row_instEdit['YearOffered'];?>"><?php echo $row_instEdit['YearOffered'];?></option>

        <?php
		do {  
		?>
				<option value="<?php echo $row_semester['Semester']?>"><?php echo $row_semester['Semester'];?></option>
				<?php
		} while ($row_semester = mysql_fetch_assoc($semester));
		  $rows = mysql_num_rows($semester);
		  if($rows > 0) {
			  mysql_data_seek($semester, 0);
			  $row_semester = mysql_fetch_assoc($semester);
		  }
		?>
      </select></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th scope="row"><div align="right">Category:</div></th>
      <td width="114" nowrap="nowrap">
            <select name="cmbCategory" id="cmbCategory">
              <option value="<?php echo $row_instEdit['Category']; ?>"selected><?php
			  $cat = $row_instEdit['Category'];
			  if($cat==1){
			   echo 'Theoretical'; 
			  }elseif($cat==2){
			   echo 'Clinical'; 
			  }else{
			   echo 'Select Category'; 
              }			   
			   ?>
			   </option>
              <option value="1">Theoretical</option>
              <option value="2">Clinical</option>
              <option value="0">Aready Counted</option>
            </select>
        <strong>Hours:</strong><input name="txtHours" type="text" id="txtHours" value="<?php echo $row_instEdit['Hours']; ?>"size="4" maxlength="4" />
      </td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th scope="row"><input name="id" type="hidden" id="id" value="<?php echo $key ?>"></th>
      <td><div align="center">
        <input type="submit" name="Submit" value="Edit Record">
      </div></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="frmInstEdit">
</form>
<?php
}
	# include the footer
	include("../footer/footer.php");

@mysql_free_result($inst);

@mysql_free_result($instEdit);

@mysql_free_result($faculty);

@mysql_free_result($campus);
?>