<?php
   global $szSection, $szSubSection;
	$szSection = 'Admission Process';
	$szSubSection = 'Course Books';
 require_once('../Connections/zalongwa.php');
require('ifms.php');
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
$ayear=$_POST['ayear'];
$facultyId=$_POST['faculty'];
if(($facultyId=="all")||($facultyId==""))
{
//$db=mysql_query("select * from faculty ORDER by FacultyName");
$dept=strtoupper('ALL DEPARTMENTS');
$faculty=mysql_query("select * from faculty ORDER by FacultyName");
}else
{
$faculty=mysql_query("select * from faculty where FacultyID='$facultyId'");
$flty=mysql_query("select * from faculty where FacultyID='$facultyId'");
$d=mysql_fetch_array($flty);
$dept=strtoupper($d['FacultyName']);
}
$pdf= new PDF_TOC(); 
$pdf->SetFont('Times','',12);
$pdf->AddPage();
$pdf->Cell(0,5,'KAMUZU COLLEGE OF NURSING',0,1,'C');
$pdf->Cell(0,15,''.$dept,0,2,'C');
$pdf->Image('logo.jpg',98,30,'C');
$pdf->Cell(0,130,'LIST OF REGISTERED COURSES  '.$ayear,0,1,'C');
$pdf->AddPage();

$pdf->startPageNums();


while($f=mysql_fetch_array($faculty))
{
$registered=mysql_query("select * from course,programme where programme.ProgrammeCode=course.Programme
 and programme.Faculty='$f[FacultyName]'")or die(mysql_error());
$nms=mysql_num_rows($registered);
if($nms>0)
{
$department=$f['FacultyName'];	
$pdf->TOC_Entry($department, 0);
$query=mysql_query("select * from programme where Faculty='$f[FacultyName]' ORDER by ProgrammeName");	
while($r=mysql_fetch_array($query))
{
$Ename=$r['Title']." ".$r['ProgrammeName'];
$darasa=mysql_query("select * from course where Programme='$r[ProgrammeCode]' ORDER by CourseName");
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
$k=1;
while($c=mysql_fetch_array($darasa))
{
$coname=$k.". ".$c['CourseName']." (".$c['CourseCode'].")";
$pdf->Cell(0,5,$coname,0,1,'L');
//$pdf->TOC_Entry($coname, 1);
$k++;
}
$pdf->AddPage();
}else
{
//Skip Courses with No Courses
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
$pdf->Output();
?>
