<!-- Connection Test -->
<?php
	//Database Variables
	$server = "mysql6.gear.host";
	$user = "cmsc447";
	$pwd = "Ao8WXV~5nw~3";
	
	//Connect to the SQL DB
	$sqldb = new mysqli($server, $user, $pwd);
	
	//Check if the connection went through
	if($sqldb->connect_error)
	{
		echo "ERROR!";
	}
	else
	{
		echo "SUCCESS!";
	}
?>