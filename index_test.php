<?php
//ini_set('display_errors', 1);

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

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- kamuzu college of nursing saris-->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		
		<link rel="shortcut icon" type="image/ico" href="http://www.kcn.unima.mw">	
		<title>Student Records Information System - KCN</title>		
		<link href="./My Company_files/styles.css" type="text/css" media="screen" rel="stylesheet">		<style type="text/css">
		img, div { behavior: url(iepngfix.htc) }
		</style>
	</head>
	<SCRIPT ID=clientEventHandlersJS LANGUAGE=javascript>
<!--
function userlogin_onsubmit() {
if (userlogin.textusername.value == "" || userlogin.textpassword.value == "")
return false;
}
//-->
</SCRIPT>

	<body onLoad="f_setfocus( userlogin_onsubmit );>
		<div id="wrappertop"></div>
			<div id="wrapper">
					<div id="content">
						<div id="header">
							<h1><b><?php echo strtoupper($org)?></b><br>
							  Student Academic Register Information System (SARIS)	For	Year	1, 2,	3 and 4		</h1>
						</div>
						<div id="darkbanner" class="banner320">
						  <h2>Login</h2>
						</div>
						<div id="darkbannerwrap">
						</div>
						<form action="userlogin.php" method="post" enctype="multipart/form-data" name="userlogin" class="style15" onsubmit="return userlogin_onsubmit()" LANGUAGE=javascript>
						<fieldset class="form">
                        	                                                                                       <p>
								<label for="user_name">Username:</label>
								<input name="textusername" tabindex="1"   value="<?php echo $_SESSION['username']?>">
							</p>
							<p>
								<label for="user_password">Password:</label>
								<input type="password" name="textpassword"  value="" tabindex="2">
							</p>
							<p>
							<?php
							if (isset($loginerror ) && $loginerror !="")
							{
							?>
							
								<?php echo $loginerror; ?>
								
								
							
							<?php
							session_cache_limiter('nocache');
							$_SESSION = array();
							session_unset(); 
							session_destroy(); 
							}
							?>
							
							</p>
							<button type="submit" class="positive" name="Login">
								<img src="./My Company_files/key.png" alt="Announcement">Login</button>
								<ul id="forgottenpassword">
								<li class="boldtext">|</li>
								<li><a href="passwordrecover.php">Forgot your password ?</a></li>
								<li class="boldtext">|</li>
								<li><a href="registration.php">New User</a></li>
							</ul>
                            						</fieldset>
													<br>
						
						
					</form></div>
				</div>   

<div id="wrapperbottom_branding"><div id="wrapperbottom_branding_text">By <a href="http://www.kcn.unima.mw" style="text-decoration:none">Charlie Maere, ICT Department, KCN</a></div></div></body></html>

