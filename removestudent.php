<?php

//Create a new session (if we need to)
if(session_id() == '' || !isset($_SESSION))
{
	//Start the session
	session_start();
}

?>
		
<?php

//Are you supposed to be here?!?
if(isset($_SESSION["accessLevel"]))
{
	if($_SESSION["accessLevel"] != 0)
	{
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
		
		//Check if the current user has the ability to delete this user
		if($_GET["mode"] != "finish")
		{
				$query = "SELECT * FROM waitlist WHERE HelpID = '" . $_GET["helpID"] . "'";
				
				//Get the result
				$fetchData = $mySQL->query($query);

				//Did we get anything? (we should've)
				if($fetchData->num_rows > 0)
				{
					//Get the row
					$row = mysqli_fetch_assoc($fetchData);
					
					//Is our accessLevel equal to the classID?
					if($_SESSION["accessLevel"] == $row["ClassID"])
					{
						//Are we deleting them, or moving them to pastissues?
						if($_GET["mode"] == "remove")
						{
							//Okay, then we can remove them
							$query = "DELETE FROM waitlist WHERE HelpID='" . $_GET["helpID"] . "'";
							$mySQL->query($query);
						}
						//We're just moving them to past issues
						else
						{
							//Insert into past issues...
							$timeStamp = date("Y-m-d H:i:s");
							$query = "INSERT INTO pastissues
							(`StudentEmail`,
							`Topic`,
							`ClassID`,
							`EnterTime`,
							`TA_HelpBegin`,
							`TA_ID`)
							VALUES
							('" . $row["StudentEmail"] . "',
							'" . $row["Topic"] . "',
							" . $row["ClassID"] . ",
							'" . $row["EnterTime"] . "',
							'" . $timeStamp . "',
							'" . $_SESSION["email"] . "')";
							$mySQL->query($query);
							
							//Grab the auto-generated id
							$query = "SELECT IssueID FROM pastissues WHERE TA_HelpBegin='" . $timeStamp . "' AND TA_ID='" . $_SESSION["email"] . "'";
							$fetchData = $mySQL->query($query);
							$IssueID = -1;
							if($fetchData->num_rows > 0)
							{
								$row = mysqli_fetch_assoc($fetchData);
								$IssueID = $row["IssueID"];
							}
							
							//...and delete from waitlist
							$query = "DELETE FROM waitlist WHERE HelpID='" . $_GET["helpID"] . "'";
							$mySQL->query($query);
							
							//and finally, echo the output
							echo $IssueID;
						}
					}
				}
		}
		else
		{
			//Are we allowed to make this edit?
			$query = "SELECT ClassID FROM pastissues WHERE IssueID = '" . $_GET["helpID"] . "'";
	
			//Get the result
			$fetchData = $mySQL->query($query);

			//Did we get anything? (we should've)
			if($fetchData->num_rows > 0)
			{
				//Get the row
				$row = mysqli_fetch_assoc($fetchData);
				
				//Is our accessLevel equal to the classID?
				if($_SESSION["accessLevel"] == $row["ClassID"])
				{
					//Run the query
					$timeStamp = date("Y-m-d H:i:s");
					$query = "UPDATE pastissues SET EndTime='". $timeStamp . "' WHERE IssueID='" . $_GET["helpID"] . "'";
					$mySQL->query($query);
					echo "Success.";
				}
				else
				{
					echo "You shouldn't be here";
				}
			}
			else
			{
				echo "You shouldn't be here.";
			}
		}
	}
}
else
{
	echo "You should not be here.";
}
?>