

<?php

//Create a new session (if we need to)
if(session_id() == '' || !isset($_SESSION))
{
	//Start the session
	session_start();
}

?>
		
<?php

//SQL Server Information (obfuscated from the user, since they won't see this tag)
$serverName = "mysql6.gear.host:3306";
$userName = "cmsc447";
$password = "Ao8WXV~5nw~3";
$databaseName = "cmsc447";

//Connect to the SQL Server
$mySQL = mysqli_connect($serverName, $userName, $password, $databaseName);

//Handle connection issues
if($mySQL->connect_error)
{
	die("Connection to the ITE 240 Waitlist Failed! " . $conn->connect_error);
}

//Request the Waitlist
$query = "SELECT * FROM pastissues WHERE (";
$query = $query . (($_GET["lookupmode"] == "student") ? ("StudentEmail") : ("TA_ID"));
$query = $query . " = '" . $_GET["student"] . "') AND (EnterTime < '" . $_GET["exittime"] . "') AND (EnterTime > '" . $_GET["entertime"] . "') ORDER BY EnterTime DESC";

//Fetch the Data
$fetchData = $mySQL->query($query);

//Handles the row that we're on (for alternating color scheme)
$rowNumber = 0;

//Iterate through each row
if($fetchData->num_rows > 0)
{	
	$tableHeaderEmail = ($_GET["lookupmode"] == "student") ? ("TA E-Mail") : ("Student E-Mail");
	
	while($row = mysqli_fetch_assoc($fetchData))
	{
		echo json_encode($row);
		echo "\n";
	}
}

else
{ echo "Student doesn't exist. Is there a typo?"; }


?>