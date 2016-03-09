<?php 
require_once('../Connections/sessioncontrol.php');
require_once('../Connections/zalongwa.php');
# include the header
include('studentMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Accommodation';
	$szTitle = 'Room Application Status';
	$szSubSection = 'Application Status';
	//$additionalStyleSheet = './general.css';
	include("studentheader.php");


$query_roomapplication = "SELECT RegNo, AppYear, AllCriteria, Hall, Received, Status, Processed FROM roomapplication WHERE RegNo = '$RegNo' ORDER BY AppYear DESC";
$roomapplication = mysql_query($query_roomapplication, $zalongwa) or die(mysql_error());
$row_roomapplication = mysql_fetch_assoc($roomapplication);
$totalRows_roomapplication = mysql_num_rows($roomapplication);
?>
<?php
  $i = 1; 
		   do { 
		   
		  $statusvalue = $row_roomapplication['Status']; 
		  if ($statusvalue ==0){
		  $status = "Application Delivered on";
		  $processed = "Pending Application Since ";
		  }else{
		  $status = "Application Delivered on";
		  $processed = "Officially Processed on";
		  }
?>
<table width="376" border="1" cellpadding="0" cellspacing="0">
                         <tr>
                          <td width="170" align="left" valign="top" nowrap>Your RegNo</td>
                          <td width="190" align="left" valign="middle"><?php echo $row_roomapplication['RegNo']; ?></td>
                        </tr>
                        <tr>
                          <td width="170" align="left" valign="top" nowrap><?php echo $status; ?></td>
                          <td align="left" valign="middle"><?php echo $row_roomapplication['Received']; ?></td>
                        </tr>
					    <tr>
                          <td width="170" align="left" valign="top" nowrap><?php echo $processed; ?></td>
                          <td align="left" valign="middle"><?php echo $row_roomapplication['Processed']; ?></td>
                        </tr>
					    <tr>
                          <td width="170" align="left" valign="top" nowrap>Application Category No.</td>
                          <td align="left" valign="middle"><?php echo $row_roomapplication['AllCriteria']; ?></td>
                        </tr>
                        <tr>
                          <td width="170" align="left" valign="top" nowrap>Descriptions</td>
                          <td align="left" valign="middle"><?php echo $row_roomapplication['Hall']; ?></td>
                        </tr>
                        <?php 
					  	echo $i. ". Year ". $row_roomapplication['AppYear']; 
		  				 $i=$i+1;
					  } while ($row_roomapplication = mysql_fetch_assoc($roomapplication)); ?>
</table>
<?php
include('../footer/footer.php');
mysql_free_result($studentsuggestion);
?>