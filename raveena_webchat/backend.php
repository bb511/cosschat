<?php
	session_start();
	include('ext.inc');
	if(!isset($_SESSION['uid']))
	{
		echo "Verification required";
		exit;
	}
?>

<?php
	//New message, store in chat
	if(isset($_GET[send]))
	{
		$con = mysql_connect($sql_server, $sql_username, $sql_password);
		$query = "INSERT INTO " . $sql_database_name . ".chat (sender_id, receiver_id, content, time) VALUES ('%s', '%s', '%s', '%s')";
		$query = sprintf($query, mysql_real_escape_string($_GET[uid]), mysql_real_escape_string($_GET[oid]), mysql_real_escape_string($_GET[msg]), date('Y-m-d H:i:s', strtotime('now')));
		mysql_query($query, $con);
		
		$msg = "{'id':'%s','displayname':'%s','content':'%s','time':'%s'}";
		$msg = sprintf($msg, $_GET[oid], $_SESSION['username'], $_GET[msg],  date('l dS \o\f F Y h:i:s A', strtotime("now")));
		echo $msg;
	}

	//Requesting for new messages
	if(isset($_GET[ping]))
	{
		$chat = "{'a0':";
		$con = mysql_connect($sql_server, $sql_username, $sql_password);
		$query = "SELECT * FROM " . $sql_database_name . ".chat WHERE receiver_id = %s AND sender_id = %s AND status = 'Not Read' ORDER BY chat.chat_id DESC";
		$query = sprintf($query, mysql_real_escape_string($_GET[uid]), mysql_real_escape_string($_GET[oid]));
		$result = mysql_query($query, $con);
		if(mysql_num_rows($result) > 0)
		{
			$chat .= mysql_num_rows($result);
			$i = 1;
			while($row = mysql_fetch_assoc($result))
			{
				$chat .=  ",'a" . $i . "':";
				$chatx = "{'id':'%s','displayname':'%s','content':'%s','time':'%s'}";
				
				//Finding the display name
				$query = "SELECT displayname FROM " . $sql_database_name . ".reg_info WHERE id=%s";
				$query = sprintf($query, $row[sender_id]);
				$resultx = mysql_query($query, $con);
				$rowa = mysql_fetch_assoc($resultx);
				
				$chatx = sprintf($chatx, $row[sender_id], $rowa[displayname], $row[content], date('l dS \o\f F Y h:i:s A', strtotime($row[time])));
				
				//Move read to archive and mark as read
				$query = "INSERT INTO " . $sql_database_name . ".archive(chat_id, sender_id, receiver_id, content, time, status, mime) VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s')";
				$query = sprintf($query, $row[chat_id], $row[sender_id], $row[receiver_id], $row[content], $row['time'], 'Read', $row[mime]);
				mysql_query($query, $con);
				
				
				//Delete read in chat table
				$query = "DELETE FROM " . $sql_database_name . ".chat WHERE chat_id = %s LIMIT 1";
				$query = sprintf($query, $row[chat_id]);
				mysql_query($query, $con);
				
				$i++;
				$chat .=  $chatx;
			}
			$chat .=  "}";
		}
		else
		{
			$chat .= "0}";
		}
		echo $chat;//Return JSON
	}
	
	//scan for new messages
	if(isset($_GET[scan]))
	{
		$id = "{a0";
		$con = mysql_connect($sql_server, $sql_username, $sql_password);
		$query = "SELECT sender_id FROM " . $sql_database_name . ".chat WHERE receiver_id = %s AND status = 'Not Read' GROUP BY chat.sender_id ORDER BY chat.chat_id DESC";
		$query = sprintf($query, mysql_real_escape_string($_GET[uid]));
		$result = mysql_query($query, $con);
		if(mysql_num_rows($result) > 0)
		{
			$id .= ":" . mysql_num_rows($result);
			$i=1;
			while($row = mysql_fetch_assoc($result))
			{
				//Finding the display name
				$query = "SELECT displayname FROM " . $sql_database_name . ".reg_info WHERE id=%s";
				$query = sprintf($query, $row[sender_id]);
				$resultx = mysql_query($query, $con);
				$rowa = mysql_fetch_assoc($resultx);
				
				
				$x = "{'id':'%s','displayname':'%s'}";
				$x = sprintf($x, $row[sender_id], $rowa[displayname]);
				$id .= ",a" . $i . ":" . $x;
				$i++;
			}
			$id .= "}";
		}
		else
		{
			$id .= ":0}";
		}
		echo $id;
	}
	
	//Retrieve old messages from archive
	if(isset($_GET[archive]))
	{
		$chat = "{'a0':";
		$con = mysql_connect($sql_server, $sql_username, $sql_password);
		$query = "SELECT * FROM " . $sql_database_name . ".archive WHERE receiver_id =%s AND sender_id = '%s' %s ORDER BY archive.chat_id DESC LIMIT 10";
		if($_GET[prev_index] != 0) $index = "AND chat_id < " . $_GET[prev_index]; 
		$query = sprintf($query, mysql_real_escape_string($_GET[uid]), mysql_real_escape_string($_GET[oid]), $index);
		$result = mysql_query($query, $con);
		if(mysql_num_rows($result) > 0)
		{
			$chat .= mysql_num_rows($result);
			$i = 1;
			while($row = mysql_fetch_assoc($result))
			{
				$chat .=  ",'a" . $i . "':";
				$chatx = "{'id':'%s','displayname':'%s','content':'%s','time':'%s'}";
				
				//Finding the display name
				$query = "SELECT displayname FROM " . $sql_database_name . ".reg_info WHERE id=%s";
				$query = sprintf($query, $row[sender_id]);
				$result = mysql_query($query, $con);
				$rowa = mysql_fetch_assoc($result);
				
				$chatx = sprintf($chatx, $row[sender_id], $rowa[displayname], $row[content], date('l dS \o\f F Y h:i:s A', strtotime($row[time])));
				$query = "UPDATE " . $sql_database_name . ".chat SET status = 'Read' WHERE chat_id = " . $row[chat_id];//Mark as Read
				mysql_query($query, $con);
				
				//Move read to archive
				$query = "INSERT INTO " . $sql_database_name . ".archive(chat_id, sender_id, receiver_id, content, time, status, mime) VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s')";
				$query = sprintf($query, $row[chat_id], $row[sender_id], $row[receiver_id], $row[content], $row['time'], $row[status], $row[mime]);
				mysql_query($query, $con);
				
				
				//Delete read in chat table
				$query = "DELETE FROM " . $sql_database_name . ".chat WHERE chat_id = %s LIMIT 1";
				$query = sprintf($query, $row[chat_id]);
				mysql_query($query, $con);
				
				$i++;
				$chat .=  $chatx;
			}
			$chat .=  "}";
		}
		else
		{
			$chat .= "0}";
		}
		echo $chat;//Return JSON
	}
?>