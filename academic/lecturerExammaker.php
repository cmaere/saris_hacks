<?php
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
# include the header
	include('lecturerMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Administration';
	$szTitle = 'Register Exam Markers';
	$szSubSection = 'Exam Marker';
	include('lecturerheader.php');
?>
<?php
$currentPage = $_SERVER["PHP_SELF"];
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmInst")) {
$rawcode = $_POST['txtCode'];
$code = ereg_replace("[[:space:]]+", " ",$rawcode);
$rawname = $_POST['txtTitle'];
$name = ereg_replace("[[:space:]]+", " ",$rawname);

#check if subject exist exist
$sql ="SELECT Id 			
	  FROM exammarker WHERE (Id  = '$code')";
$result = mysql_query($sql) or die("Tunasikitika Kuwa Hatuwezi Kukuhudumia Kwa Sasa.<br>");
$coursecodeFound = mysql_num_rows($result);
if ($coursecodeFound) {
          $coursefound   = mysql_result($result,0,'SubjectID');
			print " This Combination Code: '".$coursefound."' Do Exists!!"; 
			exit;
}
#check if subject name exist
$namesql ="SELECT Name 			
	  FROM exammarker WHERE (Name  = '$name')";
$nameresult = mysql_query($namesql) or die("Tunasikitika Kuwa Hatuwezi Kukuhudumia Kwa Sasa.<br>");
$coursenameFound = mysql_num_rows($nameresult);
if ($coursenameFound) {
          $namefound   = mysql_result($nameresult,0,'Name');
			print " This Name: '".$namefound."' Do Exists!!"; 
			exit;
 }
#insert records	   				   
 $insertSQL = sprintf("INSERT INTO exammarker (Id, Name) VALUES (%s, %s)",
   GetSQLValueString($_POST['txtCode'], "text"),
   GetSQLValueString($_POST['txtTitle'], "text"));                   
  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($insertSQL, $zalongwa) or die(mysql_error());
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmInstEdit")) {
 					   $updateSQL = sprintf("UPDATE exammarker SET Name=%s WHERE Id=%s",
                       GetSQLValueString($_POST['txtTitle'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($updateSQL, $zalongwa) or die(mysql_error());
 }
 
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
//control the display table
@$new=2;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$maxRows_inst = 10;
$pageNum_inst = 0;
if (isset($_GET['pageNum_inst'])) {
  $pageNum_inst = $_GET['pageNum_inst'];
}
$startRow_inst = $pageNum_inst * $maxRows_inst;

mysql_select_db($database_zalongwa, $zalongwa);
if (isset($_GET['course'])) {
  $rawkey=$_GET['course'];
  $key = addslashes($rawkey);
  $query_inst = "SELECT * FROM exammarker WHERE Name Like '%$key%' ORDER BY Id ASC";
}else{
$query_inst = "SELECT * FROM exammarker ORDER BY Id ASC";
}
//$query_inst = "SELECT * FROM course ORDER BY CourseCode ASC";
$query_limit_inst = sprintf("%s LIMIT %d, %d", $query_inst, $startRow_inst, $maxRows_inst);
$inst = mysql_query($query_limit_inst, $zalongwa) or die(mysql_error());
$row_inst = mysql_fetch_assoc($inst);

if (isset($_GET['totalRows_inst'])) {
  $totalRows_inst = $_GET['totalRows_inst'];
} else {
  $all_inst = mysql_query($query_inst);
  $totalRows_inst = mysql_num_rows($all_inst);
}
$totalPages_inst = ceil($totalRows_inst/$maxRows_inst)-1;
?>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
.style2 {color: #000000}
-->
</style>

<p><?php echo "<a href=\"lecturerExammaker.php?new=1\">"?>Add New Exam Marker </p>
<?php @$new=$_GET['new'];
echo "</a>";
if (@$new<>1){
?>
<form name="form1" method="get" action="<?php echo $editFormAction; ?>">
              Search by Name: 
                <input name="course" type="text" id="course" maxlength="50">
              <input type="submit" name="Submit" value="Search">
</form>
	   
<table border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td><strong>S/No</strong></td>
	<td><strong>Subject Combination</strong></td>
  </tr>
  <?php do { ?>
  <tr>
     <td nowrap><?php $name = $row_inst['Id']; echo "<a href=\"lecturerExammaker.php?edit=$name\">$name</a>"?></td>
	 <td><?php echo $row_inst['Name'] ?></td>
  </tr>
  <?php } while ($row_inst = mysql_fetch_assoc($inst)); ?>
</table>
<a href="<?php printf("%s?pageNum_inst=%d%s", $currentPage, max(0, $pageNum_inst - 1), $queryString_inst); ?>">Previous</a><span class="style1">......<span class="style2"><?php echo min($startRow_inst + $maxRows_inst, $totalRows_inst) ?>/<?php echo $totalRows_inst ?> </span>..........</span><a href="<?php printf("%s?pageNum_inst=%d%s", $currentPage, min($totalPages_inst, $pageNum_inst + 1), $queryString_inst); ?>">Next</a><br>
       
	   
			
<?php }else{?>
<form action="<?php echo $editFormAction; ?>" method="POST" name="frmInst" id="frmInst">
  <table width="200" border="1" cellpadding="0" cellspacing="0" bordercolor="#006600">
    <tr bgcolor="#CCCCCC">
      
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Exam-marker Code:</div></th>
      <td><input name="txtCode" type="text" id="txtCode" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Exam-marker Name:</div></th>
      <td><input name="txtTitle" type="text" id="txtTitle" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      
    </tr>
    <tr bgcolor="#CCCCCC">
      <th scope="row">&nbsp;</th>
      <td><div align="center">
        <input type="submit" name="Submit" value="Add Record">
      </div></td>
    </tr>
  </table>
    <input type="hidden" name="MM_insert" value="frmInst">
</form>
<?php } 
if (isset($_GET['edit'])){
#get post variables
$key = $_GET['edit'];

mysql_select_db($database_zalongwa, $zalongwa);
$query_instEdit = "SELECT * FROM exammarker WHERE Id ='$key'";
$instEdit = mysql_query($query_instEdit, $zalongwa) or die(mysql_error());
$row_instEdit = mysql_fetch_assoc($instEdit);
$totalRows_instEdit = mysql_num_rows($instEdit);

$queryString_inst = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_inst") == false && 
        stristr($param, "totalRows_inst") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_inst = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_inst = sprintf("&totalRows_inst=%d%s", $totalRows_inst, $queryString_inst);

?>
<form action="<?php echo $editFormAction; ?>" method="POST" name="frmInstEdit" id="frmInstEdit">
 <table width="200" border="1" cellpadding="0" cellspacing="0" bordercolor="#006600">
    <tr bgcolor="#CCCCCC">
      
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Exam-marker Code:</div></th>
      <td><input name="txtCode" type="hidden" id="txtCode" value="<?php echo $row_instEdit['Id']; ?>" size="40"><?php echo $row_instEdit['Id']; ?></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Exam-marker Name:</div></th>
      <td><input name="txtTitle" type="text" id="txtTitle" value="<?php echo $row_instEdit['Name']; ?>" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      
    </tr>
    <tr bgcolor="#CCCCCC">
      <th scope="row"><input name="id" type="hidden" id="id" value="<?php echo $key ?>"></th>
      <td><div align="center">
        <input type="submit" name="Submit" value="Edit Record">
      </div></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="frmInstEdit">
</form>
<?php
}
	# include the footer
	include("../footer/footer.php");

@mysql_free_result($inst);

@mysql_free_result($instEdit);

@mysql_free_result($faculty);

@mysql_free_result($campus);
?>