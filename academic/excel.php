<?php
require_once('../Connections/zalongwa.php');
$program = $_GET['program'];
		$year = $_GET['year'];
	//die($program.$year);
if($program == 1001)
		{
			$program_name = 'Bachelor of Science in Nursing and Midwifery';	
			
		}
		
		
		
		$sqlayear = "SELECT AYear, Semister_status FROM academicyear WHERE Status = 1";
$resultayear=mysql_query($sqlayear);
while ($line = mysql_fetch_array($resultayear, MYSQL_ASSOC)) 
					{
						$ayear = $line["AYear"];
						$semester = $line["Semister_status"];
					}
					$i = 1;
					if($year == $ayear)
					{
					  $prog_year = "Year ". $i;	
						
					}
					else if($year == ($ayear - 1))
					{
						$prog_year = "Year ". $i+1;	
					}
					else if($year == ($ayear - 2))
					{
						$prog_year = "Year ". $i+2;	
					}
					else if($year == ($ayear - 3))
					{
						$prog_year = "Year ". $i+3;	
					}
		
		$sql = "SELECT  n.`RegNo`,s.Name,n.`ExamNumber` FROM Exam_Numbers n, student s WHERE s.RegNo = n.RegNo and n.`AYear` = '$ayear' and `Semester` = '$semester' and `YearEntry` = '$year' order by n.Regno asc";
		$resultb=mysql_query($sql);
		//die($sql);
			$checkexamnumbers = mysql_num_rows($resultb);
		if($checkexamnumbers <> 0)
		{
			// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');

// It will be called file.xls
header('Content-Disposition: attachment; filename="Exam Numbers for '.$program_name.' '.$prog_year.'.xls"');
			
			
			/** Error reporting */
			error_reporting(E_ALL);
			
			/** Include path **/
			//ini_set('include_path', ini_get('include_path').';../Classes/');
			
			/** PHPExcel */
			include '../Classes/PHPExcel.php';
			
			/** PHPExcel_Writer_Excel2007 */
			include '../Classes/PHPExcel/Writer/Excel2007.php';
			
			// Create new PHPExcel object
			//echo date('H:i:s') . " Create new PHPExcel object\n <br>";
			$objPHPExcel = new PHPExcel();
			
			// Set properties
			//echo date('H:i:s') . " Set properties\n";
			$objPHPExcel->getProperties()->setCreator("KCN ICT Department");
			$objPHPExcel->getProperties()->setLastModifiedBy("kcn Admin");
			$objPHPExcel->getProperties()->setTitle("KCN ".$program_name." Exam Numbers");
			$objPHPExcel->getProperties()->setSubject("KCN ".$program_name." Exam Numbers");
			$objPHPExcel->getProperties()->setDescription("KCN ".$program_name." Exam Numbers");
			
			
			// Add some data
			//echo date('H:i:s') . " Add some data\n <br>";
			$objPHPExcel->setActiveSheetIndex(0);
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'KAMUZU COLLEGE OF NURSING');
			$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'EXAMINATION NUMBERS FOR '.$program_name.' '.$prog_year);
			$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'Name');
			$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'RegNo');
			$objPHPExcel->getActiveSheet()->SetCellValue('D3', 'Exam Number');
			//echo date('H:i:s') . " Add some data\n 3<br>";
			$rowex = 4;
			
			
			while ($line = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
					{
						$name = $line["Name"];
						$regno = $line["RegNo"];
						$examnumber = $line["ExamNumber"];
							//echo date('H:i:s') . " Add some data\n 4 <br>".$name;
						$objPHPExcel->getActiveSheet()->SetCellValue('B'. ($rowex + 1), $name);
						//echo date('H:i:s') . " Add some data\n 5 <br>";
						$objPHPExcel->getActiveSheet()->SetCellValue('C'. ($rowex + 1), $regno);
						$objPHPExcel->getActiveSheet()->SetCellValue('D'. ($rowex + 1), $examnumber);
						$rowex ++;
					}
			
			
			// Rename sheet
			//echo date('H:i:s') . " Rename sheet\n <br>";
			$objPHPExcel->getActiveSheet()->setTitle('Simple');
			
					
			// Save Excel 2007 file
			//echo date('H:i:s') . " Write to Excel2007 format";
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			
			
			$objWriter->save('php://output');
			
			
		/*
			require_once "Spreadsheet/Excel/Writer.php"; 
			$workbook = new Spreadsheet_Excel_Writer();
			$workbook->send('test.xls'); 
			$worksheet =& $workbook->addWorksheet("My Worksheet"); 
			
			
			$fmt_title =& $workbook->addFormat();
			$fmt_title->setBold();
			$fmt_title->setSize(12);
			$fmt_title->setMerge();
			$worksheet->write(0,0,"KAMUZU COLLEGE OF NURSING",$fmt_title);
			$worksheet->write(2,0,"EXAMINATION NUMBERS FOR ".$program_name.' '.$prog_year,$fmt_title);
			$worksheet->write(4,0,"Name ",$fmt_title);
			$worksheet->write(4,1,"REGNO ",$fmt_title);
			$worksheet->write(4,2,"Exam Number ",$fmt_title);
			
			$fmt_title =& $workbook->addFormat();
			$fmt_title->setSize(12);
			$fmt_title->setMerge();
			$rowex = 5;
			$col = 0;
			while ($line = mysql_fetch_array($resultb, MYSQL_ASSOC)) 
					{
						$name = $line["Name"];
						$regno = $line["RegNo"];
						$examnumber = $line["ExamNumber"];
			// Write using the Title format
			$worksheet->write($rowex,$col,$name,$fmt_title);
			$worksheet->write($rowex,$col+1,$regno,$fmt_title);
			$worksheet->write($rowex,$col+2,$examnumber,$fmt_title);
			$rowex ++;
			
			
					}
			
			
			
			$workbook->close();
			*/
		
		}
		else
		{
			echo "No exam numbers";	
			
		}

			
?>