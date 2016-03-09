<form name="cha" action="testform.php" method="POST">
<input type="submit" name="cha1" value="A">
<input type="submit" name="cha2" value="B">
</form>


<?php
if(isset($_POST["cha1"]))
{

echo " this is A";

}
else if(isset($_POST["cha2"]))
{

	echo " this is B";
	
}
else
{
	echo " this is no where";

}


?>