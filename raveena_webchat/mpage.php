<?php
	session_start();
	include('ext.inc');
?>

<!DOCTYPE html>
	<head>
		<title>A simple Webchat - Chat Platform</title>
		<link href="df.css" media=all type="text/css" rel="stylesheet" />

	<body>
	<div style="float:right"><a href="reg.php?logout">log out</a></div>
		<input type=hidden id=visible>
		<div class="onlineuser" style= "float:left">
			<h1>welcome, <?php echo $_SESSION['username']; ?></h1>
			Other Online Users
			<span id=onlinex></span>
		</div>
		<?php
			if(isset($_SESSION['uid']))
			{
				echo "<input type=hidden id=uid value=" . $_SESSION['uid'] . ">";
			}
			else
			{
				echo "<script>window.location='index.php';</script>";
			}
		?>
		
		<div id=anchor></div>
		
		<script>
		//send msg to server
			function sendMsg(id)
			{
				msg = document.getElementById("chatInput" + id).value;
				i = new XMLHttpRequest();
				urli = "backend.php?send&uid=" + document.getElementById('uid').value + "&oid=" + id + "&msg=" + msg;
				i.open("POST", urli, true);
				i.onreadystatechange=function()
							{
								if(i.readyState==4 && i.status==200)
								{
									rex = eval("(" + i.responseText + ")");
									revMsg(rex.id, rex.displayname, rex.content, rex.time);
									document.getElementById("chatInput" + id).value = "";
								}
							}
				i.send();
			}
			
			function revMsg(oid, displayname, content, time)
			{
				o = document.getElementById('iPan' + oid);
				sty = "background:#aabb00; width:95%; float:right; margin-top:3px;";
				if(arguments[4] == 1)sty = "background:#ffcc00; width:95%; float:left; margin-top:3px;";
				details = "<div style='" + sty + "'>" + displayname + "<br>" + content + "<br><i>" + time + "</i></div>";
				if(o == null)
				{//i.e. new chat
					addNew(oid, displayname);
					return;
				}
				o.contentWindow.document.getElementById("chatPan");
				o.contentWindow.postMessage(details,'<?php echo $iframe_url ; ?>');
			}
			
			//Scans for new messages
			function scanner()
			{
				x = new XMLHttpRequest();
				urlx = "backend.php?scan&uid=" + <?php echo $_SESSION['uid']; ?>;
				x.open("POST", urlx, true);
				x.onreadystatechange=function()
							{
								if(x.readyState==4 && x.status==200)
								{
									erx = eval( "(" + x.responseText + ")" ); //JSON format {'a0':2, 'a1':'{'id':'2','displayname':'Whalleh'}', 'a2':'{'id':'23','displayname':'Osofem'}'}
									counter = erx.a0;
									while(counter > 0)
									{
										r = erx['a' + counter];
										addNew(r.id, r.displayname);
										counter--;
									}
								}
							}
				x.send();
			}
			setInterval(scanner, 5000);
		</script>
	
		
		<script>
			//Adds a new chat plate
			function addNew(oid)
			{
				if(!document.getElementById("iPan" + oid ))
				{
					var r = document.createElement('div');
					//For iframe
					r.id = "c" + oid;
					r.className = "iFrmPad";
					r.draggable = "true";
					r.innerHTML =	"<div style='background:#0000ff; height:20px; font-size:20px; padding:3px; color:#ffffff'>" + arguments[1] + " </div>";
					r.innerHTML += "<iframe class='iFrm' src='<?php echo $iframe_url; ?>?oid=" + oid + "' id='iPan" + oid + "' style='border:0'></iframe>";
					r.innerHTML += "<input class=input placeholder='Gotta say that' type=text id='chatInput" + oid + "'>";
					r.innerHTML += "<input title='Say It' type=button value='Go' onclick=sendMsg('" + oid + "'); >";
		
					anchor = document.getElementById("anchor");
					document.body.insertBefore(r, anchor);
				}
			}
		</script>
		<script src="js/online.js"></script>
	</body>
</html>