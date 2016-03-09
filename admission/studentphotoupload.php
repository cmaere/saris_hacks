<?php
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('admissionMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Admission Process';
	$szSubSection = 'Search Student';
	$szTitle = 'Search Student Record';
	include('admissionheader.php');
?>
<?php
    function getFileExtension($str) {

        $i = strrpos($str,".");
        if (!$i) { return ""; }

        $l = strlen($str) - $i;
        $ext = substr($str,$i+1,$l);

        return $ext;

    }

$studentregno = addslashes($_POST['studentregno']);
#validate the file
$imgfile_name = addslashes($_POST['userfile']);

$imgfile = $_FILES['userfile']['name'];
//The original name of the file on the client machine. 
$imgfiletype = $_FILES['userfile']['type'];
//The mime type of the file, if the browser provided this information. An example would be "image/gif". 

$imgfilesize = $_FILES['userfile']['size'];
//The size, in bytes, of the uploaded file. 

$imgfiletmp = $_FILES['userfile']['tmp_name'];
//The temporary filename of the file in which the uploaded file was stored on the server. 

$imgfiletype_error = $_FILES['userfile']['error'];

$filename=time().$_FILES['userfile']['name'];
//The error code associated with this file upload. ['error'] was added in PHP 4.2.0 
    $pext = getFileExtension($imgfile);
    $pext = strtolower($pext);
   if (($pext != "jpg")  && ($pext != "jpeg"))
    {
        print "<h1>ERROR</h1>Image Extension Unknown.<br>";
        print "<p>Please upload only a JPEG image with the extension .jpg or .jpeg ONLY<br><br>";
        print "The file you uploaded had the following extension: $pext</p>\n";

        /*== delete uploaded file ==*/

        exit();
    }

?>
<?php 

// In PHP earlier then 4.1.0, $HTTP_POST_FILES  should be used instead of $_FILES.
if (is_uploaded_file($imgfiletmp)) {
	$filename=time().$imgfile;
    copy($imgfiletmp, "images/$filename");
} else {
   echo "The file you are uploading is may be exceeds the maxmum file size: ie 30000000bytes, check it: " . $_FILES['userfile']['name'];
}
  move_uploaded_file($imgfiletmp, "images/$filename");
		#resize photo
		
			$imgfile = "images/$filename";
			$full_url = $imgfile;
			$imageInfo = getimagesize($imgfile);
			$src_width = $imageInfo[0];
			$src_height = $imageInfo[1];
			
			$dest_width = 80;//$src_width / $divide;
			$dest_height = 80;//$src_height / $divide;
			
			$src_img = imagecreatefromjpeg($imgfile);
			$dst_img = imagecreatetruecolor($dest_width,$dest_height);
			imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height);
			imagejpeg($dst_img,$full_url);
			imagedestroy($src_img);
		#new resized image file
		$imgfile = $full_url;
		
?>

<?php
	/* performing sql query*/
	$today = date("F j, Y");  
	//$url = "<img src=\"images/$filename\">";
	$url = "images/$filename";
	//$CourseCode = $_POST['CourseCode'];
	
	If (!$url) print "wewe weka ID upesi \n";
	else{
	$query = "update student set Photo =  '$url' where RegNo='$studentregno'";
	$result = mysql_query($query) or die("Query failed, Kwanini?");

	echo '<meta http-equiv = "refresh" content ="0; 
	url = admissionSearchStudent.php?key='.$studentregno.'">';
	
/* closing connection	*/
mysql_close($zalongwa);
}
?> 