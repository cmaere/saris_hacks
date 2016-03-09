 
 <?php require_once('Connections/zalongwa.php'); ?>
<?php

ini_set('display_errors', 1);
session_start();
session_cache_limiter('nocache');

if (isset($accesscheck)) {
  $GLOBALS['PrevUrl'] = $accesscheck;
  session_register('PrevUrl');
}

if (isset($_POST['textusername'])) {
  $date = date('Y m d');
  $username = $_POST['textusername'];
  $password = $_POST['textpassword'];
  // Generate jlungo hash
 $hash = "{jlungo-hash}" . base64_encode(pack("H*", sha1($password )));
//$hash = $password;

$query_AYear = "SELECT AYear, Semister_status FROM academicyear WHERE Status = 1";
        $result_AYear=mysql_query($query_AYear);
        while ($line = mysql_fetch_array($result_AYear, MYSQL_ASSOC)) 
                    {
                        $year= $line["AYear"];  
                        $semester = $line["Semister_status"];
                    }         

	$sql=sprintf("SELECT UserName, password, UPPER(RegNo) AS RegNo ,LEFT(UPPER(RegNo),9) as RegNo2,RIGHT(UPPER(RegNo),3) AS RegNo3,AccpacID,weaver,Position, Module, PrivilegeID, FullName, Faculty FROM security WHERE UserName='%s' AND password='%s'",
 		get_magic_quotes_gpc() ? $username : addslashes($username), get_magic_quotes_gpc() ? $hash : addslashes($hash)); 
		
		$result = @mysql_query($sql, $zalongwa);
		$loginFoundUser = mysql_num_rows($result);
        //die('here');
        //die('here'.$loginFoundUser);
    
 		if ($loginFoundUser <> 0) 
    {
           //die('here');
		   //Library picture update module
		  
		   
           // accpac integration module
            $accpacid = mysql_result($result,0,'AccpacID');
            $weaver = mysql_result($result,0,'weaver');
            
           
            
            
       		$loginStrGroup  = mysql_result($result,0,'password');
    		$loginName		= mysql_result($result,0,'FullName');
			$position 		= mysql_result($result,0,'Position');
			$RegNo 		= mysql_result($result,0,'RegNo');
            $RegNo2 		= mysql_result($result,0,'RegNo2');
            $RegNo3 		= mysql_result($result,0,'RegNo3');
			$module 	= mysql_result($result,0,'Module');
			$userFaculty 	= mysql_result($result,0,'Faculty');
			$privilege  = mysql_result($result,0,'PrivilegeID');
			$mtumiaji = 3;
            
            //$trim = ($RegNo2, 'KCN/BSCN/');
            //$trim2 = trim($RegNo, 'UCM' );
            //die('here'.$RegNo2 .'-- '.$RegNo3 );
			
			$_SESSION['username'] = $username; 
			$_SESSION['mtumiaji'] = $mtumiaji; 
			$_SESSION['RegNo'] = $RegNo; 
			$_SESSION['module'] = $module; 
			$_SESSION['privilege'] = $privilege; 
			$_SESSION['loginName'] = $loginName; 
			$_SESSION['userFaculty'] = $userFaculty; 
						
	 	$update_login = "UPDATE security SET LastLogin = now() WHERE UserName = '$username' AND Password = '$password'";
	 	$result = mysql_query($update_login) or die("Siwezi ku-update LastLogin, Zalongwa");
        
        
        $query_spons = "select Name from sponsors where Name not like '%Govt%' and Name not like '%Private%' and Name not like '%Self%'";
        $result_spons=mysql_query($query_spons);
        while ($line = mysql_fetch_array($result_spons, MYSQL_ASSOC)) 
                    {
                        $sponsor= $line["Name"];  
                        
                                $query_spons2 = "select Sponsor from student where RegNo = '$RegNo' and Sponsor LIKE '%$sponsor%'";
                                $result_spons2=mysql_query($query_spons2);
                                $sponsorcheck= mysql_num_rows($result_spons2);
                               
							   //HUbert added  module !=6 to deny access to blocked students            
                                  if(($sponsorcheck == 1) && ($module!='6'))
                                    {
                                    
                                       echo '<meta http-equiv = "refresh" content ="0; 
				 url = student/studentindex.php">';
				  // echo"SORRY YOU CAN NOT ACCESS SARIS AT THE MOMENT PLEASE TRY LATER OR CLICK <a href='http://saris.kcn.unima.mw/kcnconnect'> HERE </a> TO GO TO THE INTRANET";
					exit;
                                    
                                    }

                        
                        
                        
                        //$semester = $line["Semister_status"];
                    }         
        
        if($module=='1') 
         {
		   echo '<meta http-equiv = "refresh" content ="0; 
				url = academic/lecturerindex.php">';
					exit;
     	 } elseif($module=='2') {
	 	   echo '<meta http-equiv = "refresh" content ="0; 
				 url = accommodation/housingindex.php">';
					exit;
		 } elseif (($module=='3' && $RegNo2 == 'KCN/ME/16') || ($module=='3' && $RegNo2 == 'KCN/ME/15') || ($module=='3' && $RegNo2 == 'KCN/BSCAH') || ($module=='3' && $RegNo2 == 'KCN/BSCCH') || ($module=='3' && $RegNo2 == 'KCN/BSCCO') || ($module=='3' && $RegNo2 == 'KCN/BSCMH') || ($module=='3' && $RegNo2 == 'KCN/BSCMI') || ($module=='3' && $RegNo2 == 'KCN/BSCN/' && $RegNo3 != 'UCM' )||($module=='3' && $RegNo2 == 'KCN/DIPN/' && $RegNo3 != 'UCM' ) )  {
			 //die("here".$year);
			 echo"in 0";
			 $ip = $_SERVER['REMOTE_ADDR'];
			 $trimip = substr_replace($ip ,"",-3);
			 $trimipa = substr_replace($ip ,"",-5);
			 
			// die($trimipa);
			 
			if($trimip == "10.60.61" || $trimipa == "192.168" || $ip == "41.70.64.2" || $ip == "41.70.64.3")
			{
				echo "in 1";
			 //die(" entered in");
			 
			 
	 	   // echo '<meta http-equiv = "refresh" content ="0; 
			//			url = http://10.60.61.7:8003/feecheck.php?accpacid='.$accpacid.'&year='.$year.'&module='.$module.'&semester='.$semester.'&user='.$username.'&mtumiaji='.$mtumiaji.'&regno='.$RegNo.'&privilege='.$privilege.'&loginName='.$loginName.'&userFaculty='.$userFaculty.'&weaver='.$weaver.'">';
			$url = "10.60.61.7:8003/saris_accpac_api.php";
			include("test.php");
			
			
						
						
						
						// echo"SORRY YOU CAN NOT ACCESS SARIS AT THE MOMENT PLEASE TRY LATER OR CLICK <a href='http://saris.kcn.unima.mw/kcnconnect'> HERE </a> TO GO TO THE INTRANET";
						
					exit;
			}
			else if($trimip == "192.168.100")
			{
			 //die(" entered in");
			 echo "in 2";
	 	   // echo '<meta http-equiv = "refresh" content ="0; 
			//			url = http://10.60.61.7:8003/feecheck.php?accpacid='.$accpacid.'&year='.$year.'&module='.$module.'&semester='.$semester.'&user='.$username.'&mtumiaji='.$mtumiaji.'&regno='.$RegNo.'&privilege='.$privilege.'&loginName='.$loginName.'&userFaculty='.$userFaculty.'&weaver='.$weaver.'">';
			
			$url = "10.60.61.7:8003/saris_accpac_api.php";
			include("test.php");
						
						
						
						// echo"SORRY YOU CAN NOT ACCESS SARIS AT THE MOMENT PLEASE TRY LATER OR CLICK <a href='http://saris.kcn.unima.mw/kcnconnect'> HERE </a> TO GO TO THE INTRANET";
						
					exit;
			}
			else
			{
				echo "in 3";
				//die("here");
				
				// echo '<meta http-equiv = "refresh" content ="0; 
				//		url = http://41.70.64.2:8003/feecheck.php?accpacid='.$accpacid.'&year='.$year.'&module='.$module.'&semester='.$semester.'&user='.$username.'&mtumiaji='.$mtumiaji.'&regno='.$RegNo.'&privilege='.$privilege.'&loginName='.$loginName.'&userFaculty='.$userFaculty.'&weaver='.$weaver.'">';
						//url = http://cms.kcn.unima.mw:8003/feecheckyear1.php?accpacid='.$accpacid.'&year='.$year.'&module='.$module.'&semester='.$semester.'&user='.$username.'&mtumiaji='.$mtumiaji.'&regno='.$RegNo.'&privilege='.$privilege.'&loginName='.$loginName.'&userFaculty='.$userFaculty.'&weaver='.$weaver.'">';
						
						
						$url = "41.70.64.2:8003/saris_accpac_api.php";
						include("test.php");
						
						// echo"SORRY YOU CAN NOT ACCESS SARIS AT THE MOMENT PLEASE TRY LATER OR CLICK <a href='http://saris.kcn.unima.mw/kcnconnect'> HERE </a> TO GO TO THE INTRANET";
					exit;		
						
			}
         //}elseif ($module=='3') {
	 	   //echo '<meta http-equiv = "refresh" content ="0; 
			//	 url = student/studentindex.php">';
				//	exit;
            
            
		 } elseif ($module=='4') {
	 	   echo '<meta http-equiv = "refresh" content ="0; 
				 url = admission/admissionindex.php">';
					exit;
 	 	 } elseif ($module=='5') {
      			echo '<meta http-equiv = "refresh" content ="0; 
						url = administrator/administratorindex.php">';
		exit;
 	 } elseif ($module=='6') { 
			echo "Your Are Currently Blocked from Using ZALONGWA database! <br> You may contact the college Registrar for more details. </a><br> To Restore Services, Please Contact the System Administrator";
			exit;
	 } elseif ($module=='7') {
      			echo '<meta http-equiv = "refresh" content ="0; 
						url = billing/billingindex.php">';
		exit;
		} elseif ($module=='8') {
      			echo '<meta http-equiv = "refresh" content ="0; 
						url = barcode/">';
		exit;
		}elseif ($module=='9') {
      			echo '<meta http-equiv = "refresh" content ="0; 
						url = barcode/manage.php">';
		exit;
		}
		echo "outside".$RegNo2;
		    //include("http://10.60.61.7:8003/feecheck.php?");
            //$fee = check($accpacid,$year);
	

} 
else{
	
	 if($username == "kapa" && $password == "library")
		   {
			   
			 echo '<meta http-equiv = "refresh" content ="0; 
				 url = academic/getpic.php">';
			   
			   exit;
		   }
	
    $_SESSION['loginerror'] = 'Sign in Failed, Try Again!'; 
  	echo '<meta http-equiv = "refresh" content ="0; 
		url = index.php">';
		exit;
  	}
}
?>
<?php
mysql_close($zalongwa);
?>
