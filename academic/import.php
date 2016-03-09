<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    # initialise globals
	include('../academic/lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Examination';
	$szSubSection = 'Excel Import';
	$szTitle = 'Import MS Excel Database Data';
	include('../academic/lecturerheader.php');
?>
<?php
# delete old files
@unlink("./temp/zalongwa.txt");
#constants
$fileavailable=0;

#validate the file
$file_name = addslashes($_POST['userfile']);
$overwrite = addslashes($_POST['radiobutton']);
#check file extension

$file = $_FILES['userfile']['name'];
//The original name of the file on the client machine. 
$filetype = $_FILES['userfile']['type'];
//The mime type of the file, if the browser provided this information. An example would be "image/gif". 

$filesize = $_FILES['userfile']['size'];
//The size, in bytes, of the uploaded file. 

$filetmp = $_FILES['userfile']['tmp_name'];
//The temporary filename of the file in which the uploaded file was stored on the server. 

$filetype_error = $_FILES['userfile']['error'];

$filename=time().$_FILES['userfile']['name'];
?>
<?php 
// In PHP earlier then 4.1.0, $HTTP_POST_FILES  should be used instead of $_FILES.
if (is_uploaded_file($filetmp)) {
	$filename=time().$file;
    copy($filetmp, "$filename");
} else {
   echo "File not uploaded, see the error: <br>".$filetype_error;
}
  move_uploaded_file($filetmp, "./temp/$filename");


    $str = $filename;
	$i = strrpos($str,".");
	if (!$i) { return ""; }
	
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	
    $pext = strtolower($ext);
   if ($pext != "txt")
    {
        print "<h2>ERROR</h2>File Extension Unknown.<br>";
        print "<p>Please Upload a File with the Extension .txt ONLY<br>";
		print "To convert your Excel File to txt, go to File -> Save As, the Select Save as Type Text (Tab delimeded) (*.txt)</p>\n";
        print "The file you uploaded have this extension: $pext</p>\n";
		echo '<meta http-equiv = "refresh" content ="10; url = zalongwaimport.php">';
	    include('../footer/footer.php');
		unlink($filename);
        exit();
    }

?>
<?php #unzip the file first
if ($pext == "txt") {
$fileavailable=1;
}
?>
<?php
#STEP 2.0 exceute sql scripts
if ($fileavailable==1){
	echo "<strong>Data Import in Process</strong><br /><br>"; 
	
	echo "Progress Status.............<br>"; 
	  $fcontents = file ("./temp/$filename"); 
	  # expects the csv file to be in the same dir as this script
	
	  for($i=0; $i<sizeof($fcontents); $i++) { 
		 // $line = trim($fcontents[$i]); 
		  $line = trim($fcontents[$i], '\t');
		  $arr = explode("\t", $line); 
		  #if your data is comma separated
		  # instead of tab separated, 
		  # change the '\t' above to ',' 
		  if ($overwrite==1){
		  $sql = "REPLACE INTO examresult VALUES ('". 
					  implode("','", $arr) ."')"; 
		  }else{
		  $sql = "INSERT INTO examresult VALUES ('". 
		  			  implode("','", $arr) ."')"; 
		  }
		  mysql_query($sql);
		  echo $sql ."<br>\n";
		  if(mysql_error()) {
			 die ('- Zalongwa System Found:- '.mysql_error());
			 //echo "....Duplicate Entry -Record Not Imported!<br>\n"; 
		  }else{
			echo "....Record Imported Successfuly!<br>\n"; 
		  }
	  }
	echo "<br /><br /><strong>Zalongwa Data Import Process Completed</strong>"; 
	unlink("./temp/$filename");
	unlink("$filename");

} 
	include('../footer/footer.php');
?> 
