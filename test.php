
<?php
/*
Author: Charlie Maere
Date: 4th February 2016

This is an ACCPAC and SARIS API integration

*/
?>

<script language="JavaScript">


 function msgbox(msg,urlip)
{

alert(msg);
//self.location='http://saris.kcn.unima.mw/saris_year1/';
self.location='http://' + urlip + '/saris_year1/';

}

</script>
<?php

function cha_json_api($data,$url)	
{
	//This function sends data to accpac server and recieves accpac response
	//$url = "your url";    
	$content = json_encode($data);

	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER,
        array("Content-type: application/json"));
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

	$json_response = curl_exec($curl);

	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	/*if ( $status != 201 ) {
    	die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
	}*/


	curl_close($curl);

	$response = json_decode($json_response, true);

	return($response);
}

//$accpacid = "SKA279";
//$year = "2016";

$data = array(
	'accpacid' => $accpacid,
	'fiscalyear' => $year);
	
$urlip ="saris.kcn.unima.mw";
$finance_status = cha_json_api($data,$url);	

//var_dump($finance_status);

$balance = abs($finance_status["invoice"]) - abs($finance_status["payment"]);
$semester = "Semester I";

if($balance > 137500 && $semester = "Semester I")
{
    $msg = "YOU HAVE TO PAY YOUR FEES TO ACCOUNTS OFFICE BEFORE ACCESSING SARIS 2";
                        echo "<script language='JavaScript'> msgbox('$msg','$urlip'); </script>";
}
else if($balance > 27500)
{
    $msg = "YOU HAVE TO PAY YOUR FEES TO ACCOUNTS OFFICE BEFORE ACCESSING SARIS 3";
                        echo "<script language='JavaScript'> msgbox('$msg','$urlip'); </script>";
	
}
else
{
                      echo '<meta http-equiv = "refresh" content ="0; 
url = student/studentindex.php">';
}
	
?>