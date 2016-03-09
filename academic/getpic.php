<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <p>student
</p>
  <p>
    <label for="reg">Search student Reg</label>
    <input type="text" name="reg" id="reg" />
  </p>
  <p>Search
    <input type="submit" name="student" id="student" value="Submit" />
  </p>
</form>
</body>
</html>
<?php
require_once('../Connections/zalongwa.php');
	
	if(isset($_POST['student']))
	{
		
		$reg = $_POST["reg"];
		
		$sql = "SELECT pic_source FROM student WHERE RegNo = '$reg'";
		 $result_AYear=mysql_query($sql);
        while ($line = mysql_fetch_array($result_AYear, MYSQL_ASSOC)) 
                    {
                       echo '<img src="data:image/jpeg;base64,' . base64_encode( $line['pic_source'] ) . '" width="400" />'; 
                       // $semester = $line["Semister_status"];
                    }         	
	
	}



?>