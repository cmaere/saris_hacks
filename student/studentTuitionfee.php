<?php 
require_once('../Connections/sessioncontrol.php');
require_once('../Connections/zalongwa.php'); 
# include the header
include('studentMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Financial Records';
	$szTitle = 'Financial Transactions';
	$szSubSection = 'Transactions';
	include("studentheader.php");
	
?>
<?php
$query_cautionfeepaid = "SELECT student.Name, 
							  student.ProgrammeofStudy, 
							  student.EntryYear, 
							  tblcautionfee.RegNo, 
							  tblcautionfee.Amount, 
							  tblcautionfee.ReceiptNo, 
							  tblcautionfee.Paytype, 
							  tblcautionfee.ReceiptDate, 
							  tblcautionfee.Received, 
							  tblcautionfee.user,
							  tblcautionfee.Description 
						FROM student 
						INNER JOIN tblcautionfee ON 
								student.RegNo = tblcautionfee.RegNo 
						WHERE (student.RegNo = '$RegNo') 
								ORDER BY tblcautionfee.Received DESC";

@$cautionfeepaid = mysql_query($query_cautionfeepaid, $zalongwa) or die(mysql_error());
@$row_cautionfeepaid = mysql_fetch_assoc($cautionfeepaid);
@$totalRows_cautionfeepaid = mysql_num_rows($cautionfeepaid);

if (@$totalRows_cautionfeepaid > 0) {  echo @"Payment Report for Candidate:  \"$RegNo\" <hr>"; // Show if recordset not empty 
			//search degree code
			$dcode=$row_cautionfeepaid['ProgrammeofStudy'];
			$qdegree="select ProgrammeName from programme where ProgrammeCode='$dcode'";
			$dbdegre = mysql_query($qdegree);
			$row_degree=mysql_fetch_assoc($dbdegre);
			
						
		 echo $row_cautionfeepaid['Name'].": ".$row_cautionfeepaid['RegNo']."; ".$row_degree['ProgrammeName']; ; ?>
            <table width="200" border="1" cellpadding="0" cellspacing="0">
              <tr>
                 <td nowrap><strong> Category </strong></td>
				 <td nowrap><strong>Amount</strong></td>
				  <td nowrap><strong>Receipt No. </strong></td>
				  <td nowrap><strong>Receipt Date </strong></td>
                <td nowrap><strong> Recorded On </strong></td>
				<td nowrap><strong> Comments </strong></td>
              </tr>
			  
			  <?php $amount =0;
			  		$penalty = 0;
					$balance =0;
			  ?>
              <?php do { 
			  
					//search payment category
					$pay=$row_cautionfeepaid['Paytype'];
					$qpay="select Description from paytype where Id='$pay'";
					$dbpay=mysql_query($qpay);
					$row_pay=mysql_fetch_assoc($dbpay);
			  ?>
              <tr>
                <td nowrap><?php  echo $row_pay['Description']; ?></td>
				<td nowrap><?php echo $row_cautionfeepaid['Amount']; ?></td>
                <td nowrap><?php  echo $row_cautionfeepaid['ReceiptNo']; ?></td>
				<td nowrap><?php  echo $row_cautionfeepaid['ReceiptDate']; ?></td>
				<td nowrap><?php  echo $row_cautionfeepaid['Received']; ?></td>
				<td><?php echo $row_cautionfeepaid['Description'];?></td>
              </tr>
              <?php 
			  } while ($row_cautionfeepaid = mysql_fetch_assoc($cautionfeepaid)); ?>
</table>
            <?php }?>
            
<?php
mysql_free_result($cautionfeepaid);
?>
<?php
include('../footer/footer.php');
?>