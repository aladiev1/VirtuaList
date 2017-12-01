<!--ATTENTION DOUG:
    backend functionalities that must be added
    -removing the student from the waitlist when they arrive at this page
    -printing the appropiate information like name (email?) and subject of meeting
    -Possibly? calculating total time session took taken from start time and when
     the finish helping button is pressed
    -If the person logged in is not a TA, force em back to the login page-->

<html>
<head
<meta charset="utf-8" />

<link rel="stylesheet" href="style2.css">
<!--<style>

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



</style>-->

<title>TA-Help Student</title>
</head>
<body>

<!--Center content and surround it in white box-->
<div class="bord">
<h2>You are Helping:<br>
<!--Retrieve from button value--></h2>
<hr>
</center>

<!--Are we still keeping track of total time a meeting takes? if not we can remove this-->
Start Time: 
<!--Timestamp will be used to set start time.-->


<hr>
Topic: <br>
<!--Topic will be retrived from database-->

<p>

<hr>

<form action='waitlist.php' method='post'><button type='submit'> Finish Helping </button></form>
</div>
</body>
<html>