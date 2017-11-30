<!-- Start user session -->
<?php

//Create a new session (if we need to)
if(session_id() == '' || !isset($_SESSION))
{
	//Start the session
	session_start();
}

?>

<!-- Khadijah: Here's the trick. This page has 0% PHP (outside of saving the session, as seen above)
	The core of the code is a javascript function that fires every few seconds, requesting a different
	php file from our webserver (it's called "getlist.php") that will return the information for the selected
	class' waitlist as an html <table>. The page getlist.php is NOT NOT NOT intended to be accessed by the user,
	since it returns very little on it's own. This page will have a CSS file that will format the table, so each
	row looks like it's own block instead of an ugly "html default" table. -->

<!-- The Waitlist -->
<html>

<!-- Header -->
<head>
<meta charset="utf-8" />

<!-- Page title -->
<title>ITE 240 Waitlist</title>

<link rel="stylesheet" href="style2.css">

<!--

<!--
<style>
/* white block of page */
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

/* yellow background */
body {
    background-color: #f5ca5c;
    font-family: verdana;
    font-size: 15px;
    white-space:no-wrap;
}

/* left aligning stuff inside border */
div.enter {
  display: inline-block;
  text-align: left;
  float: left;
  position: relative;
  padding: 8px 30px;
}

/* Green buttons and dropdowns */
button, select {
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

/* formatting of table */
 table {
            width: 95%;
            border: 1px solid;
            text-align: left;
            margin: auto;
        }

        th {
            padding: 15px;
            text-align: left;
        }

        td {
            border-bottom: 1px solid;
            padding: 10px;
            background: white;
            
			/* NOTE: here there should be special formatting for a specific tag only the first item in the list has */
        }
</style>
-->

</head>

<!-- Declaring a function to pull from the waitlist -->
<script type="text/javascript">

//Updates the Waitlist
function GetWaitlist()
{
	//Cache the reference to the dropdown
	var dropdown = document.getElementsByTagName("select")[0];
	
	//Cache reference to waitlist's DIV tag
	var root = document.getElementById("waitlist");
	
	//Get the current class ID
	var classID = parseInt(dropdown.options[dropdown.selectedIndex].value);
	
	//Prepare the Request
	var myRequest;
	if(window.XMLHttpRequest)
	{
		//Works on Modern Browsers (Chrome, Firefox, Etc.)
		myRequest = new XMLHttpRequest();
	}
	else
	{
		//Works on Older Browsers (IE, Some Mobile Browsers, etc.)
		myRequest = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	//The Event when the request is complete
	myRequest.onreadystatechange = function()
	{
		//Did it go through?
		if(this.readyState == 4 && this.status == 200)
		{
			//Clear out any existing content
			root.innerHTML = "";
			
			//Set the waitlist's tag to the request content
			root.innerHTML = this.responseText;
		}
	}
	
	//Perform a GET Request
	myRequest.open("GET", "getlist.php?classID="+classID, true);
	myRequest.send();
	
}

//When the window has loaded, run GetWaitlist()
window.onload = function()
{
	//Run it initially
	GetWaitlist();
	//Then every 15 seconds
	setInterval(GetWaitlist, 15000);
}
</script>

</head>

<body>
<!--Header for webpage--> 
<!--Center content and surround it in white box-->
<div class="bord">

<h2>Waitlist</h2>


<!-- Class Dropdown -->
<!-- Classes are stored as single digit values, to save SQL server space
	(vs. storing the whole class name "CMSC202") -->
  <div class="enter">
  <h3>Select which class to view:</h3>

<select name="classSelector" onchange="GetWaitlist()">
	<option value="1">CMSC201</option>
	<option value="2">CMSC202</option>
	<option value="3">CMSC341</option>
</select>
</div>
<!-- Line Break -->
<p>

<!-- This is where the waitlist will go -->
<div class="section">
<div id='waitlist'></div>
</div>

<!-- Line Break -->
<br />
<!--Take student back to sign in page-->
<div class="enter">
<form action='index.php' method='post'>
<button type='submit'>Return to Login</button>
</form>
</div>
</div>
</body>

</html>