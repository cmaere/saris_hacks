<?php
#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('administratorMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Manage Users';
	$szSubSection = '';
	$szTitle = 'User Visit Information';
	include('administratorheader.php');
?>
<form action="adminmanageuser.php" method="get" class="style24">
            <div align="right"><span class="style67"><font face="Verdana"><b>Search</b></font></span> 
              <font color="006699" face="Verdana"><b> 
              <input type="text" name="content" size="15">
              </b></font><font color="#FFFF00" face="Verdana"><b> 
              <input type="submit" value="GO" name="go">
              </b></font>            </div>
        </form>
          	<?php
require_once('../Connections/zalongwa.php');

#populate module combo box
	$query_module = "SELECT moduleid, modulename FROM modules";
	$module = mysql_query($query_module, $zalongwa) or die(mysql_error());
	$row_module = mysql_fetch_assoc($module);
	$totalRows_module = mysql_num_rows($module);

#populate privileges combo box
	$query_privilege = "SELECT privilegeID, privilegename FROM privilege";
	$privilege = mysql_query($query_privilege, $zalongwa) or die(mysql_error());
	$row_privilege = mysql_fetch_assoc($privilege);
	$totalRows_privilege = mysql_num_rows($privilege);

#populate department combo box
	$query_dept = "SELECT DeptID, DeptName FROM department ORDER BY DeptName";
	$dept = mysql_query($query_dept, $zalongwa) or die(mysql_error());
	$row_dept = mysql_fetch_assoc($dept);
	$totalRows_dept = mysql_num_rows($dept);
#populate faculty combo box
	$query_faculty = "SELECT FacultyID, FacultyName FROM faculty ORDER BY FacultyName";
	$faculty = mysql_query($query_faculty, $zalongwa) or die(mysql_error());
	$row_faculty = mysql_fetch_assoc($faculty);
	$totalRows_faculty = mysql_num_rows($faculty);
	 
$login=$_GET['login'];
$sql = "SELECT FullName, RegNo, UserName, Module, Faculty, Dept, Position, PrivilegeID, LastLogin
FROM security WHERE UserName='$login' ORDER BY FullName";

$result = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
$query = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

$all_query = mysql_query($query);
$totalRows_query = mysql_num_rows($query);
/* Printing Results in html */
if (mysql_num_rows($query) > 0){
while($result = mysql_fetch_array($query)) {
		$login = stripslashes($result["UserName"]);
		$Name = stripslashes($result["FullName"]);
		$RegNo = stripslashes($result["RegNo"]);
		$currentprivilege = stripslashes($result["PrivilegeID"]);
		$currentmodule = stripslashes($result["Module"]);
		$currentfaculty = stripslashes($result["Faculty"]);
		$currentdept = stripslashes($result["Dept"]);
		$position = stripslashes($result["Position"]);
		$last = stripslashes($result["LastLogin"]);
	}
}else{
$key= stripslashes($key);
echo "Sorry, No Records Found <br>";
echo "That Match With Your Searck Key \"$key \" ";
}

#Update database values
if (isset($_POST['action']) && ($_POST['action'] == "Update"))
	{
		$position=addslashes($_POST['position']);
		$module=addslashes($_POST['module']);
		$priv=addslashes($_POST['priv']);
		$login=addslashes($_GET['login']);
		$dept=addslashes($_POST['dept']);
		$faculty=addslashes($_POST['faculty']);
		
		$sql="UPDATE security Set Position='$position', Module = '$module', Dept = '$dept', Faculty = '$faculty',
			PrivilegeID='$priv' WHERE UserName='$login'";
		$result = @mysql_query($sql,$zalongwa) or die("Cannot query the database.<br>" . mysql_error());
		echo '<meta http-equiv = "refresh" content ="0; 
		url = adminmanageuser.php?content='.$login.'">';
		exit;
mysql_free_result($result);
mysql_close($zalongwa);
	}
	?>
<form action="#" method="post" name="updateuser" id="updateuser">
<table width="200" border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
              <tr>
                <td width="81"><div align="right"><strong>Name:</strong></div></td>
                <td width="109"><?php echo $Name; ?> </td>
              </tr>
              <tr>
                <td><div align="right"><strong>RegNo:</strong></div></td>
                <td><?php echo $RegNo; ?></td>
              </tr>
              <tr>
                <td nowrap><div align="right"><strong>User Name:</strong></div></td>
                <td><?php echo $login; ?>
                  <input name="login" type="hidden" id="login" value="<?php echo $login; ?>"></td>
              </tr>
              <tr>
                <td><div align="right"><strong>Position:</strong></div></td>
                <td><select name="position" id="position">
				<option value="<?php echo $position; ?>" selected><?php echo $position; ?></option>
                  <option value="Accountant">Accountant</option>
				  <option value="Administrator">Administrator</option>
                  <option value="Lecturer">Lecturer</option>
                  <option value="student">Student</option>
				  <option value="Webmaster">Webmaster</option>
                </select></td>
              </tr>
              <tr>
                <td><div align="right"><strong>Module:</strong></div></td>
                <td><select name="module" id="module">
                 <option value="<?php echo $currentmodule?>" selected><?php echo $currentmodule?></option>
            		<?php
							do {  
									?>
            		<option value="<?php echo $row_module['moduleid']?>"><?php echo $row_module['modulename']?></option>
           				 <?php
								} while ($row_module = mysql_fetch_assoc($module));
  								$rows = mysql_num_rows($module);
  								if($rows > 0) {
      								mysql_data_seek($module, 0);
	  								$row_module = mysql_fetch_assoc($module);
  								}
						?>
                </select></td>
              </tr>
              <tr>
                <td><div align="right"><strong>Privilege:</strong></div></td>
                <td><select name="priv" id="priv">
				 <option value="<?php echo $cuerrentprivilege?>" selected><?php echo $currentprivilege?></option>
            		<?php
							do {  
									?>
            		<option value="<?php echo $row_privilege['privilegeID']?>"><?php echo $row_privilege['privilegename']?></option>
           				 <?php
								} while ($row_privilege = mysql_fetch_assoc($privilege));
  								$rows = mysql_num_rows($privilege);
  								if($rows > 0) {
      								mysql_data_seek($privilege, 0);
	  								$row_privilege = mysql_fetch_assoc($privilege);
  								}
						?>
                </select></td>
              </tr>
			   <tr>
                <td><div align="right"><strong>Department:</strong></div></td>
                <td><select name="dept" id="dept">
				 <option value="<?php echo $currentdept?>" selected><?php echo $currentdept?></option>
            		 <option value="0">[Faculty Exam Officer]</option>
					<?php
							do {  
									?>
            		<option value="<?php echo $row_dept['DeptID']?>"><?php echo $row_dept['DeptName']?></option>
           				 <?php
								} while ($row_dept = mysql_fetch_assoc($dept));
  								$rows = mysql_num_rows($dept);
  								if($rows > 0) {
      								mysql_data_seek($dept, 0);
	  								$row_dept = mysql_fetch_assoc($dept);
  								}
						?>
                </select></td>
              </tr>
			   <tr>
                <td><div align="right"><strong>Faculty:</strong></div></td>
                <td><select name="faculty" id="faculty">
				 <option value="<?php echo $cuerrentfaculty?>" selected><?php echo $currentfaculty?></option>
            		<?php
							do {  
									?>
            		<option value="<?php echo $row_faculty['FacultyID']?>"><?php echo $row_faculty['FacultyName']?></option>
           				 <?php
								} while ($row_faculty = mysql_fetch_assoc($faculty));
  								$rows = mysql_num_rows($faculty);
  								if($rows > 0) {
      								mysql_data_seek($faculty, 0);
	  								$row_faculty = mysql_fetch_assoc($faculty);
  								}
						?>
                </select></td>
              </tr>
			  <tr>
                <td><div align="right"><strong>Update:</strong></div></td>
                <td><input type="submit" name="action" value="Update"></td>
              </tr>
  </table>
</form>            
            	
<?php

	# include the footer
	include('../footer/footer.php');
?>