<?php
error_reporting(0);
session_start();
session_cache_limiter('nocache');



if($module == 3)
{

    @$username = $username;
    
            $_SESSION['username'] = $username; 
			$_SESSION['mtumiaji'] = $mtumiaji; 
			$_SESSION['RegNo'] = $RegNo; 
			$_SESSION['module'] = $module; 
			$_SESSION['privilege'] = $privilege; 
			$_SESSION['loginName'] = $loginName; 
			$_SESSION['userFaculty'] = $userFaculty; 
			
			
			

}
else
{

    @$username = $_SESSION['username'];
    @$privilege  = $_SESSION['privilege'];
@$RegNo = $_SESSION['RegNo'];
@$name = $_SESSION['loginName'];
$userFaculty = $_SESSION['userFaculty'];

			

}


if(!$username){
	echo ("Session Expired, <a href=\"http://saris.kcn.unima.mw\"> Click Here<a> to Re-Login");
	echo '<meta http-equiv = "refresh" content ="0; 
	url = http://saris.kcn.unima.mw">';
	exit;
}		
?>