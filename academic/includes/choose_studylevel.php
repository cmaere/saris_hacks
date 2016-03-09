<?php
	$studylevel= $row_course['StudyLevel'];
	     if($studylevel == 1){
		include'certificate_gradscale.php';
	}elseif($studylevel == 2){
		include'diploma_gradscale.php';
	}elseif($studylevel == 3){
		//die('in here');
		include'bachelor_gradscale.php';
	}elseif($studylevel == 4){
		include'masters_gradscale.php';
	}elseif($studylevel == 5){
		include'phd_gradscale.php';
	}else{ 
		include'bachelor_gradscale.php';
	}
	$studylevel= '';
?>