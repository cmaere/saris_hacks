<?php
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('administratorMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Manage Users';
	$szSubSection = 'Web Statistics';
	$szTitle = 'User Visits Statistics Sorted by IPs';
	include('administratorheader.php');

$currentPage = $_SERVER["PHP_SELF"];
$maxRows_stats = 20;
$pageNum_stats = 0;
if (isset($_GET['pageNum_stats'])) {
  $pageNum_stats = $_GET['pageNum_stats'];
}
$startRow_stats = $pageNum_stats * $maxRows_stats;

mysql_select_db($database_zalongwa, $zalongwa);
$query_stats = "SELECT ip, browser, received, page FROM stats ORDER BY received DESC";
$query_limit_stats = sprintf("%s LIMIT %d, %d", $query_stats, $startRow_stats, $maxRows_stats);
$stats = mysql_query($query_limit_stats, $zalongwa) or die(mysql_error());
$row_stats = mysql_fetch_assoc($stats);

if (isset($_GET['totalRows_stats'])) {
  $totalRows_stats = $_GET['totalRows_stats'];
} else {
  $all_stats = mysql_query($query_stats);
  $totalRows_stats = mysql_num_rows($all_stats);
}
$totalPages_stats = ceil($totalRows_stats/$maxRows_stats)-1;

$queryString_stats = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_stats") == false && 
        stristr($param, "totalRows_stats") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_stats = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_stats = sprintf("&totalRows_stats=%d%s", $totalRows_stats, $queryString_stats);
 
?>
 
      <form action="adminmanageuser.php" method="get" class="style24">
            <div align="right"><span class="style42"><font face="Verdana"><b>Search</b></font></span> 
              <font color="006699" face="Verdana"><b> 
              <input type="text" name="content" size="15">
              </b></font><font color="#FFFF00" face="Verdana"><b> 
              <input type="submit" value="GO" name="go">
            </b></font>            </div>
</form>        
      <p><a href="/stats/index.html">Web Statistics</a></p>
      <p>        <?php 

// Set the date and the table we will use for statistics retrieval  

$now  =  date ( "Y-m-d" );  
$linksql  =  "stats";  

// Populate our Total Distinct Hits variable $total  

$sql  =  "SELECT DISTINCT(ip) FROM $linksql ORDER BY ip" ;  
$results  =  mysql_query ( $sql );  
$total  =  mysql_num_rows ( $results );  
while ( $myrow  =  mysql_fetch_array ($results )) {  
$ip  =  $myrow [ "ip" ];}  

// Send header information for time span of statistics gathering  

echo  "<table><tr><td colspan= \"3 \" align= \"center \" >Statistics from: " ;  


// Retrieve the beginning date value from the database  

$sql =  "SELECT received FROM $linksql ORDER BY id LIMIT 1" ;  
$results  =  mysql_query ( $sql );  
while ( $myrow  =  mysql_fetch_array ($results )) {  
$date  = $myrow ["received" ];  
echo  $date ;  
}  

echo  " until $now </td></tr><tr><td colspan= \" 3 \" ><hr noshade></td></tr>" ;  

// Analyze data from the database  

$sql  =  "SELECT DISTINCT(ip) FROM $linksql WHERE ip NOT LIKE  
('196.44%') OR ip  
 NOT LIKE ('196.44%')" ;  
$results  =  mysql_query ( $sql );  
$offsite  =  mysql_num_rows ( $results );  
$onsite  = ( $total  -  $offsite );  

$sql  =  "SELECT DISTINCT(ip) FROM $linksql WHERE browser LIKE ('%MSIE%')" ;  
$results  =  mysql_query ( $sql );  
$ms  =  mysql_num_rows ( $results );  
$netscape  = ( $total  -  $ms );  

$sql  =  "SELECT DISTINCT(ip) FROM $linksql WHERE browser LIKE ('%WIN%')" ;  
$results  =  mysql_query ( $sql );  
$windows  =  mysql_num_rows ( $results );  
$mac  = ( $total  -  $windows );  

// Display Data  

echo  "<tr><td align=\"center\">" ;  
echo  "Onsite Hits: <br><b>$onsite</b></td><td align= \"center \">" ;  
echo  "Windows Hits: <br><b>$windows</b></td><td align= \"center \">" ;  
echo  "MS Explorer Hits: <br><b>$ms</b></td></tr>";  
echo  "<tr><td align=\"center\">" ;  
echo  "Offsite Hits: <br><b>$offsite</b></td><td align= \"center \">" ;  
echo  "Other Hits: <br><b>$mac</b></td><td align= \"center \">" ;  
echo  "Other Hits: <b><br>$netscape</b></td></td></tr>" ;  

// Create Percentage Data from our values  

$macval  = ( $windows /( $mac + $windows ));  
$winval  = ( $mac /( $mac + $windows ));  
$offsiteval  = ( $onsite /( $onsite + $offsite ));  
$onsiteval  = ( $offsite /( $onsite + $offsite ));  
$msval  = ( $netscape /( $netscape +$ms ));  
$nsval  = ( $ms /( $netscape + $ms ));  

// Create a function to call our graphics generation page gd.php  

function  graphic ( $blueval , $redval ){  
$pctrd  =  round($blueval*100,1 );  
$pctbl  =  round($redval*100,1 );  
$blueval  = ( $blueval * 200 );  
$redval  = ( $redval * 200 );  

echo  "  
<table><tr><td align= \"center \" >$pctbl%</td><td>  
<img src= \" gd.php?bluehg=$blueval&redhg=$redval \"  height= \" 100 \" >  
</td><td align= \" center \" >$pctrd%</td></tr></table>" ;  
}  

// Create our layout for graphic display and repeatedly call the graphic () function  

echo  "  
<tr><td colspan=\"3\" align=\"center\">  
<table cellpadding=\"5\" border=\"1\" bgcolor=\"#cccccc\">  
<tr><td align=\"center\" valign=\"bottom\">" ;  

graphic ( $onsiteval , $offsiteval );  

echo  "</td><td align=\"center\" valign=\"bottom\">" ;  

graphic ($winval ,$macval );  

echo  "</td><td align=\"center\" valign=\"bottom\">";  

graphic ( $msval , $nsval );  

echo  "</td></tr><tr><td align=\"center\">";  

echo  "Onsite VS Offsite" ;  
echo  "</td><td align=\"center\">" ;  
echo  "Windows VS Other" ;  
echo  "</td><td align=\"center\" align=\"center\">" ;  
echo  "Explorer VS Other" ;  
echo  "</td></tr></table><tr>" ;  
echo  "<td align=\"center\" colspan=\"3\">" ;  
echo  "&nbsp;</td></tr><tr><td>Total Distinct Hits: <b>" ;  
echo  "$total</b></td>" ;  
echo  "<td align=\"center\"></td><td  
align=\"left\">" ;  

// Analyze and calculate time elapsed data (i.e. Daily hits, hourly hits)  


$sql = "SELECT TO_DAYS(MAX(received)) - TO_DAYS(MIN(received)) AS record FROM $linksql";  
$results  =  mysql_query ( $sql );  
while ($myrow = mysql_fetch_array($results)) {  
$avgday = $myrow["record"];  
}  

// divide the total number of distinct hits by the difference in time  


@$avghits  = ( $total / $avgday );  

// Analyze and calculate time elapsed data (i.e. Daily hits, hourly hits)  

echo  "Avg. Daily Hits:" ;  

// implement round() function to accommodate avg. number of hits  

$avghits  = round ( $avghits );  
echo  "<b>$avghits</b><br><br>" ;  
echo  "Avg. Hourly Hits:<b>" ;  

// implement round() function to accommodate avg. number of hits  

echo round( $avghits / 24 ). "</b><br></td>" ;  
echo  "<tr><td colspan=\"3\">" ;  

// Select Total number of hits (not just distinct hits)  

$sql =  "SELECT COUNT(*) AS CNT FROM $linksql" ;  
$results  =  mysql_query ( $sql );  
while ( $myrow  =  mysql_fetch_array ($results )) {  
$bigtotal  =  $myrow ["CNT" ];  

// Repeat analysis for Total Overall Hits  

echo  "<tr><td>Total Overall Hits: <b>" ;  
echo  "$bigtotal</b></td>" ;  
echo  "<td width=\"45\" align=\"right\"></td><td  
align=\"left\">" ;  

@$avghits  = ( $bigtotal /$avgday );  

echo  "Avg. Daily Hits:" ;  

// implement round() function to accommodate avg. number of hits  

$avghits  =  round ( $avghits );  
echo  "<b>$avghits</b><br><br>" ;  
echo  "Avg. Hourly Hits:<b>" ;  

// implement round() function to accommodate avg. number of hits  

echo  round ( $avghits / 24 ). "</b><br></td></tr></table>" ;  
}  
mysql_close($zalongwa);
?>
</p>
      </p>
      <p>&nbsp;</p>
          <p>&nbsp;<a href="<?php printf("%25s?pageNum_stats=%25d%25s", $currentPage, max(0, $pageNum_stats - 1), $queryString_stats); ?>">Previous</a> <span class="style60">....</span>Records: <?php echo min($startRow_stats + $maxRows_stats, $totalRows_stats) ?>/<?php echo $totalRows_stats ?> <span class="style60">.....</span><a href="<?php printf("%25s?pageNum_stats=%25d%25s", $currentPage, min($totalPages_stats, $pageNum_stats + 1), $queryString_stats); ?>">Next</a> </p>
        
                    <table border="1" cellpadding="0" cellspacing="0">
            <tr>
              <td><strong>Computer IP</strong></td>
              <td><div align="center"><strong>Usename and Pages Visited</strong></div></td>
              <td><div align="center"><strong>Accessed on </strong></div></td>
              <td><div align="center"><strong>Web Browser</strong></div></td>
            </tr>
            <?php do { ?>
            <tr>
              <td><?php echo $row_stats['ip']; ?></td>
              <td nowrap><?php echo $row_stats['page']; ?></td>
              <td nowrap><?php echo $row_stats['received']; ?></td>
              <td nowrap><?php echo substr($row_stats['browser'],0,30); ?></td>
            </tr>
            <?php } while ($row_stats = mysql_fetch_assoc($stats)); ?>
</table>
            <p>&nbsp;<a href="<?php printf("%25s?pageNum_stats=%25d%25s", $currentPage, max(0, $pageNum_stats - 1), $queryString_stats); ?>">Previous</a> <span class="style60">......</span><a href="<?php printf("%25s?pageNum_stats=%25d%25s", $currentPage, min($totalPages_stats, $pageNum_stats + 1), $queryString_stats); ?>">Next</a> </p>
       
<?php
mysql_free_result($stats);
?>
<?php

	# include the footer
	include('../footer/footer.php');
?>