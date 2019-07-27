<?php
	if(isset($_GET[invpass]))
	{
		echo "Invalid password!";
	}
	if(isset($_GET[invusr]))
	{
		echo "Invalid information! Please register <a href=signup.php>here</a> if you are not yet registered";
	}
?>
<html>
	<head>
		<title>A simple Webchat - Login</title>
		<link href="df.css" media=all type="text/css" rel="stylesheet" />
	</head>
		
	<body>
		<div id="login">
			<form name="loginform" id="loginform" action="reg.php" method="post">
			<h1>Log in</h1>
			<p>
				<label for="user_login">Username<br />
				<input placeholder="username" type="text" name="log" id="user_login" value="" /></label>
			</p>
			<p>
				<label for="user_pass">Password<br />
				<input placeholder="password" type="password" name="pwd" id="user_pass" value="" /></label>
			</p>
			<p class="submit" style="margin-left:240px">
				<input type="submit" id="submit" value="Log In" />
			</p>
			</form>
			<a href='signup.php'>Sign up here</a>
		</div>
		</body>
</html>