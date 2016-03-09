<?php require_once('../Connections/zalongwa.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmInst")) {
  $insertSQL = sprintf("INSERT INTO programmecutoffpint (ProgrammeID, AYear, MCutoffpoint, FCutoffpoint) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['cmbProg'], "int"),
                       GetSQLValueString($_POST['txtyear'], "int"),
                       GetSQLValueString($_POST['txtPointm'], "double"),
                       GetSQLValueString($_POST['txtPointf'], "double"));

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($insertSQL, $zalongwa) or die(mysql_error());
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
$query_campus = "SELECT * FROM academicyear WHERE Status = -1";
$campus = mysql_query($query_campus, $zalongwa) or die(mysql_error());
$row_campus = mysql_fetch_assoc($campus);
$totalRows_campus = mysql_num_rows($campus);

mysql_select_db($database_zalongwa, $zalongwa);
$query_faculty = "SELECT ProgrammeID, ProgrammeName FROM programme";
$faculty = mysql_query($query_faculty, $zalongwa) or die(mysql_error());
$row_faculty = mysql_fetch_assoc($faculty);
$totalRows_faculty = mysql_num_rows($faculty);

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
$query_inst = "SELECT * FROM programmecutoffpint";
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

require_once('../Connections/sessioncontrol.php');

mysql_select_db($database_zalongwa, $zalongwa);
$query_programme = "SELECT academicyear.AYear,       
							programme.ProgrammeName,
							programmecutoffpint.Id,
							programmecutoffpint.MCutoffpoint, 
							programmecutoffpint.FCutoffpoint
					FROM programmecutoffpint    
							INNER JOIN academicyear ON 
							(programmecutoffpint.AYear = academicyear.intYearID)    
							INNER JOIN programme ON (programmecutoffpint.ProgrammeID = programme.ProgrammeID)";
$programme = mysql_query($query_programme, $zalongwa) or die(mysql_error());
$row_programme = mysql_fetch_assoc($programme);
$totalRows_programme = mysql_num_rows($programme);

# include the header
include('admissionMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Policy Setup';
	$szTitle = 'Cut-off Points Information';
	$szSubSection = 'CutoffPoint';
	include("admissionheader.php");
	
?>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>

<p><?php echo "<a href=\"admissionCutoffpoint.php?new=1\">Add New Cutoffpoint </a>"?></p>
<?php @$new=$_GET['new'];
if (@$new<>1){
?>

<p>&nbsp;</p>
Cut-off Points for Year: <?php echo $row_programme['AYear']; ?>
<table border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td>ProgrammeName</td>
    <td>MCutoffpoint</td>
    <td>FCutoffpoint</td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php $id = $row_programme['Id'];  $name = $row_programme['ProgrammeName'];
	echo "<a href=\"admissionCutoffpoint.php?edit=$id\">$name</a>"?>
	</td>
    <td><div align="center"><?php echo $row_programme['MCutoffpoint']; ?></div></td>
    <td><div align="center"><?php echo $row_programme['FCutoffpoint']; ?></div></td>
  </tr>
  <?php } while ($row_programme = mysql_fetch_assoc($programme)); ?>
</table>
<a href="<?php printf("%s?pageNum_programme=%d%s", $currentPage, max(0, $pageNum_programme - 1), $queryString_programme); ?>">Previous</a> <span class="style1">.................</span><?php echo min($startRow_programme + $maxRows_programme, $totalRows_programme) ?> /<?php echo $totalRows_programme ?> <span class="style1">........................</span><a href="<?php printf("%s?pageNum_programme=%d%s", $currentPage, min($totalPages_programme, $pageNum_programme + 1), $queryString_programme); ?>">Next</a><br>
<?php }else{?>
<form action="<?php echo $editFormAction; ?>" method="POST" name="frmInst" id="frmInst">
  <table width="200" border="1" cellpadding="0" cellspacing="0" bordercolor="#006600">
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Academic Year:</div></th>
      <td><?php $year = $row_campus['intYearID']; echo $row_campus['AYear']; ?>
      <input name="txtyear" type="hidden" id="txtyear" value="<?php echo $year ?>"></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th scope="row"><div align="right"> Programme:</div></th>
      <td><select name="cmbProg" id="cmbProg">
        <?php
do {  
?>
        <option value="<?php echo $row_faculty['ProgrammeID']?>"><?php echo $row_faculty['ProgrammeName']?></option>
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
      <th nowrap scope="row"><div align="right">Cutoff Points (M):</div></th>
      <td><input name="txtPointm" type="text" id="txtPointm" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row">Cutoff Points (F):</th>
      <td><input name="txtPointf" type="text" id="txtPointf" size="40"></td>
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
$query_instEdit = "SELECT * FROM programmecutoffpint WHERE Id ='$key'";
$instEdit = mysql_query($query_instEdit, $zalongwa) or die(mysql_error());
$row_instEdit = mysql_fetch_assoc($instEdit);
$totalRows_instEdit = mysql_num_rows($instEdit);

$maxRows_programme = 10;
$pageNum_programme = 0;
if (isset($_GET['pageNum_programme'])) {
  $pageNum_programme = $_GET['pageNum_programme'];
}
$startRow_programme = $pageNum_programme * $maxRows_programme;

mysql_select_db($database_zalongwa, $zalongwa);
$query_programme = "SELECT academicyear.AYear,        							programme.ProgrammeName, 							programmecutoffpint.MCutoffpoint,  	 programmecutoffpint.Id,						programmecutoffpint.FCutoffpoint FROM programmecutoffpint     							INNER JOIN academicyear ON  							(programmecutoffpint.AYear = academicyear.intYearID)     							INNER JOIN programme ON (programmecutoffpint.ProgrammeID = programme.ProgrammeID)";
$query_limit_programme = sprintf("%s LIMIT %d, %d", $query_programme, $startRow_programme, $maxRows_programme);
$programme = mysql_query($query_limit_programme, $zalongwa) or die(mysql_error());
$row_programme = mysql_fetch_assoc($programme);

if (isset($_GET['totalRows_programme'])) {
  $totalRows_programme = $_GET['totalRows_programme'];
} else {
  $all_programme = mysql_query($query_programme);
  $totalRows_programme = mysql_num_rows($all_programme);
}
$totalPages_programme = ceil($totalRows_programme/$maxRows_programme)-1;

$queryString_programme = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_programme") == false && 
        stristr($param, "totalRows_programme") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_programme = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_programme = sprintf("&totalRows_programme=%d%s", $totalRows_programme, $queryString_programme);

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
      <th nowrap scope="row"><div align="right">Academic Year:</div></th>
      <td>      <?php echo $row_programme['AYear']; ?>
      <input name="txtyear" type="hidden" id="txtyear" value="<?php echo $row_programme['Id']; ?>">      </td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th scope="row"><div align="right"> Programme :</div></th>
      <td><select name="cmbProg" id="cmbProg">
        <?php
do {  
?>
        <option value="<?php echo $row_faculty['ProgrammeID']?>"><?php echo $row_faculty['ProgrammeName']?></option>
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
      <th nowrap scope="row"><div align="right">Cutoff Points (M):</div></th>
      <td><input name="txtPointm" type="text" id="txtPointm" value="<?php echo $row_programme['MCutoffpoint']; ?>" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row">Cutoff Points (F):</th>
      <td><input name="txtPointf" type="text" id="txtPointf" value="<?php echo $row_programme['FCutoffpoint']; ?>" size="40"></td>
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

mysql_free_result($programme);

@mysql_free_result($faculty);

@mysql_free_result($campus);
?>