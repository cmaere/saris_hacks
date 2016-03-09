<?php require_once('../Connections/zalongwa.php'); ?>
<?php
session_start();
header("Cache-control: private"); // IE 6 Fix. 
@$auth_level = $_SESSION['auth_level'];
@$RegNo = $_GET['from'];
@$username = $_SESSION['username'];

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmsuggestion")) {
  $insertSQL = sprintf("INSERT INTO suggestion (received, fromid, toid, message) VALUES (now(), %s, %s, %s)",
                       GetSQLValueString($_POST['regno'], "text"),
                       GetSQLValueString($_POST['toid'], "text"),
                       GetSQLValueString($_POST['message'], "text"));

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($insertSQL, $zalongwa) or die(mysql_error());

  $insertGoTo = "administratorindex.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_zalongwa, $zalongwa);
$query_suggestionbox = "SELECT suggestion.received, suggestion.fromid, suggestion.toid, suggestion.message FROM suggestion";
$suggestionbox = mysql_query($query_suggestionbox, $zalongwa) or die(mysql_error());
$row_suggestionbox = mysql_fetch_assoc($suggestionbox);
$totalRows_suggestionbox = mysql_num_rows($suggestionbox);
 
if(!$username){
	echo ("Session Expired, <a href=\"ReLogin.php\"> Click Here<a> to Re-Login");
	echo '<meta http-equiv = "refresh" content ="0; 
	url = ReLogin.php">';
	exit;
}
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
<link rel="stylesheet" type="text/css" href="/master.css">

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
.style60 {font-size: 11px; color: #990000;}
.style61 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style63 {font-size: 12px; color: #990000;}
.style64 {color: #FFFFCC}
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
        <td height="69" colspan="7" align="center" valign="middle"> <img src="/images/Nkurumah.gif" width="724" height="69" align="left"></td>
      </tr>
      <tr>
        <td width="55" rowspan="5" valign="top">
            <table width="69%" height="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#FFFFCC" bgcolor="#99CCCC">
          <tr class="style35">
            <td height="0" colspan="2" nowrap><div align="center" class="style47"><img src="/images/bd21312_.gif" alt="Your Profile" width="15" height="15"></div></td>
            <td colspan="3" nowrap class="style58"><div align="left" class="style58"><?php print "<a href=\"administratorprofile.php?username=$username\">Your Profile</a>";?><span class="style59"> <font face="Verdana">&nbsp;</font> </span></div></td>
            </tr>
         		  <tr class="style35">
            <td height="20" align="right" valign="middle" nowrap><div align="center" class="style47"><img src="/images/bd21312_.gif" alt="Room Application" width="15" height="15"></div></td>
            <td colspan="4" align="left" valign="middle" nowrap class="style35"><span class="style58"><?php print "<a href=\"administratorcheckconnections.php?username=$username\">Check Connections</a>";?></span></td>
            </tr>
			 <tr class="style35">
            <td height="20" align="right" valign="middle" nowrap><div align="center" class="style47"><img src="/images/bd21312_.gif" alt="Room Application" width="15" height="15"></div></td>
            <td colspan="4" align="left" valign="middle" nowrap class="style35"><span class="style58"><?php print "<a href=\"administratoradhocqueries.php?username=$username\">Database Maintenance</a>";?></span></td>
            </tr>
				  <tr class="style35">
            <td height="20" align="right" valign="middle" nowrap><div align="center" class="style47"><img src="/images/bd21312_.gif" alt="Room Application" width="15" height="15"></div></td>
            <td colspan="4" align="left" valign="middle" nowrap class="style35"><span class="style58"><?php print "<a href=\"adminmanageuser.php?username=$username\">Manage Users</a>";?></span></td>
            </tr>
          <tr class="style35">
            <td height="20" align="right" valign="middle" nowrap><div align="center" class="style47"><img height=15 alt=Room Allocation 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></div></td>
            <td colspan="4" align="left" valign="middle" nowrap class="style35"><span class="normaltext style24"><?php print "<a href=\"administratorWebStatistics.php?username=$username\">Web Statistics</a>";?> </span></td>
            </tr>
          <tr class="style35">
            <td height="20" colspan="2" align="right" valign="middle" nowrap><div align="center" class="style47"><strong><img height=15 alt=Suggestion Box 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></strong></div></td>
            <td height="20" colspan="3" align="right" valign="middle" nowrap class="style35"><div align="left" class="style24"><?php print "<a href=\"administratorcheckmessage.php?username=$username\">Suggestion Box</a>";?> </div></td>
            </tr>
           <tr class="style35">
            <td height="20" colspan="2" align="right" valign="middle" nowrap><div align="center" class="style47"><strong><img height=15 alt=Change Password Network 
                  hspace=4 src="/images/bd21312_.gif" width=15 
                  vspace=5 border=0></strong></div></td>
            <td height="20" align="right" valign="middle" nowrap class="style35"><div align="left" class="style24">
              <div align="left" class="style34"><?php print "<a href=\"changepassword.php?username=$username\">Change Password</a>";?></div>
                              
</div></td>
          </tr>
           <tr class="style35">
            <td height="2" colspan="2" nowrap><div align="center"><span class="style35"><span class="style35"><span class="style55"><span class="style55"><span class="style47"><span class="style47"><img src="/images/bd21312_.gif" alt="Sign Out" width="15" height="15"></span></span></span></span></span></span></div></td>
            <td colspan="3" nowrap class="style35"><div align="left" class="style24"><?php print "<a href=\"signout.php?username=$username\">Sign Out</a>";?> </div></td>
            </tr>
        </table></td>
        <td><div align="left"><span class="style34"><strong>Suggestion Box <span class="style64">..............................................</span><a href="/student/studentcheckmessage.php">Check Message</a></strong></span></div></td>
        <td bgcolor="#99CCCC">&nbsp;</td>
      </tr>
      <tr>
        <td width="672" height="579" valign="top"><div align="left">
          <form action="<?php echo $editFormAction; ?>" method="POST" name="frmsuggestion" id="frmsuggestion">
            <table width="529" border="0">
              <tr>
                <td width="95" height="21"><div align="right"><strong>Send To:</strong></div></td>
                <td width="424" nowrap> <?php echo $RegNo; ?>
                  <input name="regno" type="hidden" id="regno" value="admin">
                  <input name="toid" type="hidden" id="toid" value="<?php echo $RegNo; ?>">
                  <input name="received" type="hidden" id="received" value="<?php $today = date("F j, Y"); echo $RegNo; ?>"></td>
              </tr>
              <tr>
                <td height="189"><div align="right"><strong>Message:</strong></div></td>
                <td><textarea name="message" cols="75" rows="13" class="normaltext" id="message"></textarea></td>
              </tr>
			  <tr>
                <td height="28" nowrap><div align="right"><strong>Post Message:</strong></div></td>
                <td nowrap><div align="center">
                  <input name="Send" type="submit" value="Post Message">
                  <span class="style64">................................................</span>
                  <input type="reset" name="Reset" value="Crear Message">
                </div></td>
              </tr>
            </table>
              <input type="hidden" name="MM_insert" value="frmsuggestion">
          </form>
		</div></td>
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
<?php
mysql_free_result($suggestionbox);
?>
