<?php 
	require_once('../Connections/sessioncontrol.php');
	# include the header
	include('administratorMenu.php');
	$szSection = 'Security';
	$szTitle = 'Change Password';
	$szSubSection = 'Change Password';
	include('administratorheader.php');
	
//page content starts here
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

if (isset($_POST["MM_update"]) && ($_POST["MM_update"] == "fmAdd")) {
$txtusername = addslashes($_POST['username']);
$pass = addslashes($_POST['txtoldPWD']);
  // Generate jlungo hash for old password
 $hashold = "{jlungo-hash}" . base64_encode(pack("H*", sha1($pass)));

$newpass = addslashes($_POST['txtnewPWD']);
  // Generate jlungo hash for new password
 $hashnew = "{jlungo-hash}" . base64_encode(pack("H*", sha1($newpass)));

if(strlen($newpass)<5){
echo ("Password too short, Password must be at least 5 characters!");
exit;
}
$sql = "SELECT * FROM security WHERE password ='$hashold'";
$Result1 = mysql_query($sql, $zalongwa) or die(mysql_error());
if(!mysql_num_rows($Result1)){
		echo 'Invalid Password, 
		<br> Click the Back Button to Try'; 
		exit;
}else
  $updateSQL = "UPDATE security SET password='$hashnew' WHERE UserName='$txtusername' AND password ='$hashold'";
  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($updateSQL, $zalongwa) or die(mysql_error());

  $updateGoTo = "../Login.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  echo '<meta http-equiv = "refresh" content ="0; 
						url ='. $updateGoTo.'">';
					exit;
}

$colname_changepassword = "1";
if (isset($_COOKIE['UserName'])) {
  $colname_changepassword = (get_magic_quotes_gpc()) ? $_COOKIE['UserName'] : addslashes($_COOKIE['UserName']);
}
$query_changepassword = sprintf("SELECT * FROM security WHERE UserName = '$username'", $colname_changepassword);
$changepassword = mysql_query($query_changepassword, $zalongwa) or die(mysql_error());
$row_changepassword = mysql_fetch_assoc($changepassword);
$totalRows_changepassword = mysql_num_rows($changepassword);
?>
<SCRIPT ID=clientEventHandlersJS LANGUAGE=javascript>
<!--

function fmAdd_onsubmit() {
if (fmAdd.txtoldPWD.value == "" || fmAdd.txtnewPWD.value == "" || fmAdd.selectPosition.value=="" || fmAdd.txtrenewPWD.value=="")
	{window.alert("OUT Student Information System Asks You to Fill in the Blank Text Fields");
	return false;
	}
if (fmAdd.txtnewPWD.value != fmAdd.txtrenewPWD.value)
	{window.alert("Password Texts donot Match, Enter them again, ZALONGWA");
	return false;
}
}

//-->
</SCRIPT>
<SCRIPT LANGUAGE="javascript1.2">
//form object
//var fmAdd=document.forms(0);
//Boolean to track if error found
var foundErr;
//Form element index number which the first error occured
var focusOn;

function check_form() {
		foundErr = false; focusOn = -1;
		//Username field must be at least 5 char.
		if(fmAdd.txtoldPWD.value.length<5){
		alert("RegNo too short! RegNo must be at least 4 Charaters");
		foundErr = true; focusOn=0;
		}
		
		//Passowrd field must be at least 5 char.
		if(fmAdd.txtnewPWD.value.length<5){
		alert("Birth Date too short! Birth Date must be at least 5 Charaters");
		foundErr = true; 
			if(focusOn==-1) focusOn=1;
		}
		
		//Hass any error occured?
		if(foundErr){
			//Yes. Focus on Which the first occured
			fmAdd.elements.focus(focusOn);
		}else{
			// No. Submit the form
			fmAdd.submit()
		}
}
</SCRIPT>
 <FORM action="<?php echo $editFormAction; ?>" method=POST id=fmAdd name=fmAdd LANGUAGE=javascript onsubmit="return fmAdd_onsubmit()" enctype="multipart/form-data">
          <table width=100% height="100%" border=0 align="center" cellpadding=0 cellspacing=0>
            <tr>
              <td valign=TOP align=LEFT colspan=3 BGCOLOR="#993300"></td>
            </tr>
            <tr>
              <td valign=TOP align=LEFT width=1 bgcolor="#993300"></td>
              <td valign=MIDDLE align=RIGHT width=100% bgcolor="#006699">
                <div align="left"><font color="#FFFF33"><b>USER CHANGE PASSWORD FORM</b></font></div></td>
              <td valign=TOP align=RIGHT width=1 bgcolor="#993300"></td>
            </tr>
            <tr>
              <td></td>
              <td><div align="left"></div></td>
              <td></td>
            </tr>
            <tr>
              <TD VALIGN=TOP ALIGN=LEFT BGCOLOR="#993300"></TD>
              <TD VALIGN=TOP ALIGN=LEFT BGCOLOR="#FFE5B2">
                <div align="left">
                  <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=453>
                    <TR>
                      <TD height="278" VALIGN=MIDDLE WIDTH=10 ALIGN=CENTER> <br>
                      </TD>
                      <TD WIDTH=10></TD>
                      <TD WIDTH=434 VALIGN=TOP ALIGN=LEFT>
                        <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=284>
                          <TR>
                            <TD VALIGN=TOP ALIGN=LEFT colspan="2" height="18"><div align="right"></div></TD>
                            <TD VALIGN=TOP ALIGN=LEFT WIDTH=1 BGCOLOR="#993300"><div align="right"></div></TD>
                            <TD VALIGN=TOP ALIGN=LEFT WIDTH=5><div align="right"></div></TD>
                            <TD VALIGN=TOP ALIGN=LEFT width="160"><div align="left"></div></TD>
                            <td width="1"></td>
                          </TR>
                          <TR>
                            <TD VALIGN=MIDDLE ALIGN=RIGHT colspan="2" height="28" nowrap><div align="right"><font color="#0000CC"> </font></div></TD>
                            <TD ROWSPAN=7><div align="right"></div></TD>
                            <TD VALIGN=MIDDLE ALIGN=LEFT><div align="right"></div></TD>
                            <TD VALIGN=MIDDLE ALIGN=LEFT><div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">
                            </font></div></TD>
                            <td></td>
                          </TR>
                          <TR>
                            <TD VALIGN=MIDDLE ALIGN=RIGHT colspan="2" height="28" nowrap><div align="right"><font color="#0000CC"> NAME: </font></div></TD>
                            <TD VALIGN=MIDDLE ALIGN=LEFT><div align="right"></div></TD>
                            <TD VALIGN=MIDDLE ALIGN=LEFT><div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">
                                  <?php echo $row_changepassword['FullName']; ?>
                            </font></div></TD>
                            <td></td>
                          </TR>
                          <TR>
                            <TD VALIGN=middle height="28" colspan="2" ALIGN=right>
                              <div align="right"><font color="#0000CC">ID REGNO:</font></div></TD>
                            <TD VALIGN=MIDDLE ALIGN=LEFT rowspan="2"><div align="right"></div></TD>
                            <TD VALIGN=TOP ALIGN=LEFT><div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">
                                  <?php echo $row_changepassword['RegNo']; ?>
                            </font><font color="#0000CC"></font></div></TD>
                            <td></td>
                          </TR>
                          <TR>
                            <TD colspan="2" VALIGN=MIDDLE rowspan="2" ALIGN=RIGHT nowrap><div align="right"><font color="#0000CC">POSITION:</font></div></TD>
                            <TD VALIGN=MIDDLE rowspan="2" ALIGN=LEFT><div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">
                                  <select name="selectPosition" id="selectPosition">
                                    <option value="student">student</option>
                                    <option value="student">Lecturer</option>
                                    <option value="student">Administrator</option>
                                    <option value="student">Technician</option>
                                  </select>
                            </font></div></TD>
                            <td height="9"></td>
                          </TR>
                          <TR>
                            <TD VALIGN=MIDDLE ALIGN=LEFT rowspan="2"><div align="right"></div></TD>
                            <td height="7"></td>
                          </TR>
                          <TR>
                            <TD colspan="2" rowspan="2" ALIGN=RIGHT VALIGN=MIDDLE nowrap><div align="right"><font color="#0000CC">LOGIN (eg. jlungo for Juma Lungo): </font></div></TD>
                            <TD VALIGN=MIDDLE rowspan="2" ALIGN=LEFT><div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">
                                  <?php echo $row_changepassword['UserName']; ?>
                                  <input name="username" type="hidden" id="username" value="<?php echo $row_changepassword['UserName']; ?>">
                            </font></div></TD>
                            <td height="9"></td>
                          </TR>
                          <TR>
                            <TD VALIGN=MIDDLE ALIGN=LEFT rowspan="2"><div align="right"></div></TD>
                            <td height="19"></td>
                          </TR>
                          <TR>
                            <TD colspan="2" VALIGN=MIDDLE rowspan="2" ALIGN=RIGHT><div align="right"><font color="#0000CC">Old Password:</font></div></TD>
                            <TD height="9"><div align="right"></div></TD>
                            <TD VALIGN=MIDDLE rowspan="2" ALIGN=LEFT><div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">
                                  <input name="txtoldPWD" type="password" id="txtoldPWD" size="30" >
                            </font></div></TD>
                            <td></td>
                          </TR>
                          <TR>
                            <TD height="19"><div align="right"></div></TD>
                            <TD VALIGN=MIDDLE ALIGN=LEFT rowspan="2"><div align="right"></div></TD>
                            <td></td>
                          </TR>
                          <TR>
                            <TD colspan="2" rowspan="2" ALIGN=RIGHT VALIGN=MIDDLE nowrap><div align="right"><font color="#0000CC">New Password: </font></div></TD>
                            <TD height="9"><div align="right"></div></TD>
                            <TD VALIGN=MIDDLE rowspan="2" ALIGN=LEFT><div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">
                                  <input name="txtnewPWD" type="password" id="txtnewPWD" size="30" >
                            </font></div></TD>
                            <td></td>
                          </TR>
                          <TR>
                            <TD height="19"><div align="right"></div></TD>
                            <TD VALIGN=MIDDLE ALIGN=LEFT rowspan="2"><div align="right"><font color="#0000CC"></font></div></TD>
                            <td></td>
                          </TR>
                          <TR>
                            <TD WIDTH=1 height="9"><div align="right"></div></TD>
                            <TD colspan="2" rowspan="2" VALIGN=MIDDLE ALIGN=RIGHT><div align="right"><font color="#0000CC">Comfirm Password: </font></div></TD>
                            <TD VALIGN=MIDDLE rowspan="2" ALIGN=LEFT><div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#0000CC">
                                  <input name="txtrenewPWD" type="password" id="txtrenewPWD" size="30" >
                            </font></div></TD>
                            <td></td>
                          </TR>
                          <TR>
                            <TD height="19"><div align="right"></div></TD>
                            <TD VALIGN=MIDDLE ALIGN=LEFT><div align="right"><font color="#0000CC"></font></div></TD>
                            <td></td>
                          </TR>
                          <TR>
                            <TD height="19"><div align="right"></div></TD>
                            <TD width="126" valign="bottom" align="right"><div align="right"></div></TD>
                            <TD VALIGN=MIDDLE ALIGN=LEFT BGCOLOR="#993300"><div align="right"></div></TD>
                            <TD VALIGN=TOP ALIGN=LEFT><div align="right"><font color="#0000CC"></font></div></TD>
                            <TD valign="top" nowrap bgcolor="#FFE5B2">
                                <div align="left">
                                  <input type="submit" value="Submit" name="Submit">
                                  <span class="style1 style45"> ...............</span>
                                  <input type="reset" value=" Reset" name="Reset">
                                </div></TD>
                            <td></td>
                          </TR>
                          <TR>
                            <TD height="14"><div align="right"></div></TD>
                            <TD COLSPAN=4 VALIGN=MIDDLE ALIGN=CENTER> <div align="right"></div></TD>
                            <td></td>
                          </TR>
                          <TR>
                            <TD height="2"></TD>
                            <TD></TD>
                            <TD></TD>
                            <TD></TD>
                            <TD></TD>
                            <td></td>
                          </TR>
                      </table></TD>
                    </TR>
                  </TABLE>
                  <font color="#FFE5B2"> ........... </font> </div></TD>
              <TD VALIGN=TOP ALIGN=LEFT BGCOLOR="#993300"></TD>
            </TR>
            <tr>
              <td valign=TOP align=LEFT colspan=3 BGCOLOR="#993300"></td>
            </tr>
            <tr>
              <td height="2"><img height="1" width="1" src="/images/spacer.gif"></td>
              <td><div align="left"><img height="1" width="568" src="/images/spacer.gif"></div></td>
              <td><img height="1" width="1" src="/images/spacer.gif"></td>
            </tr>
          </TABLE>
          <input type="hidden" name="MM_insert" value="true">
          <input type="hidden" name="MM_update" value="fmAdd">
        </FORM>
<?php

	# include the footer
	include("../footer/footer.php");
?>