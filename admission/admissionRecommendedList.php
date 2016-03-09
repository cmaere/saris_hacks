<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
include('admissionMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Application Process';
	$szTitle = 'Recommended List';
	$szSubSection = 'Recommended List';
	//$additionalStyleSheet = './general.css';
	include("admissionheader.php");
	
?>

<?php #populate academic year combobox
	$query_AYear = "SELECT AYear FROM academicyear ORDER BY AYear DESC";
	$AYear = mysql_query($query_AYear, $zalongwa) or die(mysql_error());
	$row_AYear = mysql_fetch_assoc($AYear);
	$totalRows_AYear = mysql_num_rows($AYear);
	
	#populate degree combobox
	$query_degree = "SELECT ProgrammeCode,ProgrammeName,Faculty FROM programme ORDER BY ProgrammeName";
	$degree = mysql_query($query_degree, $zalongwa) or die(mysql_error());
	$row_degree = mysql_fetch_assoc($degree);
	$totalRows_degree = mysql_num_rows($degree);
?>
<form name="form1" method="post" action="">
  <table width="200" border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
      <td scope="col"><input name="chEYear" type="checkbox" id="chEYear" value="checkbox"></td>
      <td nowrap scope="col"><div align="left"><strong>Entry Year: </strong></div></td>
      <td scope="col"><div align="right">
        <select name="EYear" id="EYear">
		<option value="0">[Select Academic Year]</option>
            <?php
							do {  
									?>
            <option value="<?php echo $row_AYear['AYear']?>"><?php echo $row_AYear['AYear']?></option>
            <?php
								} while ($row_AYear = mysql_fetch_assoc($AYear));
  								$rows = mysql_num_rows($AYear);
  								if($rows > 0) {
      								mysql_data_seek($AYear, 0);
	  								$row_AYear = mysql_fetch_assoc($AYear);
  								}
						?>
        </select>
      </div></td>
    </tr>
    <tr>
      <td><div align="center">
        <input name="chDegree" type="checkbox" id="chDegree" value="checkbox">
      </div></td>
      <td nowrap><strong>Degree Programme: </strong></td>
      <td><div align="right">
        <select name="Degree" id="Degree">
		<option value="0">[Select Degree]</option>
                      <?php
							do {  
									?>
                      				<option value="<?php echo $row_degree['ProgrammeName']?>"><?php echo $row_degree['ProgrammeName']?></option>
                      				<?php
								} while ($row_degree = mysql_fetch_assoc($degree));
  								$rows = mysql_num_rows($degree);
  								if($rows > 0) {
      								mysql_data_seek($degree, 0);
	  								$row_degree = mysql_fetch_assoc($degree);
  								}
						?>
        </select>
      </div></td>
    </tr>
    <tr>
      <td><div align="center">
        <input name="chFactor" type="checkbox" id="chFactor" value="checkbox">
      </div></td>
      <td><strong>Female Factor: </strong></td>
      <td><div align="right">
        <input name="Factor" type="text" id="Factor" size="5">
      </div></td>
    </tr>
    <tr>
      <td colspan="3"><div align="center">
        <input type="submit" name="Submit" value="Print Results">
      </div></td>
    </tr>
  </table>
</form>

  <?php require_once('../Connections/zalongwa.php');

$sql = "SELECT FullName, Email, Position, UserName, Registered FROM security WHERE UserName = '$username'";
$query = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
echo "<table border='1'>";
echo "<tr><td> Name </td><td> Login ID </td><td> Status </td><td> E-Post </td><td> Registered </td></tr>";
while($result = mysql_fetch_array($query)) {
		$Name = stripslashes($result["FullName"]);
		$username = stripslashes($result["UserName"]);
		$position = stripslashes($result["Position"]);
		$email = stripslashes($result["Email"]);
		$registered = stripslashes($result["Registered"]);
			echo "<tr><td>$Name</td>";
			echo "<td>$username</td>";
			echo "<td>$position</td>";
			echo "<td>$email</td>";
			echo "<td>$registered</td></tr>";
		}
echo "</table>";

	# include the footer
	include("../footer/footer.php");
?>
</p>

<p>&nbsp; </p>
