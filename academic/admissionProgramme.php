<?php
require_once('../Connections/sessioncontrol.php');
# include the header
include('lecturerMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Policy Setup';
	$szTitle = 'Programme Information';
	$szSubSection = 'Programme';
	include("lecturerheader.php");
	
?>
<?php
$currentPage = $_SERVER["PHP_SELF"];
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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
$query_campus = "SELECT CampusID, Campus FROM campus";
$campus = mysql_query($query_campus, $zalongwa) or die(mysql_error());
$row_campus = mysql_fetch_assoc($campus);
$totalRows_campus = mysql_num_rows($campus);

mysql_select_db($database_zalongwa, $zalongwa);
$query_faculty = "SELECT FacultyName FROM faculty";
$faculty = mysql_query($query_faculty, $zalongwa) or die(mysql_error());
$row_faculty = mysql_fetch_assoc($faculty);
$totalRows_faculty = mysql_num_rows($faculty);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmInst")) {
  $insertSQL = sprintf("INSERT INTO programme (ProgrammeCode, ProgrammeName, Title, Faculty) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['txtCode'], "text"),
					   GetSQLValueString($_POST['txtSname'], "text"),
                       GetSQLValueString($_POST['txtFname'], "text"),
                       GetSQLValueString($_POST['cmbFac'], "text"));

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($insertSQL, $zalongwa) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmInstEdit")) {
  $updateSQL = sprintf("UPDATE programme SET ProgrammeCode=%s, ProgrammeName=%s, Title=%s, Faculty=%s, CampusID=%s WHERE ProgrammeID=%s",
                       GetSQLValueString($_POST['txtCode'], "text"),
					   GetSQLValueString($_POST['txtSname'], "text"),
                       GetSQLValueString($_POST['txtFname'], "text"),
                       GetSQLValueString($_POST['cmbFac'], "text"),
                       GetSQLValueString($_POST['cmbInst'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($updateSQL, $zalongwa) or die(mysql_error());
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
  $query_inst = "SELECT * FROM programme WHERE ProgrammeName Like '%$key%' OR ProgrammeCode like '%$key%' ORDER BY ProgrammeName ASC";
}else{
$query_inst = "SELECT * FROM programme ORDER BY ProgrammeName ASC";
}
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
.style1 {color: #FFFFFF}
.style2 {color: #000000}
-->
</style>

<p><?php echo "<a href=\"admissionProgramme.php?new=1\">"?>Add New Degree Programme </p>
<?php @$new=$_GET['new'];
echo "</a>";
if (@$new<>1){
?>
<form name="form1" method="get" action="admissionProgramme.php">
              Search by Course Code
              <input name="course" type="text" id="course" maxlength="50">
              <input type="submit" name="Submit" value="Search">
       </form>
<table border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td><strong>Code</strong></td>
    <td><strong>Degree </strong></td>
	<td><strong>Faculty</strong></td>
  </tr>
  <?php do { ?>
  <tr>
    <td nowrap><?php $id = $row_inst['ProgrammeID']; $name = $row_inst['ProgrammeCode'];
	echo "<a href=\"admissionProgramme.php?edit=$id\">$name</a>"?></td>
    <td nowrap><?php echo $row_inst['ProgrammeName']; ?></td>
	 <td><?php echo $row_inst['Faculty']; ?></td>
  </tr>
  <?php } while ($row_inst = mysql_fetch_assoc($inst)); ?>
</table>
<a href="<?php printf("%s?pageNum_inst=%d%s", $currentPage, max(0, $pageNum_inst - 1), $queryString_inst); ?>">Previous</a><span class="style1">......<span class="style2"><?php echo min($startRow_inst + $maxRows_inst, $totalRows_inst) ?>/<?php echo $totalRows_inst ?> </span>..........</span><a href="<?php printf("%s?pageNum_inst=%d%s", $currentPage, min($totalPages_inst, $pageNum_inst + 1), $queryString_inst); ?>">Next</a><br>
<?php }else{?>
<form action="<?php echo $editFormAction; ?>" method="POST" name="frmInst" id="frmInst">
  <table width="200" border="1" cellpadding="0" cellspacing="0" bordercolor="#006600">
    <tr bgcolor="#CCCCCC">
      <th scope="row"><div align="right">Institution:</div></th>
<td><select name="cmbInst" id="cmbInst" title="">
  <?php
do {  
?>
  <option value="<?php echo $row_campus['CampusID']?>"><?php echo $row_campus['Campus']?></option>
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
      <th scope="row"><div align="right">Faculty:</div></th>
      <td><select name="cmbFac" id="cmbFac">
        <?php
do {  
?>
        <option value="<?php echo $row_faculty['FacultyName']?>"><?php echo $row_faculty['FacultyName']?></option>
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
      <th nowrap scope="row"><div align="right">Degree Code:</div></th>
      <td><input name="txtCode" type="text" id="txtCode" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Short  Name:</div></th>
      <td><input name="txtSname" type="text" id="txtSname" size="40"></td>
    </tr>
	<tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Full Name:</div></th>
      <td><input name="txtFname" type="text" id="txtFname" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th scope="row">&nbsp;</th>
      <td><div align="center">
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
$query_instEdit = "SELECT * FROM programme WHERE programmeID ='$key'";
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
      <th scope="row"><div align="right">Institution:</div></th>
<td><select name="cmbInst" id="cmbInst" title="<?php echo $row_instEdit['CampusID']; ?>"><?php echo $row_instEdit['CampusID']; ?>
  <?php
do {  
?>
  <option value="<?php echo $row_campus['CampusID']?>"><?php echo $row_campus['Campus']?></option>
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
      <th scope="row"><div align="right">Faculty:</div></th>
      <td><select name="cmbFac" id="cmbFac" title="<?php echo $row_instEdit['Faculty']; ?>"><?php echo $row_instEdit['Faculty']; ?>
        <?php
do {  
?>
        <option value="<?php echo $row_faculty['FacultyName']?>"><?php echo $row_faculty['FacultyName']?></option>
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
      <th nowrap scope="row"><div align="right">Degree Code:</div></th>
      <td><input name="txtCode" type="text" id="txtCode" value="<?php echo $row_instEdit['ProgrammeCode']; ?>" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Short Name:</div></th>
      <td><input name="txtSname" type="text" id="txtSname" value="<?php echo $row_instEdit['ProgrammeName']; ?>" size="40"></td>
    </tr>
	    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Full Name:</div></th>
      <td><input name="txtFname" type="text" id="txtFname" value="<?php echo $row_instEdit['Title']; ?>" size="40"></td>
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