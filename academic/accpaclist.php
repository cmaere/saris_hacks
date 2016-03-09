 <?php require_once('../Connections/zalongwa.php'); 
 $editFormAction = $_SERVER['PHP_SELF'];
 
 if (isset($_POST["cmdEdit"])){
 
 
// $ayear=addslashes($_POST['ayear']);
	$RegNo = $_POST['RegNo'];
	$cwk = $_POST['cwk'];
    $max = sizeof($RegNo);
    
    for($c = 0; $c < $max; $c++) 
    {
        $id = $cwk[$c];
        $regno = $RegNo [$c];
        //die('here '.$regno.' '.$id);
        //$score2 = floatval($cwk[$c]);
    
            
    
               
                
    
                    $updateSQL = "UPDATE security SET AccpacID ='$id' WHERE RegNo = '$regno'";
                        //to insert score validations later in future
                        
                  //      die($updateSQL);
                        mysql_query($updateSQL);
                 



        }
    
 
 }
 else if(isset($_POST["search"]))
 {
 
 
 
 
 
 ?>
<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" >
<form action="accpaclist.php" method="POST" enctype="multipart/form-data" name="form1">
              <tr><td><td><td>
        <td>
            <p>
              <input name="cmdEdit" type="submit" id="cmdEdit" value="Update Records">
              
              
            </p><td>
			  </tr><tr>
                <td width="4%"><strong>S/No</strong></td>
                <td width="13%"><strong>Name</strong></td>
                <td width="16%"><strong>RegNo</strong></td>
                <td width="16%"><strong>ACCPAC IDs</strong></td>
                
              </tr>
              <?php 
              
              $proyear = $_POST['program'];
             
			 
              
              $i=1;
				$sql3 = "select RegNo,FullName, AccpacID from security where  RegNo like 'kcn/bscn/".$proyear."%'  and RegNo not like '%ucm' 
order by (RegNo)";
                
        $result3 = mysql_query($sql3);
        while($rowb = mysql_fetch_array($result3, MYSQL_ASSOC))
        {
            $regno= $rowb['RegNo'];
            $fullname= $rowb['FullName'];
            $accpac = $rowb['AccpacID'];
			  ?>
              <tr>
                <td align="left" valign="middle"><div align="left"> <?php echo $i; ?> </div></td>
                <td align="left" valign="middle" nowrap><?php echo $fullname; ?></td>
                <td align="left" valign="middle"><input name="RegNo[]" type="hidden" id="RegNo[]" value="<?php echo $regno; ?>">
                <?php  echo $regno; ?></td>
                
                <td>
                <input name='cwk[]' type='text' id='cwk[]' value='<?php echo $accpac; ?>' ></td>
                
        
              </tr>
              <?php $i=$i+1;
        }
        ?><tr><td><td><td>
        <td>
            <p>
              <input name="cmdEdit" type="submit" id="cmdEdit" value="Update Records">
              
              
            </p><td>
			  </tr></table>
              </form>
              
              <?php
              }
              else
              {
              
              ?>
              
              <table>
              <form action='accpaclist.php' method='POST'>
              <tr><td><b><u>ACCPAC IDs Data entry form</tr><tr></tr>
            <tr><td>Select program year:<td><select name='program' id='program'>
            <option value=''>-------</option>
            <option value='11'>BSC Year 1</option>
            <option value='10'>BSC Year 2</option>
            <option value='09'>BSC Year 3</option>
            <option value='08'>BSC Year 4</option>
            </select></tr>
            <tr><td><input name='search' type='submit' value='Search'></tr>
            </form>
            </table>
            <?php
            
            }
            ?>