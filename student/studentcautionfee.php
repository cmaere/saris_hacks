<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
include('studentMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Financial Records';
	$szTitle = 'Caution Fee Payments';
	$szSubSection = 'Caution Fee';
	include("studentheader.php");
	
?>
<?php
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
		<td nowrap><strong>RegNo</strong></td>
		<td nowrap><strong> Caution Fee </strong></td>
		<td><strong> Penalty</strong></td>
		<td><strong>Balance </strong></td>
		<td><strong>Refunded </strong></td>
	</tr>
	<?php 
	$i=1;
	$grandtotalpaid=0;
	$grandtotalpenalty=0;
		
	while($rowstudent = mysql_fetch_array($dbstudent)) {
			$name = $rowstudent['Name'];
			$regno = $rowstudent['RegNo'];
			$totalpaid =0;
						
			//query caution fee paid
			$qcautionfee = "SELECT Amount FROM tblcautionfee WHERE RegNo = '$regno' AND Paytype = 5";
			$dbcautionfee = mysql_query($qcautionfee);
						
			//sum up caution fee payments
			while ($rowcautionfee = mysql_fetch_array($dbcautionfee)){
				$amount = $rowcautionfee['Amount'];
				$totalpaid = $totalpaid+$amount;
				}
				$grandtotalpaid = $grandtotalpaid+$totalpaid;
			//query penalty fee paid
			$qpenalty = "SELECT Amount FROM tblcautionfee WHERE RegNo = '$regno' AND Paytype = 6";
			$dbpenalty = mysql_query($qpenalty);
			$totalpenalty =0;
			
			//sum up penalty payments
			while ($rowpenalty = mysql_fetch_array($dbpenalty)){
				$penaltyamount = $rowpenalty['Amount'];
				$totalpenalty = $totalpenalty+$penaltyamount;
				}
				$grandtotalpenalty=$grandtotalpenalty+$totalpenalty;
			//print student report
			if(number_format($totalpaid - $totalpenalty,2,'.',',')>0){
			?>
			<tr>
				<td> <?php echo $i ?> </td>
				<td nowrap> <?php echo $name ?> </td>
				<td nowrap> <?php echo $regno ?> </td>
				<td> <?php echo number_format($totalpaid,2,'.',',') ?> <div align="right"></div></td>
				<td> <?php echo number_format($totalpenalty,2,'.',',') ?> <div align="right"></div></td>
				<td> <?php echo number_format($totalpaid - $totalpenalty,2,'.',',') ?> <div align="right"></div></td>
			  <td nowrap> 
			  <?php $qrefund = "select RegNo FROM tblcautionfee WHERE (RegNo = '$RegNo' AND Paytype > 9) Order By Received Desc";
			  		$refund = mysql_query($qrefund);
					$row_refund= mysql_fetch_array($refund);
					$row_num = mysql_num_rows($refund);
						if($row_num>0){
									echo "<a href=\"housingRefundReport.php?id=$regno\">Yes</a>";
									}else{
									echo "No";
							}					
			   ?> 
			  <div align="right"></div></td>
			</tr>
    	<?php $i=$i+1;
		}
	}
 ?> 
	<tr>
	<td colspan="3"><strong>Grand Total: </strong></td>
	<td><div align="right"><strong><?php echo number_format($grandtotalpaid,2,'.',',') ?></strong> </div></td>
	<td><div align="right"><strong><?php echo number_format($grandtotalpenalty,2,'.',',') ?></strong></div></td>
	<td><div align="right"><strong><?php echo number_format($grandtotalpaid-$grandtotalpenalty,2,'.',',') ?></strong></div></td>
	</tr>
	</table> 
	<?php
 } 		
include('../footer/footer.php');
?>