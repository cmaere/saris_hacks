<?php
require_once('../Connections/zalongwa.php');

$query_coursecode1 = "SELECT DISTINCT  RegNo,Recorder
FROM  `examregister` 
WHERE  `AYear` LIKE CONVERT( _utf8 '2011'
USING latin1 ) 
COLLATE latin1_swedish_ci
AND  `CourseCode` LIKE CONVERT( _utf8 '%40%'
USING latin1 ) 
COLLATE latin1_swedish_ci
";
                    $resultb1=mysql_query($query_coursecode1);
                    while ($line = mysql_fetch_array($resultb1, MYSQL_ASSOC)) 
                    {
                        $regno = $line["RegNo"];
                        $username1 = $line["Recorder"];
                        //$progcode = $line["ProgrammeCode"];
                        
                        
                        
                        $sqldelete = "DELETE FROM examregister WHERE RegNo = '$regno'";
                        
                        
                        mysql_query($sqldelete);
                        
                        
                        
                        $query_coursecode = "SELECT P.ProgrammeName, C.CourseCode, C.CourseName ,C.YearOffered FROM program_year P, course C
                                    WHERE
                                    P.ProgrammeCode = C.Programme
                                    AND P.ProgrammeCode = '10014' AND C.YearOffered = 'Semester I'
                                    ORDER BY P.ProgrammeCode, C.YearOffered";
                    $resultb=mysql_query($query_coursecode);
                    while ($line = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
                    {
                        $coursecode = $line["CourseCode"];
                        //$progcode = $line["ProgrammeCode"];
                        $insertSQL = "INSERT INTO examregister (AYear, Semester, RegNo, CourseCode, Recorder, Checked) 
                                                            VALUES ('2011', 'Semester I', '$regno', '$coursecode', '$username1', '0')";
                                                            

                                                            
                        mysql_query($insertSQL);
                        
                        
                        
                        
                    }
                        
                        
                        
                        
                      
                        
                        
                        
                    }
                    
                    
                    echo "  work donewe";





?>