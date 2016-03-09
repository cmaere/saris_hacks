 <?php
 
 require_once('../Connections/zalongwa.php');
 $sql = "SELECT
				   student.RegNo
				   
				FROM student
				WHERE 
  					 (
						(student.EntryYear='2012') AND 
						(student.ProgrammeofStudy = '1001') AND
						(student.ProgrammeofStudy <> '10103')
   					 )
						ORDER BY  student.Faculty, 
						student.ProgrammeofStudy, student.Name";
  $result_spons=mysql_query($sql);
        while ($line = mysql_fetch_array($result_spons, MYSQL_ASSOC)) 
                    {
                        $regno= $line["RegNo"];  
					
 
 //die($regno.$course);
 
 $sql ="REPLACE INTO examregister(AYear,Semester,CourseCode,RegNo) VALUES('2013','Semester I','NUR 201','$regno')";
 mysql_query($sql);
 
 echo"<font color='blue'>$regno is now  registered  successuffly to $course";
					}
                    
                    
          ?>