<?php
require "config.php";

//Check for presence of posted data
if(empty($_POST['name']))
{
	echo "No User Input";
	header('HTTP/1.0 403 Forbidden');
	exit(1);
}

$stream = $_POST['name'];
$password = $_POST['pass'];

//Checks that the entry exists in the directory, and checks the password against the list.
if ($streamlist[$stream] && ($savedpasswords[$stream]==$password || $savedpasswords[$stream]=="nopass"))
{
	echo "Stream and Password OK!";
}
else
{
	echo "Stream or Password Incorrect";
	header('HTTP/1.0 403 Forbidden');
	exit(1);
}
?>
