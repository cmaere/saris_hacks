<?php 
include("phpgraphlib.php");
include("phpgraphlib_pie.php");
$graph=new PHPGraphLibPie(450,280);
$data=array( "Direct Sales"=>150000, "Internet Orders"=>65000,"Renewels"=>258000, "Wholesale"=>450000);
$graph->addData($data); 
$graph->setTitle("Department Sales Comparison");
$graph->setLabelTextColor("blue");
$graph->createGraph();
?>
