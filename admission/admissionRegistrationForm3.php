<?php
	#get connected to the database and verfy current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');
	
	# initialise globals
	include('admissionMenu.php');
	
	# include the header
	global $szSection, $szSubSection;
	$szSection = 'Admission Process';
	$szSubSection = 'Registration Form';
	$szTitle = 'Member Registration Form';
	include('admissionheader.php');
	
	#populate sponsor combobox
	$query_sponsor = "SELECT Name FROM sponsors ORDER BY Name ASC";
	$sponsor = mysql_query($query_sponsor, $zalongwa);
	$row_sponsor = mysql_fetch_assoc($sponsor);
	$totalRows_sponsor = mysql_num_rows($sponsor);	
	
	#populate academic year combobox
	$query_AYear = "SELECT AYear FROM academicyear ORDER BY AYear DESC";
	$AYear = mysql_query($query_AYear, $zalongwa) or die(mysql_error());
	$row_AYear = mysql_fetch_assoc($AYear);
	$totalRows_AYear = mysql_num_rows($AYear);
	
	#populate campus combobox
	$query_campus = "SELECT CampusID, Campus FROM campus ORDER BY Campus ASC";
	$campus = mysql_query($query_campus, $zalongwa) or die(mysql_error());
	$row_campus = mysql_fetch_assoc($campus);
	$totalRows_campus = mysql_num_rows($campus);
	
	#populate MannerofEntry combobox
	$query_MannerofEntry = "SELECT ID, MannerofEntry FROM mannerofentry ORDER BY MannerofEntry ASC";
	$MannerofEntry = mysql_query($query_MannerofEntry, $zalongwa) or die(mysql_error());
	$row_MannerofEntry = mysql_fetch_assoc($MannerofEntry);
	$totalRows_MannerofEntry = mysql_num_rows($MannerofEntry);
	
	#populate Country combobox
	$query_country = "SELECT szCountry FROM country ORDER BY szCountry DESC";
	$country = mysql_query($query_country, $zalongwa) or die(mysql_error());
	$row_country = mysql_fetch_assoc($country);
	$totalRows_country = mysql_num_rows($country);
	
	#populate faculty combobox
	$query_faculty = "SELECT FacultyName FROM faculty ORDER BY FacultyName DESC";
	$faculty = mysql_query($query_faculty, $zalongwa) or die(mysql_error());
	$row_faculty = mysql_fetch_assoc($faculty);
	$totalRows_faculty = mysql_num_rows($faculty);
	
	#populate degree combobox
	$query_degree = "SELECT ProgrammeCode,ProgrammeName,Faculty FROM programme ORDER BY ProgrammeName";
	$degree = mysql_query($query_degree, $zalongwa) or die(mysql_error());
	$row_degree = mysql_fetch_assoc($degree);
	$totalRows_degree = mysql_num_rows($degree);
	
	#populate combination combobox
		#populate degree combobox
	$query_combi = "SELECT SubjectID,SubjectName FROM subjectcombination ORDER BY SubjectName";
	$combi = mysql_query($query_combi, $zalongwa) or die(mysql_error());
	$row_combi = mysql_fetch_assoc($combi);
	$totalRows_combi = mysql_num_rows($combi);
	
	#populate religion combobox
	$query_religion = "SELECT ReligionID,Religion FROM religion ORDER BY Religion DESC";
	$religion = mysql_query($query_religion, $zalongwa) or die(mysql_error());
	$row_religion = mysql_fetch_assoc($religion);
	$totalRows_religion = mysql_num_rows($religion);
	
	#populate disability combobox
	$query_disability = "SELECT DisabilityID,Disability FROM disability ORDER BY Disability DESC";
	$disability = mysql_query($query_disability, $zalongwa) or die(mysql_error());
	$row_disability = mysql_fetch_assoc($disability);
	$totalRows_disability = mysql_num_rows($disability);
	
	#populate studentStatus combobox
	$query_studentStatus = "SELECT StatusID,Status FROM studentstatus ORDER BY Status DESC";
	$studentStatus = mysql_query($query_studentStatus, $zalongwa) or die(mysql_error());
	$row_studentStatus = mysql_fetch_assoc($studentStatus);
	$totalRows_studentStatus = mysql_num_rows($studentStatus);
	
	#Process Registration Form
	if (isset($_POST['actionadd']) && ($_POST['actionadd'] == "AddNew"))
	{
		$regno = $_POST['regno'];
		$degree = $_POST['degree'];
		$faculty = $_POST['faculty'];
		$ayear = $_POST['ayear'];
		$combi = $_POST['combi'];
		$campus = $_POST['campus'];
		$manner = $_POST['manner'];
		$byear = addslashes($_POST['txtYear']);
		$bmon = addslashes($_POST['txtMonth']);
		$bday = addslashes($_POST['txtDay']);
		$dtDOB = $bday . " - " . $bmon . " - " . $byear;
		$surname = addslashes($_POST['surname']);
		$firstname = addslashes($_POST['firstname']);
		$middlename = addslashes($_POST['middlename']);
		$dtDOB = $_POST['dtDOB'];
		$age = $_POST['age'];
		$sex = $_POST['sex'];
        $sponsor = $_POST['txtSponsor'];
		$country = $_POST['country'];
		$district = addslashes($_POST['district']);
		$region = addslashes($_POST['region']);
		$maritalstatus = $_POST['maritalstatus'];
		$address = $_POST['address'];
		$religion = $_POST['religion'];
		$denomination = $_POST['denomination'];
		$postaladdress = addslashes($_POST['postaladdress']);
		$residenceaddress = addslashes($_POST['residenceaddress']);
		$disability = $_POST['disability'];
		$status = $_POST['status'];
		$gyear = $_POST['dtDate'];
		$name = $surname.", ".$firstname." ".$middlename;
	
		#check if RegNo Exist
		if ($regno == ''){
		echo 'ERROR: - <br> RegNo Required, Form cannot be processed';
		exit;
		}

			#check if RegNo Exist
			$qRegNo = "SELECT RegNo FROM student WHERE RegNo = '$regno'";
			$dbRegNo = mysql_query($qRegNo);
			$total = mysql_num_rows($dbRegNo);
			$row_result = mysql_fetch_assoc($dbRegNo);
				$oldRegNo = $row_result["RegNo"];
				if ($total>0) {
					echo "ZALONGWA Database System Imegundua Kuwa,<br> Registration Number Hii ". $regno. " Ina Mtu Tayari";
					echo "<br> Go Back and Insert Newone!<hr><br>";
					}else{		 
				
								#insert record
								$sql="INSERT INTO student(Name,RegNo,Sex,DBirth,MannerofEntry,MaritalStatus,Campus,ProgrammeofStudy,Faculty,Department,
								Sponsor,GradYear,EntryYear,Status,YearofStudy,Address,Nationality,Region,District,Country,ParentOccupation,Received,user,Denomination, Religion,Disability) 
								VALUES('$name','$regno','$sex','$dtDOB',' ','$maritalstatus',' ','$degree','$faculty',' ','$sponsor ','$gyear','$ayear','$status',' ','$address','$country','$region',
								'$district','$country','',now(),'$username','$denomination', '$religion','$disability')";   
								$dbstudent = mysql_query($sql) or die("We Mswahili Sikiliza: - " . mysql_error());
					}
	}
	
if (isset($_POST['actionupdate']) && ($_POST['actionupdate'] == "Update"))
	{
		$OldRegNo = $_GET['RegNo'];
		$regno = $_POST['regno'];
		$faculty = $_POST['faculty'];
		$degree = $_POST['degree'];
		$acyear = $_POST['ayear'];
		$combi = $_POST['combi'];
		$campus = $_POST['campus'];
		$manner = $_POST['manner'];
		$byear = addslashes($_POST['txtYear']);
		$bmon = addslashes($_POST['txtMonth']);
		$bday = addslashes($_POST['txtDay']);
		$surname = addslashes($_POST['surname']);
		$firstname = addslashes($_POST['firstname']);
		$middlename = addslashes($_POST['middlename']);
		$age = $_POST['age'];
		$sex = $_POST['sex'];
		$sponsor = $_POST['txtSponsor'];
		$country = $_POST['country'];
		$district = $_POST['district'];
		$region = $_POST['region'];
		$maritalstatus = $_POST['maritalstatus'];
		$address = addslashes($_POST['address']);
		$religion = $_POST['religion'];
		$denomination = addslashes($_POST['denomination']);
		$postaladdress = addslashes($_POST['postaladdress']);
		$residenceaddress = addslashes($_POST['residenceaddress']);
		$disability = $_POST['disability'];
		$status = $_POST['status'];
		$gyear = $_POST['dtDate'];
		$dbirth= $_POST['dbirth'];
		if($byear==''){
				$dtDOB = $dbirth;
				}else{
				$dtDOB = $bday . " - " . $bmon . " - " . $byear;
			}

		$name = $surname;

		#check if RegNo Exist
			$qRegNo = "SELECT RegNo FROM student WHERE RegNo = '$regno'";
			$dbRegNo = mysql_query($qRegNo);
			$row_result = mysql_fetch_assoc($dbRegNo);
			$oldRegNo = $row_result["RegNo"];
			
			if ($oldRegNo == '$regno') {
						echo "ZALONGWA Database System Imegundua Kuwa,<br> Registration Number Hii Ina Mtu Tayari";
						echo "<br> Tafadhari Chagua Nyingine!<hr><br>";
				
				}else{
						$sqlUpdate="UPDATE student SET Name = '$name', Sex = '$sex',DBirth = '$dtDOB',MannerofEntry = '$manner',MaritalStatus = '$maritalstatus',Campus = '$campus',
						ProgrammeofStudy = '$degree', Subject = '$combi', Faculty = '$faculty',Department = ' ', Sponsor = '$sponsor', GradYear = '$gyear', EntryYear = '$acyear', Status = '$status',
						YearofStudy = ' ',Address = '$address',Nationality = '$country',Region = '$region',District = '$district',Country = '$country',
						ParentOccupation = '',Received =now() ,user = '$username',Religion = '$religion',Denomination = '$denomination', Disability ='$disability' 
						WHERE RegNo = '$regno'";
				}
				$result = mysql_query($sqlUpdate) or die(mysql_error());
		
		//Display the update record
		$sql = "SELECT 
						student.Id,
						student.Name, 
						student.RegNo, 
						student.Sex, 
						student.ProgrammeofStudy, 
						Subject,
						student.Faculty, 
						student.EntryYear, 
						student.Sponsor
				FROM student
						WHERE (student.RegNo='$regno')";
						
	$result = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
	$query = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

	$all_query = mysql_query($query);
	$totalRows_query = mysql_num_rows($query);
	/* Printing Results in html */
	if (mysql_num_rows($query) > 0){
		echo "Nominal Roll Report For the Year: $year";
		echo "<p>Total Records: $totalRows_query </p>";
		echo "<table border='1'>";
		echo "<tr><td> S/No </td><td> Name </td><td> RegNo </td><td> Sex </td><td> Degree </td><td> Combination </td><td> Faculty </td><td> Sponsor </td><td>Edit</td><td>Delete</td></tr>";
		$i=1;
		while($result = mysql_fetch_array($query)) {
				$id = stripslashes($result["Id"]);
				$Name = stripslashes($result["Name"]);
				$RegNo = stripslashes($result["RegNo"]);
				$sex = stripslashes($result["Sex"]);
				$degree = stripslashes($result["ProgrammeofStudy"]);
				$subject = stripslashes($result["Subject"]);
				$faculty = stripslashes($result["Faculty"]);
				$sponsor = stripslashes($result["Sponsor"]);
				//get degree name
				 $qdegree = "SELECT ProgrammeName from programme WHERE ProgrammeCode = '$degree'";
			 $dbdegree = mysql_query($qdegree, $zalongwa);
			  $row_dbdegree = mysql_fetch_assoc($dbdegree);
				$degreename =$row_dbdegree['ProgrammeName'];
				
					echo "<tr><td>$i</td>";
					echo "<td>$Name</td>";
					echo "<td>$RegNo</td>";
					echo "<td>$sex</td>";
					echo "<td>$degreename</td>";
					echo "<td>$subject</td>";
					echo "<td>$faculty</td>";
					echo "<td>$sponsor</td>";
					echo "<td><a href=\"admissionRegistrationForm.php?id=$id&RegNo=$RegNo\">Edit</a>;</td>";
					echo "<td><a href=\"admissionRegistrationdelete.php?id=$id&RegNo=$RegNo\">Delete</a>;</td>
				
				</tr>";
				$i=$i+1;
				}
			echo "</table>";
			}else{
					echo "Sorry, No Records Found <br>";
				}
		exit;
	}
	
if (isset($_GET['id']) && ($_GET['id'] <> "")) {
#get post variables
$id = $_GET['id'];
echo $regno;
	$sql = "SELECT *
	       FROM student WHERE Id ='$id'"; 
    $update = mysql_query($sql, $zalongwa) or die(mysql_error());
	$row_update = mysql_fetch_assoc($update);
	$totalRows_update = mysql_num_rows($update);
		   ?>
<style type="text/css">
<!--
.style2 {color: #FF0000}
-->
</style>


		<form name="registration" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
	  <table width="465" border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
         <tr>
          <td colspan="2"><div align="center"><strong>Candidate Registration Form </strong></div></td>
        </tr>
        <tr>
          <td ><div align="right">Academic Year:<span class="style2">*</span></div></td>
          <td ><select name="ayear" id="select" class="vform">
            <option value="<?php echo $row_update['EntryYear']?>"><?php echo $row_update['EntryYear']?></option>
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
            <select name="campus">
			 <option value="<?php echo $row_update['Campus']?>"><?php echo $row_update['Campus']?></option>
            <?php
							do {  
									?>
            <option value="<?php echo $row_campus['Campus']?>"><?php echo $row_campus['Campus']?></option>
            <?php
								} while ($row_campus = mysql_fetch_assoc($campus));
  								$rows = mysql_num_rows($campus);
  								if($rows > 0) {
      								mysql_data_seek($campus, 0);
	  								$row_campus = mysql_fetch_assoc($campus);
  								}
						?>
          </select></td>
          </select></td>
        </tr>
		<tr>
        <tr>
			<TD><div align="right">Expiry Date:<span class="style2">*</span></div></TD>
			<!-- A Separate Layer for the Calendar -->
			<script language="JavaScript" src="datepicker/Calendar1-901.js" type="text/javascript"></script>
                <TD>
					<table border="0">
						<tr>
							<td><input type="text" class="vform" size="30" name="dtDate" value="<?php echo $row_update['GradYear']?>"></td>
							<td><input type="button" class="button" name="dtDate_button" value="Choose Date" onClick="show_calendar('registration.dtDate', '','','YYYY-MM-DD', 'POPUP','AllowWeekends=Yes;Nav=No;SmartNav=Yes;PopupX=300;PopupY=300;')"></td>
						</tr>
					</table>
                </TD>
        <tr>
          <td nowrap><div align="right">Registration No:<span class="style2">*</span></div></td>
          <td ><input name="regno" type="hidden" id="regno" value = "<?php echo $row_update['RegNo']?>">            <?php echo $row_update['RegNo']?></td><tr>
		  <td> <div align="right">Faculty:<span class="style2">*</span></div></td>
		  <td><select name="faculty" id="faculty">
		  <option value="<?php echo $row_update['Faculty']?>"><?php echo $row_update['Faculty']?></option>
                      <?php
							do {  
									?>
                      				<option value="<?php echo $row_faculty['FacultyName']?>"><?php echo $row_faculty['FacultyName']?></option>
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
			  <td><div align="right">Programme Registered:<span class="style2">*</span></div></td>
			  <? $degreecode = $row_update['ProgrammeofStudy'];
			  $qdegree = "SELECT ProgrammeName from programme WHERE ProgrammeCode = '$degreecode'";
			 $dbdegree = mysql_query($qdegree, $zalongwa);
			  $row_degree = mysql_fetch_assoc($dbdegree);
			  ?>
			  <td ><select name="degree" id="degree">
			  <option value="<?php echo $row_update['ProgrammeofStudy']?>"><?php echo $row_degree['ProgrammeName']?></option>
						  <?php
								do {  
										?>
										<option value="<?php echo $row_degree['ProgrammeCode']?>"><?php echo $row_degree['ProgrammeName']?></option>
										<?php
									} while ($row_degree = mysql_fetch_assoc($degree));
									$rows = mysql_num_rows($degree);
									if($rows > 0) {
										mysql_data_seek($degree, 0);
										$row_degree = mysql_fetch_assoc($degree);
									}
							?>
			  </select></td>
		  </tr>
		  <tr>
			  <td><div align="right">Subject Combination: </div></td>
			  <? $subjectid = $row_update['Subject'];
			  $qsubject = "SELECT SubjectName FROM subjectcombination WHERE SubjectID = '$subjectid'";
			 $dbsubject = mysql_query($qsubject, $zalongwa) or die(mysql_error());
			  $row_dbsubject = mysql_fetch_assoc($dbsubject);
			  ?>
			  <td ><select name="combi" id="combi">
			  <option value="<?php echo $row_update['Subject']?>"><?php echo $row_dbsubject['SubjectName']?></option>
						  <?php
								do {  
										?>
										<option value="<?php echo $row_combi['SubjectID']?>"><?php echo $row_combi['SubjectName']?></option>
										<?php
									} while ($row_combi = mysql_fetch_assoc($combi));
									$rows = mysql_num_rows($combi);
									if($rows > 0) {
										mysql_data_seek($combi, 0);
										$row_combi = mysql_fetch_assoc($combi);
									}
							?>
			  </select></td>
		  </tr>
		  <tr>
			  <td><div align="right">Manner of Entry:<span class="style2">*</span></div></td>
			  <td ><select name="manner" id="manner">
			  <option value="<?php echo $row_update['MannerofEntry']?>"><?php echo $row_update['MannerofEntry']?></option>
						  <?php
								do {  
										?>
										<option value="<?php echo $row_MannerofEntry['MannerofEntry']?>"><?php echo $row_MannerofEntry['MannerofEntry']?></option>
										<?php
									} while ($row_MannerofEntry = mysql_fetch_assoc($MannerofEntry));
									$rows = mysql_num_rows($MannerofEntry);
									if($rows > 0) {
										mysql_data_seek($MannerofEntry, 0);
										$row_MannerofEntry = mysql_fetch_assoc($MannerofEntry);
									}
							?>
			  </select></td>
		  </tr>
          <tr>
			  <td nowrap><div align="right"> Name:<span class="style2">*</span></div></td>
			  <td><input name="surname" type="text" id="surname" value = "<?php echo $row_update['Name']?>" size="30"></td>
		  <tr>
		  	<td nowrap><div align="right"> DateofBirth (YYYY-MM-DD):</div></td>
          	<!-- A Separate Layer for the Calendar -->
			<script language="JavaScript" src="datepicker/Calendar1-901.js" type="text/javascript"></script>
                                      <TD ALIGN=LEFT VALIGN=MIDDLE nowrap><div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">
                           <input name="txtYear" type="text" id="txtYear" size="4" maxlength="4" >
                            <select name="txtMonth" id="txtMonth">
                              <option selected>-----------</option>
                              <option value="01">January</option>
                              <option value="02">February</option>
                              <option value="03">March</option>
                              <option value="04">April</option>
                              <option value="05">May</option>
                              <option value="06">June</option>
                              <option value="07">July</option>
                              <option value="08">August</option>
                              <option value="09">September</option>
                              <option value="10">October</option>
                              <option value="11">November</option>
                              <option value="12">December</option>
                            </select>
                            <select name="txtDay" id="txtDay">
                          <option selected>---</option>
                          <option value="01">01</option>
                          <option value="02">02</option>
                          <option value="03">03</option>
                          <option value="04">04</option>
                          <option value="05">05</option>
                          <option value="06">06</option>
                          <option value="07">07</option>
                          <option value="08">08</option>
                          <option value="09">09</option>
                          <option value="10">10</option>
                          <option value="11">11</option>
                          <option value="12">12</option>
                          <option value="13">13</option>
                          <option value="14">14</option>
                          <option value="15">15</option>
                          <option value="16">16</option>
                          <option value="17">17</option>
                          <option value="18">18</option>
                          <option value="19">19</option>
                          <option value="20">20</option>
                          <option value="21">21</option>
                          <option value="22">22</option>
                          <option value="23">23</option>
                          <option value="24">24</option>
                          <option value="25">25</option>
                          <option value="26">26</option>
                          <option value="27">27</option>
                          <option value="28">28</option>
                          <option value="29">29</option>
                          <option value="30">30</option>
                          <option value="31">31</option>
                            </select>
<input name="dbirth" type="hidden" id="dbirth" value = "<?php echo $row_update['DBirth']?>"><?php echo $row_update['DBirth']?></font></div></TD>
		  </tr>
          <tr>
			  <td nowrap><div align="right"> AgeatEntry:</div></td>
			  <td><input name="age" type="text" id="age" value = "" size="30"></td>
		  <tr>
			  <td><div align="right">Sex:</div></td>
			  <td><select name="sex" id="sex">
			  <option value="<?php echo $row_update['Sex']?>"><?php echo $row_update['Sex']?></option>
         	   <option value="M">Male</option>
         	   <option value="F">Female</option>
        	  </select></td>
		  </tr>
	      <tr>
			  <td><div align="right">Country:</div></td>
			  <td><select name="country" id="country">
			   <option value="<?php echo $row_update['Nationality']?>"><?php echo $row_update['Nationality']?></option>
            
			<?php
							do {  
									?>
            <option value="<?php echo $row_country['szCountry']?>"><?php echo $row_country['szCountry']?></option>
            <?php
								} while ($row_country = mysql_fetch_assoc($country));
  								$rows = mysql_num_rows($country);
  								if($rows > 0) {
      								mysql_data_seek($country, 0);
	  								$row_country = mysql_fetch_assoc($country);
  								}
						?>
          </select></td>
	    <tr>
			   <td><div align="right">District:</div></td>
			   <td><input name="district" type="text" id="district" value = "<?php echo $row_update['District']?>" size="30"> </td>
	    </tr>
          <tr>
			  <td><div align="right">Region:</div></td>
			  <td><input name="region" type="text" id="region" size="30" value = "<?php echo $row_update['Region']?>"></td>
		  </tr>
		  <tr>
          <td nowrap><div align="right"> MaritalStatus:</div></td>
          <td><select name="maritalstatus" id="maritalstatus">
		  <option value="<?php echo $row_update['MaritalStatus']?>"><?php echo $row_update['MaritalStatus']?></option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Divorced">Divorced</option>
            <option value="Widowed">Widowed</option>
          </select></td>
		  <tr>
		      <td><div align="right"> Permanent Address: </div></td>
              <td><input name="address" type="text" id="address" size="30" value = "<?php echo $row_update['Address']?>"></td>
        </tr>
		<tr>
          <td nowrap><div align="right"> Religion:</div></td>
          <td><select name="religion" id="religion">
		   <option value="<?php echo $row_update['Religion']?>"><?php echo $row_update['Religion']?></option>
                      <?php
							do {  
									?>
                      				<option value="<?php echo $row_religion['ReligionID']?>"><?php echo $row_religion['Religion']?></option>
                      				<?php
								} while ($row_religion = mysql_fetch_assoc($religion));
  								$rows = mysql_num_rows($religion);
  								if($rows > 0) {
      								mysql_data_seek($religion, 0);
	  								$row_religion = mysql_fetch_assoc($religion);
  								}
						?>
                    </select></td>
		</tr>
			<tr>
			 <td nowrap><div align="right">Sect or Denomination: </div></td>
          	 <td><input name="denomination" type="text" id="denomination" value = "<?php echo $row_update['Denomination']?>" size="30"></td>
		 </tr>
 		<tr>
          <td nowrap><div align="right"> Postal Address: </div></td>
           <td> <input name="postaladdress" type="text" id="postaladdress" size="30" value = "<?php echo $row_update['Address']?>"></td>
		</tr>
		<tr>
			<td><div align="right">Residential Address: </div></td>
			<td><input name="residenceaddress" type="text" id="residenceaddress" size="30" value ="<?php echo $row_update['Address']?>"></td>
		</tr>
		<tr>
          <td nowrap><div align="right">Physical Disability: </div></td>
           <td> <select name="disability" id="disability">
              		  <option value="<?php echo $row_update['Disability']?>"><?php echo $row_update['Disability']?></option>
                      <?php
							do {  
									?>
                      				<option value="<?php echo $row_disability['DisabilityID']?>"><?php echo $row_disability['Disability']?></option>
                      				<?php
								} while ($row_disability = mysql_fetch_assoc($disability));
  								$rows = mysql_num_rows($disability);
  								if($rows > 0) {
      								mysql_data_seek($disability, 0);
	  								$row_disability = mysql_fetch_assoc($disability);
  								}
						?>
            </select></td>
		</tr>
			<tr>
          <td nowrap><div align="right">Student Status: </div></td>
          <td ><select name="status" id="status">
		<option value="<?php echo $row_update['Status']?>">
		<?php $statusid= $row_update['Status'];
		$qstname = "select * from studentstatus where StatusID ='$statusid'";
		$dbstname = mysql_query($qstname);
		$row_stname = mysql_fetch_assoc($dbstname);
		echo $row_stname['Status'];
		?>
		</option>
                      <?php
							do {  
									?>
                      				<option value="<?php echo $row_studentStatus['StatusID']?>"><?php echo $row_studentStatus['Status']?></option>
                      				<?php
								} while ($row_studentStatus = mysql_fetch_assoc($studentStatus));
  								$rows = mysql_num_rows($studentStatus);
  								if($rows > 0) {
      								mysql_data_seek($studentStatus, 0);
	  								$row_studentStatus = mysql_fetch_assoc($studentStatus);
  								}
						?>
          </select></td>
        </tr>
		<tr>
          <td><div align="right">Sponsor:<span class="style2">*</span></div></td>
          <td><select name="txtSponsor" id="txtSponsor">
		  <option value="<?php echo $row_update['Sponsor']?>"><?php echo $row_update['Sponsor']?></option>
            <?php
do {  
?>
            <option value="<?php echo $row_sponsor['Name']?>"><?php echo $row_sponsor['Name']?></option>
            <?php
} while ($row_sponsor = mysql_fetch_assoc($sponsor));
  $rows = mysql_num_rows($sponsor);
  if($rows > 0) {
      mysql_data_seek($sponsor, 0);
	  $row_sponsor = mysql_fetch_assoc($sponsor);
  }
?>
          </select></td>
        </tr>
		<tr>
          <td>&nbsp;</td>
          <td >          <div align="center">
            <input name="actionupdate" type="submit" id="actionupdate" onClick="return formValidator()" value="Update">
          </div></td>
        </tr>
      </table>
      </form>
	  <?php    
}else{
?>	
	<form name="registration" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
	  <table width="465" border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
        <tr>
          <td colspan="2"><div align="center"><strong>Candidate  Registration Form </strong></div></td>
        </tr>
        <tr>
          <td><div align="right">Academic Year:<span class="style2">*</span></div></td>
          <td><select name="ayear" id="select" class="vform">
            <option value="0">[Academic Year]</option>
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
            <select name="campus" id="campus" class="vform">
			<option value="0">[Select Campus]</option>
            <?php
							do {  
									?>
            <option value="<?php echo $row_campus['Campus']?>"><?php echo $row_campus['Campus']?></option>
            <?php
								} while ($row_campus = mysql_fetch_assoc($campus));
  								$rows = mysql_num_rows($campus);
  								if($rows > 0) {
      								mysql_data_seek($campus, 0);
	  								$row_campus = mysql_fetch_assoc($campus);
  								}
						?>
          </select></td>
        </tr>
		<tr>
          <td nowrap><div align="right">Expiry Date:<span class="style2">*</span> </div></td>
         <!-- A Separate Layer for the Calendar -->
			<script language="JavaScript" src="datepicker/Calendar1-901.js" type="text/javascript"></script>
                <TD>
					<table border="0">
						<tr>
							<td><input type="text" class="vform" size="30" name="dtDate" value="<?php echo $row_update['GradYear']?>"></td>
							<td><input type="button" class="button" name="dtDate_button" value="Choose Date" onClick="show_calendar('registration.dtDate', '','','YYYY-MM-DD', 'POPUP','AllowWeekends=Yes;Nav=No;SmartNav=Yes;PopupX=300;PopupY=300;')"></td>
						</tr>
					</table>
                </TD>
	    </tr>
        <tr>
          <td nowrap><div align="right">Registration No:<span class="style2">*</span></div></td>
          <td nowrap><input name="regno" type="text" id="regno" size="30" >  </td>
	    </tr>
		  <tr>
		  <td><div align="right">Faculty:<span class="style2">*</span></div></td>
          <td><select name="faculty" id="faculty">
		  <option value="0">[Select Faculty]</option>
                      <?php
							do {  
									?>
                      				<option value="<?php echo $row_faculty['FacultyName']?>"><?php echo $row_faculty['FacultyName']?></option>
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
          <td><div align="right">Programme Registered:<span class="style2">*</span></div></td>
          <td><select name="degree" id="degree">
		  <option value="0">[Select Degree]</option>
                      <?php
							do {  
									?>
                      				<option value="<?php echo $row_degree['ProgrammeCode']?>"><?php echo $row_degree['ProgrammeName']?></option>
                      				<?php
								} while ($row_degree = mysql_fetch_assoc($degree));
  								$rows = mysql_num_rows($degree);
  								if($rows > 0) {
      								mysql_data_seek($degree, 0);
	  								$row_degree = mysql_fetch_assoc($degree);
  								}
						?>
          </select>
	  	    <tr>
			  <td><div align="right">Subject Combination: </div></td>
			  <td ><select name="combi" id="combi">
  <option value="0">[Select Subject Combination]</option>					  
 <?php
								do {  
										?>
										<option value="<?php echo $row_combi['SubjectID']?>"><?php echo $row_combi['SubjectName']?></option>
										<?php
									} while ($row_combi = mysql_fetch_assoc($combi));
									$rows = mysql_num_rows($combi);
									if($rows > 0) {
										mysql_data_seek($combi, 0);
										$row_combi = mysql_fetch_assoc($combi);
									}
							?>
			  </select></td>
		  </tr>
		<tr>
			  <td><div align="right">Manner of Entry:<span class="style2">*</span> </div></td>
			 
			  <td ><select name="manner" id="manner">
			  <option value="0">[Select Manner of Entry]</option>		
						  <?php
								do {  
										?>
										<option value="<?php echo $row_MannerofEntry['ID']?>"><?php echo $row_MannerofEntry['MannerofEntry']?></option>
										<?php
									} while ($row_MannerofEntry = mysql_fetch_assoc($MannerofEntry));
									$rows = mysql_num_rows($MannerofEntry);
									if($rows > 0) {
										mysql_data_seek($MannerofEntry, 0);
										$row_MannerofEntry = mysql_fetch_assoc($MannerofEntry);
									}
							?>
			  </select></td>
	    </tr>
        <tr>
          <td nowrap><div align="right"> Surname:<span class="style2">*</span></div></td>
          <td ><input name="surname" type="text" id="surname" size="30"></td>
	    </tr>
		 <tr>
		  <td wnowrap><div align="right"> Firstname:</div></td>
          <td><input name="firstname" type="text" id="firstname" size="30"></td>
		 </tr>
		 <tr>
          <td  nowrap> <div align="right">Middlename: </div></td>
          <td ><input name="middlename" type="text" id="middlename" size="15" maxlength="50"></td>
        </tr>
        <tr>
          <td nowrap><div align="right">DateofBirth (YYYY-MM-DD):<span class="style2">*</span></div></td>
          <!-- A Separate Layer for the Calendar -->
			<script language="JavaScript" src="datepicker/Calendar1-901.js" type="text/javascript"></script>
                                         <TD ALIGN=LEFT VALIGN=MIDDLE nowrap><div align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">
                           <input name="txtYear" type="text" id="txtYear" size="4" maxlength="4" >
                            <select name="txtMonth" id="txtMonth">
                              <option selected>-----------</option>
                              <option value="01">January</option>
                              <option value="02">February</option>
                              <option value="03">March</option>
                              <option value="04">April</option>
                              <option value="05">May</option>
                              <option value="06">June</option>
                              <option value="07">July</option>
                              <option value="08">August</option>
                              <option value="09">September</option>
                              <option value="10">October</option>
                              <option value="11">November</option>
                              <option value="12">December</option>
                            </select>
                            <select name="txtDay" id="txtDay">
                          <option selected>---</option>
                          <option value="01">01</option>
                          <option value="02">02</option>
                          <option value="03">03</option>
                          <option value="04">04</option>
                          <option value="05">05</option>
                          <option value="06">06</option>
                          <option value="07">07</option>
                          <option value="08">08</option>
                          <option value="09">09</option>
                          <option value="10">10</option>
                          <option value="11">11</option>
                          <option value="12">12</option>
                          <option value="13">13</option>
                          <option value="14">14</option>
                          <option value="15">15</option>
                          <option value="16">16</option>
                          <option value="17">17</option>
                          <option value="18">18</option>
                          <option value="19">19</option>
                          <option value="20">20</option>
                          <option value="21">21</option>
                          <option value="22">22</option>
                          <option value="23">23</option>
                          <option value="24">24</option>
                          <option value="25">25</option>
                          <option value="26">26</option>
                          <option value="27">27</option>
                          <option value="28">28</option>
                          <option value="29">29</option>
                          <option value="30">30</option>
                          <option value="31">31</option>
                            </select>
</font></div></TD>
		</tr>
		<tr>		
          <td nowrap><div align="right"> AgeatEntry:</div></td>
          <td><input name="age" type="text" id="age" size="30"></td>
	    </tr>
		  <tr>
          <td><div align="right">Sex:<span class="style2">*</span></div></td>
          <td><select name="sex" id="sex">
		  <option value="0">[Select Sex]</option>
            <option value="M">Male</option>
            <option value="F">Female</option>
          </select></td>
        </tr>
        <tr>
          <td><div align="right">Country:<span class="style2">*</span></div></td>
          <td><select name="country" id="country">
		  <option value="0">[Select Country]</option>
            <?php
							do {  
									?>
            <option value="<?php echo $row_country['szCountry']?>"><?php echo $row_country['szCountry']?></option>
            <?php
								} while ($row_country = mysql_fetch_assoc($country));
  								$rows = mysql_num_rows($country);
  								if($rows > 0) {
      								mysql_data_seek($country, 0);
	  								$row_country = mysql_fetch_assoc($country);
  								}
						?>
          </select></td>
	    <tr> 
          <td><div align="right">District:</div></td>
          <td><input name="district" type="text" id="district" size="30"></td>
	    </tr>
		  <tr>
          <td><div align="right">Region:</div></td>
          <td><input name="region" type="text" id="region" size="30"></td>
        </tr>
		<tr>
          <td nowrap><div align="right"> MaritalStatus:<span class="style2">*</span></div></td>
          <td><select name="maritalstatus" id="maritalstatus">
		  <option value="0">[Select Marital Status]</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Divorced">Divorced</option>
            <option value="Widowed">Widowed</option>
          </select></td>
	    </tr>
		 <tr>
          <td nowrap><div align="right">Permanent Address:<span class="style2">*</span> </div></td>
          <td><input name="address" type="text" id="address" size="30"></td>
        </tr>
		<tr>
          <td nowrap><div align="right">Religion:</div></td>
          <td><select name="religion" id="religion">
		  <option value="0">[Select Religion]</option>
                      <?php
							do {  
									?>
                      				<option value="<?php echo $row_religion['Religion']?>"><?php echo $row_religion['Religion']?></option>
                      				<?php
								} while ($row_religion = mysql_fetch_assoc($religion));
  								$rows = mysql_num_rows($religion);
  								if($rows > 0) {
      								mysql_data_seek($religion, 0);
	  								$row_religion = mysql_fetch_assoc($religion);
  								}
						?>
                    </select></td>
	    </tr>
		 <tr>
          <td nowrap><div align="right">Sect or Denomination: </div></td>
          <td><input name="denomination" type="text" id="denomination" size="30"></td>
	    </tr>
		<tr>
          <td nowrap><div align="right">Postal Address: </div></td>
            <td> <input name="postaladdress" type="text" id="postaladdress" size="30"></td>
		</tr>
		<tr>
          <td><div align="right">Residential Address: </div></td>
		  <td ><input name="residenceaddress" type="text" id="residenceaddress" size="30"></td>
        </tr>
		<tr>
          <td nowrap><div align="right">Physical Disability: </div></td>
		  <td>
            <select name="disability" id="disability">
              		  <option value="0">[Select Disability]</option>
                      <?php
							do {  
									?>
                      				<option value="<?php echo $row_disability['Disability']?>"><?php echo $row_disability['Disability']?></option>
                      				<?php
								} while ($row_disability = mysql_fetch_assoc($disability));
  								$rows = mysql_num_rows($disability);
  								if($rows > 0) {
      								mysql_data_seek($disability, 0);
	  								$row_disability = mysql_fetch_assoc($disability);
  								}
						?>
            </select></td>
		</tr>
			<tr>
          <td nowrap><div align="right">Student Status:<span class="style2">*</span> </div></td>
          <td ><select name="status" id="status">
		    <option value="0">[Select Status]</option>
                      <?php
							do {  
									?>
                      				<option value="<?php echo $row_studentStatus['Status']?>"><?php echo $row_studentStatus['Status']?></option>
                      				<?php
								} while ($row_studentStatus = mysql_fetch_assoc($studentStatus));
  								$rows = mysql_num_rows($studentStatus);
  								if($rows > 0) {
      								mysql_data_seek($studentStatus, 0);
	  								$row_studentStatus = mysql_fetch_assoc($studentStatus);
  								}
						?>
          </select></td>
        </tr>
		<tr>
          <td><div align="right">Sponsor:<span class="style2">*</span></div></td>
          <td ><select name="txtSponsor" id="txtSponsor" title="<?php echo $row_sponsor['Name']; ?>">
		  <option value="0">[Select Sponsor]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_sponsor['Name']?>"><?php echo $row_sponsor['Name']?></option>
            <?php
} while ($row_sponsor = mysql_fetch_assoc($sponsor));
  $rows = mysql_num_rows($sponsor);
  if($rows > 0) {
      mysql_data_seek($sponsor, 0);
	  $row_sponsor = mysql_fetch_assoc($sponsor);
  }
?>
                                        </select></td>
        </tr>
		<tr>
          <td>&nbsp;</td>
          <td ><div align="center">
            <input name="actionadd" type="submit" id="actionadd" value="AddNew">
          </div></td>
        </tr>
      </table>
</form>

	<?php 
	} 
	# include the footer
	include("../footer/footer.php");
	
mysql_free_result($sponsor);
?>
