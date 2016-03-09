<?php
				#print orgname
				$pdf->setFont('Arial', 'B', 20); 
				$pdf->setFillColor('rgb', 0, 0, 0);   
				$pdf->text(150, 134, strtoupper($ptname));
				$pdf->setFont('Arial', 'B', 14); 
				$pdf->text(167, 150, strtoupper($org));     
				$pdf->setFillColor('rgb', 0, 0, 0);   
				
				#University Addresses
				$pdf->setFont('Arial', '', 11.3);     
				$pdf->text(55, 50, 'Phone: '.$phone);   
				$pdf->text(55, 65, 'Fax: '.$fax);  
				$pdf->text(55, 80, 'Email: '.$email);   
				$pdf->text(400, 50, strtoupper($address));   
				$pdf->text(400, 65, strtoupper($city));   
				$pdf->text(400, 80, $website);   
				
				//calculate year of study
				$entry = intval(substr($year,0,4));
				$current = intval(substr($ryear,0,4));
				$yearofstudy=$current-$entry;
				
				if($yearofstudy==0){
					$class="1"; $sups="st";
					}elseif($yearofstudy==1){
					$class="2"; $sups="nd";
					}elseif($yearofstudy==2){
					$class="3"; $sups="th";
					}elseif($yearofstudy==3){
					$class="4"; $sups="th";
					}elseif($yearofstudy==4){
					$class="5"; $sups="th";
					}elseif($yearofstudy==5){
					$class="6"; $sups="th";
					}elseif($yearofstudy==6){
					$class="7"; $sups="th";
					}else{
					$class=""; $sups="";
				}
?>