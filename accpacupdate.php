<?php

require_once('Connections/zalongwa.php');

$sql = "SELECT `accpacid` , `saris` FROM `csvdump`";
$query = mysql_query($sql);
$i = 1;
while ($line = mysql_fetch_array($query, MYSQL_ASSOC)) 
                    {
						
					$accpac = $line['accpacid'];
					$saris = $line['saris'];	
						
						$sql2 = "UPDATE  `saris_year1`.`security` SET  `AccpacID` =  '$accpac' WHERE CONVERT(  `security`.`RegNo` USING utf8 ) =  '$saris' LIMIT 1";
						//die($sql2);
						mysql_query($sql2) or die(mysql_error());
						
						
						echo "$i : updated $saris added $accpac           $sql2 <br> ";
						
						$i++;
						
					}



?>