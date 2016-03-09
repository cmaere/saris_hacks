<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    # initialise globals
	include('../academic/lecturerMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Examination';
	$szSubSection = 'Excel Import';
	$szTitle = 'Import MS Excel Database Data';
	include('../academic/lecturerheader.php');

#Block this function
	//echo 'Please be informed that <BR> Exams related data entry and editing functions are currently disabled <br> Contact the Director, DUS office for more clarifications <br> -- Kind regards, Zalongwa Supporting Team';
	//include('../footer/footer.php');
	//exit;
?>
1.0 To Download Import Manual <a href="temp/ExamsImportTemplateManual.doc">click here</a>  <br> 
2.0 To Download Excel Template <a href="temp/ExamsImportTemplate.xls">click here</a>
<hr>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html> 
<style type="text/css">
<!--
.style1 {color: #990000}
-->
</style>
<head> 
<title>zalongwa database maintenance</title> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
</head> 

<body> 
     <form enctype="multipart/form-data" action="import.php" method="post">
                   <table width="512" border="1" cellpadding="1" cellspacing="0" >
                    <tr>
                      
                        <div align="right">
          <input type="hidden" name="MAX_FILE_SIZE" value="55646039">
        Choose File:</div>
                      <td colspan="2" nowrap><input name="userfile" type="file" size="40">
                    </tr>
					<?php if ($privilege==2){ ?>
                    <tr>
                      <th width="156" nowrap scope="row">
                        <div align="right">Existing Data:</div></th>
					<td><input name="radiobutton" type="radio" value="1">
					  Yes Overwrite </td>
					<td><input name="radiobutton" type="radio" value="0">
					Never Overwrite </td>					  
                    </tr>
					<?php } ?>
					<tr>
                      <th scope="row">&nbsp;</th>
                      <td width="153"><input type="submit" value="Send File"></td>
                      <td width="232">
                        <input type="reset" name="Reset" value="Reset"></td>
                    </tr>
       </table>
                  <p>&nbsp;              </p>
                  <p>&nbsp;              </p>
</form>
			<?php
				# include the footer
	include('../footer/footer.php');
	?></body> 
</html>
