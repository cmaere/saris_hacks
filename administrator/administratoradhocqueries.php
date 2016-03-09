<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('administratorMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Database Maintenance';
	$szSubSection = 'Query Database';
	$szTitle = 'Adhoc Queries for Administrator';
	include('administratorheader.php');

?>
          <form action="administratoradhocqueryexecute.php" method="post" name="fmSQL" id="fmSQL">
            <p>&nbsp;</p>
            <table width="439" border="0">
            <tr>
              <td>Please Enter your SQL Query (don't start or terminate with semicolon { ; } or quotes {&quot;&quot; }) </td>
              </tr>
            <tr>
              <td><textarea name="strSQL" cols="95" rows="6" id="strSQL"></textarea></td>
              </tr>
            <tr>
              <td><div align="center">
                <input type="submit" name="Submit" value="Execute Query">
              </div></td>
              </tr>
          </table>
            </p>
          </form>
          <?php

	# include the footer
	include('../footer/footer.php');
?>