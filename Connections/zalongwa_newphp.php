<?php
ini_set('display_errors', 1);
// User configurable variables
$szSiteTitle = 'zalongwaSARIS';
$szWebmasterEmail = '< jlungo@udsm.ac.tz >';



@$hostname_zalongwa = "41.70.64.3";
@$database_zalongwa = "saris_year1";
@$username_zalongwa = "sirasnck";
@$password_zalongwa = "awgnolaz60";
$zalongwa2 = mysqli_connect($hostname_zalongwa, strrev($username_zalongwa), strrev($password_zalongwa),$database_zalongwa); 
if (!$zalongwa2){
	 printf(mysqli_error()."Tunasikitika Kuwa Hatuwezi Kutoa Huduma Kwa Sasa,\rTafadhari Jaribu Tena Baadaye!");
	 exit;
	}
	else
	{
		//die("connected");
	}
	
	
//@mysql_select_db ($database_zalongwa, $zalongwa2); 


global $szRootURL,$szRootPath,$szSiteTitle,$szWebmasterEmail,$arrStructure,$arrVariations,$intDefaultVariation;
global $szDBName,$szDBUsername,$szDBPassword,$szDiscussionAdmin,$szDiscussionPassword;
if (!$zalongwa2){
	 printf("Tunasikitika Kuwa Hatuwezi Kutoa Huduma Kwa Sasa,\rTafadhari Jaribu Tena Baadaye!");
	 exit;
	}

	$arrVariations = array (
		1 => array( 'name' => 'English', 'shortname' => 'Eng'),
		2 => array( 'name' => 'Kiswahili', 'shortname' => 'Sw'),
	);
	
$arrVariationPreference = array (
		1 => 1,
		2 => 2
	);
	
	if (!isset($_SESSION['arrVariationPreference'])){
		// store it in the session variable
		$_SESSION['arrVariationPreference']=$arrVariationPreference;
	}
	
	// define the default variation
	$intDefaultVariation = 1;

	#Get Organisation Name and address
	$qorg = "SELECT * FROM organisation";
	$dborg = mysqli_query($zalongwa2,$qorg);
	if (!$dborg) {
	    printf("Error: %s\n", mysqli_error($zalongwa2));
	    exit();
	}
	while( $row_org=mysqli_fetch_array($dborg) ) {
	//$row_org = mysqli_fetch_assoc($dborg);
	$org = $row_org['Name'];
	$post = $row_org['Address'];
	$phone = $row_org['tel'];
	$fax = $row_org['fax'];
	$email = $row_org['email'];
	$website = $row_org['website'];
	$city = $row_org['city'];
}

function mysqli_result($result , $offset , $field = 0){
    $result->data_seek($offset);
    $row = $result->fetch_array();
    return $row[$field];
}
	//die("am here")
?>
