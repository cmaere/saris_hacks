<?php 
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('administratorMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Database Maintanance';
	$szSubSection = 'Check Connections';
	$szTitle = 'Check Server Connections';
	include('administratorheader.php');
?> 
<?php
//Author: Juma Lungo
//Date: Septermber 18, 2004
//Rationale: We are in a crisis where ZALONGWA Database System is not Working
//To intrude the MySQL Server in Dar es Salaam so that ZALONGWA Database System will run appropriately

//This is my mysql monitor where i can run different commands to set server variables
			$setCacheSize = "SET GLOBAL thread_cache_size=40";
			$setWaitTimeOut = "SET GLOBAL wait_timeout=60";
			$setMaxConnections = "SET GLOBAL max_connections=180";
			$setMaxUserConnections = "SET GLOBAL max_user_connections=150";
			//To execute the above commands, take out the comments(un-comments the respective query below
					//$query = @mysql_query($setCacheSize); 
					//$query = @mysql_query($setWaitTimeOut);
					//$query = @mysql_query($setMaxConnections);
					//$query = @mysql_query($setMaxUserConnections);
//End of my MySQL Monitor
			echo "<hr>";
		 @printf ("1.0 MySQL host info: %s\n", mysql_get_host_info());
		 	echo "<hr>";
		 @printf ("2.0 MySQL server version: %s\n", mysql_get_server_info());
			 echo "<hr>";
		 @printf ("3.0 MySQL client info: %s\n", mysql_get_client_info());
		 		 //$zalongwa = mysql_connect('localhost', 'root', '');
			//$sql = "show full processlist";
			echo "<hr>";
			echo "4.0 List of Databases:  ";
				$db_list = mysql_list_dbs($zalongwa);
				while ($row = mysql_fetch_object($db_list)) {
    				echo $row->Database . ",\n";
					}
			echo "<hr>";
			//$result = mysql_query($sql);
			$sql = "select @@global.thread_cache_size";
			$query = @mysql_query($sql); 
			while($result = mysql_fetch_array($query)) {
			$id = stripslashes($result["@@global.thread_cache_size"]);
			?>  <table border="0">  
				<tr>
					<td>5.0 Global.thread_cache_size: = </td>
					<td><?php echo $id; }?></td>
			 	</tr>
			</table>
			<?php
			echo "<hr>";
			$sql = "SHOW full processlist";
			$query = @mysql_query($sql); 
			//echo "If query was executed it will print 1, see the results: ".$query;
			?> <table border="1"> 
			6.0 Current Running Processes:
			 <tr>
					 <td>Id</td>
					<td>User</td>
					<td>Host</td>
					<td>Database</td>
					<td>Command</td>
					<td>Time</td>
					<td nowrap>SQL Executed</td>
					<td nowrap>SQL State</td>
			  </tr>
			 <?php
			while($result = mysql_fetch_array($query)) {
				$id = stripslashes($result["Id"]);
				$user = stripslashes($result["User"]);
				$host = stripslashes($result["Host"]);
				$db = stripslashes($result["db"]);
				$cmd = stripslashes($result["Command"]);
				$time = stripslashes($result["Time"]);
				$info = stripslashes($result["Info"]);
				$state = stripslashes($result["State"]);
			
			//@$result = mysql_list_processes($zalongwa);
			?> 
	<tr>
  	<td><?php echo $id; ?> </td>
     <td><?php echo $user; ?> </td>
     <td><?php echo $host; ?> </td>
     <td><?php echo $db; ?></td>
      <td><?php echo $cmd; ?></td>
     <td><?php echo $time; ?></td>
	 <td><?php echo $info; ?></td>
	 <td><?php echo $state; ?></td>
	</tr> <?php } ?>
</table>
<hr>

<?php 
mysql_free_result ($query);
mysql_close($zalongwa);
?>          
          <?php

	# include the footer
	include('../footer/footer.php');
?>