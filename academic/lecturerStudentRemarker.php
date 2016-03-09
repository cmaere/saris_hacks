<?php
#get connected to the database and verify current session
	require_once('../Connections/sessioncontrol.php');
    require_once('../Connections/zalongwa.php');

# include the header
include('lecturerMenu.php');
	global $szSection, $szSubSection, $szTitle, $additionalStyleSheet;
	$szSection = 'Administration';
	$szTitle = 'Register Student Remarks';
	$szSubSection = 'Student Remarks';
	include("lecturerheader.php");

	#populate academic year Combo Box
	$query_paytype = "SELECT AYear FROM academicyear ORDER BY AYear DESC";
	$paytype = mysql_query($query_paytype, $zalongwa) or die(mysql_error());
	$row_paytype = mysql_fetch_assoc($paytype);
	$totalRows_paytype = mysql_num_rows($paytype);
	
	#populate semester Box
	$query_semester = "SELECT Description FROM terms ORDER BY Semester ASC";
	$semester = mysql_query($query_semester, $zalongwa) or die(mysql_error());
	$row_semester = mysql_fetch_assoc($semester);
	$totalRows_semester = mysql_num_rows($semester);
	
	#populate course list combo box
	$query_course = "SELECT Remark, Description FROM examremark ORDER BY Remark ASC";
	$course = mysql_query($query_course, $zalongwa) or die(mysql_error());
	$row_course = mysql_fetch_assoc($course);
	$totalRows_course = mysql_num_rows($course);
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
#delete student remarks
if (isset($_GET['year'])){
	$cand = $_GET['Candidate'];
	$year =  $_GET['year'];
	$qdelete = "DELETE FROM studentremark WHERE RegNo='$cand' AND AYear ='$year'";
	$dbdelete = mysql_query($qdelete );
}

function add($f)
{
  global $errorindicator,$errorclass,$Javascript;
  $tocheck=explode(',',','.$_POST['required']);
  preg_match('/id="(.*?)"/i',$f,$i);
  preg_match('/name="(.*?)"/i',$f,$n);
  preg_match('/type="(.*?)"/i',$f,$t);
  preg_match('/value="(.*?)"/i',$f,$iv);
  $n=$n[1];$iv=$iv[1];$i=str_replace('_',' ',$i[1]);
  if(preg_match('/<textarea/',$f)){
    $v=$_POST[$n]==''?$i:$_POST[$n];
    $f=preg_replace('/<textarea(.*?)>(.*?)<\/textarea>/',
    '<textarea\\1>'.stripslashes(htmlentities($v)).'</textarea>',$f);
        if($Javascript){$f=preg_replace('/<textarea/',
        '<textarea onfocus="this.value=this.value==\''.
        $i.'\'?\'\':this.value"',$f);}
  }  
  if(@preg_match('/<select/',$f)){
    @preg_match('/<select.*?>
<style type="text/css">
<!--
.style4 {color: #CCCCCC}
-->
</style>
/',$f,$st);
    @preg_match_all('/<option value="(.*?)">(.*?)<\/option>/',$f,$allopt);
    foreach ($allopt[0] as $k=>$a){
      if($_POST[$n]==$allopt[1][$k] || 
      ($_POST[$n]=='' && $k==0)){
        $preg='/<option value="';
        $preg.=$allopt[1][$k].'">'.$allopt[2][$k].
        '<\/option>/si';
        $rep='<option selected="selected" value="';
        $rep.=$allopt[1][$k].'">'.$allopt[2][$k].
        '</option>';
        $f=preg_replace($preg,$rep,$f);
      }
    }
  }else{
    switch ($t[1]){
      case 'text':
        $v=$_POST[$n]==''?'value="'.$i.'"':'value="'.
        stripslashes(htmlentities($_POST[$n])).'"';
        $f=preg_replace('/<input/','<input '.$v,$f);
        if($Javascript){$f=preg_replace('/<input/',
        '<input onfocus="this.value=this.value==\''
        .$i.'\'?\'\':this.value"',$f);}
      break;
    }
  }
  $f.='<input type="hidden" name="'.$n.'initvalue" value="'.$i.'" />';
  if (array_search($n,$tocheck) and ($_POST[$n]=='' or $_POST[$n]==$i)){
      if($errorindicator!=''){$f=$errorindicator.$f;}
      if($errorclass!=''){$f=preg_replace('/name=/i','class="'.
      $errorclass.'" name=',$f);}
  }
  return $f;
}

// check the form
function check()
{
  if ($_POST!=''){
    $sentarray=array();
    $tocheck=explode(',',$_POST['required']);
    $error[0]="Errors:";
    foreach ($tocheck as $t){if(!array_key_exists($t,$_POST)){
    $error[]=$t;}}
    foreach (array_keys($_POST) as $p){
      if(!preg_match('/initvalue/',$p) and 
      !preg_match('/required/',$p)){
        $sentarray[$p]=$_POST[$p]==
        $_POST[$p.'initvalue']?'':$_POST[$p];
      }
      foreach ($tocheck as $c){
        if ($p==$c and $sentarray[$p]==''){
          $error[]=$_POST[$p.'initvalue'];
        }
      }
    }
    return $error[1]==''?$sentarray:$error;  
    }
}
?>

<?PHP
//use the function
  $errorindicator='<img src="images/delete.gif" width="14" height="14" 
  alt="Alert" title="Indicator for missing form element" border="0" />';
  $errorclass="error";
  $Javascript=true;
  $results=check();
  if($results[0]=='Errors:')
  {
    
?>
  <h3>There has been an error:</h3>
  <p>You forgot to enter the following field(s)</p>
  <ul>
  <?PHP foreach ($results as $i=>$e){if ($i>0){
    echo "<li>$e</li>";
  }}
  @$errored=1;
  $regnos = $_POST['regno'];
  ?>
  </ul>
<?PHP 
  }
  
###############################################
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["save"])) && ($_POST["save"] == "Save")) {

  //get submitted vaules
$course = addslashes($_POST['course']);
$ayear = addslashes($_POST['Ayear']);
$sregno = addslashes($_POST['regno']);
$semester = addslashes($_POST['semester']);

//query current Year
$qyear = "SELECT AYear from academicyear WHERE Status = 1";
$dbyear = mysql_query($qyear);
$row_year = mysql_fetch_assoc($dbyear);
$currentYear = $row_year['AYear'];
if($currentYear<>$ayear){
echo "You Cannot Add Exam Remarks This Year:".$ayear."<br> Database Update Rejected !!";
exit;
}

//get course capacity
$qcapacity = "SELECT Remark from studentremark where (RegNo = '$sregno') AND (AYear = '$ayear') AND (Semester = '$semester')";
$dbcapacity = mysql_query($qcapacity);
$row_capacity = mysql_fetch_assoc($dbcapacity);
$capacity = $row_capacity['Remark'];

 if($course<>$capacity ){
	  $insertSQL = sprintf("INSERT INTO studentremark (AYear, Semester, RegNo, Remark) 
	  													VALUES (%s, %s, %s,  %s)",
						   GetSQLValueString($_POST['Ayear'], "text"),
						   GetSQLValueString($_POST['semester'], "text"),
						   GetSQLValueString($_POST['regno'], "text"),
						   GetSQLValueString($_POST['course'], "text"));
	
	  mysql_select_db($database_zalongwa, $zalongwa);
	  $Result1 = mysql_query($insertSQL, $zalongwa) or die(mysql_error());
	  echo '<meta http-equiv = "refresh" content ="0; 
							url = lecturerStudentRemarker.php">';
	}else{
	echo "This remarks, ".$course.", for candidate , ".$sregno." ,  already recorded <br>";
	}
}
  ###############################################
//Search Candidate
if (isset($_POST["candidate"])){
$key=trim($_POST["candidate"]);
$query_candidate = "SELECT student.Name, student.RegNo
						  FROM student 
						  WHERE (student.RegNo ='$key')";
$candidate = mysql_query($query_candidate, $zalongwa) or die(mysql_error());
$row_candidate = mysql_fetch_assoc($candidate);
$totalRows_candidate = mysql_num_rows($candidate);

}
//display the form if candidate is found
if((@$totalRows_candidate>0)||($errored==1)){ 
?>
			<form name="addpayment" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
			  <input type="hidden" name="required" value="regno" />
			  <input name="user" type="hidden" id="user" value="<?php echo $username;?>">
              <input name="checked" type="hidden" id="checked" value="0">
			  <input name="rdate" type="hidden" id="rdate" value="<?php $today = date("Y-m-d");  echo $today;?>">
			  <fieldset>
				<legend>Student Exam  Remarks</legend>
				<table width="200" border="1" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
				  <tr>
					<th scope="row"><div align="right">Candidate:</div></th>
					<td nowrap="nowrap"><input type="hidden" name="regno" id="regno" value="<?php echo $row_candidate['RegNo'] ?>" />	
					<?php echo $row_candidate['Name'].": " ?><?php echo $row_candidate['RegNo']?>					</td>
				  </tr>
				  <tr>
					<th nowrap scope="row"><div align="right">Semester: </div></th>
					<td>
					<select name="semester" id="semester">
				  <option value="0">[Select Semester]</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_semester['Description']?>"><?php echo $row_semester['Description']?></option>
                    <?php
} while ($row_semester = mysql_fetch_assoc($semester));
  $rows = mysql_num_rows($semester);
  if($rows > 0) {
      mysql_data_seek($semester, 0);
	  $row_semester = mysql_fetch_assoc($semester);
  }
?>
                  </select>					</td>
				  </tr>
				  
				  <tr>
					
				  </tr>
				  <tr>
					<th nowrap scope="row"><div align="right">Year: </div></th>
					<td><select name="Ayear" id="Ayear">
				  <option value="0">[Select Academic Year]</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_paytype['AYear']?>"><?php echo $row_paytype['AYear']?></option>
                    <?php
} while ($row_paytype = mysql_fetch_assoc($paytype));
  $rows = mysql_num_rows($paytype);
  if($rows > 0) {
      mysql_data_seek($paytype, 0);
	  $row_paytype = mysql_fetch_assoc($paytype);
  }
?>
                  </select></td>
				  </tr>
				  <th scope="row"><div align="right">Remarks:</div></th>
					<td><select name="course" id="course">
				  <option value="0">[Select CourseCode]</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_course['Remark']?>"><?php echo $row_course['Description']?></option>
                    <?php
} while ($row_course = mysql_fetch_assoc($course));
  $rows = mysql_num_rows($course);
  if($rows > 0) {
      mysql_data_seek($course, 0);
	  $row_course = mysql_fetch_assoc($course);
  }
?>
                  </select>				    </td>
				  <tr>				  </tr>
				  <tr>
					<th scope="row">&nbsp;</th>
					<td nowrap><input name="save" type="submit" id="save" value="Save" />
				    <span class="style4">............</span>          <input name="cancel" type="reset" id="cancel" value="Cancel"></td>
				  </tr>
				</table>
				<p>&nbsp;    </p>
			  </fieldset>
			</form>
            
	<?php
			   #search student remarks
			   echo '<hr>';
			   $qremarks = "SELECT * FROM studentremark WHERE RegNo='$key'";
				$dbremarks = mysql_query($qremarks);
				while ($row_remarks = mysql_fetch_assoc($dbremarks)) {
				$regno = $row_remarks['RegNo'];
				$ayear  = $row_remarks['AYear'];
				echo "<a href=\"lecturerStudentRemarker.php?Candidate=$regno&year=$ayear\">Delete </a>".' --> '.$regno.' - '. $ayear. ' - '.  ' - '. $row_remarks['Remark']. '<br>';
  				}
  }else{
  ?>
  <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" name="search" id="search">
						<fieldset>
						 <legend>Search Candidate </legend>
						 <table width="200" border="0" align="right" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
			  <tr>
				<td nowrap><div align="right"><strong>Enter RegNo:</strong></div></td>
				<td><input name="candidate" type="text" id = "candidate" value="" size = "25" maxlength="20"></td>
				<td><input type="submit" name="Submit" value="GO"></td>
			  </tr>
			</table>
			</fieldset>
			</form>
			<?php }
	# include the footer
	include('../footer/footer.php');
?>