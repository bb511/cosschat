function update()
{
	update = new XMLHttpRequest();
	urlx = "updater.php?uid=" + document.getElementById('uid').value;
	update.open("POST", urlx, true);
	update.onreadystatechange=function()
	{
		if(update.readyState == 4 && update.status == 200)
		{
			//ok
		}
	}
	update.send();
}

function onlineusers()
{
	online = new XMLHttpRequest();
	urly = "updater.php?online&uid=" + document.getElementById('uid').value;
	online.open("POST", urly, true);
	online.onreadystatechange=function()
	{
		if(online.readyState == 4 && online.status == 200)
		{
			document.getElementById('onlinex').innerHTML = online.responseText;
		}
	}
	online.send();

}

onlineusers();
setInterval(onlineusers, 5000);
setInterval(update, 5000);
