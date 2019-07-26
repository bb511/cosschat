<?php
	ob_start();
	include('ext.inc');
?>


<?php
	//Log in
	if(isset($_POST['log']))
	{
		$con = mysql_connect($sql_server, $sql_username, $sql_password);
		$query = "SELECT * FROM ". $sql_database_name .".reg_info WHERE username='%s'";
		$query = sprintf($query, mysql_real_escape_string(stripslashes($_POST['log'])));
		$result = mysql_query($query, $con);
		if(mysql_num_rows($result) > 0)//Username found
		{
			$row = mysql_fetch_assoc($result);
			if (crypt(($_POST['pwd']), $row[password]) == $row[password]) {
				//Correct info
				session_start();
				$_SESSION['uid'] = $row[id];
				$_SESSION['username'] = $_POST['log'];
				header("Location: mpage.php");
				ob_end_flush();
				exit;
			}
			else
			{
				//Invalid password
				header('Location: index.php?invpass=0');
				ob_end_flush();
				exit;
			}
		}
		else
		{
			//Not registered
			header('Location: index.php?invusr=0');
			ob_end_flush();
			exit;
		}
	}
	
	//Logout
	if(isset($_GET[logout]))
	{
		session_start();

		// Unset all of the session variables.
		$_SESSION = array();

		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get("session.use_cookies")) 
		{
			$params = session_get_cookie_params(); setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		}
		// Finally, destroy the session.
		session_destroy();
		header("Location: index.php");
		ob_end_flush();
		exit;

	}
	
	//Signup
	if(isset($_POST['username']))
	{
		//if database is not yet created
		$con = mysql_connect($sql_server, $sql_username, $sql_password);
		$query = "CREATE DATABASE IF NOT EXISTS ". $sql_database_name;
		mysql_query($query, $con);
		
		//Create tables if they dont exist
		$query = "CREATE TABLE IF NOT EXISTS " . $sql_database_name . ".reg_info (id INT NOT NULL AUTO_INCREMENT, username VARCHAR(12) NOT NULL, displayname VARCHAR(12) NOT NULL, password VARCHAR(120) NOT NULL, INDEX (id), UNIQUE(username)) ENGINE = InnoDB";
		mysql_query($query, $con);
		
		$query = "CREATE TABLE IF NOT EXISTS " . $sql_database_name . ".chat (chat_id INT NOT NULL AUTO_INCREMENT, sender_id INT NOT NULL, receiver_id INT NOT NULL, content LONGTEXT NULL, time TIMESTAMP NOT NULL, status VARCHAR(20) NOT NULL DEFAULT 'Not Read', mime VARCHAR(20) NOT NULL DEFAULT 'chat', INDEX (chat_id)) ENGINE = InnoDB";
		mysql_query($query, $con);
		
		$query = "CREATE TABLE IF NOT EXISTS " . $sql_database_name . ".archive (chat_id INT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, content LONGTEXT NULL, time TIMESTAMP NOT NULL, status VARCHAR(20) NOT NULL, mime VARCHAR(20) NOT NULL, INDEX (chat_id)) ENGINE = InnoDB";
		mysql_query($query, $con);
		
		$query = "CREATE TABLE IF NOT EXISTS " . $sql_database_name . ".online (id INT NOT NULL AUTO_INCREMENT, time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, INDEX (id))";
		mysql_query($query, $con);
		
		
		//Username and others should be checked for duplicate here
		$query = "SELECT * FROM " . $sql_database_name . ".reg_info WHERE username='%s'";
		$query = sprintf($query, mysql_real_escape_string(stripslashes($_POST['username'])));
		$result = mysql_query($query, $con);
		if(mysql_num_rows($result) > 0)
		{
			header("Location: signup.php?unamee");
			ob_end_flush();
			exit;
		}
		
		$query = "INSERT INTO " . $sql_database_name . ".reg_info (username, displayname, password) VALUES ('%s', '%s', '%s')";
		$password = crypt($_POST['pwd']); // let the salt be automatically generated
		$query = sprintf($query, mysql_real_escape_string(stripslashes($_POST['username'])), mysql_real_escape_string(stripslashes($_POST['displayname'])), $password);
		if(mysql_query($query, $con))
		{
			$query = "SELECT id FROM ". $sql_database_name .".reg_info WHERE username='%s'";
			$query = sprintf($query, mysql_real_escape_string(stripslashes($_POST['username'])));
			$result = mysql_query($query, $con);
			$row = mysql_fetch_assoc($result);
			
			//Register the user into the online table
			$query = "INSERT INTO " . $sql_database_name . ".online (id, time) VALUES ('%s',NOW())";
			$query = sprintf($query, $row[id]);
			mysql_query($query, $con);
			
			//start the session
			session_start();
			$_SESSION['uid'] = $row[id];
			$_SESSION['username'] = $_POST['username'];
			header("Location: mpage.php");
			ob_end_flush();
			exit;
		}
		else
		{
			echo "An error occured!" . mysql_error();
			exit;
		}
	}
	
	//none
	$location = "Location: " . $_SERVER['HTTP_REFERER'] . "?unknown";
	header($location);
	ob_end_flush();
?>

<?php
	ob_end_flush();
?>