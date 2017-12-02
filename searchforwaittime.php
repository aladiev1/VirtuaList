

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
if($_GET["student"] == "") {
	$query = $query . "EnterTime < '" . $_GET["exittime"] . "') AND (EnterTime > '" . $_GET["entertime"] . "') ORDER BY EnterTime DESC";
} else {
	$query = $query . (($_GET["lookupmode"] == "student") ? ("StudentEmail") : ("TA_ID"));
	$query = $query . " = '" . $_GET["student"] . "') AND (EnterTime < '" . $_GET["exittime"] . "') AND (EnterTime > '" . $_GET["entertime"] . "') ORDER BY EnterTime DESC";
}

//Fetch the Data
$fetchData = $mySQL->query($query);

//Handles the row that we're on (for alternating color scheme)
$rowNumber = 0;

//Iterate through each row
if($fetchData->num_rows > 0)
{	
	//Print the beginning of a table
	echo "<h2><b><u>Results</u></b></h2>";
	echo "<table>";
	
	//Set whether the TA's or Student's email will appear
	$tableHeaderEmail = ($_GET["lookupmode"] != "student") ? ("TA E-Mail") : ("Student E-Mail");
	
	//Print Header
	echo "<tr><td><b>Enter Time</b></td><td><b>Exit Time</b></td><td><b>Total Duration</b></td><td><b>Type of Help</b></td><td><b>";
	echo $tableHeaderEmail;
	echo "</td></b><td><b>TA Rating</b></td></tr>";
	echo "\n";
	
	while($row = mysqli_fetch_assoc($fetchData))
	{
		echo json_encode($row);
		echo "\n";

		//Increment the ID
		$rowNumber = ($rowNumber + 1) % 2;
		
		//Start the row
		echo "<tr id='rowType" . $rowNumber . "'>";
		
		//Print the Enter Time
		$enterTime = new DateTime($row["EnterTime"]);
		echo "<td id='sl_enterTime'>" . $row["EnterTime"] . "</td>";
		
		//Print the End Time
		$endTime = new DateTime($row["EndTime"]);
		echo "<td id='sl_exitTime'>" . $row["EndTime"] . "</td>";
	
		//Print Duration
		$duration = $enterTime->diff($endTime)->format('%hh %im');
		echo "<td id='sl_duration'>" . $duration . "</td>";
		
		//Print Topic
		echo "<td id='sl_topic'>" . $row["Topic"] . "</td>";
		
		//Print TA_ID
		if($_GET["lookupmode"] != "student")
		{ echo "<td id='sl_help'>" . $row["TA_ID"] . "</td>"; }
		else
		{ echo "<td id='sl_help'>" . $row["StudentEmail"] . "</td>"; }
		
		//Print TA Rating
		echo "<td id='sl_rating'>" . $row["TA_Rating"] . "/5 Stars</td>";
		
		//End the row
		echo "</tr>";
		echo "\n";
	}

	echo "</table>";
}

else
{ echo "No data found"; }


?>