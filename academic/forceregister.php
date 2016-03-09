<?php

 require_once('../Connections/zalongwa.php');
 if(isset($_POST["reg"]))
 {
 $regno = $_POST["regno"];
 $course = $_POST["course"];
 
 //die($regno.$course);
 
 $sql ="INSERT INTO examregister(AYear,Semester,CourseCode,RegNo) VALUES('2010','Semester II','$course','$regno')";
 mysql_query($sql);
 
 echo"<font color='blue'>$regno is now  registered  successuffly to $course";
 }
 
 ?>
 
 
 <form action="forceregister.php" method="post" enctype="multipart/form-data" name="frmCourse" target="_self">
		<table width="200" border="1" cellpadding="0" cellspacing="0">
		  <tr>
			<th scope="row" nowrap><div align="right">REGNUMBER:</div>
            <input name="regno" type="text" value="">
			
			</th>
			<td><select name="course" size="1">
			<option value="0">[Select Course Code]</option>
			<?php
				$query_coursecode = "
		SELECT DISTINCT CourseCode
		FROM course 
		 ORDER BY CourseCode ASC";
         
         
        $resultb=mysql_query($query_coursecode);
        while ($line = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
        {
                                          
    
            $course = $line["CourseCode"];
            //die($course);
            
            ?>
            
          
        
						<option value="<?php echo $course; ?>"><?php echo $course;?></option>
<?php
              
			}
         
						?>
			</select></td>
		  </tr>
	
		  
		  
		  <tr>
			<th scope="row"><div align="right">Register:</div></th>
			<td><input name="reg" type="submit" value="Register"></td>
		  </tr>
		</table>
					
		</form>			