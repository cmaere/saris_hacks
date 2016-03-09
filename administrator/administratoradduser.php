<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('administratorMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Manage Users';
	$szSubSection = '';
	$szTitle = 'List of Database Users';
	include('administratorheader.php');


#populate module combo box
	$query_module = "SELECT moduleid, modulename FROM modules";
	$module = mysql_query($query_module, $zalongwa) or die(mysql_error());
	$row_module = mysql_fetch_assoc($module);
	$totalRows_module = mysql_num_rows($module);

#populate privileges combo box
	$query_privilege = "SELECT privilegeID, privilegename FROM privilege";
	$privilege = mysql_query($query_privilege, $zalongwa) or die(mysql_error());
	$row_privilege = mysql_fetch_assoc($privilege);
	$totalRows_privilege = mysql_num_rows($privilege);
	
@$check = $_POST['checksubmit'];
if ($check=='on'){
					$username = $_POST['txtUsername'];
					$pwd = $_POST['txtPassword'];
					$name = $_POST['txtName'];
					$reg = $_POST['txtRegno'];
					$desig = $_POST['position'];
					$email= $_POST['txtEmail'];
					$authlevel = $_POST['priv'];
					$module = $_POST['module'];
			$sql="INSERT INTO security (UserName,password,FullName,RegNo, Position, Email, LastLogin, 
			Registered, AuthLevel, Module, PrivilegeID) VALUES('$username','$pwd','$name','$reg','$desig','$email','now()','now()','','$authlevel','$module')";   
			$result = mysql_query($sql) or die("Siwezi kuingiza data.<br>" . mysql_error());
	}
?> 

          <form action="adminmanageuser.php" method="get" class="style24">
            <div align="right"><span class="style42"><font face="Verdana"><b>Search</b></font></span> 
              <font color="006699" face="Verdana"><b> 
              <input type="text" name="content" size="15">
              </b></font><font color="#FFFF00" face="Verdana"><b> 
              <input type="submit" value="GO" name="go">
            </b></font>            </div>
        </form>       
        		 <?php

	# include the footer
	include('../footer/footer.php');
?>