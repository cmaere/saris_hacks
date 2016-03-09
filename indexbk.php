<?php
	session_start();
	session_cache_limiter('nocache');       
	@$loginerror  = $_SESSION['loginerror'];
	
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

<SCRIPT ID=clientEventHandlersJS LANGUAGE=javascript>
<!--
function userlogin_onsubmit() {
if (userlogin.textusername.value == "" || userlogin.textpassword.value == "")
return false;
}
//-->
</SCRIPT>
<script src="css/zalongwa.js" type="text/javascript"> </script>
<BODY onLoad="f_setfocus( userlogin_onsubmit );">
<table border="0" cellspacing="0" cellpadding="0" width=100%>
<tr>
	<td align=center><br><br><br><br>
	
		<!-- Login Starts -->
		<table border="0" cellspacing="0" cellpadding="0" width=540 style="border:2px solid rgb(119,119,119)">
		<tr>
			<td align=left>
			<table border="0" cellspacing="0" cellpadding='0' width='100%' background="themes/images/loginTopHeaderBg.gif">
			<tr>
				<td align=center valign="top" nowrap="nowrap" style="color:#003300"><b><?php echo strtoupper($org)?></b><img src="themes/images/loginTopHeaderName.gif"><!--img src="themes/images/loginTopVersion.gif"--></td>
			  </tr>
			</table>
			<table border="0" cellspacing="0" cellpadding='10' width='100%'>
			<tr>
				<td align=left valign=top width=50% class=small style="padding:10px">
					<!-- Promo Text and Image -->
					<table border=0> 
					<tr>
					<td>
					  <div align="center"><img src="images/logo.jpg" alt="Institution Logo" width="83" height="127" title="Institution Logo" /></div>
					  </td>
					</tr>
					<tr>
					<td nowrap="nowrap" class=small style="padding-left:10px; color:#737373">

                      <div align="center"><?php // echo strtoupper($org)?>
                         Student Academic Register Information System (SARIS)<br>
 <br>
- Forgot your password ?<a href="passwordrecover.php" class="style16"> Get Help </a><br>
- New User ? <a href="registration.php">Create Account </a><br>
					  </div></td>
					</tr>
					</table>
					
				</td>
				<td align=center valign=top width=50%>
					<!-- Sign in box -->
					<form action="userlogin.php" method="post" enctype="multipart/form-data" name="userlogin" class="style15" onsubmit="return userlogin_onsubmit()" LANGUAGE=javascript>
					<table border="0" cellspacing="0" cellpadding="0" width=100%>
					<tr><td align=left><img src="themes/images/loginSignIn.gif" alt="Sign In" title="Sign In" border="0"></td></tr>
					<tr>
						<td background="themes/images/loginSignInShade.gif" style="background-repeat: repeat-x;" align=center valign=top class=small>
						<br>
							<table border=0 cellspacing=0 cellpadding=5 width=100% class="small">
							<tr><td width=30% class=small align=right nowrap>User Name:</td><td width=70% class=small><input  name="textusername" type="text" class="small" tabindex="1"   value="<?php echo $_SESSION['username']?>" size="30"></td></tr>
							<tr><td class=small align=right>Password: </td><td class=small><input class="small" type="password" size='30' name="textpassword"  value="" tabindex="2"></td></tr>
							<tr>
								<td colspan=2 style="padding:0px">
								</td>
							</tr>
							<tr><td colspan=2>&nbsp;</td></tr>
							<?php
							if (isset($loginerror ) && $loginerror !="")
							{
							?>
							<tr>
								<td colspan="2"><b class="small"><font color="Brown">
								<?php echo $loginerror?>
								</font>
								</b>
								</td>
							</tr>
							<?php
							session_cache_limiter('nocache');
							$_SESSION = array();
							session_unset(); 
							session_destroy(); 
							}
							?>
							<tr>
								<td colspan=2 style="padding:0px" align=center>
								  <div align="right">
								    <input class=small title="" accesskey=""  type="image" src="themes/images/loginBtnSignIn.gif" name="Login" value="   "  tabindex="3">	
							      </div></td>
							</tr>
						  </table>
						<br>
						</td>
					</tr>
					</table>
					
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
</BODY>
</html>