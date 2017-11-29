<!--Filename: index.php
 HTML and CSS programmer: Khadijah Wali
    Backend programmer: Doug Leuben-->

<!-- Login Page -->
<html>

<!-- Header -->
<head>
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

<!-- Page title -->
<title>ITE 240 Waitlist</title>

<!-- Google API (for UMBC Sign in) -->
<script src="https://apis.google.com/js/platform.js" async defer></script>
<meta name = "google-signin-client_id" content="902091372770-jb35hjp2akn75ba68nh560i6sgr3qg0g.apps.googleusercontent.com">

<!-- Sign in JS -->
<script type="text/javascript">
//Sign in code
function onSignIn(googleUser)
{
	//Pull Profile Info
	var profile = googleUser.getBasicProfile();
	
	//Did they use a UMBC email, or a google email?
	var email = profile.getEmail();
	var name = profile.getName();
	if(!email.includes("@umbc.edu"))
	{
		//If they aren't using a UMBC account, log them out
		alert("Please use your myUMBC login, not a Google account");
		onSignOut();
	}
	
	//If they are using a UMBC email, send them to the next page!
	else
	{
		//Store email + Name
		document.googleInfoForm.email.value = email;
		document.googleInfoForm.userName.value = name;
		//POST the form (go to the next page)
		document.googleInfoForm.submit();
	}
}

//Sign out code
function onSignOut()
{
	var gAuth = gapi.auth2.getAuthInstance();
	gAuth.signOut().then(function()
	{
		location.reload();
	});
}
</script>

</head>

<!-- Body -->
<body>

<!--Center content and surround it in white box-->
<div class="bord">

<img src="logo.png" alt="Logo Goes here">
<p>

<!-- Sign in button -->
<center> <!--Extra center tag because this button doesn't center by itself-->
<div class="g-signin2" data-onsuccess="onSignIn"></div>
</center>

<!-- Khadijah: Remember that PHP is ALWAYS executed before JavaScript.
	This is because PHP runs on the server before the client ever gets
	the webpage, and JavaScript runs in the browser. So, a work around
	to pass variables from JavaScript to PHP is to use a POST form.
	We don't want this to be a LITERAL form, so I use input type
	"hidden" to keep the user from seeing anything (unless they view
	the source code, but who cares at that point). -->
<form id="googleInfoForm" name="googleInfoForm" method="post" action="join_list.php">
	<!-- This will be stored in the waitlist database -->
	<input type="hidden" name="email" id="email" value="" />
	<!-- This will only be used as flavortext for the user while using this app -->
	<input type="hidden" name="userName" id="userName" value="" />
</form>

<form action='waitlist.php' method='post'>
    <button type='submit'>View Waitlist</button>
</form>
</div>
</body>
</html>