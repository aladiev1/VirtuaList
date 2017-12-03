<!--Filename: join_list.php
    Join list allows students to add themselves to the waitlist-->

<!-- Start user session -->
<?php

//Create a new session (if we need to)
if(session_id() == '' || !isset($_SESSION))
{
	//Start the session
	session_start();
}

?>

<!-- Login Page -->
<html>

<!-- Header -->
<head>
<meta charset="utf-8" />

<!-- Page title -->
<title>ITE 240 Waitlist</title>

<link rel="stylesheet" href="style_general.css">

<!--

<!--
<style>
div.bord {
    border-style: solid;
    border-width: 1px;
    border-radius: 5px;
    background: #fafafa;
    width:70%;
    height:80%;
    text-align: center;
    margin: auto;
    
}

body {
    background-color: #f5ca5c;
    font-family: verdana;
    font-size: 15px;
    white-space:no-wrap;
}

div.enter {
  display: inline-block;
  text-align: left;
  float: left;
  position: relative;
  padding: 8px 30px;
}

button {
    background-color: #66aa44; /* Green */
    border: none;
    color: white;
    padding: 8px 30px;
    text-align: center;
    display: inline-block;
    font-size: 16px;
    border-radius: 5px;
    white-space:normal; 
}
</style>
-->

</head>

<!-- Store PHP -->
<?php
	
	//Your access level will go here
	$accessLevel = 0;
	
	//If the user properly posted here... (passed google auth)
	if(!empty($_POST))
	{
		//Promote POST variables to SESSION variables
		$_SESSION["email"] = $_POST["email"];
		$_SESSION["userName"] = $_POST["userName"];
		
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

		//Request the user's access level
		$query = "SELECT AccessLevel FROM userinfo WHERE Email = '" . $_SESSION["email"] . "'";
		
		//Fetch the Data
		$fetchData = $mySQL->query($query);

		//Iterate through each row
		if($fetchData->num_rows > 0)
		{
			//Get the row
			$row = mysqli_fetch_assoc($fetchData);
			
			//Print the access level
			$accessLevel = $row["AccessLevel"];
			
			//Store as SESSION variable
			$_SESSION["accessLevel"] = $accessLevel;
		}
		else
		{
			//Set to zero
			$accessLevel = 0;
			
			$_SESSION["accessLevel"] = 0;
		}
	}
	//Hey wait- they shouldn't be here!?! Kick em out!
	else
	{
		//(currently this isn't working quite right...I think it's the redirect)
		session_destroy();
		alert("Please sign in before using the waitlist");
		window.location.replace("my.umbc.edu");
	}
	
	/* Now that we stored the POSTed variables as
	SESSION variables, we can use them directly in our 
	JavaScript code, since PHP runs first! 
	
	From now on, ONLY use SESSION variables and NOT POST
	variables */

?>

<!-- Pass values to JavaScript -->
<script type="text/javascript">
	//Note we'll have to do this on each page...
	//...since JS doesn't have cross page variables (that I know of?)
	var userName = <?php echo $_SESSION["userName"]; ?>;
	var email = <?php echo $_SESSION["email"]; ?>;
</script>

</head>

<!-- Body -->
<body>

<!--Center content and surround it in white box-->
<div class="bord">

<!-- Welcome Message (example) -->
<h2 name="welcomeMessage" id="welcomeMessage">Welcome <?php echo $_SESSION["userName"]; ?>!</h2>

<h3>Please specify what you need help with.</h3>

<?php
	//If you're a TA, set your accessLevel's class name
	$accessText = "";
	switch($accessLevel)
	{
		//201
		case 1:
		{
			$accessText = "CMSC 201";
			break;
		}
		//202
		case 2:
		{
			$accessText = "CMSC 202";
			break;
		}
		//341
		case 3:
		{
			$accessText = "CMSC 341";
			break;
		}
	}
	
	//Determine whether to print something
	if($accessLevel == 100) //Admin
	{
		echo "<h6>You are an Administrator, and can edit all waitlists.</h6>";
	}
	else if($accessLevel > 0) //TA
	{
		echo "<h6>You are a TA for " . $accessText . ", and can edit that waitlist</h6>";
	}
?>

<!-- Give user option to view waitlist without joining it -->
<form action='waitlist.php' method='post'>
    <button type='submit'>View Waitlist</button>
</form>

<!--Have the following content left aligned (not currently working for some reason?)-->
<hr>
<!-- Join Waitlist form -->
<form action="joined_list.php" method="post">

<!-- Class Dropdown -->
<!-- Classes are stored as single digit values, to save SQL server space
	(vs. storing the whole class name "CMSC202") -->
Course:<br>
<select name="classID">
	<option value="1">CMSC201</option>
	<option value="2">CMSC202</option>
	<option value="3">CMSC341</option>
</select>
<br />

<!-- Topic -->
<!--Topic options are not numbered because they are more likely to change
    Eg. one sememster maybe there are no labs or one more project than normal etc.-->
Topic:<br>
<select name="topic">
	<option>Project 1</option>
	<option>Project 2</option>
	<option>Project 3</option>
	<option>Project 4</option>
	<option>Project 5</option>
	<option>Lab</option>
	<option>Exam</option>
	<option>Grade</option>
	<option>Other</option>
</select>
<br />

<!-- Submit Form Button -->
<button type='submit'> Enter Waitlist! </button>

</form>
</div> <!--Close bord div-->

</body>
</html>