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
<?php
if(isset($_GET['login'])){
//validate received get value
$user = addslashes($_GET['login']);
echo "Deleting user account - ".$user;
$qdelete="DELETE FROM security WHERE UserName = '$user'";
$dbdelete_user = mysql_query($qdelete)or die('Cannot delete this user account!!');

//after deleting go back to user list
echo '<meta http-equiv = "refresh" content ="0; url = adminmanageuser.php">';
}
?>
<?php
	# include the footer
	include('../footer/footer.php');
?>