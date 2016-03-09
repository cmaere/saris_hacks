<?php

 require_once('../Connections/zalongwa.php');
 
 
 $sqlb = "select RegNo,DBirth from student";
                $resultb = mysql_query($sqlb);
                $row = mysql_num_rows($resultb);
                
                    while ($lineb = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
                        {
                        
                             $regno = $lineb["RegNo"];
                             $dob = $lineb["DBirth"];
                             //$to = $lineb["to_1"];
                             
                           echo "old date = $dob <br>";
                           
                           
                            
                            $sTestString = $dob;
 
                            $sPattern = '/\s*/m'; 
                            $sReplace = '';
 
                            //echo $sTestString . '<br />';
                            $newdob = preg_replace( $sPattern, $sReplace, $sTestString );
                            if($newdob !="")
                            {
			                	$new_date=date('Y-m-d', strtotime($newdob));
                            }
                            echo "new date =  $new_date<br>";
                            //die();
                            $sqlb2 = "update student set DBirth2 = '$new_date' where RegNo = '$regno'";
                            mysql_query($sqlb2);
                           
                               // echo" <font color='red'>$name is blocked from $from to $to</font>";
                        }
                        echo "done2_";
                            
                            
                


?>