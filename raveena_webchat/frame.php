<?php
	session_start();
	include('ext.inc');
?>

<html>
	<head>
		<title>A Simple Webchat</title>
		<link href="df.css" media=all type="text/css" rel="stylesheet" />
	</head>
	
	<body>
		<div id='chatPan'></div>
		
		<script>
			window.addEventListener('message', receiver, false);
			function receiver(e)
			{
				if(e.origin == '<?php echo $server_url; ?>')
				{
					document.getElementById("chatPan").innerHTML += e.data + "<br>";
					window.scrollBy(0, document.body.offsetHeight);//force page to scroll to the bottom
				}
			}
		</script>
		
		<script>
			//Function to query server for new messages
			function pingServer()
			{
				j = new XMLHttpRequest();
				urlj = "backend.php?ping&uid=" + <?php echo $_SESSION['uid']; ?> + "&oid=" + <?php echo $_GET[oid]; ?>;
				j.open("POST", urlj, true);
				j.onreadystatechange=function()
							{
								if(j.readyState==4 && j.status==200)
								{
									var er = eval("(" + j.responseText + ")");//Receive JSON; format {'a0':2,'a1':{'id':'234','displayname':'a-friend','content':'Hello world','time':'2013-03-15 10:16:57'},'a2':{'id':'567','displayname':'b-friend','content':'Hello world again','time':'2013-03-15 11:16:57'}}
									counter = er.a0;
									while(counter > 0)
									{
										x = er['a' + counter];
										parent.revMsg(x.id, x.displayname, x.content, x.time, 1);
										counter--;
									}
								}
							}
				j.send();
			}
			//query server for msg every 5sec
			setInterval(pingServer, 5000);
		</script>
	</body>
</html>