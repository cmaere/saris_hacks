<?php 
require_once('../Connections/sessioncontrol.php');
# include the header
include('studentMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Accommodation';
	$szTitle = 'ACCOMMODATION';
	$szSubSection = 'Apply for a Room';
	//$additionalStyleSheet = './general.css';
	include("studentheader.php");
    mysql_select_db($database_zalongwa, $zalongwa);
    
	
   if(isset($_POST["apply"]))
{
    
    $acyear = date ('Y');
    $date = date ( "Y-m-d H:t:s " );
    $name1 = $_POST['name'];
    $regno = $_POST['regno'];
    $gender = $_POST['gender'];
    $degree = $_POST['degree'];
    $mate = $_POST['mate'];
    $letter = $_POST['letter'];
    
    
    $sql = "INSERT INTO roomapp(acyear,date,name,regno,gender,degree,mate,letter) VALUES('$acyear','$date','$name1','$regno','$gender','$degree','$mate','$letter')";
    mysql_query($sql) or die(mysql_error());   
    
    echo "==============APPLICATION RECIEVED====================";

}
else
{
    $year = date ('Y');
    
$sqlb= "SELECT regno FROM roomapp WHERE acyear = $year AND regno = '$RegNo'";

            $result=mysql_query($sqlb);
          
             $check = mysql_num_rows($result);
             
             if($check < 1)
             {

    
    
    
    
                    $query_Hostel = "SELECT ProgrammeCode, ProgrammeName FROM programme ORDER BY ProgrammeName ASC";
                    $Hostel = mysql_query($query_Hostel, $zalongwa) or die(mysql_error());
                    $row_Hostel = mysql_fetch_assoc($Hostel);
                    $totalRows_Hostel = mysql_num_rows($Hostel);




                    ?>
                    <br>
                    ACCOMMODATION APPLICATION FORM <BR><BR>
                    <form action='roomapplication.php' method='POST'>
                    <table>

                    <tr><td>FullName:<td><input type='text' name='name' value='<?php echo $name; ?>'></tr>
                    <tr><td>Regno:<td><input type='text' name='regno' value='<?php echo $RegNo; ?>'></tr>
                    <tr><td>Gender:<td><select name="gender" id="select2">
                             <option value="0">--------------------------------</option>
                                

                                <option value="Male">MALE</option>
                                <option value="Female">FEMALE</option>
                               
                               
                              </select></tr>
                    <tr><td>Programme To be Next Academic Year:<td><select name="degree" id="select">
                               <option value="0">--------------------------------</option>
                                <?php
                    do {  
                    ?>
                                <option value="<?php echo $row_Hostel['ProgrammeCode']?>"><?php echo $row_Hostel['ProgrammeName']?></option>
                                <?php
                    } while ($row_Hostel = mysql_fetch_assoc($Hostel));
                      $rows = mysql_num_rows($Hostel);
                      if($rows > 0) {
                          mysql_data_seek($Hostel, 0);
                          $row_Hostel = mysql_fetch_assoc($Hostel);
                      }
                    ?>
                              </select></tr>

                    <tr><td>Proposed Room Mate:<td><input type='text' name='mate' value=''></tr>

                    <tr><td>Application Letter:<td><textarea name="letter" cols="20" rows="8"></textarea>
                    </tr>
                    <tr><td><td><input type='submit' name='apply' value='APPLY >>'></tr>
                    </form>
                    </table>

                    <br> 
                    <?php
                    
                    
                }
                else
                {
                    
                    echo "$name HAS ALREADY APPLIED ";
                    
                    
                    
                }
                    
}
	# include the footer
	include("../footer/footer.php");
?>