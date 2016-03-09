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

if ((isset($_GET['CourseCode'])) && ($_GET['CourseCode'] != "")) {
  $coursecode = $_GET['CourseCode'];
  $regno = $_GET['RegNo'];
  $deleteSQL = "DELETE FROM examresult WHERE CourseCode='$coursecode' AND checked=0 AND RegNo='$regno'";

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($deleteSQL, $zalongwa) or die(mysql_error());

  $deleteGoTo = "lecturercourseregisteredlist.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?>
