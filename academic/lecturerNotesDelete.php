<?php require_once('../Connections/zalongwa.php'); ?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

if ((isset($_GET['id'])) && ($_GET['id'] != "")) {
	//get the file
	$filename=$_GET['file'];
	// define the upload directory
	$homedir = "../download";
	$file_to_delete = "$filename";
	//rename("../download/$filename", "../download/juma.doc");
	//delete the file
	unlink("$homedir/$filename");
 	//echo "$file_to_delete is deleted!";
	$deleteSQL = sprintf("DELETE FROM tbllecturenotes WHERE id=%s",
                       GetSQLValueString($_GET['id'], "text"));

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($deleteSQL, $zalongwa) or die(mysql_error());

  $deleteGoTo = "lecturerindex.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?>
