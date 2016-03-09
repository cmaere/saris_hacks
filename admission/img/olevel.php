<?php
   #get connected to the database and verfy current session
    require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	# initialise globals
	include('admissionMenu.php');
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Admission Process';
	$szSubSection = 'Entry Qualification';
	

//Header Specification
$category=$_GET['category'];
if($category==1)
{
$szTitle = 'Form Four Results Recording';
}
if($category==2)
{
$szTitle = 'Form Six Results Recording';
}

if($category==3)
{
$szTitle = 'Equivalent Results Recording';
}




	include('admissionheader.php');

//Combo BOXES



function gradeAdd()
{
echo"<select name='marks'>";
echo"<option value=''>--Select Grade--</option>";
$g=array('A','B','C','D','E','F');
for($k=0;$k<count($g);$k++)
{
echo"<option value='$g[$k]'>$g[$k]</option>";
}
echo"</select>";
}

//*****************
include('styles.inc');
if(isset($_GET[regno]))
{
$regno=$_GET['regno'];
$index=$_GET['index']; 
$category=$_GET['category'];

}

$category=$_GET['category'];


//***********Delete Record
if(isset($_GET['deleteCode']))
{
$deleteCode=$_GET['deleteCode'];
$d=mysql_query("delete from olevel_results where Id='$deleteCode'");
}
//******************Save Record
if(isset($_GET['save']))
{
$save=$_GET['save'];
$subjectCode=$_GET['subjectCode'];
$indexNo=$_GET['indexNo'];
$reg=$_GET['regno'];
$grad=$_GET['marks'];
$category=$_GET['category'];
$s=mysql_query("insert into olevel_results(subjectID,indexNo,regno,grade,category) 
values('$subjectCode','$indexNo','$reg','$grad','$category')")or die(mysql_error());
}
//***********Save Changes after edit
if(isset($_GET['saveEdit']))
{
$subjectID=$_GET['subjectID'];
$indexNo=$_GET['indexNo'];
$regno=$_GET['regno'];
$grade=$_GET['grade1'];
$Id=$_GET['Id'];
$user=$_GET['usr'];
$category=$_GET['category'];
$e=mysql_query("update olevel_results set subjectID='$subjectID',regno='$regno',indexNo='$indexNo',grade='$grade' where  Id='$Id'");
}
$me=$_SERVER['PHP_SELF'];
if(isset($_GET['user']))
{
$user=$_GET['user'];
//***Edit
if(isset($_GET['edit']))
{
$edit=$_GET['edit'];
if($edit==true)
{				
$state=1;
}
}
//****Save
}
else
{
//***************Default
echo"";
}
$order1="<a href='$me?order=1'><img src='images/up.gif'></a>";
$order2="<a href='$me?order=2'><img src='images/down.gif'></a>";
$order3="<a href='$me?order=3'><img src='images/up.gif'></a>";
$order4="<a href='$me?order=4'><img src='images/down.gif'></a>";
$order5="<a href='$me?order=5'><img src='images/up.gif'></a>";
$order6="<a href='$me?order=6'><img src='images/down.gif'></a>";
$order7="<a href='$me?order=7'><img src='images/up.gif'></a>";
$order8="<a href='$me?order=8'><img src='images/down.gif'></a>";
if((isset($_GET['order']))||(isset($_GET['order'])))
{
$order=$_GET['order'];
switch($order)
{
case 1:$query="select * from olevel_results order by regno asc";break;
case 2:$query="select * from olevel_results order by regno desc";break;
case 3:$query="select * from olevel_results order by subjectID asc";break;
case 4:$query="select * from olevel_results order by subjectID desc";break;
case 5:$query="select * from olevel_results order by indexNo asc";break;
case 6:$query="select * from olevel_results order by indexNo desc";break;
case 7:$query="select * from olevel_results order by grade asc";break;
case 8:$query="select * from olevel_results order by grade desc";break;
default:$query="select * from olevel_results";
}
}else
{
$query="select * from olevel_results";
}
$order=$_GET['order'];
$data=mysql_query($query)or die(mysql_error());
echo"<form action='$me' method='GET'>";
echo"<table  cellspacing='0' cellpadding='0' class='dtable'>";
echo"<tr class='dhead'>
<th class='dleft'>S/N</th>";
echo"<th>ADMISSION No";
echo $order1."&nbsp;".$order2;
echo"</th>";
echo"<th>SUBJECT";
echo $order3."&nbsp;".$order4;
echo"</th>";
echo"<th>GRADE";
echo $order7."&nbsp;".$order8;
echo"</th>";
echo"<th>INDEX No";
echo $order5."&nbsp;".$order6;
echo"</th>";
echo"<th colspan='2' class='ficha'>ACTION</th>
</tr>";
$j=1;
while($r=mysql_fetch_array($data))
{
$c=mysql_query("select * from olevel_results where Id='$r[Id]' and category='$category'");
$cp=mysql_fetch_array($c);
if($edit==true && $user==$r['Id'])
{
$state1=1;
echo"<tr class='editrow'>";
echo"<td class='dleft'>$j</td>";
echo"<td>
<input type='hidden' name='usr' value='$r[Id]' class='editfield'>
<input type='hidden' name='order' value='$order'>
<input type='hidden' name='category' value='$category'>
<input type='hidden' name='Id' value='$r[Id]' class='editfield'>";
echo"<input type='text' name='regno' value='$r[regno]' class='editfield'>
</td>";
echo"<td>";
echo"<select name='subjectID'>";
if($r['subjectID'])
{
$query_somo=mysql_query("select * from subject_olevel where subjectID='$r[subjectID]'");
$sm=mysql_fetch_array($query_somo);
echo"<option value='$sm[subjectID]'>$sm[subjectName]</option>";
}else
{
echo"<option value=''>--Select Subject--</option>";
}
$somo_query="select * from subject_olevel where subjectID !='$r[subjectID]'";
$somo=mysql_query($somo_query);
while($s=mysql_fetch_array($somo))
{
echo"<option value='$s[subjectID]'>$s[subjectName]</option>";
}
echo"</select>";
echo"</td>";
echo"<td>";
echo"<select name='grade1'>";
$g=array('A','B','C','D','E','F');
if($r['grade'])
{
echo"<option value='$r[grade]'>$r[grade]</option>";
}else
{
echo"<option value=''>--Select Grade--</option>";
}
for($k=0;$k<count($g);$k++)
{
if($r['grade']==$g['$k'])
{
echo"<option value='$g[$k]'>$g[$k]</option>";
}else
{
echo"<option value='$g[$k]'>$g[$k]</option>";
}
}
echo"</select>";
echo"</td>";
echo"<td>
<input type='hidden' name='category' value='$category'>
<input type='text' name='indexNo' value='$r[indexNo]' class='editfield'>
</td>";
echo"<td class='ficha'>
<input type='submit' name='saveEdit' value='Save'>
</td>";
}
else
{
echo"<tr>";
echo"<td>&nbsp;$j</td>";
echo"<td>&nbsp;$r[regno]</td>";
echo"<td>&nbsp;$r[subjectID]</td>";
echo"<td>&nbsp;$cp[grade]</td>
<td>&nbsp;$r[indexNo]</td>";
echo"<td class='ficha'>";
if($state==1)
{
echo"&nbsp;";
}else
{

echo"<a href='$me?user=$r[Id]&edit=true&cid=$r[regno]&order=$order&category=$category' title='Click to Edit Record'><img src='images/edit.gif'></a>";
}
echo"</td>";
}
//buttons
if($edit==true )
{
echo"<td>";
if($state==1)
{
echo"&nbsp;";
if($state1==1)
{
echo"<a href='$me?order=$order' title='Click to Cancel Edit of Record'><img src='images/cancel.gif'></a>";
$state1=0;
}else
{
}
}
else
{
echo"<a href='$me?order=$order' title='Click to Cancel Edit of Record'><img src='images/cancel.gif'></a>";
}
echo"</td>";

}else
{
echo"<td class='ficha'>
<a href='$me?deleteCode=$r[Id]&order=$order' onClick='confirmdelete()' title='Click to Delete this Record'>
<img src='images/delete.gif'></a></td>";
}
$j++;
}
if($edit==true )
{

}else
{
echo"<tr>
<td>
$j
</td>
<td>
<input type='text' name='regno' value='$regno' readonly>
</td>
<td>";

if($regno)
{
$query="select * from subject_olevel where subjectID NOT IN (select subjectID from olevel_results where regno='$regno')";
}else
{
$query="select * from subject_olevel";
}
echo"<select name='subjectCode'>";
echo"<option value=''>--Select Subject--</option>";
$somo=mysql_query($query);
while($s=mysql_fetch_array($somo))
{
echo"<option value='$s[subjectID]'>$s[subjectName]</option>";
}
echo"</select>";
echo"</td>";
echo"<td>";
gradeAdd();
echo"</td>
<td >
<input type='text' name='indexNo' value='$index'>
</td>";
echo"<td>
<input type='hidden' name='order' value='$order'>
<input type='hidden' name='regno' value='$regno'>
<input type='hidden' name='index' value='$index'>
<input type='hidden' name='category' value='$category'>
<input type='submit' name='save' value='Save' title='Click to Save New Record'>
</td>";
echo"<td>&nbsp;</td>";
echo"</tr>";
}
echo"</table>";
echo"<a href='./studentlist.php?category=$category'>Finish</a>";
?>




