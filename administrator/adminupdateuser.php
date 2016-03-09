<?php
require_once('../Connections/zalongwa.php'); 
$position=addslashes($_POST['position']);
$priv=addslashes($_POST['priv']);
$login=addslashes($_POST['login']);
$sql="UPDATE security Set Position='$position', AuthLevel='$priv' WHERE UserName='$login'";
$result = @mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
echo '<meta http-equiv = "refresh" content ="0; 
	url = adminmanageuser.php?content='.$login.'">';
	exit;
mysql_free_result($result);
mysql_close($zalongwa);
?>