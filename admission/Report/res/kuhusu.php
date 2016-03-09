<?php
mysql_connect('localhost','root','deo');
mysql_select_db('zalongwamuco');
?>
<style type="text/css">
<!--
	table.page_header 
	{
	width: 100%;
	border: 0px; 
	background-color: #DDDDFF; 
	border-bottom: solid 1mm #AAAADD;
	padding: 2mm 
	}
	.page_table 
	{
	width: 100%;
	border: 1px solid black; 
	background-color: #DDDDFF; 
	}
	table.page_footer
	{
	width: 100%; 
	border: none; 
	background-color:#DDDDFF;
	border-top: solid 1mm #AAAADD;
	padding: 2mm}
	div.note {
	border: solid 1mm #DDDDDD;
	background-color: #EEEEEE; 
	padding: 2mm; 
	border-radius: 2mm 2mm;
	width: 100%;
	}
	ul.main 
	{ 
	width: 95%;
	list-style-type: square;
	}
	ul.main li 
	{ 
	padding-bottom: 2mm;
	}
	h1 {
	text-align: center; 
	font-size: 20mm
	}
	h3 {
	text-align: center; 
	font-size: 14mm
	}

table.dtable td{
border-left: 1px solid #6B78A9; 
border-top: 1px solid #6B78A9; 

}
.dtable{
border: 1px solid #6B78A9; 
background-color:white ;
}
.dhead th
{
background-color: #F7F7F4; 
border:1px solid #6B78A9;
}
-->
</style>
<?php

##################################################################
function  titleCase($string) 
 { 
        $len=strlen($string); 
        $i=0; 
        $last= ""; 
        $new= ""; 
        $string=strtoupper($string); 
        while  ($i<$len): 
                $char=substr($string,$i,1); 
                if  (ereg( "[A-Z]",$last)): 
                        $new.=strtolower($char); 
                else: 
                        $new.=strtoupper($char); 
                endif; 
                $last=$char; 
                $i++; 
        endwhile; 
        return($new); 
}
####################################################
?>

<page backtop="14mm" backbottom="14mm" backleft="10mm" backright="10mm" style="font-size: 12pt">
<page_header>
<table class="page_header">
<tr>
<td style="width: 50%; text-align: left">
SARIS
</td>
<td style="width: 50%; text-align: right">
INSTITUTE OF FINANCE MANAGEMENT
</td>
</tr>
</table>
</page_header>

<page_footer>
<table class="page_footer">
<tr>
<td style="width: 33%; text-align: left;">
Institute of Finance Management
</td>
<td style="width: 34%; text-align: center">
Page [[page_cu]]/[[page_nb]]
</td>
<td style="width: 33%; text-align: right">
&copy;SARIS <?php echo date('Y');?>
</td>
</tr>
</table>
</page_footer>

<bookmark title="Cover Page" level="0" ></bookmark>	
<div style="text-align: center; width: 100%;">
<br>
<img src="./images/logo.jpg" width='700' height='800'>
<br>
</div>
<br><br>
<div class="note">
Email:&nbsp;dvc@africaonline.com<br>
Url:&nbsp;http://www.ifm.ac.tz<br>
Postal Address:&nbsp;78890 DAR ES SALAAM<br>
</div>
</page>


<?php
$M=0;
$F=0;
$U=0;

$faculty=mysql_query("select * from faculty");
while($f=mysql_fetch_array($faculty))
{
$test=mysql_query("select * from student,programme where student.ProgrammeofStudy=programme.ProgrammeCode and programme.Faculty='$f[FacultyName]'");

$test_no=mysql_num_rows($test);
if($test_no==0)
{
//SKIP NULL STUDENTS
}else
{
echo"<page pageset='old'>";
echo"<bookmark title='$f[FacultyName]' level='0' ></bookmark>";
$department=mysql_query("select * from department where Faculty='$f[FacultyID]'");
while($d=mysql_fetch_array($department))
{
echo"<bookmark title='$d[DeptName]' level='1' ></bookmark>";
$programme=mysql_query("select * from programme where Faculty='$d[DeptName]'");
while($p=mysql_fetch_array($programme))
{
echo"<bookmark title='$p[ProgrammeName]' level='2' ></bookmark>";

echo"<h2>$p[Title]-$p[ProgrammeName]</h2>";
echo"<br>";
echo"<table width='1000' class='dtable' cellspacing='0' cellpadding='0'>";
$query=mysql_query("select * from student where ProgrammeofStudy='$p[ProgrammeCode]' limit 100");
echo"<tr class='dhead'>";
echo"<th>S/N</th>
<th>Name</th>
<th>REGNO</th>
<th>SEX</th>
<th>SPONSOR</th>
<th>STATUS</th>";
echo"</tr>";
$k=1;
while($r=mysql_fetch_array($query))
{
$st=mysql_query("select * from studentstatus where StatusID='$r[Status]'");
$s=mysql_fetch_array($st);
$status=$s['Status'];
//Count Genders
if($r['Sex']=='M')
{
$M++;
}else
{
if($r['Sex']=='F')
{
$F++;
}else
{
$U++;
}
}

//END OF GENDER COUNTING
//NAMES
$rawname = $r['Name'];
$expsurname = explode(",",$rawname);
$surname = strtoupper($expsurname[0]);
$othername = $expsurname[1];
$expothername = explode(" ", $othername);
$firstname = $expothername[1];
$middlename = $expothername[2].' '.$expothername[3];
$sn=titleCase($surname);
$fn=titleCase($firstname);
$ln=titleCase($middlename);


echo"<tr>
<td>$k</td>
<td>$sn &nbsp;$fn,$ln</td>
<td>$r[RegNo]</td>
<td>$r[Sex]</td>
<td>$r[Sponsor]</td>
<td>$status</td>
</tr>";
$k++;
}
echo"</table>";
}
}
echo"</page>";
}
}
?>	
<page pageset="old">
<bookmark title="Summary" level="0" ></bookmark>	
<div class="note">
SUMMARY :<br>
</div>
<?php
echo"<table border='1' width='100%'>";
echo"<tr><td>MALE</td><td>$M</td></tr>";
echo"<tr><td>FEMALE</td><td>$F</td></tr>";
echo"<tr><td>UNKNOWN</td><td>$U</td></tr>";
echo"</table>";
?>
</page>
