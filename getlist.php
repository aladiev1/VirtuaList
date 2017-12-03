<!-- USERS SHOULD NEVER EVER EVER EVER EVER EVER SEE THIS PAGE!
	THIS IS A HACK TO MAKE THE WAITLIST WORK IN JAVASCRIPT.
		YOU SHOULDN'T SEE THIS COMMENT BUT IF YOU DO, HELLO--
		<3 CMSC 447 Fall 17 -->
		
<!-- Start user session -->
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
$query = "SELECT * FROM waitlist WHERE ClassID = " . $_GET["classID"] . " ORDER BY EnterTime ASC";

//Fetch the Data
$fetchData = $mySQL->query($query);

//Iterate through each row
if($fetchData->num_rows > 0)
{	
	//Print the beginning of a table
	echo "<table>";
  
	echo "<tr>";
  
	//print headers for table
	echo "<td id='email'>" . "Email" . "</td>";
	echo "<td id='topic'>" . "Topic" . "</td>";
	echo "<td id='EnterTime'>" . "Enter Time" . "</td>";
  
	echo "</tr>";
	
	//Set a variable for whether or not to print the help button
	$canHelp = true;

	//NOTE: here you would check to see if there's any data
	//if so print the row with the same format as below 
	//except with an extra tag for the first guy in the list
	//and then enter this loop
	  
	while($row = mysqli_fetch_assoc($fetchData))
	{		
		//Start the row
		echo "<tr>";
		
		//Print the Email
		echo "<td id='email'>" . $row["StudentEmail"] . "</td>";
		
		//Print the Topic
		echo "<td id='topic'>" . $row["Topic"] . "</td>";
		
		//Print the ClassID (Don't think we need this, since we're only fetching one list at a time)
		//echo "<td id='ClassID'>" . $row["ClassID"] . "</td>";
		
		//Print the Enter Time
		echo "<td id='EnterTime'>" . $row["EnterTime"] . "</td>";
    
		//Am I a TA or an admin for this section?
		if(isset($_SESSION["accessLevel"]))
		{
			if(($_SESSION["accessLevel"] == $_GET["classID"]) || ($_SESSION["accessLevel"] == 100))
			{
				//Print the Help button
				if($canHelp)
				{
					//Display the button...
					echo "<td id='TAColumn' class='" . $row["HelpID"] . "'>";
					echo "<input id='TAOkay' type='button' value='Help' onclick='HelpPerson(" . $row["HelpID"] . ")'/>";
					echo "</td>";
					
					//...and don't do it again
					$canHelp = false;
				}
				
				//Print the Remove button
				echo "<td id='TAColumn' class='" . $row["HelpID"] . "'>";
				echo "<input id='TARemove' type='button' value='Remove' onclick='RemovePerson(" . $row["HelpID"] . ")'/>";
				echo "</td>";
			}
		}
		
		//End the row
		echo "</tr>";

	}
	
	//And finish the table!
	echo "</table>";
}

else
{ echo "The Waitlist is Empty!"; }


?>