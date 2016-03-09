<?php
require('../fpdf.php');
require_once('../../../src/common.php');



function getCourseGrade($Code,$year,$reg_num,$class_ID,$semesterID)
    {
    
     global $dbConn;
        
    
     $sqlCoursegrade=sprintf("SELECT students_final_grades.Course_Code, students_final_grades.FinalGrade_College,students_eos_results.EOSAverage_College
                              FROM students_final_grades,students_eos_results
                              WHERE students_final_grades.Stud_RegNum='%s'
                              AND students_final_grades.AcYr='%s'
                              AND students_final_grades.Class_ID='%s'
                              AND students_final_grades.Stud_RegNum=students_eos_results.Stud_RegNum
                              AND students_eos_results.Semester='%s'
                              AND students_final_grades.Course_Code='%s'",$reg_num,$year,$class_ID,$semesterID,$Code);
                     
     $grade=$dbConn->query($sqlCoursegrade);
     
     $gradeObj=$grade->fetchRow(DB_FETCHMODE_OBJECT);
     
     $val[0]=$gradeObj->FinalGrade_College;
     $val[1]=$gradeObj->EOSAverage_College;
          
     return $val;
    
    }
    
class PDF extends FPDF
{

function getCourseName($accyr,$regnum,$class_ID,$semester)
    {
    global $dbConn;
    
    
     $sqlCourseName=sprintf("SELECT Subj_Name,Subj_Code
                             FROM subject
                             WHERE Class_ID='%s'
                             AND SemesterOffered='%s'",$class_ID,$semester);
                     
                  
     $name=$dbConn->query($sqlCourseName);
     //print "<table border=1>";
     $i=1; $b=2; $c=3;
     $a=0; $inc=1;
     $datac=array();
    while($CourseName=$name->fetchRow(DB_FETCHMODE_OBJECT))
    {
     
     //foreach $Coursename as $CourseName
     //{
     $CourseCode=$CourseName->Subj_Code;
     
     $cname=$CourseName->Subj_Name;     
          
    $grade=getCourseGrade($CourseCode,$accyr,$regnum,$class_ID,$semester);
     $coursegrade=$grade[0];
     $average=$grade[1];
     
     if($coursegrade=='')
     {
        $coursegrade='-';
     }
     
     
     if($average=='')
     {
        //This part should be left blank
     }
     else
     {
       $avg1=$average;
     }
         
    $datac[$a]= $inc.';';
    $datac[$b]= $CourseCode.';';
    $datac[$i]= $cname.';';
    $datac[$c]="$coursegrade\n";
    //printReport($cname,$CourseCode,$coursegrade);
    $a=$a+4;
    $i=$i+4;
    $b=$b+4;
    $c=$c+4;
    $inc++;
    
    //}
     
    }
    
   $count = count($datac);
   $datac[$count+1]=';';
   $datac[$count+2]=';';
   $datac[$count+3]='Average;';
   $datac[$count+4]=$avg1;
   
    
    $f=fopen('cha.txt','w');
    foreach($datac as $i)
    {
	$txt=fwrite($f,$i);
    }
	fclose($f);
    }
    

function Header()
{
	global $title;

	//Arial bold 15
    $this->Image('poly_logo.jpg',90,6,17);
    $this->Ln(4);
	$this->SetFont('Arial','B',13);
	//Calculate width of title and position
	$w=$this->GetStringWidth($title)+120;
	$this->SetX((210-$w)/2);
	//Colors of frame, background and text
	//$this->SetDrawColor(0,80,180);
	//$this->SetFillColor(230,230,0);
	//$this->SetTextColor(220,50,50);
	//Thickness of frame (1 mm)
    $this->Ln(13);
	$this->SetLineWidth(1);
	//Title
    
	$this->Cell($w,9,$title,0,0,'C',0);
	//Line break
	$this->Ln(10);
}

function Footer()
{
	//Position at 1.5 cm from bottom
	$this->SetY(-15);
	//Arial italic 8
	$this->SetFont('Arial','I',8);
	//Text color in gray
	$this->SetTextColor(128);
	//Page number
	$this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}

function ChapterTitle($num,$label,$student)
{
	//Arial 12
	$this->SetFont('Arial','B',10);
	//Background color
	//$this->SetFillColor(200,220,255);
	//Title
	$this->Cell(0,6,"$label",0,0,'L',0);
	//Line break
	$this->Ln(6);
    $this->Cell(4,6,"C/O Polytechnic",0,0,'L',0);
    $this->Ln(8);
    $this->SetFont('Arial','',12);
    $this->Cell(15,6,"Dear $student",0,0,'L',0);
    $this->Ln(6);
}


function Heading($head,$academic_yr)
{
	
	//Arial bold 15
	$this->SetFont('Arial','B',10);
	//Calculate width of title and position
	$w=$this->GetStringWidth($head)+6;
	$this->SetX((210-$w)/2);
	//Colors of frame, background and text
	//$this->SetDrawColor(0,80,180);
	//$this->SetFillColor(230,230,0);
	//$this->SetTextColor(220,50,50);
	//Thickness of frame (1 mm)
	$this->SetLineWidth(1);
	//Title
	$this->Cell($w,9,"$head $academic_yr",0,0,'C',0);
	//Line break
	$this->Ln(9);
}




function ChapterBody($file)
{
	//Read text file
	$f=fopen($file,'r');
	$txt=fread($f,filesize($file));
	fclose($f);
	//Times 12
	$this->SetFont('Times','',10);
	//Output justified text
	$this->MultiCell(0,5,$txt);
	//Line break
	$this->Ln();
	//Mention in italics
	$this->SetFont('','I');
	//$this->Cell(0,5,'(end of excerpt)');
}

function PrintChapter($num,$title,$student)
{
	
	$this->ChapterTitle($num,$title,$student);
	//$this->ChapterBody($file);
}

function arrenger($date,$status)
{
	$this->SetFont('Times','',10);
	$this->Cell(32,5,"At the College Assessment Meeting this semester, the Committee confirmed that your performance in Semester ");
    $this->Ln(4);
    $this->Cell(50,5,"One was unsatisfactory. You had a"); 
    $this->SetFont('Times','B',10);
    $this->Cell(22,5,"$status");
    $this->Ln(9);
    $this->SetFont('Times','',10);
    $this->Cell(0,5,"Your marks were as follows: "); 
	$this->Ln(6);
}
function salutation($status)
{
    $repeat='REPEAT';
    $proceed='PROCEED';  
    $time='9:00 am';    
     
	$this->SetFont('Times','',10);
    $this->Ln(4);
	$this->Cell(118,5,"On the basis of these marks, it is unlikely that you will pass the final examinations unless you improve your ");
    $this->Ln(4);
	$this->Cell(36,5,"performance. Please be advised that students who fail to satisfy examiners in final assessment (which include ");
    $this->Ln(4);
	$this->Cell(36,5,"continuous and first semester's assessment) may be required to take supplementary examinations or repeat the");
    $this->Ln(4);
	$this->Cell(36,5,"year or withdraw from college.");
    $this->Ln(8);
	$this->Cell(36,5,"You are earnestly being urged to see your Dean or Head of Department concerned as soon as possible, to discuss");
    $this->Ln(4);
	$this->Cell(36,5,"your work and consider ways in which you can improve your academic performance.  If I can be of further assistance,");
	$this->Ln(4);
	$this->Cell(36,5,"please do not hesitate to see me.");
	
}

function ending($principal)
{
	$this->SetFont('Times','B',10);
    $this->Ln(10);
	$this->Cell(0,5,"$principal");
    $this->Ln(6);
    $this->Cell(0,5,"Principal "); 
    $this->Ln(12);
    $this->SetFont('Times','',10);
    $this->Cell(0,5,"Cc:      Dean "); 
    $this->Ln(6);
    $this->Cell(0,5,"           Personal File"); 
    $this->Ln(8);
	$this->Cell(0,5,"CM/NTB"); 
}

//cha table

function LoadData($file)
{
    //Read file lines
    $lines=file($file);
    $data=array();
    foreach($lines as $line)
        $data[]=explode(';',chop($line));
    return $data;
}

//Simple table
function BasicTable($header,$data)
{
//$this->SetFillColor(255,0,0);
    //$this->SetTextColor(255);
    //$this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.2);
//$this->SetFont('Times','',12);
$this->SetFont('','');

    //Header
    //foreach($header as $col)
        //$this->Cell(30,7,$header[0],1);
        $this->SetFont('Times','B',10);
        $this->Cell(60,4,$header[1],1);
        $this->Cell(70,4,$header[2],1);
        $this->Cell(30,4,$header[3],1);
        $this->SetFont('Times','',10);
    $this->Ln();
    //Data
    
    foreach($data as $row)
    {
    
       $this->Cell(20,4,$row[0],1);
        $this->Cell(40,4,$row[1],1);
        if($row[2]=='Average')
        {
        $this->SetFont('Times','B',10);
        $this->Cell(70,4,$row[2],1);
        $this->SetFont('Times','',10);
        }
        else
        {
        $this->Cell(70,4,$row[2],1);
        }
        $this->Cell(30,4,$row[3],1);
            
        $this->Ln();
    }
}

function address($date,$princ)
{
global $dbConn;

$this->AddPage();
$this->SetFont('Times','B',8);
       $this->Cell(40,7,'PRINCIPAL',0);
       $this->SetFont('Times','',8);
        $this->Cell(90,7,'',0);
        $this->Cell(50,7,'Please address all correspondence to the principal',0);
        //$this->Cell(30,7,$row[3],1);
        
        $this->Ln(4);
       
       
       $sql="select CollegeName,UniversityName,AddressLine2,AddressLIne3,AddressLIne4,AddressLIne5
from college";
    $result=$dbConn->query($sql);
     
     $sqlObj=$result->fetchRow(DB_FETCHMODE_OBJECT);
    
     //$val[1]=$gradeObj->EOSAverage_College;
       
       
       
       $this->Cell(40,7,"$princ",0);
       $this->SetFont('Times','B',10);
        $this->Cell(90,7,'',0);
        $this->Cell(50,7, $sqlObj->CollegeName,0);
        //$this->Cell(30,7,$row[3],1);
        
        $this->Ln(4);
        
       $this->SetFont('Times','B',10);
        $this->Cell(130,7,'',0);
        $this->Cell(50,7,$sqlObj->AddressLine2,0);
        $this->Ln(4);
        
        
        $this->SetFont('Times','',8);
         $this->Cell(12,7,"Our Ref :",0);
         $this->SetFont('Times','B',10);
         $this->Cell(5,7,"PF",0);
       $this->SetFont('Times','B',10);
        $this->Cell(113,7,'',0);
        $this->Cell(50,7,$sqlObj->AddressLIne3,0);
        $this->Ln(4);
        
        $this->SetFont('Times','',8);
        $this->Cell(40,7,"Your Ref:",0);
        $this->SetFont('Times','B',10);
         $this->Cell(90,7,'',0);
        $this->Cell(50,7,$sqlObj->AddressLIne4,0);
        
        $this->Ln(4);
        $this->SetFont('Times','B',12);
        $this->Cell(130,7,'',0);
        $this->Cell(50,7,$sqlObj->AddressLIne5,0);
        $this->Ln(8);
        
        $this->SetFont('Times','',8);
        $this->Cell(6,7,"Date :",0);
        $this->SetFont('Times','',10);
        $this->Cell(124,7," $date",0);
        $this->SetFont('Times','',8);
        $this->Cell(50,7,'Tel: (265) 1 870 411',0);
        
         $this->Ln(4);
        $this->SetFont('Times','',8);
        $this->Cell(130,7,'',0);
        $this->Cell(50,7,'Fax: (265) 1 870 578',0);
        $this->Ln(4);
        
        $this->SetFont('Times','',8);
        $this->Cell(130,7,'',0);
        $this->Cell(50,7,'E-Mail: principal@poly.ac.mw',0);
        $this->Ln(4);
        $this->Ln(6);
}



}


$regnum='BIT/06/PE/017';
$academic_yr='2006';
$title='UNIVERSITY OF MALAWI';
$subject = 'PROGRESS REPORT SEMESTER 1 -';
$date=date(' d \of F Y');
$status='Marginal Failure Mark in one course';
$classID='BIT4';
$semester_ID=1;

$stud_details=sprintf("SELECT p.Programme_Name,s.Firstname, s.Middlenames, s.Surname, s.Title
                       from students s, students_details d, programme p, classes c
                       where s.Stud_RegNum=d.Stud_RegNum
                       and p.Programme_Code=c.Programme_Code
                       and c.Class_ID='%s' 
                       and s.Stud_RegNum='%s' 
                       and d.AcYr='%s'",$classID,$regnum,$academic_yr);
               
$details=$dbConn->query($stud_details);
$Obj=$details->fetchRow(DB_FETCHMODE_OBJECT);
$Fname=$Obj->Firstname;
$Mname=$Obj->Middlenames;
$Sname=$Obj->Surname;
$stitle=$Obj->Title;

$principal_details="select principals_qualification.qualification,principal.Fname,principal.Middlename,principal.Surname
                    from principals_qualification,principal
                    where principal.ID=principals_qualification.ID";
                    
$sql=$dbConn->query($principal_details);
while($principal_Obj=$sql->fetchRow(DB_FETCHMODE_OBJECT))
{
   $principal_fname=$principal_Obj->Fname;
   $principal_mname=$principal_Obj->Middlename;
     $principal_sname=$principal_Obj->Surname;
   
   $principal=$principal_Obj->Fname.' '.$principal_Obj->Middlename.' '.$principal_Obj->Surname.' '.$qualification[0];

   $qualification[]=$principal_Obj->qualification;
   $space=', ';
   $award.=$principal_Obj->qualification.$space;

}


$princname=$principal_fname.' '.$principal_sname;

  $princ=$princname.', '.$award;

$student_salutation= $stitle.' '.$Sname;
$student_full_name=$Fname.' '.$Mname.' '.$Sname;
$pdf=new PDF();

$pdf->SetTitle($title);

//$pdf->SetAuthor('Jules Verne');
$pdf->address($date,$princ);
$pdf->PrintChapter(1,$student_full_name,$student_salutation);

$pdf->Heading($subject,$academic_yr);
$pdf->arrenger($date,$status);


$header=array('NUMBER','COURSE ID','COURSE NAME','GRADE');
//Data loading


$pdf->getCourseName($academic_yr,$regnum,$classID,$semester_ID);
$data=$pdf->LoadData('cha.txt');

//etCourseName();
//$data=$pdf->LoadData($data);
//$pdf->SetFont('Arial','',14);
//$pdf->AddPage();
$pdf->BasicTable($header,$data);



$pdf->salutation($status);
$pdf->ending($principal);
//$pdf->PrintChapter(2,'THE PROS AND CONS','20k_c2.txt');

$pdf->Output();
?>
