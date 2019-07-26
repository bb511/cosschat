<?php
	if(isset($_GET[unamee]))
	{
		echo "That username already exist. Please choose another one";
	}
	else if(isset($_GET[unknown]))
	{
		echo "An unknown error ocurred";
	}
?>
<html>
	<head>
		<title>A simple Webchat - Sign up</title>
		<link href="df.css" media=all type="text/css" rel="stylesheet" />
	</head>
		
	<body>
		<div id="signup">
			<form name="signupform" id="signupform" action="reg.php" method="post">
			<h1>Sign Up</h1>
			<p>
				<label for="user_login">Username<br />
				<input placeholder="username" type="text" name="username" id="user_signup" value="" /></label>
			</p>
			<p>
				<label for="user_login">Display Name<br />
				<input placeholder="username" type="text" name="displayname" id="user_displayname" value="" /></label>
			</p>
			<p>
				<label for="user_pass">Password<br />
				<input placeholder="password" type="password" name="pwd" id="user_pass" value="" /></label>
			</p>
			<p>
				<label for="user_pass">Confirm Password<br />
				<input placeholder="confirm password" type="password" name="cpwd" id="user_cpass" value="" /></label>
			</p>
	
			<p class="submit" style="margin-left:240px">
				<input type="submit" id="submit" class="btn" value="Sign Up" />
			</p>
			</form>
			<a href='index.php'>Login</a>
		</div>
		</body>
</html>