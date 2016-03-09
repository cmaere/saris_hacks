<?php
session_start(); 
session_cache_limiter('nocache');
$_SESSION = array();
session_unset(); 
session_destroy(); 
echo '<meta http-equiv = "refresh" content ="0;	url = index.php">';
?>
