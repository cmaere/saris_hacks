<?php /*
	This is the header to be included for all files.
	Variables to be set before including this file are as follows:
	
	szSection - the name of the section.
	szSubSection - the name of the subsection.
*/ 
	
	# this script will use the following globals
	global $szSection, $szSubSection, $szSubSubSection,$szSubSubSectionTitle,$szSubSectionTitle, $szTitle, $additionalStyleSheet, $arrStructure, $szRootURL, $blnHideNav, $arrVariations;

	//if (!isset($blnHideNav)){$blnHideNav = false;}
	if (isset($_GET['hidenav'])){$blnHideNav=true;}else{$blnHideNav = false;}
	
	// change language if necessary...
	if (isset($_GET['chooselang']) && isset($arrVariations[$_GET['chooselang']])){
		if ($_GET['chooselang'] ==1){
			$_SESSION['arrVariationPreference'] = array (
				1 => 1,
				2 => 2
			);
		}elseif ($_GET['chooselang'] ==2){
			$_SESSION['arrVariationPreference'] = array (
				1 => 2,
				2 => 1
			);
		}
	}
	
	$intDisplayLanguage = $_SESSION['arrVariationPreference'][1];
	
	// Need to figure out what section we are currently in, to be able to display the relevant colours.
	for ( $i=1; $i<= count( $arrStructure ); $i++ ){	
		# is this the current section?
		if ( $arrStructure[$i]['name1'] == $szSection ) $intCurrentSectionID = $i;
	}
	#get organisation name
	
	$qname = 'SELECT Name, Address FROM organisation';
	$dbname = mysql_query($qname);
	$name_row = mysql_fetch_assoc($dbname);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>SARIS -> <?php echo  $szSection ?> -> <?php echo  $szSubSection ?></title>
	<!-- include style sheet -->
	<link rel="stylesheet" type="text/css" href="../css/header.css">
	
<!--
	<meta http-equiv="pragma" content="no-cache">
	<META HTTP-EQUIV="Expires" CONTENT="-1">
	-->
    <style type="text/css">
<!--
.style5 {color: #003300}
.style6 {color: #660000}
-->
    </style>
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<table border="0" cellspacing="0" cellpadding='0' width='100%' background="../themes/images/loginTopHeaderBg.gif" style="border:2px solid rgb(119,119,119)">
<tr>
	<td align=left valign="middle"><span class="style5"><?php include '../includes/pagetitle.php'?></span></td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width=100% height=100% style="border:2px solid rgb(119,119,119)">
<tr>
</tr>
<tr><td height=30 colspan=2 bgcolor="#CCCCCC" class="style6">
		<table border="0" cellspacing="0" cellpadding="0" width=100%>
		<tr>
		    <td class="smalltext">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $name ?></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
    <td rowspan=2 width=180 valign=top align=center style="padding:5px" bgcolor="#CCCCCC"><span class="white"></span>
      <table cellspacing="0" cellpadding="0" border="0">
		  <tr>
		      <td align="left" style="padding-left: 1px;" valign=top>
			
			  
			<div class="EnclosureBox">
			  <?php # loop around and write out the Sections
				for ( $i=1; $i<= count( $arrStructure ); $i++ ){	
					
					# is this the current section?
					if ( $arrStructure[$i]['name1'] == $szSection ) $intSectionID = $i;
					
					# only output parent sections
					
					if ( $arrStructure[$i]['parentID'] == 0){
					
						# Section Enclosure
						echo '<div class="SectionEnclosureBox">';
						
						# is this the current section
						if ( $i == $intSectionID ) { 
							# Section On
							echo '<div class="SectionOnBox"><a class="NavigationLink" href="'.$arrStructure[$i]['url'].'">'.$arrStructure[$i]['name'.$intDisplayLanguage].'</a></div>';
							
							# Subsections
							
							# loop around and write out the sub-sections
							$arrSubStructure = $arrStructure[$intSectionID]['subsections'];
							for ($j=1; $j<= count($arrSubStructure); $j++){	
							
								# Sub Section Enclosure
								echo '<div class="SubSectionEnclosureBox">';
								
								# is this the current subsection?
								if ( $arrSubStructure[$j]['name1'] == $szSubSection ) $intSubSectionID = $j;
								
								# is this a section page?
								
								# is this the current subsection
								if ( $j == $intSubSectionID ) { 
									# SubSection On
									echo '<div class="SubSectionOnBox"><a class="NavigationLink" href="'.$arrSubStructure[$j]['url'].'">'.$arrSubStructure[$j]['name'.$intDisplayLanguage].'</a></div>';
									
									# loop around and write out the sub-sections
									$arrSubSubStructure = $arrStructure[$intSectionID]['subsections'][$intSubSectionID]['subsections'];
									
									for ($k=1; $k<= count($arrSubSubStructure); $k++){	
		
										# is this the current subsubsection?
										if ( $arrSubSubStructure[$k]['name1'] == $szSubSubSection ) $intSubSubSectionID = $k;
										
										# is this the current subsubsection
										if ( $k == $intSubSubSectionID ) { 
											// ON
											echo '<div class="SubSubSectionOnBox">'.$arrSubSubStructure[$k]['name'.$intDisplayLanguage].'</div>';
										}else{
											echo '<div class="SubSubSectionOffBox"><a class="NavigationLink" href="'.$arrSubSubStructure[$k]['url'].'">'.$arrSubSubStructure[$k]['name'.$intDisplayLanguage].'</a></div>';
										}
										
									}
								}else{
									# subsection is off
									echo '<div class="SubSectionOffBox"><a class="NavigationLink" href="'.$arrSubStructure[$j]['url'].'">'.$arrSubStructure[$j]['name'.$intDisplayLanguage].'</a></div>';
								}
								
								# End of Sub Section Enclosure
								echo '</div>';
								
							}
						}else{
							# section is off
							echo '<div class="SectionOffBox"><a class="NavigationLink" href="'.$arrStructure[$i]['url'].'">'.$arrStructure[$i]['name'.$intDisplayLanguage].'</a></div>';
							//echo '<tr><td style="padding-left: 10px;" height="20" align="left">&nbsp;<a class="navoff"href="'.$arrStructure[$i]['url'].'">'.$arrStructure[$i]['name'.$intDisplayLanguage].'</a>&nbsp;</td></tr>';
						}
						
						# End of Section Enclosure
						echo '</div>';
					}
				}
				
			?>
			  </div>
			  <div>
			&nbsp;
			  </div>
			  <?php if (isset($arrSpecialStructure)){?>
				  <div class="EnclosureBox">
				  <?php # loop around and write out the Sections
					for ( $i=1; $i<= count( $arrSpecialStructure ); $i++ ){	
						
						# only output parent sections
						if ( $arrSpecialStructure[$i]['parentID'] == 0){
						
							# Section Enclosure
							echo '<div class="SectionEnclosureBox">';
							
							# section is off
							echo '<div class="SpecialSectionBox"><a class="NavigationLink" href="'.$arrSpecialStructure[$i]['url'].'">'.$arrSpecialStructure[$i]['name'.$intDisplayLanguage].'</a></div>';

							
							# End of Section Enclosure
							echo '</div>';
						}
					}
					
				?>
				  </div>
				  <div>
				&nbsp;
				  </div>
			  <?php } ?>
			  </td>
		  </tr>
	  </table></td>
      <td valign=top style="font-size:12pt;"><div style="width:550;padding:15px 10px 15px 20px">
		  <?php if (strlen($szTitle)){
				echo '<div class="header1">'.$szTitle.'</div><hr noshade style="color:#B9C2C6" size=1>';
			}elseif (strlen($szSubSubSectionTitle)){
				echo '<div class="header1">'.$szSubSubSectionTitle.'</div><hr noshade style="color:#B9C2C6" size=1>';
			}elseif (strlen($szSubSectionTitle)){
				echo '<div class="header1">'.$szSubSectionTitle.'</div><hr noshade style="color:#B9C2C6" size=1>';
			}elseif (strlen($szSectionTitle)){
				echo '<div class="header1">'.$szSectionTitle.'</div><hr noshade style="color:#B9C2C6" size=1>';
			}
			
			 $fees = $_GET['fees'];
			$minimumfee = $_GET['minimumfee'];
			$balance = $_GET['balance'];
			$semester = $_GET['semester'];
			$sponsor = $_GET['sponsor'];
			if($semester == "Semester II")
			{
				if($fees < $minimumfee && $sponsor == 1 && $semester == "Semester II") 
			 	{
				   
					echo "<br><font color='#FF0000'><b>YOU CAN NOT REGISTER FOR SECOND SEMESTER, YOU HAVE TO SETTLE YOU BALANCE AT ACCOUNTS OFFICE. YOUR BALANCE IS </font><font color='black'>  K $balance </font><font color='#FF0000'><br> YOU ARE REQUIRED TO PAY UP TO 3/4 OF YOUR FEES TO REGISTER</b> </font><br><br>";   
				   
			 	}
			}
			
			
			
			?>