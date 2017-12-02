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
#studentSearch, #taSearch, #visitSearch, #waitSearch
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

.line-graph.path {
	stroke-width: 2;
	fill-opacity: 0;
}

.line-graph.circle {
	r: 4;
	stroke-width: 0;
}

.line-graph.x-axis {

}

.line-graph.y-axis {

}

.line-graph.label {
	font-size: 20;
}

.pie-chart.label {
	font-size: 20;
	font-weight: bolder;
}

.percent-bar.rect {
	rx: 6;
	ry: 6;
}

.percent-bar.rect.left {
	fill: red;
}

.percent-bar.rect.right {
	fill: blue;
}

.percent-bar.label.title {
	font-weight: bolder;
}

.axis path,
.axis line {
    fill: none;
    stroke: black;
    stroke-width: 1px;
    shape-rendering: crispEdges;
}

.no-select {
	-webkit-user-select: none;        
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none; 
}

</style>

<!-- JS -->
<script src="./lib/jquery-3.0.0.min.js"></script>
<script src="./lib/d3.min.js"></script>
<script src="./lib/d3-shape.min.js"></script>
<script src="./lib/d3.tip.min.js"></script>

<script type="text/javascript">

function drawPieChart(data) { //https://bl.ocks.org/mbostock/3887235

	var divName = "#visitResults";

	var displayWidth = $(divName).width();
	var displayHeight = $(divName).height();

	var graphWidth = displayWidth// - yAxisWidth;
	var graphHeight = displayHeight// - xAxisHeight;

	var outerRadius = graphWidth < graphHeight ? graphWidth / 2 : graphHeight / 2;
	var innerRadius = outerRadius - outerRadius / 3;

	var graphDisplayPort = d3.select(divName).append("svg")
			.attr('width', graphWidth)
			.attr('height', graphHeight)
		.append("g");

	var arcData = [];

	for(var i = 0; i < data.length; i++) {
		arcData.push(data[i].value);
	}

	var arcs = d3.pie()(arcData);

	var cx = displayWidth / 2;
	var cy = displayHeight / 2;

	for(var i = 0; i < arcs.length; i++) {
		var arc = d3.svg.arc()
			.innerRadius(innerRadius)
			.outerRadius(outerRadius)
			.padAngle(Math.PI / outerRadius * 2)
			.startAngle(arcs[i].startAngle)
			.endAngle(arcs[i].endAngle);

		graphDisplayPort.append("path")
			.attr("class", "arc")
			.attr("d", arc)
			.attr("fill", "rgb(" + Math.floor(Math.random()*255) + "," + Math.floor(Math.random()*255) + "," + Math.floor(Math.random()*255) + ")")
			.attr("transform", "translate(" + cx + "," + cy + ")");

		graphDisplayPort.append("text")
			.attr("class", "no-select pie-chart label")
			.text(data[i].label)
			.attr("transform", "translate(" + arc.centroid() + ")")
			.attr("x", function(d) {
				return cx - (this.getComputedTextLength() / 2);
			})
			.attr("y", cy)
			// .attr("cursor", "default")
	}
}

function drawLineGraph(div, data, xLabel, yLabel) {

	var divName = "#" + div;

	var minX = Infinity;
	var maxX = -1;

	var minY = Infinity;
	var maxY = -1;

	for(var i = 0; i < data.length; i++) {

		var datum = data[i];

		if(datum[0] < minX)
			minX = datum[0];
		if(datum[0] > maxX)
			maxX = datum[0];

		if(datum[1] < minY)
			minY = datum[1];
		if(datum[1] > maxY)
			maxY = datum[1];
	}

	if(minY == maxY) {
		minY -= minY / 10;
		maxY += minY / 10;
	} else {
		minY -= (maxY - minY) / 10;
		maxY += (maxY - minY) / 10;
	}

	if(minX == maxX) {
		var dif = 1000 * 60 * 60 * 24;

		minX -= dif;
		maxX += dif;
	} else {
		var dif = (maxX - minX) / 10;

		dif = Math.max(dif, 1000 * 60 * 60 * 24);

		minX -= dif;
		maxX += dif;
	}

	minY = Math.max(minY, 0);

	var yAxisWidth = 100;
	var xAxisHeight = 100;

	var startTime = 0, endTime = 500;

	var displayWidth = $(divName).width() - 50;
	var displayHeight = $(divName).height() - 50;

	var graphWidth = displayWidth - yAxisWidth;
	var graphHeight = displayHeight - xAxisHeight;

	var xScale = d3.time.scale()
		.domain([new Date(minX).setHours(0), new Date(maxX).setHours(0)])
		.range([0, graphWidth]);

	var xAxis = d3.svg.axis()
    	.scale(xScale)
    	.orient('bottom')
    	.ticks(5);

	var yScale = d3.scale.linear()
		.domain([minY, maxY])
		.range([graphHeight, 1]);

	var yAxis = d3.svg.axis()
    	.scale(yScale)
    	.orient('left')
    	.ticks(5)
    	.tickFormat(d3.format("d"));

    var yAxisDisplayPort = d3.select(divName).append("svg")
			.attr("width", yAxisWidth)
			.attr("height", graphHeight)
			.style("overflow", "visible")
		.append("g");

	yAxisDisplayPort.append("g")
		.attr("class", "line-graph y-axis axis")
		.attr("transform", "translate(" + (yAxisWidth) + "," + 0 + ")")
		.call(yAxis);

	var graphDisplayPort = d3.select(divName).append("svg")
			.attr('width', graphWidth)
			.attr('height', graphHeight)
		.append("g");

	var xAxisDisplayPort = d3.select(divName).append("svg")
			.attr("width", graphWidth)
			.attr("height", xAxisHeight)
			.style("padding-left", yAxisWidth)
			.style("overflow", "visible")
		.append("g");

	xAxisDisplayPort.append("g")
		.attr("class", "line-graph x-axis axis")
		.call(xAxis);

	var polygonData = [];

	polygonData.push({x:0, y:0});
	polygonData.push({x:graphWidth, y:0});
	polygonData.push({x:graphWidth, y:graphHeight});
	polygonData.push({x:0, y:graphHeight});
	polygonData.push({x:0, y:0});

	var polygonString = "";

	for(var q = 0; q < polygonData.length; q++) {
		polygonString += [(polygonData[q].x), polygonData[q].y].join(",") + " ";
	}

	data.sort(function (a,b) {

		if(a[0] > b[0]) {
			return 1;
		}
		else if(a[0] < b[0]){
			return -1;
		}
		else
			return 0;
	});


	drawLine(data, graphDisplayPort, xScale, yScale, "rgb(" + Math.floor(Math.random()*255) + "," + Math.floor(Math.random()*255) + "," + Math.floor(Math.random()*255) + ")");


	//http://bl.ocks.org/phoebebright/3061203
	yAxisDisplayPort.append("text")
		    .attr("class", "line-graph label")
            .attr("text-anchor", "middle")  
            .attr("transform", "translate("+ (yAxisWidth/2) +","+(graphHeight/2)+")rotate(-90)")
            .text(yLabel);

    xAxisDisplayPort.append("text")
		    .attr("class", "line-graph label")
            .attr("text-anchor", "middle") 
            .attr("transform", "translate("+ (graphWidth/2) +","+(xAxisHeight/2)+")") 
            .text(xLabel);
}


function drawLine(data, graph, xScale, yScale, color) {

	var poly = "";

	for(var i = 0; i < data.length; i++) {

		if(i == 0)
			poly = "M ";
		else
			poly += "L ";

		poly += [xScale(new Date(data[i][0]).setHours(0)), yScale(data[i][1])].join(",") + " ";

	}

	graph.append("path")
		.attr("class", "line-graph path")
		.attr("d", poly)
		// .attr("stroke-opacity", 1)
		.attr("stroke", color)
		// .attr("stroke-width", 2)
		// .attr("fill-opacity", 0);

	for(var i = 0; i < data.length; i++) {
		if(data[i][1] != 0) graph.append("circle")
			.attr("class", "line-graph circle")
			.attr("cx", xScale(new Date(data[i][0]).setHours(0)))
			.attr("cy", yScale(data[i][1]))
			// .attr("r", 5)
			// .attr('stroke-width', 0)
			.attr("fill", color);
	}
}

function drawPercentBar(divName, data) { //https://bl.ocks.org/mbostock/3887235

	var displayWidth = $(divName).width();
	var displayHeight = $(divName).height();

	var graphWidth = displayWidth// - yAxisWidth;
	var graphHeight = displayHeight// - xAxisHeight;

	var barHeight = graphHeight / 4;
	var barWidth = graphWidth * .9;

	var graphDisplayPort = d3.select(divName).append("svg")
			.attr('width', graphWidth)
			.attr('height', graphHeight)
		.append("g");

	var splitScale = d3.scale.linear()
		.domain([0, 1])
		.range([0, barWidth]);

	var split = splitScale(data[0] / (data[0] + data[1]))

	graphDisplayPort.append("rect")
		.attr("class", "percent-bar rect left")
		.attr("width", split - 4)
		.attr("height", barHeight)
		.attr("transform", "translate(" + ((graphWidth - barWidth) / 2) + "," + graphHeight / 4 + ")");

	graphDisplayPort.append("rect")
		.attr("class", "percent-bar rect right")
		.attr("x", split + 4)
		.attr("width", (barWidth - split))
		.attr("height", barHeight)
		.attr("transform", "translate(" + ((graphWidth - barWidth) / 2) + "," + graphHeight / 4 + ")");

	graphDisplayPort.append("text")
		.attr("class", "no-select percent-bar label title")
		.text("Avg. Wait Time")
		.attr("x", function(d) {
			return (graphWidth / 3) - (this.getComputedTextLength() / 2);
		})
		.attr("y", graphHeight * 0.75)

	graphDisplayPort.append("text")
		.attr("class", "no-select percent-bar label value")
		.text(function() {

			var time = data[0];

			str = Math.floor(time / (1000 * 60)) + " minutes";

			return str;
		})
		.attr("x", function(d) {
			return (graphWidth / 3) - (this.getComputedTextLength() / 2);
		})
		.attr("y", (graphHeight * 0.75) + 20)

	graphDisplayPort.append("text")
		.attr("class", "no-select percent-bar label title")
		.text("Avg. Help Duration")
		.attr("x", function(d) {
			return ((graphWidth / 3) * 2) - (this.getComputedTextLength() / 2);
		})
		.attr("y", graphHeight * 0.75)

	graphDisplayPort.append("text")
		.attr("class", "no-select percent-bar label value")
		.text(function() {
			var time = data[1];

			str = Math.floor(time / (1000 * 60)) + " minutes";

			return str;
		})
		.attr("x", function(d) {
			return ((graphWidth / 3) * 2) - (this.getComputedTextLength() / 2);
		})
		.attr("y", (graphHeight * 0.75) + 20)
}

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

function updateVisitFrequencyGraph(div, tableId, url) {

	var root = document.getElementById(div);

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

			var res = this.responseText.trim();

			var resArr = res.split("\n");

			var issues = [];

			var table = "";

			for(var i = 0; i < resArr.length; i++) {
				if(resArr[i][0] == "{")
					issues.push(JSON.parse(resArr[i]));
				else {
					table += resArr[i];
				}
			}

			document.getElementById(tableId).innerHTML = table;

			var data = [];

			var buckets = {};

			for(var i = 0; i < issues.length; i++) {
				var date = new Date(issues[i]["EnterTime"]);

				var key = "" + date.getFullYear() + date.getMonth() + date.getDate();

				if(buckets.hasOwnProperty(key)) {
					buckets[key].count++;
				}
				else {
					buckets[key] = {}
					buckets[key].count = 1;
					buckets[key].date = date;
				}

			}

			for(var b in buckets) {
				var bucket = buckets[b];
				var datum = [];

				datum.push(bucket.date.getTime());
				datum.push(bucket.count);

				data.push(datum);
			}

			data.sort(function (a,b) {
				return a[0] > b[0];
			});


			if(data.length != 1) { for(var i = 0; i < data.length; i++) {
				var datum = data[i];

				var date = new Date(datum[0]);

				date.setDate(date.getDate() + 1);

				if(i != data.length - 1) {
					var date2 = new Date(data[i+1][0]);
					if(date.getDate() != date2.getDate() || date.getFullYear() != date2.getFullYear() || date.getMonth() != date2.getMonth()) {
						data.splice(i+1, 0, [date.getTime(), 0]);
					}
				}
			} } else {
				var date = new Date(data[0][0]);
				date.setHours(0);

				var prev = new Date(date.getTime() - (1000 * 60 * 60 * 24));
				var next = new Date(date.getTime() + (1000 * 60 * 60 * 24));

				data.splice(0,0, [prev.getTime(), 0]);
				data.push([next.getTime(), 0]);

			}

			drawLineGraph(div, data, "Date", "Visits");

			//Set the waitlist's tag to the request content
			//root.innerHTML = this.responseText;

		}
	}
	
	//Set Loading
	root.innerHTML = "Loading...";
	
	//Perform a GET Request
	myRequest.open("GET", url, true);
	myRequest.send();
}

function updateVisitReasonGraph(tableId, url) {

	var root = document.getElementById("visitResults");

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

			var res = this.responseText.trim();

			var resArr = res.split("\n");

			var issues = [];

			var table = "";

			for(var i = 0; i < resArr.length; i++) {
				if(resArr[i][0] == "{")
					issues.push(JSON.parse(resArr[i]));
				else {
					table += resArr[i];
				}
			}

			document.getElementById(tableId).innerHTML = table;

			var data = [];

			var buckets = {};

			for(var i = 0; i < issues.length; i++) {
				var key = issues[i]["Topic"];

				if(buckets.hasOwnProperty(key)) {
					buckets[key]++;
				}
				else {
					buckets[key] = 1;
				}
			}

			for(var b in buckets) {
				var bucket = buckets[b];

				data.push({label:b, value:bucket});
			}

			if(data.length > 0)
				drawPieChart(data);
			else
				root.innerHTML = "No data found";

		}
	}
	
	//Set Loading
	root.innerHTML = "Loading...";
	
	//Perform a GET Request
	myRequest.open("GET", url, true);
	myRequest.send();
}

function updateWaitTimeGraph(tableId, url) {

	var root = document.getElementById("waitResults");

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

			var res = this.responseText.trim();

			var resArr = res.split("\n");

			var issues = [];

			var table = "";

			for(var i = 0; i < resArr.length; i++) {
				if(resArr[i][0] == "{")
					issues.push(JSON.parse(resArr[i]));
				else {
					table += resArr[i];
				}
			}

			document.getElementById(tableId).innerHTML = table;

			var totals = [0, 0];
			var num = 0;

			for(var i = 0; i < issues.length; i++) if(issues[i]["TA_HelpBegin"] != null && issues[i]["TA_HelpBegin"] != "null" &&
													  issues[i]["EndTime"] != null && issues[i]["EndTime"] != "null")  {

				var start = new Date(issues[i]["EnterTime"]);
				var help = new Date(issues[i]["TA_HelpBegin"]);
				var end = new Date(issues[i]["EndTime"]);

				var wait = help.getTime() - start.getTime();
				var duration = end.getTime() - help.getTime();

				totals[0] += wait;
				totals[1] += duration;

				num++;
			}

			if(num > 0 ) {
				var data = [totals[0] / num, totals[1] / num];

				drawPercentBar("#waitResults", data)
			}

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

function onEnterStudentFrequency(e, id) {
	if(e.key == "Enter")
		document.getElementById(id).onclick();
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
	<option value="waitTime">Wait vs Help Time</option>
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
<input id="getFrequencyStudentButton" type="button" value="Search by Student's E-Mail:" 
onclick="updateVisitFrequencyGraph('studentLookupResults', 'studentLookupTable', 'searchforfrequency.php?student='+document.getElementById('studentSearch').value+'&entertime='+encodeURIComponent(GetEnterTime())+'&exittime='+encodeURIComponent(GetExitTime())+'&lookupmode=student')" id="searchButton"/>
<input type="text" id="studentSearch" onkeypress="onEnterStudentFrequency(event, 'getFrequencyStudentButton')"/>
</center>
<hr/>

<!-- Results -->
<center><div id="studentLookupResults" style="width:1000px; height:500px;"></div></center>

<center><div id="studentLookupTable"></div></center>

<!-- End of Student Lookup -->
</div>

<!-- TA Lookup -->
<div id="taLookup" style="display: none">

<!-- Search Box -->
<center>
<input id="getFrequencyTAButton" type="button" value="Search by TA's E-Mail:" 
onclick="updateVisitFrequencyGraph('taLookupResults', 'taLookupTable', 'searchforfrequency.php?student='+document.getElementById('taSearch').value+'&entertime='+encodeURIComponent(GetEnterTime())+'&exittime='+encodeURIComponent(GetExitTime())+'&lookupmode=ta')" id="searchButton"/>
<input type="text" id="taSearch" onkeypress="onEnterStudentFrequency(event, 'getFrequencyTAButton')"/>
</center>
<hr/>

<!-- Results -->
<center><div id="taLookupResults" style="width:1000px; height:500px;"></div></center>

<center><div id="taLookupTable"></div></center>

<!-- End of TA Lookup -->
</div>

<!-- Reason For Visit -->
<div id="reasonForVisit" style="display:none;">

<center>
<input id="getReasonsButton" type="button" value="Search by Student E-Mail:" 
onclick="updateVisitReasonGraph('visitsTable','searchforreason.php?student='+document.getElementById('visitSearch').value+'&entertime='+encodeURIComponent(GetEnterTime())+'&exittime='+encodeURIComponent(GetExitTime()))" id="searchButton"/>
<input type="text" id="visitSearch" onkeypress="onEnterStudentFrequency(event, 'getReasonsButton')"/>
</center>
<hr/>

<!-- Results -->
<center><div id="visitResults" style="width:1000px; height:500px;"></div></center>

<center><div id="visitsTable"></div></center>

<!-- End of Reason For Visit -->
</div>

<!-- Reason For Visit -->
<div id="waitTime" style="display:none;">

<center>
<input id="getWaitButton" type="button" value="Search by Student E-Mail:" 
onclick="updateWaitTimeGraph('waitTable', 'searchforwaittime.php?student='+document.getElementById('waitSearch').value+'&entertime='+encodeURIComponent(GetEnterTime())+'&exittime='+encodeURIComponent(GetExitTime()))" id="searchButton"/>
<input type="text" id="waitSearch" onkeypress="onEnterStudentFrequency(event, 'getWaitButton')"/>
</center>
<hr/>

<!-- Results -->
<center><div id="waitResults" style="width:1000px; height:300px;"></div></center>

<center><div id="waitTable"></div></center>
<!-- End of Reason For Visit -->
</div>

<!-- End of Visualization Area -->
</div>

<!-- End of Main Content -->
</div>

</body>

</html>