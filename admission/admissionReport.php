<?php
require_once('../Connections/sessioncontrol.php');
require_once('../Connections/zalongwa.php');
require('pdf_engine.php');
$ayear=$_POST['ayear'];
$faculty=$_POST['faculty'];
if($faculty=="all")
{
$db=mysql_query("select * from faculty ORDER by FacultyName");
$dept=strtoupper('ALL DEPARTMENTS');
}else
{
$dp=mysql_query("select * from faculty where FacultyID='$faculty'");
$d=mysql_fetch_array($dp);
$dept=strtoupper($d['FacultyName']);
}
$pdf= new PDF_TOC(); 
$pdf->SetFont('Times','',12);
$pdf->AddPage();
$pdf->Cell(0,5,'KAMUZU COLLEGE OF NURSING',0,1,'C');
$pdf->Cell(0,15,''.$dept,0,2,'C');
$pdf->Image('logo.jpg',98,30,'C');
$pdf->Cell(0,130,'LIST OF ADMITTED STUDENTS  '.$ayear,0,1,'C');
$pdf->AddPage();
//Preface
//End of Preface
$pdf->startPageNums();
if($faculty=="all")
{
$faculty=mysql_query("select * from faculty ORDER by FacultyName");
}else
{
$faculty=mysql_query("select * from faculty  where FacultyID='$faculty' ORDER by FacultyName");
}
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
while($f=mysql_fetch_array($faculty))
{
$registered=mysql_query("select * from student,programme where programme.ProgrammeCode=student.ProgrammeofStudy  and programme.Faculty='$f[FacultyName]' and student.EntryYear='$ayear'")or die(mysql_error());
$nms=mysql_num_rows($registered);
if($nms>0)
{
$department=strtoupper($f['FacultyName']);	
$pdf->TOC_Entry($department, 0);
$query=mysql_query("select * from programme where Faculty='$f[FacultyName]' ORDER by ProgrammeName");	
while($r=mysql_fetch_array($query))
{
$Ename=$r['Title']." ".$r['ProgrammeName'];
$darasa=mysql_query("select * from student where ProgrammeofStudy='$r[ProgrammeCode]' and student.EntryYear='$ayear' and regno!='' ORDER by Name");
$num=mysql_num_rows($darasa);
if($num>0)
{
$Ename=strtoupper($Ename);
//$labelSize=12;
//$entrySize=9;
//$tocfont='Times';
//$pdf->SetFont($tocfont,'B',$labelSize);
$pdf->head($Ename);
$lines="                                      ";
$pdf->head($lines);
$Ename=titleCase($Ename);
$pdf->SetFont('Times','',10);
$pdf->TOC_Entry($Ename, 1);
//$pdf->TOC_Entry($Ename, 2);
$k=1;
while($c=mysql_fetch_array($darasa))
{
#################TITLE CASE FUNCTION
if($c['Sex']=='M'){
$male++;
}
else{
if($c['Sex']=='F'){
$female++;
}
else
{
$unknown++;
}
}
	        $rawname = $c['Name'];
		$expsurname = explode(",",$rawname);
		$surname = strtoupper($expsurname[0]);
		$othername = $expsurname[1];
		$expothername = explode(" ", $othername);
		$firstname = $expothername[1];
		$middlename = $expothername[2].' '.$expothername[3];



$sn=titleCase($surname);
$fn=titleCase($firstname);
$ln=titleCase($middlename);
######################END OF ALGORITHM
$coname=$k.". ".$surname." ".$fn." ".$ln." ( RegNo"." ".$c['RegNo'].")";
$pdf->Cell(0,5,$coname,0,1,'L');
//$pdf->TOC_Entry($coname, 1);
$k++;
}
$pdf->AddPage();
}else
{
//Skip Courses with No Students
}
}
}else
{
//Skip Null Department
}
}
//Generate and insert TOC at page 2
$pdf->insertTOC(2);
//Summarization
$pdf->Cell(0,5,'SUMMARY',0,1,'L');
$pdf->Cell(0,5,'------------------------------------------------------------',0,1,'L');
$pdf->Cell(0,5,'Male='.$male,0,1,'L');
$pdf->Cell(0,5,'Female='.$female,0,1,'L');
$pdf->Cell(0,5,'Unknown='.$unknown,0,1,'L');



//#################

$pdf->Output();


?>
