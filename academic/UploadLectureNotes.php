<?php require_once('../Connections/zalongwa.php'); ?>
<?php
$_FILES['userfile']['name'];
//The original name of the file on the client machine. 
$_FILES['userfile']['type'];
//The mime type of the file, if the browser provided this information. An example would be "image/gif". 

$_FILES['userfile']['size'];
//The size, in bytes, of the uploaded file. 

$_FILES['userfile']['tmp_name'];
//The temporary filename of the file in which the uploaded file was stored on the server. 

$_FILES['userfile']['error'];
//The error code associated with this file upload. ['error'] was added in PHP 4.2.0 
?>
<?php 
// In PHP earlier then 4.1.0, $HTTP_POST_FILES  should be used instead of $_FILES.
if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
	$filename=time().$_FILES['userfile']['name'];
    copy($_FILES['userfile']['tmp_name'], "../download/$filename");
} else {
   echo "The file you are uploading is may be exceeds the maxmum file size: ie 30000000bytes, check it: " . $_FILES['userfile']['name'];
}
/* ...or... */

move_uploaded_file($_FILES['userfile']['tmp_name'], "../download/$filename");
?>

<?php
	
	/* performing sql query*/
	$today = date("F j, Y");  
	$url = "<a href=\"../download/$filename\">".$_POST['url']."</a>";
	$CourseCode = $_POST['CourseCode'];
	
	If (!$url) print "Title of the File is Requires \n";
	else{
	$query = "insert into tbllecturenotes (coursecode, notes, received, filename) Values ('$CourseCode', '$url', now(), '$filename')";
	$result = mysql_query($query) or die("Query failed, Kwanini?");

	echo '<meta http-equiv = "refresh" content ="0; 
	url = lecturerlecturenotes.php?CourseCode='.$CourseCode.'">';
	
/* closing connection	*/
mysql_close($zalongwa);
}
?> 