<?php
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	# include the header
	include('admissionMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'E-Voting System';
	$szTitle = 'Register Candidates';
	$szSubSection = 'Set Candidates';
	include("admissionheader.php");
?>
<?php require_once('../Connections/zalongwa.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmInst")) {
$code = $_POST['txtCode'];

#check if candidate exist
$sql ="SELECT Id 			
	  FROM electioncandidate WHERE (Id  = '$code')";
$result = mysql_query($sql) or die("Tunasikitika Kuwa Hatuwezi Kukuhudumia Kwa Sasa.<br>");
$coursecodeFound = mysql_num_rows($result);
if ($coursecodeFound) {
          $coursefound   = mysql_result($result,0,'Id');
			print " This Candidate: '".$coursefound."' Do Exists!!"; 
			exit;
}else{
	   				   $insertSQL = sprintf("INSERT INTO electioncandidate (Name, Post, Faculty, Institution, Period) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['txtName'], "text"),
                       GetSQLValueString($_POST['cmbPost'], "text"),
                       GetSQLValueString($_POST['cmbFac'], "text"),
                       GetSQLValueString($_POST['cmbInst'], "text"),
                       GetSQLValueString($_POST['ayear'], "text"));

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($insertSQL, $zalongwa) or die(mysql_error());
  }
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmInstEdit")) {
 					   //$updateSQL = sprintf("UPDATE electioncandidate SET Post=%s, Faculty=%s, Institution=%s, Period=%s, Name=%s WHERE Id=%s",
					   $updateSQL = sprintf("UPDATE electioncandidate SET Name=%s, Post=%s, Faculty=%s, Institution=%s, Period=%s WHERE Id=%s",
                       GetSQLValueString($_POST['txtName'], "text"),
                       GetSQLValueString($_POST['cmbPost'], "text"),
                       GetSQLValueString($_POST['cmbFac'], "text"),
                       GetSQLValueString($_POST['cmbInst'], "text"),
                       GetSQLValueString($_POST['ayear'], "text"),
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

mysql_select_db($database_zalongwa, $zalongwa);
$query_campus = "SELECT * FROM campus ORDER BY Campus ASC";
$campus = mysql_query($query_campus, $zalongwa) or die(mysql_error());
$row_campus = mysql_fetch_assoc($campus);
$totalRows_campus = mysql_num_rows($campus);

mysql_select_db($database_zalongwa, $zalongwa);
$query_faculty = "SELECT * FROM faculty ORDER BY FacultyName ASC";
$faculty = mysql_query($query_faculty, $zalongwa) or die(mysql_error());
$row_faculty = mysql_fetch_assoc($faculty);
$totalRows_faculty = mysql_num_rows($faculty);

mysql_select_db($database_zalongwa, $zalongwa);
$query_post = "SELECT * FROM electionpost ORDER BY Post ASC";
$post = mysql_query($query_post, $zalongwa) or die(mysql_error());
$row_post = mysql_fetch_assoc($post);
$totalRows_post = mysql_num_rows($post);

mysql_select_db($database_zalongwa, $zalongwa);
$query_ayear = "SELECT * FROM academicyear ORDER BY AYear DESC";
$ayear = mysql_query($query_ayear, $zalongwa) or die(mysql_error());
$row_ayear = mysql_fetch_assoc($ayear);
$totalRows_ayear = mysql_num_rows($ayear);

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
  $key=$_GET['course'];
  $query_inst = "SELECT * FROM electioncandidate WHERE Name Like '%$key%' ORDER BY Period, Name DESC";
}else{
$query_inst = "SELECT * FROM electioncandidate ORDER BY Period, Name DESC";
}

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

<p><?php echo "<a href=\"admissionSetcandidate.php?new=1\">"?>Add New Candidate </p>
<?php @$new=$_GET['new'];
echo "</a>";
if (@$new<>1){
?>
<form name="form1" method="get" action="admissionSubject.php">
              Search by Name:
                <input name="course" type="text" id="course" maxlength="50">
              <input type="submit" name="Submit" value="Search">
</form>
	   
<table border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td><strong>Name</strong></td>
    <td><strong>Post</strong></td>
	<td><strong>Faculty</strong></td>
	<td><strong>Institution</strong></td>
	<td><strong>Period</strong></td>
  </tr>
  <?php do { ?>
  <tr>
    <td nowrap><?php $id = $row_inst['id']; echo $row_inst['Name']; ?></td>
    <td nowrap><?php $name = $row_inst['Post']; echo "<a href=\"admissionSetcandidate.php?edit=$id\">$name</a>"?></td>
	<td><?php echo $row_inst['Faculty'] ?></td>
	<td><?php echo $row_inst['Institution']; ?></td>
	<td><?php echo $row_inst['Period']; ?></td>
  </tr>
  <?php } while ($row_inst = mysql_fetch_assoc($inst)); ?>
</table>
<a href="<?php printf("%s?pageNum_inst=%d%s", $currentPage, max(0, $pageNum_inst - 1), $queryString_inst); ?>">Previous</a><span class="style1">......<span class="style2"><?php echo min($startRow_inst + $maxRows_inst, $totalRows_inst) ?>/<?php echo $totalRows_inst ?> </span>..........</span><a href="<?php printf("%s?pageNum_inst=%d%s", $currentPage, min($totalPages_inst, $pageNum_inst + 1), $queryString_inst); ?>">Next</a><br>
       
	   
			
<?php }else{?>
<form action="<?php echo $editFormAction; ?>" method="POST" name="frmInst" id="frmInst">
  <table width="200" border="1" cellpadding="0" cellspacing="0" bordercolor="#006600">
    <tr bgcolor="#CCCCCC">
      <th scope="row"><div align="right">Institution:</div></th>
      <td><select name="cmbInst" id="cmbInst" title="<?php echo $row_campus['Campus']; ?>">
        <?php
do {  
?>
        <option value="<?php echo $row_campus['Campus']?>"><?php echo $row_campus['Campus']?></option>
        <?php
} while ($row_campus = mysql_fetch_assoc($campus));
  $rows = mysql_num_rows($campus);
  if($rows > 0) {
      mysql_data_seek($campus, 0);
	  $row_campus = mysql_fetch_assoc($campus);
  }
?>
      </select></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th scope="row"><div align="right">Faculty:</div></th>
      <td><select name="cmbFac" id="cmbFac" title="<?php echo $row_faculty['FacultyName']; ?>">
	  <option value="[All Faculties]">[All Faculties]</option>
        <?php
do {  
?>
        <option value="<?php echo $row_faculty['FacultyName']?>"><?php echo $row_faculty['FacultyName']?></option>
        <?php
} while ($row_faculty = mysql_fetch_assoc($faculty));
  $rows = mysql_num_rows($faculty);
  if($rows > 0) {
      mysql_data_seek($faculty, 0);
	  $row_faculty = mysql_fetch_assoc($faculty);
  }
?>
      </select></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Post:</div></th>
      <td><select name="cmbPost" id="cmbPost" title="<?php echo $row_post['Post']; ?>">
        <?php
do {  
?>
        <option value="<?php echo $row_post['Post']?>"><?php echo $row_post['Post']?></option>
        <?php
} while ($row_post = mysql_fetch_assoc($post));
  $rows = mysql_num_rows($post);
  if($rows > 0) {
      mysql_data_seek($post, 0);
	  $row_post = mysql_fetch_assoc($post);
  }
?>
      </select></td>
    </tr>
	    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Period:</div></th>
      <td><select name="ayear" id="ayear" title="<?php echo $row_ayear['AYear']; ?>">
        <?php
do {  
?>
        <option value="<?php echo $row_ayear['AYear']?>"><?php echo $row_ayear['AYear']?></option>
        <?php
} while ($row_ayear = mysql_fetch_assoc($ayear));
  $rows = mysql_num_rows($ayear);
  if($rows > 0) {
      mysql_data_seek($ayear, 0);
	  $row_ayear = mysql_fetch_assoc($ayear);
  }
?>
      </select></td>
    </tr>

    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Name:</div></th>
      <td><input name="txtName" type="text" id="txtName" size="40"></td>
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
$query_instEdit = "SELECT * FROM electioncandidate WHERE Id ='$key'";
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
      <th scope="row"><div align="right">Institution:</div></th>
<td><select name="cmbInst" id="cmbInst" title="<?php echo $row_campus['Campus']; ?>">
<option value="<?php echo $row_instEdit['Institution']?>"><?php echo $row_instEdit['Institution']?></option>
  <?php
do {  
?>
<option value="<?php echo $row_campus['Campus']?>"><?php echo $row_campus['Campus']?></option>
  <?php
} while ($row_campus = mysql_fetch_assoc($campus));
  $rows = mysql_num_rows($campus);
  if($rows > 0) {
      mysql_data_seek($campus, 0);
	  $row_campus = mysql_fetch_assoc($campus);
  }
?>
      </select></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th scope="row"><div align="right">Faculty:</div></th>
      <td><select name="cmbFac" id="cmbFac" title="<?php echo $row_faculty['FacultyName']; ?>">
	  <option value="<?php echo $row_instEdit['Faculty']?>"><?php echo $row_instEdit['Faculty']?></option>
	   <option value="[All Faculties]">[All Faculties]</option>
        <?php
do {  
?>
        <option value="<?php echo $row_faculty['FacultyName']?>"><?php echo $row_faculty['FacultyName']?></option>
        <?php
} while ($row_faculty = mysql_fetch_assoc($faculty));
  $rows = mysql_num_rows($faculty);
  if($rows > 0) {
      mysql_data_seek($faculty, 0);
	  $row_faculty = mysql_fetch_assoc($faculty);
  }
?>
      </select></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Post:</div></th>
      <td><select name="cmbPost" id="cmbPost" title="">
	  	  <option value="<?php echo $row_instEdit['Post']?>"><?php echo $row_instEdit['Post']?></option>
        <?php
do {  
?>
        <option value="<?php echo $row_post['Post']?>"><?php echo $row_post['Post']?></option>
        <?php
} while ($row_post = mysql_fetch_assoc($post));
  $rows = mysql_num_rows($post);
  if($rows > 0) {
      mysql_data_seek($post, 0);
	  $row_post = mysql_fetch_assoc($post);
  }
?>
      </select></td>
    </tr>
		    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Period:</div></th>
      <td><select name="ayear" id="ayear" title="<?php echo $row_ayear['AYear']; ?>">
	  	  	  <option value="<?php echo $row_instEdit['Period']?>"><?php echo $row_instEdit['Period']?></option>

        <?php
do {  
?>
        <option value="<?php echo $row_ayear['AYear']?>"><?php echo $row_ayear['AYear']?></option>
        <?php
} while ($row_ayear = mysql_fetch_assoc($ayear));
  $rows = mysql_num_rows($ayear);
  if($rows > 0) {
      mysql_data_seek($ayear, 0);
	  $row_ayear = mysql_fetch_assoc($ayear);
  }
?>
      </select></td>
    </tr>

    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Name:</div></th>
      <td><input name="txtName" type="text" id="txtName" value="<?php echo $row_instEdit['Name']; ?>" size="40"></td>
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