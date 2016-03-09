<?php require_once('../Connections/zalongwa.php'); ?>
<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
include('admissionMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Policy Setup';
	$szTitle = 'Academic Year';
	$szSubSection = 'Academic Year';
	include("admissionheader.php");
	
?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmAyear")) {
        $year = $_POST['txtYear'];
        $query_year = "SELECT * FROM academicyear WHERE AYear = '$year' ";
		$result = mysql_query($query_year) or die("Hii ngumu");
		$totalRows_result = mysql_num_rows($result);
		if ($totalRows_result == 0) {
  $insertSQL = sprintf("INSERT INTO academicyear (AYear, Status) VALUES (%s, %s)",
                       GetSQLValueString($_POST['txtYear'], "text"),
                       GetSQLValueString(isset($_POST['chStatus']) ? "true" : "", "defined","1","0"));

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($insertSQL, $zalongwa) or die(mysql_error());
  }
}

$maxRows_ayear = 10;
$pageNum_ayear = 0;
if (isset($_GET['pageNum_ayear'])) {
  $pageNum_ayear = $_GET['pageNum_ayear'];
}
$startRow_ayear = $pageNum_ayear * $maxRows_ayear;

mysql_select_db($database_zalongwa, $zalongwa);
$query_ayear = "SELECT * FROM academicyear";
$query_limit_ayear = sprintf("%s LIMIT %d, %d", $query_ayear, $startRow_ayear, $maxRows_ayear);
$ayear = mysql_query($query_limit_ayear, $zalongwa) or die(mysql_error());
$row_ayear = mysql_fetch_assoc($ayear);

if (isset($_GET['totalRows_ayear'])) {
  $totalRows_ayear = $_GET['totalRows_ayear'];
} else {
  $all_ayear = mysql_query($query_ayear);
  $totalRows_ayear = mysql_num_rows($all_ayear);
}
$totalPages_ayear = ceil($totalRows_ayear/$maxRows_ayear)-1;

$queryString_ayear = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_ayear") == false && 
        stristr($param, "totalRows_ayear") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_ayear = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_ayear = sprintf("&totalRows_ayear=%d%s", $totalRows_ayear, $queryString_ayear);
?>

<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>

<p>Add New Year</p>
<p> 
</p>

<table border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td>Academic Year</td>
    <td>Status</td>
    <td>Description</td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo $row_ayear['AYear']; ?></td>
    <td><?php $b=$row_ayear['Status']; echo $row_ayear['Status']; ?></td>
    <td><?php if (($b== -1) || ($b==1)) 
					{echo "Current Year";
					}else{
							echo "Not Current Year" ;
							}?></td>
  </tr>
  <?php } while ($row_ayear = mysql_fetch_assoc($ayear)); ?>
</table>
<p>&nbsp;<a href="<?php printf("%s?pageNum_ayear=%d%s", $currentPage, max(0, $pageNum_ayear - 1), $queryString_ayear); ?>">Previous</a><span class="style1"> ..............</span><?php echo min($startRow_ayear + $maxRows_ayear, $totalRows_ayear) ?>/<?php echo $totalRows_ayear ?><span class="style1">...............</span><a href="<?php printf("%s?pageNum_ayear=%d%s", $currentPage, min($totalPages_ayear, $pageNum_ayear + 1), $queryString_ayear); ?>">Next</a> </p>
<form action="<?php echo $editFormAction; ?>" method="POST" name="frmAyear" id="frmAyear">
  <table width="200" border="0" cellspacing="0" cellpadding="0">
    <tr bgcolor="#999999">
      <th nowrap scope="row"><div align="right">Academic Year:</div></th>
      <td><input name="txtYear" type="text" id="txtYear"></td>
    </tr>
    <tr bgcolor="#999999">
      <th nowrap scope="row"><div align="right">Current Year: </div></th>
      <td><input name="chStatus" type="checkbox" id="chStatus" value="checkbox"></td>
    </tr>
    <tr bgcolor="#999999">
      <th scope="row">&nbsp;</th>
      <td><input type="submit" name="Submit" value="Update"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="frmAyear">
</form>
<p>&nbsp;</p>
<?php

	# include the footer
	include("../footer/footer.php");

mysql_free_result($ayear);
?>