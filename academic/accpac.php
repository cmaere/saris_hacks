<?php 
#get connected to the database and verfy current session
	
    require_once('../Connections/zalongwa.php');

//end cha modification
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
//save contents to database
	$key=addslashes($_POST['coursecode']);
	$ayear=addslashes($_POST['ayear']);
	$RegNo = $_POST['RegNo'];
	$cwk = $_POST['cwk'];
	$examcat = addslashes($_POST['examcat']);
	$examdate = addslashes($_POST['examdate']);
	$exammarker = addslashes($_POST['exammarker']);
	$remark = $_POST['sitting'];
	//$core=$_POST['core'];
	$comment = $_POST['comment'];
	$max = sizeof($RegNo);
	$_SESSION['max']=$max;
	
	#start for loop to treat each candidate
	for($c = 0; $c < $max; $c++) 
    {
        $score1 = $cwk[$c];
        $score2 = floatval($cwk[$c]);
    
            //cha edits 
            
            //UPDATE examresult set AYear = '$ayear', Marker = '$exammarker', CourseCode = '$key', ExamCategory = '$examcat', ExamDate =, ExamSitting, Recorder, RecordDate, RegNo, ExamScore, Status, Comment
            $curdate = date(d.'-'.m.'-'.Y);
    
                $presql = "select RegNo from examresult WHERE RegNo = '$RegNo[$c]' AND AYear ='$ayear' AND CourseCode = '$key' AND ExamCategory = '$examcat'";
                //die($presql);
                $result_presql=mysql_query($presql);
                $presqlrows= mysql_num_rows($result_presql);
                
                if($presqlrows == 0)
                {
                
                    $updateSQL = "INSERT INTO examresult(AYear, Marker, CourseCode, ExamCategory, ExamDate, ExamSitting, Recorder, RecordDate, RegNo, ExamScore, Status, Comment)
						 VALUES ('$ayear', '$exammarker', '$key', '$examcat', '$examdate', '$remark[$c]', '$username', now(), '$RegNo[$c]', '$cwk[$c]', '1', '$comment[$c]')";
                //die($updateSQL);
                    mysql_query($updateSQL);
                }
                else
                {
                
    
                    $updateSQL = "UPDATE examresult SET AYear ='$ayear', Marker = '$exammarker', CourseCode = '$key', ExamCategory = '$examcat', ExamDate = '$examdate', ExamSitting = '$remark[$c]', Recorder = '$username', RecordDate = '$curdate', RegNo = '$RegNo[$c]', ExamScore = '$cwk[$c]', Status = '1', Comment = '$comment[$c]'
                    WHERE RegNo = '$RegNo[$c]' AND AYear ='$ayear' AND CourseCode = '$key' AND ExamCategory = '$examcat'";
                        //to insert score validations later in future
                        
                  //      die($updateSQL);
                        mysql_query($updateSQL);
                 }



        }//close for loop
				#session error
				$_SESSION['err']=$err;
                
                
          echo "<br>Database updated successfully";
			#open data entry form again
	 	   echo '<meta http-equiv = "refresh" content ="0; 
				 url = lecturerGradebookAdd.php?r=1">';      
                exit;
			
}



$query_addexam_add = "SELECT Name, RegNo FROM student WHERE RegNo LIKE 'KCN/BSCN/11%'";

	
$addexam = mysql_query($query_addexam_add, $zalongwa) or die('Problem: Check the Add Query!3');
$row_addexam = mysql_fetch_array($addexam);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title><?php echo $key ?></title>
	<style type="text/css">
			body{font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px}
			h1, h2{font-size:20px;}
			.style1 {color: #990000}
	</style>
</head>
<body>
	<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1">

                   <span class="style71"> <span class="style67">FIRST YEAR ACCPAC INTERGRATION, <?php echo $ayear ?></span><br>
 <hr>
			
			
			  <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
              <tr>
                <td width="4%"><strong>S/No</strong></td>
				
                <td ><strong>Name</strong></td>
                <td ><strong>RegNo</strong></td>
                <td ><strong>Accpac ID</strong></td>
               
              </tr>
              <?php $i=1;
             
			  $addexam_add = mysql_query($query_addexam_add, $zalongwa) or die('Problem: Check the Add Query22!');
			  while ($row_addexam = mysql_fetch_assoc($addexam_add)){ 
			    $currentreg = $row_addexam['RegNo'];
			    $name = $row_addexam['Name'];

			  ?>
              <tr >
                <td align="left" valign="middle"><div align="left"> <?php echo $i; ?> </div></td>
				
                <td align="left" valign="middle" nowrap><?php echo $name; ?></td>
                <td align="left" valign="middle"><?php echo $currentreg; ?>
				<input name="RegNo[]" type="hidden" id="RegNo[]" value="<?php echo $currentreg; ?>">
                <td align="left" valign="middle">
				<input name="sitting[]" type="text" id="sitting[]" value="">
               

               
              </tr>
              <?php $i=$i+1;
			    
			  }  #ends while add loops
			  #starts edit row display
			  ?>
            <tr></tr><tr><td>
            <p>
              <input name="cmdEdit" type="submit" id="cmdEdit" value="Update Records">
              <input type="hidden" name="MM_update" value="form1">
              
            </p><td><td  width ='100%'>
            <p>
           
            </p>
            </table> 
</form>
<div id="timestamp"></div> 
