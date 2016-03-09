<?php 
   #POST connected to the database and verfy current session
    require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');

include("./graph/phpgraphlib.php");
$graph=new PHPGraphLib(750,500); 

//$link = mysql_connect('localhost', 'root', 'deo')
//	 or die('Could not connect: ' . mysql_error());
	 
//mysql_select_db('zalongwamuco') or die('Could not select database');

$dataArray=array();

$sql="SELECT faculty.FacultyID, COUNT(*) AS 'count' FROM student,faculty where faculty.FacultyName=student.Faculty GROUP BY student.Faculty";
$result = mysql_query($sql) or die('Query failed: ' . mysql_error());	
if($result)
{
	while($row = mysql_fetch_assoc($result))
	{	
			$salesgroup=$row["FacultyID"];
			$count=$row["count"];
                        //ADD TO ARRAY
			$dataArray[$salesgroup]=$count;
	}
}
$graph->addData($dataArray);
$graph->setTitle("STUDENTS BY DEPARTMENT");
$graph->setTitleLocation("left");
$graph->setGradient("lime", "olive");
$graph->setBarOutlineColor("black");
$graph->setLegend(true);
$graph->setLegendTitle("Students No.");
$graph->createGraph();
?>
