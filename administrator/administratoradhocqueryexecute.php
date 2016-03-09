<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('administratorMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Database Maintanance';
	$szSubSection = 'Query Database';
	$szTitle = 'Adhoc Queries for Administrator';
	include('administratorheader.php');

?> 

          
        <?php
//Author: Juma Lungo
//Date: Septermber 18, 2004
//Rationale: We are in a crisis where ZALONGWA Database System is not Working
//To intrude the MySQL Server in Dar es Salaam so that ZALONGWA Database System will run appropriately
require_once('../Connections/zalongwa.php'); 

//This is my mysql monitor where i can run different commands to set server variables
			    	//Receive SQL STRINGVariable
					$sql = $_POST['strSQL'];
					//put it in agreed syntax
					$strSQL = stripslashes("$sql");
					echo "Query Executed: $strSQL\n <hr>";
					// Execute Query
					$result = @mysql_query($strSQL); 
					if(!$result) {
							echo "Execution Status: \n --Samahani Sana, Query Not Executed!<hr>";
							exit;
					}else{
							echo "Execution Status: \n --Hongera Sana, Query Executed Successful! <hr>";
					}
		//End of my MySQL Monitor          
 
 /* Printing results in HTML */
 echo "Query Execution Results: <hr>";
    print "<table border=\"1\">\n";
    while (@$line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        print "\t<tr>\n";
        foreach ($line as $col_value) {
            print "\t\t<td>$col_value</td>\n";
        }
        print "\t</tr>\n";
    }
    print "</table>\n";

    /* Free resultset */
    @mysql_free_result($result);

    /* Closing connection */
  
@mysql_close($zalongwa);
						
?>

<?php

	# include the footer
	include('../footer/footer.php');
?>