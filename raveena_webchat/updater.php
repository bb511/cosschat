<?php
	include('ext.inc');
?>

<?php
	if(isset($_GET['uid']))
	{
		$con = mysql_connect($sql_server, $sql_username, $sql_password);
		$now = date('Y-m-d h:i:s', strtotime('now'));
		$query = "UPDATE " . $sql_database_name . ".online SET time = '%s' WHERE id = '%s'";
		$query = sprintf($query, $now, mysql_real_escape_string(stripslashes($_GET['uid'])));
		mysql_query($query, $con);
	}
	
	if(isset($_GET['online']))
	{
		$resp = "";
		$con = mysql_connect($sql_server, $sql_username, $sql_password);
		$query = "SELECT * FROM " . $sql_database_name . ".online";
		$result = mysql_query($query, $con);
		if(mysql_num_rows($result) > 0)
		{
			while($row = mysql_fetch_assoc($result)) 
			{
				//If last seen is less than 1 mins, user is assumed online
				if((abs((strtotime($row['time'])-strtotime(date('Y-m-d h:i:s', strtotime("now"))))) < 60) && ($row['id'] != stripslashes($_GET['uid'])))
				{
					$x = " - <a href=\"javascript:addNew('%s','%s');\" style='text-decoration:none;' title='Chat with %s'>%s</a>";
					
					$query = "SELECT * FROM ". $sql_database_name .".reg_info WHERE id='%s'";
					$query = sprintf($query, $row[id]);
					$resultr = mysql_query($query, $con);
					$rowr = mysql_fetch_assoc($resultr);
					
					$x = sprintf($x, $rowr[id], $rowr[displayname], $rowr[displayname], $rowr[displayname]);
					$resp .= $x;
				}
			}
		}
		echo $resp;
	}
?>