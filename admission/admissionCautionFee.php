<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('admissionMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Admission Process';
	$szSubSection = 'Caution Fee';
	$szTitle = 'Caution Fee';
	include('admissionheader.php');

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


//Print Room Allocation Report
if (isset($_POST['search']) && ($_POST['search'] == "Search")) {
#get post variables
$keyword = $_POST['key'];
			
//query for Payment Types list
$qtype = "SELECT DISTINCT Description ";
$qtype .= "FROM tblcautionfee ";
$qtype .= "ORDER BY Description ";

$dbtype = mysql_query($qtype, $zalongwa) or die(mysql_error());

//query for crosstab
$qx = "SELECT RegNo";
while($rowx = mysql_fetch_object($dbtype)){
$qx .= ", SUM(IF(Description = '$rowx->Description', Amount, 0)) AS Amount ";
}
//$qx .= ", MINUS(Amount) AS \"Balance by Student\" ";
$qx .= "FROM tblcautionfee ";
$qx .= "WHERE (tblcautionfee.RegNo LIKE '%$keyword%') ";
$qx .= "GROUP BY tblcautionfee.RegNo";

//print($qx);

if(!($dbx = mysql_query($qx, $zalongwa))){
	print("MySQL reports: " . mysql_error() . "\n");
	exit();
	}
?>
<table border="1" cellpadding="4" cellspacing="1">
<tr>
<td bgcolor="#FFFFCC"> RegNo</td>
<?php
mysql_data_seek($dbtype, 0);
	while($rowx = mysql_fetch_object($dbtype)){
		print("<td bgcolor=\"#FFFFCC\">");
		print("$rowx->Description");
		print("</td>");
		}
?>
<td bgcolor="#00FFFF"><strong>Balance</strong></td>
</tr>
<?php
while($dbrow = mysql_fetch_row($dbx)){
	print("<tr>");
	$col_num = 0;
	foreach($dbrow as $key=>$value){
		if($dbrow[$col_num] > 0){
			print("<td>$dbrow[$col_num]</td>");
			}
		else {
			print("<td> </td>");
			}
		$col_num++;
		}
	print("</tr>\n");
	}
//total the columns
print("<tr bgcolor=\"#CCCCCC\">");
print("<td><strong>Total By Record</strong></td>");
	$alpha = b;
	$numeric = 2;
	$rows = mysql_num_rows($dbx)+1;
	for($i=1; $i < mysql_num_fields($dbx); $i++){
		print("<td><strong>=sum($alpha$numeric:$alpha$rows)</strong></td>");
		$alpha++;
		}
print("</tr>\n");
?>
</table>
<?php }else{

?>

<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" name="studentRoomApplication" id="studentRoomApplication">
            <table width="284" border="0">
        <tr>
          <td colspan="2" nowrap>
            <div align="right">
              <input name="action" type="submit" id="action" value="Add New">
            </div></td></tr>
        <tr>
          <td width="111" nowrap><div align="right"><span class="style67"> RegNo:</span></div></td>
          <td width="157" bordercolor="#ECE9D8" bgcolor="#CCCCCC"><span class="style67">
          <input name="key" type="text" id="key" size="40" maxlength="40">
          </span></td>
        </tr>
        <tr>
          <td nowrap><div align="right">Click Search: </div></td>
          <td bgcolor="#CCCCCC"><input type="submit" name="search" value="Search"></td>
        </tr>
      </table>
</form>
<?php
}
include('../footer/footer.php');
?>
