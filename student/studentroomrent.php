<?php require_once('../Connections/zalongwa.php'); ?>
<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
include('studentMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Financial Records';
	$szTitle = 'Tuition Fee Payments Reports';
	$szSubSection = 'Tuition Fee';
	include("studentheader.php");
	

#Get all student in this cohot
$qstudent = "SELECT Name, RegNo from student WHERE RegNo = '$RegNo'";
$dbstudent = mysql_query($qstudent);
$totalstudent = mysql_num_rows($dbstudent);

 if ($totalstudent > 0) {
	?>
	<table border='1'cellpadding='0' cellspacing='0' bordercolor='#006600'>
	<tr>
		<td><strong>S/No. </strong></td>
		<td nowrap><strong>Candidate Name </strong></td>
		<td><strong>RegNo</strong></td>
		<td><strong> Amount </strong></td>
		<td nowrap><strong> Receipt No</strong></td>
		<td nowrap><strong>Receipt Date </strong></td>
		<td><strong>Recorder </strong></td>
		<td nowrap><strong>Recorded On </strong></td>
	</tr>
	<?php 
	$i=1;
	$grandtotalpaid=0;
	$totalpaid =0;
	while($rowstudent = mysql_fetch_array($dbstudent)) {
			$name = $rowstudent['Name'];
			$regno = $rowstudent['RegNo'];
			
						
			//query caution fee paid
			$qcautionfee = "SELECT * FROM tblcautionfee WHERE RegNo = '$RegNo' AND Paytype = 4";
			$dbcautionfee = mysql_query($qcautionfee);
			$rowcautionfee = mysql_fetch_array($dbcautionfee);
			//print student report
			if (intval($rowcautionfee['Amount'])>0){
			?>
			<?php do { ?>
			<tr>
				<td> <?php echo $i ?> </td>
				<td nowrap>  <?php echo $name ?> </td>
				<td nowrap>  <?php echo $regno ?> </td>
				<td nowrap>  <?php echo $rowcautionfee['Amount'];
										$amount = $rowcautionfee['Amount'];
										$totalpaid = $totalpaid+$amount;
				//number_format($totalpaid,2,'.',',') ?> <div align="right"></div></td>
				<td nowrap>  <?php echo $rowcautionfee['ReceiptNo'] ?> <div align="right"></div></td>
				<td nowrap>  <?php echo $rowcautionfee['ReceiptDate'] ?> <div align="right"></div></td>
				<td nowrap>  <?php echo $rowcautionfee['user'] ?> <div align="right"></div></td>
				<td nowrap>  <?php echo $rowcautionfee['Received'] ?> <div align="right"></div></td>
				
	  </tr>
	  <?php $i=$i+1;
	  } while ($rowcautionfee = mysql_fetch_assoc($dbcautionfee)); ?>
    <?php }
}
    ?> 
	<tr>
	<td colspan="3"><strong>Grand Total: </strong></td>
	<td><div align="right"><strong><?php echo number_format($totalpaid,2,'.',',') ?></strong> </div></td>
	<td colspan="5"><strong>End of Report </strong></td>
	</tr>
	</table> 
	<?php
 } 			
include('../footer/footer.php');
?>