<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Communication';
	$szSubSection = 'Check Message';
	$szTitle = 'Check Message';
	include('lecturerheader.php');

$maxRows_studentsuggestion = 1;
$pageNum_studentsuggestion = 0;
if (isset($_GET['pageNum_studentsuggestion'])) {
  $pageNum_studentsuggestion = $_GET['pageNum_studentsuggestion'];
}
$startRow_studentsuggestion = $pageNum_studentsuggestion * $maxRows_studentsuggestion;

$colname_studentsuggestion = "1";
if (isset($_COOKIE['RegNo'])) {
  $colname_studentsuggestion = (get_magic_quotes_gpc()) ? $_COOKIE['RegNo'] : addslashes($_COOKIE['RegNo']);
}
mysql_select_db($database_zalongwa, $zalongwa);
$query_studentsuggestion = "SELECT id, received, fromid, toid, message,replied FROM suggestion WHERE toid = 'admin' ORDER BY received DESC";
$query_limit_studentsuggestion = sprintf("%s LIMIT %d, %d", $query_studentsuggestion, $startRow_studentsuggestion, $maxRows_studentsuggestion);
$studentsuggestion = mysql_query($query_limit_studentsuggestion, $zalongwa) or die(mysql_error());
$row_studentsuggestion = mysql_fetch_assoc($studentsuggestion);

if (isset($_GET['totalRows_studentsuggestion'])) {
  $totalRows_studentsuggestion = $_GET['totalRows_studentsuggestion'];
} else {
  $all_studentsuggestion = mysql_query($query_studentsuggestion);
  $totalRows_studentsuggestion = mysql_num_rows($all_studentsuggestion);
}
$totalPages_studentsuggestion = ceil($totalRows_studentsuggestion/$maxRows_studentsuggestion)-1;

$queryString_studentsuggestion = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_studentsuggestion") == false && 
        stristr($param, "totalRows_studentsuggestion") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_studentsuggestion = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_studentsuggestion = sprintf("&totalRows_studentsuggestion=%d%s", $totalRows_studentsuggestion, $queryString_studentsuggestion);

?>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
.style2 {color: #990000}
-->
</style>

<table width="669" border="1" cellpadding="0" cellspacing="0" bordercolor="#990000">
            <?php do { ?>
            <tr>
                <td width="66"><div align="right">Date:</div></td>
                <td width="593"><?php echo $row_studentsuggestion['received']; ?></td>
            </tr>
            <tr>
                <td><div align="right">From:</div></td>
                <td><?php $from=$row_studentsuggestion['fromid']; 
						  $id=$row_studentsuggestion['id']; 
								//select student
								$qstudent = "SELECT Name, RegNo, ProgrammeofStudy from student WHERE RegNo = '$from'";
								$dbstudent = mysql_query($qstudent) or die("Mwanafunzi huyu hana matokeo".  mysql_error()); 
								$row_result = mysql_fetch_array($dbstudent);
								$name = $row_result['Name'];
								$regno = $row_result['RegNo'];
								$degree = $row_result['ProgrammeofStudy'];
								
								//get degree name
								$qdegree = "Select Title from programme where ProgrammeCode = '$degree'";
								$dbdegree = mysql_query($qdegree);
								$row_degree = mysql_fetch_array($dbdegree);
								$programme = $row_degree['Title'];
								echo  "$name - $regno - $programme";	
							//echo $row_studentsuggestion['fromid'];
				 ?></td>
            </tr>
            <tr>
                <td valign="top"><div align="right">Message:</div></td>
                <td><?php echo $row_studentsuggestion['message']; ?></td>
            </tr>
			<tr>
                <td valign="top"><div align="right" class="style2">Comments:</div></td>
                <td><span class="style2"><?php echo $row_studentsuggestion['replied']; ?></span></td>
            </tr>
            <?php } while ($row_studentsuggestion = mysql_fetch_assoc($studentsuggestion)); ?>
</table>
<p><a href="<?php printf("%s?pageNum_studentsuggestion=%d%s", $currentPage, max(0, $pageNum_studentsuggestion - 1), $queryString_studentsuggestion); ?>">Previous</a> Message: <?php echo min($startRow_studentsuggestion + $maxRows_studentsuggestion, $totalRows_studentsuggestion) ?> of <?php echo $totalRows_studentsuggestion ?> <span class="style64 style1">...</span><a href="<?php printf("%s?pageNum_studentsuggestion=%d%s", $currentPage, min($totalPages_studentsuggestion, $pageNum_studentsuggestion + 1), $queryString_studentsuggestion); ?>">Next</a> <span class="style64 style1">.......</span><?php echo "<a href=\"admissionSuggestionBox.php?from=$from&id=$id\">Reply Message</a>" ?></p>
       
<?php

include('../footer/footer.php');
mysql_free_result($studentsuggestion);
?>
