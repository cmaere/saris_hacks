<?php
$browser  =  $_SERVER["HTTP_USER_AGENT"];   
$ip  =  $_SERVER["REMOTE_ADDR"];   
$today = date("F j, Y, g:i a");
$username = addslashes($_GET['username']);



require_once('../Connections/zalongwa.php'); 

$sql="INSERT INTO stats(ip,browser,received,page) VALUES('$ip','$browser',now(),'Data Backup')";   
$result = mysql_query($sql);// or die("Siwezi kuingiza data.<br>" . mysql_error());

//$result = @mysql_query("show tables"); 
    //while (@$line = mysql_fetch_array($result, MYSQL_ASSOC)) {
       // foreach ($line as $tablename) {
              
$tablename = "examresult";
						$select = "SELECT * FROM  $tablename";     
								  
						$export = mysql_query($select); 
						$fields = mysql_num_fields($export); 
						
						for ($i = 0; $i < $fields; $i++) { 
							@$header .= mysql_field_name($export, $i) . "\t"; 
						} 
						
						while($row = mysql_fetch_row($export)) { 
							$line = ''; 
							foreach($row as $value) {                                             
								if ((!isset($value)) OR ($value == "")) { 
									$value = "\t"; 
								} else { 
									$value = str_replace('"', '""', $value); 
									$value = '"' . $value . '"' . "\t"; 
								} 
								$line .= $value; 
							} 
							@$data .= trim($line)."\n"; 
						} 
						$data = str_replace("\r","",$data); 
						
						if ($data == "") { 
							$data = "\n(0) Records Found!\n";                         
						} 
						header("Content-type: application/octet-stream Content-type: application/octet-stream>");
						
						header("Content-Disposition: attachment; filename=extraction.xls"); 
						
						header("Pragma: no-cache"); 
						
						header("Expires: 0"); 
						print "$header\n$data"; 
   // }
       
//}
  
mysql_close($zalongwa);
?> 
