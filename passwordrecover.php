<?php
	session_start();
	session_cache_limiter('nocache');       
	@$loginerror  = $_SESSION['loginerror'];
	$_SESSION['loginerror'] = "";
	
 require_once('Connections/zalongwa.php'); 
	#Get Organisation Name
	$qorg = "SELECT * FROM organisation";
	$dborg = mysql_query($qorg);
	$row_org = mysql_fetch_assoc($dborg);
	$org = $row_org['Name'];
	$post = $row_org['Address'];
	$phone = $row_org['tel'];
	$fax = $row_org['fax'];
	$email = $row_org['email'];
	$website = $row_org['website'];
	$city = $row_org['city'];

header("Cache-control: private"); // IE 6 Fix. 
if (isset($accesscheck)) {
  $GLOBALS['PrevUrl'] = $accesscheck;
  session_register('PrevUrl');
}
#update userpassword
if (isset($_POST["save"]) && ($_POST["save"] == "Save Password")) {
$txtusername = addslashes($_POST['username']);
$newpass = addslashes($_POST['txtnewPWD']);
$_SESSION['username'] = $txtusername;
  // Generate jlungo hash for new password
 $hashnew = "{jlungo-hash}" . base64_encode(pack("H*", sha1($newpass)));
if(strlen($newpass)<5){
	$_SESSION['loginerror'] = 'Password too short, Password must be at least 5 characters! '; 
	$_POST['ok'] ="OK";
	$_SESSION['thisuser'] = $txtusername;
}
  $updateSQL = "UPDATE security SET password='$hashnew' WHERE UserName='$txtusername'";
  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($updateSQL, $zalongwa) or die(mysql_error());

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  if($_POST['ok'] <>"OK"){
  echo '<meta http-equiv = "refresh" content ="0; 
						url ='. $updateGoTo.'">';
					exit;
	}
}

#end update user password
#change password
if (isset($_POST['ok']) && $_POST['ok'] !="") {
$colname_changepassword = "1";
$thisuser = $_SESSION['thisuser'];
$query_changepassword = sprintf("SELECT UserName, FullName, RegNo FROM security WHERE UserName = '$thisuser'", $colname_changepassword);
$changepassword = mysql_query($query_changepassword, $zalongwa) or die(mysql_error());
$row_changepassword = mysql_fetch_assoc($changepassword);
$totalRows_changepassword = mysql_num_rows($changepassword);

$browser  =  $_SERVER["HTTP_USER_AGENT"];   
$ip  =  $_SERVER["REMOTE_ADDR"];   
$today = date("F j, Y, g:i a");
$msg = $thisuser.' - Recovered  a Password';
$sql="INSERT INTO stats(ip,browser,received,page) VALUES('$ip','$browser',now(),'$msg')";   
$result = mysql_query($sql) or die("Siwezi kuingiza data.<br>");
}
#end of change passsword

if (isset($_POST['Submit']) && $_POST['Submit'] !="") {
	#get post variables
	$rawkey = addslashes(trim($_POST['txtRegNo']));
	$userregno = ereg_replace("[[:space:]]+", " ",$rawkey);
	$byear = addslashes($_POST['txtYear']);
	$bmon = addslashes($_POST['txtMonth']);
	$bday = addslashes($_POST['txtDay']);
	$bdate = $bday . " - " . $bmon . " - " . $byear;
	//ensure it retrieve records for students only, lecturers and housing officers cannot retrieve their passwords
	if($bdate){
	$sql=sprintf("SELECT student.DBirth,
					  security.UserName,
					  security.password,
					  security.RegNo,
					  security.Module,
					  security.Position
                FROM security
                      INNER JOIN student ON (security.RegNo = student.RegNo)
                WHERE security.RegNo = '$userregno' AND student.DBirth = '$bdate'",
 		get_magic_quotes_gpc() ? $userregno : addslashes($userregno), get_magic_quotes_gpc() ? $bdate : addslashes($bdate)); 
		
		$result = @mysql_query($sql, $zalongwa) or die("query imeshindikana");
		$loginFoundUser = mysql_num_rows($result);
 		
						if ($loginFoundUser) {
							$loginName		= mysql_result($result,0,'UserName');
							$password 		= mysql_result($result,0,'password');
							$module 		= mysql_result($result,0,'Module');
							if ($module==3){
							$_SESSION['loginerror'] = "no";
							}else{
							$_SESSION['loginerror'] = "Only Students can Retrieve Password, <br> Please Contact Your Local ZALONGWA HelpDesk Officers";
							}
													
						}else{
								$_SESSION['loginerror'] =  'Please, You Must Enter Your Correct Registration Number!<br> Year of Birth Date Should Be in 4 digits e.g. 1982'; 
					}
		}else{
								$_SESSION['loginerror'] =  'Please, You Must Enter Your Correct Registration Number!<br> Year of Birth Date Should Be in 4 digits e.g. 1982'; 
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Zalongwa Software</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="keywords" content="ZALONGWA, zalongwa, Student Information System (SIS), accommodation information system, database system, examination database system, Student records system, database system">
<meta name="description" content="School Management Information System, Database System, Juma Lungo Lungo Lungo Lungo, Database System , Student Information System (SIS), Accommodation Record Keeping, Examination Results System, Student Normninal Roll Database">
<meta name="authors" content="Juma Hemed Lungo">
<link rel="stylesheet" type="text/css" href="themes/bluelagoon/style.css" media="screen,projection" />
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
		if(fmAdd.txtRegNo.value.length<4){
		alert("RegNo too short! RegNo must be at least 4 Charaters");
		foundErr = true; focusOn=0;
		return false;
		}
		
		//Passowrd field must be at least 5 char.
		if(fmAdd.bdate.value.length<7){
		alert("Birth Date too short! Birth Date must be at least 7 Charaters");
		foundErr = true; 
			if(focusOn==-1) focusOn=1;
		}
		
		//Hass any error occured?
		if(foundErr){
			//Yes. Focus on Which the first occured
			fmAdd.elements.focus(focusOn);
			return false;
		}else{
			// No. Submit the form
			fmAdd.submit()
		}
}
</SCRIPT>
<script src="css/zalongwa.js" type="text/javascript"> </script>
<BODY onLoad="f_setfocus();">
<style type="text/css">
<!--
.style2 {color: #FFFFCC}
.style3 {color: #FFFFFF}
.style4 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
}
.style5 {color: #000000}
-->
</style>
<table border="0" cellspacing="0" cellpadding="0" width=100%>
<tr>
	<td align=center><br>
	  <br>
		<!-- Registration Form Starts -->
		<table border="0" cellspacing="0" cellpadding="0" width=540 style="border:2px solid rgb(119,119,119)">
		<tr>
			<td align=left>
			
			
			<table border="0" cellspacing="0" cellpadding='0' width='100%' background="themes/images/loginTopHeaderBg.gif">
			<tr>
				<td align=left><img src="themes/images/loginTopHeaderName.gif"></td>
				<td align=right><!--img src="themes/images/loginTopVersion.gif"--></td>
			</tr>
			</table>
			<table border="0" cellspacing="0" cellpadding='6' width='100%'>
			<tr>
				<td align=left valign=top class=small style="padding:10px">
					<!-- Sign in box -->
					<div align="center"><?php echo strtoupper($org)?><br>ZALONGWA PASSWORD RECOVERY ALGORITHM</div>
					<hr>
<FORM action="<?php echo $_SERVER['PHP_SELF']?>" method="post" id="fmAdd" name="fmAdd" onSubmit="return check_form()" LANGUAGE="javascript"  enctype="multipart/form-data">
        <table width="330" border=0 align="center" cellpadding=0 cellspacing=0> 
 <?php
 #startpassword form
 if (isset($_SESSION['thisuser'])){
?>
					<TABLE WIDTH=100% BORDER=0 align="center" CELLPADDING=0 CELLSPACING=0>
							<?php
							$errlen = strlen($_SESSION['loginerror']);
							if ($errlen>5)
							{
							?>
							<tr>
								<td colspan="3"><b class="small"><font color="Brown">
								<div align="center"></div><?php echo $_SESSION['loginerror']?></div>
								</font>
								</b>
								</td>
							</tr>
							<?php
							}
							?>
						  <TR>
                            <TD colspan="3" ALIGN=LEFT VALIGN=TOP><div align="right"></div>                              
                            <div align="right" class="style6">
                              <div align="left" class="style7">Please Change Your Password: </div>
                            </div>                              <div align="right"><font color="#0000CC"> </font></div>                              <div align="right"></div></TD>
                            <TD colspan="2" rowspan="9" ALIGN=LEFT VALIGN=TOP><div align="left"></div>                              <div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">
                            </font></div>                              <div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">
                            </font></div>                            <div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">
                            </font></div>                            <div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">
                            </font></div>                            <div align="right"><font color="#0000CC"></font></div>                            <div align="right"><font color="#0000CC"></font></div>
                            <div align="left">                                </div></TD>
                          </TR>
                          <TR>
                            <TD VALIGN=MIDDLE ALIGN=RIGHT colspan="2" height="28" nowrap><div align="right" class="style6"><font color="#0000CC"> NAME: </font></div></TD>
                            <TD VALIGN=MIDDLE ALIGN=LEFT><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><?php echo $row_changepassword['FullName']; ?></font></TD>
                          </TR>
                          <TR>
                            <TD height="28" colspan="2" ALIGN=right VALIGN=middle nowrap>
                            <div align="right" class="style6"><font color="#0000CC">ID REGNO:</font></div></TD>
                            <TD VALIGN=MIDDLE ALIGN=LEFT rowspan="2"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><?php echo $row_changepassword['RegNo']; ?> </font><font color="#0000CC">&nbsp;</font></TD>
                          </TR>
                          <TR>
                            <TD height="14" colspan="2" rowspan="2" ALIGN=RIGHT VALIGN=MIDDLE nowrap><span class="style6"></span>                              <div align="right" class="style6"><font color="#0000CC">User Name:</font></div></TD>
                          </TR>
                          <TR>
                            <TD ALIGN=LEFT VALIGN=MIDDLE><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><?php echo $row_changepassword['UserName']; ?>
                                <input name="username" type="hidden" id="username" value="<?php echo $row_changepassword['UserName']; ?>">
                            </font></TD>
                          </TR>
                          <TR>
                            <TD height="19" colspan="2" ALIGN=RIGHT VALIGN=MIDDLE nowrap><div align="right" class="style6"><font color="#0000CC">New Password: </font></div>                              <div align="right"><span class="style6"></span></div>                              <div align="right"><span class="style6"></span></div></TD>
                            <TD VALIGN=MIDDLE ALIGN=LEFT><div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">
                                  <input name="txtnewPWD" type="password" id="txtnewPWD" size="30" >
                            </font></div></TD>
                          </TR>
                          <TR>
                            <TD colspan="2"><div align="right"><span class="style6"></span></div>                              <div align="right"><span class="style6"></span></div>                              <div align="right" class="style6"><font color="#0000CC">Comfirm Password: </font></div></TD>
                            <TD VALIGN=MIDDLE ALIGN=LEFT><div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#0000CC">
                                  <input name="txtrenewPWD" type="password" id="txtrenewPWD" size="30" >
                            </font></div></TD>
                          </TR>
                          <TR>
                            <TD height="19" colspan="2"><div align="right"></div>                              <div align="right"></div>                              <div align="right"></div></TD>
                            <TD VALIGN=TOP ALIGN=LEFT><div align="center"><font color="#0000CC">
                              <input type="submit" value="Save Password" name="save">
                              <span class="style1 style45"> </span>
                            </font></div></TD>
                          </TR>
                      </table>		
					  <input type="hidden" name="MM_insert" value="true">
          <input type="hidden" name="MM_update" value="fmAdd">
<?php
session_cache_limiter('nocache');
$_SESSION = array();
session_unset(); 
session_destroy(); 
exit;
}
#end change password form
if ($_SESSION['loginerror']=="no"){
?>
					 <table width="319" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
                                    <tr>
                                      <td width="130" nowrap><div align="right"><span class="style48">Your Username is:</span></div></td>
                                      <td width="261">  - <?php echo $loginName?> &nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td nowrap><div align="right"><span class="style48">Write it Down ! </span></div></td>
                                      <td nowrap="nowrap"><div align="center">
                                          <?php 
							$_SESSION['thisuser'] = $loginName;
						echo "Click OK to Get Your Password</a>";
						?>
                                          <input name="ok" type="submit" id="ok" value="OK">
</div></td>
                                    </tr>
                      </table>
					  <?php 
					  exit;
					  }else{?>
              <table width="400" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
							<?php
							$errlen = strlen($_SESSION['loginerror']);
							if ($errlen>5)
							{
							?>
							<tr>
								<td colspan="6"><b class="small"><font color="Brown">
								<div align="center"></div><?php echo $_SESSION['loginerror']?></div>
								</font>
								</b>
								</td>
							</tr>
							<?php
							}
							?>
                    <tr>
                      <td height="39" nowrap><div align="right"><span class="style6">ID RegNo(nnnnn/X.YYYY):</span></div></td>
                      <td><input name="txtRegNo" type="text" id="txtRegNo" size="30" value="<?php echo((isset($_POST["txtRegNo"]))?$_POST["txtRegNo"]:"") ?>"></td>
                    </tr>
					<tr>
                      <td height="40" nowrap><div align="right"><span class="style6">Birth Date (DD-MM-YYYY):</span></div></td>
                      <td nowrap>
                            <select name="txtDay" id="select">
							<option value="<?php echo((isset($_POST["txtDay"]))?$_POST["txtDay"]:"") ?>"><?php echo((isset($_POST["txtDay"]))?$_POST["txtDay"]:"") ?></option>  
                              <option value=>--</option>
                              <option value="01">01</option>
                              <option value="02">02</option>
                              <option value="03">03</option>
                              <option value="04">04</option>
                              <option value="05">05</option>
                              <option value="06">06</option>
                              <option value="07">07</option>
                              <option value="08">08</option>
                              <option value="09">09</option>
                              <option value="10">10</option>
                              <option value="11">11</option>
                              <option value="12">12</option>
                              <option value="13">13</option>
                              <option value="14">14</option>
                              <option value="15">15</option>
                              <option value="16">16</option>
                              <option value="17">17</option>
                              <option value="18">18</option>
                              <option value="19">19</option>
                              <option value="20">20</option>
                              <option value="21">21</option>
                              <option value="22">22</option>
                              <option value="23">23</option>
                              <option value="24">24</option>
                              <option value="25">25</option>
                              <option value="26">26</option>
                              <option value="27">27</option>
                              <option value="28">28</option>
                              <option value="29">29</option>
                              <option value="30">30</option>
                              <option value="31">31</option>
                            </select>
                            <select name="txtMonth" id="txtMonth">
							<option value="<?php echo((isset($_POST["txtMonth"]))?$_POST["txtMonth"]:"") ?>"><?php echo((isset($_POST["txtMonth"]))?$_POST["txtMonth"]:"") ?></option>  
                              <option value=>-----------</option>
                              <option value="01">January</option>
                              <option value="02">February</option>
                              <option value="03">March</option>
                              <option value="04">April</option>
                              <option value="05">May</option>
                              <option value="06">June</option>
                              <option value="07">July</option>
                              <option value="08">August</option>
                              <option value="09">September</option>
                              <option value="10">October</option>
                              <option value="11">November</option>
                              <option value="12">December</option>
                            </select>
                        <input name="txtYear" type="text" id="txtYear" size="4" maxlength="4" value="<?php echo((isset($_POST["txtYear"]))?$_POST["txtYear"]:"") ?>"></td>
					</tr>
                    <tr>
                      <td height="42">&nbsp;</td>
                      <td><div align="center">
					  	<input type="submit" value="Search Username" name="Submit">
                        
                      </div></td>
                    </tr>
              </table>
				  
				 <div align="center">You May Contact KCN HelpDesk <span class="style19"><br />
				   Website: <?php //echo $website?> http://www.kcn.unima.mw </span><br />
                   <span class="style19">E-Mail: <?php //echo $email?>registrar@kcn.unima.mw<br />
Tel.: <?php //echo $phone?>+265 1 751 622 <br />
Mobile: <?php //echo $phone?>+265 888 381 535 OR +265 888 308 631</span></div>
                </form>
					<?php
					} //close else statement for displaying a table
			session_cache_limiter('nocache');
			$_SESSION = array();
			session_unset(); 
			session_destroy(); 
			?></td>
			  </table>
			
			</td>
		</tr>
	  </table>
	
			<!-- Shadow -->
			<table border=0 cellspacing=0 cellpadding=0 width=640>
			<tr>
				<td><img src="themes/images/loginBottomShadowLeft.gif"></td>
				<td width=100% background="themes/images/loginBottomShadowBg.gif"><img src="themes/images/loginBottomShadowBg.gif"></td>
				<td><img src="themes/images/loginBottomShadowRight.gif"></td>
			</tr>
	  </table>
	</td>
</tr>
</table>