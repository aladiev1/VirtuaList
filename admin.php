<!-- Start user session -->
<?php

//Create a new session (if we need to)
if(session_id() == '' || !isset($_SESSION))
{
	//Start the session
	session_start();
}

?>

<html>

<head>

<!-- CSS -->
<style type="text/css">

/* Background */
body
{
	/* Color */
	background-color: #44768D;
	
	/* Font */
	font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Line Break */
hr
{
	/* Border/Line */
	border-color: #606060;
}

/* Controls */
input[type=button], select
{
	/* Color */
	color: #FFFFFF;
	background-color: #66AA44;
	
	/* Font Size */
	font-size: 105%;
	
	/* Box Model */
	padding: 0.4em 1.3em 0.4em 1.3em;
	
	/* Border */
	border-style: none;
	border-radius: 1em;
}

/* Search Buttons */
#searchButton
{
	/* Curve on one side */
	border-radius: 1em 0em 0em 1em;
	
	/* Make a bit taller */
	height: 2.2em;
}

/* Selector */
#selectVisual
{
	float: right;
}

/* Main Content Box */
#mainContent
{
	/* Shape and Color */
	border-radius: 1em;
	background-color: white;
	
	/* Border */
	border-style: solid;
	border-width: 1.5px;
	border-color: #606060;
	
	/* Box Model */
	margin: 3% 10% 10% 10%;
}

/* Controls Panel */
#controlsSection, #visualization
{
	/* Box Model */
	padding: 0% 10% 2% 10%;
}

/* Title Area */
#titleSection
{
	/* Color and Size */
	color: #474747;
	font-size: 220%;
	
	/* Alignment */
	text-align: center;
}

/* Student Lookup */

/* Student Searchbar */
#studentSearch, #taSearch
{
	/* Size */
	width: 20em;
	
	/* Font Size */
	font-size: 105%;
	
	/* Box Model */
	padding: 0.4em 1.3em 0.4em 1.3em;
}

/* Student Results */
#studentLookupResults
{
	/* Box Model */
	margin-top: 2em;
}

/* Table */
table, tr
{
	/* Color */
	background-color: #FFFFFF;
	
	/* Border */
	border: 1px solid black;
}
/* Even Rows */
#rowType0
{
	background-color: #DDDDDD;
}
/* Odd Rows */
#rowType1
{
	background-color: #BBBBBB;
}
td
{
	/* Text */
	text-align: center;
	
	/* Border */
	border-width: 0em 0.1em 0em 0em;
	
	/* Box Model */
	padding: 0.4em 0.6em 0.4em 0.6em;
}

</style>

<!-- JS -->
<script type="text/javascript">

/* Sets the "From" and "To" section to,
roughly, the current semester's timespan */
function SetCurrentSemester()
{
	//Get the current date
	var obj = new Date();
	
	//Month
	var month = "";
	var monthRaw = obj.getMonth();
	if(monthRaw < 10) { month = '0' + monthRaw; }
	else { month = monthRaw; }
	
	//Day
	var dayRaw = obj.getDate();
	var day = "";
	if(dayRaw < 10) {day = '0' + dayRaw; }
	else { day = dayRaw; }
	
	//Year
	var year = obj.getFullYear();
	
	//Combine them
	var today = month + '/' + day + '/' + year;
	console.log(today);
	
	//Setup Variables
	var fromDate = '';
	var toDate = '';
	
	/*If the date is between September and
	the end of the year, set it to Fall's range */
	if((monthRaw >= 9) && (monthRaw <= 12))
	{
		fromDate = year + '-09-01';
		toDate = year + '-12-31';
	}
	
	/* Otherwise, if it's January,
	set it to Winter's range */
	else if(monthRaw == 1)
	{
		fromDate = year + '-01-01';
		toDate = year + '-01-30';
	}
	
	/* Otherwise, if it's between the end of January
	and the end of May, it's Spring */
	else if((monthRaw >= 2) && (monthRaw <= 5))
	{
		fromDate = year + '-01-31';
		toDate = year + '-05-31';
	}
	
	/* Finally, if it's anything else, it's summer */
	else
	{
		fromDate = year + '-06-01';
		toDate = year + '-08-29';
	}
	
	//Set the Date Objects
	var fromSelect = document.getElementById("fromTime");
	console.log(fromSelect);
	fromSelect.value = fromDate;
	var toSelect = document.getElementsByName("toTime")[0];
	toSelect.value = toDate;
}

//Get the Enter Time
function GetEnterTime()
{
	return document.getElementById("fromTime").value;
}

//Get the Exit Time
function GetExitTime()
{
	return document.getElementById("toTime").value;
}

//Updates the Waitlist
function UpdateDIV(rootId, url)
{	
	console.log(url);
	//Cache reference to waitlist's DIV tag
	var root = document.getElementById(rootId);
	
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
	
	//Set Loading
	root.innerHTML = "Loading...";
	
	//Perform a GET Request
	myRequest.open("GET", url, true);
	myRequest.send();
}

//Switching between visualizations
function newVisual()
{
	//Get the value we selected
	var selector = document.getElementById("selectVisual");
	var selected = selector.options[selector.selectedIndex].innerHTML;
	
	//Hide all values we didn't select
	for(var i = 0; i < selector.options.length; i++)
	{
		console.log("i: " + i + " selected: " + selector.selectedIndex);
		
		//Pull the item
		var item = document.getElementById(selector.options[i].value); 
		
		//Set it's visibility
		item.style.display = (i == selector.selectedIndex) ? ("inline") : ("none");
	}
}

</script>

<!-- Page Title -->
<title>Admin Mode: ITE 240 Waitlist</title>

</head>

<body>

<div id="mainContent">

<!-- Title -->
<div id="titleSection">Analytics<br/><hr/></div>

<!-- Controls -->
<div id="controlsSection">

<!-- Date Controls -->
<b>From:</b> <input type="date" id="fromTime" name="fromTime"></input> 
<b>To:</b> <input type="date" id="toTime" name="toTime" />
<input type="button" value="Current Semester" onclick="SetCurrentSemester()" />

<!-- Visualization Selector -->
<select id="selectVisual" onchange="newVisual()">
	<option value="studentLookup">Student Lookup</option>
	<option value="taLookup">TA Lookup</option>
	<option value="reasonForVisit">Reason for Visit</option>
</select>

<br/>

<!-- End of Controls -->
</div>

<!-- Only one visualization will be live at a time -->
<div id="visualization">

<!-- Student Lookup -->
<div id="studentLookup">

<!-- Search Box -->
<center>
<input type="button" value="Search by E-Mail:" 
onclick="UpdateDIV('studentLookupResults', 'studentlookup.php?student='+document.getElementById('studentSearch').value+'&entertime='+encodeURIComponent(GetEnterTime())+'&exittime='+encodeURIComponent(GetExitTime())+'&lookupmode=student')" id="searchButton"/>
<input type="text" id="studentSearch"/>
</center>
<hr/>

<!-- Results -->
<center><div id="studentLookupResults"></div></center>

<!-- End of Student Lookup -->
</div>

<!-- TA Lookup -->
<div id="taLookup" style="display: none">

<!-- Search Box -->
<center>
<input type="button" value="Search by TA's E-Mail:" 
onclick="UpdateDIV('taLookupResults', 'studentlookup.php?student='+document.getElementById('taSearch').value+'&entertime='+encodeURIComponent(GetEnterTime())+'&exittime='+encodeURIComponent(GetExitTime())+'&lookupmode=ta')" id="searchButton"/>
<input type="text" id="taSearch"/>
</center>
<hr/>

<!-- Results -->
<center><div id="taLookupResults"></div></center>

<!-- End of TA Lookup -->
</div>

<!-- Reason For Visit -->
<div id="reasonForVisit">

<!-- End of Reason For Visit -->
</div>

<!-- End of Visualization Area -->
</div>

<!-- End of Main Content -->
</div>

</body>

</html>