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
$szTitle = 'Entry Qualification';
include('admissionheader.php');
include('styles.inc');
$rowPerPage=20;
$pageNum=1;


echo"<table cellpadding='0' cellspacing='0'>";
echo"<tr>
<td>
<fieldset>
<legend>Form  four Results</legend>
<form action='$_SERVER[PHP_SELF]' method='GET'>
<input type='hidden' name='category' value='1'>
<input type='submit' name='ok' value='Add Form Four Results'>
</legend>
</form>
</fieldset>
</td>

<td>
<fieldset>
<legend>Form  Six Results</legend>
<form action='$_SERVER[PHP_SELF]' method='GET'>
<input type='hidden' name='category' value='2'>
<input type='submit' name='ok' value='Add Form Six Results'>
</legend>
</form>
</fieldset>
</td>

<td>
<fieldset>
<legend>Equivalent Results</legend>
<form action='$_SERVER[PHP_SELF]' method='GET'>
<input type='hidden' name='category' value='3'>
<input type='submit' name='ok' value='Equivalent Results'>
</legend>
</form>
</fieldset>
</td>

</tr>
</table>";





$category=$_GET['category'];

//FILTER PANEL
if(!$category)
{
//Skip Panel
}
else
{
echo"<form action='$_SERVER[PHP_SELF]' method='GET'>";
echo"<table>
<tr><td>Search by Admission No /Reg No</td>
<input type='hidden' name='category' value='$category'>
<td><input type='text' name='regno'></td>
<td><input type='submit' name='Go' value='Go'></td></tr>
</table>";
}


echo"</form>";

if(isset($_GET['page']))
{
$pageNum=$_GET['page'];
}
$offset=($pageNum-1)*$rowPerPage;
$sn=$offset+1;

if(isset($_GET['regno']))
{
$regno=$_GET['regno'];
$student="SELECT * FROM student where AdmissionNo  like '%$regno'";
}else
{
$student="SELECT * FROM student";
}

$cat=mysql_query($student ." LIMIT $offset,$rowPerPage");
$num=mysql_num_rows($cat);

//$category=$_GET['category'];
echo "<table class='dtable' width='750' cellspacing='0' cellpadding='0'>";
echo"<tr>
<th>S/N</th>
<th>Name</th>
<th>AdmissionNo</th>
<th>OPTION</th>
</tr>";

if($category==1)
{
//Case it is FORM FOUR RESULTS
while($j=mysql_fetch_array($cat))
{
echo"
<form action='olevel.php' method='GET'>
<input type='hidden' name='index' value='$j[form4no]'>
<input type='hidden' name='regno' value='$j[AdmissionNo]'>
<input type='hidden' name='category' value='$category'>
<tr class='dhead'>
<td>&nbsp;$sn</td>
<td>&nbsp;$j[Name]</td>
<td>&nbsp;$j[AdmissionNo]</td>
<td>
<input type='submit' value='Add Form Four Results'>
</td>
</tr></form>";
$sn++;
}
}

if($category==2)
{
//Case it is FORM SIX RESULTS
while($j=mysql_fetch_array($cat))
{
echo"
<form action='olevel.php' method='GET'>
<input type='hidden' name='index' value='$j[form6no]'>
<input type='hidden' name='regno' value='$j[AdmissionNo]'>
<input type='hidden' name='category' value='$category'>
<tr class='dhead'>
<td>&nbsp;$sn</td>
<td>&nbsp;$j[Name]</td>
<td>&nbsp;$j[AdmissionNo]</td>
<td>
<input type='submit' value='Add Form Six Results'>
</td>
</tr></form>";
$sn++;
}
}

if($category==3)
{
//Case it is EQUIVALENT RESULTS
while($j=mysql_fetch_array($cat))
{
echo"
<form action='olevel.php' method='GET'>
<input type='hidden' name='index' value='$j[form7no]'>
<input type='hidden' name='regno' value='$j[AdmissionNo]'>
<input type='hidden' name='category' value='$category'>
<tr class='dhead'>
<td>&nbsp;$sn</td>
<td>&nbsp;$j[Name]</td>
<td>&nbsp;$j[AdmissionNo]</td>
<td>
<input type='submit' value='Add Equilvalent Results'>
</td>
</tr></form>";
$sn++;
}


}




echo "</table>";
$rec=mysql_query($student);
$numrows=mysql_num_rows($rec);
$resu=mysql_query($rec);
$row=mysql_fetch_array($rec);
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
$nav.="<a href=\"$self?page=$page\" class='mymenu'>$page</a>";

}
}
if($pageNum>1)
{
$page=$pageNum-1;
$prev="<a href=\"$self?page=$page&category=$category\"><img src='./images/previous.gif'></a>";
$first="<a href=\"$self?page=1\" class='mymenu'>[First]</a>";
}
else
{
$prev='&nbsp;';
$first='&nbsp;';
}

if($pageNum<$maxPage)
{
$page=$pageNum+1;
$next="<a href=\"$self?page=$page&category=$category\" class='mymenu'><img src='./images/next.gif'></a>";
$last="<a href=\"$self?page=$maxPage\" class='mymenu'>[Last Page]</a>";
}
else
{
$next='&nbsp;';
$last='&nbsp;';
}
$d=date('Y');
$cd=date('d/m/y');
echo"<center><table>
<tr>
<td>&nbsp;$prev&nbsp; </td>
<td>&nbsp;&nbsp;Page $nm of $maxPage&nbsp;</td>
<td>&nbsp;$next&nbsp;</td>
</tr></table></center>";




?>


