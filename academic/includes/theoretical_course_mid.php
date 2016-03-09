<?php
#get courses for the candidate
/*
$qcourse = "SELECT CourseCode FROM examresult WHERE RegNo='$key' ORDER BY CourseCode";
$dbcourse = mysql_query($qcourse);
while ($row_course = mysql_fetch_assoc($dbcourse)){
	#get coursecode
	$coursecode=$row_course['CourseCode'];
	*/
	#check is it is a theoretical course
    $CAT = 412;
    //die('here'.$degree);
	$qtcourse ="SELECT CourseName, Category, Hours FROM course  WHERE  Category = '$CAT'  and Programme = '$degree' ORDER BY CourseName";
	$dbtcourse = mysql_query($qtcourse);
    //die($qtcourse);
	while ($row_tcourse = mysql_fetch_assoc($dbtcourse)){
	$ccategory = $row_tcourse['Category'];
	//if($ccategory==1){
		#print results
		$cname = $row_tcourse['CourseName'];
        //die('here'$cname);
		$chours = $row_tcourse['Hours'];
		$pdf->text($x+8, $y+$rh, substr($cname,0,73)); 
		$pdf->text($x+480, $y+$rh, $chours); 
		$y = $y+12;
	//}
    if($ccategory==11){
		#print results
		$cname = $row_tcourse['CourseName'];
		$chours = $row_tcourse['Hours'];
		$pdf->text($x+8, $y+$rh, substr($cname,0,73)); 
		$pdf->text($x+480, $y+$rh, $chours); 
		$y = $y+12;
	}
	#check if there is enough printing area
	$indarea = 820.89-$y;
	if ($y>800){
			$pdf->addPage();  

			$x=50;
			$y=80;
			$pg=$pg+1;
			$tpg =$pg;
			$pdf->setFont('Arial', 'I', 8);     
			$pdf->text(530.28, 820.89, 'Page '.$pg);  
			$pdf->text(300, 820.89, $copycount);    
			$pdf->text(50, 820.89, $city.' '.$today = date("d-m-Y H:i:s"));   
			$yind = $y; 
    }
	
}
?>