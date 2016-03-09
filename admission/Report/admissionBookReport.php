<?php
require_once('html2pdf.class.php');
	// récupération de l'html
 	ob_start();
 	include('res/admissionBookReport.php');
	$content = ob_get_clean();

	// initialisation de HTML2PDF
	$html2pdf = new HTML2PDF('P','A4','en', array(0, 0, 0, 0));
	
	// affichage de la page en entier
	$html2pdf->pdf->SetDisplayMode('fullpage');
	
	// conversion
	$html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
	
	// ajout de l'index (obligatoirement en fin de document)
	$html2pdf->setNewPage();
	$html2pdf->CreateIndex('Index', 25, 12);
	
	// envoie du PDF
	$html2pdf->Output('admissionBookReport.pdf');
