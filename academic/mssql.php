<?php
$data_source='saris_accpac';
$user='kcn\root';
$password='kcnroot';
// Connect to the data source and get a handle for that connection.
$conn=odbc_connect($data_source,$user,$password);
if (!$conn){
if (phpversion() < '4.0'){
exit("Connection Failed: . $php_errormsg" );
}
else{
exit("Connection Failed:" . odbc_errormsg() );
}
}
// This query generates a result set with one record in it.
$sql="SELECT 1 AS test_col";
# Execute the statement.
$rs=odbc_exec($conn,$sql);
// Fetch and display the result set value.
if (!$rs){
exit("Error in SQL");
}
while (odbc_fetch_row($rs)){
$col1=odbc_result($rs, "test_col");
echo "$col1\n";
}
// Disconnect the database from the database handle.
odbc_close($conn);
?>