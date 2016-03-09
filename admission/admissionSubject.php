<?php
require_once('../Connections/sessioncontrol.php');
# include the header
include('admissionMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Policy Setup';
	$szTitle = 'Subject Information';
	$szSubSection = 'Subject';
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
if(strlen($code)>13){
echo "Too Long Course Code, Please revise!";
exit;
}elseif(strlen($code)<6) {
echo "Too Short Course Code, Please revise!";
exit;
}
#check if username exist
$sql ="SELECT course.CourseCode 			
	  FROM course WHERE (course.CourseCode  = '$code')";
$result = mysql_query($sql) or die("Tunasikitika Kuwa Hatuwezi Kukuhudumia Kwa Sasa.<br>");
$coursecodeFound = mysql_num_rows($result);


if ($coursecodeFound) {
          $coursefound   = mysql_result($result,0,'CourseCode');
			print " This Course Code: '".$coursefound."' Do Exists!!"; 
            
           
            
			exit;
            
            
            
}else{
	   				   $insertSQL = sprintf("INSERT INTO course (CourseCode, CourseName, Units, Department, Faculty) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['txtCode'], "text"),
                       GetSQLValueString($_POST['txtTitle'], "text"),
                       GetSQLValueString($_POST['txtUnit'], "text"),
                       GetSQLValueString($_POST['cmbFac'], "text"),
                       GetSQLValueString($_POST['cmbInst'], "text"));

  mysql_select_db($database_zalongwa, $zalongwa);
  $Result1 = mysql_query($insertSQL, $zalongwa) or die(mysql_error());
  }
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmInstEdit")) {
///charlie chingwalu and msosa edits

 $cha_id = GetSQLValueString($_POST['id'], "int");
 $cha_newcode = GetSQLValueString($_POST['txtCode'], "text");
            $coursename =GetSQLValueString($_POST['txtTitle'], "text");
            $unit=GetSQLValueString($_POST['txtUnit'], "text");
            $dept=GetSQLValueString($_POST['cmbFac'], "text");
            $fac=GetSQLValueString($_POST['cmbInst'], "text");

 
 $sql1="select CourseCode from course where Id = '$cha_id'";

    $result=mysql_query($sql1);
    $rowa = mysql_num_rows($result);
 while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) 
            {
                                                      
             $cha_oldcode  = $line["CourseCode"];
                
                }
                
 ///charlie chingwalu and msosa edits
 
$chasql = "update examresult set CourseCode = $cha_newcode WHERE CourseCode = '$cha_oldcode'";
//die("cha anadutsa = ". $chasql);
//die("cha anadutsa = ".$updateSQL);
  $Result2 = mysql_query($chasql);
  $chasqlb = "update examregister set CourseCode = $cha_newcode WHERE CourseCode = '$cha_oldcode'";
//die("cha anadutsa = ". $chasql);
//die("cha anadutsa = ".$updateSQL);
  $Result2b = mysql_query($chasqlb);
  //die("cha anadutsa = ". $chasql);
 //;

 					   $updateSQL = "UPDATE course SET CourseName=$coursename, Units=$unit, Department=$dept, Faculty=$fac, CourseCode=$cha_newcode WHERE Id=$cha_id";
                       
                      $Result1 = mysql_query($updateSQL); 
  

 
  
  
  
  
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
$query_campus = "SELECT FacultyName FROM faculty ORDER BY FacultyName ASC";
$campus = mysql_query($query_campus, $zalongwa) or die(mysql_error());
$row_campus = mysql_fetch_assoc($campus);
$totalRows_campus = mysql_num_rows($campus);

mysql_select_db($database_zalongwa, $zalongwa);
$query_faculty = "SELECT DeptName FROM department ORDER BY DeptName ASC";
$faculty = mysql_query($query_faculty, $zalongwa) or die(mysql_error());
$row_faculty = mysql_fetch_assoc($faculty);
$totalRows_faculty = mysql_num_rows($faculty);

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
  $query_inst = "SELECT * FROM course WHERE CourseCode Like '%$key%' ORDER BY CourseCode ASC";
}else{
$query_inst = "SELECT * FROM course ORDER BY CourseCode ASC";
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

<p><?php echo "<a href=\"admissionSubject.php?new=1\">"?>Add New Subject (Course) </p>
<?php @$new=$_GET['new'];
echo "</a>";
if (@$new<>1){
?>
<form name="form1" method="get" action="admissionSubject.php">
              Search by Course Code
              <input name="course" type="text" id="course" maxlength="50">
              <input type="submit" name="Submit" value="Search">
       </form>
	   
<table border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td><strong>Department</strong></td>
    <td><strong>Course</strong></td>
	<td><strong>Description</strong></td>
	<td><strong>Units</strong></td>
  </tr>
  <?php do { ?>
  <tr>
    <td nowrap><?php $id = $row_inst['Id']; echo $row_inst['Department']; ?>
	</td>
    <td nowrap><?php $name = $row_inst['CourseCode']; echo "<a href=\"admissionSubject.php?edit=$id\">$name</a>"?></td>
	 <td><?php echo $row_inst['CourseName'] ?></td>
	 <td><?php echo $row_inst['Units']; ?></td>
  </tr>
  <?php } while ($row_inst = mysql_fetch_assoc($inst)); ?>
</table>
<a href="<?php printf("%s?pageNum_inst=%d%s", $currentPage, max(0, $pageNum_inst - 1), $queryString_inst); ?>">Previous</a><span class="style1">......<span class="style2"><?php echo min($startRow_inst + $maxRows_inst, $totalRows_inst) ?>/<?php echo $totalRows_inst ?> </span>..........</span><a href="<?php printf("%s?pageNum_inst=%d%s", $currentPage, min($totalPages_inst, $pageNum_inst + 1), $queryString_inst); ?>">Next</a><br>
       
	   
			
<?php }else{?>
<form action="<?php echo $editFormAction; ?>" method="POST" name="frmInst" id="frmInst">
  <table width="200" border="1" cellpadding="0" cellspacing="0" bordercolor="#006600">
    <tr bgcolor="#CCCCCC">
      <th scope="row"><div align="right">Faculty:</div></th>
<td><select name="cmbInst" id="cmbInst" title="<?php echo $row_campus['FacultyName']; ?>">
  <?php
do {  
?>
  <option value="<?php echo $row_campus['FacultyName']?>"><?php echo $row_campus['FacultyName']?></option>
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
      <th scope="row"><div align="right">Department:</div></th>
      <td><select name="cmbFac" id="cmbFac" title="<?php echo $row_faculty['DeptName']; ?>">
        <?php
do {  
?>
        <option value="<?php echo $row_faculty['DeptName']?>"><?php echo $row_faculty['DeptName']?></option>
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
      <th nowrap scope="row"><div align="right">Course Code:</div></th>
      <td><input name="txtCode" type="text" id="txtCode" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Course Title:</div></th>
      <td><input name="txtTitle" type="text" id="txtTitle" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Units:</div></th>
      <td><input name="txtUnit" type="text" id="txtUnit" size="40"></td>
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
$query_instEdit = "SELECT * FROM course WHERE Id ='$key'";
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
      <th scope="row"><div align="right">Faculty:</div></th>
<td><select name="cmbInst" id="cmbInst" title="<?php echo $row_campus['FacultyName']; ?>">
<option value="<?php echo $row_instEdit['Faculty']?>"><?php echo $row_instEdit['Faculty']?></option>
  <?php
do {  
?>
<option value="<?php echo $row_campus['FacultyName']?>"><?php echo $row_campus['FacultyName']?></option>
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
      <th scope="row"><div align="right">Department:</div></th>
      <td><select name="cmbFac" id="cmbFac" title="<?php echo $row_faculty['DeptName']; ?>">
	  <option value="<?php echo $row_instEdit['Department']?>"><?php echo $row_instEdit['Department']?></option>
        <?php
do {  
?>
        <option value="<?php echo $row_faculty['DeptName']?>"><?php echo $row_faculty['DeptName']?></option>
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
      <th nowrap scope="row"><div align="right">Course Code:</div></th>
      <td><input name="txtCode" type="text" id="txtCode" value="<?php echo $row_instEdit['CourseCode']; ?>" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Course Title:</div></th>
      <td><input name="txtTitle" type="text" id="txtTitle" value="<?php echo $row_instEdit['CourseName']; ?>" size="40"></td>
    </tr>
    <tr bgcolor="#CCCCCC">
      <th nowrap scope="row"><div align="right">Units:</div></th>
      <td><input name="txtUnit" type="text" id="txtUnit" value="<?php echo $row_instEdit['Units']; ?>" size="40"></td>
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