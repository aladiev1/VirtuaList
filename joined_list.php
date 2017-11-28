<!-- Start user session -->
<?php

//Create a new session (if we need to)
if(session_id() == '' || !isset($_SESSION))
{
	//Start the session
	session_start();
}

?>

<!-- Joined the Waitlist -->
<html>

<head>

<title>ITE 240 Waitlist</title>

<!-- Khadijah: This page primarily runs on the server in PHP, attempting to update the
	database with your place in the waitlist, and the only thing the user sees is a confirmation,
	before they are redirected to the waitlist -->
<?php

//SQL Server Information (obfuscated from the user, since they won't see this tag)
$serverName = "mysql6.gear.host:3306";
$userName = "cmsc447";
$password = "Ao8WXV~5nw~3";
$databaseName = "cmsc447";

//Connect to the SQL Server
$mySQL = mysqli_connect($serverName, $userName, $password, $databaseName);

//Handle connection problems
if($mySQL->connect_error)
{
	die("Connection to the ITE 240 Waitlist Failed! " . $conn->connect_error);
}

//Insert Query
$timeStamp = date("Y-m-d H:i:s");
$query = "INSERT INTO `cmsc447`.`waitlist`
(`StudentEmail`,
`Topic`,
`ClassID`,
`EnterTime`)
VALUES
('" . $_SESSION["email"] . "',
'" . $_POST["topic"] . "',
" . $_POST["classID"] . ",
'" . $timeStamp . "')";

//Run the Query
if(mysqli_query($mySQL, $query))
{
	echo "Success!";
	
	//Redirect the user
	header("Location: waitlist.php");
	exit();
}
//The query failed
else
{
	echo "We couldn't add you to the waitlist. Please try again! Error: " . $sql . "<br>" . mysqli_error($mySQL);
}

//Close the connection
mysqli_close($mySQL);
?>

</head>

</html>