<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('administratorMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Profile';
	$szSubSection = 'Profile';
	$szTitle = 'Adminstrator Module';
	include('administratorheader.php');

#Store Login History	
$browser  =  $_SERVER["HTTP_USER_AGENT"];   
$ip  =  $_SERVER["REMOTE_ADDR"];
$jina = $username." - Visited the Administrator Page";   
//$username = $username." "."Visited ".$szTitle;
$sql="INSERT INTO stats(ip,browser,received,page) VALUES('$ip','$browser',now(),'$jina')";   
$result = mysql_query($sql) or die("Siwezi kuingiza data.<br>" . mysql_error());

?> 
<form action="adminmanageuser.php" method="get" class="style24">
            <div align="right"><span class="style67"><font face="Verdana"><b>Search</b></font></span> 
              <font color="006699" face="Verdana"><b> 
              <input type="text" name="content" size="15">
              </b></font><font color="#FFFF00" face="Verdana"><b> 
              <input type="submit" value="GO" name="go">
              </b></font>            </div>
        </form>

          <p>Welcome to the Database Services</p>
          <p>Please select a Service you wants </p>
       <?php

	# include the footer
	include('../footer/footer.php');
?>