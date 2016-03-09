<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('admissionMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Communication';
	$szSubSection = 'News & Events';
	$szTitle = 'University News & Events';
	include('admissionheader.php');

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
$query_studentsuggestion = "SELECT received, fromid, toid, message FROM news ORDER BY received DESC";
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
-->
</style>To Add News, Click:
<a href="admissionPostNews.php">Publish News and or Events</a><hr><table width="721" border="1" cellpadding="0" cellspacing="0">
            <?php do { ?>
            <tr>
                <td width="61"><div align="right">Date:</div></td>
                <td width="703"><?php echo $row_studentsuggestion['received']; ?> - From: <?php echo $row_studentsuggestion['fromid']; ?></td>
            </tr>
            <tr>
                <td valign="top"><div align="right">Message:</div></td>
                <td><?php echo $row_studentsuggestion['message']; ?></td>
            </tr>
            <?php } while ($row_studentsuggestion = mysql_fetch_assoc($studentsuggestion)); ?>
</table>
		    <p><a href="<?php printf("%s?pageNum_studentsuggestion=%d%s", $currentPage, max(0, $pageNum_studentsuggestion - 1), $queryString_studentsuggestion); ?>">Previous</a> Message: <?php echo min($startRow_studentsuggestion + $maxRows_studentsuggestion, $totalRows_studentsuggestion) ?> of <?php echo $totalRows_studentsuggestion ?> <span class="style64">...</span><a href="<?php printf("%s?pageNum_studentsuggestion=%d%s", $currentPage, min($totalPages_studentsuggestion, $pageNum_studentsuggestion + 1), $queryString_studentsuggestion); ?>">Next</a> </p>
<?php

include('../footer/footer.php');
mysql_free_result($studentsuggestion);
?>
