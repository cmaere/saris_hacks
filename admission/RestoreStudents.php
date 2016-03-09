<?php
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('admissionMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Admission Process';
	$szSubSection = 'Restore logs';
	$szTitle = 'Restore Deleted Student Records';
	include('admissionheader.php');
?>
<script type='text/javascript'>
function confirmdelete( data)
{
if(confirm("Are you sure you want to restore the record      "+data))
{
return true;
}else
{
return false;
}
}
</script>
<?php
if(isset($_POST['Remove']))
{
$stid=$_POST['stid'];
$remove_log=mysql_query("delete from studentlog where Id='$stid'")or die(mysql_error());
}
if(isset($_POST['delete']))
{
$Eq=$_POST[Eq];
$count=count($Eq);
if($count<=0)
{
echo "<table>
<tr><td>
<font color='red'>Choose Student please.</font>
</td></tr>
</table>";
}
else
{
$e=1;
for($j=0;$j<$count;$j++)
{
#Fetch Records
$sql = "SELECT * FROM studentlog WHERE Id ='$Eq[$j]'"; 
$update = mysql_query($sql) or die(mysql_error());
$update_row = mysql_fetch_array($update)or die(mysql_error());
	$regno = $update_row['RegNo'];
	$stdid = $update_row['Id'];
	$AdmissionNo = $update_row['AdmissionNo'];     
	$degree = $update_row['ProgrammeofStudy'];
	$faculty = $update_row['Faculty'];
	$ayear = $update_row['EntryYear'];
	$combi = $update_row['Subject'];
	$campus = $update_row['Campus'];
	$manner = $update_row['MannerofEntry'];
	$surname = $update_row['Name'];
	$dtDOB = $update_row['DBirth'];
	$age = $update_row['age'];
	$sex = $update_row['Sex'];
	$sponsor = $update_row['Sponsor'];
	$country = $update_row['Nationality'];
	$district =$update_row['District'];
	$region =$update_row['Region'];
	$maritalstatus = $update_row['MaritalStatus'];
	$address = $update_row['Address'];
	$religion = $update_row['Religion'];
	$denomination = $update_row['Denomination'];
	$postaladdress =$update_row['postaladdress'];
	$residenceaddress = addslashes($update_row['residenceaddress']);
	$disabilityCategory = $update_row['disabilityCategory'];
	$status = $update_row['Status'];
	$gyear = $update_row['GradYear'];
	$phone1 = $update_row['Phone'];
	$email1 = $update_row['Email'];
	$formsix = $update_row['formsix'];
	$formfour = $update_row['formfour'];
	$diploma = $update_row['diploma'];
	$School_attended_olevel = $update_row['School_attended_olevel'];
	$School_attended_alevel = $update_row['School_attended_alevel'];
	$name = $surname ;
//Added fields
$account_number=$update_row['account_number'];
$bank_branch_name=$update_row['bank_branch_name'];
$bank_name=$update_row['bank_name'];
$form4no=$update_row['form4no'];
$form4name=$update_row['form4name'];
$form6name=$update_row['form6name'];
$form6no=$update_row['form6no'];
$form7name=$update_row['form7name'];
$form7no=$update_row['form7no'];
$paddress=$update_row['paddress'];
$currentaddaress=$update_row['currentaddaress'];
$f4year=$update_row['f4year'];
$f6year=$update_row['f6year'];
$f7year=$update_row['f7year'];
#next of kin info
$kin_email=$update_row['kin_email'];
$kin=$update_row['kin'];
$kin_phone=$update_row['kin_phone'];
$kin_address=$update_row['kin_address'];
$kin_job=$update_row['kin_job'];
$studylevel=$update_row['studylevel'];
//***********             
#insert record
$sql="INSERT INTO student
(Name,AdmissionNo,
Sex,DBirth,
MannerofEntry,MaritalStatus,
Campus,ProgrammeofStudy,
Faculty,
Sponsor,GradYear,
EntryYear,Status,
Address,Nationality,
Region,District,Country,
Received,user,
Denomination, Religion,
Disability,f7year,
kin,kin_phone,
kin_address,kin_job,
disabilityCategory,Subject,
account_number,
bank_branch_name,
bank_name,
form7name,
form7no,
paddress,
Phone,
Email,
currentaddaress,
RegNo,
studylevel,
kin_relationship,
kin_email,
village
) 
VALUES
('$name','$AdmissionNo',
'$sex','$dtDOB',
'$manner','$maritalstatus',
'$campus','$degree',
'$faculty',' $sponsor',
'$gyear','$ayear',
'$status','$address',
'$country',
'$region','$district',
'$country',now(),
'$username','$denomination', 
'$religion','$disability','$f7year',
'$kin','$kin_phone',
'$kin_address','$kin_job',
'$disabilityCategory','$Subject',
'$account_number',
'$bank_branch_name',
'$bank_name',
'$form7name',
'$form7no',
'$paddress',
'$phone1',
'$email1',
'$currentaddaress',
'$regno',
'$studylevel',
'$kin_relationship',
'$kin_email',
'$village'
)";
//echo $sql;
$plg=mysql_query($sql)or die(mysql_error());
if($plg)
{
$delete=mysql_query("delete from studentlog where Id='$Eq[$j]'");
}else
{

}
}
}
}


//Begin of Filter Panel
$look="SELECT * FROM  studentlog   "; //where RegNo like '%$key'OR Name like '%$key%' OR AdmissionNo like '%$key'";
//Begin of Pagination
///START DISPLAYING RECORDS
$rowPerPage=10;
$pageNum=1;
if(isset($_GET['page']))
{
$pageNum=$_GET['page'];
}
$offset=($pageNum-1)*$rowPerPage;
$k=$offset+1;
$query=$look."LIMIT $offset,$rowPerPage";
$result=mysql_query($query) or die(mysql_error());
echo"<form action='$_SERVER[PHP_SELF]' method='POST'>";
echo "<table cellspacing='0' border='1' width='950' cellpadding='0'>
<tr class='dtable' bgcolor='#ccccf'>
<th>sN</th>
<th colspan='2'>Student</th>
<th>Faculty</th>
<th>Action</th>
<th>User</th>
<th>Date</th>
<th>Select</th>
</tr>";
while($r=mysql_fetch_array($result))
{
echo "<tr>
<td>&nbsp;$k</td>
<td>&nbsp;$r[Name]</td>
<td>&nbsp;$r[RegNo]</td>
<td>&nbsp;$r[Faculty]</td>
<td>&nbsp;$r[Action]</td>
<td>&nbsp;$r[ActUser]</td>
<td>&nbsp;$r[ActionDate]</td>
<td>";
if($r['Action']=='Updated' ||!$r['Action'])
{
//echo"<input type='checkbox' name='Eq[]' value='$r[Id]' Disabled>";
echo"<form action='$_SERVER[PHP_SELF]' method='POST'>";
echo"<input type='hidden' name='stid' value='$r[Id]'>";
?>
<input type='submit' name='Remove' value='Delete'
id="<?php echo $r['Name'];?>" onClick="return confirmdelete(this.id)"
>
<?php
echo"</form>";
}
else
{
echo"<input type='checkbox' name='Eq[]' value='$r[Id]'>";
}
echo"</td></tr>";
$k++;
}
echo"</table>";
$data=mysql_query($look);
$numrows=mysql_num_rows($data);
$result=mysql_query($data);
$row=mysql_fetch_array($data);
$maxPage=ceil($numrows/$rowPerPage);
$self=$_SERVER['PHP_SELF'];
$nav='';
for($page=1;$page<=$maxPage;$page++)
{
if($page==$pageNum)
{
$nav.=" $page";
$nm=$page;
}else
{
$nav.="<a href=\"$self?page=$page&key=$key\">$page</a>";
}
}
if($pageNum>1)
{
$page=$pageNum-1;
$prev="<a href=\"$self?page=$page&key=$key\">Previous</a>";
$first="<a href=\"$self?page=1\">[First]</a>";
}
else
{
$prev='&nbsp;';
$first='&nbsp;';
}

if($pageNum<$maxPage)
{
$page=$pageNum+1;
$next="<a href=\"$self?page=$page&key=$key\">Next</a>";
$last="<a href=\"$self?page=$maxPage\" class='mymenu'>[Last Page]</a>";
}
else
{
$next='&nbsp;';
$last='&nbsp;';
}
echo"<table>
<tr>
<td width='200'>&nbsp;&nbsp;&nbsp;&nbsp;$prev&nbsp;&nbsp;</td>
<td width='200'>&nbsp;&nbsp;Page $nm of $maxPage&nbsp;&nbsp;</td>
<td width='200'>&nbsp;&nbsp;$next&nbsp;&nbsp;</td>
<td>&nbsp;&nbsp;&nbsp;<font color='#CCCCCC'></font></td>
</tr></table></center>";
//End of Pagination


echo"<table><tr><td colspan='5'>";
?>
<input type='submit' name='delete' value='Restore' style='background-color:lightblue;color:black ;font-size:9pt;font-weight:bold'
id="All selected Students ?" onClick="return confirmdelete(this.id)">
<?php
echo"</td></tr></table>";
echo"</form>";
/*
}

else
{
$key="";
}
*/
///END OF THE ISSUE OF DELETE
include('../footer/footer.php');
?>


