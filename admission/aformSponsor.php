<?php 
	# include the header
	require_once('../Connections/sessioncontrol.php');
	require_once('../Connections/zalongwa.php');
	# include the header
	include('admissionMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Policy Setup';
	$szTitle = 'Sponsor Details';
	$szSubSection = 'Sponsor';
	include("admissionheader.php");
?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "staff")) {
  $insertSQL = sprintf("INSERT INTO sponsors (Name, Address, comment) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['szSponsor'], "text"),
                       GetSQLValueString($_POST['szAddress'], "text"),
                       GetSQLValueString($_POST['szTelephone'], "text"));

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($insertSQL, $zalongwa) or die(mysql_error());
}
?>
   <form name="staff" method="POST" action="<?php echo $editFormAction; ?>">
<table cellspacing="1" cellpadding="0" border="0" width=100% bgcolor="#CCCCCC">
              <TR bgcolor="ffffff">
                <TD align=right valign=top>Name of Sponsor:</TD>
                <TD><input type="text" name="szSponsor" size="30" class="vform" id="szSponsor">
                </TD>
              </TR>
			  <TR bgcolor="ffffff">
                <TD align=right valign=top>Address:</TD>
                <TD><input type="text" class="vform" size="30" name="szAddress"></TD>
              </TR>
			  
			  <TR bgcolor="ffffff">
                <TD align=right valign=top>Telephone No.:</TD>
                <TD><input type="text" class="vform" size="30" name="szTelephone"></TD>
              </TR>
			  
			  <tr>
				<td>&nbsp;</td>
				<td><input  class="vform" type="submit" name="action" value="update" onClick="return formValidator()"></td>
			  </tr>
</table>
<input type="hidden" name="MM_insert" value="staff">
</form>
<?php 
	include("../footer/footer.php");
?>
